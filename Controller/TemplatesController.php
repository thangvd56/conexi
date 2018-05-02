<?php

App::uses('AppController', 'Controller');

class TemplatesController extends AppController {

    public $helpers = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash');
    public $components = array(
        'Paginator',
        'Flash',
        'RequestHandler',
        'ImageResize');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('logout'));
        $this->Auth->authorize = 'Controller';
    }

    public function index() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete':
                    echo $this->delete();
                    return false;
                case 'save':
                    echo $this->save();
                    return false;
            }
        }
    }

    public function fetch_template_lists() {
        $template = $this->Template->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'is_deleted IS NULL',
                    'is_deleted <>' => 1
                )
            ),
            'recursive' => -1
        ));
        $this->set('template', $template);
        $this->layout = 'ajax';
    }

    public function delete() {
        $template_id = $this->request->query('template_id');
        $del_physical = $this->request->query('del_physical');
        $this->Template->id = $template_id;
        if (!($this->Template->exists())) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid Template ID'
            ));
        }
        if ($del_physical == '1') {
            $this->Template->delete($template_id);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Template has been deleted'
            ));
        } else {
            $this->Template->saveField('is_deleted', 1);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Template has been deleted'
            ));
        }
    }

    public function save() {
        $title = $this->request->query('title');
        $remark = $this->request->query('remark');
        $id = $this->request->query('id');
        $template = $this->Template->find('all', array('recursive' => -1));
        $count = count($title);
        $old_title = array();
        foreach ($template as $key => $value) {
            array_push($old_title, $value['Template']['title']);
        }
        for ($i = 0; $i < $count; $i++) {
            $data = array('Template' => array(
                    'title' => $title[$i],
                    'remarks' => $remark[$i]
            ));
            if ($id[$i] == '') {
                if (in_array($title[$i], $old_title)) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Title is already exist'
                    ));
                }
                $this->Template->create();
                if (!$this->Template->save($data)) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Data could not save'
                    ));
                }
            } else {
                $this->Template->id = $id[$i];
                if (!$this->Template->save($data)) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Data could not save'
                    ));
                }
            }
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Data has been saved'
        ));
    }
}
