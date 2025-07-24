<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

interface SettingRepositoryInterface
{
    public function get(string $key, $default = NULL): mixed;

    public function set(string $key, $value): void;
}
