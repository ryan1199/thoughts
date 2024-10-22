<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::query();
        if ($request->has('name')) {
            $users = $users->name($request->name);
        }
        if ($request->has('email')) {
            $users = $users->email($request->email);
        }
        if ($request->has('order')) {
            $columns = ['name', 'email', 'created'];
            $column = in_array($request->get('order'), $columns) ? $request->get('order') : 'created';
            $column = $column == 'created' ? 'created_at' : $column;
            if ($request->has('direction')) {
                $directions = ['asc', 'desc'];
                $direction = in_array($request->get('direction'), $directions) ? $request->get('direction') : 'desc';
            } else {
                $direction = 'desc';
            }
            $users = $users->orderBy($column, $direction);
        }
        if ($request->has('direction')) {
            $directions = ['asc', 'desc'];
            $direction = in_array($request->get('direction'), $directions) ? $request->get('direction') : 'desc';
            if ($request->has('order')) {
                $columns = ['name', 'email', 'created'];
                $column = in_array($request->get('order'), $columns) ? $request->get('order') : 'created';
                $column = $column == 'created' ? 'created_at' : $column;
            } else {
                $column = 'created_at';
            }
            $users = $users->orderBy($column, $direction);
        }
        if ($request->has('page')) {
            if ($request->get('page') == 'all') {
                $users = $users->get();
            } else {
                $users = (int) $request->get('page') > 0 ? $users->paginate($request->get('page'))->withQueryString() : $users->paginate(10)->withQueryString();
            }
        }
        if (!$request->has('page')) {
            $users = $users->paginate(10)->withQueryString();
        }
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('thoughts');
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->update($validated);
        $user->load('thoughts');
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', [User::class, $user]);
        $user->tokens()->delete();
        $user->delete();
        return response()->json([
           'message' => 'User deleted successfully.',
        ], 200);
    }
}
