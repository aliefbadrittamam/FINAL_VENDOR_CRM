<?php
namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Catalog extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';

    public $showModal = false;
    public $editingProductId = null;
    public $notification = [
        'show' => false,
        'message' => ''
    ];

    // Mendefinisikan listeners untuk event
    protected $listeners = [
        'closeModal' => 'handleCloseModal',
        'productSaved' => 'handleProductSaved'
    ];

    // Membuka modal dan mengatur ID customer jika dalam mode edit
    public function openModal($productId = null)
    {
        $this->editingProductId = $productId;
        $this->showModal = true;
    }

    // Menutup modal dan membersihkan state
    public function handleCloseModal()
    {
        $this->showModal = false;
        $this->editingProductId = null;
        $this->dispatch('modalClosed');
    }

    // Menangani event setelah customer disimpan
    public function handleProductSaved($message)
    {
        $this->handleCloseModal();
        $this->notification['show'] = true;
        $this->notification['message'] = $message;
        
        // Refresh data
        $this->resetPage();
    }

    // Reset halaman saat pencarian berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset halaman saat filter berubah
    public function updatingFilters()
    {
        $this->resetPage();
    }

    // Menghapus campaign (soft delete)
    public function deleteProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete(); // Soft delete campaign

        // Refresh data dan tampilkan notifikasi
        $this->notification['show'] = true;
        $this->notification['message'] = 'Product deleted successfully.';

        $this->resetPage(); // Reset pagination
    }

    public function render()
    {
        
        $category = Category::all();
        $product = Product::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('product_name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categoryFilter, function($query) {
                $query->where('category_id', '=', $this->categoryFilter);
            })         
            ->orderBy('created_at', 'desc')
            ->with('category')
            ->paginate(10);

        // Return data ke view
        return view('livewire.products.catalog', [
            'products' => $product,
            'categories' => $category,
        ]);
    }
}
