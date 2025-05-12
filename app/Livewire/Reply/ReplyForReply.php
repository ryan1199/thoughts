<?php

namespace App\Livewire\Reply;

use App\Models\Notification;
use App\Models\Reply;
use App\Models\Thought;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

class ReplyForReply extends Component
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
    public $replies_count = 0;
    
    public function mount($reply_id)
    {
        $this->reply = Reply::withCount('replies')->where('id', $reply_id)->firstOrFail();
        $this->replies_count = $this->reply->replies_count;
    }
    public function render()
    {
        return view('livewire.reply.reply-for-reply');
    }
    public function showEditor()
    {
        $this->show = ! $this->show;
    }
    public function store()
    {
        // must login
        $comment = $this->comment;
        $user = User::where('id', Auth::id())->firstOrFail();
        $replied_reply = $this->reply;
        $thought = Thought::where('id', $replied_reply->thought_id)->firstOrFail();
        $reply = Reply::store($user, $thought, $replied_reply->id, $comment);
        if ($reply) {
            $sender = $user;
            $recipient = User::where('id', $replied_reply->user_id)->firstOrFail();
            if ($sender->id != $recipient->id) {
                $links = [];
                $links['user'] = $sender->slug;
                $links['thought'] = $thought->slug;
                $links['reply'] = $replied_reply->slug;
                $content = 'replied to your reply';
                $notification = Notification::store($recipient, $links, $content);
            }
            $this->success('Comment submitted', position: 'toast-bottom');
        } else {
            $this->error('Comment failed to submit', position: 'toast-bottom');
        }
        $this->comment = '';
        $this->showEditor();
    }
}
