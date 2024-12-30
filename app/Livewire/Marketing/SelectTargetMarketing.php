<?php

namespace App\Livewire\Marketing;
use App\Models\Customer;
use Livewire\WithPagination;
use Livewire\Component;

class SelectTargetMarketing extends Component
{
    use WithPagination;
    public $search = '';
    public function saveSelectedCustomers()
    {
        // Menampilkan data yang dikirim saat tombol disubmit
        dd($this->selectedCustomers);

        // Proses lainnya, misalnya menyimpan ke database
    }

    public function mount()
    {
       
    }
    // Menutup modal dan mereset state
    public function closeModal()
    {
        $this->dispatch('closeModal');
        // $this->resetState();
    }

    public function render()
    {
        $customer = Customer::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('customer_name', 'like', "%{$this->search}%");
                });
            })
            ->paginate(10);
        
        return view('livewire.marketing.select-target-marketing', ['customers'=> $customer]);
    }
}
