<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class OneRouteService
{

    public static $oneRouteApiKey = "BQ6RR8R-2D74YBG-K6ZJD2F-7BBR18W";

    public static function sendSMS($message, $recipients, $from){
        $url = "https://api.oneroute.io/api/public/channel/$from/sms";

        $headers = [
            'Content-Type' => 'application/json',
            'apiKey' => self::$oneRouteApiKey,
        ];
        
        $phoneNumbers = explode(',', $recipients);

        $payload = [
            'message' => $message,
            'recipients' => $phoneNumbers,
        ];

        // return $payload;
        try{

            $response = Http::withHeaders($headers)->post($url, $payload);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 422);
        }

        
        if ($response->status() == 200) {
            $responseData = $response->json();

            return $responseData;
        } else {
            return response()->json(['error' => 'Failed to send SMS'], 422);
        }
    }
    
    public static function fetchChannels(){

        $url = "https://api.oneroute.io/api/public/channels/sms";

        $headers = [
            'Content-Type' => 'application/json',
            'apiKey' => self::$oneRouteApiKey,
        ];
        
        
        $response = Http::withHeaders($headers)->get($url);
        
        if ($response->status() == 200) {
            $responseData = $response->json();

            return $responseData['data'];
        } else {
            return response()->json(['error' => 'Failed to retrieve channels'], $response->status());
        }
    }

}
