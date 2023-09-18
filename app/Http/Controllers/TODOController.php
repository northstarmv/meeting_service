<?php

namespace App\Http\Controllers;

use App\Models\ToDos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            ToDos::create([
                'user_id' => $request->get('user_id'),
                'todo' => $request->get('todo'),
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
    {
        ToDos::where('id','=',$request->get('id'))->delete();
        return response()->json([
            'success' => true
        ]);
    }




}
