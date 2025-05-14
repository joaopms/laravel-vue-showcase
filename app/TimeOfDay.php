<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

enum TimeOfDay: string
{
    case Morning = 'morning';
    case Afternoon = 'afternoon';
    case AllDay = 'all_day';

    /**
     * Time of day that can be selected by users
     *
     * @return TimeOfDay[]
     */
    public static function selectable(): array
    {
        return [self::Morning, self::Afternoon];
    }

    /**
     * Same as `selectable()` but returns an array of strings
     *
     * @return string[]
     */
    public static function selectableStrings(): array
    {
        return Arr::map(self::selectable(), fn (TimeOfDay $time) => $time->value);
    }

    /**
     * Checks if all possible times of days are selected, meaning a full day is selected
     *
     * @param  TimeOfDay[]  $times
     */
    public static function isAllDay(array $times): bool
    {
        $selectable = array_map(fn (TimeOfDay $time) => $time->value, self::selectable());
        $given = array_map(fn (TimeOfDay $time) => $time->value, $times);

        sort($selectable);
        sort($given);

        return $selectable == $given;
    }

    /**
     * If all possible times are selected, return `AllDay`; if not, return the only result
     *
     * @param  TimeOfDay[]  $times
     */
    private static function allDayOrOneTime(array $times): TimeOfDay
    {
        if (self::isAllDay($times)) {
            return self::AllDay;
        }

        assert(
            count($times) == 1,
            'Got times of day and it was not considered as AllDay. Did you pass an array of TimeOfDay?'
        );

        return $times[0];
    }

    public function format(): string
    {
        return Str::headline($this->value);
    }

    /**
     * @param  string[]  $values
     */
    public static function fromInputData(array $values): TimeOfDay
    {
        $converted = array_map(fn (string $value) => TimeOfDay::from($value), $values);

        return TimeOfDay::allDayOrOneTime($converted);
    }

    public static function toInputData(TimeOfDay $value): array
    {
        if ($value == TimeOfDay::AllDay) {
            return TimeOfDay::selectable();
        }

        return [$value];
    }
}
