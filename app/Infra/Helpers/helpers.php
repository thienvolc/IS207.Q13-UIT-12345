<?php

use Illuminate\Support\Carbon;

function int_or_null($value): ?int
{
    return is_null($value) ? null : (int)$value;
}

function float_or_null($value): ?float
{
    return is_null($value) ? null : (float)$value;
}

function string_or_null($value): ?string
{
    return is_null($value) ? null : (string)$value;
}

function bool_or_null($value): ?bool
{
    return is_null($value) ? null : (bool)$value;
}

function array_or_null($value): ?array
{
    return is_null($value) ? null : (array)$value;
}

function datetime_or_null($value): ?string
{
    return is_null($value)
        ? null
        : Carbon::parse($value)->toDateTimeString();
}
