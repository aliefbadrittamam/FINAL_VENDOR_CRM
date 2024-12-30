<?php

namespace App\Livewire\Marketing;

use Livewire\Component;
use App\Models\MarketingDetail;

class CampaignStatusChart extends Component
{
    public $filters = [];

    public function mount($filters)
    {
        $this->filters = $filters;
    }

    public function render()
    {
        $query = MarketingDetail::query();

        if ($this->filters['date_from']) {
            $query->where('send_date', '>=', $this->filters['date_from']);
        }
        if ($this->filters['date_to']) {
            $query->where('send_date', '<=', $this->filters['date_to']);
        }

        $totalSend = $query->count();
        $totalDelivered = $query->clone()->where('state', 'delivered')->count();

        $this->dispatchBrowserEvent('updateCampaignChart', [
            'total_send' => $totalSend,
            'total_delivered' => $totalDelivered,
        ]);

        return view('livewire.marketing.campaign-status-chart');
    }
}


