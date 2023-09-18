<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseHelper;
use Yasser\Agora\RtcTokenBuilder;
use App\Helpers\ValidationErrrorHandler;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\User;

class RtcTokenGenerator extends Controller
{
    public function Generate(Request $request):JsonResponse
    {

        try{
            
            $validatedData = $this->validate($request, [
                'channelName' => 'required|string|min:1|max:100|xssPrevent',
            ]);
           
            try{

                $appID = env('AGORA_APP_ID');
                $appCertificate = env('AGORA_APP_CERTIFICATE');

                $channelName = $validatedData['channelName'];
                $user = DB::table('users')->select('name')->where('id','=', $request["auth"]["id"])->first(['name'])->name;

                $role = RtcTokenBuilder::RoleAttendee;
                $expireTimeInSeconds = env('ExpireTimeInSeconds');

                $currentTimestamp = Carbon::now('UTC');
                $privilegeExpiredTs = $currentTimestamp->addSeconds($expireTimeInSeconds)->toDateTimeString();
                $rtcToken = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);
                
                return response()->json([
                    ResponseHelper::success("200", [
                        'token' => $rtcToken
                    ],"success")
                        
                ]);

            }catch  (\Exception $e) {
                error_log($e);
                return response()->json(
                    [
                        ResponseHelper::error("500")
                    ], 200
                    );

            }
        }catch  (ValidationException  $e) {
            return response()->json(
                [
                    ResponseHelper::error( "0000", ValidationErrrorHandler::handle($e->validator->errors()) )
                ], 200
                );
        }
    }
}
