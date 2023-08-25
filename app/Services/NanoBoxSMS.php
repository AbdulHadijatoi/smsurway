<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NanoBoxSMS
{
    public static function sendSMS($messageContent,$destinationMsisdn, $from = null, $allowDelivery = true){

        $url = get_setting('nb_sms_url')->value;
        $nb_live_key = get_setting('nb_live_key')->value;
        if(!$from){
            $from = get_setting('nb_source')->value;
        }
        $nb_system_id = get_setting('nb_system_id')->value;
            
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer NB_live'.$nb_live_key,
        ];
        
        $phoneNumbers = explode(',', $destinationMsisdn);


        $payload = [
            'sourceMsisdn' => $from,
            'destinationMsisdn' => $phoneNumbers,
            'allowDelivery' => $allowDelivery,
            'messageContent' => $messageContent,
            'routeAuth' => [
                'systemId' => $nb_system_id,
            ],
        ];
        
        $response = Http::withHeaders($headers)->post($url, $payload);
        
        if ($response->status() == 200) {
            $responseData = $response->json();
            // return response()->json($responseData, 200);
            return $responseData;
        } else {
            return response()->json(['error' => 'Failed to send SMS'], $response->status());
        }
    }

}
