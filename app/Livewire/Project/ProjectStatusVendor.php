<?php

namespace App\Livewire\Project;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProjectStatusVendor extends Component
{
    use WithPagination;

    public $search = '';
    public $customerFilter = '';
    public $statusFilter = '';
    public $dateRangeFilter = '';
    public $sortField = 'project_duration_start';
    public $sortDirection = 'asc';
    public $selectedProject = null;
    public $showDetailModal = false;

    protected $queryString = [
        'search', 
        'customerFilter', 
        'statusFilter', 
        'dateRangeFilter'
    ];

    private function getVendorId()
    {
        return Vendor::where('user_id', Auth::id())->first()->vendor_id;
    }

    public function getProjectMetrics()
    {
        $vendorId = $this->getVendorId();
        $projects = Project::where('vendor_id', $vendorId)->get();
        $totalProjects = $projects->count();
        $notStarted = 0;
        $inProgress = 0;
        $completed = 0;
        $onTrack = 0;
        $delayed = 0;
        $totalValue = 0;

        $today = now();

        foreach ($projects as $project) {
            $startDate = Carbon::parse($project->project_duration_start);
            $endDate = Carbon::parse($project->project_duration_end);

            $totalValue += $project->project_value;

            if ($today < $startDate) {
                $notStarted++;
            } elseif ($today > $endDate) {
                $completed++;
            } else {
                $inProgress++;
                $totalDays = $startDate->diffInDays($endDate) ?: 1;
                $daysElapsed = $startDate->diffInDays($today);
                $expectedProgress = ($daysElapsed / $totalDays) * 100;
                
                if ($expectedProgress > 75) {
                    $delayed++;
                } else {
                    $onTrack++;
                }
            }
        }

        return [
            'total' => $totalProjects,
            'in_progress' => $inProgress,
            'completed' => $completed,
            'on_track' => $onTrack,
            'delayed' => $delayed,
            'total_value' => $totalValue,
            'completion_rate' => $totalProjects > 0 ? ($completed / $totalProjects) * 100 : 0,
            'on_track_rate' => $inProgress > 0 ? ($onTrack / $inProgress) * 100 : 0
        ];
    }

    public function getProjectStatus($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $today = now();

        $totalDays = $startDate->diffInDays($endDate) ?: 1;
        $elapsedDays = $startDate->diffInDays($today);
        $progress = min(100, max(0, ($elapsedDays / $totalDays) * 100));

        if ($today < $startDate) {
            return [
                'status' => 'Pending',
                'color' => 'gray',
                'progress' => 0,
                'badge_color' => 'bg-gray-100 text-gray-800'
            ];
        } elseif ($today > $endDate) {
            return [
                'status' => 'Selesai',
                'color' => 'green',
                'progress' => 100,
                'badge_color' => 'bg-green-100 text-green-800'
            ];
        } else {
            $isOnTrack = ($progress >= ($elapsedDays / $totalDays) * 100);
            return [
                'status' => 'Dalam Pengerjaan',
                'color' => $isOnTrack ? 'blue' : 'yellow',
                'progress' => $progress,
                'badge_color' => $isOnTrack ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'
            ];
        }
    }

    public function getDaysRemaining($project)
    {
        $endDate = Carbon::parse($project->project_duration_end);
        $today = now();
    
        if ($today > $endDate) {
            $daysAgo = round($today->diffInDays($endDate));
            
            if ($daysAgo == 0) {
                return ['text' => 'Selesai hari ini', 'class' => 'text-green-600'];
            } elseif ($daysAgo == 1) {
                return ['text' => 'Selesai kemarin', 'class' => 'text-green-600'];
            } else {
                return ['text' => "Selesai {$daysAgo} hari yang lalu", 'class' => 'text-green-600'];
            }
        }
        
        $daysLeft = round($today->diffInDays($endDate));
        
        if ($daysLeft <= 7) {
            return ['text' => $daysLeft . ' hari tersisa', 'class' => 'text-yellow-600'];
        }
        
        return ['text' => $daysLeft . ' hari tersisa', 'class' => 'text-green-600'];
    }

    public function showProjectDetail($projectId)
    {
        $this->selectedProject = Project::with(['customer'])
            ->findOrFail($projectId);
        $this->showDetailModal = true;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $vendorId = $this->getVendorId();

        $query = Project::with(['customer'])
            ->where('vendor_id', $vendorId)
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->where('project_header', 'like', "%{$this->search}%")
                          ->orWhere('project_detail', 'like', "%{$this->search}%");
                });
            })
            ->when($this->customerFilter, function($q) {
                $q->where('customer_id', $this->customerFilter);
            })
            ->when($this->statusFilter, function($q) {
                $today = now();
                switch($this->statusFilter) {
                    case 'Pending':
                        $q->where('project_duration_start', '>', $today);
                        break;
                    case 'Dalam Pengerjaan':
                        $q->where('project_duration_start', '<=', $today)
                          ->where('project_duration_end', '>=', $today);
                        break;
                    case 'Selesai':
                        $q->where('project_duration_end', '<', $today);
                        break;
                }
            })
            ->when($this->dateRangeFilter, function($q) {
                $selectedDate = Carbon::parse($this->dateRangeFilter);
                $q->where(function($query) use ($selectedDate) {
                    $query->whereDate('project_duration_start', '<=', $selectedDate)
                          ->whereDate('project_duration_end', '>=', $selectedDate);
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $customers = Customer::whereHas('projects', function($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })->orderBy('customer_name')->get();

        return view('livewire.project.project-status-vendor', [
            'projects' => $query->paginate(10),
            'customers' => $customers,
            'metrics' => $this->getProjectMetrics()
        ]);
    }
}