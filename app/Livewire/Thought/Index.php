<?php

namespace App\Livewire\Thought;

use App\Models\Thought;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public ?string $topic;
    #[Url(history: true)]
    public ?string $content;
    #[Url(history: true)]
    public ?string $tags;
    #[Url(history: true)]
    public ?bool $open;
    #[Url(history: true)]
    public ?string $order;
    #[Url(history: true)]
    public ?string $direction;
    #[Url(history: true)]
    public ?int $itemsInPage;
    #[Locked]
    public $available_columns = ['topic', 'content', 'tags', 'open', 'created'];
    #[Locked]
    public $available_direction = ['asc', 'desc'];
    #[Locked]
    public $available_open_options = [
        [
            'name' => 'Only opened thought',
            'value' => true
        ],
        [
            'name' => 'All thoughts',
            'value' => null
        ],
        [
            'name' => 'Only closed thought',
            'value' => false
        ],
    ];
    
    public function mount()
    {
        $this->topic = null;
        $this->content = null;
        $this->tags = null;
        $this->open = null;
        $this->order = 'created';
        $this->direction = 'desc';
        $this->itemsInPage = 10;
        
    }
    public function render()
    {
        return view('livewire.thought.index', [
            'thoughts' => $this->thoughts()
        ]);
    }
    #[Computed()]
    public function thoughts()
    {
        $thoughts = Thought::query()->with('user');
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
            $thoughts = $thoughts->open($this->open);
        }
        if ($this->order !== null) {
            $column = in_array($this->order, $this->available_columns) ? $this->order : 'created';
            $column = $column == 'created' ? 'created_at' : $column;
            if ($this->direction !== null) {
                $directions = ['asc', 'desc'];
                $direction = in_array($this->direction, $directions) ? $this->direction : 'desc';
            } else {
                $direction = 'desc';
            }
            $thoughts = $thoughts->orderBy($column, $direction);
        }
        if ($this->direction !== null) {
            $directions = ['asc', 'desc'];
            $direction = in_array($this->direction, $directions) ? $this->direction : 'desc';
            if ($this->order !== null) {
                $column = in_array($this->order, $this->available_columns) ? $this->order : 'created';
                $column = $column == 'created' ? 'created_at' : $column;
            } else {
                $column = 'created_at';
            }
            $thoughts = $thoughts->orderBy($column, $direction);
        }
        if ($this->itemsInPage !== null) {
            $thoughts = $this->itemsInPage > 0 && $this->itemsInPage <= 100 ? $thoughts->paginate($this->itemsInPage)->withQueryString() : $thoughts->paginate(50)->withQueryString();
        }
        if ($this->itemsInPage === null) {
            $thoughts = $thoughts->paginate(50)->withQueryString();
        }
        return $thoughts;
    }
    public function updated($property)
    {
        unset($this->thoughts);
        $this->resetPage();
    }
}
