<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class DtrTypeCast implements CastsAttributes
{
    // DB -> PHP
    public function get($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ((int) $value) {
            1 => 'Time In',
            2 => 'Time Out',
            default => (string) $value,
        };
    }

    // PHP -> DB
    public function set($model, string $key, $value, array $attributes): ?int
    {
        if ($value === null) {
            return null;
        }

        // allow saving numeric values directly
        if (is_numeric($value)) {
            $int = (int) $value;
            if (! in_array($int, [1, 2], true)) {
                throw new InvalidArgumentException("Invalid DTR type number: {$value}");
            }
            return $int;
        }

        // allow saving labels too
        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            'time in', 'in' => 1,
            'time out', 'out' => 2,
            default => throw new InvalidArgumentException("Invalid DTR type label: {$value}"),
        };
    }
}