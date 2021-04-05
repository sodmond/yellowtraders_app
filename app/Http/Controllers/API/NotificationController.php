<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvLogCollection;
use App\Http\Resources\TraderResource;
use App\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function get()
    {
        $notify = UserNotification::where('trader_id', auth()->user()->username)
                    ->orderBy('created_at', 'desc')
                    ->take(30)->get();
        //return new InvLogCollection($notify);
        $data = json_decode(json_encode($notify), true);
        $userNote = new UserNotification;
        return response()->json(['data' => $userNote->changeStatus($data)]);
    }

    public function markAsRead($id)
    {
        $note = UserNotification::where('id', $id)->update(['status' => 1]);
        if ($note) {
            return response()->json(['message' => 'Notification marked as read'], 200);
        }
        return response()->json(['message' => 'Oops! An error occured'], 200);
    }
}
