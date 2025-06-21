<?php

namespace App\Enums;

enum LinkType: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';

    /**
     * 取得所有值的陣列
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * 取得選項陣列（用於表單選擇）
     */
    public static function options(): array
    {
        return [
            self::INTERNAL->value => '內部連結',
            self::EXTERNAL->value => '外部連結',
        ];
    }

    /**
     * 取得顯示名稱
     */
    public function label(): string
    {
        return match ($this) {
            self::INTERNAL => '內部連結',
            self::EXTERNAL => '外部連結',
        };
    }

    /**
     * 取得圖示
     */
    public function icon(): string
    {
        return match ($this) {
            self::INTERNAL => 'fas fa-link',
            self::EXTERNAL => 'fas fa-external-link-alt',
        };
    }

    /**
     * 取得描述
     */
    public function description(): string
    {
        return match ($this) {
            self::INTERNAL => '指向網站內部的頁面或路由',
            self::EXTERNAL => '指向外部網站的連結',
        };
    }

    /**
     * 取得 CSS 類別
     */
    public function cssClass(): string
    {
        return match ($this) {
            self::INTERNAL => 'internal-link',
            self::EXTERNAL => 'external-link',
        };
    }

    /**
     * 檢查是否需要新視窗開啟
     */
    public function shouldOpenNewTab(): bool
    {
        return $this === self::EXTERNAL;
    }

    /**
     * 取得預設的 target 屬性
     */
    public function defaultTarget(): string
    {
        return match ($this) {
            self::INTERNAL => '_self',
            self::EXTERNAL => '_blank',
        };
    }

    /**
     * 驗證 URL 格式
     */
    public function validateUrl(string $url): bool
    {
        return match ($this) {
            self::INTERNAL => $this->validateInternalUrl($url),
            self::EXTERNAL => $this->validateExternalUrl($url),
        };
    }

    /**
     * 驗證內部 URL
     */
    private function validateInternalUrl(string $url): bool
    {
        // 允許相對路徑、絕對路徑或路由名稱
        return !empty($url) && (
            str_starts_with($url, '/') ||
            str_starts_with($url, '#') ||
            !str_contains($url, '://') // 不包含協議的視為內部連結
        );
    }

    /**
     * 驗證外部 URL
     */
    private function validateExternalUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * 格式化 URL
     */
    public function formatUrl(string $url): string
    {
        return match ($this) {
            self::INTERNAL => $this->formatInternalUrl($url),
            self::EXTERNAL => $this->formatExternalUrl($url),
        };
    }

    /**
     * 格式化內部 URL
     */
    private function formatInternalUrl(string $url): string
    {
        // 如果是路由名稱，嘗試生成 URL
        if (!str_starts_with($url, '/') && !str_starts_with($url, '#')) {
            try {
                return route($url);
            } catch (\Exception $e) {
                return $url;
            }
        }

        return $url;
    }

    /**
     * 格式化外部 URL
     */
    private function formatExternalUrl(string $url): string
    {
        // 如果沒有協議，添加 https://
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return 'https://' . $url;
        }

        return $url;
    }
}
