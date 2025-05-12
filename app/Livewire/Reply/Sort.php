<?php

namespace App\Livewire\Reply;

use App\Models\Thought;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Sort extends Component
{
    #[Locked]
    public $thought;
    public $sort_by;
    public $sort_order;
    #[Locked]
    public $sort_by_options = [
        [
            'id' => 'created_at',
            'name' => 'Created At'
        ],
        [
            'id' => 'updated_at',
            'name' => 'Updated At'
        ],
        [
            'id' => 'replies_count',
            'name' => 'Replies Count'
        ]
    ];
    #[Locked]
    public $sort_order_options = [
        [
            'id' => 'asc',
            'name' => 'Ascending'
        ],
        [
            'id' => 'desc',
            'name' => 'Descending'
        ]
    ];

    public function mount(Thought $thought, $sort_by = 'created_at', $sort_order = 'desc')
    {
        $this->thought = $thought;
        $this->sort_by = in_array($sort_by, array_column($this->sort_by_options, 'id')) ? $sort_by : 'created_at';
        $this->sort_order = in_array($sort_order, array_column($this->sort_order_options, 'id')) ? $sort_order : 'desc';
        $this->sort_order = $sort_order;
    }
    public function render()
    {
        return view('livewire.reply.sort');
    }
    public function updated($property)
    {
        if ($property === 'sort_by') {
            $this->sort_by = in_array($this->sort_by, array_column($this->sort_by_options, 'id')) ? $this->sort_by : 'created_at';
        }
        if ($property === 'sort_order') {
            $this->sort_order = in_array($this->sort_order, array_column($this->sort_order_options, 'id')) ? $this->sort_order : 'desc';
        }
    }
}
