<?php
namespace App\Helpers;

class ApiHelpers
{
    protected static $response = [
        'code' => null,
        'status' => null,
        'message' => null,
    ];

    public static function success($data = null, $message = null, $code = 200)
    {
        self::$response['code'] = $code;
        self::$response['status'] = 'success';
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }

    public static function error($error = null, $message = null, $code = 400)
    {
        self::$response['code'] = $code;
        self::$response['status'] = 'error';
        self::$response['message'] = $message;
        self::$response['error'] = $error;

        return response()->json(self::$response, self::$response['code']);
    }
}
