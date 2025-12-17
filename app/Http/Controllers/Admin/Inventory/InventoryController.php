<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Domains\Catalog\DTOs\Product\Queries\AdminSearchProductsDTO;
use App\Domains\Catalog\Services\ProductReadService;
use App\Domains\Inventory\Constants\StockOperationType;
use App\Domains\Inventory\DTOs\Commands\AdjustInventoryDTO;
use App\Domains\Inventory\Services\ProductStockService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        private readonly ProductReadService $productReadService,
        private readonly ProductStockService $productStockService,
    ) {
    }

    /**
     * Display inventory list.
     */
    public function index(Request $request)
    {
        // Build search DTO from request
        $searchDTO = new AdminSearchProductsDTO(
            query: $request->get('q'),
            categoryIdOrSlug: $request->get('category') ? (int) $request->get('category') : null,
            tagId: null,
            priceMin: null,
            priceMax: null,
            page: $request->get('page', 1),
            size: $request->get('size', 20),
            sortField: $request->get('sort', 'quantity'),
            sortOrder: $request->get('order', 'asc'),
        );

        // Get products with stock info
        $productsPage = $this->productReadService->search($searchDTO);

        // Get low stock count (quantity < 10)
        $lowStockCount = collect($productsPage->data)->filter(fn($p) => $p->quantity < 10)->count();

        // Get out of stock count
        $outOfStockCount = collect($productsPage->data)->filter(fn($p) => $p->quantity <= 0)->count();

        return view('admin.inventory.index', [
            'products' => $productsPage->data,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'pagination' => [
                'current' => $productsPage->page,
                'total' => ceil($productsPage->total / $productsPage->size),
                'totalItems' => $productsPage->total,
                'perPage' => $productsPage->size,
            ],
        ]);
    }

    /**
     * Adjust stock for a product.
     */
    public function adjust(Request $request, int $id)
    {
        $validated = $request->validate([
            'operation' => 'required|in:increase,decrease',
            'amount' => 'required|integer|min:1',
        ]);

        $operationType = $validated['operation'] === 'increase'
            ? StockOperationType::INCREASE
            : StockOperationType::DECREASE;

        $dto = new AdjustInventoryDTO(
            productId: $id,
            amount: (int) $validated['amount'],
            operationType: $operationType,
            reason: 'Admin adjustment',
        );

        try {
            $this->productStockService->adjustInventory($dto);
            return redirect()
                ->route('admin.inventory.index')
                ->with('success', 'Đã cập nhật tồn kho thành công!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.inventory.index')
                ->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
