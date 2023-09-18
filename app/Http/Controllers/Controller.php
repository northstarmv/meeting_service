<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function test(Request $request):JsonResponse
    {
        return response()->json([
            'message' => 'test',
            'user' => $request->all()
        ], 200);
    }
}
