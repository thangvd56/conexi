<?php

App::uses('AppController', 'Controller');

class IpsController extends AppController {

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
     * Default function list all ips
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_index() {
        $keyword = $this->request->query('Search');
        $this->Paginator->settings = array(
            'conditions' => array(
                'OR' => array(
                    array('Ip.ip LIKE' => '%' . $keyword . '%'),
                    array('Ip.created LIKE' => '%' . $keyword . '%'),
                    array('Ip.ramarks LIKE' => '%' . $keyword . '%'),
                )
            ),
            'order' => 'Ip.created DESC',
            'recursive' => 1,
            'paramType' => 'querystring',
            'limit' => PAGE_LIMIT
        );
        try {
            $data = $this->Paginator->paginate('Ip');
            $this->set('allIps', $data);
            $this->set('title_for_layout', IP_ADDRESS_MENAGEMENT);
        } catch (NotFoundException $e) {
            $this->redirect('/admin/ips');
        }
    }

    /*
     * Function create new ips
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_create() {
        $post = $this->request->is('post');
        if ($post) {
            $this->Ip->create();
            $getData = $this->request->data;
            if ($this->Ip->save($getData)) {
                $this->Ip->id = $this->Ip->getLastInsertId();
                return $this->redirect('/admin/ips');
            } else {
                $this->Flash->set(__(IP_COULD_NOT_BE_SAVE));
            }
        }
        $this->set('title_for_layout', IP_ADDRESS_MENAGEMENT);
    }

    /*
     * Function edit ips
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_edit($id = null) {
        $this->Ip->id = $id;
        $isExist = $this->Ip->exists();
        if (!$isExist) {
            throw new NotFoundException(__(INVALID_IP));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Ip->save($this->request->data)) {
                return $this->redirect('/admin/ips');
            } else {
                $this->Flash->set(__(IP_COULD_NOT_BE_SAVE));
            }
        } else {
            $options = array('conditions' => array('Ip.' . $this->Ip->primaryKey => $id));
            $this->request->data = $this->Ip->find('first', $options);
        }
        $this->set('title_for_layout', IP_ADDRESS_MENAGEMENT);
    }

    /*
     * Function delete ips
     * Created 11/ November/2015
     * Vanda
     */

    public function admin_delete($id = null) {
        $this->Ip->id = $id;
        if (!$this->Ip->exists()) {
            throw new NotFoundException(__(INVALID_IP));
        }
        $this->Ip->delete($id);
        return $this->redirect('/admin/ips');
    }

    /*
     * Function logout
     * Created 11/ November/2015
     * Vanda
     */

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}
