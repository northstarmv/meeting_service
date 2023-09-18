<?php

namespace App\Http\Controllers;

use App\Models\DoctorMeetings;
use App\Models\DoctorUser;
use App\Models\MeetingTrainerClients;
use App\Models\Notifications;
use App\Models\TrainerClientsMeetingData;
use App\Models\User;
use Carbon\Carbon;
use IClimber\LaravelZoomMeetings\Meeting;
use IClimber\LaravelZoomMeetings\Support\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MacsiDigital\Zoom\Facades\Zoom;
use OneSignal;

class MeetingController extends Controller
{

    public function newTrainerDoctorMeeting(Request $request): JsonResponse
    {
        $this->validate($request, [
            'trainer_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'start_time' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            DoctorMeetings::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'trainer_id' => $request->get('trainer_id'),
                'client_id' => $request->get('trainer_id'),
                'doctor_id' => $request->get('doctor_id'),
                'start_time' => Carbon::parse($request->get('start_time'))
            ]);

            Notifications::create([
                'sender_id' => $request->get('trainer_id'),
                'receiver_id' => $request->get('doctor_id'),
                'title' => 'Doctor Meeting Approved',
                'description' => 'Your meeting has been approved by the doctor',
            ]);


            OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                "You Have a New Notification!",
                [$request->get('doctor_id').""],
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );

            return response()->json([
                'success' => true,
                'message' => 'Meeting created successfully']);
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ],500);
        }
    }

    public function newClientDoctorMeeting(Request $request): JsonResponse
    {
        $this->validate($request, [
            'trainer_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'client_id' => 'required|integer',
            'start_time' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            DoctorMeetings::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'trainer_id' => $request->get('trainer_id'),
                'client_id' => $request->get('client_id'),
                'doctor_id' => $request->get('doctor_id'),
                'start_time' => Carbon::parse($request->get('start_time'))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Meeting created successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]
                , 500);
        }
    }

    // public function newTrainerClientMeeting(Request $request): JsonResponse
    // {
    //     $this->validate($request, [
    //         'title' => 'required|string',
    //         'description' => 'required|string',
    //         'clients' => 'required|array',
    //         'trainer_id' => 'required|numeric',
    //         'start_time' => 'required',
    //     ]);

    //     try {

    //         $clients = $request->get('clients');
    //         $user = Zoom::user()->find('ruWzAUvATLiKBptk3bzh3Q');
    //         $meeting = $user->meetings()->create([
    //             'topic' => $request->get('title'),
    //             'type' => 2,
    //             'start_time' => Carbon::parse($request->get('start_time')),
    //             'duration' => 60,
    //             'settings'=>[
    //                 'join_before_host'=>true,
    //                 'audio'=>'voip',
    //                 'auto_recording'=>'none', //set to cloud on paid plan
    //             ]
    //         ]);

    //         MeetingTrainerClients::create([
    //             'title' => $request->get('title'),
    //             'description' => $request->get('description'),
    //             'trainer_id' => $request->get('trainer_id'),
    //             'clients' => $request->get('clients'),
    //             'start_time' => Carbon::parse($request->get('start_time')),
    //             'meeting_id' => $meeting->id,
    //             'passcode' => $meeting->password,
    //         ]);

    //         foreach ($clients as $client) {
    //             TrainerClientsMeetingData::create([
    //                 'meeting_id' => $meeting->id,
    //                 'client_id' => $client,
    //                 'trainer_id' => $request->get('trainer_id'),
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function newTrainerClientMeeting(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'clients' => 'required|array',
            'trainer_id' => 'required|numeric',
            'start_time' => 'required',
        ]);

        try {

            $access_token = Auth::getToken();
           
            $clients = $request->get('clients');
            
            $meeting = Meeting::setAccessToken($access_token)->create([
                'topic' => $request->get('title'),
                'type' => 2,
                'start_time' => Carbon::parse($request->get('start_time')),
                'duration' => 60,
                'settings'=>[
                    'join_before_host'=>true,
                    'audio'=>'voip',
                    'auto_recording'=>'none', //set to cloud on paid plan
                ]
            ]);

            MeetingTrainerClients::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'trainer_id' => $request->get('trainer_id'),
                'clients' => $request->get('clients'),
                'start_time' => Carbon::parse($request->get('start_time')),
                'meeting_id' => $meeting["body"]["id"],
                'passcode' => $meeting["body"]["password"],
            ]);

            foreach ($clients as $client) {
                TrainerClientsMeetingData::create([
                    'meeting_id' => $meeting["body"]["id"],
                    'client_id' => $client,
                    'trainer_id' => $request->get('trainer_id'),
                ]);
            }

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //Accepts and Rejects
    public function doctorMeetingAccept($id):JsonResponse
    {
        try {
            $ApprovingMeeting = DoctorMeetings::where('id','=', $id)->first();

            $user = Zoom::user()->find('aRB5G37CRqm7scmoI4_6kw');
            $meeting = $user->meetings()->create([
                'topic' => $ApprovingMeeting->title,
                'type' => 2,
                'start_time' => new Carbon($ApprovingMeeting->start_time),
                'duration' => 60,
                'settings'=>[
                    'join_before_host'=>true,
                    'audio'=>'voip',
                    'auto_recording'=>'none', //set to cloud on paid plan
                ]
            ]);

            $ApprovingMeeting->approved = true;
            $ApprovingMeeting->meeting_id = $meeting->id;
            $ApprovingMeeting->passcode = $meeting->password;
            $ApprovingMeeting->save();

            Notifications::create([
                'sender_id' => $ApprovingMeeting->doctor_id,
                'receiver_id' => $ApprovingMeeting->client_id,
                'title' => 'Doctor Meeting Approved',
                'description' => 'Your meeting has been approved by the doctor',
            ]);

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function doctorMeetingReject($id): JsonResponse
    {
        try {
            $ApprovingMeeting = DoctorMeetings::where('id','=', $id)->first();

            $ApprovingMeeting->approved = false;
            $ApprovingMeeting->has_rejected = true;
            $ApprovingMeeting->save();

            Notifications::create([
                'sender_id' => $ApprovingMeeting->doctor_id,
                'receiver_id' => $ApprovingMeeting->client_id,
                'title' => 'Doctor Meeting Rejected',
                'description' => 'Your meeting has been rejected by the doctor',
            ]);


            OneSignal::setParam('priority', 10)->sendNotificationToExternalUser(
                "You Have a New Notification!",
                [$ApprovingMeeting->client_id.""],
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null
            );

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //Getting Trainer Client Meetings
    public function getTrainerClientMeetingsTrainer($id): JsonResponse
    {
        try {
            return response()->json(MeetingTrainerClients::with('owner')
                ->where('trainer_id', $id)
                ->where('start_time','>=',Carbon::now()->subHours(3))
                ->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTrainerClientMeetingsClient($id): JsonResponse
    {
        try {
            $Meetings = MeetingTrainerClients::whereDate('start_time','>=',Carbon::now()->subHours(3))
                ->whereRaw("JSON_CONTAINS(clients, '[".$id."]' )")
                ->get();

            foreach ($Meetings as $Meeting) {
                $Meeting->client = User::find($id);
            }

            return response()->json($Meetings);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    //Doctor Meetings
    public function getMyDoctorMeetings($user_id,$user_role): JsonResponse
    {
        try {
            if ($user_role == 'trainer'){
                return response()->json(
                    DoctorMeetings::whereDate('start_time', '>=', Carbon::now()->addMinutes(30)->toDateString())
                        ->where('trainer_id', $user_id)
                        ->where('has_rejected','=',false)
                        ->where('finished','=', false)
                        ->with('client')
                        ->get()
                );
            } else {
                return response()->json(
                    DoctorMeetings::whereDate('start_time', '>=', Carbon::now()->addMinutes(30)->toDateString())
                        ->where('client_id', $user_id)
                        ->where('has_rejected','=',false)
                        ->where('finished', false)
                        ->with('client')
                        ->get()
                );
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDoctorDoctorMeeting($doctor_id): JsonResponse
    {
        $ApprovedMeeting = DoctorMeetings::with('trainer')
            ->with('client')
            ->whereDate('start_time', '>=', Carbon::now()->subHour()->toDateString())
            ->where('doctor_id', $doctor_id)
            ->where('approved','=', true)
            ->where('has_rejected','=', false)
            ->where('finished','=', false)
            ->get();

        $PendingMeeting = DoctorMeetings::with('client')
            ->whereDate('start_time', '>=', Carbon::now()->toDateString())
            ->where('doctor_id', $doctor_id)
            ->where('approved','=', false)
            ->where('finished', '=', false)
            ->where('has_rejected','=', false)
            ->count();


        try {
            return response()->json([
                'doctor' => DoctorUser::find($doctor_id),
                'upcoming' => $ApprovedMeeting,
                'pending_count' => $PendingMeeting,
                'upcoming_count' => $ApprovedMeeting->count()
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getDoctorPendingDoctorMeeting($doctor_id): JsonResponse
    {
        $PendingMeeting = DoctorMeetings::with('client')
            ->whereDate('start_time', '>=', Carbon::now()->toDateString())
            ->where('doctor_id', $doctor_id)
            ->where('approved','=', false)
            ->where('has_rejected','=', false)
            ->where('finished','=', false)
            ->get();

        return response()->json([
            'meetings'=>$PendingMeeting
        ]);
    }

    public function getDoctorApprovedDoctorMeeting($doctor_id): JsonResponse
    {

        $ApprovedMeeting = DoctorMeetings::with('trainer')
            ->with('client')
            ->whereDate('start_time', '>=', Carbon::now()->subHour()->toDateString())
            ->where('doctor_id', $doctor_id)
            ->where('approved','=', true)
            ->where('has_rejected','=', false)
            ->where('finished','=', false)
            ->get();

        return response()->json([
            'meetings'=>$ApprovedMeeting
        ]);
    }

    public function getDoctorMeetingsHistory($doctor_id): JsonResponse
    {
        $ApprovedMeeting = DoctorMeetings::with('trainer')
            ->with('client')
            ->whereDate('start_time', '>=', Carbon::yesterday()->toDateString())
            ->where('doctor_id', $doctor_id)
            ->where('approved','=', true)
            ->where('finished','=', true)
            ->get();

        return response()->json($ApprovedMeeting);
    }

    public function doctorMeetingFinish($id): JsonResponse
    {
        DoctorMeetings::where('id','=', $id)
            ->update(['finished' => true]);

        return response()->json(['success' => 'Meeting finished']);
    }

}
