<?php
namespace App\Livewire\Marketing;

use App\Models\MarketingCampaign;
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
    public $editingCampaignId = null;
    public $notification = [
        'show' => false,
        'message' => ''
    ];

    // Mendefinisikan listeners untuk event
    protected $listeners = [
        'closeModal' => 'handleCloseModal',
        'campaignSaved' => 'handleCampaignSaved'
    ];

    // Membuka modal dan mengatur ID customer jika dalam mode edit
    public function openModal($campaignId = null)
    {
        $this->editingCampaignId = $campaignId;
        $this->showModal = true;
    }

    // Menutup modal dan membersihkan state
    public function handleCloseModal()
    {
        $this->showModal = false;
        $this->editingCampaignId = null;
        $this->dispatch('modalClosed');
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

    // Reset halaman saat filter berubah
    public function updatingFilters()
    {
        $this->resetPage();
    }

    // Menghapus campaign (soft delete)
    public function deleteCampaign($campaignId)
    {
        $campaign = MarketingCampaign::findOrFail($campaignId);
        $campaign->delete(); // Soft delete campaign

        // Refresh data dan tampilkan notifikasi
        $this->notification['show'] = true;
        $this->notification['message'] = 'Campaign deleted successfully.';

        $this->resetPage(); // Reset pagination
    }

    public function render()
    {
        // Validasi jika date_to lebih kecil dari date_from
        if (
            isset($this->filters['date_from'], $this->filters['date_to']) && 
            $this->filters['date_to'] < $this->filters['date_from']
        ) {
            // Simpan pesan kesalahan ke sesi untuk ditampilkan di view
            session()->flash('error', 'The "Date To" must be greater than or equal to "Date From".');
            
            // Return view dengan paginator kosong
            return view('livewire.marketing.main', [
                'campaign' => MarketingCampaign::query()->paginate(1) 
            ]);
        }

        // Query data kampanye
        $campaign = MarketingCampaign::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('campaign_name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filters['date_from'], function($query) {
                $query->whereDate('end_date', '>=', $this->filters['date_from']);
            })
            ->when($this->filters['date_to'], function($query) {
                $query->whereDate('end_date', '<=', $this->filters['date_to']);
            })            
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        // Return data ke view
        return view('livewire.marketing.main', [
            'campaign' => $campaign
        ]);
    }
}
