<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationCollection;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user)
    {
        $user->load('notifications');
        return new NotificationCollection($user->notifications);
    }
}
