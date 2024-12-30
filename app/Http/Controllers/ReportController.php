<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function marketing(){
        return view('marketing.report-page');
    }
}
