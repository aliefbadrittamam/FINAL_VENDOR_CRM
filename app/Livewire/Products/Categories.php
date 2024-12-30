<?php
namespace App\Livewire\Products;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteConfirmation = false;
    public $deleteCategory = null;
    public $showModal = false;
    public $editingCategoryId = null;
    public $notification = [
        'show' => false,
        'message' => ''
    ];

    // Mendefinisikan listeners untuk event
    protected $listeners = [
        'closeModal' => 'handleCloseModal',
        'categorySaved' => 'handleCategorySaved'
    ];

    // Membuka modal dan mengatur ID customer jika dalam mode edit
    public function openModal($categoryId = null)
    {
        $this->editingCategoryId = $categoryId;
        $this->showModal = true;
    }

    // Menutup modal dan membersihkan state
    public function handleCloseModal()
    {
        $this->showModal = false;
        $this->editingCategoryId = null;
        $this->dispatch('modalClosed');
    }

    // Menangani event setelah customer disimpan
    public function handleCategorySaved($message)
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
    public function test(){
        $categoryId = $this->deleteCategory;
        $category = Category::findOrFail($categoryId); 
        $category->delete(); 
        $this->deleteConfirmation = false;
        $this->deleteCategory = null; 
        $this->resetPage(); 
    }
    // Menghapus kategori (soft delete)
    
    // Menampilkan halaman konfirmasi
    public function deleteConfirm($categoryId)
    {
        
        $this->deleteConfirmation = true; // Menampilkan halaman konfirmasi
        $this->deleteCategory = $categoryId; 
    }

    // Menutup halaman konfirmasi tanpa menghapus data
    public function closeDelete()
    {
        $this->deleteConfirmation = false; // Menutup modal konfirmasi
        $this->deleteCategory = null;  // Reset ID kategori
    }

    public function render()
    {
        // Query data kampanye
        $category = Category::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('category', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })           
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Return data ke view
        return view('livewire.products.categories', [
            'categories' => $category
        ]);
    }
}
