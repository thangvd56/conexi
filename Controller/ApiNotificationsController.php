<?php
App::uses('File', 'Utility');

class ApiNotificationsController extends AppController
{
    public $components = array('Paginator', 'RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->RequestHandler->ext = 'json';
        $this->Auth->allow(array(
            'notification_setting',
            'index',
            'update_notification',
            'read_notification'
        ));
        $this->Auth->authorize = 'Controller';
        $this->autoRender = false;
    }

    public function index()
    {
        $this->autoRender = false;
        $user_id = $this->request->query('user_id');
        $page = $this->request->query('page') + 1;

        $this->loadModel('News');
        $this->loadModel('Media');
        $this->loadModel('NewsDelivery');
        $this->loadModel('User');
        $this->loadModel('Reservation');

        $this->Paginator->settings = array(
            'joins' => array( array(
                'table' => 'news',
                'alias' => 'News',
                'type' => 'Inner',
                'conditions' => array('NewsDelivery.news_id = News.id')
            )),
            'conditions' => array(
                'NewsDelivery.user_id' => $user_id,
                'NewsDelivery.is_deleted' => 0,
                'News.type' => 'notice_settings',
                'NewsDelivery.delivered_date <=' => date('Y-m-d H:i')
            ),
            'order' => array(
                'NewsDelivery.created' => 'desc',
            ),
            'limit' => PAGE_LIMIT,
            'fields' => array(
                'News.id',
                'News.title',
                'News.message',
                'News.type',
                'NewsDelivery.created',
                'NewsDelivery.is_read',
            ),
            'page' => $page
        );

        $data = $this->Paginator->paginate('NewsDelivery');
        $notification_arr = array();

        $user = $this->User->find('first', array(
            'fields' => array(
                'News.id',
                'News.title',
                'News.message',
                'News.type',
                'News.is_disabled',
                'News.image',
                'User.id',
                'User.birthday',
                'User.is_news_notification',
                'Shop.id',
                'Shop.user_id',
                'UserShop.is_allow_notification'
            ),
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'Inner',
                    'conditions' => array('User.id = UserShop.user_id')
                ),
                array(
                    'table' => 'shops',
                    'alias' => 'Shop',
                    'type' => 'Inner',
                    'conditions' => array('Shop.id = UserShop.shop_id')
                ),
                array(
                    'table' => 'news',
                    'alias' => 'News',
                    'type' => 'Inner',
                    'conditions' => array('News.user_id = Shop.user_id')
                )
            ),
            'conditions' => array(
                'User.id' => $user_id,
                'News.type' => BIRTHDAY_NOTIFICATION,
                'News.is_disabled <>' => 1,
                'UserShop.is_allow_notification <>' => 0,
                'User.is_news_notification <>' => 0,
                'MONTH(User.birthday) = MONTH(CURRENT_DATE())'
            ),
            'recursive' => -1
        ));

        if ($user) {
            $user['News']['is_read'] = false;
            //check if it is saved to delivery table
            $birthday_delivery = $this->NewsDelivery->find('first', array(
                'conditions' => array(
                    'NewsDelivery.news_id' => $user['News']['id'],
                    'NewsDelivery.user_id' => $user['User']['id'],
                    'NewsDelivery.is_deleted' => 0,
                    'NewsDelivery.notification_type' => BIRTHDAY_NOTIFICATION,
                    'YEAR(NewsDelivery.delivered_date) = YEAR(CURDATE())' //only currently year.
                ),
                'recursive' => -1,
                'order' => array('NewsDelivery.delivered_date' => 'DESC'),
                'limit' => 1
            ));

            if (!empty($birthday_delivery)) {
                $user['News']['is_read'] = $birthday_delivery['NewsDelivery']['is_read'];
                $user['News']['date'] = $birthday_delivery['NewsDelivery']['delivered_date'];

                if (!empty($user['News']['image'])) {
                    $user['News']['image'] = array(Router::url('/', true) . 'uploads/news/' . $user['News']['image']);
                }
                //check if customers already received but they change the birthday to future then not allow to see.
                $delivery_date = date('m', strtotime($birthday_delivery['NewsDelivery']['delivered_date']));
                $change_date = date('m');
                if ($delivery_date == $change_date) {
                    array_push($notification_arr, $user['News']);
                }
            }
        }

        $reservation = $this->Reservation->find('first', array(
            'conditions' => array(
                'Reservation.user_id' => $user_id
            ),
            'order' => array('Reservation.checkin_date' => 'DESC'),
            'recusive' => -1
        ));

        if ($reservation && $reservation['Reservation']['checkin_date']) {
            $datetime1 = date_create($reservation['Reservation']['checkin_date']);
            $datetime2 = date_create(date('Y-m-d'));
            $interval = date_diff($datetime1, $datetime2);
            $days = $interval->format('%a');

            $news = $this->News->find('all', array(
                'fields' => array(
                    'News.id',
                    'News.title',
                    'News.message',
                    'News.type',
                    'News.is_disabled',
                    'News.time',
                    'News.duration',
                    'News.image',
                    'User.id',
                    'User.is_news_notification',
                    'Shop.id',
                    'Shop.user_id',
                    'UserShop.is_allow_notification'
                ),
                'joins' => array(
                    array(
                        'table' => 'shops',
                        'alias' => 'Shop',
                        'type' => 'Inner',
                        'conditions' => array('News.user_id = Shop.user_id')
                    ),
                    array(
                        'table' => 'user_shops',
                        'alias' => 'UserShop',
                        'type' => 'Inner',
                        'conditions' => array('Shop.id = UserShop.shop_id')
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'Inner',
                        'conditions' => array('User.id = UserShop.user_id')
                    )
                ),
                'recursive' => -1,                
                'conditions' => array(
                    'User.id' => $user_id,
                    'News.type' => LAST_VISIT_NOTIFICATION,
                    'News.is_disabled <>' => 1,
                    'UserShop.is_allow_notification <>' => 0,
                    'User.is_news_notification <>' => 0,
                    'OR' => array(
                        array(
                            'News.duration' => $days,
                            'News.time <=' => date('H:i')
                        ),
                        array(
                            'News.duration <' => $days,
                        )
                    )
                )
            ));

            if ($news) {
                foreach ($news as $key => $value) {
                    if (($days == $value['News']['duration']) && (date('H:i:s') < $value['News']['time'])) {
                        continue;
                    }

                    $news1 = array();
                    $news1['is_read'] = false;
                    //check if it is saved to delivery table
                    $last_notice = $this->NewsDelivery->find('first', array(
                        'conditions' => array(
                            'NewsDelivery.news_id' => $value['News']['id'],
                            'NewsDelivery.user_id' => $value['User']['id'],
                            'NewsDelivery.is_deleted' => 0,
                            'NewsDelivery.notification_type' => LAST_VISIT_NOTIFICATION
                        ),
                        'recursive' => -1,
                        'order' => array('NewsDelivery.delivered_date' => 'DESC')
                    ));
                    //if not then save that last visit notification to table delivery
                    if (empty($last_notice)) {
                        $this->NewsDelivery->create();
                        $dts = array(
                            'NewsDelivery' => array(
                                'news_id' => $value['News']['id'],
                                'user_id' => $value['User']['id'],
                                'is_deleted' => 0,
                                'is_read' => 0,
                                'is_published' => 1,
                                'is_pushed' => 1,
                                'delivered_date' => date('Y-m-d H:i:s'),
                                'notification_type' => LAST_VISIT_NOTIFICATION
                            )
                        );
                        $this->NewsDelivery->save($dts, false);
                    } else {
                        $news1['is_read'] = $last_notice['NewsDelivery']['is_read'];
                    }
                    
                    if (!empty($value['News']['image'])) {
                        $news1['image'][0] = Router::url('/', true) . 'uploads/news/' . $value['News']['image'];
                    }

                    $news1['id'] = $value['News']['id'];
                    $news1['title'] = $value['News']['title'];
                    $news1['message'] = $value['News']['message'];
                    $news1['date'] = $reservation['Reservation']['checkin_date'];                    
                    $news1['type'] = $value['News']['type'];
                    array_push($notification_arr, $news1);
                }
            }
        }

        foreach ($data as $value) {
            $image = $this->Media->find('all', array(
                'recursive' => -1,
                'conditions' => array(
                    'Media.model' => 'news',
                    'Media.external_id' => $value['News']['id']
                )
            ));
            $img_array = array();
            foreach ($image as $k => $v) {
                $img = Router::url('/', true) . 'uploads/photo_notices/' . $v['Media']['file'];
                array_push($img_array, $img);
            }
            $notification['id'] = $value['News']['id'];
            $notification['title'] = $value['News']['title'];
            $notification['message'] = $value['News']['message'];
            $notification['date'] = $value['NewsDelivery']['created'];
            $notification['is_read'] = $value['NewsDelivery']['is_read'];
            $notification['type'] = $value['News']['type'];
            $notification['image'] = $img_array;
            array_push($notification_arr, $notification);
        }
        echo json_encode(array(
            'notifications' => $notification_arr,
            'totalpage' => $this->params['paging']['NewsDelivery']['pageCount'],
            'success' => 1,
            'message' => MESSAGE_SUCCESS
        ));
    }

    public function notification_setting()
    {
        $this->loadModel('User');
        $this->loadModel('UserShop');
        $user_id = $this->request->query('user_id');
        $shop_id = $this->request->query('shop_id');

        $user_shop = $this->UserShop->find('first', array(
            'conditions' => array(
                'AND' => array(
                    'UserShop.user_id' => $user_id,
                    'UserShop.shop_id' => $shop_id
                ),
                'recursive' - 1
            ),
        ));

        if ($user_shop) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id
                ),
            ));

            if ($user) {
                echo json_encode(array(
                    'firstname' => $user['User']['firstname'],
                    'lastname' => $user['User']['lastname'],
                    'firstname_kana' => $user['User']['firstname_kana'],
                    'lastname_kana' => $user['User']['lastname_kana'],
                    'contact' => $user['User']['contact'],
                    'email' => $user['User']['email'],
                    'area_id' => $user['User']['area_id'],
                    'birtdate' => $user['User']['birthday'],
                    'user_code' => $user['User']['user_code'],
                    'model_id' => $user['User']['model_id'],
                    'sex' => $user['User']['gender'],
                    'membership_id' => $user['User']['membership_id'],
                    'is_news_notification' => $user['User']['is_news_notification'],
                    'is_medical_notification' => $user['User']['is_medical_notification'],
                    'is_allow_notification' => $user_shop['UserShop']['is_allow_notification'],
                    'success' => 1,
                    'message' => 'Successful'
                ));
            } else {
                echo json_encode(array(
                    'success' => 0,
                    'message' => 'Invalid User ID'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'UserID and ShopID are not matched!'
            ));
        }
        $this->autoRender = false;
    }

    public function update_notification()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $this->loadModel('User');
            $this->loadModel('UserShop');
            $img = $this->request->data('user_image');
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $name = uniqid() . '.png';
            $file = WWW_ROOT . 'uploads/user_images/' . $name;
            try {
                $lastname = $this->request->data('lastname');
                $contact = $this->request->data('contact');
                $gender = $this->request->data('sex');
                $email = $this->request->data('email');
                $area_id = $this->request->data('area_id');
                if (empty($area_id)) {
                    $area_id = null;
                }
                $birthdate = $this->request->data('birthdate');
                $id = $this->request->data('user_id');
                $firstname = $this->request->data('firstname');
                $membership_id = $this->request->data('membership_id');
                $firstname_kana = $this->request->data('firstname_kana');
                $lastname_kana = $this->request->data('lastname_kana');
                $is_news_notification = false;
                $is_medical_notification = false;
                if (empty($firstname) || empty($lastname)) {
                    echo json_encode(array(
                        'success' => 0,
                        'message' => 'First Name and Last Name are require.',
                    ));
                    return false;
                }

                if ($this->request->data('is_news_notification') == 'true') {
                    $is_news_notification = true;
                }
                if ($this->request->data('is_medical_notification') == 'true') {
                    $is_medical_notification = true;
                }
            } catch (Exception $ex) {
                echo json_encode(array(
                    'success' => 0,
                    'message' => 'Miss Parameters or Parameter may wrong!',
                ));
            }
            $this->User->id = $id;
            if (!$this->User->exists()) {
                $message = 'Invalid User ID';
                echo json_encode(array(
                    'success' => 0,
                    'message' => $message,
                ));
                $this->autoRender = false;
            } else {
                if (file_put_contents($file, $data)) {
                    $update = $this->User->updateAll(array(
                        'User.firstname' => "'" . $firstname . "'",
                        'User.lastname' => "'" . $lastname . "'",
                        'User.firstname_kana' => "'" . $firstname_kana . "'",
                        'User.lastname_kana' => "'" . $lastname_kana . "'",
                        'User.email' => "'" . $email . "'",
                        'User.area_id' => $area_id,
                        'User.membership_id' => "'" . $membership_id . "'",
                        'User.contact' => "'" . $contact . "'",
                        'User.birthday' => "'" . $birthdate . "'",
                        'User.gender' => "'" . $gender . "'",
                        'User.image' => "'" . $name . "'",
                        'User.is_medical_notification' => $is_medical_notification,
                        'User.is_news_notification' => $is_news_notification,
                        'User.completed' => 1
                    ), 
                    array('User.id' => h($id)));
                } else {
                    $update = $this->User->updateAll(array(
                        'User.firstname' => "'" . $firstname . "'",
                        'User.lastname' => "'" . $lastname . "'",
                        'User.firstname_kana' => "'" . $firstname_kana . "'",
                        'User.lastname_kana' => "'" . $lastname_kana . "'",
                        'User.membership_id' => "'" . $membership_id . "'",
                        'User.email' => "'" . $email . "'",
                        'User.area_id' => $area_id,
                        'User.contact' => "'" . $contact . "'",
                        'User.birthday' => "'" . $birthdate . "'",
                        'User.gender' => "'" . $gender . "'",
                        'User.is_medical_notification' => $is_medical_notification,
                        'User.is_news_notification' => $is_news_notification,
                        'User.completed' => 1
                    ),
                    array('User.id' => h($id)));
                }

                echo json_encode(array(
                    'success' => 1,
                    'message' => 'successfully update',
                ));
            }
        }
    }

    public function read_notification()
    {
        try {
            $this->loadModel('NewsDelivery');
            $user_id = $this->request->query('user_id');
            $news_id = $this->request->query('news_id');
            $data = $this->NewsDelivery->find('first', array(
                'conditions' => array(
                    'news_id' => $news_id,
                    'user_id' => $user_id,
                    'is_read' => 0
                )
            ));
            if (!empty($data)) {
                $this->NewsDelivery->updateAll(
                    array('is_read' => 1), array(
                    'news_id' => $news_id,
                    'user_id' => $user_id
                ));
                return json_encode(array(
                    'success' => 1,
                    'message' => 'successful'
                ));
            } else {
                return json_encode(array(
                    'success' => 1,
                    'message' => 'News is already read.'
                ));
            }
        } catch (Exception $ex) {
            return json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
    }
}