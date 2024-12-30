<?php

namespace App\Livewire\Project;

use Livewire\Component;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\WithPagination;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\DB;


class ProjectListCustomer extends Component
{
    use WithPagination;

    public $showDetailModal = false;
    public $selectedProject = null;
    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $sortField = 'project_duration_start';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search',
        'statusFilter',
        'dateFilter'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewDetail($projectId)
    {
        $this->selectedProject = Project::with([
            'vendor',
            'customer',
            'products',
            'priceQuotations' => function($query) {
                $query->latest();
            },
            'priceQuotations.vendor'
        ])->findOrFail($projectId);

        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedProject = null;
    }

    public function getProjectStatus($project)
    {
        $today = now();
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);

        if ($today < $startDate) {
            return 'backlog';
        } elseif ($today > $endDate) {
            return 'completed';
        } elseif ($project->status === 'cancelled') {
            return 'cancelled';
        } elseif ($today->diffInDays($endDate) <= 7) {
            return 'review';
        } else {
            return 'in_progress';
        }
    }

    public function getProjectProgress($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $today = now();

        if ($today < $startDate) {
            return 0;
        }

        if ($today > $endDate) {
            return 100;
        }

        $totalDays = $startDate->diffInDays($endDate) ?: 1;
        $daysElapsed = $startDate->diffInDays($today);
        return min(100, round(($daysElapsed / $totalDays) * 100));
    }

    protected function filterProjects($projects)
    {
        return $projects->filter(function ($project) {
            $matchesSearch = true;
            if ($this->search) {
                $searchLower = strtolower($this->search);
                $matchesSearch = 
                    str_contains(strtolower($project->project_header), $searchLower) ||
                    str_contains(strtolower($project->project_detail), $searchLower) ||
                    str_contains(strtolower($project->vendor->vendor_name), $searchLower);
            }

            $matchesStatus = !$this->statusFilter || $project->status === $this->statusFilter;

            $matchesDate = true;
            if ($this->dateFilter) {
                $date = Carbon::parse($this->dateFilter);
                $projectStart = Carbon::parse($project->project_duration_start);
                $projectEnd = Carbon::parse($project->project_duration_end);
                $matchesDate = $date->between($projectStart, $projectEnd);
            }

            return $matchesSearch && $matchesStatus && $matchesDate;
        });
    }
    public function createPurchase($projectId)
    {
        try {
            $project = Project::with(['products'])->findOrFail($projectId);
            
            // Check if project has vendor
            if (!$project->vendor) {
                $this->dispatch('flash-message', 'Cannot create purchase: Project has no vendor assigned.');
                return;
            }
            
            // Create Purchase
            $purchase = Purchase::create([
                'vendor_id' => $project->vendor_id,
                'user_id' => Auth::id(),
                'project_id' => $project->project_id,
                'total_amount' => $project->project_value,
                'purchase_date' => now(),
                'status' => 'Pending'
            ]);
    
            // Create Purchase Details dengan updated_at
            foreach ($project->products as $product) {
                DB::table('purchase_details')->insert([
                    'purchase_id' => $purchase->purchase_id,
                    'product_id' => $product->product_id,
                    'quantity' => $product->pivot->quantity,
                    'subtotal' => $product->pivot->subtotal,
                    'updated_at' => now()
                ]);
            }
    
            $this->dispatch('flash-message', 'Purchase created successfully!');
        } catch (\Exception $e) {
            $this->dispatch('flash-message', 'Error creating purchase: ' . $e->getMessage());
        }
    }
    
    protected function checkPurchaseStatus($project)
    {
        return Purchase::where('project_id', $project->project_id)
            ->latest()
            ->first();
    }
    public function render()
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return view('livewire.project.project-list-customer', [
                'projectGroups' => [
                    'in_progress' => collect(),
                    'completed' => collect(),
                    'cancelled' => collect(),
                    'backlog' => collect(),
                    'review' => collect()
                ],
                'customer' => null,
                'hasSearchResults' => false
            ]);
        }

        $allProjects = Project::where('customer_id', $customer->customer_id)
            ->with(['vendor', 'products'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->get()
            ->map(function ($project) {
                $project->status = $this->getProjectStatus($project);
                $project->progress = $this->getProjectProgress($project);
                return $project;
            });

        $filteredProjects = $this->filterProjects($allProjects);

        $projectGroups = [
            'in_progress' => $filteredProjects->where('status', 'in_progress'),
            'completed' => $filteredProjects->where('status', 'completed'),
            'cancelled' => $filteredProjects->where('status', 'cancelled'),
            'backlog' => $filteredProjects->where('status', 'backlog'),
            'review' => $filteredProjects->where('status', 'review')
        ];

        return view('livewire.project.project-list-customer', [
            'projectGroups' => $projectGroups,
            'hasSearchResults' => $filteredProjects->isNotEmpty(),
            'customer' => $customer
        ]);
    }
}