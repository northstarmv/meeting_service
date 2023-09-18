<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\ClientChats;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OneSignal;

class ChatsController extends Controller
{
    public function sendMessageNotification(Request $request):JsonResponse
    {
        try {

            OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                "".$request->get('title'),
                $request->get('clients'),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );

            return response()->json(['success' => true]);
        } catch (\Exception $th) {
            return response()->json([
                'info' => $th->getMessage(),
                'success' => false]);
        }
    }

    public function createChat(Request $request){
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'chat_id' => 'required|string',
            'clients' => 'required|json',
            'type' => 'required|string',
        ]);

        try {
            $Chat = new Chats();
            $Chat->chat_id = $request->get('chat_id');
            $Chat->title = $request->get('title');
            $Chat->description = $request->get('description');
            $Chat->owner_id = $request->get('owner_id');
            $Chat->type = $request->get('type');
            $Chat->save();

            foreach (json_decode($request->get('clients'),true) as $client) {
                $ClientChat = new ClientChats();
                $ClientChat->chat_id = $Chat->chat_id;
                $ClientChat->client_id = $client;
                $ClientChat->save();


                OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                    "You Have been added to a new chat '".$request->get('title')."'",
                    $client,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
            }

            return response()->json(['success' => true, 'message' => 'Chat created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getChatData(Request $request):JsonResponse
    {
        $this->validate($request, [
            'chat_id' => 'required|string',
        ]);

        try {
            return response()->json(
                Chats::with('clients.user')->where('chat_id', $request->get('chat_id'))->first()
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function editChat(Request $request):JsonResponse
    {
        $this->validate($request, [
            'chat_id' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            Chats::find($request->get('chat_id'))->update([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
            ]);

            return response()->json(['success' => true, 'message' => 'Chat updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteChat(Request $request):JsonResponse
    {
        $this->validate($request, [
            'chat_id' => 'required|string',
        ]);

        try {
            Chats::find($request->get('chat_id'))->delete();
            return response()->json(['success' => true, 'message' => 'Chat updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addOrRemoveClient(Request $request):JsonResponse
    {
        $this->validate($request, [
            'chat_id' => 'required|string',
            'client_id' => 'required|integer',
            'action' => 'required|string', // ADD or REMOVE
            'title' => 'required|string',
        ]);

        try {
            if($request->get('action') == 'ADD'){
                $ClientChat = new ClientChats();
                $ClientChat->chat_id = $request->get('chat_id');
                $ClientChat->client_id = $request->get('client_id');
                $ClientChat->save();

                $ClientArray = ["".$ClientChat->client_id];


                OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                    "You Have been added to a new chat '".$request->get('title')."'",
                    $ClientArray,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
            }elseif ($request->get('action') == 'REMOVE'){
                ClientChats::where('chat_id', $request->get('chat_id'))
                    ->where('client_id', $request->get('client_id'))
                    ->delete();

                $ClientArray = ["".$request->get('client_id')];


                OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                    "You Have been removed from chat '".$request->get('title')."'",
                    $ClientArray,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
            }

            return response()->json(['success' => true, 'message' => 'Chat updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    public function getTrainerMyChats($owner_id):JsonResponse
    {
        return response()->json(
            Chats::with('clients.user')->where('owner_id', $owner_id)->get()
        );
    }

    public function getClientMyChats($user_id):JsonResponse
    {
        $CChats = ClientChats::where('client_id','=', $user_id)->get()->pluck('chat_id');
        $Chats = Chats::whereIn('chat_id', $CChats)->get();
        return response()->json($Chats);

        /*return response()->json(
            Chats::with(['clients'=> function($query) use ($user_id){
                $query->where('client_id', '=', $user_id);
            }])->get()
        );*/
    }
}
