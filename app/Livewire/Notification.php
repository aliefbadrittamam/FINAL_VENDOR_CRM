<?php
namespace App\Livewire;

use Livewire\Component;

class Notification extends Component
{
    public $message = '';
    public $show = false;
    public $type = 'success';

    protected $listeners = ['showNotification'];

    public function showNotification($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
    }

    public function render()
    {
        return view('livewire.notification');
    }
}