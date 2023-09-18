<?php

namespace App\Http\Controllers;

use App\Models\DoctorMeetings;
use App\Models\MeetingTrainerClients;
use App\Models\ToDos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonEventsController extends Controller
{
    public function getMyAllEvents($user_id,$user_role):JsonResponse
    {
        try {
            $ROLECol = $user_role.'_id';

            $TrainerMeetingsMod = [];
            $DocAppointmentsMod = [];

            $DocAppointments = DoctorMeetings::whereDate('start_time', '>=', Carbon::now()->addMinutes(30)->toDateString())
                ->where($ROLECol,'=', $user_id)
                ->where('has_rejected','=',false)
                ->where('finished','=', false)
                ->with('client')
                ->with('doctor')
                ->get();

            foreach ($DocAppointments as $DA) {
                $DocAppointmentsMod[] = [
                    'id' => $DA->id,
                    'title' => $DA->title,
                    'description' => $DA->description,
                    'start' =>  Carbon::parse($DA->start_time),
                    'end' => Carbon::parse($DA->start_time)->addMinutes(60),
                    'owner'=>$DA->doctor->name,
                    'type'=>'DOCTOR_MEETING',
                ];
            }

            if($user_role == 'trainer'){
                $TrainerMeetings = MeetingTrainerClients::where('trainer_id', $user_id)
                    ->where('start_time','>=',Carbon::now()->subHours(3))
                    ->with('owner')
                    ->get();
            } else {
                $TrainerMeetings = MeetingTrainerClients::whereDate('start_time','>=',Carbon::now()->subHours(3))
                    ->whereRaw("JSON_CONTAINS(clients, '[".$user_id."]' )")
                    ->with('owner')
                    ->get();
            }

            foreach ($TrainerMeetings as $TM) {
                $TrainerMeetingsMod[] = [
                    'id' => $TM->id,
                    'title' => $TM->title,
                    'description' => $TM->description,
                    'start' => Carbon::parse($TM->start_time),
                    'end' => Carbon::parse($TM->start_time)->addMinutes(60),
                    'owner'=>$TM->owner->name,
                    'type'=>'TRAINER MEETING',
                ];
            }

            $Todos = ToDos::where('user_id','=', $user_id)->get();

            foreach ($Todos as $TD) {
                $TrainerMeetingsMod[] = [
                    'id' => $TD->id,
                    'title' => $TD->todo,
                    'description' => $TD->notes,
                    'start' => Carbon::parse($TD->end_date),
                    'end' => Carbon::parse($TD->end_date),
                    'owner'=>User::find($user_id)->name,
                    'type'=>'TODO',
                ];
            }
            $DATA = array_merge($DocAppointmentsMod, $TrainerMeetingsMod);
            $DATA = array_merge($DATA, $TrainerMeetingsMod);
            return response()->json($DATA);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
