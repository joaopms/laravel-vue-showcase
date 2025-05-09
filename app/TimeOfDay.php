<?php

namespace App;

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
        return [
            self::Morning,
            self::Afternoon,
        ];
    }

    /**
     * Checks if all possible times of days are selected, meaning a full day is selected
     *
     * @param  TimeOfDay[]  $times
     */
    public static function isAllDay(array $times): bool
    {
        return self::selectable() == $times;
    }

    /**
     * If all possible times are selected, return `AllDay`; if not, return the only result
     *
     * @param  TimeOfDay[]  $times
     */
    public static function allDayOrOneTime(array $times): TimeOfDay
    {
        if (self::isAllDay($times)) {
            return self::AllDay;
        }

        assert(
            count($times) == 1,
            'Got times of day and it was not considered as AllDay'
        );

        return $times[0];
    }
}
