<?php

namespace App;

enum TimeOfDay: string
{
    case Morning = 'morning';
    case Afternoon = 'afternoon';
    case AllDay = 'all_day';
}
