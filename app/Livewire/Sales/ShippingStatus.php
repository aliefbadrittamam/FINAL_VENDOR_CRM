<?php

namespace App\Livewire\Sales;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Shipping;
use App\Models\PurchaseDetail;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class ShippingStatus extends Component
{
    use WithPagination;

    public $purchase_detail_id;
    public $project_id;
    public $vendor_id;
    public $customer_id;
    public $shipping_status = 'Pending';
    public $number_receipt;
    public $showModal = false;
    public $editMode = false;
    public $shipping_id;
    public $search = '';
    public $statusFilter = '';
    public $projectFilter = '';
    public $vendorFilter = '';

    protected $rules = [
        'purchase_detail_id' => 'required|exists:purchase_details,purchase_detail_id',
        'project_id' => 'required|exists:projects,project_id',
        'vendor_id' => 'required|exists:vendors,vendor_id',
        'customer_id' => 'required|exists:customers,customer_id',
        'shipping_status' => 'required|in:Pending,Completed,Cancelled',
        'number_receipt' => 'required|integer'
    ];

    protected $messages = [
        'purchase_detail_id.required' => 'Purchase detail is required',
        'project_id.required' => 'Project is required',
        'vendor_id.required' => 'Vendor is required',
        'customer_id.required' => 'Customer is required',
        'number_receipt.required' => 'Receipt number is required',
        'number_receipt.integer' => 'Receipt number must be a number'
    ];

    public function mount()
    {
        $this->shipping_status = 'Pending';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
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

    public function openModal()
    {
        $this->resetValidation();
        $this->resetExcept(['search', 'statusFilter', 'projectFilter', 'vendorFilter']);
        $this->showModal = true;
        $this->shipping_status = 'Pending';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->resetExcept(['search', 'statusFilter', 'projectFilter', 'vendorFilter']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->resetExcept(['search', 'statusFilter', 'projectFilter', 'vendorFilter']);
        $this->editMode = true;
        $this->shipping_id = $id;

        $shipping = Shipping::findOrFail($id);
        
        $this->purchase_detail_id = $shipping->purchase_detail_id;
        $this->project_id = $shipping->project_id;
        $this->vendor_id = $shipping->vendor_id;
        $this->customer_id = $shipping->customer_id;
        $this->shipping_status = $shipping->shipping_status;
        $this->number_receipt = $shipping->Number_receipt;

        $this->showModal = true;
    }

    public function updateStatus($id, $newStatus)
    {
        try {
            $shipping = Shipping::findOrFail($id);
            $shipping->update([
                'shipping_status' => $newStatus
            ]);
            
            $this->dispatch('shipping-updated', 'Shipping status updated successfully!');
        } catch (\Exception $e) {
            logger('Error updating shipping status: '. $e->getMessage());
            session()->flash('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'purchase_detail_id' => $this->purchase_detail_id,
                'project_id' => $this->project_id,
                'vendor_id' => $this->vendor_id,
                'customer_id' => $this->customer_id,
                'shipping_status' => $this->shipping_status,
                'Number_receipt' => $this->number_receipt
            ];

            if ($this->editMode) {
                $shipping = Shipping::findOrFail($this->shipping_id);
                $shipping->update($data);
                $message = 'Shipping updated successfully!';
            } else {
                Shipping::create($data);
                $message = 'Shipping created successfully!';
            }

            DB::commit();

            $this->showModal = false;
            $this->resetExcept(['search', 'statusFilter', 'projectFilter', 'vendorFilter']);
            
            $this->dispatch('shipping-saved', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            logger('Error saving shipping: '. $e->getMessage());
            session()->flash('error', 'Error saving shipping: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $shipping = Shipping::findOrFail($id);
            $shipping->delete();

            DB::commit();
            
            $this->dispatch('shipping-deleted', 'Shipping deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            logger('Error deleting shipping: '. $e->getMessage());
            session()->flash('error', 'Error deleting shipping: ' . $e->getMessage());
        }
    }

    public function render()
{
    $query = Shipping::with(['purchaseDetail', 'project', 'vendor', 'customer'])
        ->when($this->search, function($q) {
            $q->where(function($query) {
                $query->whereHas('customer', function($q) {
                    $q->where('customer_name', 'like', '%' . $this->search . '%');
                })->orWhereHas('vendor', function($q) {
                    $q->where('vendor_name', 'like', '%' . $this->search . '%');
                });
            });
        })
        ->when($this->statusFilter, function($q) {
            $q->where('shipping_status', $this->statusFilter);
        })
        ->when($this->projectFilter, function($q) {
            $q->where('project_id', $this->projectFilter);
        })
        ->when($this->vendorFilter, function($q) {
            $q->where('vendor_id', $this->vendorFilter);
        })
        ->orderBy('shipping_id', 'desc'); // Ganti ordering menggunakan shipping_id

    return view('livewire.sales.shipping-status', [
        'shippings' => $query->paginate(10),
        'purchaseDetails' => PurchaseDetail::all(),
        'projects' => Project::all(),
        'vendors' => Vendor::all(),
        'customers' => Customer::all()
    ]);
}
}