<?php
App::uses('AppController', 'Controller');

class RecordsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->authorize = 'Controller';

        $this->loadModel('Shop');
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $this->loadModel('Chair');
        $this->loadModel('Staff');
    }

    public function index()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'checkin':
                    echo $this->check_in();
                    return false;
                case 'cancel':
                    echo $this->cancel_reservation();
                    return false;
                case 'delete':
                    echo $this->delete();
                    return false;
            }
        }

        $user_id_list = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        } else {
            $user_id_list[] = $this->Auth->user('id');
        }
        $shop = $this->Shop->find('list', array(
            'fields' => array('Shop.id', 'Shop.shop_name'),
            'conditions' => array(
                'is_deleted <>' => 1,
                'user_id' => $user_id_list
            ),
            'recursive' => -1,
        ));

        $this->loadModel('Chair');
        $shop_id = $this->request->query('shop_id');
        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $shop_id) {
            //check shop if it belong to this user
            if (!$this->Shop->ShopBelongTo($user_id_list, $shop_id)) {
                throw new NotFoundException();
            }
            $this->set('chair', $this->Chair->getChairData('', $shop_id));
        } else {
            foreach ($shop as $key => $value) {
                $this->set('chair', $this->Chair->getChairData('', $key));
                $shop2 = $this->Shop->findById($key);
                $shop_id = $shop2 ? $shop2['Shop']['id'] : '';
                break;
            }
        }
        $this->set('shop', $shop);
        $this->set('shop_id', $shop_id);
    }

    public function fetch_records()
    {
        $action = $this->request->query('action');
        $option = $this->request->query('option');
        $year = $this->request->query('year');
        $month = $this->request->query('month');
        $today = $this->request->query('today');
        $this->loadModel('Shop');
        $this->loadModel('UserShop');

        $shop_id = $this->request->query('shop_id');
        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $shop_id) {
            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
            if (!$this->Shop->ShopBelongTo($user_id_list, $shop_id)) {
                throw new NotFoundException();
            }
        } else {
            $shop = $this->UserShop->getShopId($this->Auth->user('id'));
            if ($shop) {
                $shop_id = $shop['UserShop']['shop_id'];
            }
        }

        switch ($action) {
            case 'default':
                $data = $this->get_reservation_default($shop_id);
                break;
            case 'onchange':
                $data = $this->get_reservation_onchange($option, $year, $month, $today, $shop_id);
                break;
        }
        $this->set('data', $data);
        $this->layout = 'ajax';
    }
    
    //Function load default
    public function get_reservation_default($shop_id)
    {
        $this->Reservation->recursive = 2;
        $order =
            'CASE
                WHEN Reservation.status = "visit" AND Reservation.is_checkin = 0 THEN 1
                WHEN Reservation.status = "visit" AND Reservation.is_checkin = 1 THEN 2
             END ';
        $sql = $this->Reservation->find('all', array(
            'fields' => array('*'),
            'conditions' => array(
                'Reservation.shop_id' => $shop_id,
                'Reservation.is_completed' => 1,
                'Reservation.is_deleted <>' => 1,
                'Reservation.status ' => 'visit',
                'YEAR(Reservation.date)' => date('Y'),
                'MONTH(Reservation.date)' => date('m'),
                'DAY(Reservation.date)' => date('d')
            ),
            'order' => array($order. ',Reservation.date desc,Reservation.start asc , Reservation.end asc'),
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
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => array('User.id = Reservation.user_id')
                )
            )
        ));
        return $sql;
    }
    //Function get records by onchange option,year,month
    public function get_reservation_onchange($option, $year, $month, $today, $shop_id)
    {
        $condition = '';
        if ($option == 'monthly') {
            $condition = " YEAR(Reservation.date)='$year' AND MONTH(Reservation.date) ='$month' ";
        } elseif ($option == 'today') {
            $condition = " YEAR(Reservation.date)='$year' AND MONTH(Reservation.date) ='$month' AND DAY(Reservation.date)='$today' ";
        }
        $this->Reservation->recursive = 2;
        $order =
            'CASE
                WHEN Reservation.status = "visit" AND Reservation.is_checkin = 0 THEN 1
                WHEN Reservation.status = "visit" AND Reservation.is_checkin = 1 THEN 2
             END ';
        $sql = $this->Reservation->find('all', array(
            'fields' => array('*'),
            'conditions' => array(
                'Reservation.shop_id' => $shop_id,
                'Reservation.is_completed' => 1,
                'Reservation.is_deleted <>' => 1,
                'Reservation.status ' => 'visit',
                $condition
            ),
            'order' => array($order. ',Reservation.date desc,Reservation.start asc , Reservation.end asc'),
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
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'left',
                    'conditions' => array('User.id = Reservation.user_id')
                )
            )
        ));
        return $sql;
    }
    //Checkin customer
    public function check_in()
    {
        $this->loadModel('Reservation');
        $this->loadModel('StampSetting');
        $this->loadModel('Stamp');
        $this->loadModel('UserShop');
        $this->loadModel('Log');
        $id = $this->request->query('id');
        $this->Reservation->id = $id;
        if (!$this->Reservation->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid reservation ID'
            ));
        }
        $data_checkin =array('Reservation'=>array(
            'is_checkin'=> 1,
            'checkin_date'=> date("Y-m-d")
        ));
        $respond = $this->Reservation->save($data_checkin);
        if ($respond) {
            //When user checkin will get stamp
            $sql_user_id = $this->Reservation->query('SELECT R.user_id from reservations R where R.id= '.$id.'');
            $getShop= $this->Shop->query('SELECT shops.id from shops where shops.user_id="'.$this->Auth->user('id').'" ');
            $shop_id = $getShop[0]['shops']['id'];
            $sql_stamp_info = $this->StampSetting->query('SELECT S.id,S.app_checkin from stamp_settings S where S.shop_id='.$shop_id.'');

            $data_stamp = array('Stamp'=>array(
                'user_id' =>$sql_user_id[0]['R']['user_id'],
                'stamp_setting_id'=>$sql_stamp_info[0]['S']['id'],
                'stamp_type'=>'app_checkin',
                'count'=>$sql_stamp_info[0]['S']['app_checkin']
            ));
            if($this->Stamp->save($data_stamp)){
                $data_log=array('Log'=>array(
                    'user_id' =>$sql_user_id[0]['R']['user_id'],
                    'type' =>'app_checkin',
                    'value' =>'Get full stamp app_checkin from : '.$sql_stamp_info[0]['S']['id'].''
                ));
                $this->Log->save($data_log);
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Reservation has been check in'
                ));
            }else{
               return json_encode(array(
                'result' => 'error',
                'msg' => 'Reservation cannot check in'
            ));
            }
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Reservation cannot check in'
            ));
        }
    }
    //Function cancel reservation
    public function cancel_reservation()
    {
        $this->loadModel('Reservation');
        $id = $this->request->query('id');
        $this->Reservation->id = $id;
        if (!$this->Reservation->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid reservation ID'
            ));
        }
        $data_cancel =array('Reservation'=>array(
            'status'=> 'cancel'
        ));
        $respond = $this->Reservation->save($data_cancel);
        if ($respond) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Reservation has been cancel'
            ));

        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Reservation cannot cancel'
            ));
        }
    }
    //Function Delete reservation
    public function delete()
    {
        $this->loadModel('Reservation');
        $this->loadModel('ReservationTag');
        $id = $this->request->query('reservation_id');
        $del_physical = $this->request->query('del_physical');
        $this->Reservation->id = $id;
        if (!$this->Reservation->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }
        $reservation_tag = $this->ReservationTag->query('select R.* from reservation_tag as R where R.reservation_id='.$id.''
            .' and R.is_deleted <> 1 OR R.is_deleted is null');
        if ($del_physical == 1) {
            $this->Reservation->delete($id);
            for ($i = 0; $i < count($reservation_tag); $i++) {
                $this->ReservationTag->id = $reservation_tag[$i]['R']['id'];
                $this->ReservationTag->delete($reservation_tag[$i]['R']['id']);
            }
        } else {
            $this->Reservation->saveField('is_deleted', 1);
            for ($i = 0; $i < count($reservation_tag); $i++) {
                $this->ReservationTag->id = $reservation_tag[$i]['R']['id'];
                $this->ReservationTag->saveField('is_deleted', 1);
            }
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Delete successful'
        ));
    }

}