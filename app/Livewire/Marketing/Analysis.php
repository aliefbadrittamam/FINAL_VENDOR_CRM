<?php

namespace App\Livewire\Marketing;

use App\Models\Lead;
use App\Models\Project;
use App\Models\Sales;
use App\Models\MarketingDetail;
use App\Models\MarketingCampaign;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Untuk DB query

class Analysis extends Component
{
    use WithPagination;
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

    public function updatingFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Inisialisasi query
        $query = MarketingDetail::query();
        $sales = Sales::query();
        
        // Atur tanggal default jika tidak ada filter
        $startDate = $this->filters['date_from'] 
            ? Carbon::parse($this->filters['date_from']) 
            : Carbon::now()->subDays(6);

        $endDate = $this->filters['date_to'] 
            ? Carbon::parse($this->filters['date_to']) 
            : Carbon::now();

        // Buat array tanggal
        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->toDateString());
        }

        // Tambahkan filter ke query
        if ($this->filters['date_from']) {
            $query->where('send_date', '>=', $this->filters['date_from']);
            $sales->where('created_at', '>=', $this->filters['date_from']);
        }
        if ($this->filters['date_to']) {
            $query->where('send_date', '<=', $this->filters['date_to']);
            $sales->where('created_at', '<=', $this->filters['date_to']);
        }
        if ($this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }

        // Hitung total
        $total_send = $query->count();
        $total_delivered = $query->clone()->where('state', 'delivered')->count();
        $total_send_customer = $query->distinct('customer_id')->count();
        $total_sales = $sales->count();
        $salesPerDay = [];
        $messagePerDay = [];

        foreach ($dates as $date) {
            $dailySalesCount = $sales->clone()->whereDate('created_at', $date)->count();
            $dailyMessageCount = $query->clone()->whereDate('send_date', $date)->count();

            $salesPerDay[] = $dailySalesCount;
            $messagePerDay[] = $dailyMessageCount;
        }
        $efectivity = ($total_sales*100)/$total_delivered;
        // Return data ke view
        return view('livewire.marketing.analysis', [
            'total_send' => $total_send,
            'total_delivered' => $total_delivered,
            'total_send_customer' => $total_send_customer,
            'total_sales' => $total_sales,
            'dates_label' => $dates->toArray(),
            'sales_daily' => $salesPerDay,
            'message_daily' => $messagePerDay,
            'effectivity' => round($efectivity, 2),
            'campaigns' => $query->distinct()->select('campaign_id')->get()
        ]);
    }
}

