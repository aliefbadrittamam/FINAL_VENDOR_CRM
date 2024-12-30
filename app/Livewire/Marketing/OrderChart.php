<?php

namespace App\Livewire\Marketing;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Sales;
use App\Models\MarketingDetail;

class OrderChart extends Component
{
    public $filters = [];
    public $dates_label = [];
    public $sales_daily = [];
    protected $listeners = [
        'filters' => 'filterSelected',
    ];
    public function filterSelected(array $filters){
        $startDate = $this->filters['date_from']
            ? Carbon::parse($this->filters['date_from'])
            : Carbon::now()->subDays(6);

        $endDate = $this->filters['date_to']
            ? Carbon::parse($this->filters['date_to'])
            : Carbon::now();

        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->toDateString());
        }

        $sales = Sales::query();
        if ($this->filters['date_from']) {
            $sales->where('created_at', '>=', $this->filters['date_from']);
        }
        if ($this->filters['date_to']) {
            $sales->where('created_at', '<=', $this->filters['date_to']);
        }

        $salesPerDay = [];
        foreach ($dates as $date) {
            $salesPerDay[] = $sales->clone()->whereDate('created_at', $date)->count();
        }

        $this->dispatch('updateOrderChart', [
            'dates_label' => $dates->toArray(),
            'sales_daily' => $salesPerDay,
        ]);
        // Menyimpan data ke dalam properti komponen
        $this->dates_label = $dates->toArray();
        $this->sales_daily = $salesPerDay;
    }
    public function mount()
    {
        
    }

    public function render()
    {
        
        
        return view('livewire.marketing.order-chart');
    }
}
