<?php

class ReservationsController extends AppController {

    public $components = array('FileUpload');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
        $this->loadModel('User');
    }

    public function index() {
        $this->loadModel('Reserveration');
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete':
                    echo $this->delete_permanently();
                    return false;
                case 'revert':
                    echo $this->revert_reservation();
                    return false;
            }
        }
        //This coundition for count reservatiom

        $conditions = $this->User->find('count', array(
            'fields' => array('User.id', 'User.firstname', 'User.lastname', 'Reservation.*'),
            'joins' => array( array(
                'table' => 'reservations',
                'alias' => 'Reservation',
                'type' => 'inner',
                'conditions' => array(
                    'User.id = Reservation.user_id'
                )
            )),
            'conditions' => array(
                'Reservation.is_deleted' => 1
            )
        ));
        $this->set('count_result', $conditions);
    }

    public function list_user() {
        $this->autoRender = false;
        $this->loadModel('User');
        $this->loadModel('Shop');
        $this->loadModel('UserShop');
        $conditions = array(
            'User.status' => 1,
            'User.role' => ROLE_USER
        );

        $is_shop_id = false;
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $conditions['UserShop.shop_id'] = $this->request->query('shop_id');
            if (!empty($this->request->query('shop_id'))) {
                $is_shop_id = true;
            }
        } else if ($this->Auth->user('role') === ROLE_SHOP) {
            $shop_id = $this->Shop->getOwnerShopId($this->Auth->user('id'));
            if (!empty($shop_id)) {
                $conditions['UserShop.shop_id'] = $shop_id['Shop']['id'];
                $is_shop_id = true;
            }
        }

        $users = $this->User->find('all', array(
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'inner',
                    'conditions' => array(
                        'UserShop.user_id = User.id',
                    )
                )
            ),
            'fields' => array(
                'User.id',
                'User.lastname_kana',
                'User.firstname_kana',
                'User.contact',
                'User.user_code'
            ),
            'conditions' => $conditions,
            'recursive' => -1
        ));

        $newArr = array();
        foreach ($users as $key => $value) {
            if ($this->request->query('param') === 'name') {
                if ($value['User']['lastname_kana'] === null && $value['User']['firstname_kana'] === null) {
                    continue;
                }
            }

            if ($this->request->query('param') === 'contact') {
                if ($value['User']['contact'] === null) {
                    continue;
                }
            }

            if ($this->request->query('param') === 'code') {
                if ($value['User']['user_code'] === null) {
                    continue;
                }
            }
            
            $newArr[$key]['id'] = $value['User']['id'];
            $newArr[$key]['name'] = $value['User']['lastname_kana'].' '.$value['User']['firstname_kana'];
            $newArr[$key]['contact'] = $value['User']['contact'];
            $newArr[$key]['user_code'] = $value['User']['user_code'];
        }


        if ($is_shop_id) {
            echo json_encode(array_values($newArr));
        } else {
            echo json_encode(array());
        }
    }

    public function checkIn($clientId = null) {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->layout = false;
            $error = array();
            if (!empty($this->request->data['Reservation'])) {
                foreach ($this->request->data['Reservation'] as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $this->Reservation->id = $value;
                    if ($this->request->query('type') == 'checkin') {
                        if ($this->Reservation->save(array('Reservation' => array('is_checkin' => 1)))) {
                            $this->loadModel('Shop');
                            $this->loadModel('StampSetting');
                            $shop = $this->Shop->findByUserId($this->Auth->user('id'));
                            $stamp = $this->StampSetting->findByShopId($shop['Shop']['id']);

                            $stampFiels = array('Stamp' => array(
                                'user_id' => $clientId,
                                'stamp_setting_id' => $stamp['StampSetting']['id'],
                                'stamp_type' => 'app_checkin',
                                'count' => $stamp['StampSetting']['app_checkin']
                            ));

                            $logFiels = array('Log' => array(
                                'user_id' => $this->Auth->user('id'),
                                'type' => 'app_checkin',
                                'value' => 'get stamp no reservation'
                            ));

                            $this->loadModel('Stamp');
                            $this->loadModel('Log');

                            $this->Stamp->create();
                            $this->Stamp->save($stampFiels);

                            $this->Log->create();
                            $this->Log->save($logFiels);
                        }
                    } else {
                        $this->Reservation->save(array('Reservation' => array('status' => 'cancel')));
                    }
                }
                $error['result'] = 'Success';
            } else {
                if ($this->createFastCheckIn($clientId)) {
                    $error['result'] = 'Success';
                } else {
                    $error['result'] = 'Error';
                }
            }
        } else {
            throw NotFoundException;
        }

        echo json_encode($error);
    }

    public function getUnCheckInByClientId($clientId = null) {
        if ($this->request->is('ajax')) {
            $this->loadModel('Shop');
            $shop_id = null;
            if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
                $shop_id = $this->request->query('shop');
            } else if ($this->Auth->user('role') === ROLE_SHOP) {
                $shop = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                if (!empty($shop)) {
                    $shop_id = $shop['Shop']['id'];
                }
            } else {
                throw new NotFoundException('User Id not found.');
            }
            $this->Shop->recursive = -1;

            $conditions_1['AND'] = array(
                'Reservation.user_id' => $clientId,
                'Reservation.is_deleted <>' => 1,
                'Reservation.is_completed' => 1,
                'Reservation.status' => 'visit',
                'Reservation.is_checkin' => 0,
                'Reservation.shop_id' => $shop_id
            );

            $conditions_2['AND'] = array(
                'Reservation.user_id' => $clientId,
                'Reservation.is_deleted <>' => 1,
                'Reservation.is_completed' => 1,
                'Reservation.status' => 'visit_only',
                'Reservation.is_checkin' => 1,
                'Reservation.shop_id' => $shop_id
            );

            $this->Reservation->recursive = -1;
            $reservation_1 = $this->Reservation->find('all', array(
                'fields' => array('*'),
                'conditions' => $conditions_1,
                'joins' => array(
                    array(
                        'table' => 'staffs',
                        'alias' => 'Staff',
                        'type' => 'left',
                        'conditions' => array('Staff.id = Reservation.staff_id')
                    )
                ),
            ));

            $reservation_2 = $this->Reservation->find('all', array(
                'fields' => array('*'),
                'conditions' => $conditions_2,
                'joins' => array(
                    array(
                        'table' => 'staffs',
                        'alias' => 'Staff',
                        'type' => 'left',
                        'conditions' => array('Staff.id = Reservation.staff_id')
                    )
                ),
            ));

            $reservation = array();
            array_push($reservation, $reservation_1);
            array_push($reservation, $reservation_2);
			
            echo json_encode($reservation);

            $this->autoRender = false;
            $this->layout = false;
        } else {
            throw NotFoundException;
        }
    }

    public function result_search() {
        $this->loadModel('Reservation');
        if ($this->request->is('ajax')) {
            $action = $this->request->query('action');
            switch ($action) {
                case 'index':
                    $conditions = $this->User->query('SELECT User.id,User.firstname,User.lastname,Reservation.* FROM users User '
                            . 'INNER JOIN reservations Reservation on User.id = Reservation.user_id WHERE Reservation.is_deleted=1');
                    break;
                case 'search':
                    $year = $this->request->query('year');
                    $month = $this->request->query('month');
                    $conditions = $this->User->query('SELECT User.id,User.firstname,User.lastname,Reservation.* FROM users User '
                            . 'INNER JOIN reservations Reservation on User.id = Reservation.user_id  WHERE  MONTH(Reservation.date) =' . $month . ' AND YEAR(Reservation.date)=' . $year . ' AND Reservation.is_deleted = 1');
                    break;
            }
        }
        $this->set('reservation', $conditions);
        //$this->set('count_result', count($conditions));
        $this->layout = 'ajax';
    }

    public function delete_permanently() {
        $this->loadModel('ReservationTag');
        $id = $this->request->query('get_delete_id');
        $this->Reservation->id = $id;
        if (!$this->Reservation->exists()) {

            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid Reservation ID'
            ));
        }
        $reservation_tag = $this->ReservationTag->query('select R.* from reservation_tag as R where R.reservation_id=' . $id . ''
                . ' and R.is_deleted =1');
        if ($this->Reservation->delete($id)) {
            for ($i = 0; $i < count($reservation_tag); $i++) {
                $this->ReservationTag->id = $reservation_tag[$i]['R']['id'];
                $this->ReservationTag->delete($reservation_tag[$i]['R']['id']);
            }
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Reservation has been deleted'
            ));
        }
        echo $this->loading();
    }

    public function revert_reservation() {
        $this->loadModel('ReservationTag');
        $id = $this->request->query('get_revert_id');
        $this->Reservation->id = $id;
        if (!$this->Reservation->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid Reservation ID'
            ));
        }
        $reservation_tag = $this->ReservationTag->query('select R.* from reservation_tag as R where R.reservation_id=' . $id . ''
                . ' and R.is_deleted =1');
        if ($this->Reservation->save(array('Reservation' => array('is_deleted' => 0)))) {
            for ($i = 0; $i < count($reservation_tag); $i++) {
                $this->ReservationTag->id = $reservation_tag[$i]['R']['id'];
                $this->ReservationTag->saveField('is_deleted', 0);
            }
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Reservation has been reverted'
            ));
        }
    }

    public function create($user_id = null) {
        $this->loadModel('User');
        $this->loadModel('UserShop');

        $methode = array('appointment', 'staff', 'tag', 'budget', 'user', 'confirm');
        $mode = $this->request->query('mode');
        $reservationid = '';
        if ($this->Session->check('ReservationId') == true) {
            $reservationid = $this->Session->read('ReservationId');
        }

        $reservation = array();
        $reservation_condition = array();

        if (!empty($this->User->findById($user_id))) {
            $this->Reservation->recursive = 2;
            $reservation = $this->Reservation->find('first', array(
                'fields' => array('*'),
                'conditions' => array(
                    'Reservation.is_completed' => 0,
                    'Reservation.user_id' => $user_id,
                    'Reservation.is_deleted <>' => 1,
                ),
                'order' => array('Reservation.created' => 'DESC'),
                'joins' => array(
                    array(
                        'table' => 'staffs',
                        'alias' => 'Staff',
                        'type' => 'left',
                        'conditions' => array('Staff.id = Reservation.staff_id')
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'left',
                        'conditions' => array('Reservation.user_id = User.id')
                    )
                )
            ));
        } elseif (!empty($reservationid)) {
            $this->Reservation->recursive = 2;
            $reservation = $this->Reservation->find('first', array(
                'fields' => array('*'),
                'conditions' => array(
                    'Reservation.is_completed' => 0,
                    'Reservation.id' => $reservationid,
                    'Reservation.is_deleted <>' => 1,
                ),
                'order' => array('Reservation.created' => 'DESC'),
                'joins' => array(
                    array(
                        'table' => 'staffs',
                        'alias' => 'Staff',
                        'type' => 'left',
                        'conditions' => array('Staff.id = Reservation.staff_id')
                    ),
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'left',
                        'conditions' => array('Reservation.user_id = User.id')
                    )
                )
            ));            
        }

        if (in_array($mode, $methode)) {
            if (empty($reservation)) {
                throw new NotFoundException();
            }
        }

        switch ($mode) {
            case 'appointment':
                if ($this->request->query('date')) {
                    $date = str_replace('-', ',', $this->request->query('date'));
                } else {
                    $date = str_replace('-', ',', $reservation['Reservation']['date']);
                }
                $this->loadModel('Chair');
                $this->set('is_created', $this->Reservation->isExistAppoitmentDate($date, $reservation['Reservation']['id']));
                $this->set('chair_json', $this->Chair->getChairData('json', $reservation['Reservation']['shop_id']));
                $this->set('timeline', $this->Reservation->getAppointmentDate($date, $reservation['Reservation']['id'], $reservation['Reservation']['shop_id']));
                $this->set('date', $date);

                $this->set('schedule_header', $this->Reservation->getDateHeaderTimeline($date));
                $this->set('chair', $this->Chair->getChairData('', $reservation['Reservation']['shop_id']));
                break;

            case 'staff':
                $this->loadModel('Staff');
                $this->set('staff', $this->Staff->getStaff($reservation['Reservation']['shop_id']));
                break;

            case 'tag':
                $this->loadModel('Tag');
                $this->set('tag', $this->Tag->getTag($reservation['Reservation']['shop_id']));
                break;

            case 'user':
                $this->set('SessionTimeout', $this->Session->read('Config.time'));
                break;

            default:
                break;
        }

        if ($this->request->is(array('post', 'put'))) {
            if (!empty($reservation)) {
                $this->Reservation->id = $reservation['Reservation']['id'];
            } else {
                $this->Reservation->create();
            }
            $this->request->data['Reservation']['created'] = date('Y-m-d H:i:s');
            if (!empty($this->request->data['Reservation']['user_id']) && empty($this->request->data['Reservation']['shop_id'])) {
                $s = $this->UserShop->getShopId($user_id);
                if (!empty($s)) {
                    $this->request->data['Reservation']['shop_id'] = $s['UserShop']['shop_id'];
                }
            }

            if ($this->Reservation->save($this->request->data)) {
                if (isset($this->request->data['ReservationTag']['tag_id'])) {
                    $this->loadModel('ReservationTag');
                    $this->ReservationTag->UpdateTag($this->request->data['ReservationTag']['tag_id'], $this->Reservation->id);
                }

                if (isset($this->request->data['Reservation']['user_id']) && !empty($this->request->data['Reservation']['user_id'])) {
                    $user_id = $this->request->data['Reservation']['user_id'];
                }

                if (!empty($user_id)){
                    $this->redirect($this->Reservation->nextUrl(
                        $mode,
                        array('reservation_id' => $this->Reservation->id,
                            'user_id' => $user_id),
                        $this->request->action,
                        $this->request->query('shop_id')
                    ));
                } else {
                    $this->Session->write('ReservationId', $this->Reservation->id);
                    $this->redirect($this->Reservation->nextUrl(
                        $mode,
                        array('reservation_id' => $this->Reservation->id),
                        $this->request->action,
                        $this->request->query('shop_id')
                    ));
                }
            }
        }

        $this->request->data = $reservation;
        if (!empty($reservation)) {
            $this->set('User', $reservation['User']);
        }

        $this->loadModel('Shop');
        $this->Shop->recursive = -1;
        $shop_id = $this->request->query('shop_id');
        if ($this->Auth->user('role') != ROLE_HEADQUARTER && !$shop_id) {
            $shop = $this->Shop->findByUserId($this->Auth->user('id'));
            $shop_id = $shop ? $shop['Shop']['id'] : '';
        }

        $this->set('shop_id', $shop_id);
        $this->set('user_id', $user_id);

        $this->render($mode);
    }

    public function edit($reservation_id = null) {
        if (empty($this->Reservation->findById($reservation_id))) {
            throw new NotFoundException();
        }
        $methode = array('appointment', 'staff', 'tag', 'budget', 'confirm');
        $mode = $this->request->query('mode');
        $this->Reservation->recursive = 2;
        $reservation = $this->Reservation->find('first', array(
            'fields' => array('*'),
            'conditions' => array('Reservation.is_completed' => 1, 'Reservation.id' => $reservation_id, 'Reservation.is_deleted <>' => 1),
            'joins' => array(
                array(
                    'table' => 'staffs',
                    'alias' => 'Staff',
                    'type' => 'left',
                    'conditions' => array('Staff.id = Reservation.staff_id')
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => array('Reservation.user_id = User.id')
                )
            )
        ));
        if (in_array($mode, $methode)) {
            if (empty($reservation)) {
                throw new NotFoundException();
            }
        }

        switch ($mode) {
            case 'appointment':
                if ($this->request->query('date')) {
                    $date = str_replace('-', ',', $this->request->query('date'));
                } else {
                    $date = str_replace('-', ',', $reservation['Reservation']['date']);
                }
                $this->loadModel('Chair');
                $this->set('is_created', $this->Reservation->isExistAppoitmentDate($date, $reservation['Reservation']['id']));
                $this->set('chair_json', $this->Chair->getChairData('json', $reservation['Reservation']['shop_id']));
                $this->set('timeline', $this->Reservation->getAppointmentDate($date, $reservation['Reservation']['id'], $reservation['Reservation']['shop_id']));
                $this->set('date', $date);

                $this->set('schedule_header', $this->Reservation->getDateHeaderTimeline($date));
                $this->set('chair', $this->Chair->getChairData('', $reservation['Reservation']['shop_id']));
                break;

            case 'staff':
                $this->loadModel('Staff');
                $this->set('staff', $this->Staff->getStaff($reservation['Reservation']['shop_id']));
                break;

            case 'tag':
                $this->loadModel('Tag');
                $this->set('tag', $this->Tag->getTag($reservation['Reservation']['shop_id']));
                break;

            default:
                break;
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Reservation->id = $reservation['Reservation']['id'];

            if ($this->Reservation->save($this->request->data)) {
                if (isset($this->request->data['ReservationTag']['tag_id'])) {
                    $this->loadModel('ReservationTag');
                    $this->ReservationTag->UpdateTag($this->request->data['ReservationTag']['tag_id'], $reservation['Reservation']['id']);
                }
                $this->redirect($this->Reservation->nextUrl($mode, array('reservation_id' => $reservation_id, 'user_id' => $reservation['Reservation']['user_id']), $this->request->action));
            }
        }

        $this->request->data = $reservation;
        if (!empty($reservation)) {
            $this->set('User', $reservation['User']);
        }
        $this->loadModel('Shop');
        $this->Shop->recursive = -1;
        $shop = $this->Shop->findByUserId($this->Auth->user('id'));
        if (!empty($shop)) {
            $this->set('shop_id', $shop['Shop']['id']);
        }
        $this->set('reservation_id', $reservation_id);
        $this->render($mode);
    }

    public function createFastCheckIn($user_id = null) {

        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';

            $this->User->id = $user_id;
            if (!$this->User->exists()) {
                throw NotFoundException;
            }

            $this->loadModel('Shop');
            $this->loadModel('StampSetting');

            $shop_id = null;
            if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
                $shop_id = $this->request->query('shop_id');
            } else if ($this->Auth->user('role') === ROLE_SHOP) {
                $shop = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                if (!empty($shop)) {
                    $shop_id = $shop['Shop']['id'];
                }
            } else {
                throw new NotFoundException('User Id not found.');
            }

            //$shop = $this->Shop->findByUserId($this->Auth->user('id'));
            $stamp = $this->StampSetting->findByShopId($shop_id);

            // Define date
            $now = date('Y-m-d H:i:s');
            $fiels = array(
                'Reservation' => array(
                'shop_id' => $shop_id,
                'user_id' => $user_id,
                'date' => date('Y-m-d'),
                'is_checkin' => 1,
                'status' => 'visit_only',
                'checkin_date' => date('Y-m-d H:i:s'),
                'is_completed' => 1,
                'created' => $now,
                'modified' => $now
            ));
            $this->Reservation->create();

            if ($this->Reservation->save($fiels)) {
                $stampFiels = array('Stamp' => array(
                    'user_id' => $user_id,
                    'stamp_setting_id' => $stamp['StampSetting']['id'],
                    'stamp_type' => 'app_checkin',
                    'count' => $stamp['StampSetting']['app_checkin']
                ));
                $logFiels = array('Log' => array(
                    'user_id' => $this->Auth->user('id'),
                    'type' => 'app_checkin',
                    'value' => 'get stamp no reservation'
                ));

                $this->loadModel('Stamp');
                $this->loadModel('Log');

                $this->Stamp->create();
                $this->Stamp->save($stampFiels);

                $this->Log->create();
                $this->Log->save($logFiels);

                return true;
            } else {
                return false;
            }

            $this->render(false);
        } else {
            throw NotFoundException;
        }
    }

    public function view($user_id = null) {
        $this->loadModel('User');
        $user = $this->User->findById($user_id);
        if (empty($user)) {
            throw NotFoundException;
        }
        $this->Reservation->recursive =2;
        $order =
            'CASE
                WHEN Reservation.status = "visit" AND Reservation.is_checkin = 0 THEN 1
                WHEN Reservation.status = "visit_only" AND Reservation.is_checkin = 1 THEN 2
                WHEN Reservation.status = "visit" AND Reservation.is_checkin = 1 THEN 3
            END ASC, Reservation.date DESC';

        $reservation = $this->Reservation->find('all', array(
            'fields' => array('*'),
            'conditions' => array(
                'Reservation.user_id' => $user_id,
                'Reservation.is_completed' => 1,
                'Reservation.is_deleted <>' => 1,
                'Reservation.status <>' => 'cancel'
            ),
            'order' => array($order),
            'joins' => array(
                array(
                    'table' => 'chairs',
                    'alias' => 'Chair',
                    'type' => 'left',
                    'conditions' => array('Chair.id = Reservation.chair_id')
                ),
                array(
                    'table' => 'staffs',
                    'alias' => 'Staff',
                    'type' => 'left',
                    'conditions' => array('Staff.id = Reservation.staff_id')
                )
            )
        ));
        //pr($reservation); exit;
        $this->set('user', $user);
        $this->set('reservation', $reservation);
    }

    public function delete($id) {
        $reservation = $this->Reservation->findById($id);
        if (empty($reservation)) {
            throw NotFoundException;
        }
        $user_id = $reservation['Reservation']['user_id'];
        $this->Reservation->id = $id;
        if ($this->Reservation->save(array('Reservation' => array('is_deleted' => 1)))) {
            $this->Session->setFlash(MESSAGE_DELETE, 'success');
        } else {
            $this->Session->setFlash(MESSAGE_FAIL, 'error');
        }
        if ($this->Session->check('ReservationId') == true){
            $this->Session->delete('ReservationId');
        }
        $this->redirect('/reservations/view/' . $user_id);
    }

    public function deleteReservation() {
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->autoRender = false;
            $this->Reservation->id = $this->request->query('id');

            if ($this->Reservation->exists()) {
                if ($this->Session->check('ReservationId')) {
                    $this->Session->delete('ReservationId');
                }
                echo json_encode($this->Reservation->delete());
            } else {
                echo json_encode('true');
            }
        }
    }

    public function send_picture($customer_id = null) {
        $this->loadModel('Reservation');
        $this->loadModel('Media');
        $this->loadModel('User');
        $shop_id = $this->request->query('shop_id');
        $count_reservations = $this->Reservation->find('count', array(
            'conditions' => array(
                'is_checkin' => 1,
                'is_deleted' => 0,
                'user_id' => $customer_id
            ),
            'recursive' => -1
        ));
        $this->User->recursive = -1;
        $user = $this->User->findById($customer_id);
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            $cust_id = $this->request->query('customer_id');
            switch ($action) {
                case 'delete':
                    echo $this->delete_reservation_send_photo();
                    return false;
                case 'list':
                    echo $this->list_reservation_send_photo($cust_id, $shop_id);
                    return FALSE;
            }
        }
        if (empty($user)) {
            throw NotFoundException;
        }

        $this->set('user', $user);
        $this->set('count_reservations', $count_reservations);
        $this->set('shop_id', $shop_id);
        $this->set('customer_id', $customer_id);
    }

    public function delete_reservation_send_photo() {
        try {
            $this->loadModel('Media');
            $medias = $this->request->query('media');
            if (empty($medias)) {
                throw NotFoundException;
            }
            foreach ($medias as $media) {
                $m = explode(',', $media);
                $id = $m[0];
                $value = $m[1];
                $this->Media->id = $id;
                $image = $this->Media->find('first', array('conditions' => array('id' => $id)));
                if ($image) {
                    $image_arr = explode(',', $image['Media']['file']);
                    if (count($image_arr) > 1) {
                        $image_string = '';
                        $index = 0;
                        foreach ($image_arr as $photo) {
                            if ($photo != $value) {
                                if ($index > 0) {
                                    $image_string .= ',';
                                }
                                $image_string .= $photo;
                                $index++;
                            }
                        }

                        if ($image_string == "") {
                            $this->Media->save(array('id' => $id, 'is_deleted' => TRUE));
                        } else {
                            $this->Media->save(array('id' => $id, 'file' => $image_string));
                            $trash_media = array(
                                'user_id' => $image['Media']['user_id'],
                                'external_id' => $image['Media']['external_id'],
                                'model' => 'reservations',
                                'file' => $image['Media']['id'] . ',' . $value,
                                'is_deleted' => 1,
                                'modified' => $image['Media']['created']
                            );
                            $this->Media->create();
                            $this->Media->save($trash_media);
                        }
                    } else {
                        $this->Media->save(array('id' => $id, 'is_deleted' => TRUE));
                    }
                }
            }
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Delete successful'
            ));
        } catch (Exception $ex) {
            return json_encode(array(
                'result' => FALSE,
                'msg' => 'Delete successful'
            ));
        }
    }

    public function save_reservation_photos() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            try {
                $this->loadModel('Media');
                $this->loadModel('ReservationSendPicture');
                $this->loadModel('User');
                $this->loadModel('UserShop');
                $this->loadModel('Shop');
                $this->loadModel('Reservation');
                $this->loadModel('NewsDelivery');
                $user_id = $this->request->query('user_id');
                $images = $this->request->query('images');
                $reservation_id = $this->request->query('reservation_id');
                if ($reservation_id != null) {
                    $image_list = '';
                    for ($i = 0; $i < count($images); $i++) {
                        $imagepath = explode('reservation_send_photos/', $images[$i]);
                        $image_list = $image_list . $imagepath[1];
                        if ($i < count($images) - 1) {
                            $image_list = $image_list . ',';
                        }
                    }
                    $media = array(
                        'file' => $image_list,
                        'user_id' => $user_id,
                        'external_id' => $reservation_id,
                        'model' => 'reservations'
                    );
                    $this->Media->create();
                    if (($this->Media->save($media))) {
                        //find shop by customer id
                        $shop = $this->UserShop->getShopId($user_id);

                        $this->Reservation->id = $reservation_id;
                        $this->Reservation->saveField('is_read', 0, false);

                        if (!empty($shop)) {
                            $shop_info = $this->Shop->getShopInfo($shop['UserShop']['shop_id']);
                            $customer = $this->UserShop->getSingleCustomer($user_id);
                            if (!empty($customer) && !empty($shop_info)) {
                                if ($customer['UserShop']['is_allow_notification'] == 1) { //check if he or she is allow notification
                                    $this->User->recursive = -1;
                                    $cust = $this->User->findById($user_id);
                                    if ($cust) {
                                        $reservation_badge = $this->Reservation->find('count', array(
                                            'conditions' => array(
                                                'AND' => array(
                                                    'Reservation.is_read' => 0,
                                                    'Reservation.user_id' => $cust['User']['id'],
                                                    'Reservation.shop_id' => $shop['UserShop']['shop_id'],
                                                    'Reservation.is_completed' => 1,
                                                    'Reservation.is_deleted' => 0,
                                                    'Reservation.is_checkin' => 1
                                                )
                                            ),
                                            'recursive' => -1
                                        ));

                                        // Get unread from NewDelivery
                                        $news_badge = $this->NewsDelivery->find('count', array(
                                            'conditions' => array(
                                                'NewsDelivery.user_id' => $cust['User']['id'],
                                                'NewsDelivery.is_read' => 0,
                                            )
                                        ));
                                        $userBadge = $cust['User']['reservation_badge'];
                                        $totalBadge = (int)($userBadge + $news_badge + $reservation_badge);

                                        if ($cust['User']['platform_type'] == ANDROID_PLATFORM) {
                                            $this->User->send_android_notification($shop_info['Shop']['android_key'], $cust['User']['token'], NOTIFICATION_MSG_SEND_PHOTO, $totalBadge);
                                        } else if ($cust['User']['platform_type'] == IOS_PLATFORM) {
                                            $this->User->send_ios_notification($shop_info['Shop']['ios_ck_file'], $cust['User']['token'], NOTIFICATION_MSG_SEND_PHOTO, $totalBadge);
                                        }

                                        return json_encode(array(
                                            'result' => true,
                                            'msg' => 'Photo has successfully sent.',
                                        ));
                                    }
                                }
                                return json_encode(array(
                                    'result' => false,
                                    'msg' => 'Customer notification was disabled.',
                                ));
                            }
                            return json_encode(array(
                                'result' => false,
                                'msg' => 'Either shop or customer not found.',
                            ));
                        }

                        return json_encode(array(
                            'result' => true,
                            'msg' => 'Photo has successfully sent.',
                        ));
                    }
                    return json_encode(array(
                        'result' => false,
                        'msg' => 'System error. Cannot send the photo.',
                    ));
                }
            } catch (Exception $ex) {
                return json_encode(array(
                    'result' => FALSE,
                    'msg' => $ex->getMessage(),
                ));
            }
        }
    }

    public function get_reservations() {
        if ($this->request->is('ajax')) {
            $this->autoRender = FALSE;
            try {
                $this->loadModel('Reservation');
                $this->loadModel('Staff');
                $user_id = $this->request->query('user_id');
                $reservations = $this->Reservation->find('all', array(
                    'conditions' => array(
                        'is_checkin' => 1,
                        'is_deleted <>' => 1,
                        'user_id' => $user_id
                    ),
                    'order' => array('Reservation.date' => 'DESC', 'Reservation.start' => 'DESC'),
                    'recursive' => 2
                ));

                $reservation_results = array();
                foreach ($reservations as $value) {
                    $staff = $this->Staff->find('first', array(
                        'fields' => array('name'),
                        'conditions' => array(
                            'id' => $value['Reservation']['staff_id']
                        ),
                        'recursive' => -1,
                    ));
                    $staff_name = '';
                    if ($staff) {
                        $staff_name = $staff['Staff']['name'];
                    }
                    $arr = array(
                        'id' => $value['Reservation']['id'],
                        'date' => $value['Reservation']['date'],
                        'staff_name' => $staff_name,
                        'time' => $value['Reservation']['start'] . ' ~ ' . $value['Reservation']['end'],
                        //''
                        'treatment_contents' => $value['Reservation']['treatment_contents'],
                        'treatment_cost' => $value['Reservation']['treatment_cost'],
                        'persion' => $value['Reservation']['adult'] + $value['Reservation']['child']
                    );

                    array_push($reservation_results, $arr);
                }
                return json_encode(array(
                    'status' => 1,
                    'message' => 'successful',
                    'reservations' => $reservation_results
                ));
            } catch (Exception $ex) {
                return json_encode(array(
                    'status' => 0,
                    'message' => $ex->getMessage()
                ));
            }
        }
    }

    public function upload_multiple() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            try {
                if ($this->request->is('post')) {
                    $this->loadModel('Media');
                    $data = $this->request->data['ReservationSendPictures']['file'];
                    $image_results = Array();
                    for ($index = 0; $index < count($data); $index++) {
                        $file = $this->request->data['ReservationSendPictures']['file'][$index];
                        $respond = json_decode($this->FileUpload->upload_image($file, 'reservation_send_photos'));
                        array_push($image_results, array(
                            'path' => HTTP . $_SERVER['HTTP_HOST'] . $this->webroot . 'uploads/reservation_send_photos/' . $respond->image
                        ));
                    }
                    return json_encode(array(
                        'result' => TRUE,
                        'images' => $image_results
                    ));
                }
            } catch (Exception $ex) {
                return json_encode(array(
                    'result' => FALSE,
                    'message' => $ex->getMessage()
                ));
            }
        }
    }

    public function list_reservation_send_photo($customer_id, $shop_id) {
    //One reservation?
        $reservations = $this->Reservation->query('SELECT R.* FROM '
                . ' reservations AS R '
                . ' where R.user_id="' . $customer_id . '" '
                . ' AND is_deleted =0'
        );
        $reservation_result = array();
        foreach ($reservations as $value) {
            //multiple media?
            $media = $this->Media->query('SELECT * FROM media AS M'
                    . ' where M.user_id="' . $customer_id . '" '
                    . ' AND M.external_id = "' . $value['R']['id'] . '" '
                    . ' AND M.model="reservations"'
                    . ' AND (M.is_deleted <> 1 OR M.is_deleted is null)'
            );
            if ($media) {
                $media_array = array();
                foreach ($media as $media_value) {
                    array_push($media_array, $media_value['M']);
                }
                $data = array(
                    $value['R'],
                    'M' => $media_array
                );
                array_push($reservation_result, $data);
            }
        }
        return json_encode($reservation_result);
    }

    public function schedule_list($reservation_id = NULL) {
        $this->autoRender = false;
        $this->loadModel('Shop');
        if ($this->request->is('ajax')) {
            if ($this->Session->check('ReservationId') == true){
                $reservation_id = $this->Session->read('ReservationId');
            }

            $shop_id = $this->request->query('shop_id');
            if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
                if (!$shop_id && $reservation_id) {
                    $reservation = $this->Reservation->findById($reservation_id);
                    if ($reservation) {
                        $shop_id = $reservation['Reservation']['shop_id'];
                    }
                }
                $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
                if (!$this->Shop->ShopBelongTo($user_id_list, $shop_id)) {
                    throw new NotFoundException();
                }
            } else {
                $shop = $this->Shop->findByUserId($this->Auth->user('id'));
                $shop_id = $shop['Shop']['id'];
            }
            $date = $this->request->query('date');
            $timeline = $this->Reservation->getAppointmentDate($date, $reservation_id, $shop_id);
            echo json_encode(array('timeline' => $timeline));
        }
    }
}