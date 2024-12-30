<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerInteraction;
use App\Models\PriceQuotation;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Shipping;


class ProjectList extends Component
{
    use WithPagination;
    

    // Properties untuk form
    public $vendor_id;
    public $customer_id;
    public $product_id;
    public $project_header;
    public $project_value = 0;
    public $project_duration_start;
    public $project_duration_end;
    public $project_detail;
    public $project_status = 'Pending';

    // Properties untuk filter dan pencarian
    public $search = '';
    public $vendorFilter = '';
    public $customerFilter = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Properties untuk modal dan state
    public $showModal = false;
    public $showDeleteModal = false;
    public $showDetailModal = false;
    public $editMode = false;
    public $project_id;
    public $selectedProject = null;

    // Properties untuk products
    public $selectedProducts = [];
    public $quantities = [];
    public $productPrices = [];
    public $productSubtotals = [];
    public $total_products = 0;
    public $total_value = 0;

    // Properties untuk status tracking
    public $selectedStatus = [];
    public $pendingDuration = [];
    public $progress = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'vendorFilter' => ['except' => ''],
        'customerFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => '']
    ];

    protected function rules()
    {
        return [
            'vendor_id' => 'nullable|exists:vendors,vendor_id', 
            'customer_id' => 'required|exists:customers,customer_id',
            'product_id' => 'required|exists:products,product_id',
            'project_header' => 'required|string|max:100',
            'project_value' => 'required|numeric|min:0',
            'project_duration_start' => 'required|date',
            'project_duration_end' => 'required|date|after:project_duration_start',
            'project_detail' => 'required|string|max:255',
            'selectedProducts' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'vendor_id.required' => 'Vendor harus dipilih',
        'customer_id.required' => 'Customer harus dipilih',
        'product_id.required' => 'Produk utama harus dipilih',
        'project_header.required' => 'Judul proyek harus diisi',
        'project_header.max' => 'Judul proyek maksimal 100 karakter',
        'project_value.required' => 'Nilai proyek harus diisi',
        'project_value.numeric' => 'Nilai proyek harus berupa angka',
        'project_value.min' => 'Nilai proyek minimal 0',
        'project_duration_start.required' => 'Tanggal mulai harus diisi',
        'project_duration_end.required' => 'Tanggal selesai harus diisi',
        'project_duration_end.after' => 'Tanggal selesai harus setelah tanggal mulai',
        'project_detail.required' => 'Detail proyek harus diisi',
        'project_detail.max' => 'Detail proyek maksimal 255 karakter',
        'selectedProducts.required' => 'Minimal satu produk harus dipilih',
        'selectedProducts.min' => 'Minimal satu produk harus dipilih',
        'quantities.*.required' => 'Jumlah produk harus diisi',
        'quantities.*.integer' => 'Jumlah produk harus berupa angka bulat',
        'quantities.*.min' => 'Jumlah produk minimal 1',
    ];
    private function initializeProjectStatus()
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            $this->selectedStatus[$project->project_id] = $this->calculateProjectStatus($project);
            $this->progress[$project->project_id] = $this->calculateProgress($project);
        }
    }

    public function mount()
    {
        $this->project_duration_start = now()->format('Y-m-d');
        $this->project_duration_end = now()->addMonths(1)->format('Y-m-d');
        $this->initializeProjectStatus();
    }

   


    private function calculateProgress($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $now = Carbon::now();
    
        // Jika belum dimulai
        if ($now < $startDate) {
            return 0;
        }
    
        // Jika sudah selesai
        if ($now > $endDate) {
            return 100;
        }
    
        // Hitung progress berdasarkan waktu
        $totalDays = $startDate->diffInDays($endDate) ?: 1;
        $daysElapsed = $startDate->diffInDays($now);
        
        return min(100, round(($daysElapsed / $totalDays) * 100));
    }

    private function calculateProjectStatus($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $today = Carbon::now();
    
        // Project belum dimulai
        if ($today < $startDate) {
            return 'Pending';
        }
        
        // Project sudah selesai
        if ($today > $endDate) {
            return 'Selesai';
        }
    
        // Project sedang berjalan
        return 'Dalam Pengerjaan';
    }
    
    public function updatedSelectedProducts($value, $productId)
    {
        if ($value) {
            if (!isset($this->quantities[$productId])) {
                $this->quantities[$productId] = 1;
                $product = Product::find($productId);
                if ($product) {
                    $this->productPrices[$productId] = $product->product_price;
                    $this->calculateSubtotal($productId);
                }
            }
        } else {
            unset($this->quantities[$productId]);
            unset($this->productPrices[$productId]);
            unset($this->productSubtotals[$productId]);
        }
        $this->calculateTotal();
    }

    public function calculateSubtotal($productId)
{
    try {
        if (isset($this->quantities[$productId]) && isset($this->productPrices[$productId])) {
            $this->productSubtotals[$productId] = 
                floatval($this->quantities[$productId]) * floatval($this->productPrices[$productId]);
        }
        $this->calculateTotal();
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error calculating subtotal: ' . $e->getMessage());
    }
}

    public function calculateTotal()
{
    try {
        // Calculate total from products
        $this->total_products = 0;
        foreach ($this->selectedProducts as $productId => $selected) {
            if ($selected && isset($this->productSubtotals[$productId])) {
                $this->total_products += floatval($this->productSubtotals[$productId]);
            }
        }
        
        // Add additional project value
        $this->total_value = $this->total_products + floatval($this->project_value);
        
    } catch (\Exception $e) {
        session()->flash('error', 'Error calculating totals: ' . $e->getMessage());
    }
}

    public function updatedProjectValue()
    {
        $this->calculateTotal();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        try {
            $this->resetValidation();
            $this->editMode = true;
            $this->project_id = $id;
    
            $project = Project::with(['products', 'vendor', 'customer'])->findOrFail($id);
    
            // Reset arrays dulu untuk menghindari data tersisa
            $this->selectedProducts = [];
            $this->quantities = [];
            $this->productPrices = [];
            $this->productSubtotals = [];
            $this->total_products = 0;
            $this->total_value = 0;
    
            // Set basic fields
            $this->vendor_id = $project->vendor_id;
            $this->customer_id = $project->customer_id;
            $this->product_id = $project->product_id;
            $this->project_header = $project->project_header;
            $this->project_duration_start = $project->project_duration_start->format('Y-m-d');
            $this->project_duration_end = $project->project_duration_end->format('Y-m-d');
            $this->project_detail = $project->project_detail;
            $this->project_status = $project->status ?? 'Pending';
    
            // Set products with pivot data
            foreach ($project->products as $product) {
                $this->selectedProducts[$product->product_id] = true;
                $this->quantities[$product->product_id] = $product->pivot->quantity;
                $this->productPrices[$product->product_id] = $product->pivot->price_at_time;
                $this->productSubtotals[$product->product_id] = $product->pivot->subtotal;
                $this->total_products += $product->pivot->subtotal;
            }
    
            // Set initial project value (nilai tambahan di luar produk)
            $this->project_value = floatval($project->project_value) - $this->total_products;
            
            // Calculate total value
            $this->calculateTotal();
    
            $this->showModal = true;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error editing project: ' . $e->getMessage());
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();
    
            $projectData = [
                'vendor_id' => $this->vendor_id,
                'customer_id' => $this->customer_id,
                'product_id' => $this->product_id,
                'project_header' => $this->project_header,
                'project_value' => $this->total_value,
                'project_duration_start' => $this->project_duration_start,
                'project_duration_end' => $this->project_duration_end,
                'project_detail' => $this->project_detail
                // Hapus status karena sekarang ditentukan berdasarkan tanggal
            ];
    
            if ($this->editMode) {
                $project = Project::findOrFail($this->project_id);
                $project->update($projectData);
                $project->products()->detach();
            } else {
                $project = Project::create($projectData);
            }

            // Attach products
            foreach ($this->selectedProducts as $productId => $selected) {
                if ($selected) {
                    $project->products()->attach($productId, [
                        'quantity' => $this->quantities[$productId],
                        'price_at_time' => $this->productPrices[$productId],
                        'subtotal' => $this->productSubtotals[$productId],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Create customer interaction record
            CustomerInteraction::create([
                'customer_id' => $this->customer_id,
                'user_id' => Auth::id(),
                'vendor_id' => $this->vendor_id,
                'interaction_type' => 'Other',
                'interaction_date' => now(),
                'notes' => $this->editMode ? 
                    "Project updated: {$this->project_header}" : 
                    "New project created: {$this->project_header}"
            ]);

            DB::commit();
        
        $this->dispatch('project-saved', $this->editMode ? 
            'Project updated successfully!' : 
            'Project created successfully!');
        $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving project: ' . $e->getMessage());
        }
    }
    public function showDetail($projectId)
    {
        try {
            // Load project dengan relasinya
            // Tidak perlu load pivot secara terpisah karena sudah termasuk dalam relasi products
            $this->selectedProject = Project::with([
                'vendor', 
                'customer', 
                'products'  // pivot akan otomatis tersedia dalam relasi belongsToMany
            ])->findOrFail($projectId);
    
            // Hitung progress untuk proyek yang dipilih
            if (!isset($this->progress[$projectId])) {
                $this->progress[$projectId] = $this->calculateProgress($this->selectedProject);
            }
    
            $this->showDetailModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading project details: ' . $e->getMessage());
        }
    }
    public function delete()
    {
        try {
            DB::beginTransaction();
    
            $project = Project::findOrFail($this->project_id);
            
            // 1. Hapus shipping data
            $purchases = Purchase::where('project_id', $this->project_id)->get();
            foreach ($purchases as $purchase) {
                // Hapus shipping berdasarkan purchase details
                $purchaseDetails = $purchase->purchaseDetails;
                foreach ($purchaseDetails as $detail) {
                    Shipping::where('purchase_detail_id', $detail->purchase_detail_id)->delete();
                }
                
                // Hapus purchase details menggunakan relasi
                $purchase->purchaseDetails()->delete();
                
                // Hapus purchase
                $purchase->delete();
            }
    
            // 2. Hapus price quotations
            $project->priceQuotations()->delete();
    
            // 3. Hapus relasi products
            $project->products()->detach();
    
            // 4. Hapus project
            $project->delete();
    
            DB::commit();
            
            $this->showDeleteModal = false;
            $this->dispatch('project-deleted', 'Proyek berhasil dihapus!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error menghapus proyek: ' . $e->getMessage());
        }
    }

    public function confirmDelete($projectId)
{
    try {
        // Set project_id yang akan dihapus
        $this->project_id = $projectId;
        
        // Pastikan project ada sebelum menampilkan konfirmasi
        $project = Project::findOrFail($projectId);
        
        // Tampilkan modal konfirmasi
        $this->showDeleteModal = true;
    } catch (\Exception $e) {
        session()->flash('error', 'Error finding project: ' . $e->getMessage());
    }
}

    public function render()
    {
        $query = Project::with(['vendor', 'customer', 'products'])
        ->when($this->search, function($q) {
            $q->where(function($query) {
                $query->where('project_header', 'like', '%' . $this->search . '%')
                      ->orWhere('project_detail', 'like', '%' . $this->search . '%')
                      ->orWhereHas('vendor', function($q) {
                          $q->where('vendor_name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('customer', function($q) {
                          $q->where('customer_name', 'like', '%' . $this->search . '%');
                      });
            });
        })
            ->when($this->vendorFilter, function($q) {
                $q->where('vendor_id', $this->vendorFilter);
            })
            ->when($this->customerFilter, function($q) {
                $q->where('customer_id', $this->customerFilter);
            })
            ->when($this->statusFilter, function($q) {
                // Filter berdasarkan tanggal untuk menentukan status
                $now = Carbon::now();
                switch($this->statusFilter) {
                    case 'Pending':
                        $q->where('project_duration_start', '>', $now);
                        break;
                    case 'Dalam Pengerjaan':
                        $q->where('project_duration_start', '<=', $now)
                          ->where('project_duration_end', '>', $now);
                        break;
                    case 'Selesai':
                        $q->where('project_duration_end', '<=', $now);
                        break;
                }
            })
            ->when($this->dateFilter, function($q) {
                $q->whereDate('project_duration_start', '<=', $this->dateFilter)
                  ->whereDate('project_duration_end', '>=', $this->dateFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.project.project-list', [
            'projects' => $query->paginate(10),
            'vendors' => Vendor::orderBy('vendor_name')->get(),
            'customers' => Customer::orderBy('customer_name')->get(),
            'products' => Product::orderBy('product_name')->get()
        ]);
    }

    private function resetForm()
    {
        $this->reset([
            'vendor_id',
            'customer_id',
            'product_id',
            'project_header',
            'project_value',
            'project_detail',
            'project_status',
            'selectedProducts',
            'quantities',
            'productPrices',
            'productSubtotals',
            'total_products',
            'total_value',
            'editMode',
            'project_id'
        ]);

        $this->project_duration_start = now()->format('Y-m-d');
        $this->project_duration_end = now()->addMonths(1)->format('Y-m-d');
    }
}