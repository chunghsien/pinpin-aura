<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingRepository implements SettingRepositoryInterface
{
    protected string $cachePrefix = 'settings.';

    public function get(string $key, $default = null): mixed
    {
        return Cache::rememberForever($this->cachePrefix . $key, function () use ($key, $default) {
            $value = Setting::get($key, $default);
            return $this->maybeDecode($value);
        });
    }

    public function set(string $key, $value): void
    {
        Setting::set($key, $this->maybeEncode($value));
        Cache::forget($this->cachePrefix . $key);
    }

    protected function maybeEncode(mixed $value): string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return (string) $value;
    }

    protected function maybeDecode(?string $value): mixed
    {
        if (is_string($value) && $this->isJson($value)) {
            return json_decode($value, true);
        }
        return $value;
    }

    protected function isJson(string $value): bool
    {
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
