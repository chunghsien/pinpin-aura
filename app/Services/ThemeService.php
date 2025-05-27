<?php

namespace App\Services;

use App\Repositories\ThemeRepositoryInterface;

class ThemeService
{
    protected $theme;

    public function __construct(protected ThemeRepositoryInterface $repository)
    {
        //
    }

    public function getLayout($useType = 'web'): string
    {
        $this->setTheme($useType);
        return $this->theme ? $this->theme->slug . "::layouts.app" : 'layouts.app';
    }

    public function getTheme($useType = 'web')
    {
        $this->setTheme($useType);
        return $this->theme;
    }

    protected function setTheme($useType)
    {
        if (!is_string($this->theme)) {
            $this->theme = $this->repository->getActiveSiteTheme($useType);
        }
    }
}
