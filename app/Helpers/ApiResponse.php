<?php

namespace App\Helpers;

class ApiResponse
{
    public static function exito($data, $message = 'Operacion Exitosa')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public static function error($message, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}