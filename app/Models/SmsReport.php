<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SmsReport extends Model
{
    use HasFactory;
    // `user_id`, `msg_id`, `sender`, `destination`, `units`, `msg`, `status`,
    protected $fillable =[
        'user_id',
        'msg_id',
        'sender',
        'destination',
        'units',
        'msg',
        'status',
        'send_id',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function addreport()
    {
        $username=@get_setting('username')->value;
        $userpass=@get_setting('password')->value;
        $sms= SendMsg::where('msg_id','!=','error_limit')->where('msg_id','!=','error_msgid')->where('msg_id','!=','error_restricted_senderid')->get();
        foreach($sms as $item){
            $repo = explode("~", $item->msg_id);
            if($item->msg_id!='error_limit' && $item->msg_id!='error_msgid' && $item->msg_id!="") 
            {
                $price = explode(",", $item->msg_price);
                foreach($repo as $index => $nmbr){
                        $sms_id = explode(",", $nmbr);
                        $chk=SmsReport::where('destination',$sms_id[0])->where('msg_id',$item->id)->where('user_id',$item->user_id)->count();
                        if($chk==0){
                            $curl=curl_init("https://sms.hollatags.com/api/report");
                            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(["user"=>$username,"pass"=>$userpass,"msgid"=>$sms_id[1]]));
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                            $response= curl_exec($curl);
                            curl_close($curl);
                            $status = SmsReport::create([
                                'msg_id' => $item->id,
                                'user_id' => $item->user_id,
                                'destination' => $sms_id[0],
                                'send_id' => $sms_id[1],
                                'units' => $price[$index],
                                'status' => $response,
                            ]);
                        }
                        else{
                            $chk=SmsReport::where('user_id',$item->user_id)->where('status','PROCESSED')->get();
                            if (count($chk->toArray()) > 0){
                                foreach($chk as $i){
                                    $curl=curl_init("https://sms.hollatags.com/api/report");
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(["user"=>$username,"pass"=>$userpass,"msgid"=>$i->send_id]));
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                                    $response= curl_exec($curl);
                                    curl_close($curl);
                                    $report = SmsReport::find($i->id);
                                    $report->update([
                                        'status'=> $response,
                                    ]);
                                }
                            }
                        }
                }
            }
        }
        
        return "Report Done.";
    }

    public function getReport($msgid){
        $username=@get_setting('username')->value;
        $userpass=@get_setting('password')->value;
        $url = 'https://sms.hollatags.com/api/report'; //this is the url of the gateway's interface
        // return $username.$userpass.$url.$msgid;
        $ch = curl_init(); //initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); //set the url
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(["user"=>$username,"pass"=>$userpass,"msgid"=>$msgid])); //set the POST variables
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POST, 1); //set POST method
        $response = curl_exec($ch); // grab URL and pass it to the browser. Run the whole process and return the response
        curl_close($ch);
        return $response;
    }
}
