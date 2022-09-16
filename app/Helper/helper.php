<?php

namespace App\Helper;

class Helper
{

    public static function responseData($code, $message, $data=null)
    {
        if($data==null){
            return response()->json([
                'code' => $code,
                'message' => $message,
            ]);
        }
        
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function responseValidate($message)
    {
        return response()->json([
            'code' => 1,
            'error' => $message
        ]);
    }

    // function responseData($code, $message, $resultData = NULL, $statusCode = 200)
    // {
    //     $response = [
    //         'code' => $code,
    //     ];
    //     if ($message !== NULL && $code != config('apiconst.API_OK') && is_array($message) && isset($message['error_message'])) {
    //         $response['message'] = $message['error_message'];
    //     }
    //     if ($message !== NULL && $code == 2) {
    //         $response['message'] = config('apiconst.INVALID_PARAMETERS_MESS');
    //     } else if ($message !== NULL) {
    //         $response['message'] = $message;
    //     }
    //     if ($message !== NULL && $code == 2) {
    //         $response['messageObject'] = (object) $message;
    //     } else {
    //         $response['messageObject'] = (object)[];
    //     }
    //     if ($resultData !== NULL && is_array($resultData)) {
    //         $response['data'] = $resultData;
    //     } else if ($resultData !== NULL) {
    //         $response['data'] = (object) $resultData;
    //     }
    //     return response()->json($response, $statusCode, ['Content-Type' => 'application/json; charset=utf-8']);
    // }
}
