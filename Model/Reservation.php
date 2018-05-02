<?php

App::uses('AppModel', 'Model');

class Reservation extends AppModel {

    public $primary_key = 'id';
    public $belongTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Shop' => array(
            'className' => 'Shop',
            'foreignKey' => 'shop_id'
        ),
        'UserShop',
        'Chair'=> array(
            'className' => 'Chair',
            'foreignKey' => 'chair_id'
        ),
        'Staff'=> array(
            'className' => 'Staff',
            'foreignKey' => 'staff_id'
        ),
    );

    public $hasMany = array(
        'Media' => array(
            'className' => 'Media',
            'dependent' => true,
            'foreignKey' => 'external_id'
            ),
        'ReservationTag' => array(
            'className' => 'ReservationTag',
            'dependent' => true,
            'foreignKey' => 'reservation_id'
            ),
        );

    public $validate = array(
        'date' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'required',
            ),
        )
    );



/**
     * Before Save
     * @param array $options
     * @return boolean
     */
    public function beforeSave($options = array())
    {

    }

    public function afterFind($results, $primary = false)
    {
        parent::afterFind($results, $primary);
        foreach ($results as $key => $val) {
            if (isset($val['Reservation']['treatment_cost']) && $val['Reservation']['treatment_cost'] == 0) {
                $results[$key]['Reservation']['treatment_cost'] = '';
            }
        }
        return $results;
    }

    public function getAppointmentDate($date = null, $reservation_id = null, $shop_id = null) {
        // Find all appointment by date
        // Configure::write('debug', 2);

        $reservation =  $this->findById($reservation_id);
        $Patient = 0;
        if ($reservation) {
            $Patient = ($reservation['Reservation']['child'] + $reservation['Reservation']['adult']);
            $reservation_id = $reservation['Reservation']['id'];
        }
        $allowChair = array();

        $scheduler['data'] = array();
        $date1 = $date;
        if (strpos($date, ',') !== false) {
            $date1 = str_replace(',', '-', $date);
        }
        $timeline = $this->find('all', array('conditions' => array(
            'DATE(Reservation.date)' => $date1,
            'Reservation.start <>' => null,
            'Reservation.end <>' => null,
            'Reservation.is_deleted <>' => 1,
            'Reservation.status <>' => 'cancel',
            'Reservation.shop_id' => $shop_id
        )));

        App::import('Model', 'Chair');
        $Chair = new Chair();
        $Chair->recursive = -1;
        $chairs = $Chair->getChairData('', $shop_id);
        $startDate = '8:00:00';
        $endDate = '23:45:00';
        foreach ($chairs as $key2 => $value2) {
            if ($value2['Chair']['capacity'] < $Patient) {
                // Disable all timeline which the capacity of the reservation is bigger than chair
                $scheduler['data'][$key2]['id'] = $value2['Chair']['id'];
                $scheduler['data'][$key2]['disadble'] = 0; // disable move or change for other timeline
                $scheduler['data'][$key2]['is_move'] = 0;
                $scheduler['data'][$key2]['start_date'] = $date1.' '.date('H:i:s', strtotime($startDate));
                $scheduler['data'][$key2]['end_date'] = $date1.' '.date("H:i:s", strtotime($endDate));
                $scheduler['data'][$key2]['text'] = '定員オーバーのためこのテーブルには設定できません';
                $scheduler['data'][$key2]['color'] = '#e0fad5 !important; color:#000 !important';
                $scheduler['data'][$key2]['seat_id'] = $value2['Chair']['id'];
            }

            if ($value2['Chair']['capacity'] >= $Patient) {
                // Allow these chair id
                $allowChair[] = $value2['Chair']['id'];
            }
        }

        if (empty($allowChair)) {
            // Re-order the array key
            $scheduler['data'] = array_values($scheduler['data']);
        }

        // get last array key of $scheduler['data']
        end($scheduler['data']);
        $newKey = key($scheduler['data']);
        if (!empty($timeline)) {
            App::import('Model', 'User');
            App::import('Model', 'Staff');
            App::import('Model', 'Tag');
            $User = new User();
            $Staff = new Staff();
            $Tag = new Tag();
            $User->recursive = -1;
            $Staff->recursive = -1;
            $Tag->recursive = -1;

            foreach ($timeline as $key1 => $value1) {
                if (empty($value1['Reservation']['start']) || empty($value1['Reservation']['end'])) {
                    continue;
                }

                if (!in_array($value1['Reservation']['chair_id'], $allowChair)) {
                    continue;
                }
                $key1 = $newKey + $key1 + 1;
                $scheduler['data'][$key1]['id'] = $value1['Reservation']['id'];
                $scheduler['data'][$key1]['disadble'] = ($value1['Reservation']['id'] == $reservation_id) ? 1 : 0; // disable move or change for other timeline
                $scheduler['data'][$key1]['is_move'] = ($value1['Reservation']['id'] == $reservation_id) ? 0 : 1;
                $scheduler['data'][$key1]['start_date'] = $date1.' '.date('H:i:s', strtotime($value1['Reservation']['start']));
                $scheduler['data'][$key1]['end_date'] = $date1.' '.date("H:i:s", strtotime($value1['Reservation']['end']));
                $scheduler['data'][$key1]['text'] = ($value1['Reservation']['id'] == $reservation_id) ? 'Edit' : '';
                $scheduler['data'][$key1]['color'] = ($value1['Reservation']['id'] === $reservation_id) ? 'green !important' : 'DarkGrey !important';
                $scheduler['data'][$key1]['seat_id'] = $value1['Reservation']['chair_id'];
                $scheduler['data'][$key1]['number_of_reservation'] = $value1['Reservation']['adult'] + $value1['Reservation']['child'];

                $user = $User->findById($value1['Reservation']['user_id']);
                $staff = $Staff->findById($value1['Reservation']['staff_id']);                
                if ($user) {
                    $scheduler['data'][$key1]['user_name'] = $user['User']['lastname'].'　'.$user['User']['firstname'];
                }
                if ($staff) {
                    $scheduler['data'][$key1]['staff_name'] = $staff['Staff']['name'];
                }
                if (!empty($value1['ReservationTag'][0]['tag_id'])) {
                    $tag = $Tag->findById($value1['ReservationTag'][0]['tag_id']);
                    if ($tag) {
                        $scheduler['data'][$key1]['tag_name'] = $tag['Tag']['tag'];
                    }
                }
            }
        }
        $scheduler['data'] = array_values($scheduler['data']);
        return json_encode($scheduler['data']);
    }

    public function isOverCapcity($chair_id = null, $patien = null, $key = null) {
        $match = array();
        App::import('Model', 'Chair');
        $Chair = new Chair();
        $Chair->recursive = -1;
        $chairs = $Chair->findById($chair_id);

        if ((int)$chairs['Chair']['capacity'] < (int)$patien) {
            $match = $chairs;
            $match['Chair']['key'] = $key;
        }
        
        return $match;
    }

    public function isExistAppoitmentDate($date = null, $reservation_id = null){
        $is_created = 0;

		App::import('Model', 'Shop');
		$Shop = new Shop();
		$Shop->recursive = -1;
		$Shops = $Shop->findByUserId(AuthComponent::user('id'));
		
        $timeline = $this->find('all', array(
			'conditions' => array(
				'DATE(Reservation.date)' => str_replace(',', '-', $date),
				'Reservation.start <>' => null,
				'Reservation.shop_id' => $Shops['Shop']['id']
			)
		));

        if (!empty($timeline)) {
            foreach ($timeline as $key1 => $value1) {
                if ($value1['Reservation']['id'] == $reservation_id) {
                    $is_created = 1;
                }
            }
        }

        return $is_created;
    }

    //timeline header must smaller than timline of json in one month
    public function getDateHeaderTimeline($date = null) {
        return date('Y,m,d', strtotime('-1 month', strtotime(str_replace(',', '-', $date))));
    }

    public function nextUrl($mode = null, $data = null, $action = null, $shop_id = '') {
        $url = '';
        if ($action == 'edit') {
            $action = '/reservations/edit/'. $data['reservation_id'];
        } else {
            if ( !isset($data['user_id']) ){
                $action = '/reservations/create/';
            } else {
                $action = '/reservations/create/'. $data['user_id'];
            }
        }

        switch ($mode) {
            case '':
                if (!empty($shop_id)) {
                    $url = $action . '?mode=appointment&shop_id=' . $shop_id;
                } else {
                    $url = $action . '?mode=appointment';
                }
                break;
            case 'appointment':
                if (!empty($shop_id)) {
                    $url = $action . '?mode=staff&shop_id=' . $shop_id;
                } else {
                    $url = $action . '?mode=staff';
                }
                break;
            case 'staff':
                if (!empty($shop_id)) {
                    $url = $action . '?mode=tag&shop_id=' . $shop_id;
                } else {
                    $url = $action . '?mode=tag';
                }
                break;
            case 'tag':
                if (!empty($shop_id)) {
                    $url = $action . '?mode=budget&shop_id=' . $shop_id;
                } else {
                    $url = $action . '?mode=budget';
                }
                break;
            case 'budget':
                if (isset($data['user_id'])) {
                    if (!empty($shop_id)) {
                        $url = $action . '?mode=confirm&shop_id=' . $shop_id;
                    } else {
                        $url = $action . '?mode=confirm';
                    }
                } else {
                    if (!empty($shop_id)) {
                        $url = $action . '?mode=user&shop_id=' . $shop_id;
                    } else {
                        $url = $action . '?mode=user';
                    }
                }
                break;
            case 'user':
                if (!empty($shop_id)) {
                    $url = $action . '?mode=confirm&shop_id=' . $shop_id;
                } else {
                    $url = $action . '?mode=confirm';
                }
                break;
            case 'confirm':
                $url = '/reservations/view/' . $data['user_id'];
                break;
            default:
                // Not found
                break;
        }

        return $url;
    }

    public function countIsCheckin($customer_id = null, $shop_id = null)
    {
        return $this->find('count', array(
            'conditions' => array(
                'Reservation.user_id' => $customer_id,
                'Reservation.shop_id' => $shop_id,
                'Reservation.is_checkin' => 1,
                'Reservation.is_deleted' => 0
            ),
            'recursive' => -1
        ));
    }

    protected function reservation_customer($user_id = null)
    {
        $this->User = ClassRegistry::init('User');
        return $this->User->find('first', array(
            'fields' => array('User.id', 'User.token', 'User.platform_type'),
            'conditions' => array('User.id' => $user_id),
            'recursive' => -1
        ));
    }

    /**
     * check if there any reservation that need to notify for tomorrow.
     * @return array
     */
    protected function is_reservation()
    {
        $date = new DateTime(date('Y-m-d'));
        $tomorrow = $date->modify('+1 day');
        $tomorrow = $date->format('Y-m-d');
        
        $reservations = $this->find('all', [
            'fields' => array(
                'Reservation.id',
                'Reservation.user_id',
                'Reservation.shop_id',
                'Reservation.date',
                'Reservation.start',
                'Reservation.end',
                'Reservation.is_pushed'
            ),
            'conditions' => array(
                'Reservation.is_checkin' => 0,
                'Reservation.is_deleted <> ' => 1,
                'Reservation.status' => 'visit',
                'Reservation.is_pushed <>' => 2,
                'DATE(Reservation.date) >= ' => date('Y-m-d'),
                'DATE(Reservation.date) <= ' => $tomorrow
            ),
            'recursive' => -1
        ]);

        return $reservations;
    }

    public function send_reservation_notification()
    {
        $reservations = $this->is_reservation();
        //pr($reservations); exit;
        if (!empty($reservations)) {
            foreach ($reservations as $reservation) {
                $this->Shop = ClassRegistry::init('Shop');
                $shop = $this->Shop->find('first', array(
                    'fields' => array('Shop.id', 'Shop.user_id', 'Shop.android_key', 'Shop.ios_ck_file'),
                    'conditions' => array(
                        'Shop.id' => $reservation['Reservation']['shop_id'],
                        'Shop.is_deleted' => 0
                    ),
                    'recursive' => -1
                ));
//pr($shop); exit;
                if (!empty($shop)) {
                    $this->News = ClassRegistry::init('News');
                    $news = $this->News->find('first', array( //find each reservation notification of each shop
                        'fields' => array('News.id', 'News.title', 'News.message', 'News.reservation_notice1', 'News.reservation_notice2'),
                        'conditions' => array(
                            'News.user_id' => $shop['Shop']['user_id'],
                            'News.type' => NOTICE_TYPE_RESERVATION,
                            'OR' => array(
                                array('News.is_disabled' => null),
                                array('News.is_disabled' => 0),
                                array('News.is_deleted' => null),
                                array('News.is_deleted' => 0)
                            ),
                        ),
                        'recursive' => -1
                    ));
//pr($news); exit;
                    //send 2h before the event start
                    if ($news['News']['reservation_notice1'] == 1) {
                        if (date('Y-m-d') == date('Y-m-d', strtotime($reservation['Reservation']['date']))) { //is equal today?
                            $rev_time = new DateTime($reservation['Reservation']['start']);
                            $current_time = new DateTime(date('H:i:s'));
                            $diff = $rev_time->diff($current_time);
                            //echo 'H' . $diff->format('%h-%i'); //exit;
                            if ($diff->format('%h') == 1 && ($diff->format('%i') >= 53 && $diff->format('%i') <= 59)) {
                                $customer = $this->reservation_customer($reservation['Reservation']['user_id']);
                                //pr($customer); exit;
                                if (!empty($customer)) {
                                    if ($customer['User']['platform_type'] == ANDROID_PLATFORM) {
                                        $this->send_android_notification($shop['Shop']['android_key'], $customer['User']['token'], $news['News']['title'], 1);
                                        //then update the is_push to 2
                                        $this->update_push_notification($reservation['Reservation'], 2);
                                    } else if ($customer['User']['platform_type'] == IOS_PLATFORM) {
                                        $this->send_ios_notification($shop['Shop']['ios_ck_file'], $customer['User']['token'], $news['News']['title'], 1);
                                        //then update the is_push to 2
                                        $this->update_push_notification($reservation['Reservation'], 2);
                                    }
                                }
                            }
                        }
                    }

                    //send 1day before the event start
                    if ($news['News']['reservation_notice2'] == 1 && $reservation['Reservation']['is_pushed'] == 0) {
                        $rev_date = new DateTime($reservation['Reservation']['date']);
                        $current_date = new DateTime(date('Y-m-d'));                        
                        $diff = $current_date->diff($rev_date);
                        //echo 'D' . $diff->format('%a'); exit;
                        if ($diff->format('%a') == 1) { //is it tomorrow?
                            $customer = $this->reservation_customer($reservation['Reservation']['user_id']);
                            //pr($customer); exit;
                            if (!empty($customer)) {
                                if ($customer['User']['platform_type'] == ANDROID_PLATFORM) {
                                    $this->send_android_notification($shop['Shop']['android_key'], $customer['User']['token'], $news['News']['title'], 1);
                                    //then update the is_push to 1
                                    $this->update_push_notification($reservation['Reservation'], 1);
                                } else if ($customer['User']['platform_type'] == IOS_PLATFORM) {
                                    $this->send_ios_notification($shop['Shop']['ios_ck_file'], $customer['User']['token'], $news['News']['title'], 1);
                                    //then update the is_push to 1
                                    $this->update_push_notification($reservation['Reservation'], 1);
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    protected function update_push_notification($reservation_id, $type)
    {
        $this->id = $reservation_id;
        if ($type == 1) {
            $this->saveField('is_pushed', 1); //push as one day before
        } else if ($type == 2) {
            $this->saveField('is_pushed', 2); //push as 2h before.
        }
    }

    /**
     * sending android notification
     * @param string $device_id
     * @param string $msg
     */
    public function send_android_notification($android_key, $device_id = null, $android_msg = null, $totalBadge = null)
    {
        if (!empty($android_key) && !empty($device_id)) {
            $url = 'https://android.googleapis.com/gcm/send';
            $message = array('message' => $android_msg, 'badge' => $totalBadge);
            $fields = array(
                'registration_ids' => array($device_id),
                'data' => $message,
                'content-available' => '1'
            );

            $headers = array(
                'Authorization: key=' . $android_key,
                'Content-Type: application/json'
            );
            $ch = curl_init();
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
//            $get_result = json_decode($result, true);
//            if ($get_result['failure'] === 1) {
//                die('Curl failed: '.curl_error($ch));
//            }
            // Close connection
            curl_close($ch);
        }
    }

    /**
     * sending iOS notification
     * @param string $deviceToken
     * @param string $message
     */
    public function send_ios_notification($iOSKey, $deviceToken = null, $message = null, $totalBadge = null)
    {
        if (!empty($iOSKey) && !empty($deviceToken)) {
            $passphrase = IOS_APP_NAME;
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', IOS_PATH . $iOSKey);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            // Open a connection to the APNS server
            $fp = stream_socket_client(IOS_URL, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            if (!$fp) {
                exit("Failed to connect: $err $errstr".PHP_EOL);
            }
            //echo 'Connected to APNS' . PHP_EOL;
            // Create the payload body
            $body['aps'] = array(
                'alert' => $message,
                'sound' => 'default',
                'content-available' => '1',
                'badge' => $totalBadge
            );
            // Encode the payload as JSON
            $payload = json_encode($body);
            // Build the binary notification
            $msg = chr(0).pack('n', 32).pack('H*', $deviceToken).pack('n', strlen($payload)).$payload;
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
//            if ($result === FALSE) {
//                die('Message not delivered' . PHP_EOL);
//            }
            // Close the connection to the server
            fclose($fp);
        }
    }
}
