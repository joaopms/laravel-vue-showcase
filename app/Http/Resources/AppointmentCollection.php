<?php

namespace App\Http\Resources;

use App\Exceptions\ResourceConditionsException;
use App\ResourceConditions;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AppointmentCollection extends ResourceCollection
{
    use ResourceConditions;

    protected array $conditions = ['listing', 'showing'];

    /**
     * @throws ResourceConditionsException
     */
    public function toArray(Request $request): array
    {
        // TODO Only set conditions on the resource itself, the ResourceCollection can just get it from there
        $this->injectConditions();

        return parent::toArray($request);
    }
}
