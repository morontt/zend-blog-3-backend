<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 07.04.2026
 * Time: 11:27
 */

namespace App\Utils;

function base64url_decode(string $value): string|false
{
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $value), true);
}

function base64url_encode(string $value): string
{
    return str_replace(['+', '/'], ['-', '_'], base64_encode($value));
}
