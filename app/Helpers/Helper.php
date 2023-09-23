<?php

namespace App\Helpers;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class Helper
{
    /**
     * Get exception error response
     */
    public static function getErrorResponse($exception)
    {
        return response()->json([
            'status' => false,
            'msg'    => $exception->getMessage(),
        ]);
    }

    /**
     * Send mail
     */
    public static function sendMail($data)
    {
        Mail::send($data['view'], $data['viewParams'], function ($message) use ($data) {
            $message->to($data['mailTo'], $data['userName'])
                ->subject($data['subject'])
                ->from(env('MAIL_FROM_ADDRESS'), 'Adda');
        });

        return true;
    }

    /**
     * Generate random number
     */
    public static function generateRandomNumber()
    {
        return rand(1000, 9999);
    }
}