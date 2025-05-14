<?php

namespace App\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JsonSerializable;

// TODO Unit test
readonly class AgeParser implements Arrayable, JsonSerializable
{
    public function __construct(private int $inMonths) {}

    public static function from(int $years, int $months): AgeParser
    {
        return new AgeParser(self::yearsAndMonthsToMonths($years, $months));
    }

    private static function yearsAndMonthsToMonths(int $years, int $months): int
    {
        return $years * 12 + $months;
    }

    public function asMonths(): int
    {
        return $this->inMonths;
    }

    public function years(): int
    {
        return floor($this->inMonths / 12);
    }

    public function months(): int
    {
        return $this->inMonths % 12;
    }

    public function yearsWord(): string
    {
        return Str::plural('year', $this->years());
    }

    public function monthsWord(): string
    {
        return Str::plural('month', $this->months());
    }

    public function yearsWithWord(): ?string
    {
        return $this->years() > 0 ? (Arr::join([$this->years(), $this->yearsWord()], ' ')) : null;
    }

    public function monthsWithWord(): ?string
    {
        return $this->months() > 0 ? (Arr::join([$this->months(), $this->monthsWord()], ' ')) : null;
    }

    public function human(): string
    {
        return Arr::join(
            Arr::whereNotNull([$this->yearsWithWord(), $this->monthsWithWord()]),
            ' and '
        );
    }

    public function toArray(): array
    {
        return [
            'years' => $this->years(),
            'months' => $this->months(),
            'human' => $this->human(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
