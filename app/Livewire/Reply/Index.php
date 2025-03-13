<?php

namespace App\Livewire\Reply;

use App\Models\Reply;
use App\Models\Thought;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Index extends Component
{
    #[Locked]
    public $thought;
    #[Locked]
    public $replied_reply;
    public function mount(Thought $thought, $replied_reply = null)
    {
        // dd('yes');
        $this->thought = $thought;
        if ($replied_reply === null) {
            $this->replied_reply = $replied_reply;
        } else {
            $this->replied_reply = Reply::where('slug', $replied_reply)->firstOrFail();
        }
    }
    public function render()
    {
        return view('livewire.reply.index', [
            'replies' => $this->replies()
        ]);
    }
    #[Computed()]
    public function replies()
    {
        if ($this->replied_reply !== null) {
            $replies = Reply::with('user')->where('replied_id', $this->replied_reply->id)->orderBy('created_at', 'desc')->get();
        } else {
            $replies = Reply::with('user')->where('thought_id', $this->thought->id)->orderBy('created_at', 'desc')->get();
        }
        $pinned_replies = $replies->filter(function($value, $key) {
            if($value['pinned'] == 'Pinned') {
                return $value;
            }
        });
        $unpinned_replies = $replies->filter(function($value, $key) {
            if($value['pinned'] === 'Unpinned') {
                return $value;
            }
        });
        $replies = $pinned_replies->merge($unpinned_replies);
        return $replies;
    }
}
