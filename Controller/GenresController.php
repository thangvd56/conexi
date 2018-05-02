<?php

App::uses('AppController', 'Controller');

class GenresController extends AppController {

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
     * Default function list all genre
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_index() {
        $keyword = $this->request->query('Search');
        $this->Paginator->settings = array(
            'conditions' => array(
                'OR' => array(
                    array('Genre.genre LIKE' => '%' . $keyword . '%'),
                    array('Genre.created LIKE' => '%' . $keyword . '%'),
                    array('Genre.remarks LIKE' => '%' . $keyword . '%'),
                )
            ),
            'order' => 'Genre.created DESC',
            'recursive' => -1,
            'paramType' => 'querystring',
            'limit' => PAGE_LIMIT
        );

        try {
            $data = $this->Paginator->paginate('Genre');
            $this->set('allGenres', $data);
            $this->set('title_for_layout', GENRE_MANAGEMENT);
        } catch (NotFoundException $e) {
            $this->redirect('/admin/genres');
        }
    }

    /*
     * function create genre
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_create() {
        if ($this->request->is('post')) {
            $this->Genre->create();
            $getData = $this->request->data;
            if ($this->Genre->save($getData)) {
                $this->Genre->id = $this->Genre->getLastInsertId();
                return $this->redirect('/admin/genres');
            } else {
                $this->Flash->set(__(GENRE_COULD_NOT_BE_SAVE));
            }
        }
        $this->set('title_for_layout', GENRE_MANAGEMENT);
    }

    /*
     * function edit genre
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_edit($id = null) {
        $this->Genre->id = $id;
        if (!$this->Genre->exists()) {
            throw new NotFoundException(__(INVALID_GENRE));
        }
        if ($this->request->is(array('post', 'put'))) {
            $getData = $this->request->data;
            if ($this->Genre->save($getData)) {
                return $this->redirect('/admin/genres');
            } else {
                $this->Flash->set(__(GENRE_COULD_NOT_BE_SAVE));
            }
        } else {
            $options = array('conditions' => array('Genre.' . $this->Genre->primaryKey => $id));
            $this->request->data = $this->Genre->find('first', $options);
        }
        $this->set('title_for_layout', GENRE_MANAGEMENT);
    }

    /*
     * function delete genre
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_delete($id = null) {
        $this->Genre->id = $id;
        $isExist = $this->Genre->exists();
        if (!$isExist) {
            throw new NotFoundException(__(INVALID_GENRE));
        }
        $this->Genre->delete($id);
        return $this->redirect('/admin/genres');
    }

    /*
     * function logout
     * Created 11/ November/2015
     * Vanda
     */

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}
