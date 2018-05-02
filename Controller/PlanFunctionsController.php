<?php

App::uses('AppController', 'Controller');

class PlanFunctionsController extends AppController {

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
     * Modified 17/ November/2015
     * Channeth
     */

    public function admin_index() {
        $plan_id = $this->request->query('plan_id');
        $genre_id = $this->request->query('genre_id');
        if ($this->request->is('post')) {
            //Delete old data
            $this->PlanFunction->deleteAll(array(
                'plan_id' => $plan_id
                    )
            );
            //If checkbox is check save new data
            if ($this->request->data) {
                $check = $this->request->data['PlanFunction']['function'];
                foreach ($check as $key => $checked) {
                    $this->PlanFunction->create();
                    if (strpos($checked, 'Function') !== false) {
                        $this->PlanFunction->saveField('function', $checked);
                        $this->PlanFunction->saveField('plan_id', $plan_id);
                        $this->PlanFunction->id = $this->PlanFunction->getLastInsertId();
                    }
                }
            }
            return $this->redirect('/admin/plans');
        }
        $data = $this->PlanFunction->find('all', array(
            'conditions' => array(
                'AND' => array(
                    array('PlanFunction.plan_id' => $plan_id),
                    array("PlanFunction.function IN (SELECT function FROM genre_functions "
                        . "WHERE genre_functions.genre_id = '" . $genre_id . "' )")
                )
            ),
                )
        );
        $this->loadModel('GenreFunction');
        $genre_data = $this->GenreFunction->find('all', array(
            'conditions' => array('GenreFunction.genre_id' => $genre_id),
            'recursive' => -1
        ));
        $this->set('data', $data);
        $this->set('genre_data', $genre_data);
        $this->set('title_for_layout', PLAN_MANAGEMENT);
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}
