<?php
App::uses('AppController', 'Controller');

class ChairsController extends AppController
{
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

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('logout'));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->loadModel('User');
        $this->loadModel('Shop');
        $user_id_list = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        } else {
            $user_id_list[] = $this->Auth->user('id');
        }
        $shops = $this->Shop->find('list', array(
            'fields' => array('Shop.id', 'Shop.shop_name'),
            'conditions' => array(
                'is_deleted <>' => 1,
                'user_id' => $user_id_list
            ),
            'recursive' => -1,
        ));
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
        $this->set('shops', $shops);
    }

    public function fetch_chair_lists()
    {
        $this->loadModel('Shop');
        $this->loadModel('ChairCapacity');
        $this->loadModel('Chair');
        $this->loadModel('User');

        $shop_id = $this->request->query('shop_id');
        $user_id_list = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        } else {
            $user_id_list[] = $this->Auth->user('id');
        }

//        $shops = $this->Shop->find('list', array(
//            'fields' => array('Shop.id', 'Shop.shop_name'),
//            'conditions' => array(
//                'is_deleted <>' => 1,
//                'user_id' => $user_id_list
//            ),
//            'recursive' => -1,
//        ));

        $shop = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $shop_id) {
            $shop = $this->Shop->find('first', array(
                'conditions' => array(
                    'id' => $shop_id
                ),
                'recursive' => -1
            ));
        } else {
            if (count($user_id_list) > 0) {
                foreach($user_id_list as $key => $value) {
                    $user_id = $value;
                    break;
                }
                $shop = $this->Shop->find('first', array(
                    'conditions' => array('Shop.user_id' => $user_id),
                    'recursive' => -1
                ));
                $shop_id = $shop['Shop']['id'];
            }
        }

        $chair = array();
        if ($shop) {
            $chair = $this->Chair->find('all', array(
                'conditions' => array(
                    'shop_id' => $shop_id,
                    'is_deleted <>' => 1
                )
            ));
        }
        $this->set('chair', $chair);
        
        //Select Capacity
        $capacity = $this->ChairCapacity->find('list');
        $this->set('capacity', $capacity);
        $this->layout = 'ajax';
    }

    public function delete()
    {
        $chair_id = $this->request->query('chair_id');
        $del_physical = $this->request->query('del_physical');
        $this->Chair->id = $chair_id;
        if (!($this->Chair->exists())) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid Chair ID'
            ));
        }
        if ($del_physical == '1') {
            $this->Chair->delete($chair_id);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Chair has been deleted'
            ));
        } else {
            $this->Chair->saveField('is_deleted', 1);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Chair has been deleted'
            ));
        }
    }

    public function save()
    {
        $this->loadModel('Shop');        

        if ($this->Auth->user('role') == ROLE_HEADQUARTER && $this->request->query('shop_id')) {
            $shop_id = $this->request->query('shop_id');
        } else {
            $shop=$this->Shop->findByUserId($this->Auth->user('id'));
            if (!$shop) {
                throw new NotFoundException();
            }
            $shop_id = $shop['Shop']['id'];
        }
        $chair_name = $this->request->query('chair_name');
        $capacity = $this->request->query('capacity');
        $id = $this->request->query('id');
        $chair = $this->Chair->find('all', array(
            'conditions' => array(
                'shop_id' => $shop_id,
                'is_deleted <>' =>1
            )
        ));
        $count = count($chair_name);
        $old_chair_name = array();
        foreach ($chair as $key => $value) {
            array_push($old_chair_name, $value['Chair']['chair_name']);
        }

        for ($i = 0; $i < $count; $i++) {
            $data = array('Chair' => array(
                    'shop_id' => $shop_id,
                    'chair_name' => $chair_name[$i],
                    'capacity' => $capacity[$i]
            ));

            if ($id[$i] == '') {
                if (in_array($chair_name[$i], $old_chair_name)) {
                    return json_encode(array(
                        'result' => 'exist',
                        'msg' => 'Chair name is already exist'
                    ));
                }

                if($chair_name[$i]==''){
                     return json_encode(array(
                        'result' => 'exist',
                        'msg' => 'Chair name is empty'
                    ));
                }

                $this->Chair->create();
                if (!$this->Chair->save($data)) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Data could not save'
                    ));
                }
            } else {
                $this->Chair->id = $id[$i];
                if (!$this->Chair->save($data)) {
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