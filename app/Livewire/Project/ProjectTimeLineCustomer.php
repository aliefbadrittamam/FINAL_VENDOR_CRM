<?php

namespace App\Livewire\Project;

use Livewire\Component;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProjectTimeLineCustomer extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $search = '';
    public $statusFilter = 'all';

    protected $queryString = [
        'selectedMonth',
        'selectedYear',
        'statusFilter'
    ];

    public function mount()
    {
        $this->selectedMonth = request('selectedMonth', now()->format('m'));
        $this->selectedYear = request('selectedYear', now()->format('Y'));
    }

    private function getCustomerId()
    {
        return Customer::where('user_id', Auth::id())->first()->customer_id;
    }

    public function calculateProjectStatus($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $today = now();

        if ($today < $startDate) {
            return 'Pending';
        } 
        
        if ($today > $endDate) {
            return 'Selesai';
        }

        return 'Dalam Pengerjaan';
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
        
        $customerId = $this->getCustomerId();

        // Generate days array
        $daysInMonth = $startDate->daysInMonth;
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

        $projects = Project::with(['vendor'])
            ->where('customer_id', $customerId)
            ->when($this->search, function($query) {
                $query->where('project_header', 'like', '%' . $this->search . '%')
                      ->orWhere('project_detail', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function($query) {
                $now = Carbon::now();
                switch($this->statusFilter) {
                    case 'Pending':
                        $query->where('project_duration_start', '>', $now);
                        break;
                    case 'Dalam Pengerjaan':
                        $query->where('project_duration_start', '<=', $now)
                              ->where('project_duration_end', '>', $now);
                        break;
                    case 'Selesai':
                        $query->where('project_duration_end', '<=', $now);
                        break;
                }
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

        return view('livewire.project.project-time-line-customer', [
            'projects' => $projects,
            'days' => $days,
            'currentMonth' => Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->format('F Y')
        ]);
    }
}