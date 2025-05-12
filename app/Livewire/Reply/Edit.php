<?php

namespace App\Livewire\Reply;

use App\Models\Reply;
use HTMLPurifier;
use HTMLPurifier_Config;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

class Edit extends Component
{
    use Toast;
    
    #[Locked]
    public $config = [
        'spellChecker' => true,
        'uploadImage' => false,
        'toolbar' => ['heading', 'bold', 'italic', 'strikethrough', 'code', 'quote', 'unordered-list', 'ordered-list', 'clean-block', 'link', 'table', 'horizontal-rule', '|', 'preview', 'side-by-side', 'fullscreen', '|', 'guide', '|', 'undo', 'redo'],
    ];
    #[Locked]
    public $reply;
    public $comment;
    public $show = false;
    
    public function mount($reply_id)
    {
        $this->reply = Reply::where('id', $reply_id)->firstOrFail();
        $this->comment = $this->reply->content['content'];
    }
    public function render()
    {
        return view('livewire.reply.edit');
    }
    public function showEditor()
    {
        $this->show = ! $this->show;
        $this->comment = $this->reply->content['content'];
    }
    public function update()
    {
        // must login
        $old_contents = [
            'content' => $this->reply->content['content'],
            'updated_at' => $this->reply->updated_at
        ];
        $edited_contents = [];
        if ($this->reply->edited_contents !== null) {
            $edited_contents = $this->reply->edited_contents;
        }
        $edited_contents[] = $old_contents;
        $reply = Reply::updateReply($this->reply, $this->comment, $edited_contents);
        if ($reply) {
            $this->success('Comment updated', position: 'toast-bottom');
        } else {
            $this->error('Comment failed to update', position: 'toast-bottom');
        }
    }
}
