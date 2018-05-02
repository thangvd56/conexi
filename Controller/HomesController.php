<?php

/*
 * Function admin_index
 * Created 02/ December/2015
 * Channeth
 */
App::uses('AppController', 'Controller');

class HomesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->authorize = 'Controller';
        //Configure::write('debug', 2);
    }

    public function index() {
        return $this->redirect('/records');
    }

    public function list_user() {
        $this->autoRender = false;
        $this->loadModel('User');
        $this->User->recursive = -1;
        $users = $this->User->find('all', array(
            'fields' => array('User.id', 'User.lastname_kana', 'User.firstname_kana', 'User.contact'),
            'conditions' => array('User.role <>' => 'shop', 'User.status <> ' => 0)
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
            
            $newArr[$key]['id'] = $value['User']['id'];
            $newArr[$key]['name'] = $value['User']['lastname_kana'].' '.$value['User']['firstname_kana'];
            $newArr[$key]['contact'] = $value['User']['contact'];
        }
        echo json_encode(array_values($newArr));
    }
}
