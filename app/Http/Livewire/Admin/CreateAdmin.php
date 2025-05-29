<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Component
{
    public $name;
    public $email;
    public $password;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'admin',
        ]);

        session()->flash('message', 'Admin created successfully.');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.create-admin');
    }
}
