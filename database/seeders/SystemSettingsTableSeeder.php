<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsTableSeeder extends Seeder
{
    public function run() 
    {
        DB::table('system_settings')->insert([
            ['parameter_name' => 'notification_limit', 'parameter_value' => '24'],
            ['parameter_name' => 'currency', 'parameter_value' => 'IDR'],
        ]);
    }  
}
