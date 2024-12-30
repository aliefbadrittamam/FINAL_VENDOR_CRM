<?php
namespace App\Livewire\Marketing;

use Illuminate\Support\Facades\DB;
use App\Models\MarketingCampaign;
use Livewire\Component;

class CampaignForm extends Component
{
    // Properties untuk data form
    public $campaignId = null;
    public $campaign_name = '';
    public $start_date = '';
    public $end_date = '';
    public $description = '';
    public $name_included = false;

    // Properties untuk state UI
    public $showConfirmation = false;
    public $isLoading = false;
    public $showSuccess = false;

    // Mendefinisikan rule validasi
    protected function rules()
    {
        return [
            'campaign_name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'name_included'=> 'nullable|boolean'
        ];
    }

    // Inisialisasi data untuk mode edit
    public function mount($campaignId = null)
    {
        if ($campaignId) {
            $campaign = MarketingCampaign::findOrFail($campaignId);
            $this->campaignId = $campaign->campaign_id;
            $this->campaign_name = $campaign->campaign_name;
            $this->start_date = $campaign->start_date;
            $this->end_date = $campaign->end_date;
            $this->description = $campaign->description;
            $this->name_included = $campaign->name_included;        }
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

            if ($this->campaignId) {
                // Update existing campaign
                $campaign = MarketingCampaign::findOrFail($this->campaignId);
                $campaign->update([
                    'campaign_name' => $this->campaign_name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'description' => $this->description,
                    'name_included' =>$this->name_included,
                ]);

                $message = 'Campaign updated successfully!';
            } else {
                // Buat campaign baru
                MarketingCampaign::create([
                    'campaign_name' => $this->campaign_name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'description' => $this->description,
                    'name_included' =>$this->name_included,
                ]);

                $message = 'Campaign created successfully!';
            }

            DB::commit();

            // Tampilkan animasi sukses
            $this->isLoading = false;
            $this->showSuccess = true;

            // Tunggu sebentar untuk animasi
            $this->dispatch('saved')->self();

            // Kirim notifikasi ke komponen parent
            $this->dispatch('campaignSaved', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->isLoading = false;
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // Reset semua state
    private function resetState()
    {
        $this->campaignId = null;
        $this->campaign_name = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->description = '';
        $this->showConfirmation = false;
        $this->isLoading = false;
        $this->showSuccess = false;
    }

    // Render view
    public function render()
    {
        return view('livewire.marketing.campaign-form');
    }
}
