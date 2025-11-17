<?php

namespace App\Domains\Catalog\Repositories;

use App\Domains\Catalog\Entities\ProductMeta;
use App\Domains\Common\Constants\ResponseCode;
use App\Exceptions\BusinessException;

class ProductMetaRepository
{
    public function create(array $data): ProductMeta
    {
        return ProductMeta::create($data);
    }

    public function getByIdAndProductOrFail(int $metaId, int $productId): ?ProductMeta
    {
        return ProductMeta::where('meta_id', $metaId)
            ->where('product_id', $productId)
            ->firstOr(fn() => throw new BusinessException(ResponseCode::NOT_FOUND));
    }
}
