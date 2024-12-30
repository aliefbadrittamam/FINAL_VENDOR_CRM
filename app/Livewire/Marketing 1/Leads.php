<?php

namespace App\Livewire\Marketing;
use App\Models\MarketingDetail;
use App\Models\Customer;
use App\Models\Lead;
use Livewire\WithPagination;

use Livewire\Component;

class Leads extends Component
{
    use WithPagination;
    public $showModal = false;
    public $editingLeadsId = null;
    public $status = 'all'; 
    public $search = '';
    public $filters = [
        'date_from' => '',
        'date_to' => '',
        'status'=>'',
    ];
  
    public $notification = [
        'show' => false,
        'message' => ''
    ];
    protected $listeners = [
        'closeModal' => 'handleCloseModal',
        'campaignSaved' => 'handleCampaignSaved'
    ];

    public function openModal($leadId = null)
    {
        $this->editingLeadsId = $leadId;  // Use leadId instead of editingLeadsId
        $this->showModal = true;
    }
    
    public function handleCloseModal()
    {
        $this->showModal = false;
        $this->editingLeadId = null;  // Reset the editing lead ID
    }
    

    // Menangani event setelah customer disimpan
    public function handleCampaignSaved($message)
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
    public function closeModal()
    {
        $this->dispatch('closeModal');
        $this->resetPage();  // Reset form fields (e.g., $comment)
    }
    
    // Reset halaman saat filter berubah
    public function updatingFilters()
    {
        $this->resetPage();
    }
     // Fungsi untuk memperbarui data leads
    public function updateLeads()
     {
        $leads = Lead::withCount(['marketingDetails' => function ($query) {
            $query->where('status', 'delivered');
        }])->get();
    
        foreach ($leads as $lead) {
            // Hitung persentase delivered
            $deliveredCount = $lead->marketing_details_count;  
            $messageCount = $lead->message_count;
    
            // Tentukan status berdasarkan deliveredCount dan messageCount
            if ($messageCount > 0) {
                $deliveredPercentage = ($deliveredCount / $messageCount) * 100;
    
                if ($deliveredPercentage == 0) {
                    $status = 'follow up';
                } elseif ($deliveredPercentage >= 50 && $deliveredPercentage < 100) {
                    $status = 'potential';
                } elseif ($deliveredPercentage == 100) {
                    $status = 'qualified';
                }
    
                // Update status pada tabel Lead
                $lead->update([
                    'delivered' => $deliveredCount,
                    'status' => $status
                ]);
            }
        }
        $customersWithoutLeads = Customer::whereDoesntHave('leads')->get();

        // Loop dan tambahkan data lead
        foreach ($customersWithoutLeads as $customer) {
            Lead::create([
                'customer_id' => $customer->customer_id,
            ]);
        }
    
        // Tampilkan notifikasi berhasil
        $this->notification = [
            'show' => true,
            'message' => 'Leads updated successfully.',
        ];
     }
 
     public function render()
     {
        $leads = Lead::with('customer')
        ->when($this->status !== 'all', function ($query) {
            return $query->where('status', $this->status);
        })
        ->paginate(10);

        return view('livewire.marketing.leads', [
            'leads' => $leads,
        ]);
     }
    
}
