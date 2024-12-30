<?php
namespace App\Livewire\Sales;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PriceQuotation as PriceQuotationModel;
use App\Models\Project;
use App\Models\Vendor;
use App\Services\Fonnte;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerInteraction;
use Illuminate\Support\Facades\Auth;

class PriceQuotation extends Component
{
    use WithPagination;

    // Properties untuk form
    public $project_id;
    public $vendor_id;
    public $amount;
    public $showModal = false;
    public $editMode = false;
    public $quotation_id;
    // Properties untuk konfirmasi
    public $showAcceptModal = false;
    public $selectedQuotation = null;
    public $confirmationNote;

    // Properties untuk filtering
    public $search = '';
    public $projectFilter = '';
    public $vendorFilter = '';
    public $statusFilter = '';
    protected $fonnte;

    
    protected function rules()
    {
        return [
            'project_id' => 'required|exists:projects,project_id',
            'vendor_id' => 'required|exists:vendors,vendor_id',
            'amount' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'project_id.required' => 'Project harus dipilih',
        'vendor_id.required' => 'Vendor harus dipilih',
        'amount.required' => 'Nilai quotation harus diisi',
        'amount.numeric' => 'Nilai quotation harus berupa angka',
        'amount.min' => 'Nilai quotation minimal 0',
    ];

    // Reset pagination ketika filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProjectFilter()
    {
        $this->resetPage();
    }

    public function updatingVendorFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['project_id', 'vendor_id', 'amount', 'editMode', 'quotation_id']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->quotation_id = $id;
        
        $quotation = PriceQuotationModel::findOrFail($id);
        
        $this->project_id = $quotation->project_id;
        $this->vendor_id = $quotation->vendor_id;
        $this->amount = $quotation->amount;
        
        $this->showModal = true;
    }

    public function openAcceptModal($id)
    {
        try {
            $this->selectedQuotation = PriceQuotationModel::with(['project', 'vendor'])
                ->findOrFail($id);

            if ($this->selectedQuotation->status === 'Accepted') {
                session()->flash('error', 'Quotation ini sudah diterima sebelumnya.');
                return;
            }

            $this->showAcceptModal = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading quotation: ' . $e->getMessage());
        }
    }

    public function acceptQuotation($quotationId)
    {
        try {
            DB::beginTransaction();
    
            // Ambil data quotation bersama project
            $quotation = PriceQuotationModel::with('project', 'vendor')->findOrFail($quotationId);
            if (!$quotation) {
                throw new \Exception('Quotation tidak ditemukan');
            }
    
            if (!$quotation->project) {
                throw new \Exception('Project tidak ditemukan untuk quotation ini');
            }
            
            // Update project value dengan amount dari quotation
            $quotation->project->update([
                'project_value' => $quotation->amount,
                'vendor_id' => $quotation->vendor_id  
            ]);
            $fonnte = new Fonnte();

            // Ambil vendor data
            $vendor = $quotation->vendor;
            if (!$vendor) {
                throw new \Exception('Vendor tidak ditemukan');
            }

            $project = $quotation->project; 
    
            $amount = $quotation->amount; 
            if ($vendor && $project) {
                $targetData = "{$vendor->vendor_phone} | {$vendor->vendor_name} | {$project->project_header} | {$project->project_detail} | {$amount}";
                $message = "Teruntuk yang terhormat, {$vendor->vendor_name}\nPemberitahuan penawaran kerja sama untuk proyek {$project->project_header}, dengan detail {$project->project_detail}. \nSerta besar penawaran {$amount}. Telah berstatus DITERIMA.";
            } else {
                $targetData = 'Data incomplete';
                $message = 'Tidak dapat mengirim pesan karena data tidak lengkap.';
            }
    
            $postData = [
                'target' => $targetData,
                'message' => $message,
                'countryCode' => 62,
            ];    
            
            $fonnte->send('https://api.fonnte.com/send', $postData);
            $this->reset(['project_id', 'vendor_id', 'amount', 'editMode', 'quotation_id']);
    
            DB::commit();
            
            $this->dispatch('quotation-accepted', 'Quotation berhasil diterima dan nilai proyek telah diupdate!');
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error accepting quotation: ' . $e->getMessage());
        }
    }

    public function save()
    {
        $this->validate(); // Validasi akan memastikan project_id terisi
    
        try {
            DB::beginTransaction();
    
            $data = [
                'project_id' => $this->project_id,
                'vendor_id' => $this->vendor_id,
                'amount' => $this->amount
            ];
    
            if ($this->editMode) {
                $quotation = PriceQuotationModel::findOrFail($this->quotation_id);
                $quotation->update($data);
                $message = 'Price quotation berhasil diperbarui!';
            } else {
                PriceQuotationModel::create($data); // Sekarang termasuk project_id
                $message = 'Price quotation berhasil dibuat!';
            }
    
            DB::commit();
    
            $this->showModal = false;
            $this->dispatch('quotation-saved', $message);

            $fonnte = new Fonnte();
            $target = Vendor::where('vendor_id', $this->vendor_id)->first(['vendor_name', 'vendor_phone']);
            $project = Project::where('project_id', $this->project_id)->first(['project_header', 'project_detail']);
            $amount = $this->amount;
            if ($target && $project) {
                $targetData = "{$target->vendor_phone} | {$target->vendor_name} | {$project->project_header} | {$project->project_detail} | {$amount}";
                $message = "Teruntuk yang terhormat, {$target->vendor_name}\nKami mengajukan penawaran kerja sama untuk proyek {$project->project_header}, dengan detail {$project->project_detail}.\nAdapun harga pertama yang kami tawarkan adalah {$amount}. Mohon ketersediaannya untuk melakukan diskusi lebih lanjut bersama tim Kami.";
            } else {
                $targetData = 'Data incomplete';
                $message = 'Tidak dapat mengirim pesan karena data tidak lengkap.';
            }
            
            $postData = [
                'target' => $targetData,
                'message' => $message,
                'countryCode' => 62,
            ];
            
            $fonnte->send('https://api.fonnte.com/send', $postData);
            $this->reset(['project_id', 'vendor_id', 'amount', 'editMode', 'quotation_id']);
            
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $quotation = PriceQuotationModel::findOrFail($id);
            
            if ($quotation->status === 'Accepted') {
                session()->flash('error', 'Tidak dapat menghapus quotation yang sudah diterima.');
                return;
            }
            
            $quotation->delete();
            $this->dispatch('quotation-deleted', 'Price quotation berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting quotation: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'projectFilter', 'vendorFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function render()
{
    $query = PriceQuotationModel::query()
        ->with(['project', 'vendor'])
        ->latest() // Ini akan mengurutkan berdasarkan created_at DESC
        ->when($this->search, function($q) {
            $q->where(function($query) {
                $query->whereHas('project', function($q) {
                    $q->where('project_header', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('vendor', function($q) {
                    $q->where('vendor_name', 'like', '%' . $this->search . '%');
                });
            });
        })
        ->when($this->projectFilter, function($q) {
            $q->where('project_id', $this->projectFilter);
        })
        ->when($this->vendorFilter, function($q) {
            $q->where('vendor_id', $this->vendorFilter);
        });

    return view('livewire.sales.price-quotation', [
        'quotations' => $query->paginate(10),
        'projects' => Project::orderBy('project_header')->get(),
        'vendors' => Vendor::orderBy('vendor_name')->get()
    ]);
}
}