<?php

namespace App\Supports;

use Illuminate\Http\JsonResponse;
use Nette\Utils\Json;

class ApiResponse
{
    /**
     * Create a new class instance.
     */
    public static function success($data = null ,string $message = "Success", int $status = 200):JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ],$status);
    }
    public static function error(string $message = "Error", int $status = 400,$error = null ):JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $error,
        ],$status);
    }
    public static function single($data = null ,string $message = "Success", ):JsonResponse
    {
        return self::success($data,$message);
    }
    public static function collection($data = null ,string $message = "Success", ):JsonResponse
    {
        return self::success($data,$message);
    }
}
