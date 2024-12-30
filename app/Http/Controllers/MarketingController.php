<?php

namespace App\Http\Controllers;
use App\Models\MarketingCampaign;
use App\Models\MarketingDetail;
use App\Models\ScheduledHistory;
use App\Models\Customer;
use App\Models\Lead;
use App\Services\Fonnte;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    protected $fonnte;

    public function __construct(Fonnte $fonnte)
    {
        $this->fonnte = $fonnte;
    }
    // public function index()
  
    public function whatsapp()
    {
        return view('marketing.index');
    }
    public function analysis()
    {
        return view('marketing.analysis');
    }
    public function historyShow()
    {
        return view('marketing.message-history');
    }
    public function sendCustomer($CampaignId)
    {
        return view('marketing.campaign-send', [
            'campaign' => $CampaignId
        ]);
    }
    
    public function send(Request $request)
    {
        $campaignId = $request->input('idCampaign');
        $campaign = MarketingCampaign::findOrFail($campaignId);
        $description = $campaign->description;
        $nameIncluded = $campaign->name_included;
        $schedule = null;
        $scheduleDate = null;

        // Konversi datetime ke UNIX timestamp jika ada
        if ($request->has('datetime')) {
            $request->validate(['datetime' => 'date']);
            $scheduleDate = $request->input('datetime');
            $schedule = strtotime($request->input('datetime')) - 25200;
        }

        // Validasi pelanggan yang dipilih
        if (!session('selected_customers')) {
            return response()->json([
                'status' => 'error',
                'message' => 'No customers selected. Please select customers first!',
            ], 400);
        }

        $selectedCustomerIds = session('selected_customers');
        $target = $this->fonnte->prepareTarget($selectedCustomerIds, $nameIncluded);

        $postData = [
            'target' => $target,
            'message' => $description,
            'countryCode' => '62',
        ];

        if ($schedule) {
            $postData['schedule'] = (string)$schedule;
        }

        $response = $this->fonnte->send('https://api.fonnte.com/send', $postData);
    
        $this->fonnte->saveDetail($response, $selectedCustomerIds, $campaignId, $postData, $scheduleDate);


        session()->forget('selected_customers');

        return redirect()->route('marketing.whatsapp')->with('status', 'Message has been sent!');
    }

    public function saveSelectedCustomers(Request $request)
    {
        // Periksa jika session 'selected_customers' sudah ada, lalu hapus
        if (session()->has('selected_customers')) {
            session()->forget('selected_customers');
        }
    
        $selectedCustomers = $request->input('selected_customers');
 
        session(['selected_customers' => $selectedCustomers]);
    
        return redirect()->back()->with('message', 'Customers saved');
    }

    public function detailShow($campaignId)
    {
        // Data dengan scheduled_date = null
        $unscheduledDetails = MarketingDetail::where('campaign_id', $campaignId)
        ->whereNull('scheduled_date')
        ->get();

        // Data dengan scheduled_date != null
        $scheduledDetails = MarketingDetail::where('campaign_id', $campaignId)
        ->whereNotNull('scheduled_date')
        ->get();

        $campaign = MarketingCampaign::findOrFail($campaignId);

        return view('marketing.marketing-detail', [
            'campaign'=>$campaign,
            'unscheduledDetails' => $unscheduledDetails, 
            'scheduledDetails' => $scheduledDetails, 
        ]);
    }
    
}
