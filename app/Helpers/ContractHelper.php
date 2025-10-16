<?php

use Illuminate\Support\Str;

if (!function_exists('normalize')) {
    function normalize(string $value): string {
        return Str::slug($value, '_');
    }
}
