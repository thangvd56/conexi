<?php
App::uses('AppController', 'Controller');

class PrototypesController extends AppController
{
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('test_reservation');
        $this->Auth->authorize = 'Controller';
        $this->loadModel('User');
    }

    public function test_graph()
    {
        $this->loadModel('User');
        $this->loadModel('Shop');

        $this->layout = false;        
        $year_list = array();

        for ($i = date('Y') - 27; $i <= date('Y'); $i++) {
            $year_list[$i] = $i . ' 年';
        }
        krsort($year_list);
        $this->set(compact('year_list'));
        
        $user_id_list = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        } else {
            $user_id_list[] = $this->Auth->user('id');
        }
        $shops = $this->Shop->find('list', array(
            'fields' => array('Shop.id', 'Shop.shop_name'),
            'conditions' => array(
                'is_deleted <>' => 1,
                'user_id' => $user_id_list
            ),
            'recursive' => -1,
        ));
        $this->set(compact('shops'));
    }

    public function test_reservation()
    {
        $this->layout = false;
    }
    
    public function test_push()
    {
        $this->loadModel('Reservation');
        $key = 'AIzaSyBVQdSN1sUsyHTlRZ6LYF4x0QTB94Ig4cw';
        $deviceKey = 'fWeLyTAS7ZM:APA91bFtyvlDgXK5R8u2HLeBfs8jdCYL80UIDhXjG0yLX76gmr_EQDbSRZqfgwwwG4jnlyRmNBOBfvUQkjBdtIPjTsFqd-gXnRDo0B4ifNiWzHqV30A1TJ7m9Hldq_a6lc2qkfAKkxMY';
        $this->Reservation->send_android_notification($key, $deviceKey, 'This is a test from Browser');
        exit;
    }

    //send notification 2hours and 1day before.
    public function test_reservation_notice()
    {
        $this->loadModel('Reservation');
        $notice = $this->Reservation->send_reservation_notification();

        pr($notice);
        exit;
    }

    public function test_user()
    {
        //test user that not yet send notification
        $this->loadModel('User');
        pr($this->User->get_user_id_of_shop());
        exit;
    }

    public function test_send_news()
    {
        Configure::write('debug', 2);
        $this->loadModel('News');
        pr($this->News->send_news_notification());
        exit;
    }
}