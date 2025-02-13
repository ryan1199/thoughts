<?php

namespace App\Livewire\Thought;

use App\Models\Thought;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public $thought_slug;

    public function mount(Thought $thought)
    {
        $this->thought_slug = $thought->slug;
    }
    public function render()
    {
        return view('livewire.thought.show', [
            'thought' => $this->thought,
            'user' => $this->user
        ]);
    }
    #[Computed(persist: true)]
    public function thought()
    {
        return Thought::withCount('replies')->where('slug', $this->thought_slug)->firstOrFail();
    }
    #[Computed(persist: true)]
    public function user()
    {
        return User::with(['thoughts' => function($query) {
            $query->whereNot('slug', $this->thought->slug)->orderBy('created_at', 'desc')->limit(2);
        }])->where('id', $this->thought->user->id)->firstOrFail();
    }
}
