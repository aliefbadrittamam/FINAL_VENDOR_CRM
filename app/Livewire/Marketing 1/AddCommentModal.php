<?php
namespace App\Http\Livewire;
namespace App\Livewire\Marketing;
use Livewire\Component;
use App\Models\Lead;

class AddCommentModal extends Component
{
    public $comment = ''; 
     // Properties untuk data form
    public $leadId = null;

    // Store the comment in the database
    public function storeComment()
    {
        
        $this->validate([
            'comment' => 'required|string|max:500',
        ]);

        
        Lead::find($this->leadId)->update([
            'comments' => $this->comment,
        ]);

        $this->closeModal();
        $this->comment = '';
        session()->flash('message', 'Comment added successfully!');
    }
    public function closeModal()
    {
        $this->dispatch('closeModal');
    }
    public function mount($leadId = null)
    {
        if ($leadId) {
            $lead = Lead::findOrFail($leadId);
            $this->comment = $lead->comments; 
        }
    }
    public function render()
    {
        return view('livewire.marketing.add-comment-modal');
    }
}
