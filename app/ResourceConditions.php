<?php

namespace App;

use App\Exceptions\ResourceConditionsException;
use Illuminate\Http\Resources\DelegatesToResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Allows conditions to be passed to Resources and ResourceCollections
 *
 * Define `$conditions` in the resource/collection class and then call the name
 * of the conditions.
 *
 * Example:
 * <code>
 * class AnimalResource extends JsonResource {
 *     use ResourceConditions;
 *     protected $conditions = ['listing'];
 * }
 * </code>
 *
 * <code>
 * new AnimalResource($animal)->listing();
 * </code>
 */
trait ResourceConditions
{
    use DelegatesToResource {
        __get as resourceGet;
        __call as resourceCall;
    }

    public array $enabledConditions = [];

    public function __call($name, $arguments)
    {
        in_array($name, $this->conditions)
            ? array_push($this->enabledConditions, $name)
            : $this->resourceCall($name, $arguments);

        return $this;
    }

    public function __get($name)
    {
        return in_array($name, $this->conditions)
            ? in_array($name, $this->enabledConditions)
            : $this->resourceGet($name);
    }

    /**
     * @throws ResourceConditionsException
     */
    public function injectConditions(): void
    {
        if (! is_subclass_of($this, ResourceCollection::class)) {
            throw new ResourceConditionsException(sprintf(
                "Class %s does not use %s, can't call injectConditions()",
                get_class($this), ResourceCollection::class
            ));
        }

        $this->collection->each(function ($resource) {
            if (! is_subclass_of($this, ResourceCollection::class)) {
                throw new ResourceConditionsException(sprintf(
                    "Class %s does not use %s, can't call injectConditions()",
                    get_class($resource), ResourceCollection::class
                ));
            }

            $resource->enabledConditions = $this->enabledConditions;
        });
    }
}
