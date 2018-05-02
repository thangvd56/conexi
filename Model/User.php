<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel
{
    public $name = 'User';
    public $actsAs = array('Containable');    

    public $hasMany = array(
        'Stamp' => array(
            'dependent' => true
        ),
        'Reservation' => array(
            'dependent' => true
        ),
        'Shop' => array('dependent' => true),
        'UserShop' => array('dependent' => true)
    );

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Name is required'
            )
        ),
        'username' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Username is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This username is already exist',
                'on' => 'create'
            ),
            'unique_update' => array(
                'rule' => 'isUniqueUpdate',
                'message' => 'This username is already exist',
                'on' => 'update'
            )
        ),
        'username_update' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Password is required'
            ),
            'min_length' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Password must be at least 6 characters',
            )
        ),
        'password_update' => array(
            'min_length' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Password must be at least 6 characters',
                'allowEmpty' => true,
                'required' => false,
            )
        ),
        'role' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Role is required'
            )
        ),
        'status' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Status is required'
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => '無効なメールアドレスフォーマット',
                'allowEmpty' => true,
                'required' => false
            )
        ),
    );

    public function isUniqueUpdate($check, $value = null)
    {

        $username = $this->find('count', array(
            'conditions' => array(
                'User.id <>' => $this->data['User']['id'],
                'User.username' => $check
            ),
            'recursive' => -1
        ));
        return ($username == 0);
    }

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $BlowfishPasswordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $BlowfishPasswordHasher->hash(
                    $this->data[$this->alias]['password']
            );
        }
        if (isset($this->data[$this->alias]['password_update'])) {
            $BlowfishPasswordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $BlowfishPasswordHasher->hash(
                    $this->data[$this->alias]['password_update']
            );
        }
        return true;
    }

    public function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function generateModelId($n = null)
    {
        $random = ($n === null) ? 6 : $n;
        $modelId = $this->randomString($random);
 
        while ($this->isExistModelId($modelId)) {
            $modelId = $this->randomString($random);
        }
        return $modelId;
    }

    private function isExistModelId($modelId = null)
    {
        return !empty($this->findByModelIdChange($modelId)) ? true : false;
    }

    public function getListIdbyParrentId($parent_id)
    {
        $user_id_list = $this->find('list', [
            'conditions' => [
                'parent_id' => $parent_id,
                'confirmed' => 1,
                'role' => ROLE_SHOP,
                'status' => 1
            ]
        ]);
        return $user_id_list;
    }

    /**
     * get all user's id under a headquarter
     * @param integer $parent_id
     * @return array
     */
    public function getHeadquarterUserId($parent_id = null)
    {
        $users = $this->find('all', array(
            'fields' => array('User.id'),
            'conditions' => array(
                'parent_id' => $parent_id,
                'confirmed' => 1,
                'role' => ROLE_SHOP,
                'status' => 1
            ),
            'recursive' => -1
        ));

        if (!empty($users)) {
            return Hash::extract($users, '{n}.User.id');
        }
        return array();
    }

    public function getCountCustomer($conditions = array())
    {
        return $this->find('count', array(
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'left',
                    'conditions' => array(
                        'UserShop.user_id = User.id'
                    )
                )
            ),
            'contain' => array('UserShop'),
            'recursive' => -1
        ));
    }

    public function generateUniqueUserCode()
    {
        static $chars;
        if (!$chars) {
            $chars = array_flip(array_merge(range('a', 'z'), range('0', '9')));
        }
        $user_code = '';
        $attempt = 10;
        for ($i = 0; $i < 5; ++$i) {
            if ($attempt == 0) { //prevent infinite loop
                return null;
            }
            $user_code .= array_rand($chars);
            if ($i == 4) {
                $existing_user_code = $this->find('first', array(
                    'fields' => array('user_code'),
                    'conditions' => array('User.user_code' => $user_code),
                    'recursive' => -1
                ));

                if (!empty($existing_user_code)) {
                    $user_code = '';
                    $i = 0;
                    $attempt--;
                } else {
                    return $user_code;
                }
            }
        }
    }

    public function generateUniqueModelId()
    {
        static $chars;
        if (!$chars) {
            $chars = array_flip(array_merge(range('a', 'z'), range('0', '9')));
        }
        $model_id = '';
        $attempt = 10;
        for ($i = 0; $i < 6; ++$i) {
            if ($attempt == 0) { //prevent infinite loop
                return null;
            }
            $model_id .= array_rand($chars);
            if ($i == 5) {
                $existing_model_id = $this->find('first', array(
                    'fields' => array('model_id'),
                    'conditions' => array('User.model_id' => $model_id),
                    'recursive' => -1
                ));
                if (!empty($existing_model_id)) {
                    $model_id = '';
                    $i = 0;
                    $attempt--;
                } else {
                    return $model_id;
                }
            }
        }
    }

    /**
     * get all users for sending notification on the first day of the month
     * of his or her birthday.
     * @return array
     */
    protected function get_user_birthday_notification()
    {
        $user = $this->find('all', array(
            'fields' => array('User.id', 'User.birthday'),
            'joins' => array(
                array(
                    'table' => 'news_deliveries',
                    'alias' => 'NewsDelivery',
                    'type' => 'LEFT',
                    'conditions' => array('User.id = NewsDelivery.user_id')
                )
            ),
            'conditions' => array(
                'User.is_news_notification <>' => 0,
                'MONTH(User.birthday) = MONTH(CURRENT_DATE())', //a whole month of the user birthdate.
                'User.completed' => 1,
                'User.role' => ROLE_USER,
                'User.status' => USER_STATUS_ACTIVE
            ),
            'recursive' => -1            
        ));
        return $user;
    }    

    /**
     * check if user is not yet received the notification.
     * @return array|boolean
     */
    protected function get_delivery_notification()
    {
        $users = $this->get_user_birthday_notification();
        //pr($users); exit;
        if (!empty($users)) {
            $not_received_users = array();
            for ($i = 0; $i < count($users); $i++) {
                $this->NewsDelivery = ClassRegistry::init('NewsDelivery');
                $delivery = $this->NewsDelivery->find('first', array(
                    'fields' => array('NewsDelivery.news_id'),
                    'conditions' => array(
                        'NewsDelivery.notification_type' => BIRTHDAY_NOTIFICATION,
                        'NewsDelivery.user_id' => $users[$i]['User']['id'],
                        'YEAR(NewsDelivery.delivered_date) = YEAR(CURDATE())'
                    ),
                    'recursive' => -1
                ));
                
                if (empty($delivery)) {
                    $not_received_users[] = $users[$i]['User']['id'];
                }
            }
            return $not_received_users; //all users who not yet receive the notification
        }
        return false;
    }

    /**
     * find shop_id of owner who create the notification and its customers
     * @return array|boolean
     */
    protected function get_shop_owner()
    {
        $user_ids = $this->get_delivery_notification();
        //pr($user_ids); exit;
        if (!empty($user_ids)) {
            $this->UserShop = ClassRegistry::init('UserShop');
            $shops = $this->UserShop->find('all', array(
                'fields' => array('UserShop.shop_id', 'UserShop.user_id'),
                'conditions' => array(
                    'UserShop.user_id' => $user_ids,
                    'UserShop.type' => 'shop',
                    'UserShop.is_allow_notification' => 1,
                    'UserShop.is_disabled' => 0
                ),
                'group' => array('UserShop.shop_id'),
                'recursive' => -1
            ));

            if (!empty($shops)) {
                $arr_shop_id = array();
                $arr_customer_id = array();
                foreach($shops as $shop) {
                    $arr_shop_id[] = $shop['UserShop']['shop_id'];
                    $arr_customer_id[$shop['UserShop']['shop_id']][] = $shop['UserShop']['user_id'];
                }
                return array($arr_shop_id, $arr_customer_id);
            }
        }
        return false;
    }
    
    /**
     * send notification here.
     * @return array|boolean
     */
    public function send_customer_birthday_notification()
    {
        list($shop_ids, $customer_id) = $this->get_shop_owner();

//        pr($shop_ids);
//        pr($customer_id); exit;

        if (!empty($shop_ids)) {
            $this->Shop = ClassRegistry::init('Shop');
            $users = $this->Shop->find('all', array(
                'fields' => array('Shop.id', 'Shop.user_id', 'Shop.android_key', 'Shop.ios_ck_file'),
                'conditions' => array(
                    'Shop.id' => $shop_ids,
                    'Shop.is_deleted' => 0
                ),
                'group' => array('Shop.id'),
                'recursive' => -1
            ));

            if (!empty($users)) {
                foreach ($users as $user) {
                    $this->News = ClassRegistry::init('News');
                    $news = $this->News->find('first', array( //find each birthday notification of each shop
                        'fields' => array('News.id', 'News.title', 'News.message'),
                        'conditions' => array(
                            'News.user_id' => $user['Shop']['user_id'],
                            'News.type' => BIRTHDAY_NOTIFICATION,
                            'News.is_disabled <>' => 1
                        ),
                        'recursive' => -1
                    ));
                    if (!empty($news)) {                       
                        //find all customers of its shop
                        foreach ($customer_id as $key => $value) {
                            $customer = array();
                            if ($key == $user['Shop']['id']) {
                                foreach ($value as $v) {
                                    $customer[] = $v;
                                }

                                //now find all the customers of the shop
                                $cust = $this->find('all', [
                                    'fields' => array('User.id', 'User.token', 'User.platform_type'), //device_id and its platform.
                                    'conditions' => array('User.id' => $customer),
                                    'recursive' => -1
                                ]);
                                if (!empty($cust)) {
                                    foreach ($cust as $c) {
                                        if ($c['User']['platform_type'] == ANDROID_PLATFORM) {
                                            $this->send_android_notification($user['Shop']['android_key'], $c['User']['token'], NOTIFICATION_MSG_BIRTHDAY_OR_NEWS, 1);
                                            //then update the delivery
                                            $this->update_birthdate_delivery($c['User']['id'], $news['News']['id']);
                                        } else if ($c['User']['platform_type'] == IOS_PLATFORM) {
                                            $this->send_ios_notification($user['Shop']['ios_ck_file'], $c['User']['token'], NOTIFICATION_MSG_BIRTHDAY_OR_NEWS, 1);
                                            //then update the delivery
                                            $this->update_birthdate_delivery($c['User']['id'], $news['News']['id']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    protected function update_birthdate_delivery($customer_id, $news_id)
    {
        $this->NewsDelivery = ClassRegistry::init('NewsDelivery');
        $this->NewsDelivery->create();
        $data = array(
            'NewsDelivery' => array(
                'news_id' => $news_id,
                'user_id' => $customer_id,
                'is_deleted' => 0,
                'is_read' => 0,
                'is_published' => 1,
                'is_pushed' => 1,
                'delivered_date' => date('Y-m-d H:i:s'),
                'notification_type' => BIRTHDAY_NOTIFICATION
            )
        );
        return $this->NewsDelivery->save($data, false);
    }

    /**
     * sending android notification
     * @param string $device_id
     * @param string $msg
     */
    public function send_android_notification($android_key, $device_id = null, $android_msg = null, $totalBadge = null)
    {
        if (!empty($android_key)) {
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
//            if ($result === FALSE) {
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
        if (!empty($iOSKey)) {
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

    public function getSingleCustomer($user_id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
                'User.completed' => 1,
                'User.role' => ROLE_USER,
                'User.status' => USER_STATUS_ACTIVE
            ),
            'recursive' => -1
        ));
    }
}