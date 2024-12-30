<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    // Property to store the authenticated user information
    public $userName;
    public $userEmail;
    public $userInitials;

    // The mount method is Livewire's version of a constructor
    // It runs when the component is first loaded
    public function mount()
    {
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Set the user properties
        $this->userName = $user->name;
        $this->userEmail = $user->email;
        
        // Create initials from the user's name
        // This takes the first letter of each word in the name
        $this->userInitials = collect(explode(' ', $user->name))
            ->map(function ($segment) {
                return strtoupper(substr($segment, 0, 1));
            })
            ->take(2)
            ->join('');
    }

    // The render method handles displaying the component
    public function render()
    {
        return view('livewire.sidebar');
    }
}