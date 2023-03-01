<?php


namespace App\Http\Livewire\Frontend;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Balance extends Component
{
    protected $listeners = ['updateBalance' => 'updateBalance'];

    public $balance = 0;

    public function mount()
    {
        $this->updateBalance();
    }

    public function updateBalance()
    {
        $this->balance = Auth::user()->getBalance();
    }

    public function render()
    {
        return view('livewire.balance');
    }
}