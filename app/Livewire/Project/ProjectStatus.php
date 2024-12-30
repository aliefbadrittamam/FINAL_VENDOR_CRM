<?php

namespace App\Livewire\Project;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class ProjectStatus extends Component
{
    use WithPagination;

    public $search = '';
    public $vendorFilter = '';
    public $customerFilter = '';
    public $statusFilter = '';
    public $dateRangeFilter = '';
    public $sortField = 'project_duration_start';
    public $sortDirection = 'asc';
    public $selectedProject = null;
    public $showDetailModal = false;
    public $startDateFilter = '';
public $endDateFilter = '';

    protected $queryString = ['search', 'vendorFilter', 'customerFilter', 'statusFilter', 'dateRangeFilter','startDateFilter' => ['except' => ''],
    'endDateFilter' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
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

    public function showProjectDetail($projectId)
    {
        $this->selectedProject = Project::with(['vendor', 'customer'])
            ->findOrFail($projectId);
        $this->showDetailModal = true;
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
            'status' => 'Not Started',
            'color' => 'gray',
            'progress' => 0,
            'days_remaining' => $today->diffInDays($startDate) . ' days until start',
            'badge_color' => 'bg-gray-100 text-gray-800'
        ];
    } elseif ($today > $endDate) {
        $delay = round($endDate->diffInDays($today));
        return [
            'status' => 'Completed',
            'color' => 'green', // Ubah warna menjadi hijau
            'progress' => 100,
            'days_remaining' => $delay > 0 ? "Completed {$delay} days ago" : 'Completed on time',
            'badge_color' => 'bg-green-100 text-green-800'
        ];
    } else {
        $daysLeft = round($today->diffInDays($endDate));
        $isOnTrack = ($progress >= ($elapsedDays / $totalDays) * 100);
        return [
            'status' => 'In Progress',
            'color' => $isOnTrack ? 'blue' : 'yellow',
            'progress' => $progress,
            'days_remaining' => $daysLeft . ' days remaining',
            'badge_color' => $isOnTrack ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'
        ];
    }
}

public function getProjectMetrics()
{
    $projects = Project::all();
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

        // Hitung status proyek
        if ($today < $startDate) {
            // Proyek belum dimulai
            $notStarted++;
            $delayed++; // Proyek yang belum dimulai dianggap delayed
        } elseif ($today > $endDate) {
            // Proyek sudah lewat waktu
            $completed++;
            $delayed++;
        } else {
            // Proyek sedang berjalan
            $inProgress++;
            
            // Hitung apakah on track
            $totalDays = $startDate->diffInDays($endDate) ?: 1;
            $daysElapsed = $startDate->diffInDays($today);
            $expectedProgress = ($daysElapsed / $totalDays) * 100;
            
            if ($expectedProgress > 75) {
                $delayed++;
            } else {
                $onTrack++;
            }
        }

        $totalValue += $project->project_value;
    }

    return [
        'total' => $totalProjects,
        'not_started' => $notStarted,
        'in_progress' => $inProgress,
        'completed' => $completed,
        'on_track' => $onTrack,
        'delayed' => $delayed,
        'total_value' => $totalValue,
        'completion_rate' => $totalProjects > 0 ? ($completed / $totalProjects) * 100 : 0,
        'on_track_rate' => $inProgress > 0 ? ($onTrack / $inProgress) * 100 : 0
    ];
}

    public function getDaysRemaining($project)
    {
        $endDate = Carbon::parse($project->project_duration_end);
        $today = now();
    
        // Jika proyek sudah selesai
        if ($today > $endDate) {
            $daysAgo = round($today->diffInDays($endDate));
            
            // Variasi pesan berdasarkan jumlah hari
            if ($daysAgo == 0) {
                return ['text' => 'Completed today', 'class' => 'text-green-600'];
            } elseif ($daysAgo == 1) {
                return ['text' => 'Completed yesterday', 'class' => 'text-green-600'];
            } else {
                return ['text' => "Completed {$daysAgo} days ago", 'class' => 'text-green-600'];
            }
        }
        
        // Untuk proyek yang masih berjalan
        $daysLeft = round($today->diffInDays($endDate));
        
        if ($daysLeft <= 7) {
            return ['text' => $daysLeft . ' days left', 'class' => 'text-yellow-600'];
        }
        
        return ['text' => $daysLeft . ' days remaining', 'class' => 'text-green-600'];
    }

    public function render()
    {
        $query = Project::with(['vendor', 'customer'])
        // ... existing filters ...
        ->when($this->startDateFilter && $this->endDateFilter, function($q) {
            $startDate = Carbon::parse($this->startDateFilter);
            $endDate = Carbon::parse($this->endDateFilter);
            
            $q->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('project_duration_start', [$startDate, $endDate])
                      ->orWhereBetween('project_duration_end', [$startDate, $endDate])
                      ->orWhere(function($subQuery) use ($startDate, $endDate) {
                          $subQuery->where('project_duration_start', '<=', $startDate)
                                   ->where('project_duration_end', '>=', $endDate);
                      });
            });
        })
            ->when($this->statusFilter, function($q) {
                $today = now();
                switch($this->statusFilter) {
                    case 'not_started':
                        $q->where('project_duration_start', '>', $today);
                        break;
                    case 'in_progress':
                        $q->where('project_duration_start', '<=', $today)
                          ->where('project_duration_end', '>=', $today);
                        break;
                    case 'completed':
                        $q->where('project_duration_end', '<', $today);
                        break;
                    case 'delayed':
                        $q->where(function($query) use ($today) {
                            // Proyek yang belum dimulai
                            $query->where('project_duration_start', '>', $today)
                                  // Atau proyek yang sudah lewat waktu
                                  ->orWhere('project_duration_end', '<', $today);
                        });
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

        return view('livewire.project.project-status', [
            'projects' => $query->paginate(10),
            'vendors' => Vendor::orderBy('vendor_name')->get(),
            'customers' => Customer::orderBy('customer_name')->get(),
            'metrics' => $this->getProjectMetrics()
        ]);
    }

    public function resetDateFilters()
{
    $this->reset(['startDateFilter', 'endDateFilter']);
    $this->resetPage();
}
}