<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor; // Tambahkan ini di bagian imports
use App\Models\Project; // Tambahkan ini di bagian imports


class SalesOrder extends Component
{
   use WithPagination;

   public $editMode = false;
    public $showModal = false;
    public $showConvertModal = false;
    public $customer_id;
    public $sale_date;
    public $status = 'Pending'; 
    public $selectedProducts = [];
    public $quantities = [];
    public $fixed_amount = 0;
    public $search = '';
    public $statusFilter = '';
    public $sale_id;
    public $total = 0;

   // Project conversion properties
   public $selectedSale;
   public $vendor_id;
   public $project_header;
   public $project_detail;
   public $project_duration_start;
   public $project_duration_end;
   public $project_value;
   public $projectProducts = [];
   public $projectQuantities = [];
   public $productPrices = [];
   public $productSubtotals = [];

   protected function rules()
   {
       $rules = [
           'customer_id' => 'required|exists:customers,customer_id',
           'sale_date' => 'required|date',
           'selectedProducts' => 'required|array|min:1',
           'quantities.*' => 'required|numeric|min:1'
       ];

        // Tambahkan rules untuk konversi project jika modal konversi terbuka
        if ($this->showConvertModal) {
            $rules += [
                'vendor_id' => 'required|exists:vendors,vendor_id',
                'project_header' => 'required|string|max:100',
                'project_detail' => 'required|string|max:255',
                'project_duration_start' => 'required|date',
                'project_duration_end' => 'required|date|after:project_duration_start'
            ];
        }   
        return $rules;

    }

   public function openConvertModal($saleId)
   {
       try {
           $this->selectedSale = Sales::with(['customer', 'details.product'])
                                    ->findOrFail($saleId);

           if ($this->selectedSale->status === 'Converted') {
               session()->flash('error', 'Sales order ini sudah dikonversi menjadi project.');
               return;
           }

           // Set initial values for project
           $this->project_duration_start = now()->format('Y-m-d');
           $this->project_duration_end = now()->addMonths(1)->format('Y-m-d');
           $this->project_value = $this->selectedSale->fixed_amount;
           
           // Load products from sale
           $this->projectProducts = [];
           $this->projectQuantities = [];
           $this->productPrices = [];
           $this->productSubtotals = [];

           foreach ($this->selectedSale->details as $detail) {
               $this->projectProducts[$detail->product_id] = true;
               $this->projectQuantities[$detail->product_id] = $detail->quantity;
               $this->productPrices[$detail->product_id] = $detail->product->product_price;
               $this->productSubtotals[$detail->product_id] = $detail->subtotal;
           }

           $this->showConvertModal = true;

       } catch (\Exception $e) {
           session()->flash('error', 'Error loading sale details: ' . $e->getMessage());
       }
   }


  // Di SalesOrder component
  public function convertToProject()
  {
      $this->validate([
          'project_header' => 'required|string|max:100'
      ]);
  
      try {
          DB::beginTransaction();
  
          $sale = Sales::with(['details.product', 'customer'])->findOrFail($this->selectedSale->sale_id);
          
          if (!$sale) {
              throw new \Exception('Sale dengan ID ' . $this->selectedSale . ' tidak ditemukan.');
          }
  
          if ($sale->status === 'Converted') {
              throw new \Exception('Sale sudah dikonversi ke project sebelumnya.');
          }
  
          // Buat project baru tanpa vendor (null)
          $project = Project::create([
              'vendor_id' => null,  // Set vendor_id ke null
              'customer_id' => $sale->customer_id,
              'product_id' => $sale->details->first()->product_id,
              'project_header' => $this->project_header,
              'project_value' => $sale->fixed_amount,
              'project_duration_start' => now(),
              'project_duration_end' => now()->addMonths(1),
              'project_detail' => "Converted from Sales Order #" . str_pad($sale->sale_id, 5, '0', STR_PAD_LEFT)
          ]);
  
          // Attach products dari sales ke project
          foreach ($sale->details as $detail) {
              $project->products()->attach($detail->product_id, [
                  'quantity' => $detail->quantity,
                  'price_at_time' => $detail->product->product_price,
                  'subtotal' => $detail->subtotal,
                  'created_at' => now(),
                  'updated_at' => now()
              ]);
          }
  
          // Update status sale
          $sale->update(['status' => 'Converted']);
  
          DB::commit();
          
          $this->showConvertModal = false;
          $this->reset(['project_header']);
          $this->dispatch('sale-converted', 'Sales Order berhasil dikonversi ke Project!');
  
      } catch (\Exception $e) {
          DB::rollBack();
          session()->flash('error', $e->getMessage());
      }
  }
   protected function messages()
   {
       return [
           'customer_id.required' => 'Pilih customer terlebih dahulu',
           'sale_date.required' => 'Tanggal penjualan harus diisi',
           'selectedProducts.required' => 'Pilih minimal satu produk',
           'selectedProducts.min' => 'Pilih minimal satu produk',
           'quantities.*.required' => 'Jumlah produk harus diisi',
           'quantities.*.numeric' => 'Jumlah harus berupa angka',
           'quantities.*.min' => 'Jumlah minimal 1',
           'vendor_id.required' => 'Pilih vendor terlebih dahulu',
           'project_header.required' => 'Judul proyek harus diisi',
           'project_header.max' => 'Judul proyek maksimal 100 karakter',
           'project_detail.required' => 'Detail proyek harus diisi',
           'project_detail.max' => 'Detail proyek maksimal 255 karakter',
           'project_duration_start.required' => 'Tanggal mulai harus diisi',
           'project_duration_end.required' => 'Tanggal selesai harus diisi',
           'project_duration_end.after' => 'Tanggal selesai harus setelah tanggal mulai'
       ];
   }
   private function resetConvertForm()
   {
       $this->reset([
           'vendor_id',
           'project_header',
           'project_detail',
           'project_duration_start',
           'project_duration_end',
           'project_value',
           'projectProducts',
           'projectQuantities',
           'productPrices',
           'productSubtotals',
           'selectedSale'
       ]);
   }

   public function closeConvertModal()
   {
       $this->showConvertModal = false;
       $this->resetConvertForm();
       $this->resetValidation();
   }

   public function updatingSearch()
   {
       $this->resetPage();
   }

   public function updatingStatusFilter()
   {
       $this->resetPage();
   }

   public function mount($saleId = null)
   {
       $this->sale_date = date('Y-m-d');
       $this->status = 'Pending';
       
       if ($saleId) {
           $this->loadSale($saleId);
       }
   }

   private function loadSale($saleId)
   {
       $sale = Sales::findOrFail($saleId);
       // Load data sale jika diperlukan
   }

   public function openModal()
   {
       $this->resetForm();
       $this->sale_date = date('Y-m-d');
       $this->status = 'Pending';
       $this->showModal = true;
   }

   public function closeModal()
   {
       $this->showModal = false;
       $this->resetForm();
   }

   public function calculateTotal()
   {
       $this->total = 0;
       $this->fixed_amount = 0;
       
       foreach ($this->selectedProducts as $productId) {
           if (isset($this->quantities[$productId])) {
               $product = Product::find($productId);
               if ($product) {
                   $subtotal = $product->product_price * $this->quantities[$productId];
                   $this->total += $subtotal;
                   $this->fixed_amount += $subtotal;
               }
           }
       }
   }

   public function updatedSelectedProducts($value)
   {
       if(!empty($value)) {
           foreach($this->selectedProducts as $productId) {
               if(!isset($this->quantities[$productId])) {
                   $this->quantities[$productId] = 1;
               }
           }
           $this->calculateTotal();
       }
   }

   public function updatedQuantities($value, $key)
   {
       $this->calculateTotal();
   }

   public function edit($id)
   {
       $this->resetForm();
       $this->editMode = true;
       $this->sale_id = $id;

       $sale = Sales::with('details')->find($id);
       if ($sale) {
           $this->customer_id = $sale->customer_id;
           $this->sale_date = $sale->sale_date;
           $this->status = $sale->status;
           $this->fixed_amount = $sale->fixed_amount;
           $this->total = $sale->fixed_amount;

           foreach ($sale->details as $detail) {
               $this->selectedProducts[] = $detail->product_id;
               $this->quantities[$detail->product_id] = $detail->quantity;
           }
       }

       $this->showModal = true;
   }

   public function updateStatus($id, $newStatus)
   {
       try {
           $sale = Sales::findOrFail($id);
           $sale->update(['status' => $newStatus]);
           
           $this->dispatch('order-updated', 'Order status updated successfully!');
       } catch (\Exception $e) {
           session()->flash('error', 'Error updating status: ' . $e->getMessage());
       }
   }

   public function save()
{
    $this->validate();

    try {
        DB::beginTransaction();

        $sale = Sales::create([
            'customer_id' => $this->customer_id,
            'fixed_amount' => $this->fixed_amount,
            'sale_date' => $this->sale_date,
            'status' => 'Pending'
        ]);

        foreach ($this->selectedProducts as $productId) {
            if (isset($this->quantities[$productId])) {
                $product = Product::find($productId);
                $quantity = $this->quantities[$productId];
                
                $sale->details()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'subtotal' => $product->product_price * $quantity,
                    'updated_at' => now() // PENTING! Karena ini required di database
                ]);
            }
        }

        DB::commit();
        
        $this->showModal = false;
        $this->dispatch('order-saved', 'Sales order created successfully!');
        $this->resetForm();

    } catch (\Exception $e) {
        DB::rollBack();
        logger('Error saving order: ' . $e->getMessage());
        session()->flash('error', 'Error saving order: ' . $e->getMessage());
    }
}

   public function delete($id)
   {
       try {
           DB::beginTransaction();

           $sale = Sales::findOrFail($id);
           $sale->details()->delete();
           $sale->delete();

           DB::commit();
           
           $this->dispatch('order-deleted', 'Sales order deleted successfully!');
       } catch (\Exception $e) {
           DB::rollBack();
           session()->flash('error', 'Error deleting order: ' . $e->getMessage());
       }
   }

   private function resetForm()
   {
       $this->reset([
           'customer_id',
           'selectedProducts',
           'quantities',
           'fixed_amount',
           'total',
           'editMode',
           'sale_id'
       ]);
       $this->sale_date = date('Y-m-d');
       $this->status = 'Pending';
   }

   public function render()
{
    $query = Sales::with(['customer', 'details.product'])
        ->latest() // Menambahkan ini untuk urutan terbaru di atas
        ->when($this->search, function($q) {
            $q->whereHas('customer', function($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->statusFilter, function($q) {
            $q->where('status', $this->statusFilter);
        });

    return view('livewire.sales.sales-order', [
        'sales' => $query->paginate(10),
        'customers' => Customer::all(),
        'products' => Product::all()
    ]);
}   
}