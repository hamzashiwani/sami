<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

    // Retrieve all notifications
            $skip = 0;
            $skip = $request->skip;
            $allNotifications = Notification::orderBy('created_at', 'desc')->skip($skip)->take(10)->get();

            // Map through notifications and check if the user has read them
            $notifications = $allNotifications->map(function ($notification) use ($user) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'description' => $notification->description,
                    'file' => $notification->file,
                    'file_screenshot' => $notification->file_screenshot,
                    'file_type' => $notification->file_type,
                    'created_at' => $notification->created_at,
                    'is_read' => $user->notifications()->where('notification_id', $notification->id)->exists(), // Check if read
                ];
            });
            // $skip = 0;
            // $skip = $request->skip;
            // $getUserData = Notification::orderBy('created_at', 'desc')
            //     ->skip($skip)->take(10)->get();
            return $this->respond($notifications, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function detail(Request $request)
    {
        try {
            $user = $request->user();
            $allNotifications = Notification::where('id', $request->id)->first();
            $user->notifications()->syncWithoutDetaching([$request->id]);
            return $this->respond($allNotifications, [], true, 'Success');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        // Get all notifications
        $allNotifications = Notification::all();

        // Attach all notifications to the user to mark them as read
        $user->notifications()->syncWithoutDetaching($allNotifications->pluck('id')->toArray());

        return $this->respond([], [], true, 'All notifications marked as read');
        // return response()->json(['message' => 'All notifications marked as read.']);
    }

}
