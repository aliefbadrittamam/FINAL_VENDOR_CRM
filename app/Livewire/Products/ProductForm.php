<?php
namespace App\Livewire\Products;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;

class ProductForm extends Component
{
    // Properties untuk data form
    public $productId = null;
    public $product_name = '';
    public $product_price = '';
    public $description = '';
    public $category_id = '';

    // Properties untuk state UI
    public $showConfirmation = false;
    public $isLoading = false;
    public $showSuccess = false;

    // Mendefinisikan rule validasi
    protected function rules()
    {
        return [
            'product_name' => 'required|string|max:100',
            'product_price' => 'required',
            'category_id' => 'required',
            'description' => 'nullable|string',
        ];
    }

    // Inisialisasi data untuk mode edit
    public function mount($productId = null)
    {
    
        if ($productId) {
            $product = Product::findOrFail($productId);
            $this->productId = $product->product_id;
            $this->product_name = $product->product_name;
            $this->description = $product->description;
            $this->product_price = $product->product_price;
            $this->category_id = $product->category_id;        }
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

            if ($this->productId) {
                // Update existing campaign
                $product = Product::findOrFail($this->productId);
                $product->update([
                    'product_name' => $this->product_name,
                    'description' => $this->description,
                    'product_price' => $this->product_price,
                    'category_id' => $this->category_id,
                ]);

                $message = 'Product updated successfully!';
            } else {
                // Buat campaign baru
                Product::create([
                    'product_name' => $this->product_name,
                    'description' => $this->description,
                    'product_price' => $this->product_price,
                    'category_id' => $this->category_id,
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
            $this->dispatch('productSaved', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->isLoading = false;
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // Reset semua state
    private function resetState()
    {
        $this->productId = null;
        $this->product_name = '';
        $this->product_price = '';
        $this->category_id = '';
        $this->description = '';
        $this->showConfirmation = false;
        $this->isLoading = false;
        $this->showSuccess = false;
    }

    // Render view
    public function render()
    {
        $category = Category::all();
        return view('livewire.products.product-form', ['categories'=>$category]);
    }
}
