<?php
namespace App\Livewire\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Livewire\Component;

class UserForm extends Component
{
    // Properties untuk data form
    public $userId = null;
    public $name = '';
    public $email = '';
    public $status = 'active';
    public $role = '';
    public $password = '';
    public $password_confirmation = '';

    // Properties untuk state UI
    public $showConfirmation = false;
    public $isLoading = false;
    public $showSuccess = false;

    // Mendefinisikan rule validasi
    protected function rules()
    {
        $emailRules = ['required', 'email'];

        // Menambahkan validasi unique email untuk user baru
        if (!$this->userId) {
            $emailRules[] = 'unique:users,email';
        } else {
            // Untuk update, email harus unique kecuali untuk user yang sedang diedit
            $emailRules[] = 'unique:users,email,' . $this->userId . ',id';
        }

        $rules = [
            'name' => 'required|min:3',
            'email' => $emailRules,
            'role' => 'required|in:Vendor,Admin,Customers',
            'status' => 'required|in:active,inactive',
        ];

        // Password hanya required untuk user baru
        if (!$this->userId) {
            $rules['password'] = 'required|min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        return $rules;
    }

    // Inisialisasi data untuk mode edit
    public function mount($userId = null)
    {
        if ($userId) {
            $user = User::findOrFail($userId);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->status = $user->status;
            $this->role = $user->role;
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

    // Proses penyimpanan data
    public function save()
    {
        try {
            // Aktifkan loading state
            $this->isLoading = true;

            DB::beginTransaction();

            if ($this->userId) {
                // Update existing user
                $user = User::findOrFail($this->userId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'status' => $this->status,
                    'role' => $this->role,
                ]);

                $message = 'User updated successfully!';
            } else {
                // Buat user baru
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'status' => $this->status,
                    'role' => $this->role,
                ]);

                $message = 'User created successfully!';
            }

            DB::commit();

            // Tampilkan animasi sukses
            $this->isLoading = false;
            $this->showSuccess = true;

            // Kirim notifikasi ke komponen parent
            $this->dispatch('userSaved', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->isLoading = false;
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
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
        return view('livewire.user.user-form');
    }
}
