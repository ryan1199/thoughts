<?php

namespace App\Http\Controllers\API\Thought;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Thought\StoreThoughtRequest;
use App\Http\Requests\API\Thought\UpdateThoughtRequest;
use App\Http\Resources\ThoughtCollection;
use App\Http\Resources\ThoughtResource;
use App\Models\Thought;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ThoughtController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', only: ['store', 'update', 'destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $thoughts = Thought::query()->with('user');
        if ($request->has('topic')) {
            $thoughts = $thoughts->topic($request->topic);
        }
        if ($request->has('content')) {
            $thoughts = $thoughts->content($request->content);
        }
        if ($request->has('tags')) {
            $thoughts = $thoughts->tags($request->tags);
        }
        if ($request->has('open')) {
            $thoughts = $thoughts->open($request->open);
        }
        if ($request->has('order')) {
            $columns = ['topic', 'content', 'tags', 'open', 'created'];
            $column = in_array($request->get('order'), $columns) ? $request->get('order') : 'created';
            $column = $column == 'created' ? 'created_at' : $column;
            if ($request->has('direction')) {
                $directions = ['asc', 'desc'];
                $direction = in_array($request->get('direction'), $directions) ? $request->get('direction') : 'desc';
            } else {
                $direction = 'desc';
            }
            $thoughts = $thoughts->orderBy($column, $direction);
        }
        if ($request->has('direction')) {
            $directions = ['asc', 'desc'];
            $direction = in_array($request->get('direction'), $directions) ? $request->get('direction') : 'desc';
            if ($request->has('order')) {
                $columns = ['topic', 'content', 'tags', 'open', 'created'];
                $column = in_array($request->get('order'), $columns) ? $request->get('order') : 'created';
                $column = $column == 'created' ? 'created_at' : $column;
            } else {
                $column = 'created_at';
            }
            $thoughts = $thoughts->orderBy($column, $direction);
        }
        if ($request->has('page')) {
            if ($request->get('page') == 'all') {
                $thoughts = $thoughts->get();
            } else {
                $thoughts = (int) $request->get('page') > 0 ? $thoughts->paginate($request->get('page'))->withQueryString() : $thoughts->paginate(10)->withQueryString();
            }
        }
        if (!$request->has('page')) {
            $thoughts = $thoughts->paginate(10)->withQueryString();
        }
        return new ThoughtCollection($thoughts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThoughtRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();
        $thought = Thought::create([
            'user_id' => $user->id,
            'slug' => Thought::generateSlug(),
            'topic' => $validated['topic'],
            'content' => $validated['content'],
            'tags' => $validated['tags'],
            'open' => $validated['open'],
        ]);
        $thought->load('user');
        return new ThoughtResource($thought);
    }

    /**
     * Display the specified resource.
     */
    public function show(Thought $thought)
    {
        $thought->load('user');
        return new ThoughtResource($thought);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThoughtRequest $request, Thought $thought)
    {
        $validated = $request->validated();
        $thought->update($validated);
        $thought->load('user');
        return new ThoughtResource($thought);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thought $thought)
    {
        auth('sanctum')->user()->can('delete', [Thought::class, $thought]);
        $thought->delete();
        return response()->json([
            'message' => 'Thought deleted successfully.',
        ], 200);
    }
}
