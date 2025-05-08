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
}
