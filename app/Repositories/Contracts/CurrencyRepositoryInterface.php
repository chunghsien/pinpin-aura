<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

/**
 * 幣別儲存庫介面
 */
interface CurrencyRepositoryInterface
{
    /**
     * 取得所有幣別
     *
     * @return Collection|Currency[]
     */
    public function all();

    /**
     * 依 ID 查詢幣別
     *
     * @param int $id
     * @return Currency|null
     */
    public function find(int $id): ?Currency;

    /**
     * 依 code 查詢幣別
     *
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency;

    /**
     * 建立新幣別
     *
     * @param array $data
     * @return Currency
     */
    public function create(array $data): Currency;

    /**
     * 更新指定幣別
     *
     * @param Currency $model
     * @param array $data
     * @return bool
     */
    public function update(Currency $model, array $data): bool;

    /**
     * 刪除指定幣別
     *
     * @param Currency $model
     * @return bool
     */
    public function delete(Currency $model): bool;
}
