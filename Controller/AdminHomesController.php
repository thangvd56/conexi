<?php
App::uses('AppController', 'Controller');

class AdminHomesController extends AppController {
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function admin_index()
    {
        $this->set('title_for_layout', HOME);
        if ($this->Auth->login()) {
            $role = $this->Auth->user();
            if ($role['role'] == 'admin') {
                return $this->redirect('/admin/users?role=shop');
            }
        }
    }

    public function index()
    {
        $this->layout = false;
        $this->set('title_for_layout', HOME);
    }
}