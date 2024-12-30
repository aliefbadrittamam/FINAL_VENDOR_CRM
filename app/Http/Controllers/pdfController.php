<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\MarketingDetail;

class pdfController extends Controller
{
    public function generate()
    {
        $date = date('m-d-Y');
        
        $pdf = Pdf::loadView('marketing.marketing-report');
        return $pdf->download('marketing ' . $date . '.pdf');
        
    }
}
