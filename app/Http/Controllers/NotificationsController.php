<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OneSignal;

class NotificationsController extends Controller
{


    public function callInvoker(Request $request):JsonResponse
    {
        $this->validate($request, [
            'from' => 'required|integer',
            'to' => 'required|integer',
            'channel' => 'required|string',
        ]);
        try {
            OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                "Incoming Call!",
                [$request->get('to').""],
                $url = null,
                $data = [
                    'from' => User::find($request->get('from')),
                    'to' => User::find($request->get('to')),
                    'channel' => $request->get('channel'),
                ],
                $buttons = null,
                $schedule = null
            );
            return response()->json(['message'=>'calling Invoked'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createNotification(Request $request):JsonResponse
    {
        $this->validate($request,[
            'sender_id' => 'required|integer',
            'receiver_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:512',
        ]);

        try {

            if($request->get('sender_id') == 0){
                $senderID = 0;
            } else {
                $senderID = User::find($request->get('auth')['id'])->id;
            }



            $type = $request->get('type');
            if($type == null){
                $type = 'Common';
            }

            if($type == 'ClientRequest'){
                $HasPreviousRequests = Notifications::where('type','=', 'ClientRequest')
                    ->where('sender_id','=', $senderID)
                    ->where('receiver_id','=', $request->get('receiver_id'))
                    ->exists();

                if($HasPreviousRequests){
                    return response()->json(['error'=>'You have already sent a request to this trainer!'], 400);
                }
            }

            $NSData = Notifications::create([
                'sender_id' => $senderID,
                'receiver_id' => $request->get('receiver_id'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'type' => $type,
                'data' => $request->get('data'),
            ]);

            OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                $request->get('title'),
                [$request->get('receiver_id').""],
                $url = null,
                $data = $NSData,
                $buttons = null,
                $schedule = null
            );

            return response()->json(['message' => 'Notification created successfully']);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function markAllAsSeen($id):JsonResponse
    {
        Notifications::where('receiver_id','=', $id)->update(['has_seen' => true]);
        return response()->json(['success' => true]);
    }

    public function markOneAsSeen($id):JsonResponse
    {
        Notifications::where('id','=', $id)->update(['has_seen' => true]);
        return response()->json(['success' => true]);
    }

    public function deleteOneNotification($id):JsonResponse
    {
        try {
            Notifications::where('id','=', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteAllNotifications($id):JsonResponse
    {
        try {
            Notifications::where('receiver_id','=', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMyNotifications($id):JsonResponse
    {
        try {
            return response()->json(Notifications::where('receiver_id', $id)
                ->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllMyNotifications($id):JsonResponse
    {
        try {
            return response()->json(Notifications::where('receiver_id', $id)
                ->where('has_seen','=', false)
                ->orderBy('created_at', 'desc')
                ->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteRequests(Request $request):JsonResponse
    {
        try {
            $senderID = User::find($request->get('auth')['id'])->id;
            Notifications::where('sender_id','=',$senderID)->whereIn('type',[
                'ClientRequest',
                'TrainerRequest',
            ])->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
