<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Domains\Catalog\DTOs\Category\Commands\CreateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Commands\UpdateCategoryDTO;
use App\Domains\Catalog\DTOs\Category\Queries\AdminSearchCategoriesDTO;
use App\Domains\Catalog\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService,
    )
    {
    }

    /**
     * [GET] /admin/categories
     */
    public function index(Request $request)
    {
        $searchDTO = new AdminSearchCategoriesDTO(
            query: $request->get('q'),
            level: $request->get('level') ? (int)$request->get('level') : null,
            page: $request->get('page', 1),
            size: $request->get('size', 20),
            sortField: $request->get('sort', 'created_at'),
            sortOrder: $request->get('order', 'desc'),
        );

        $categoriesPage = $this->categoryService->search($searchDTO);
        $allCategories = $this->categoryService->getAllPublic();

        return view('admin.categories.index', [
            'categories' => $categoriesPage->data,
            'allCategories' => $allCategories,
            'pagination' => [
                'current' => $categoriesPage->page,
                'total' => ceil($categoriesPage->total / $categoriesPage->size),
                'totalItems' => $categoriesPage->total,
                'perPage' => $categoriesPage->size,
            ],
        ]);
    }

    /**
     * [GET] /admin/categories/create
     */
    public function create()
    {
        $allCategories = $this->categoryService->getAllPublic();
        return view('admin.categories.create', compact('allCategories'));
    }

    /**
     * [GET] /admin/categories/create
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'parent_id' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
        ]);

        // Determine level based on parent
        $level = 0;
        if (!empty($validated['parent_id'])) {
            try {
                $parent = $this->categoryService->getById((int)$validated['parent_id']);
                $level = $parent->level + 1;
            } catch (\Exception $e) {
                $level = 1;
            }
        }

        // Build DTO from validated data
        $createDTO = new CreateCategoryDTO(
            parentId: $validated['parent_id'] ? (int)$validated['parent_id'] : null,
            level: $level,
            title: $validated['title'],
            metaTitle: $validated['meta_title'] ?? null,
            slug: $validated['slug'] ?? null,
            desc: $validated['desc'] ?? null,
        );

        // Use service to create category
        $category = $this->categoryService->create($createDTO);

        if ($request->action === 'save_and_new') {
            return redirect()
                ->route('admin.categories.create')
                ->with('success', 'Danh mục đã được tạo thành công!');
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    /**
     * [GET] /admin/categories/{category}
     */
    public function show($id)
    {
        $category = $this->categoryService->getById((int)$id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * [GET] /admin/categories/{category}/edit
     */
    public function edit($id)
    {
        $category = $this->categoryService->getById((int)$id);
        $allCategories = $this->categoryService->getAllPublic();
        return view('admin.categories.edit', compact('category', 'allCategories'));
    }

    /**
     * [PUT | PATCH] /admin/categories/{category}
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'parent_id' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
        ]);

        $level = 0;
        if (!empty($validated['parent_id'])) {
            try {
                $parent = $this->categoryService->getById((int)$validated['parent_id']);
                $level = $parent->level + 1;
            } catch (\Exception $e) {
                $level = 1;
            }
        }

        $updateDTO = new UpdateCategoryDTO(
            categoryId: (int)$id,
            parentId: $validated['parent_id'] ? (int)$validated['parent_id'] : null,
            level: $level,
            title: $validated['title'],
            metaTitle: $validated['meta_title'] ?? null,
            slug: $validated['slug'] ?? null,
            desc: $validated['desc'] ?? null,
        );

        $category = $this->categoryService->update($updateDTO);

        if ($request->action === 'save_and_continue') {
            return redirect()
                ->route('admin.categories.edit', $id)
                ->with('success', 'Danh mục đã được cập nhật!');
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    /**
     * [DELETE] /admin/categories/{category}
     */
    public function destroy($id)
    {
        $this->categoryService->delete((int)$id);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa!');
    }
}
