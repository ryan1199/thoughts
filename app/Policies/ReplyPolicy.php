<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\Thought;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReplyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reply $reply): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reply $reply): Response
    {
        return $user->id == $reply->user_id ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reply $reply): Response
    {
        return $user->id == $reply->user_id ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reply $reply): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reply $reply): bool
    {
        //
    }
    public function pinned(User $user, Thought $thought, Reply $reply): Response
    {
        return $user->id == $thought->user_id && $thought->id == $reply->thought_id ? Response::allow() : Response::deny();
    }
    public function unpinned(User $user, Thought $thought, Reply $reply): Response
    {
        return $user->id == $thought->user_id && $thought->id == $reply->thought_id ? Response::allow() : Response::deny();
    }
}
