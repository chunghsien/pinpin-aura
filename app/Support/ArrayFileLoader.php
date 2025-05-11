<?php

namespace App\Support;

class ArrayFileLoader
{
    protected static array $cache = [];

    public static function load(string $realPath): array
    {
        if (preg_match('/^packages\//', $realPath)) {
            throw new \RuntimeException("僅適用於packages內");
        }
        if (!isset(self::$cache[$realPath])) {
            if (!file_exists($realPath)) {
                throw new \RuntimeException("Config file not found: {$realPath}");
            }

            $data = require $realPath;
            if (!is_array($data)) {
                throw new \UnexpectedValueException("file must return array: {$realPath}");
            }

            self::$cache[$realPath] = $data;
        }

        return self::$cache[$realPath];
    }
}
