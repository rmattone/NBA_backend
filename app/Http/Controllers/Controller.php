<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($resource = null, $code = null): JsonResponse
    {
        if(empty($resource)){
            return response()->json($resource, 204);
        }

        return response()->json($resource, $code ?? 200);
    }
    
    protected function created($resource = null): JsonResponse
    {
        return response()->json($resource, 201);
    }
    
    protected function error($message = null, $context = [], $statusCode = 500, $code = null): JsonResponse
    {
        $message = !$message ? "Unknown error" : $message;

        $response = [
            'message' => $message
        ];

        if($code){
            $response['code'] = $code;
        }

        return response()->json($response, $statusCode);
    }

    

    
}
