<?php

App::uses('AppModel', 'Model');

class ReservationSendPicture extends AppModel {

    public $primary_key = 'id';
    public $useTable = 'reservation_send_photos';
    
    /**
     * sending android notification
     * @param string $device_id
     * @param string $msg
     */
    public function send_android_notification($device_id, $android_msg,$totalBadge)
    {
        $url     = 'https://android.googleapis.com/gcm/send';
        $message = array('message' => $android_msg, 'badge' =>$totalBadge);
        $fields  = array(
            'registration_ids' => array($device_id),
            'data' => $message,
            'content-available'=>'1'
        );

        App::import('Model', 'Shop');
        $Shop = new Shop();
        $this_shop = $Shop->find('first', array(
            'conditions' => array(
                'user_id' => CakeSession::read('Auth.User.id')
            ),
            'recursive' => -1
        ));

        $headers = array(
            // 'Authorization: key=AIzaSyA0b0KFKhn9uJhHlPUbt99eDQrPlGBF6g8',
            'Authorization: key=' . $this_shop['Shop']['android_key'],
            'Content-Type: application/json'
        );
        $ch      = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            return json_encode(array(
                    'result' => FALSE,
                    'msg' =>' Curl failed: '.curl_error($ch),
                ));
        }
        // Close connection
        curl_close($ch);
        //echo $result;
    }
    /**
     * sending iOS notification
     * @param string $deviceToken
     * @param string $message
     */
    public function send_ios_notification($deviceToken, $message,$totalBadge)
    {
        App::import('Model', 'Shop');
        $Shop = new Shop();
        $this_shop = $Shop->find('first', array(
            'conditions' => array(
                'user_id' => CakeSession::read('Auth.User.id')
            ),
            'recursive' => -1
        ));
        
        $passphrase = IOS_APP_NAME;
        $ctx        = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', IOS_PATH . $this_shop['Shop']['ios_ck_file']);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(IOS_URL, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
          //  exit("Failed to connect: $err $errstr".PHP_EOL);
             return json_encode(array(
                    'result' => FALSE,
                ));
        }
        //echo 'Connected to APNS' . PHP_EOL;
        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default',
            'content-available'=>'1',
            'badge' =>$totalBadge
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0).pack('n', 32).pack('H*', $deviceToken).pack('n', strlen($payload)).$payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
         if ($result === FALSE) {
            return json_encode(array(
                    'result' => FALSE,
                    'msg' =>' Message not delivered '.PHP_EOL,
                ));
        }
        // Close the connection to the server
        fclose($fp);
    }
}
