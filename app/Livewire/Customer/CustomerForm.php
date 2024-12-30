<?php
namespace App\Livewire\Customer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Lead;
use App\Models\Customer;
use Livewire\Component;

class CustomerForm extends Component
{
    // Properties untuk data form
    public $customerId = null;
    public $customer_name = '';
    public $customer_email = '';
    public $customer_phone = '';
    public $customer_address = '';
    public $password = '';
    public $password_confirmation = '';

    // Properties untuk state UI
    public $showConfirmation = false;
    public $isLoading = false;
    public $showSuccess = false;

    // Mendefinisikan rule validasi yang dinamis
    protected function rules()
    {
        $emailRules = ['required', 'email'];
        
        // Menambahkan validasi unique email untuk user baru
        if (!$this->customerId) {
            $emailRules[] = 'unique:users,email';
        } else {
            // Untuk update, email harus unique kecuali untuk user yang sedang diedit
            $customer = Customer::find($this->customerId);
            if ($customer) {
                $emailRules[] = 'unique:users,email,' . $customer->user_id . ',id';
            }
        }

        $rules = [
            'customer_name' => 'required|min:3',
            'customer_email' => $emailRules,
            'customer_phone' => 'required',
            'customer_address' => 'required',
        ];

        // Password hanya required untuk customer baru
        if (!$this->customerId) {
            $rules['password'] = 'required|min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        return $rules;
    }

    // Inisialisasi data untuk mode edit
    public function mount($customerId = null)
    {
        if ($customerId) {
            $customer = Customer::findOrFail($customerId);
            $this->customerId = $customer->customer_id;
            $this->customer_name = $customer->customer_name;
            $this->customer_email = $customer->customer_email;
            $this->customer_phone = $customer->customer_phone;
            $this->customer_address = $customer->customer_address;
        }
    }

    // Menutup modal dan mereset state
    public function closeModal()
    {
        $this->dispatch('closeModal');
        $this->resetState();
    }

    // Menampilkan modal konfirmasi setelah validasi
    public function confirmSave()
    {
        $this->validate();
        $this->showConfirmation = true;
    }

    // Membatalkan konfirmasi
    public function cancelConfirmation()
    {
        $this->showConfirmation = false;
        $this->isLoading = false;
        $this->showSuccess = false;
    }

    // Proses penyimpanan data dengan animasi
    public function save()
    {
        try {
            // Aktifkan loading state
            $this->isLoading = true;

            DB::beginTransaction();

            if ($this->customerId) {
                // Update existing customer
                $customer = Customer::findOrFail($this->customerId);
                
                // Update user jika email berubah
                if ($customer->user && $customer->customer_email !== $this->customer_email) {
                    $customer->user->update([
                        'email' => $this->customer_email,
                        'name' => $this->customer_name
                    ]);
                }

                $customer->update([
                    'customer_name' => $this->customer_name,
                    'customer_email' => $this->customer_email,
                    'customer_phone' => $this->customer_phone,
                    'customer_address' => $this->customer_address,
                ]);

                $message = 'Customer updated successfully!';
            } else {
                // Buat user account baru
                $user = User::create([
                    'name' => $this->customer_name,
                    'email' => $this->customer_email,
                    'password' => Hash::make($this->password),
                    'role' => 'Customers',
                    'status' => 'Active'
                ]);

                // Buat customer baru
                Customer::create([
                    'user_id' => $user->id,
                    'customer_name' => $this->customer_name,
                    'customer_email' => $this->customer_email,
                    'customer_phone' => $this->customer_phone,
                    'customer_address' => $this->customer_address,
                ]);

                $message = 'Customer and user account created successfully!';
            }

            DB::commit();

            // Tampilkan animasi sukses
            $this->isLoading = false;
            $this->showSuccess = true;

            // Tunggu sebentar untuk animasi
            $this->dispatch('saved')->self();

            // Kirim notifikasi ke komponen parent
            $this->dispatch('customerSaved', $message);
            $this->updateLeads();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->isLoading = false;
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }
    private function updateLeads(){
        $customersWithoutLeads = Customer::whereDoesntHave('leads')->get();
        // Loop dan tambahkan data lead
        DB::beginTransaction();
        foreach ($customersWithoutLeads as $customer) {
            Lead::create([
                'customer_id' => $customer->customer_id,
            ]);
        }
        DB::commit();
    }
    // Reset semua state
    private function resetState()
    {
        $this->showConfirmation = false;
        $this->isLoading = false;
        $this->showSuccess = false;
    }

    // Render view
    public function render()
    {
        return view('livewire.customer.customer-form');
    }
}