<?php
namespace App\Livewire\Vendor;

use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class Main extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filters = [
        'date_from' => '',
        'date_to' => ''
    ];
    
    public $showModal = false;
    public $vendorId = null;
    public $vendor_name;
    public $vendor_email;
    public $vendor_phone;
    public $vendor_address;
    public $password;
    public $password_confirmation;
    public $showPassword = false;  // untuk password
public $showConfirmPassword = false;  // untuk confirm password
    public $notification = [
        'show' => false,
        'message' => ''
    ];

    protected $rules = [
        'vendor_name' => 'required|min:3',
        'vendor_email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'vendor_phone' => 'required',
        'vendor_address' => 'required',
    ];


    public function openModal($vendorId = null)
    {
        $this->showModal = true;
        $this->vendorId = $vendorId;
        
        if ($vendorId) {
            $vendor = Vendor::findOrFail($vendorId);
            $this->vendor_name = $vendor->vendor_name;
            $this->vendor_email = $vendor->vendor_email;
            $this->vendor_phone = $vendor->vendor_phone;
            $this->vendor_address = $vendor->vendor_address;
        } else {
            $this->resetForm();
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
{
    $this->vendorId = null;
    $this->vendor_name = '';
    $this->vendor_email = '';
    $this->vendor_phone = '';
    $this->vendor_address = '';
    $this->password = '';
    $this->password_confirmation = '';
    $this->showPassword = false;
    $this->showConfirmPassword = false;
} 

    public function save()
    {
        try {
            DB::beginTransaction();

            if ($this->vendorId) {
                // Update existing vendor
                $vendor = Vendor::findOrFail($this->vendorId);
                $vendor->update([
                    'vendor_name' => $this->vendor_name,
                    'vendor_email' => $this->vendor_email,
                    'vendor_phone' => $this->vendor_phone,
                    'vendor_address' => $this->vendor_address,
                ]);

                // Update user with same email and name
                $vendor->user->update([
                    'name' => $this->vendor_name,
                    'email' => $this->vendor_email
                ]);

                $message = 'Vendor updated successfully';
            } else {
                // Validate for new vendor
                $this->validate();

                // Create new user
                $user = User::create([
                    'name' => $this->vendor_name, // Gunakan vendor_name
                    'email' => $this->vendor_email, // Gunakan vendor_email
                    'password' => Hash::make($this->password),
                    'role' => 'Vendor',
                    'status' => 'Active'
                ]);

                // Create new vendor
                Vendor::create([
                    'user_id' => $user->id,
                    'vendor_name' => $this->vendor_name,
                    'vendor_email' => $this->vendor_email,
                    'vendor_phone' => $this->vendor_phone,
                    'vendor_address' => $this->vendor_address,
                ]);

                $message = 'Vendor and account created successfully';
            }

            DB::commit();

            $this->closeModal();
            $this->notification['show'] = true;
            $this->notification['message'] = $message;
            $this->resetPage();

        } catch (\Exception $e) {
            DB::rollback();
            $this->notification['show'] = true;
            $this->notification['message'] = 'Error: ' . $e->getMessage();
        }
    }

    public function deleteVendor($vendorId)
    {
        try {
            DB::beginTransaction();

            $vendor = Vendor::with('user')->findOrFail($vendorId);
            
            // Delete user account
            if ($vendor->user) {
                $vendor->user->delete();
            }
            
            // Delete vendor
            $vendor->delete();

            DB::commit();

            $this->notification['show'] = true;
            $this->notification['message'] = 'Vendor and account deleted successfully.';
            $this->resetPage();

        } catch (\Exception $e) {
            DB::rollback();
            $this->notification['show'] = true;
            $this->notification['message'] = 'Error: ' . $e->getMessage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $vendors = Vendor::with('user')
            ->when($this->search, function($query) {
                $query->where(function($query) {
                    $query->where('vendor_name', 'like', "%{$this->search}%")
                        ->orWhere('vendor_email', 'like', "%{$this->search}%")
                        ->orWhere('vendor_phone', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('vendor_id', 'desc')
            ->paginate(10);

        return view('livewire.vendor.main', [
            'vendors' => $vendors
        ]);
    }
}