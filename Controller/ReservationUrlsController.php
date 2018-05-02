<?php

App::uses('AppController', 'Controller');

class ReservationUrlsController extends AppController {

    public $helpers = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash');
    public $components = array(
        'Paginator',
        'Flash',
        'RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function index() {

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            if ($action == 'save') {
                echo $this->save();
            }
        }
//        $this->loadModel('Shop'); 
//        $shop = $this->Shop->find('list', array(
//            'conditions' => array(
//                'OR' => array(
//                    'is_deleted IS NULL',
//                    'is_deleted <>' => 1
//                ),
//                'AND' => array(
//                    'user_id' => $user_id
//                )),
//            'recursive' => -1,
//            'order' => 'Shop.name ASC'
//        ));
        //$this->set('shop', $shop);
        $data = $this->ReservationUrl->find('first', array('ReservationUrl.user_id' => $this->Auth->user('id')));
        if ($data) {
            $url = $data['ReservationUrl']['url'];
            $this->set('url', $url);
        } else {
            $this->set('url', '');
        }
    }
    public function save() {

        if ($this->request->is('get')) {
            //$shop_id = $this->request->data['ReservationUrl']['shop_id'];
            $user_id = $this->Auth->user('id');
            $res_url = $this->request->query('url');
            $data = array('ReservationUrl' => array(
                    'user_id' => $user_id,
                    'url' => $res_url
            ));
            $url = $this->ReservationUrl->find('count', array('recursive' => -1));
            if ($url > 0) {
                $url_data = $this->ReservationUrl->find('first', array('recursive' => -1));
                $id = $url_data['ReservationUrl']['id'];
                $this->ReservationUrl->id = $id;
                if ($this->ReservationUrl->save($data)) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'ReservationUrl have been updated'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'ReservationUrl error saved'
                    ));
                }
            } else {
                $this->ReservationUrl->create();
                if ($this->ReservationUrl->save($data)) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'ReservationUrl have been saved'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'ReservationUrl error saved'
                    ));
                }
            }
        }
    }

}
