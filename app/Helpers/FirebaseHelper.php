<?php

namespace App\Helpers;

use App\Models\FirebaseToken;
use App\Exceptions\AppException;
use Exception;

class FirebaseHelper
{
    /** push notification */
    public static function pushFirebaseNotification($transaction,$notif_code)
    {
       
        try{

            $list_user_vending = $transaction->vendingMachine->userVendingMachine;
        }
        catch(\Throwable $th){
            $list_user_vending = $transaction->first()->vendingMachine->userVendingMachine;

        }
        $token = [];
        foreach ($list_user_vending as $key => $user_vending) {
            $list_firebase_token = FirebaseToken::where('user_id', $user_vending->user_id)->select('token')->get();
            foreach ($list_firebase_token as $firebase_token) {
                array_push($token, $firebase_token->token);
            }
        }

        self::pushFirebase($token, $transaction, $notif_code);
    }


    public static function pushFirebase($token, $transaction,$notif_code)
    {
        $ch = curl_init();
        $url = "https://fcm.googleapis.com/fcm/send";
        $api_key_access = 'AAAAAUcYlIE:APA91bEXv3zNxmHgtW35z7o_NXuHVNFS-WpJ-x2dKBYzkha3MwWLDOuMlGkEyTuzvTmXwCpEIHv1Ep81nNjKNjFE4uRKsqhuR3G78bAi-u76h_ZqxpptGEMc794DCZV65iuFBSydwhsO';

       

        // $body = array(
        //     'standId' => $transaction->vendingMachine->id,
        //     'typeOrder' => $label_order,
        // );

        /** mengambil body berdasarkan notif code  */
        $body=self::dataBodyOfNotifCode($transaction,$notif_code);

        $data = array(
            'registration_ids' => $token,
            'notification'=> array(
                'title'     => self::titleOfNotifCode($transaction,$notif_code),
                'body'   => 'silahkan cek aplikasi mu',
            ),
            'data' => $body
        );
      
        $curl_options = array(
          CURLOPT_URL => $url,
          CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json',
              'Authorization: key='.$api_key_access
          ),
          CURLOPT_RETURNTRANSFER => 1,
        );
      
        $curl_options[CURLOPT_POST] = 1;
        $curl_options[CURLOPT_POSTFIELDS] = json_encode($data);
        curl_setopt_array($ch, $curl_options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        info('success push');
        
        info ($result);
    }

    public static function dataBodyOfNotifCode($transaction,$notif_code){

        if( $notif_code=="take_food"){
            $ids="";
            foreach($transaction as $tr){
                $ids= $ids.$tr->id.";";
            }
            $body = array(
                'data' => $ids,
                'typeOrder' => "take"
            );
        }

        elseif($notif_code=="checkout"){
            $label_order = 'order';
            if ($transaction->is_preorder) {
                $label_order = 'preorder';
            }
            $body = array(
                'data' => $transaction->vendingMachine->id,
                'typeOrder' => $label_order
            );
        }

        return ($body);
    }

    public static function titleOfNotifCode($transaction,$notif_code){
        if($notif_code =="take_food"){
            $tittle="Seseorang telah berhasil mengambil menu mu";
        }
        elseif($notif_code=="checkout"){
            $label_order = 'order';
            if ($transaction->is_preorder) {
                $label_order = 'preorder';
            }
            $tittle="Seseorang telah ".$label_order." menu mu";
            
        }
        return($tittle);
    }
}
