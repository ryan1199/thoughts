<?php

namespace App\Http\Controllers\API\Reply;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Reply\StoreReplyRequest;
use App\Http\Requests\API\Reply\UpdateReplyRequest;
use App\Http\Resources\ReplyCollection;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Thought;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Thought $thought, Request $request)
    {
        $replies = Reply::query()->with(['user', 'thought', 'replies', 'reply'])->where('thought_id', $thought->id)->where('replied_id', null);
        if ($request->has('content')) {
            $replies = $replies->content($request->content);
        }
        if ($request->has('pinned')) {
            $replies = $replies->pinned($request->pinned);
        }
        if ($request->has(['replied', 'replied_id'])) {
            $replies = $replies->replied($request->replied)->repliedId($request->replied_id);
        }
        if ($request->has('order')) {
            $columns = ['pinned', 'created'];
            $column = in_array($request->get('order'), $columns) ? $request->get('order') : 'created';
            $column = $column == 'created' ? 'created_at' : $column;
            if ($request->has('direction')) {
                $directions = ['asc', 'desc'];
                $direction = in_array($request->get('direction'), $directions) ? $request->get('direction') : 'desc';
            } else {
                $direction = 'desc';
            }
            $replies = $replies->orderBy($column, $direction);
        }
        if ($request->has('direction')) {
            $directions = ['asc', 'desc'];
            $direction = in_array($request->get('direction'), $directions) ? $request->get('direction') : 'desc';
            if ($request->has('order')) {
                $columns = ['pinned', 'created'];
                $column = in_array($request->get('order'), $columns) ? $request->get('order') : 'created';
                $column = $column == 'created' ? 'created_at' : $column;
            } else {
                $column = 'created_at';
            }
            $replies = $replies->orderBy($column, $direction);
        }
        if ($request->has('page')) {
            if ($request->get('page') == 'all') {
                $replies = $replies->get();
            } else {
                $replies = (int) $request->get('page') > 0 ? $replies->paginate($request->get('page'))->withQueryString() : $replies->paginate(10)->withQueryString();
            }
        }
        if (!$request->has('page')) {
            $replies = $replies->paginate(10)->withQueryString();
        }
        return new ReplyCollection($replies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request, Thought $thought)
    {
        $validated = $request->validated();
        $replied_id = Arr::exists($validated,'replied_id') ? $validated['replied_id'] : false;

        $reply = new Reply;
        $reply->slug = Reply::generateSlug();
        $reply->content = $validated['content'];
        $reply->pinned = false;
        $reply->user_id = auth('sanctum')->user()->id;
        $reply->replied = false;

        if ($replied_id) {
            $replied_reply = Reply::findOrFail($replied_id);
            if ($replied_reply->thought_id != $thought->id) {
                return response()->json(['message' => 'Reply does not belong to the thought'], 403);
            }
            $replied_reply->update([
                'replied' => true
            ]);

            $reply->thought_id = $replied_reply->thought_id;
            $reply->replied_id = $replied_id;
        } else {
            $reply->thought_id = $thought->id;
        }
        $reply->save();
        $reply->load(['user', 'thought', 'replies', 'reply']);
        return new ReplyResource($reply);
    }

    /**
     * Display the specified resource.
     */
    public function show(Thought $thought, Reply $reply)
    {
        $reply->load(['user', 'thought', 'replies', 'reply']);
        return new ReplyResource($reply);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReplyRequest $request, Thought $thought, Reply $reply)
    {
        $validated = $request->validated();
        if ($reply->thought_id != $thought->id) {
            return response()->json(['message' => 'Reply does not belong to the thought'], 403);
        }
        if ($reply->user_id != auth('sanctum')->user()->id) {
            return response()->json(['message' => 'Unauthorized to update this reply'], 403);
        }
        $edited_contents = $reply->edited_contents;
        $edited_contents[] = [
            'content' => $reply->content,
            'updated_at' => $reply->updated_at,
        ];
        $reply->update([
            'content' => $validated['content'],
            'edited_contents' => $edited_contents,
        ]);
        $reply->load(['user', 'thought', 'replies', 'reply']);
        return new ReplyResource($reply);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thought $thought, Reply $reply)
    {
        auth('sanctum')->user()->can('delete', [Reply::class, $reply]);
        if ($reply->thought_id!= $thought->id) {
            return response()->json(['message' => 'Reply does not belong to the thought'], 403);
        }
        $reply->delete();
        return response()->json([
           'message' => 'Reply deleted successfully.',
        ], 200);
    }
}
