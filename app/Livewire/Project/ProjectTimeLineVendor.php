<?php

namespace App\Livewire\Project;

use Livewire\Component;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProjectTimeLineVendor extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $search = '';
    public $customerFilter = '';
    public $statusFilter = 'all';
    public $vendors = '';

    protected $queryString = [
        'selectedMonth',
        'selectedYear',
        'customerFilter',
        'statusFilter'
    ];

    public function mount()
    {
        $this->selectedMonth = request('selectedMonth', now()->format('m'));
        $this->selectedYear = request('selectedYear', now()->format('Y'));
    }

    public function calculateProjectStatus($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $today = now();

        // Project belum dimulai
        if ($today < $startDate) {
            return 'Pending';
        }
        
        // Project sudah selesai
        if ($today > $endDate) {
            return 'Selesai';
        }

        // Project sedang berjalan
        return 'Dalam Pengerjaan';
    }

    private function getVendorId()
    {
        return Vendor::where('user_id', Auth::id())->first()->vendor_id;
    }

    public function prevMonth()
    {
        $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->format('m');
        $this->selectedYear = $date->format('Y');
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        $this->selectedMonth = $date->format('m');
        $this->selectedYear = $date->format('Y');
    }

    public function resetTimeline()
    {
        $this->selectedMonth = now()->format('m');
        $this->selectedYear = now()->format('Y');
    }

    public function render()
{
    $startDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
    $endDate = $startDate->copy()->endOfMonth();
    
    $vendorId = $this->getVendorId();
    
    // Dapatkan jumlah hari dalam bulan
    $daysInMonth = $startDate->daysInMonth;

    // Generate days array dengan benar
    $days = [];
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $currentDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, $day);
        $days[] = [
            'date' => $day,
            'dayName' => $currentDate->format('D'),
            'isToday' => $currentDate->isToday(),
            'isWeekend' => $currentDate->isWeekend(),
            'fullDate' => $currentDate->format('Y-m-d')
        ];
    }

    $projects = Project::with(['customer'])
        ->where('vendor_id', $vendorId)
        ->when($this->search, function($query) {
            $query->where('project_header', 'like', '%' . $this->search . '%')
                  ->orWhere('project_detail', 'like', '%' . $this->search . '%');
        })
        ->when($this->customerFilter, function($query) {
            $query->where('customer_id', $this->customerFilter);
        })
        ->where(function($query) use ($startDate, $endDate) {
            $query->whereBetween('project_duration_start', [$startDate, $endDate])
                  ->orWhereBetween('project_duration_end', [$startDate, $endDate])
                  ->orWhere(function($q) use ($startDate, $endDate) {
                      $q->where('project_duration_start', '<=', $startDate)
                        ->where('project_duration_end', '>=', $endDate);
                  });
        })
        ->orderBy('project_duration_start')
        ->get();

    $customers = Customer::whereHas('projects', function($query) use ($vendorId) {
        $query->where('vendor_id', $vendorId);
    })->get();

    return view('livewire.project.project-time-line-vendor', [
        'projects' => $projects,
        'days' => $days,
        'customers' => $customers,
        'currentMonth' => Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->format('F Y')
    ]);
}
}