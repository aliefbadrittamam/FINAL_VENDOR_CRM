<?php

namespace App\Livewire\Project;

use Livewire\Component;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class ProjectTimeline extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $search = '';
    public $vendorFilter = '';
    public $customerFilter = '';
    public $statusFilter = 'all';

    protected $queryString = [
        'selectedMonth',
        'selectedYear',
        'vendorFilter',
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

        if ($today < $startDate) {
            return 'Not Started';
        } elseif ($today > $endDate) {
            return 'Completed';
        } else {
            $totalDays = $startDate->diffInDays($endDate);
            $daysElapsed = $startDate->diffInDays($today);
            $progress = ($daysElapsed / max(1, $totalDays)) * 100;
            return [
                'status' => 'In Progress',
                'progress' => min(100, max(0, $progress))
            ];
        }
    }

    public function calculateTimelinePosition($date)
    {
        $startOfMonth = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $projectDate = Carbon::parse($date);
        
        if ($projectDate->month != $this->selectedMonth || $projectDate->year != $this->selectedYear) {
            return null;
        }

        return (($projectDate->day - 1) / $daysInMonth) * 100;
    }

    public function getDurationInDays($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        return $startDate->diffInDays($endDate);
    }

    public function getTimelineWidth($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $startOfMonth = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Jika project diluar bulan yang dipilih
        if ($endDate < $startOfMonth || $startDate > $endOfMonth) {
            return 0;
        }

        // Sesuaikan tanggal jika project melewati batas bulan
        $projectStart = $startDate < $startOfMonth ? $startOfMonth : $startDate;
        $projectEnd = $endDate > $endOfMonth ? $endOfMonth : $endDate;

        $width = ($projectEnd->day - $projectStart->day + 1) / $startOfMonth->daysInMonth * 100;
        return min(100, max(0, $width));
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

        $projects = Project::with(['vendor', 'customer'])
            ->when($this->search, function($query) {
                $query->where('project_header', 'like', '%' . $this->search . '%')
                      ->orWhere('project_detail', 'like', '%' . $this->search . '%');
            })
            ->when($this->vendorFilter, function($query) {
                $query->where('vendor_id', $this->vendorFilter);
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

        $days = collect(range(1, $startDate->daysInMonth))->map(function($day) use ($startDate) {
            $date = $startDate->copy()->addDays($day - 1);
            return [
                'date' => $day,
                'dayName' => $date->format('D'),
                'isToday' => $date->isToday(),
                'isWeekend' => $date->isWeekend(),
                'fullDate' => $date->format('Y-m-d')
            ];
        });

        return view('livewire.project.project-timeline', [
            'projects' => $projects,
            'days' => $days,
            'vendors' => Vendor::all(),
            'customers' => Customer::all(),
            'currentMonth' => Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->format('F Y')
        ]);
    }
}