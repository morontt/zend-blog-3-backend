<?php

namespace App\DTO;

use ArrayAccess;
use RuntimeException;

/**
 * @implements ArrayAccess<string, mixed>
 */
abstract class BaseObject implements ArrayAccess
{
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    public function offsetGet($offset): mixed
    {
        if (!property_exists($this, $offset)) {
            throw new RuntimeException("Illegal property \"{$offset}\" of \\" . static::class);
        }

        return $this->{$offset};
    }

    public function offsetSet($offset, $value): void
    {
        if (!property_exists($this, $offset)) {
            throw new RuntimeException("Illegal property \"{$offset}\" of \\" . static::class);
        }

        $this->{$offset} = $value;
    }

    public function offsetUnset($offset): void
    {
        if (!property_exists($this, $offset)) {
            throw new RuntimeException("Illegal property \"{$offset}\" of \\" . static::class);
        }

        $this->{$offset} = null;
    }
}
