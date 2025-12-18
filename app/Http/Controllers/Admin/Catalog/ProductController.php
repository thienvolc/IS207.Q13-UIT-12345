<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Domains\Catalog\DTOs\Product\Commands\CreateProductDTO;
use App\Domains\Catalog\DTOs\Product\Commands\UpdateProductDTO;
use App\Domains\Catalog\DTOs\Product\Queries\AdminSearchProductsDTO;
use App\Domains\Catalog\Entities\Product;
use App\Domains\Catalog\Services\CategoryService;
use App\Domains\Catalog\Services\ProductManageService;
use App\Domains\Catalog\Services\ProductReadService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductReadService $productReadService,
        private readonly ProductManageService $productManageService,
        private readonly CategoryService $categoryService,
    ) {
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        // Build search DTO from request
        $searchDTO = new AdminSearchProductsDTO(
            query: $request->get('q'),
            categoryIdOrSlug: $request->get('category') ? (int) $request->get('category') : null,
            tagId: $request->get('tag') ? (int) $request->get('tag') : null,
            priceMin: $request->get('price_min') ? (float) $request->get('price_min') : null,
            priceMax: $request->get('price_max') ? (float) $request->get('price_max') : null,
            page: $request->get('page', 1),
            size: $request->get('size', 20),
            sortField: $request->get('sort', 'created_at'),
            sortOrder: $request->get('order', 'desc'),
        );

        // Use service to search products
        $productsPage = $this->productReadService->search($searchDTO);

        // Get categories for filters
        $categories = $this->categoryService->getAllPublic();

        // Calculate stats
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 1)->count();
        // Out of stock: Status = 2 OR Quantity = 0
        $outOfStockProducts = Product::query()
            ->where(function ($q) {
                $q->where('status', 2)->orWhere('quantity', 0);
            })->count();
        $lowStockProducts = Product::where('quantity', '>', 0)->where('quantity', '<', 10)->count();

        return view('admin.products.index', [
            'products' => $productsPage->data,
            'categories' => $categories,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'lowStockProducts' => $lowStockProducts,
            'pagination' => [
                'current' => $productsPage->page,
                'total' => ceil($productsPage->total / $productsPage->size),
                'totalItems' => $productsPage->total,
                'perPage' => $productsPage->size,
            ],
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = $this->categoryService->getAllPublic();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:500',
            'desc' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
            'type' => 'nullable|string',
            'thumb' => 'nullable|string|max:500',
            'sku' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
        ]);

        // Generate SKU if not provided
        $sku = $validated['sku'] ?? 'SKU-' . strtoupper(uniqid());

        $metas = $request->input('metas', []);
        $formattedMetas = [];
        if (is_array($metas)) {
            foreach ($metas as $meta) {
                if (!empty($meta['key'])) {
                    $formattedMetas[] = [
                        'key' => $meta['key'],
                        'content' => $meta['value'] ?? $meta['content'] ?? null,
                    ];
                }
            }
        }

        // Build DTO from validated data (matching exact DTO signature)
        $createDTO = new CreateProductDTO(
            title: $validated['title'],
            meta_title: $validated['meta_title'] ?? null,
            slug: $validated['slug'] ?? null,
            thumb: $validated['thumb'] ?? null,
            desc: $validated['desc'] ?? null,
            summary: $validated['summary'] ?? null,
            type: $validated['type'] ?? null,
            sku: $sku,
            price: (float) $validated['price'],
            discount: isset($validated['discount']) ? (int) $validated['discount'] : null,
            quantity: (int) $validated['quantity'],
            status: (int) $validated['status'],
            starts_at: $request->get('starts_at'),
            ends_at: $request->get('ends_at'),
            metas: $formattedMetas,
        );

        // Use service to create product
        $product = $this->productManageService->create($createDTO);

        // Handle categories if provided
        if ($request->has('categories') && !empty($request->categories)) {
            // Categories will be handled by the service if we add category assignment
        }

        if ($request->action === 'save_and_new') {
            return redirect()
                ->route('admin.products.create')
                ->with('success', 'Sản phẩm đã được tạo thành công!');
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = $this->productReadService->getById((int) $id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = $this->productReadService->getById((int) $id);
        $categories = $this->categoryService->getAllPublic();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:500',
            'desc' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'nullable|integer|min:0',
            'status' => 'required|in:1,2,3,4,5',
            'type' => 'nullable|string',
            'thumb' => 'nullable|string|max:500',
            'sku' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
        ]);

        // Re-map metas for update BEFORE instantiation
        $metas = $request->input('metas', []);
        $formattedMetas = [];
        if (is_array($metas)) {
            foreach ($metas as $meta) {
                if (!empty($meta['key'])) {
                    $formattedMetas[] = [
                        'key' => $meta['key'],
                        'content' => $meta['value'] ?? $meta['content'] ?? null,
                    ];
                }
            }
        }

        // Build DTO from validated data (matching exact DTO signature)
        $updateDTO = new UpdateProductDTO(
            productId: (int) $id,
            title: $validated['title'],
            metaTitle: $validated['meta_title'] ?? null,
            slug: $validated['slug'] ?? null,
            thumb: $validated['thumb'] ?? null,
            desc: $validated['desc'] ?? null,
            summary: $validated['summary'] ?? null,
            type: $validated['type'] ?? null,
            sku: $validated['sku'] ?? null,
            price: (float) $validated['price'],
            discount: isset($validated['discount']) ? (float) $validated['discount'] : null,
            status: (int) $validated['status'],
            startsAt: $request->get('starts_at'),
            endsAt: $request->get('ends_at'),
            metas: $formattedMetas,
            categories: $request->input('categories', []),
        );

        // Use service to update product
        $product = $this->productManageService->update($updateDTO);

        if ($request->action === 'save_and_continue') {
            return redirect()
                ->route('admin.products.edit', $id)
                ->with('success', 'Sản phẩm đã được cập nhật!');
        }

        return redirect()
            ->route('admin.products.edit', $id)
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $this->productManageService->delete((int) $id);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa!');
    }
}
