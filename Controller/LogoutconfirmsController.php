<?php

App::uses('AppController', 'Controller');

class LogoutConfirmsController extends AppController {

    public $helpers = array(
        'Html',
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function index() {
        $this->layout = false;
        $this->set('title_for_layout','Logout Confirm');
    }

}
