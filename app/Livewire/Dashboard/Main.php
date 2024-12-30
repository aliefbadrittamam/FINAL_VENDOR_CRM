<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\CustomerInteraction;
use Carbon\Carbon;

class Main extends Component
{
    // Define properties untuk filter
    public $selectedVendor = 'All Vendor';
    public $startDate;
    public $endDate;

    // Tambahkan property untuk realtime update
    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount()
    {
        // Set default date ke bulan ini
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }

    // Method ini akan dipanggil setiap kali filter berubah
    public function updatedStartDate()
    {
        $this->dispatch('refreshDashboard');
    }

    public function updatedEndDate()
    {
        $this->dispatch('refreshDashboard');
    }

    public function updatedSelectedVendor()
    {
        $this->dispatch('refreshDashboard');
    }

   public function getFilteredData()
{
    $query = Project::query()
            ->join('vendors', 'projects.vendor_id', '=', 'vendors.vendor_id'); // Sesuaikan dengan struktur database
    
    if ($this->startDate && $this->endDate) {
        $query->whereBetween('project_duration_start', [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ]);
    }

    // Filter berdasarkan nama vendor
    if ($this->selectedVendor !== '' && $this->selectedVendor !== null && $this->selectedVendor !== 'All Vendors') {
        $query->where('vendors.vendor_name', $this->selectedVendor);
    }

    return $query->select('projects.*'); 


}
  public function getChartData()
{
    $query = Project::query()
        ->join('vendors', 'projects.vendor_id', '=', 'vendors.vendor_id')
        ->when($this->startDate && $this->endDate, function($q) {
            return $q->whereBetween('project_duration_start', [
                Carbon::parse($this->startDate),
                Carbon::parse($this->endDate)
            ]);
        })
        ->when($this->selectedVendor !== '' && $this->selectedVendor !== 'All Vendors', function($q) {
            return $q->where('vendors.vendor_name', $this->selectedVendor);
        });

    $projects = (clone $query)
        ->selectRaw('MONTH(project_duration_start) as month_num, DATE_FORMAT(project_duration_start, "%b") as month, COUNT(*) as count')
        ->groupBy('month_num', 'month')
        ->orderBy('month_num')
        ->get();

    $revenue = $query
        ->selectRaw('MONTH(project_duration_start) as month_num, DATE_FORMAT(project_duration_start, "%b") as month, SUM(project_value) as total')
        ->groupBy('month_num', 'month')
        ->orderBy('month_num')
        ->get();

    $timelineData = $query->get()->map(function($project) {
        $today = now();
        $start = Carbon::parse($project->project_duration_start);
        $end = Carbon::parse($project->project_duration_end);
        
        return [
            'x' => $project->project_header,
            'y' => [
                strtotime($start) * 1000, 
                strtotime($end) * 1000
            ],
            'fillColor' => $today->between($start, $end) ? '#3B82F6' : 
                          ($today->lt($start) ? '#FCD34D' : '#10B981')
        ];
    });

    return [
        'projectLabels' => $projects->pluck('month'),
        'projectData' => $projects->pluck('count'),
        'revenueLabels' => $revenue->pluck('month'),
        'revenueData' => $revenue->pluck('total'),
        'timelineData' => $timelineData
    ];
}
public function render()
{
    $filteredQuery = $this->getFilteredData();
    $chartData = $this->getChartData();

    return view('livewire.dashboard.main', [
        'totalProjects' => $filteredQuery->count(),
        'totalVendors' => Vendor::count(),
        'revenue' => $filteredQuery->sum('project_value'),
        'pendingProjects' => Project::whereDate('project_duration_start', '>', now())->count(),
        'recentProjects' => $filteredQuery->with(['vendor', 'customer'])
            ->orderBy('project_duration_start', 'desc')
            ->limit(5)
            ->get(),
        'recentInteractions' => CustomerInteraction::with(['customer'])
            ->orderBy('interaction_date', 'desc')
            ->take(5)
            ->get(),
        'vendors' => Vendor::pluck('vendor_name'),
        'chartData' => $chartData  // Passing chartData sebagai satu variabel
    ]);
}
 
  
}