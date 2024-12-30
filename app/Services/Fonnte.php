<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\MarketingCampaign;
use App\Models\MarketingDetail;
use App\Models\Lead;
use Carbon\Carbon;

class Fonnte
{
    protected $apiToken;

    public function __construct()
    {
        $this->apiToken = env('FONNTE_API_TOKEN');
    }

    /**
     * Persiapkan target pengiriman berdasarkan customer yang dipilih.
     */
    public function prepareTarget($selectedCustomerIds, $nameIncluded)
    {
        $dataAll = [];

        foreach ($selectedCustomerIds as $id) {
            $customer = Customer::find($id);
            if ($customer) {
                if ($nameIncluded) {
                    $data = "{$customer->customer_phone}|{$customer->customer_name}";
                } else {
                    $data = $customer->customer_phone;
                }
                $dataAll[] = $data;
            }
        }

        return implode(',', $dataAll);
    }

    /**
     * Kirim request ke API Fonnte.
     */
    public function send($url, $postData)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->apiToken,
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    /**
     * Simpan detail pengiriman ke database.
     */
    public function saveDetail($response, $selectedCustomerIds, $campaignId, $postData, $schedule)
    {
        $sendId = $response->id;
        $campaign = MarketingCampaign::findOrFail($campaignId);
        $campaignName = $campaign->campaign_name;
        $sendDate = now()->toDateTimeString();
        $status = $response->process;
        $formattedDatetime = null;
        if($schedule){
            $formattedDatetime = Carbon::createFromFormat('Y-m-d\TH:i', $schedule)->format('Y-m-d H:i:s');
        }
        
        
        foreach ($selectedCustomerIds as $index => $id) {
            $customer = Customer::find($id);

            if ($customer) {
                $customerName = $customer->customer_name;
                $customerId = $customer->customer_id;
                $customerPhone = $customer->customer_phone;
                $currentSendId = $sendId[$index];
                MarketingDetail::create([
                    'campaign_id' => $campaignId,
                    'campaign_name' => $campaignName,
                    'send_id' => $currentSendId,
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'customer_id' => $customerId,
                    'send_date' => $sendDate,
                    'scheduled_date' => $formattedDatetime,
                    'status' => $status,
                ]);
                $massageCount = Lead::where('customer_id', $customerId)->value('message_count');
                Lead::where('customer_id', $customerId)->update([ 
                    'message_count' => $massageCount + 1, 
                ]);
        }}
    }
}
