<?php

namespace App\Livewire\Marketing;
use Illuminate\Support\Facades\DB;
use App\Models\MarketingCampaign;
use App\Models\Customer;
use Livewire\Component;

class CampaignSend extends Component
{
    public $campaign;
    public $showModal = false;
    public $scheduled = false;

    // Mendefinisikan listeners untuk event
    protected $listeners = [
        'closeModal' => 'handleCloseModal',
        'customerSaved' => 'handleCustomerSaved'
    ];

    public function mount($campaign)
    {
        // Assign the passed data to the $campaign property
        $this->campaign = MarketingCampaign::findOrFail($campaign);
    }
     
    // Membuka modal dan mengatur ID customer jika dalam mode edit
    public function openModal()
    {
        $this->showModal = true;
    }

    
    public function cancel()
    {
        if (session('selected_customers')){
            session()->forget('selected_customers');
        }
    
        return redirect()->route('marketing.whatsapp');
    }
    // Menutup modal dan membersihkan state
    public function handleCloseModal()
    {
        $this->showModal = false;
        $this->editingCustomerId = null;
        $this->dispatch('modalClosed');
    }
    // Menutup modal dan mereset state

    public function closeModal()
    {
        $this->dispatch('closeModal');
        $this->resetState();
    }
    
    public function closeSend()
        {
            return redirect()->back(); // Mengarahkan kembali ke halaman sebelumnya
        }

    public function render()
    {
        return view('livewire.marketing.campaign-send');
    }
}
