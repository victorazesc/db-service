<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function getNotifications(Request $request)
    {
        if ($request->topbar) {
            $all = Notification::where('status', 0)->orWhere('status', null)->limit(10)->get();
        } else {
            $all = Notification::all();
        }

        return response()->json($all);
    }
    public function getNotificationsList(Request $request)
    {
        $all = Notification::paginate(25);
        return response()->json($all);
    }

    public function alterStatus(Request $request)
    {

        if ($request->status === 0 || $request->status === null) {
            $notification = Notification::where('id', $request->id)->update(
                [
                    'status' => '1',
                    'user' => '[]'
                ]
            );
            $request->status = 1;
        } else {
            $notification = Notification::where('id', $request->id)->update(
                [
                    'status' => '0',
                    'user' => '[]'
                ]
            );
            $request->status = 0;
        }

        return response()->json([
            'status' => $request->status
        ]);
    }
}
