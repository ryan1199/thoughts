<?php

namespace App\Http\Controllers\API\Reply;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReplyResource;
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
        return new ReplyResource($reply);
    }
}
