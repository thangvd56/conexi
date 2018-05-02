<?php

App::uses('AppController', 'Controller');

class SnsSharesController extends AppController {

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

        $sns = $this->SnsShare->find('first');
        $this->set('sns', $sns);
        ///////////////Get Ajax//////////////
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            if ($action == 'save') {
                echo $this->save();
            }
        }
    }

    public function save() {

        $sns_count = $this->SnsShare->find('count');
        $title = $this->request->query('title');
        $description = $this->request->query('description');
        if ($this->request->is('get')) {
            if ($sns_count > 0) {
               $sns = $this->SnsShare->find('first');
                $id = $sns['SnsShare']['id'];
                $this->SnsShare->id = $id;
                $data = array('SnsShare' => array(
                        'title' => $title,
                        'description' => $description
                ));
                if ($this->SnsShare->save($data)) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Data has been updated'
                    ));
                }
            } else {
                $this->SnsShare->create();
                if ($this->SnsShare->save($data)) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Data has been saved'
                    ));
                }
            }
        }
    }
}
