<?php

namespace App\Http\Controllers;

use App\Models\ToDos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class TODOController extends Controller
{
    public function getTodos(Request $request): JsonResponse
    {
        return response()->json(ToDos::where('user_id', $request->get('user_id'))->get());
    }


    public function addTodo(Request $request): JsonResponse
    {
        $this->validate($request, [
            'todo' => 'required',
            'notes' => 'required',
            'endDate' => 'required',
            'user_id' => 'required',
        ]);
        try {
            $imagepath = "";
            if( $request->hasFile('image')){
                $allowedMimeTypes = [
                    'image/jpeg',
                    'image/png',
                    // Add other allowed MIME types here if needed
                ];
    
                $file_1 = $request->file('image');
                $mime_1 = $file_1->getClientMimeType();
    
                if (! (in_array($mime_1, $allowedMimeTypes)) ) {
                    
                    return response()->json(
                        [
                            ResponseHelper::error("0043")
                        ]
                        );
                }
                
                $disk = Storage::disk('s3');
                $fileName_1 =  'todo/'.uniqid('img_').'.'.$file_1->getClientOriginalExtension();
                $disk->put( $fileName_1, $file_1->getContent(), 'public');

                $imagepath = $fileName_1;
            }

            ToDos::create([
                'user_id' => $request->get('user_id'),
                'todo' => $request->get('todo'),
                'image' => $imagepath,
                'notes' => $request->get('notes'),
                'endDate' => $request->get('endDate'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Todo added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function completeTodo(Request $request): JsonResponse
    {
        ToDos::where('id','=',$request->get('id'))->update(['completed' => true]);
        return response()->json([
            'success' => true
        ]);
    }

    public function deleteTodo(Request $request): JsonResponse
    {error_log($request);
        $disk = Storage::disk('s3');
        $prev_img = ToDos::select('image')
                    ->where('id','=', $request->get('id'))
                    ->get()->first();
        
         if(!$prev_img){
            return response()->json(
                [
                    ResponseHelper::error("0045")
                ], 200
            );
        }

        if($prev_img->image != ""){
            $existFile = $disk->exists($prev_img->image );
                    if($existFile){
                        $disk->delete($prev_img->image );
                    }
        }
        ToDos::where('id','=',$request->get('id'))->delete();
        return response()->json([
            'success' => true
        ]);
    }

}
