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

// ============= ARRAY-SAFE HELPERS =============
// Automatically handle missing array keys without ?? null

function get_int(?array $array, string $key): ?int
{
    return isset($array[$key]) ? (int)$array[$key] : null;
}

function get_float(?array $array, string $key): ?float
{
    return isset($array[$key]) ? (float)$array[$key] : null;
}

function get_string(?array $array, string $key): ?string
{
    return isset($array[$key]) ? (string)$array[$key] : null;
}

function get_bool(?array $array, string $key): ?bool
{
    return isset($array[$key]) ? (bool)$array[$key] : null;
}

function get_array(?array $array, string $key): ?array
{
    return isset($array[$key]) ? (array)$array[$key] : null;
}

function get_datetime(?array $array, string $key): ?string
{
    return isset($array[$key])
        ? Carbon::parse($array[$key])->toDateTimeString()
        : null;
}
