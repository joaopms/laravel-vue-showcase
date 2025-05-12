import { CalendarDate, DateFormatter } from '@internationalized/date';
import type { DateRange } from 'reka-ui';

const dateFormatter = new DateFormatter('en-US', { dateStyle: 'medium' });

export function formatDate(date: Date | CalendarDate) {
    return dateFormatter.format(date instanceof CalendarDate ? date.toDate('UTC') : date);
}

export function isSameDate(dates?: DateRange) {
    if (!dates || !dates.start || !dates.end) {
        return false;
    }

    return dates.start.compare(dates.end) === 0;
}
