<?php

namespace App\Services;

use App\Repositories\ThemeRepositoryInterface;

class ThemeService
{
    protected $theme;

    public function __construct(ThemeRepositoryInterface $repository)
    {
        $this->theme = $repository->getActiveSiteTheme();
    }

    public function getLayout(): string
    {
        return $this->theme ? $this->theme->slug . "::layouts.app" : 'default::layouts.app';
    }

    public function getTheme()
    {
        return $this->theme;
    }
}
