<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Models\HeaderFooterStyle;
use App\Models\SiteStyle;
use App\Repositories\Contracts\ThemeRepositoryInterface;
use Illuminate\Support\Str;

class SiteHeaderViewModel
{
    public function __construct(
        protected string $useType = 'web',
        protected ThemeRepositoryInterface $themeRepository
    ) {
        //
    }

    public function getHeaderComponentSlug(): ?string
    {
        $styleId = SiteStyle::where('use_type', $this->useType)->value('header_style_id');
        if (! $styleId) {
            return NULL;
        }

        return HeaderFooterStyle::where('id', $styleId)
            ->where('type', 'header')
            ->value('slug');
    }

    public function getHeaderComponentName(): ?string
    {
        $slug = $this->getHeaderComponentSlug();
        $theme = $this->themeRepository->getActiveSiteTheme($this->useType);

        return $slug ? "{$theme->slug}::headers." . Str::kebab($slug) : NULL;
    }

    // 若未來擴充多語語系/區塊，可在此補上更多方法
}
