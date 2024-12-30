<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Purchase;
use App\Models\CustomerInteraction;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectListVendor extends Component
{
    use WithPagination;

    // Properties for modals and filters
    public $purchaseStatus = '';

    public $showDetailModal = false;
    public $showUpdateModal = false;
    public $selectedProject = null;
    public $search = '';
    public $customerFilter = '';
    public $dateFilter = '';
    public $sortField = 'project_duration_start';
    public $sortDirection = 'desc';

    // Form properties
    public $project_id;
    public $project_header;
    public $project_detail;
    public $project_duration_start;
    public $project_duration_end;
    public $notes;
    public $showPurchaseModal = false;
    public $selectedPurchase = null;
    protected $rules = [
        'project_header' => 'required|string|max:100',
        'project_duration_start' => 'required|date',
        'project_duration_end' => 'required|date|after:project_duration_start',
        'project_detail' => 'required|string',
        'notes' => 'nullable|string'
    ];

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCustomerFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    // Get current vendor
    protected function getCurrentVendor()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();
        if (!$vendor) {
            $this->dispatch('error', 'You do not have vendor access.');
            return null;
        }
        return $vendor;
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function checkPurchaseStatus($project)
{
    try {
        // Cari purchase terkait dengan project
        $purchase = Purchase::where('project_id', $project->project_id)
            ->first();

        return $purchase;
    } catch (\Exception $e) {
        // Tangani error jika terjadi
        $this->dispatch('error', 'Error checking purchase status: ' . $e->getMessage());
        return null;
    }
}
    public function viewPurchase($projectId)
    {
        $vendor = $this->getCurrentVendor();
        if (!$vendor) {
            return;
        }

        try {
            // Cari purchase terkait dengan project
            $purchase = Purchase::whereHas('project', function($query) use ($projectId, $vendor) {
                $query->where('project_id', $projectId)
                      ->where('vendor_id', $vendor->vendor_id);
            })->first();

            if (!$purchase) {
                $this->dispatch('error', 'Purchase not found for this project.');
                return;
            }

            $this->selectedPurchase = $purchase;
            $this->showPurchaseModal = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Error loading purchase: ' . $e->getMessage());
        }
    }
    public function openUpdateModal($projectId)
    {
        $this->resetValidation();
        $vendor = $this->getCurrentVendor();
        if (!$vendor) {
            return;
        }

        try {
            $project = Project::findOrFail($projectId);
            
            // Check if project belongs to current vendor
            if ($project->vendor_id !== $vendor->vendor_id) {
                $this->dispatch('error', 'Unauthorized access to project.');
                return;
            }

            $this->project_id = $project->project_id;
            $this->project_header = $project->project_header;
            $this->project_detail = $project->project_detail;
            $this->project_duration_start = $project->project_duration_start->format('Y-m-d');
            $this->project_duration_end = $project->project_duration_end->format('Y-m-d');
            $this->notes = '';
            
            $this->showUpdateModal = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Error loading project: ' . $e->getMessage());
        }
    }
    public function updatePurchaseStatus()
    {
        if (!$this->selectedPurchase) {
            $this->dispatch('error', 'No purchase selected.');
            return;
        }

        try {
            $this->selectedPurchase->update([
                'status' => $this->purchaseStatus
            ]);

            $this->dispatch('project-updated', 'Purchase status updated successfully!');
            $this->showPurchaseModal = false;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Error updating purchase status: ' . $e->getMessage());
        }
    }

    public function updateProject()
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();
        if (!$vendor) {
            session()->flash('error', 'You do not have vendor access.');
            return;
        }
    
        $this->validate();
    
        try {
            DB::beginTransaction();
            
            $project = Project::findOrFail($this->project_id);
            
            if($project->vendor_id !== $vendor->vendor_id) {
                throw new \Exception('Unauthorized action.');
            }
    
            $project->update([
                'project_header' => $this->project_header,
                'project_detail' => $this->project_detail,
                'project_duration_start' => $this->project_duration_start,
                'project_duration_end' => $this->project_duration_end
            ]);
    
            // Ubah type menjadi 'Other' dan tambahkan detail di notes
            CustomerInteraction::create([
                'customer_id' => $project->customer_id,
                'user_id' => Auth::id(),
                'vendor_id' => $project->vendor_id,
                'interaction_type' => 'Other', // Sesuaikan dengan ENUM di database
                'interaction_date' => now(),
                'notes' => "Project Update: " . ($this->notes ?: 'Project details updated') // Tambahkan prefix di notes
            ]);
    
            DB::commit();
    
            $this->showUpdateModal = false;
            $this->dispatch('project-updated', 'Project successfully updated!');
            $this->reset(['project_id', 'project_header', 'project_detail', 'project_duration_start', 'project_duration_end', 'notes']);
    
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating project: ' . $e->getMessage());
        }
    }
    public function viewDetail($projectId)
    {
        $vendor = $this->getCurrentVendor();
        if (!$vendor) {
            return;
        }

        try {
            $project = Project::with(['customer', 'products', 'vendor'])
                ->where('vendor_id', $vendor->vendor_id)
                ->findOrFail($projectId);

            $this->selectedProject = $project;
            $this->showDetailModal = true;
        } catch (\Exception $e) {
            $this->dispatch('error', 'Error loading project details: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showUpdateModal = false;
        $this->showDetailModal = false;
        $this->selectedProject = null;
        $this->reset(['project_id', 'project_header', 'project_detail', 'project_duration_start', 'project_duration_end', 'notes']);
        $this->resetValidation();
    }

    public function getProjectStatus($project)
    {
        $startDate = Carbon::parse($project->project_duration_start);
        $endDate = Carbon::parse($project->project_duration_end);
        $today = now();

        if ($today < $startDate) {
            return [
                'status' => 'Not Started',
                'color' => 'gray',
                'progress' => 0,
                'days_remaining' => $today->diffInDays($startDate) . ' days until start'
            ];
        }

        if ($today > $endDate) {
            return [
                'status' => 'Completed',
                'color' => 'green',
                'progress' => 100,
                'days_remaining' => 'Completed ' . $today->diffInDays($endDate) . ' days ago'
            ];
        }

        // Calculate progress for ongoing projects
        $totalDays = $startDate->diffInDays($endDate) ?: 1;
        $daysElapsed = $startDate->diffInDays($today);
        $progress = min(100, round(($daysElapsed / $totalDays) * 100));

        return [
            'status' => 'In Progress',
            'color' => 'blue',
            'progress' => $progress,
            'days_remaining' => $endDate->diffInDays($today) . ' days remaining'
        ];
    }

    public function render()
    {
        $vendor = $this->getCurrentVendor();

        if (!$vendor) {
            return view('livewire.project.project-list-vendor', [
                'noVendorAccess' => true,
                'projects' => collect([]),
                'totalValue' => 0,
                'activeProjects' => 0,
                'completedProjects' => 0,
                'customers' => collect([])
            ]);
        }

        try {
            $query = Project::where('vendor_id', $vendor->vendor_id)
                ->with(['customer', 'products'])
                ->when($this->search, function($q) {
                    $q->where(function($query) {
                        $query->where('project_header', 'like', '%' . $this->search . '%')
                              ->orWhere('project_detail', 'like', '%' . $this->search . '%')
                              ->orWhereHas('customer', function($q) {
                                  $q->where('customer_name', 'like', '%' . $this->search . '%');
                              });
                    });
                })
                ->when($this->customerFilter, function($q) {
                    $q->where('customer_id', $this->customerFilter);
                })
                ->when($this->dateFilter, function($q) {
                    $date = Carbon::parse($this->dateFilter);
                    $q->whereDate('project_duration_start', '<=', $date)
                      ->whereDate('project_duration_end', '>=', $date);
                })
                ->orderBy($this->sortField, $this->sortDirection);

            $projects = $query->paginate(10);

            // Get customers for filter
            $customers = Customer::whereHas('projects', function($query) use ($vendor) {
                $query->where('vendor_id', $vendor->vendor_id);
            })->get();

            // Calculate metrics
            $totalValue = Project::where('vendor_id', $vendor->vendor_id)->sum('project_value');
            
            $activeProjects = Project::where('vendor_id', $vendor->vendor_id)
                ->whereDate('project_duration_start', '<=', now())
                ->whereDate('project_duration_end', '>=', now())
                ->count();
                
            $completedProjects = Project::where('vendor_id', $vendor->vendor_id)
                ->whereDate('project_duration_end', '<', now())
                ->count();

            return view('livewire.project.project-list-vendor', [
                'projects' => $projects,
                'customers' => $customers,
                'totalValue' => $totalValue,
                'activeProjects' => $activeProjects,
                'completedProjects' => $completedProjects
            ]);

        } catch (\Exception $e) {
            $this->dispatch('error', 'Error loading projects: ' . $e->getMessage());
            
            return view('livewire.project.project-list-vendor', [
                'projects' => collect([]),
                'customers' => collect([]),
                'totalValue' => 0,
                'activeProjects' => 0,
                'completedProjects' => 0
            ]);
        }
    }
    
}