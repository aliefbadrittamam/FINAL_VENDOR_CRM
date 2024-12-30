<?php
namespace App\Livewire\User;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Main extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filters = [
        'date_from' => '',
        'date_to' => ''
    ];
    
    public $showModal = false;
    public $editingUserId = null;
    public $notification = [
        'show' => false,
        'message' => ''
    ];

    // Mendefinisikan listeners untuk event
    protected $listeners = [
        'closeModal' => 'handleCloseModal',
        'userSaved' => 'handleUserSaved'
    ];

    // Membuka modal dan mengatur ID user jika dalam mode edit
    public function openModal($userId = null)
    {
        $this->editingUserId = $userId;
        $this->showModal = true;
    }

    // Menutup modal dan membersihkan state
    public function handleCloseModal()
    {
        $this->showModal = false;
        $this->editingUserId = null;
        $this->dispatch('modalClosed');
    }

    // Menangani event setelah user disimpan
    public function handleUserSaved($message)
    {
        $this->handleCloseModal();
        $this->notification['show'] = true;
        $this->notification['message'] = $message;
        
        // Refresh data
        $this->resetPage();
    }

    // Reset halaman saat pencarian berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset halaman saat filter berubah
    public function updatingFilters()
    {
        $this->resetPage();
    }
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        $this->notification['show'] = true;
        $this->notification['message'] = 'User deleted successfully.';

        // Refresh data
        $this->resetPage();
    }
    public function render()
    {
        $users = User::query()
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%")
                        ->orWhere('role', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filters['date_from'], function($query) {
                $query->whereDate('created_at', '>=', $this->filters['date_from']);
            })
            ->when($this->filters['date_to'], function($query) {
                $query->whereDate('created_at', '<=', $this->filters['date_to']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.user.main', [
            'users' => $users
        ]);
    }
}