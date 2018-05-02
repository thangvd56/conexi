<?php

App::uses('AppController', 'Controller');

class GenreFunctionsController extends AppController {

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

    /*
     * function set function of each genre by checking checkboxes or unchecked
     * Created 13/ November/2015
     * Vanda
     */

    public function admin_index() {
        $genre_id = $this->request->query('genre_id');
        if ($this->request->is('post')) {
            $check = $this->request->data['GenreFunction']['function'];
            $this->GenreFunction->deleteAll(array(
                'genre_id' => $genre_id
                    )
            );
            foreach ($check as $key => $checked) {
                $this->GenreFunction->create();
                if (strpos($checked, 'Function') !== false) {
                    $this->GenreFunction->saveField('function', $checked);
                    $this->GenreFunction->saveField('genre_id', $genre_id);
                    $this->GenreFunction->id = $this->GenreFunction->getLastInsertId();
                }
            }
            return $this->redirect('/admin/genres');
        }
        $data = $this->GenreFunction->find('all', array('conditions' => array(
                'GenreFunction.genre_id' => $genre_id
        )));
        $this->set('data', $data);
        $this->set('title_for_layout', GENRE_MANAGEMENT);
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}
