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
        $headerStyleModel = $siteStyleModel->headerStyle()->firstOrFail();
        $footerStyleModel = $siteStyleModel->footerStyle()->firstOrFail();
        $installedThemeModel = $headerStyleModel->theme()->firstOrFail();
        return [
            'headerViewModel' => [
                'properties' => [
                    'installedTheme' => $installedThemeModel,
                    'header' => $headerStyleModel,
                ]
            ],
            'footerViewModel' => [
                'component' => $footerStyleModel->slug,
                'properties' => [
                    'installedTheme' => $installedThemeModel,
                    'footer' => $footerStyleModel,
                ]
            ]
        ];
    }
}
