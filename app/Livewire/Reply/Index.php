<?php

namespace App\Livewire\Reply;

use App\Models\Reply;
use App\Models\Thought;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Index extends Component
{
    #[Locked]
    public $thought;
    #[Locked]
    public $replied_reply;
    #[Locked]
    public $colors_of_border = [
        'lime',
        'violet',
        'neutral',
        'teal',
        'yellow',
        'fuchsia',
        'stone',
        'blue',
        'pink',
        'gray',
        'slate',
        'green',
        'cyan',
        'purple',
        'sky',
        'red',
        'orange',
        'rose',
        'indigo',
        'emerald',
        'zinc',
        'amber',
    ];
    public $position_of_color_for_border = 0;
    #[Reactive]
    public $sort_by;
    #[Reactive]
    public $sort_order;

    public function mount(Thought $thought, $replied_reply = null, $position_of_color_for_border)
    {
        $this->thought = $thought;
        if ($replied_reply === null) {
            $this->replied_reply = $replied_reply;
        } else {
            $this->replied_reply = Reply::where('slug', $replied_reply)->firstOrFail();
        }
        $this->position_of_color_for_border = $position_of_color_for_border >= count($this->colors_of_border) ? 0 : $position_of_color_for_border;
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
            $replies = Reply::with('user')->withCount('replies')->where('replied_id', $this->replied_reply->id)->orderBy($this->sort_by, $this->sort_order)->get();
        } else {
            $replies = Reply::with('user')->withCount('replies')->where('thought_id', $this->thought->id)->where('replied_id', null)->orderBy($this->sort_by, $this->sort_order)->get();
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
        // dd($replies->pluck('slug'));
        return $replies;
    }
}
