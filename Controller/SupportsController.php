<?php

App::uses('AppController', 'Controller');

class SupportsController extends AppController {

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
        $this->Auth->allow(array(
            'logout',
            'index'
        ));
        $this->Auth->authorize = 'Controller';
        $this->loadModel('UserShop');
    }

    public function index() {
        $this->loadModel('Support');
        $this->loadModel('QuestionAnswer');
//        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
//        $shop_id= $shops[0]['shops']['id'];
        $support_plan = $this->Support->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'is_deleted IS NULL',
                    'is_deleted <>' => 1
                ),
                'AND' => array(
                //'shop_id'=>$shop_id
            )
            ),
           'order' => array('id' => 'desc'),
            'recursive' => -1
        ));
        $this->set('support', $support_plan);
         $question_answer = $this->QuestionAnswer->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'is_deleted IS NULL',
                    'is_deleted <>' => 1
                ),'AND' => array(
                //'shop_id'=>$shop_id
            )
            ),
            'order' => array('id' => 'desc'),
            'recursive' => -1
        ));
        $this->set('question_answer', $question_answer);
    }

//    public function create() {
//        if ($this->request->is('ajax')) {
//            $this->autoRender = false;
//            $action = $this->request->query('action');
//            switch ($action) {
//                case 'delete':
//                    echo $this->delete();
//                    return false;
//                case 'save':
//                    echo $this->save();
//                    return false;
//                case 'delete_question_answer':
//                    echo $this->delete_question_answer();
//                     return false;
//                case 'save_question_answer':
//                    echo $this->save_question_answer();
//                    return false;
//            }
//        }
//    }
    
//    //Function save support plan
//    public function save() {
//        $this->loadModel('Support');
//        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
//        $shop_id= $shops[0]['shops']['id'];
//        $support_plan = $this->request->query('support_plan');
//        $title = $this->request->query('title');
//        $detail = $this->request->query('detail');
//        $id = $this->request->query('edit_id');
//
//        $data = array('Support' => array(
//                'shop_id' =>$shop_id,
//                'support_plan' => $support_plan,
//                'title' => $title,
//                'detail' => $detail
//        ));
//        if ($id == '') {
//            $this->Support->create();
//            if (!$this->Support->save($data)) {
//                return json_encode(array(
//                    'result' => 'error',
//                    'msg' => 'Data could not saved'
//                ));
//            }
//        } else {
//            $this->Support->id = $id;
//            if (!$this->Support->save($data)) {
//                return json_encode(array(
//                    'result' => 'error',
//                    'msg' => 'Data could not updated'
//                ));
//            }
//        }
//        return json_encode(array(
//            'result' => 'success',
//            'msg' => 'Data has been saved'
//        ));
//    }
//
//    //Function save question and answer
//    public function save_question_answer() {
//        $this->loadModel('QuestionAnswer');
//        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
//        $shop_id= $shops[0]['shops']['id'];
//        $question = $this->request->query('question');
//        $answer = $this->request->query('answer');
//        $id = $this->request->query('question_answer_edit_id');
//        $data = array('QuestionAnswer' => array(
//                'shop_id'=>$shop_id,
//                'question' => $question,
//                'answer' => $answer
//        ));
//        if ($id == '') {
//            if (!$this->QuestionAnswer->save($data)) {
//                return json_encode(array(
//                    'result' => 'error',
//                    'msg' => 'Data has not saved'
//                ));
//            } else {
//                return json_encode(array(
//                    'result' => 'success',
//                    'msg' => 'Data has been saved'
//                ));
//            }
//        } else {
//            $this->QuestionAnswer->id = $id;
//            if (!$this->QuestionAnswer->save($data)) {
//                return json_encode(array(
//                    'result' => 'error',
//                    'msg' => 'Data has not updated'
//                ));
//            } else {
//                return json_encode(array(
//                    'result' => 'success',
//                    'msg' => 'Data has been updated'
//                ));
//            }
//        }
//    }
//
//    //Function delete about support
//    public function delete() {
//        $this->loadModel('Support');
//        $support_id = $this->request->query('support_id');
//        $del_physical = $this->request->query('del_physical');
//        $this->Support->id = $support_id;
//        if (!($this->Support->exists())) {
//            return json_encode(array(
//                'result' => 'error',
//                'msg' => 'Invalid Support ID'
//            ));
//        }
//        if ($del_physical == '1') {
//            $this->Support->delete($support_id);
//            return json_encode(array(
//                'result' => 'success',
//                'msg' => '$support_id has been deleted'
//            ));
//        } else {
//            $this->Support->saveField('is_deleted', 1);
//            return json_encode(array(
//                'result' => 'success',
//                'msg' => 'Support has been deleted'
//            ));
//        }
//    }
//
//    //Function delete question and answer
//    public function delete_question_answer() {
//        $this->loadModel('QuestionAnswer');
//        $question_answer_id = $this->request->query('question_answer_id');
//        $del_physical = $this->request->query('del_physical');
//        $this->QuestionAnswer->id = $question_answer_id;
//        if (!($this->QuestionAnswer->exists())) {
//            return json_encode(array(
//                'result' => 'error',
//                'msg' => 'Invalid question_answer_id ID'
//            ));
//        }
//        if ($del_physical == '1') {
//            $this->QuestionAnswer->delete($question_answer_id);
//            return json_encode(array(
//                'result' => 'success',
//                'msg' => 'Question and Answer has been deleted from db'
//            ));
//        } else {
//            $this->QuestionAnswer->saveField('is_deleted', 1);
//            return json_encode(array(
//                'result' => 'success',
//                'msg' => 'Question and Answer has been updated is_deleted=1'
//            ));
//        }
//    }
//
//    public function fetch_support_lists() {
//        $this->loadModel('Shop');
//        $getShop=$this->Shop->query('SELECT shops.id from shops where shops.user_id="'.$this->Auth->user('id').'" ');
//        $is_shop ="";
//        if(!empty($getShop)){
//            $is_shop =$getShop[0]['shops']['id'];
//        }
//        $support_plan = $this->Support->find('all', array(
//            'conditions' => array(
//                'OR' => array(
//                    'is_deleted IS NULL',
//                    'is_deleted <>' => 1
//                ),
//                'shop_id'=> $is_shop
//            ),
//            'recursive' => -1
//        ));
//        $this->set('supports', $support_plan);
//        //$this->layout = 'ajax';
//    }
//
//    public function fetch_question_answer_lists() {
//        $this->loadModel('QuestionAnswer');
//        $this->loadModel('Shop');
//        $getShop=$this->Shop->query('SELECT shops.id from shops where shops.user_id="'.$this->Auth->user('id').'" ');
//        $is_shop ="";
//        if(!empty($getShop)){
//            $is_shop =$getShop[0]['shops']['id'];
//        }
//        $question_answer = $this->QuestionAnswer->find('all', array(
//            'conditions' => array(
//                'OR' => array(
//                    'is_deleted IS NULL',
//                    'is_deleted <>' => 1
//                ),
//                'shop_id'=> $is_shop
//            ),
//            'recursive' => -1
//        ));
//        $this->set('question_answer', $question_answer);
//    }
        //----------------Admin create support and question & answers-----------------//

    public function admin_index() {
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
                case 'delete_question_answer':
                    echo $this->delete_question_answer();
                     return false;
                case 'save_question_answer':
                    echo $this->save_question_answer();
                    return false;
            }
        }
    }

    //Function save support plan
    public function save() {
        $this->loadModel('Support');
//        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
//        $shop_id= $shops[0]['shops']['id'];
        $support_plan = $this->request->query('support_plan');
        $title = $this->request->query('title');
        $detail = $this->request->query('detail');
        $id = $this->request->query('edit_id');

        $data = array('Support' => array(
                'support_plan' => $support_plan,
                'title' => $title,
                'detail' => $detail
        ));
        if ($id == '') {
            $this->Support->create();
            if (!$this->Support->save($data)) {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Data could not saved'
                ));
            }
        } else {
            $this->Support->id = $id;
            if (!$this->Support->save($data)) {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Data could not updated'
                ));
            }
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Data has been saved'
        ));
    }

    //Function save question and answer
    public function save_question_answer() {
        $this->loadModel('QuestionAnswer');
//        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
//        $shop_id= $shops[0]['shops']['id'];
        $question = $this->request->query('question');
        $answer = $this->request->query('answer');
        $id = $this->request->query('question_answer_edit_id');
        $data = array('QuestionAnswer' => array(
                'question' => $question,
                'answer' => $answer
        ));
        if ($id == '') {
            if (!$this->QuestionAnswer->save($data)) {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Data has not saved'
                ));
            } else {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Data has been saved'
                ));
            }
        } else {
            $this->QuestionAnswer->id = $id;
            if (!$this->QuestionAnswer->save($data)) {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Data has not updated'
                ));
            } else {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Data has been updated'
                ));
            }
        }
    }

    //Function delete about support
    public function delete() {
        $this->loadModel('Support');
        $support_id = $this->request->query('support_id');
        $del_physical = $this->request->query('del_physical');
        $this->Support->id = $support_id;
        if (!($this->Support->exists())) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid Support ID'
            ));
        }
        if ($del_physical == '1') {
            $this->Support->delete($support_id);
            return json_encode(array(
                'result' => 'success',
                'msg' => '$support_id has been deleted'
            ));
        } else {
            $this->Support->saveField('is_deleted', 1);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Support has been deleted'
            ));
        }
    }

    //Function delete question and answer
    public function delete_question_answer() {
        $this->loadModel('QuestionAnswer');
        $question_answer_id = $this->request->query('question_answer_id');
        $del_physical = $this->request->query('del_physical');
        $this->QuestionAnswer->id = $question_answer_id;
        if (!($this->QuestionAnswer->exists())) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid question_answer_id ID'
            ));
        }
        if ($del_physical == '1') {
            $this->QuestionAnswer->delete($question_answer_id);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Question and Answer has been deleted from db'
            ));
        } else {
            $this->QuestionAnswer->saveField('is_deleted', 1);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Question and Answer has been updated is_deleted=1'
            ));
        }
    }

    public function admin_fetch_support_lists() {
        $this->loadModel('Shop');
        $getShop=$this->Shop->query('SELECT shops.id from shops where shops.user_id="'.$this->Auth->user('id').'" ');
        $is_shop ="";
        if(!empty($getShop)){
            $is_shop =$getShop[0]['shops']['id'];
        }
        $support_plan = $this->Support->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'is_deleted IS NULL',
                    'is_deleted <>' => 1
                ),
                //'shop_id'=> $is_shop
            ),
            'recursive' => -1
        ));
        $this->set('supports', $support_plan);
        //$this->layout = 'ajax';
    }

    public function admin_fetch_question_answer_lists() {
        $this->loadModel('QuestionAnswer');
        $this->loadModel('Shop');
        $getShop=$this->Shop->query('SELECT shops.id from shops where shops.user_id="'.$this->Auth->user('id').'" ');
        $is_shop ="";
        if(!empty($getShop)){
            $is_shop =$getShop[0]['shops']['id'];
        }
        $question_answer = $this->QuestionAnswer->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'is_deleted IS NULL',
                    'is_deleted <>' => 1
                ),
                //'shop_id'=> $is_shop
            ),
            'recursive' => -1
        ));
        $this->set('question_answer', $question_answer);
    }
}
