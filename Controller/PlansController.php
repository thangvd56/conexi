<?php

App::uses('AppController', 'Controller');

class PlansController extends AppController {

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
                    array('Plan.name LIKE' => '%' . $keyword . '%'),
                    array('Plan.created LIKE' => '%' . $keyword . '%'),
                    array('Plan.remarks LIKE' => '%' . $keyword . '%'),
                )
            ),
            'order' => 'Plan.created DESC',
            'recursive' => -1,
            'paramType' => 'querystring',
            'limit' => PAGE_LIMIT
        );
        //Prevent invalid page number
        try {
            $data = $this->Paginator->paginate('Plan');
            $this->set('allPlans', $data);
            $this->set('title_for_layout', PLAN_MANAGEMENT);
        } catch (NotFoundException $e) {
            $this->redirect('/admin/plans');
        }
    }

    /*
     * function create genre
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_create() {
        if ($this->request->is('post')) {
            $this->Plan->create();
            if ($this->Plan->save($this->request->data)) {
                $this->Plan->id = $this->Plan->getLastInsertId();
                return $this->redirect('/admin/plans');
            } else {
                $this->Flash->set(__(PLAN_COULD_NOT_BE_SAVE));
            }
        }
        $this->loadModel('Genre');
        $data = $this->Genre->find('all', array(
            'order' => array(
                'Genre.genre' => 'ASC'
            ),
        ));
        $this->set('title_for_layout', PLAN_MANAGEMENT);
        $this->set('allGenres', $data);
    }

    /*
     * function edit genre
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_edit($id = null) {
        $this->Plan->id = $id;
        if (!$this->Plan->exists()) {
            throw new NotFoundException(__(INVALID_PLAN));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Plan->save($this->request->data)) {
                return $this->redirect('/admin/plans');
            } else {
                $this->Flash->set(__(PLAN_COULD_NOT_BE_SAVE));
            }
        } else {
            $options = array('conditions' => array('Plan.' . $this->Plan->primaryKey => $id));
            $this->request->data = $this->Plan->find('first', $options);
        }
        $this->loadModel('Genre');
        $data = $this->Genre->find('all', array(
            'order' => array(
                'Genre.genre' => 'ASC'
            ),
        ));
        $this->set('allGenres', $data);
        $this->set('title_for_layout', PLAN_MANAGEMENT);
    }

    /*
     * function delete genre
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_delete($id = null) {
        $this->Plan->id = $id;
        if (!$this->Plan->exists()) {
            throw new NotFoundException(__(INVALID_PLAN));
        }
        $this->Plan->delete($id);
        return $this->redirect('/admin/plans');
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
