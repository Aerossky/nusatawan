<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Service untuk mengelola kategori
     *
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * Inisialisasi controller dengan dependency injection
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Menampilkan daftar kategori
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'search'   => $request->query('search'),
            'per_page' => $request->query('per_page', 10),
        ];

        return view('admin.categories.index', [
            'categories' => $this->categoryService->getCategoriesList($filters)
        ]);
    }

    /**
     * Menampilkan form untuk membuat kategori baru
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan kategori baru ke database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        try {
            $category = $this->categoryService->createCategory($validated);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat kategori: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit kategori
     *
     * @param Category $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Memperbarui data kategori yang sudah ada
     *
     * @param Category $category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        try {
            $updatedCategory = $this->categoryService->updateCategory($category, $validated);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus kategori dari database
     *
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->deleteCategory($category);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', $e->getMessage());
        }
    }
}
