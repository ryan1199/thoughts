<?php

namespace App\Livewire\User;

use App\Models\Thought;
use App\Models\User;
use Illuminate\Support\Arr;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Show extends Component
{
    use WithPagination, Toast;

    #[Url(history: true)]
    public string $topic = '';
    #[Url(history: true)]
    public string $content = '';
    #[Url(history: true)]
    public string $tags = '';
    #[Url(history: true)]
    public string $open = 'both';
    #[Url(history: true)]
    public string $order = 'created';
    #[Url(history: true)]
    public string $direction = 'desc';
    #[Url(history: true)]
    public int $items_in_page = 10;
    #[Locked]
    public $available_columns = [
        [
            'name' => 'Topic',
            'value' => 'topic'
        ],
        [
            'name' => 'Content',
            'value' => 'content'
        ],
        [
            'name' => 'Tags',
            'value' => 'tags'
        ],
        [
            'name' => 'Open',
            'value' => 'open'
        ],
        [
            'name' => 'Created At',
            'value' => 'created'
        ],
    ];
    #[Locked]
    public $available_direction = [
        [
            'name' => 'Ascending',
            'value' => 'asc'
        ],
        [
            'name' => 'Descending',
            'value' => 'desc'
        ],
    ];
    #[Locked]
    public $available_open_options = [
        [
            'name' => 'Opened thought',
            'value' => 'open'
        ],
        [
            'name' => 'All thoughts',
            'value' => 'both'
        ],
        [
            'name' => 'Closed thought',
            'value' => 'close'
        ],
    ];
    #[Locked]
    public $user_slug;

    public function mount(User $user)
    {
        $this->user_slug = $user->slug;
    }
    public function render()
    {
        return view('livewire.user.show', [
            'user' => $this->user,
            'thoughts' => $this->thoughts
        ]);
    }
    #[Computed(persist: true)]
    public function user()
    {
        return User::where('slug', $this->user_slug)->firstOrFail();
    }
    #[Computed()]
    public function thoughts()
    {
        $thoughts = Thought::query()->withCount('replies')->where('user_id', $this->user->id);
        if ($this->topic !== null) {
            $thoughts = $thoughts->topic($this->topic);
        }
        if ($this->content !== null) {
            $thoughts = $thoughts->content($this->content);
        }
        if ($this->tags !== null) {
            $thoughts = $thoughts->tags($this->tags);
        }
        if ($this->open !== null) {
            switch ($this->open) {
                case 'open':
                    $thoughts = $thoughts->open(true);
                    break;
                case 'close':
                    $thoughts = $thoughts->open(false);
                    break;
                default:
                    $thoughts = $thoughts;
                    break;
            }
        }
        if ($this->order !== null) {
            $column = in_array($this->order, Arr::pluck($this->available_columns, 'value')) ? $this->order : 'created';
            $column = $column == 'created' ? 'created_at' : $column;
            if ($this->direction !== null) {
                $direction = in_array($this->direction, Arr::pluck($this->available_direction, 'value')) ? $this->direction : 'desc';
            } else {
                $direction = 'desc';
            }
            $thoughts = $thoughts->orderBy($column, $direction);
        }
        if ($this->direction !== null) {
            $direction = in_array($this->direction, Arr::pluck($this->available_direction, 'value')) ? $this->direction : 'desc';
            if ($this->order !== null) {
                $column = in_array($this->order, Arr::pluck($this->available_columns, 'value')) ? $this->order : 'created';
                $column = $column == 'created' ? 'created_at' : $column;
            } else {
                $column = 'created_at';
            }
            $thoughts = $thoughts->orderBy($column, $direction);
        }
        if ($this->items_in_page !== null) {
            $thoughts = $this->items_in_page > 0 && $this->items_in_page <= 100 ? $thoughts->paginate($this->items_in_page)->withQueryString() : $thoughts->paginate(50)->withQueryString();
        }
        if ($this->items_in_page === null) {
            $thoughts = $thoughts->paginate(50)->withQueryString();
        }
        return $thoughts;
    }
    public function updated($property)
    {
        unset($this->thoughts);
        $this->resetPage();
        $this->success('Found '.$this->thoughts->total(), position: 'toast-bottom');
    }
}
