<?php

namespace App\Livewire\Products;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
class CategoriesForm extends Component
{
    // Properties untuk data form
    public $categoryId = null;
    public $category = '';
    public $description = '';

    // Properties untuk state UI
    public $showConfirmation = false;
    public $isLoading = false;
    public $showSuccess = false;

    // Mendefinisikan rule validasi
    protected function rules()
    {
        return [
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
        ];
    }

    // Inisialisasi data untuk mode edit
    public function mount($categoryId = null)
    {
    
        if ($categoryId) {
            $category = Category::findOrFail($categoryId);
            $this->productId = $category->id;
            $this->category = $category->category;
            $this->description = $category->description;
            }
    }

    // Menutup modal dan mereset state
    public function closeModal()
    {
        $this->dispatch('closeModal');
        $this->resetState();
    }

    // Menampilkan modal konfirmasi setelah validasi
    public function confirmSave()
    {
        $this->validate();
        $this->showConfirmation = true;
    }

    // Membatalkan konfirmasi
    public function cancelConfirmation()
    {
        $this->showConfirmation = false;
        $this->isLoading = false;
        $this->showSuccess = false;
    }

    // Proses penyimpanan data dengan animasi
    public function save()
    {
        try {
            // Aktifkan loading state
            $this->isLoading = true;

            DB::beginTransaction();

            if ($this->categoryId) {
                // Update existing campaign
                $category = Category::findOrFail($this->categoryId);
                $category->update([
                    'category' => $this->category,
                    'description' => $this->description,
                ]);

                $message = 'Product updated successfully!';
            } else {
                // Buat campaign baru
                Category::create([
                    'category' => $this->category,
                    'description' => $this->description,
                ]);

                $message = 'Product created successfully!';
            }

            DB::commit();

            // Tampilkan animasi sukses
            $this->isLoading = false;
            $this->showSuccess = true;

            // Tunggu sebentar untuk animasi
            $this->dispatch('saved')->self();

            // Kirim notifikasi ke komponen parent
            $this->dispatch('categorySaved', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->isLoading = false;
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // Reset semua state
    private function resetState()
    {
        $this->categoryId = null;
        $this->category = '';
        $this->description = '';
        $this->showConfirmation = false;
        $this->isLoading = false;
        $this->showSuccess = false;
    }

    public function render()
    {
        return view('livewire.products.categories-form');
    }
}
