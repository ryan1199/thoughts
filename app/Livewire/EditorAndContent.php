<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\Reply;
use App\Models\Thought;
use App\Models\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Illuminate\Support\Str;
use Mary\Traits\Toast;

class EditorAndContent extends Component
{
    use Toast;
    
    #[Locked]
    public $config = [
        'spellChecker' => true,
        'uploadImage' => false,
        'toolbar' => ['heading', 'bold', 'italic', 'strikethrough', 'code', 'quote', 'unordered-list', 'ordered-list', 'clean-block', 'link', 'table', 'horizontal-rule', '|', 'preview', 'side-by-side', 'fullscreen', '|', 'guide', '|', 'undo', 'redo'],
    ];
    #[Locked]
    public $comment_to;
    #[Locked]
    public $comment_to_slug;
    public $comment;
    public $show = false;
    public $comment_count = 0;
    
    public function mount($comment_to, $comment_to_slug)
    {
        $this->comment_to = $comment_to;
        $this->comment_to_slug = $comment_to_slug;
        if ($this->isThought()) {
            $thought = $this->loadThought();
            $this->comment_count = $thought->replies_count;
        } else {
            $reply = $this->loadReply();
            $this->comment_count = $reply->replies_count;
        }
    }
    public function render()
    {
        return view('livewire.editor-and-content');
    }
    public function showCommentForm()
    {
        $this->show = ! $this->show;
    }
    public function loadThought()
    {
        return Thought::withCount('replies')->where('slug', $this->comment_to_slug)->firstOrFail();
    }
    public function loadReply()
    {
        return Reply::withCount('replies')->where('slug', $this->comment_to_slug)->firstOrFail();
    }
    public function submit()
    {
        // $config = HTMLPurifier_Config::createDefault();
        // $purifier = new HTMLPurifier($config);
        // $safe_comment = $purifier->purify($this->comment);
        // $comment = $safe_comment;
        // $comment = Str::of($safe_comment)->markdown([
        //     'html_input' => 'strip',
        //     'allow_unsafe_links' => false,
        // ]);
        $comment = $this->comment;
        $user = User::inRandomOrder()->first(); //current login user
        if ($this->isThought()) {
            $thought = $this->loadThought();
            $reply = Reply::store($user, $thought, false, $comment);
            if ($reply) {
                $sender = $user;
                $recipient = User::where('id', $thought->user_id)->firstOrFail();
                if ($sender->id != $recipient->id) {
                    $links = [];
                    $links['user'] = $sender->slug;
                    $links['thought'] = $thought->slug;
                    $content = 'replied to your thought';
                    $notification = Notification::store($recipient, $links, $content);
                }
                $this->success('Comment submitted', position: 'toast-bottom');
            } else {
                $this->error('Comment failed to submit', position: 'toast-bottom');
            }
        } else {
            $replied_reply = $this->loadReply();
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
        }
        $this->comment = '';
        $this->showCommentForm();
    }
    public function isThought()
    {
        if ($this->comment_to === 'thought') {
            return true;
        } else {
            return false;
        }
    }
}
