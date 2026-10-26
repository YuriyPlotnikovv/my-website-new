<?php

namespace core;

class Env
{
    public static function get(string $name, string $default = ''): string
    {
        $value = $_ENV[$name] ?? $_SERVER[$name] ?? null;

        return ($value === null || $value === '') ? $default : (string) $value;
    }
}
