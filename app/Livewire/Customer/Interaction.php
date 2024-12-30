<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\CustomerInteraction;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Interaction extends Component
{
    use WithPagination;

    // Konfigurasi tema pagination
    protected $paginationTheme = 'tailwind';

    // Properties untuk data form
    public $selectedCustomerId = null;
    public $selectedCustomerName = null;
    public $interaction_type = '';
    public $notes = '';
    public $vendor_id;
    public $interaction_date;

    // Properties untuk state UI
    public $showModal = false;
    public $editingInteractionId = null;
    public $isLoading = false;

    // Inisialisasi komponen
    public function mount()
    {
        $this->interaction_date = now()->format('Y-m-d\TH:i');
    }

    // Aturan validasi
    protected function rules()
    {
        return [
            'selectedCustomerId' => 'required|exists:customers,customer_id',
            'vendor_id' => 'required|exists:vendors,vendor_id',
            'interaction_type' => 'required|in:Call,Email,Meeting,Other',
            'notes' => 'required|min:10',
            'interaction_date' => 'required|date'
        ];
    }

    // Pesan validasi kustom
    protected function messages()
    {
        return [
            'selectedCustomerId.required' => 'Pilih customer terlebih dahulu',
            'vendor_id.required' => 'Pilih vendor terlebih dahulu',
            'interaction_type.required' => 'Tipe interaksi harus dipilih',
            'notes.required' => 'Catatan interaksi harus diisi',
            'notes.min' => 'Catatan minimal 10 karakter',
            'interaction_date.required' => 'Tanggal interaksi harus diisi'
        ];
    }

    // Method untuk membuka modal
    public function openModal($interactionId = null)
    {
        $this->resetValidation();
        $this->showModal = true;

        if ($interactionId) {
            try {
                $interaction = CustomerInteraction::findOrFail($interactionId);
                if ($interaction && $interaction->customer_id == $this->selectedCustomerId) {
                    $this->editingInteractionId = $interactionId;
                    $this->interaction_type = $interaction->interaction_type;
                    $this->vendor_id = $interaction->vendor_id;
                    $this->notes = $interaction->notes;
                    $this->interaction_date = $interaction->interaction_date->format('Y-m-d\TH:i');
                }
            } catch (\Exception $e) {
                Log::error('Error loading interaction: ' . $e->getMessage());
                session()->flash('error', 'Gagal memuat data interaksi');
                $this->closeModal();
            }
        } else {
            $this->resetExcept(['selectedCustomerId', 'selectedCustomerName', 'showModal']);
            $this->interaction_date = now()->format('Y-m-d\TH:i');
        }
    }

    // Method untuk menutup modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetExcept(['selectedCustomerId', 'selectedCustomerName']);
        $this->resetValidation();
    }

    // Method untuk menyimpan data
    public function save()
    {
        $this->isLoading = true;

        try {
            $validatedData = $this->validate();
            
            DB::beginTransaction();

            $interactionData = [
                'customer_id' => $this->selectedCustomerId,
                'user_id' => Auth::id(),
                'vendor_id' => $this->vendor_id,
                'interaction_type' => $this->interaction_type,
                'interaction_date' => $this->interaction_date,
                'notes' => $this->notes
            ];

            if ($this->editingInteractionId) {
                $interaction = CustomerInteraction::findOrFail($this->editingInteractionId);
                if ($interaction->customer_id != $this->selectedCustomerId) {
                    throw new \Exception('Akses tidak valid');
                }
                $interaction->update($interactionData);
                $message = 'Interaksi berhasil diperbarui';
            } else {
                CustomerInteraction::create($interactionData);
                $message = 'Interaksi baru berhasil disimpan';
            }

            DB::commit();
            
            $this->closeModal();
            session()->flash('message', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Interaction Error: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    // Method untuk menghapus data
    public function deleteInteraction($id)
    {
        try {
            $interaction = CustomerInteraction::findOrFail($id);
            
            if ($interaction->customer_id != $this->selectedCustomerId) {
                throw new \Exception('Akses tidak valid');
            }

            $interaction->delete();
            session()->flash('message', 'Interaksi berhasil dihapus');
            
        } catch (\Exception $e) {
            Log::error('Delete Interaction Error: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    // Method untuk mengupdate customer yang dipilih
    public function updatedSelectedCustomerId($value)
    {
        $this->resetPage();
        if ($value) {
            $customer = Customer::find($value);
            if ($customer) {
                $this->selectedCustomerName = $customer->customer_name;
            }
        } else {
            $this->selectedCustomerName = null;
        }
    }

    // Method render untuk menampilkan view
    public function render()
    {
        $interactions = $this->selectedCustomerId
            ? CustomerInteraction::with(['user', 'vendor'])
                ->where('customer_id', $this->selectedCustomerId)
                ->orderBy('interaction_date', 'desc')
                ->paginate(10)
            : collect([]);

        return view('livewire.customer.interaction', [
            'customers' => Customer::orderBy('customer_name')->get(),
            'vendors' => Vendor::orderBy('vendor_name')->get(),
            'interactions' => $interactions
        ]);
    }
}