<?php

namespace App\Models\Traits;

trait Activate
{

    /**
     * @desc 取得目前啟用中的主題
     */
    public static function active()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * @desc 啟用指定主題（自動取消其他主題）
     */
    public function activate(): void
    {
        self::where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);

        // 啟用這個主題（如果尚未啟用）
        if (!$this->is_active) {
            $this->is_active = true;
            $this->save();
        }
    }
}
