<?php

namespace App\Services;

use App\Repositories\Contracts\SiteStyleRepositoryInterface;

class LayoutService
{

    public function __construct(
        protected SiteStyleRepositoryInterface $siteStyleRepository
    ) {
        //
    }

    public function getViewModel(string $useType = 'web'): array
    {
        $siteStyleModel = $this->siteStyleRepository->getByUseType($useType);
        $headerFootStyleModel = $siteStyleModel->headerStyle()->firstOrFail();
        $installedThemeModel = $headerFootStyleModel->theme()->firstOrFail();
        return [
            'headerViewModel' => [
                'component' => $installedThemeModel->slug . '::livewire.headers.widgets.' . $headerFootStyleModel->slug,
                'properties' => [
                    'installedTheme' => $installedThemeModel,
                    'header' => $headerFootStyleModel,
                ]
            ]
        ];
    }
}
