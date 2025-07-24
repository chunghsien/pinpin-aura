<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MenuType;
use App\Models\Menu;
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
        $headerMenus = Menu::getHierarchicalMenu(MenuType::HEADER);
        $viewModel = [
            'headerViewModel' => [
                'properties' => [
                    'installedTheme' => $installedThemeModel,
                    'header' => $headerStyleModel,
                    'menus' => $headerMenus,
                ],
            ],
            'footerViewModel' => [
                'component' => $footerStyleModel->slug,
                'properties' => [
                    'installedTheme' => $installedThemeModel,
                    'footer' => $footerStyleModel,
                ],
            ],

        ];

        return $viewModel;
    }
}
