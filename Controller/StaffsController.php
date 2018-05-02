<?php

App::uses('AppController', 'Controller');

class StaffsController extends AppController {

    public $helpers = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash');
    public $components = array(
        'Paginator',
        'Flash',
        'FileUpload',
        'RequestHandler');
    public $fileType = array(
        'gif',
        'jpeg',
        'png',
        'jpg');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function index() {
        $this->loadModel('Shop');
        $this->loadModel('User');

        if ($this->request->is('post')) {
            if (!empty($this->request->data['Staff']['id'])) {
                for ($i = 0; $i < count($this->request->data['Staff']['id']); $i++) {
                    $this->Staff->id = $this->request->data['Staff']['id'][$i];
                    $this->Staff->saveField('sort', $this->request->data['Staff']['sort'][$i], false);
                }
                //$this->Session->setFlash('Staff order has been rearranged.', 'success');
            }
        }
        $shop_id = $this->request->query('shop_id');
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
        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $shop_id) {
            $shop = $this->Shop->find('first',array(
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
                    'conditions' => array(
                        'Shop.user_id' => $user_id
                    ),
                    'recursive' => -1
                ));
                $shop_id = $shop['Shop']['id'];
            }
            
        }

        $staff = $this->Staff->find('all', array(
            'conditions' => array(
                'shop_id' => $shop_id,
                'is_deleted <>' => 1
            ),
            'recursive' => -1,
            'order' => 'Staff.sort ASC'
        ));
        $this->set('shops', $shops);
        $this->set('staff', $staff);
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete':
                    echo $this->delete();
                    return false;
                case 'publish':
                    echo $this->publish();
                    return false;
                case 'at_work':
                    echo $this->is_at_work();
                    return false;
            }
        }
    }

    public function delete() {
        $staff_id = $this->request->query('staff_id');
        $del_physical = $this->request->query('del_physical');
        $this->Staff->id = $staff_id;
        if (!$this->Staff->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid staff ID'
            ));
        }
        if ($del_physical == 1) {
            $this->Staff->delete($staff_id);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Staff has been deleted'
            ));
        } else {
            $this->Staff->saveField('is_deleted', 1);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Staff has been deleted'
            ));
        }
    }

    public function publish() {
        $staff_id = $this->request->query('staff_id');
        $publish = $this->request->query('publish');

        if ($publish == 'true') {
            $publish = 1;
        } else {
            $publish = 0;
        }
        $this->Staff->id = $staff_id;
        if (!$this->Staff->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid staff ID'
            ));
        }
        $this->Staff->set(array('published' => $publish));
        if ($this->Staff->save()) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Data has been save'
            ));
        }
    }

    public function is_at_work() {
        $staff_id = $this->request->query('staff_id');
        $at_work = $this->request->query('at_work');

        $at_work == 'true' ? $at_work = '1' : $at_work = '0';
        $this->Staff->id = $staff_id;
        if (!$this->Staff->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid staff ID'
            ));
        }

        if ($this->Staff->save(array('Staff' => array('is_at_work' => $at_work)))) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Data has been save'
            ));
        }
    }

    public function create() {
        $this->loadModel('Staff');
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete();
                    return false;
                case 'save';
                    echo $this->save_staff();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_staff();
                return false;
            }
        }
    }

    public function upload_image() {
        $image = $this->request->data['App_Staff']['file_image'];
        echo $this->FileUpload->upload_image($image, 'staffs');
    }

    public function save_staff() {

        $this->loadModel('Staff');
        $this->loadModel('Shop');
        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $this->request->query('shop_id')) {
            $shop_id = $this->request->query('shop_id');
        } else {
            $shop=$this->Shop->findByUserId($this->Auth->user('id'));
            if (!$shop) {
                throw new NotFoundException();
            }
            $shop_id =$shop['Shop']['id'];
        }

        if ($this->request->is('get')) {
            //Insert New
            $last_sort = $this->Staff->find('all', array(
                'conditions' => array(
                    'is_deleted <>' => 1
                ),
                'limit' => 1,
                'order' => 'Staff.sort DESC'
            ));
            $sort = $last_sort[0]['Staff']['sort'];
            $data = array('Staff' => array(
                'shop_id' => $shop_id,
                'name' => $this->request->query('name'),
                'position' => $this->request->query('position'),
                'hobbies' => $this->request->query('hobby'),
                'introduction' => $this->request->query('introduction'),
                'user_id' => $this->Auth->user('id'),
                'image' => $this->request->query('image1'),
                'sort' => $sort + 1
            ));
            if ($this->Staff->save($data)) {
                return json_encode(array(
                    'result' => 'create',
                    'msg' => 'Staff has been saved!'
                ));
            }
        }
    }

    public function edit($id = null)
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete_image();
                    return false;
                case 'edit';
                    echo $this->staff_edit();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->staff_edit();
                return false;
            }
        }

        if (empty($id)) {
            return $this->redirect(array(
                'controller' => 'staffs',
                'action' => 'index'
            ));
        } else {
            //Show in input
            $staff = $this->Staff->find('first', array('conditions' => array('Staff.id' => $id)));
            if ($staff) {
                $this->set('image', $staff['Staff']['image']);
                $this->set('name', $staff['Staff']['name']);
                $this->set('position', $staff['Staff']['position']);
                $this->set('hobby', $staff['Staff']['hobbies']);
                $this->set('introduction', $staff['Staff']['introduction']);
                $this->set('id', $staff['Staff']['id']);
            }
        }
    }

    public function delete_image()
    {
        $image_name = $this->request->query('image_name');
        $id = $this->request->query('image_id');
        //Delete photo
        if (!empty($image_name)) {
            unlink(WWW_ROOT . 'uploads/staffs/' . $image_name);
        }
        //Delete record
        $this->Staff->id = $id;
        $this->Staff->saveField('image', '');
    }

    public function staff_edit()
    {
        $id = $this->request->query('staff_id');
        if ($this->request->is('get')) {
            $this->Staff->id = $id;
            if (!$this->Staff->exists()) {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'error!'
                ));
            }
            $image = '';
            if (!empty($this->request->query('image1'))) {
                $image = $this->request->query('image1');
            } else {
                if (!empty($this->request->query('exist_img'))) {
                    $image = $this->request->query('exist_img');
                }
            }
            $data = array('Staff' => array(
                    'name' => $this->request->query('name'),
                    'position' => $this->request->query('position'),
                    'hobbies' => $this->request->query('hobby'),
                    'introduction' => $this->request->query('introduction'),
                    'image' => $image
            ));
            if ($this->Staff->save($data)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Staff has been saved!'
                ));
            } else {
                 return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Staff has been error!'
                ));
            }
        }
    }
}
