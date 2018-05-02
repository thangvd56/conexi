<?php

App::uses('AppModel', 'Model');
class News extends AppModel
{
    public $name = 'News';
    public $hasMany = array(
        'NewsStatus' => array(
            'className' => 'NewsStatus',
            'dependent' => false
        ),
        'NewsDelivery' => array(
            'className' => 'NewsDelivery',
            'dependent' => false
        ),
        'AreaNoticeSetting' => array('foreignKey' => 'notice_id')
    );

    public $belongsTo = array(
        'User',
    );

    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Title is required'
            ),
            'min_length' => array(
                'rule' => array('minLength', '5'),
                'message' => 'title must be at least 5 characters',
            )
        ),
        'message' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Content is required'
            )
        ),
        'target' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Target is required'
            )
        ),
        'start' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Start time is required'
            )
        ),
        'end' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'End time is required'
            )
        ),
        'type' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Type is required'
            )
        ),
        'date' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Date is required'
            )
        ),
        'time' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Time is required'
            )
        ),
        'title' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'title is required'
            )
        ),
        'url' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'url is required'
            )
        ),
        'message' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Message is required'
            )
        )
    );

    /**
     * find all unsend news
     * @return array list
     */
    protected function notice_news()
    {
        $news = $this->find('all', array(
            'fields' => array(
                'News.id', 'News.user_id', 'News.title', 'News.message', 'News.delivery_date_value',
                'News.delivery_time_value', 'News.type', 'News.destination_target',
                'News.gender', 'News.target', 'News.area_id'
            ),
            'joins' => array(
                array(
                    'table' => 'news_deliveries',
                    'alias' => 'NewDelivery',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'NewDelivery.news_id = News.id'
                    )
                )
            ),
            'conditions' => array(
                'News.type' => NOTICE_TYPE_SETTING,
                'DATE(News.delivery_date_value)' => date('Y-m-d'),
                'News.delivery_time_value <=' => date('H:i'),
                'OR' => array(
                    array('News.is_deleted' => null),
                    array('News.is_deleted' => 0)
                ),
                'NewDelivery.news_id' => null
            ),
            'recursive' => -1
        ));
        $shop_owner_id = array();
        if (!empty($news)) {
            $shop_owner_id = array_unique(Hash::extract($news, '{n}.News.user_id'));
        }
        return array($news, $shop_owner_id);
    }

    /**
     * customers of each shop
     * @param integer $shop_id
     * @return array
     */
    protected function customer_of_each_shop($shop_id = null)
    {
        $this->UserShop = ClassRegistry::init('UserShop');
        $customers = $this->UserShop->find('all', array(
            'conditions' => array(
                'UserShop.shop_id' => $shop_id,
                'UserShop.is_allow_notification' => 1,
                'UserShop.is_disabled' => 0
            ),
            'recursive' => -1
        ));
        $customer_id = array();
        if (!empty($customers)) {
            $customer_id = array_unique(Hash::extract($customers, '{n}.UserShop.user_id'));
        }
        return $customer_id;
    }

    /**
     * send to customers of each shop base upon conditions.
     * @param integer $user_id
     * @param string $gender
     * @param integer $minAge
     * @param integer $maxAge
     * @return array
     */
    protected function all_customers($user_id = null, $gender = null, $minAge = null, $maxAge = null, $area_id = null)
    {
        $conditions = array('User.id' => $user_id);
        if (!is_null($gender)) {
            if ($gender == '男性') { //male
                $conditions['OR'] = array(
                    array('User.gender' => 'Male'),
                    array('User.gender' => '男性')
                );
            } else if ($gender == '女性') { //female
                $conditions['OR'] = array(
                    array('User.gender' => 'Female'),
                    array('User.gender' => '女性')
                );
            }
        }

        if (!is_null($area_id)) {
            $conditions[] = array('User.area_id' => $area_id);
        }

        if (!is_null($minAge) && !is_null($maxAge)) {
            $age = 'User.birthday <= now() - INTERVAL '.$minAge.' YEAR and User.birthday >= now() - INTERVAL '.$maxAge.' YEAR';
            $conditions[] = array($age);
        }
        $this->User = ClassRegistry::init('User');
        return $this->User->find('all', array(
            'fields' => array(
                'User.id', 'User.token', 'User.platform_type', 'User.reservation_badge'
            ),
            'conditions' => $conditions,
            'recursive' => -1
        ));
    }

    /**
     * Send all news to all customers of each shop
     */
    public function send_news_notification()
    {
        list($news, $shop_owner_id) = $this->notice_news();
        //pr($news); exit;
        //pr($shop_owner_id);
        if (!empty($shop_owner_id)) {
            $this->Shop = ClassRegistry::init('Shop');
            $shops = $this->Shop->find('all', array(
                'fields' => array('Shop.id', 'Shop.user_id', 'Shop.android_key', 'Shop.ios_ck_file'),
                'conditions' => array(
                    'Shop.user_id' => $shop_owner_id,
                    'Shop.is_deleted' => 0
                ),
                'recursive' => -1
            ));

            if (!empty($shops)) {
                foreach ($news as $new) {
                    if ($new['News']['destination_target'] == 'all') { //send to all customers
                        foreach ($shops as $shop) {
                            $customers = $this->customer_of_each_shop($shop['Shop']['id']); //pr($customers);
                            if ($customers) {
                                $cust = $this->all_customers($customers);
                                //pr($cust); exit;
                                if ($cust) {
                                    foreach ($cust as $c) {
                                        if ($c['User']['platform_type'] == ANDROID_PLATFORM) {
                                            //then create delivery table
                                            $news_bage = $this->update_news_delivery($new['News']['id'], $c['User']['id']);
                                            $reservation_badge = $this->reservation_badge($c['User']['id'], $shop['Shop']['id']);
                                            $total_badge = (int)($c['User']['reservation_badge'] + $news_bage + $reservation_badge);
                                            //pr($total_badge);
                                            $this->sendAndroidNotification($shop['Shop']['android_key'], $c['User']['token'], NOTIFICATION_MSG_BIRTHDAY_OR_NEWS, $total_badge);
                                        } else if ($c['User']['platform_type'] == IOS_PLATFORM) {
                                            //then create delivery table
                                            $news_bage = $this->update_news_delivery($new['News']['id'], $c['User']['id']);
                                            $reservation_badge = $this->reservation_badge($c['User']['id'], $shop['Shop']['id']);
                                            $total_badge = (int)($c['User']['reservation_badge'] + $news_bage + $reservation_badge);
                                            $this->sendiOSnotification($shop['Shop']['ios_ck_file'], $c['User']['token'], NOTIFICATION_MSG_BIRTHDAY_OR_NEWS, $total_badge);
                                        }
                                    }
                                }
                            }
                        }
                    } else if ($new['News']['destination_target'] == 'filter') { //send to selected customers
                        foreach ($shops as $shop) {
                            $customers = $this->customer_of_each_shop($shop['Shop']['id']); //pr($customers);
                            if ($customers) {
                                $max = null;
                                $min = null;
                                if (!empty($new['News']['target'])) {
                                    $age_range = explode(',', $new['News']['target']);
                                    if (count($age_range) > 1) {
                                        $a = explode('-', current($age_range));
                                        $min = $a[0];
                                        $b = explode('-', end($age_range));
                                        $max = $b[1];
                                    } else {
                                        $age = explode('-', $age_range[0]);
                                        $min = $age[0];
                                        $max = $age[1];
                                    }
                                }
                                
                                $cust = $this->all_customers($customers, $new['News']['gender'], $min, $max, $new['News']['area_id']);
                                //pr($cust); exit;
                                if ($cust) {
                                    foreach ($cust as $c) {
                                        if ($c['User']['platform_type'] == ANDROID_PLATFORM) {
                                            //then create delivery table
                                            $news_bage = $this->update_news_delivery($new['News']['id'], $c['User']['id']);
                                            $reservation_badge = $this->reservation_badge($c['User']['id'], $shop['Shop']['id']);
                                            $total_badge = (int)($c['User']['reservation_badge'] + $news_bage + $reservation_badge);
                                            //pr($total_badge);
                                            $this->sendAndroidNotification($shop['Shop']['android_key'], $c['User']['token'], NOTIFICATION_MSG_BIRTHDAY_OR_NEWS, $total_badge);
                                        } else if ($c['User']['platform_type'] == IOS_PLATFORM) {
                                            //then create delivery table
                                            $news_bage = $this->update_news_delivery($new['News']['id'], $c['User']['id']);
                                            $reservation_badge = $this->reservation_badge($c['User']['id'], $shop['Shop']['id']);
                                            $total_badge = (int)($c['User']['reservation_badge'] + $news_bage + $reservation_badge);
                                            $this->sendiOSnotification($shop['Shop']['ios_ck_file'], $c['User']['token'], NOTIFICATION_MSG_BIRTHDAY_OR_NEWS, $total_badge);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function reservation_badge($user_id, $shop_id)
    {
        $this->Reservation = ClassRegistry::init('Reservation');
        $badge = $this->Reservation->find('count', array(
            'conditions' => array(
                'AND' => array(
                    'Reservation.is_read' => 0,
                    'Reservation.user_id' => $user_id,
                    'Reservation.shop_id' => $shop_id,
                    'Reservation.is_completed' => 1,
                    'Reservation.is_deleted' => 0,
                    'Reservation.is_checkin' => 1
                )
            ),
            'recursive' => -1
        ));
        return $badge;
    }

    /**
     * update NewsDelivery of the news sent.
     * @param type $news_id
     * @param type $user_id
     * @return integer number of badges
     */
    protected function update_news_delivery($news_id, $user_id)
    {
        //news_deliveries
        $this->NewsDelivery = ClassRegistry::init('NewsDelivery');
        $this->NewsDelivery->create();
        $data = array(
            'NewsDelivery' => array(
                'user_id' => $user_id,
                'news_id' => $news_id,
                'delivered_date' => date('Y-m-d H:i:s'),
                'is_read' => 0,
                'is_published' => 1,
                'is_deleted' => 0,
                'notification_type' => NOTICE_TYPE_SETTING
            )
        );

        if ($this->NewsDelivery->save($data, false)) {
            return $this->NewsDelivery->find('count', array(
                'conditions' => array(
                    'NewsDelivery.user_id' => $user_id,
                    'NewsDelivery.is_read' => 0,
                ),
                'recursive' => -1
            ));
        }
        return 0;
    }

    /**
     * @deprecated since version 1
     */
    public function sendNews()
    {
        $this->Behaviors->load('Containable');
        //get notification which is not expire.
        $lst_notifications = $this->find('all', array(
            'contain' => array(
                'User' => array(
                    'conditions' => array('User.status' => 1),
                ),
                'AreaNoticeSetting'
            ),
            'conditions' => array(
                'News.type' => NOTICE_TYPE_SETTING,
                'date(News.delivery_date_value)' => date('Y-m-d'),
                'News.delivery_time_value >=' => date('H:i'),
            )
        ));

        if ($lst_notifications) {
            App::import('Model', 'Shop');
            App::import('Model', 'UserShop');
            App::import('Model', 'NewsDelivery');
            App::import('Model', 'User');
      
            $obj_shop = new Shop();
            $obj_user = new User();
            $obj_user_shop = new UserShop();
            $obj_news_delivery = new NewsDelivery();
      
            $obj_shop->recursive = -1;
            $obj_news_delivery->recursive = -1;
            $obj_user_shop->recursive = -1;
            $obj_user->recursive = -1;

            foreach ($lst_notifications as $key => $value) {
                $shop = $obj_shop->findByUserId($value['News']['user_id']);
                if (!$shop || !$value['News']['user_id']) {
                    continue;
                }

                //get user that already exite this notification
                $lst_notice_user_id = $obj_news_delivery->find('list', array(
                    'fields' => 'user_id',
                    'conditions' => array(
                        'news_id' => $value['News']['id']
                    )
                ));

                $conditions = array();
                if ($value['News']['destination_target'] != 'all') {
                    if ($value['News']['target']) {
                        $lst_age = explode(",", $value['News']['target']);
                        foreach($lst_age as $key1 => $value1) {
                            $ages = explode("-", $value1);
                            $conditions['OR'][] = array(
                                'User.birthday >=' => date('Y-m-d', strtotime(date('Y-m-d').' -'.$ages[1].' year')),
                                'User.birthday <=' => date('Y-m-d', strtotime(date('Y-m-d').' -'.$ages[0].' year'))
                            );
                        }
                    }
                    if ($value['News']['gender'] && $value['News']['gender'] != '全て対象') {
                        if ($value['News']['gender'] == FEMALE || $value['News']['gender'] == '女性') {
                            $conditions['User.gender'] = array(FEMALE, '女性');
                        } else if ($value['News']['gender'] == MALE || $value['News']['gender'] == '男性') {
                            $conditions['User.gender'] = array(MALE, '男性');
                        }
                    }
                    if ($value['AreaNoticeSetting']) {
                        $lst_area_id = array();
                        foreach ($value['AreaNoticeSetting'] as $key2 => $value2) {
                            $lst_area_id[] = $value2['area_id'];
                        }
                        $conditions['User.area_id'] = $lst_area_id;
                    }
                }

                //get users of this notification shop
                $lst_user_id = $obj_user_shop->find('list', array(
                    'fields' => 'user_id',
                    'conditions' => array(
                        'UserShop.user_id not' => $lst_notice_user_id,
                        'UserShop.shop_id' => $shop['Shop']['id'],
                        'UserShop.is_allow_notification' => 1,
                        'UserShop.is_disabled <>' => 1
                    )
                ));
                
                $conditions['User.id'] = $lst_user_id;
                $conditions['User.role'] = 'user';

                $users = $obj_user->find('all', array(
                    'conditions' => $conditions,
                    'recursive' => -1
                ));

                if ($users) {
                    foreach ($users as $key3 => $value3) {
                        $obj_news_delivery->create();
                        $data = array();
                        $data['NewsDelivery']['user_id'] = $value3['User']['id'];
                        $data['NewsDelivery']['news_id'] = $value['News']['id'];
                        $data['NewsDelivery']['delivered_date'] = $value['News']['delivery_date_value'].' '.$value['News']['delivery_time_value'].':00';
                        $data['NewsDelivery']['is_read'] = 0;
                        $data['NewsDelivery']['is_published'] = 1;
                        $data['NewsDelivery']['is_deleted'] = 0;

                        $obj_news_delivery->save($data);
                    }
                    $this->News->id = $value['News']['id'];
                    $this->News->saveField('is_disabled', 1, false);
                }
            }
        }
        $this->pushToMobile();
    }

    public function pushToMobile()
    {
        App::import('Model', 'Reservation');
        App::import('Model', 'NewsDelivery');
        App::import('Model', 'User');
        App::import('Model', 'Shop');
        App::import('Model', 'UserShop');

        $Reservation = new Reservation();
        $NewsDelivery = new NewsDelivery();
        $obj_user = new User();
        $obj_user_shop = new UserShop();
        $obj_shop = new Shop();

        $lst_news_id = $this->find('list', array(
            'fields' => 'id',
            'conditions' => array(
                'News.type' => 'notice_settings',
                'date(News.delivery_date_value)' => date('Y-m-d'),
                'News.delivery_time_value <=' => date('H:i'),
            )
        ));

        $data_news_delivery = $NewsDelivery->find('all', array(
            'conditions' => array(
                'NewsDelivery.news_id' => $lst_news_id,
                'NewsDelivery.is_pushed <>' => 1,
                'NewsDelivery.delivered_date <=' => date('Y-m-d H:i')
            )
        ));

        foreach($data_news_delivery as $key => $value) {

            $value['NewsDelivery']['is_pushed'] = 1;
            $NewsDelivery->save($value);

            $user = $obj_user->find('first', array(
                'conditions' => array(
                    'User.id' => $value['NewsDelivery']['user_id'],
                    'User.is_news_notification' => 1,
                    'User.role' => 'user'
                )
            ));

            if (!$user) {
                continue;
            }
            $news = $this->findById($value['NewsDelivery']['news_id']);
            if (!$news) {
                continue;
            }
            $shop = $obj_shop->findByUserId($news['News']['user_id']);
            if (!$shop) {
                continue;
            }
            $user_shop = $obj_user_shop->find('first', array(
                'conditions' => array(
                    'UserShop.user_id' => $user['User']['id'],
                    'UserShop.shop_id' => $shop['Shop']['id'],
                    'UserShop.is_allow_notification' => 1
                )
            ));
            if (!$user_shop) {
                continue;
            }

            $reservation_badge = $Reservation->find('count', array(
                'conditions' => array(
                    'AND' => array(
                        'Reservation.is_read' => 0,
                        'Reservation.user_id' => $user['User']['id'],
                        'Reservation.shop_id' => $shop['Shop']['id'],
                        'Reservation.is_completed' => 1,
                        'Reservation.is_deleted' => 0,
                        'Reservation.is_checkin' => 1
                    )
                ),
                'recursive' => -1
            ));
            // Get unread from NewDelivery

            $news_badge = $NewsDelivery->find('count', array(
                'conditions' => array(
                    'NewsDelivery.user_id' => $user['User']['id'],
                    'NewsDelivery.is_read' => 0,
                )
            ));

            $totalBadge = (int)($user['User']['reservation_badge'] + $news_badge + $reservation_badge);

            if ($user['User']['platform_type'] === ANDROID_PLATFORM) {
                echo $this->send_android_notification($shop['Shop']['id'], $user['User']['token'], $news['News']['title'], $totalBadge);
            } else if ($user['User']['platform_type'] === IOS_PLATFORM) {
                echo $this->send_ios_notification($shop['Shop']['id'], $user['User']['token'], $news['News']['title'], $totalBadge);
            }
        }
    }

    public function sendReservationNotice()
    {
        $dt = new DateTime();
        //Send two hour before
        $time = $dt->format('H:i').':00';
        //Add two hour for current time
        $minutes = strtotime('+120 minutes', strtotime($time));

        $send_twohour_before = date('H:i', $minutes);
        //+----+Send One day before+----+//
        $send_oneday_before = date('Y-m-d', strtotime($dt->format('Y/m/d').'-1 days')); //Add one day for current server date
        $start = strtotime('0 minutes', strtotime($time));
        $start_hour = date('H:i', $start);
        $this->Behaviors->load('Containable');
        $this->recursive = 3;
        $advance_notice = $this->find('all', array(
            'contain' => array(
                'User' => array(
                    'conditions' => array('User.status' => 1),
                    'Shop' => array(
                        'UserShop' => array(
                            'conditions' => array('UserShop.is_disabled' => 0),
                        ),
                    )
                )
            ),
            'conditions' => array('News.type' => 'reservation_notice')
        ));

        foreach ($advance_notice as $key1 => $value1) { //Get Advance notice info
            if (!isset($value1['User']['Shop'])) {
                continue;
            }
            foreach ($value1['User']['Shop'] as $key2 => $value2) { //Get shop info
                if (!isset($value2['UserShop'])) {
                    continue;
                }
                if ($value1['User']['is_medical_notification']==0) {
                    continue;
                }
                foreach ($value2['UserShop'] as $key3 => $value3) { //Get UserShop info
                    $this->User->recursive = -1;
                    $getUser = $this->User->findById($value3['user_id']);
                    if (!isset($getUser['User'])){
                        continue;
                    }
                    if ($value3['is_allow_notification']==0) {
                        continue;
                    }
                    if ($getUser['User']['is_medical_notification']==0) {
                        continue;
                    }
                    App::import('Model', 'Reservation');
                    $Reservation = new Reservation();
                    App::import('Model', 'NewsDelivery');
                    $NewsDelivery = new NewsDelivery();
                    //Send reservation 2 hour before [ON]
                    if ($value1['News']['reservation_notice1'] == 1) {
                        $getReservationHour = $Reservation->find('first', array(
                            'conditions' => array(
                                'Reservation.user_id' => $getUser['User']['id'],
                                'Reservation.date' => $dt->format('Y-m-d'),
                                'Reservation.start' => $send_twohour_before,
                                'Reservation.is_checkin' => 0,
                                'Reservation.is_deleted <> ' => 1,
                                'Reservation.status' => 'visit',
                                'Reservation.is_pushed' => 0,
                            )
                        ));

                        if (!empty($getReservationHour)) {
                            $Reservation->id = $getReservationHour['Reservation']['id'];
                            $Reservation->saveField('is_pushed',1);
                            $platform_type = $getUser['User']['platform_type'];
                            $device_id = $getUser['User']['token'];
                            //------ Notification badge mobile
                            //update user badge
                            $userBadge = (int)($getUser['User']['reservation_badge'] + 1);
                            $this->User->id = $getUser['User']['id'];
                            $this->User->save(array('User' => array('reservation_badge' => $userBadge)));
                            //Get reservation badge
                            $reservation_badge = $Reservation->find('count', array(
                                'conditions' => array(
                                    'AND' => array(
                                        'Reservation.is_read' => 0,
                                        'Reservation.user_id' => $getUser['User']['id'],
                                        'Reservation.shop_id' => $value3['shop_id'],
                                        'Reservation.is_completed' => 1,
                                        'Reservation.is_deleted' => 0,
                                        'Reservation.is_checkin' => 1
                                    )
                                ),
                                'recursive' => -1
                            ));
                            //Get NewDelivery
                            $news_badge = $NewsDelivery->find('count', array(
                            'conditions' => array(
                                'NewsDelivery.user_id' => $getUser['User']['id'],
                                'NewsDelivery.is_read' => 0,
                            )
                            ));
                            $totalBadge = (int)($userBadge + $reservation_badge + $news_badge );
                            if ($platform_type == 'android') {
                                $android_msg = $value1['News']['title'];
                                echo $this->send_android_notification($value3['shop_id'], $device_id,$android_msg,$totalBadge);
                            } elseif ($platform_type == 'ios') {
                                $ios_msg = $value1['News']['title'];
                                echo $this->send_ios_notification($value3['shop_id'], $device_id,$ios_msg,$totalBadge);
                            }
                        }
                    }
                    //Send reservation 1 day before [ON]
                    if ($value1['News']['reservation_notice2'] == 1) {
                        $getReservationDay = $Reservation->find('first', array(
                            'conditions' => array(
                                'Reservation.user_id' => $getUser['User']['id'],
                                'Reservation.date' => $send_oneday_before,
                                'Reservation.start' => $start_hour,
                                'Reservation.is_checkin' => 0,
                                'Reservation.is_deleted <> ' => 1,
                                'Reservation.is_pushed' => 0,
                            )
                        ));
                        if (!empty($getReservationDay)) {
                            $Reservation->id = $getReservationDay['Reservation']['id'];
                            $Reservation->saveField('is_pushed',1);
                            $platform_type = $getUser['User']['platform_type'];
                            $device_id = $getUser['User']['token'];
                            //------ Notification badge mobile
                            //update user badge
                            $userBadge = (int)($getUser['User']['reservation_badge'] + 1);
                            $this->User->id = $getUser['User']['id'];
                            $this->User->save(array('User' => array('reservation_badge' => $userBadge)));
                            //Get reservation badge
                            $reservation_badge = $Reservation->find('count', array(
                                'conditions' => array(
                                    'AND' => array(
                                        'Reservation.is_read' => 0,
                                        'Reservation.user_id' => $getUser['User']['id'],
                                        'Reservation.shop_id' => $value3['shop_id'],
                                        'Reservation.is_completed' => 1,
                                        'Reservation.is_deleted' => 0,
                                        'Reservation.is_checkin' => 1
                                    )
                                ),
                                'recursive' => -1
                            ));
                            //Get NewDelivery
                            $news_badge = $NewsDelivery->find('count', array(
                            'conditions' => array(
                                'NewsDelivery.user_id' => $getUser['User']['id'],
                                'NewsDelivery.is_read' => 0,
                            )
                            ));
                            $totalBadge = (int)($userBadge + $news_badge + $reservation_badge);
                            if ($platform_type === 'android') {
                                $android_msg = $value1['News']['title'];
                                echo $this->send_android_notification($value3['shop_id'], $device_id,$android_msg,$totalBadge);
                            } elseif ($platform_type === 'ios') {
                                $ios_msg = $value1['News']['title'];
                                echo $this->send_ios_notification($value3['shop_id'], $device_id,$ios_msg,$totalBadge);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * sending android notification
     * @param string $device_id
     * @param string $msg
     */

    public function send_android_notification($shop_id, $device_id = null, $android_msg = null, $totalBadge = null)
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
        $Shop->recursive = -1;
        $shop = $Shop->findById($shop_id);

        $headers = array(
            //'Authorization: key=AIzaSyAjmbdHquCyMljjlON4VRaZ2CVpdlSVDmY',
            // 'Authorization: key=AIzaSyA0b0KFKhn9uJhHlPUbt99eDQrPlGBF6g8',
            'Authorization: key=' . $shop['Shop']['android_key'],
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
            die('Curl failed: '.curl_error($ch));
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

    public function send_ios_notification($shop_id, $deviceToken = null, $message = null, $totalBadge = null)
    {
        App::import('Model', 'Shop');
        $Shop = new Shop();
        $Shop->recursive = -1;
        $shop = $Shop->findById($shop_id);

        if ($shop['Shop']['ios_ck_file']){
        $passphrase = IOS_APP_NAME;
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', IOS_PATH . $shop['Shop']['ios_ck_file']);
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
	        'content-available'=>'1',
            'badge'=> $totalBadge
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0).pack('n', 32).pack('H*', $deviceToken).pack('n', strlen($payload)).$payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
         if ($result === FALSE) {
            die('Message not delivered' . PHP_EOL);
        }
        // Close the connection to the server
        fclose($fp);
        }
    }

    public function notifications()
    {
        $this->Behaviors->load('Containable');
        //get notification which is not expire.
        $lst_notifications = $this->find('all', array(
            'contain' => array(
                'User' => array(
                    'conditions' => array('User.status' => 1),
                ),
                'AreaNoticeSetting'
            ),
            'conditions' => array(
                'News.type' => 'notice_settings',
                'date(News.delivery_date_value)' => date('Y-m-d'),
                'News.delivery_time_value >=' => date('H:i'),
            )
        ));

        if ($lst_notifications) {
            App::import('Model', 'Shop');
            App::import('Model', 'UserShop');
            App::import('Model', 'NewsDelivery');
            App::import('Model', 'User');

            $obj_shop = new Shop();
            $obj_user = new User();
            $obj_user_shop = new UserShop();
            $obj_news_delivery = new NewsDelivery();

            $obj_shop->recursive = -1;
            $obj_news_delivery->recursive = -1;
            $obj_user_shop->recursive = -1;
            $obj_user->recursive = -1;

            foreach ($lst_notifications as $key => $value) {
                $shop = $obj_shop->findByUserId($value['News']['user_id']);
                if (!$shop || !$value['News']['user_id']) {
                    continue;
                }

                //get user that already exite this notification
                $lst_notice_user_id = $obj_news_delivery->find('list', array(
                    'fields' => 'user_id',
                    'conditions' => array(
                        'news_id' => $value['News']['id']
                    )
                ));

                $conditions = array();
                if ($value['News']['destination_target'] != 'all') {
                    if ($value['News']['target']) {
                        $lst_age = explode(",", $value['News']['target']);
                        foreach($lst_age as $key1 => $value1) {
                            $ages = explode("-", $value1);
                            $conditions['OR'][] = array(
                                'User.birthday >=' => date('Y-m-d', strtotime(date('Y-m-d').' -'.$ages[1].' year')),
                                'User.birthday <=' => date('Y-m-d', strtotime(date('Y-m-d').' -'.$ages[0].' year'))
                            );
                        }
                    }
                    if ($value['News']['gender'] && $value['News']['gender'] != '全て対象') {
                        if ($value['News']['gender'] == FEMALE || $value['News']['gender'] == '女性') {
                            $conditions['User.gender'] = array(FEMALE, '女性');
                        } else if ($value['News']['gender'] == MALE || $value['News']['gender'] == '男性') {
                            $conditions['User.gender'] = array(MALE, '男性');
                        }
                    }
                    if ($value['AreaNoticeSetting']) {
                        $lst_area_id = array();
                        foreach ($value['AreaNoticeSetting'] as $key2 => $value2) {
                            $lst_area_id[] = $value2['area_id'];
                        }
                        $conditions['User.area_id'] = $lst_area_id;
                    }
                }

                //get users of this notification shop
                $lst_user_id = $obj_user_shop->find('list', array(
                    'fields' => 'user_id',
                    'conditions' => array(
                        'UserShop.user_id not' => $lst_notice_user_id,
                        'UserShop.shop_id' => $shop['Shop']['id'],
                        'UserShop.is_allow_notification' => 1,
                        'UserShop.is_disabled <>' => 1
                    )
                ));

                $conditions['User.id'] = $lst_user_id;
                $conditions['User.role'] = 'user';

                $users = $obj_user->find('all', array(
                    'conditions' => $conditions,
                    'recursive' => -1
                ));

                if ($users) {
                    foreach ($users as $key3 => $value3) {
                        $obj_news_delivery->create();
                        $data = array();
                        $data['NewsDelivery']['user_id'] = $value3['User']['id'];
                        $data['NewsDelivery']['news_id'] = $value['News']['id'];
                        $data['NewsDelivery']['delivered_date'] = $value['News']['delivery_date_value'].' '.$value['News']['delivery_time_value'];
                        $data['NewsDelivery']['is_read'] = 0;
                        $data['NewsDelivery']['is_published'] = 1;
                        $data['NewsDelivery']['is_deleted'] = 0;

                        $obj_news_delivery->save($data);
                    }
                }
            }
        }
    }

    /**
     * sending android notification
     * @param string $device_id
     * @param string $msg
     */
    public function sendAndroidNotification($android_key, $device_id = null, $android_msg = null, $totalBadge = null)
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
    public function sendiOSnotification($iOSKey, $deviceToken = null, $message = null, $totalBadge = null)
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