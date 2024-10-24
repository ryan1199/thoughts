<?php

namespace App\Http\Controllers\API\Reply;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReplyResource;
use App\Models\Notification;
use App\Models\Reply;
use App\Models\Thought;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PinnedReplyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Thought $thought, Reply $reply)
    {
        Gate::authorize('pinned', [Reply::class, $thought, $reply]);
        $reply->pinned = true;
        $reply->save();
        $reply->load(['user', 'thought', 'replies', 'reply']);
        if (auth('sanctum')->user()->id != $reply->user_id) {
            $links = [];
            $links['user'] = auth('sanctum')->user()->slug;
            $links['thought'] = $thought->slug;
            $links['reply'] = $reply->slug;
            $content = 'pinned your reply';
            $user_id = $reply->user_id;
            $notification = new Notification;
            $notification->slug = Notification::generateSlug();
            $notification->content = $content;
            $notification->read = false;
            $notification->links = $links;
            $notification->user_id = $user_id;
            $notification->save();
        }
        return new ReplyResource($reply);
    }
}
