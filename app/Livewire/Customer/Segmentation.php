<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\CustomerInteraction;
use App\Models\Project;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class Segmentation extends Component
{
    use WithPagination;

    // Properties untuk pagination dan sorting
    protected $paginationTheme = 'tailwind';
    public $sortField = 'customer_name'; // Default sort field
    public $sortDirection = 'asc';        // Default sort direction

    // Properties untuk filter
    public $search = '';
    public $dateRange = '30';
    public $interactionType = 'all';
    public $minimumInteractions = 0;

    // Properties untuk UI state
    public $showingCustomerDetails = false;
    public $selectedCustomer = null;
    public $selected = [];
    public $selectAll = false;

    // Method untuk sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Method untuk menampilkan detail customer
    public function showDetails($customerId)
    {
        $this->selectedCustomer = Customer::with([
            'interactions' => function($query) {
                $query->latest('interaction_date')->limit(5);
            },
            'projects',
            'sales'
        ])->find($customerId);
        
        $this->showingCustomerDetails = true;
    }

    // Method untuk menutup modal detail
    public function closeCustomerDetails()
    {
        $this->showingCustomerDetails = false;
        $this->selectedCustomer = null;
    }

    // Method untuk mengupdate selected items
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getCustomers()->pluck('customer_id')->map(function($id) {
                return (string) $id;
            })->toArray();
        } else {
            $this->selected = [];
        }
    }

    // Method untuk mendapatkan data customer
    private function getCustomers()
    {
        return Customer::select([
            'customers.customer_id',
            'customers.customer_name', 
            'customers.customer_email',
            'customers.customer_phone',   // Perubahan dari phone ke customer_phone
            'customers.customer_address', // Perubahan dari address ke customer_address
            'customers.created_at',
            'customers.updated_at',
            DB::raw('COUNT(DISTINCT ci.interaction_id) as interaction_count'),
            DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
            DB::raw('COALESCE(SUM(s.fixed_amount), 0) as total_sales'),
            DB::raw('MAX(ci.interaction_date) as last_interaction_date')
        ])
        ->leftJoin('customer_interactions as ci', function($join) {
            $join->on('customers.customer_id', '=', 'ci.customer_id')
                ->when($this->dateRange != 'all', function($query) {
                    return $query->where('ci.interaction_date', '>=', 
                        now()->subDays((int)$this->dateRange));
                });
        })
        ->leftJoin('projects as p', 'customers.customer_id', '=', 'p.customer_id')
        ->leftJoin('sales as s', 'customers.customer_id', '=', 's.customer_id')
        ->when($this->search, function($query) {
            $query->where(function($q) {
                $q->where('customers.customer_name', 'like', "%{$this->search}%")
                  ->orWhere('customers.customer_email', 'like', "%{$this->search}%");
            });
        })
        ->when($this->interactionType != 'all', function($query) {
            return $query->whereExists(function($subquery) {
                $subquery->from('customer_interactions')
                    ->whereColumn('customer_interactions.customer_id', 'customers.customer_id')
                    ->where('interaction_type', $this->interactionType);
            });
        })
        ->groupBy([
            'customers.customer_id',
            'customers.customer_name',
            'customers.customer_email',
            'customers.customer_phone',   // Perubahan dari phone ke customer_phone
            'customers.customer_address', // Perubahan dari address ke customer_address
            'customers.created_at',
            'customers.updated_at'
        ])
        ->having('interaction_count', '>=', $this->minimumInteractions)
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(10);
    }

    // Method untuk render view
    public function render()
    {
        $customers = $this->getCustomers();
        
        $statistics = [
            'total_customers' => Customer::count(),
            'active_customers' => CustomerInteraction::where('interaction_date', '>=', 
                now()->subDays(30))->distinct('customer_id')->count(),
            'total_interactions' => CustomerInteraction::count(),
        ];

        return view('livewire.customer.segmentation', [
            'customers' => $customers,
            'statistics' => $statistics
        ]);
    }

    // Method untuk export data
    public function export($type)
    {
        // Implementasi export sesuai tipe (excel, pdf, csv)
    }

    // Method untuk mendapatkan label segment
    public function getSegmentLabel($metrics)
    {
        if ($metrics['interaction_count'] >= 10 && $metrics['project_count'] >= 2) {
            return ['name' => 'Premium', 'color' => 'bg-purple-100 text-purple-800'];
        } elseif ($metrics['interaction_count'] >= 5 || $metrics['project_count'] >= 1) {
            return ['name' => 'Active', 'color' => 'bg-green-100 text-green-800'];
        } else {
            return ['name' => 'Regular', 'color' => 'bg-gray-100 text-gray-800'];
        }
    }
}