<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data = [], $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public function successwithCMSResponse($data = [], $cms = [], $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'cms'    => $cms,
            'data'    => $data,
        ], $code);
    }

    public function errorResponse($message = 'Something went wrong', $code = 400, $data = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }
}

