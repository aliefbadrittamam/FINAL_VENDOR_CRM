<?php

namespace App\Livewire\Marketing;
use App\Models\MarketingDetail;
use Livewire\WithPagination;
use Carbon\Carbon;

use Livewire\Component;


class MessageHistory extends Component
{
    use WithPagination;
    public $startDate;
    public $endDate;
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
    public function deleteCampaign($campaignId)
    {
        $campaign = MarketingDetail::findOrFail($campaignId);
        $campaign->delete(); // Soft delete campaign

        // Refresh data dan tampilkan notifikasi
        $this->notification['show'] = true;
        $this->notification['message'] = 'Campaign deleted successfully.';

        $this->resetPage(); // Reset pagination
    }
    public function deleteAllCampaign($criteria)
    {    
            // Tentukan query dasar untuk kampanye
            $query = MarketingDetail::query();
            

            // Tentukan penghapusan berdasarkan kriteria
            if ($criteria === 'this_month') {
                // Hapus kampanye yang dibuat pada bulan ini
                $query->whereMonth('send_date', Carbon::now()->month)
                      ->whereYear('send_date', Carbon::now()->year);
            } elseif ($criteria === 'all') {
                // Hapus semua kampanye
                $query->whereNotNull('id');
            } elseif ($criteria === 'choose_range') {
                
                // Hapus kampanye berdasarkan rentang tanggal
                if (!$this->startDate || !$this->endDate) {
                    $this->notification['show'] = true;
                    $this->notification['message'] = 'Please provide both start and end dates.';
                    return;
                    dd('halo');
                }

                // Pastikan startDate dan endDate adalah objek Carbon atau string dengan format yang tepat
                
                // Lakukan query untuk mendapatkan data berdasarkan rentang tanggal
                $result = $query->when($this->startDate, function($query) {
                    $query->whereDate('send_date', '>=', $this->startDate);
                })           
                ->when($this->endDate, function($query) {
                    $query->whereDate('send_date', '<=', $this->endDate);
                })->get();
                // Debug hasil query
                
            }

            // Eksekusi penghapusan
            $deletedCount = $query->delete();

            // Notifikasi berdasarkan jumlah yang dihapus
            if ($deletedCount > 0) {
                $this->notification['show'] = true;
                $this->notification['message'] = $deletedCount . ' campaign(s) deleted successfully.';
            } else {
                $this->notification['show'] = true;
                $this->notification['message'] = 'No campaigns found for the selected criteria.';
            }

            // Reset pagination
            $this->resetPage();
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
            return view('livewire.marketing.message-history', [
                'campaigns' => MarketingDetail::query()->paginate(1) 
            ]);
        }


        $campaign = MarketingDetail::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('campaign_name', 'like', "%{$this->search}%")
                        ->orWhere('customer_name', 'like', "%{$this->search}%")
                        ->orWhere('customer_phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filters['date_from'], function($query) {
                $query->whereDate('send_date', '>=', $this->filters['date_from']);
            })           
            ->when($this->filters['date_to'], function($query) {
                $query->whereDate('send_date', '<=', $this->filters['date_to']);
            })            
            ->orderBy('send_date', 'desc')
            ->paginate(10);

        return view('livewire.marketing.message-history', ['campaigns' => $campaign]);
    }
}
