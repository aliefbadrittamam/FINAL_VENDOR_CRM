<?php
namespace App\Livewire\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filters = [
        'date_from' => '',
        'date_to' => ''
    ];
    
    public $showModal = false;
    public $editingCustomerId = null;
    public $notification = [
        'show' => false,
        'message' => ''
    ];

    // Mendefinisikan listeners untuk event
    protected $listeners = [
        'closeModal' => 'handleCloseModal',
        'customerSaved' => 'handleCustomerSaved'
    ];

    // Membuka modal dan mengatur ID customer jika dalam mode edit
    public function openModal($customerId = null)
    {
        $this->editingCustomerId = $customerId;
        $this->showModal = true;
    }

    // Menutup modal dan membersihkan state
    public function handleCloseModal()
    {
        $this->showModal = false;
        $this->editingCustomerId = null;
        $this->dispatch('modalClosed');
    }

    // Menangani event setelah customer disimpan
    public function handleCustomerSaved($message)
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
    public function deleteCustomer($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $customer->delete();

        $this->notification['show'] = true;
        $this->notification['message'] = 'Customer deleted successfully.';

        // Refresh data
        $this->resetPage();
    }
    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('customer_name', 'like', "%{$this->search}%")
                        ->orWhere('customer_email', 'like', "%{$this->search}%")
                        ->orWhere('customer_phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filters['date_from'], function($query) {
                $query->whereDate('created_at', '>=', $this->filters['date_from']);
            })
            ->when($this->filters['date_to'], function($query) {
                $query->whereDate('created_at', '<=', $this->filters['date_to']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.customer.main', [
            'customers' => $customers
        ]);
    }
}