<?php

namespace App\Api\Factory;

class GenericFactory
{
    public function create(string $class)
    {
        if (!is_string($class) || !class_exists($class)) {
            throw new \InvalidArgumentException('Expected $entityClassName to be a valid class name.');
        }

        return new $class();
    }
}
