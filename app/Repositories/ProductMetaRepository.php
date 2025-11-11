<?php

namespace App\Repositories;

use App\Models\ProductMeta;

class ProductMetaRepository
{
    public function findByIdAndProductId(int $metaId, int $productId): ?ProductMeta
    {
        return ProductMeta::where('meta_id', $metaId)
            ->where('product_id', $productId)
            ->first();
    }

    public function create(array $data): ProductMeta
    {
        return ProductMeta::create($data);
    }

    public function update(ProductMeta $meta, array $data): bool
    {
        return $meta->update($data);
    }

    public function delete(ProductMeta $meta): bool
    {
        return $meta->delete();
    }
}
