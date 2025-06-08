<?php

namespace App\Repositories\Contracts;

interface SettingRepositoryInterface
{
    public function get(string $key, $default = null): mixed;

    public function set(string $key, $value): void;
}
