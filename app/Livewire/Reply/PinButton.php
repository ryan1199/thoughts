<?php

namespace App\Livewire\Reply;

use App\Models\Reply;
use App\Models\Thought;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mary\Traits\Toast;

class PinButton extends Component
{
    use Toast;

    #[Locked]
    public $thought;
    #[Locked]
    public $reply;
    public $is_pinned = 'Unpinned';

    public function mount(Thought $thought, Reply $reply)
    {
        $this->thought = $thought;
        $this->reply = $reply;
        $this->is_pinned = $reply->pinned;
    }
    public function render()
    {
        return view('livewire.reply.pin-button');
    }
    public function togglePin()
    {
        // must login
        if (Auth::check()) {
            // thought policy here
            $is_pinned = $this->is_pinned == 'Pinned' ? true : false;
            $this->reply->pinned = !$is_pinned;
            $this->reply->save();
            $this->is_pinned = $this->reply->pinned;
            $this->success('Done', position: 'toast-bottom');
        } else {
            $this->error('You must be logged in to pin or unpin a reply.', position: 'toast-bottom');
        }
        // broadcast the update
    }
}
