<?php

namespace App\DTO;

use ArrayAccess;
use RuntimeException;

abstract class BaseObject implements ArrayAccess
{
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    public function offsetGet($offset)
    {
        if (!property_exists($this, $offset)) {
            throw new RuntimeException("Illegal property \"{$offset}\" of \\" . static::class);
        }

        return $this->{$offset};
    }

    public function offsetSet($offset, $value)
    {
        if (!property_exists($this, $offset)) {
            throw new RuntimeException("Illegal property \"{$offset}\" of \\" . static::class);
        }

        $this->{$offset} = $value;
    }

    public function offsetUnset($offset)
    {
        if (!property_exists($this, $offset)) {
            throw new RuntimeException("Illegal property \"{$offset}\" of \\" . static::class);
        }

        $this->{$offset} = null;
    }
}
