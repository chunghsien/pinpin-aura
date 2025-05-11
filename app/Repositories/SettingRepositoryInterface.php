<?php

namespace App\Repositories;

interface SettingRepositoryInterface
{
    public function get(string $key, $default = null): mixed;

    public function set(string $key, $value): void;
}
