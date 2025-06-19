<?php

namespace App\Repositories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRepository implements Contracts\CurrencyRepositoryInterface
{
    /**
     * 取得所有幣別
     *
     * @return Collection|Currency[]
     */
    public function all(): Collection
    {
        return Currency::all();
    }

    /**
     * 根據 ID 查詢幣別
     */
    public function find(int $id): ?Currency
    {
        return Currency::find($id);
    }

    /**
     * 根據 code 查詢幣別
     */
    public function findByCode(string $code): ?Currency
    {
        return Currency::where('code', $code)->first();
    }

    /**
     * 建立新幣別
     */
    public function create(array $data): Currency
    {
        return Currency::create($data);
    }

    /**
     * 更新幣別
     */
    public function update(Currency $currency, array $data): bool
    {
        return $currency->update($data);
    }

    /**
     * 刪除幣別
     */
    public function delete(Currency $currency): bool
    {
        return $currency->delete();
    }
}
