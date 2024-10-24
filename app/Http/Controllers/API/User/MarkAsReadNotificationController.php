<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class MarkAsReadNotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user, Notification $notification)
    {
        $notification->read = true;
        $notification->save();
        $notification->load('user');
        return new NotificationResource($notification);
    }
}
