<?php

namespace App\Livewire\Marketing;

use App\Models\Lead;
use App\Models\Project;
use App\Models\MarketingDetail;
use App\Models\Campaign;
use Livewire\Component;
use Illuminate\Support\Facades\DB; // Untuk DB query

class Analysis extends Component
{
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
        // Query untuk menghitung total data sesuai filter yang ada
        $query = MarketingDetail::query();

        // Filter berdasarkan tanggal dan status jika ada
        if ($this->filters['date_from']) {
            $query->where('send_date', '>=', $this->filters['date_from']);
        }

        if ($this->filters['date_to']) {
            $query->where('send_date', '<=', $this->filters['date_to']);
        }

        if ($this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }


        $total_send = $query->count();
        $total_delivered = $query->where('status', 'delivered')->count();
        $total_send_customer = MarketingDetail::distinct('customer_id')->count('customer_id');
        // Return data ke view
        return view('livewire.marketing.analysis', [
            'total_send' => $total_send,
            'total_delivered' => $total_delivered,
            'total_send_customer' => $total_send_customer,
        ]);
    }
}

