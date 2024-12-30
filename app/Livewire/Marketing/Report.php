<?php

namespace App\Livewire\Marketing;
use App\Models\Sales;
use App\Models\MarketingDetail;
use Livewire\Component;
use Carbon\Carbon;
class Report extends Component
{

    public function render()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Campaigns data
        $campaigns = MarketingDetail::with('campaign') // Memuat relasi ke tabel campaign
            ->whereBetween('send_date', [$startDate, $endDate]) // Filter send_date bulan ini
            ->selectRaw('campaign_id, 
                        COUNT(CASE WHEN state = "delivered" THEN 1 END) AS total_delivered, 
                        COUNT(CASE WHEN state = "sent" THEN 1 END) AS total_sent')
            ->groupBy('campaign_id')
            ->get();
        $customers = MarketingDetail::with('customer') // Memuat relasi ke tabel customer
            ->whereBetween('send_date', [$startDate, $endDate]) // Filter send
            ->whereBetween('send_date', [$startDate, $endDate]) // Filter send_date bulan ini
            ->selectRaw('customer_id, 
                        COUNT(CASE WHEN state = "delivered" THEN 1 END) AS total_delivered, 
                        COUNT(CASE WHEN state = "sent" THEN 1 END) AS total_sent')
            ->groupBy('customer_id')
            ->get();
        $sales = Sales::with('customer')->whereBetween('created_at', [$startDate, $endDate]) // Filter created_at bulan ini
            ->get();
        $totals = MarketingDetail::whereBetween('send_date', [$startDate, $endDate])
            ->selectRaw('
                COUNT(CASE WHEN state = "delivered" THEN 1 END) AS total_delivered, 
                COUNT(CASE WHEN state = "sent" THEN 1 END) AS total_sent
            ')
            ->first();
        
        return view('livewire.marketing.report', [
            'campaigns' => $campaigns,
            'customers' => $customers,
            'sales' => $sales,
            'date' => date('m/d/Y'),
            'total'=> $totals,
        ]);
    }
}
