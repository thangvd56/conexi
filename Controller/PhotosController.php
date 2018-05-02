<?php

App::uses('AppController', 'Controller');

class PhotosController extends AppController {

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
        $this->loadModel('UserShop');
    }

    public function index() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            if ($this->request->is('post')) {
                echo $this->upload_image();
            }
            if ($this->request->is('get')) {
                $action = $this->request->query('action');
                switch ($action) {
                    case 'delete':
                        echo $this->delete();
                        return false;
                    case 'publish':
                        echo $this->publish();
                        return false;
                    case 'save':
                        echo $this->save_all();
                        return false;
                    case 'save_sort':
                        echo $this->save_order();
                        return false;
                }
            }
        }
    }

    public function fetch_photo_list() {
        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
        $shop_id= $shops[0]['shops']['id'];
        $conditions = array(
            'OR' => array(
                'is_deleted is NULL',
                'is_deleted <>' => 1
            ),
            'AND' => array(
                'shop_id'=>$shop_id
            )
        );
        $photo = $this->Photo->find('all', array(
            'conditions' => $conditions,
            'order' => array('sort' => 'asc'),
            'recursive' => -1));
        $this->set('photo', $photo);
        $this->layout = 'ajax';
    }

    public function upload_image() {
        $image = $this->request->data['Photo']['file_image'];
        echo $this->FileUpload->upload_image($image, 'photo_gallerise');
    }

    public function delete() {
        $id = $this->request->query('photo_id');
        $del_physical = $this->request->query('del_physical');
        $this->Photo->id = $id;
        if (!$this->Photo->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }
        if ($del_physical == 1) {
            //DELETE IMAGE
            $photo = $this->Photo->findById($id);
            $image = $photo['Photo']['image'];
            if (!empty($image)) {
                unlink(WWW_ROOT . 'uploads/photo_gallerise/' . $image);
            }
            //DELETE DATA
            $this->Photo->delete($id);
        } else {
            $this->Photo->saveField('is_deleted', 1);
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Delete successful'
        ));
    }

    public function publish() {
        $published = $this->request->query('published');
        $id = $this->request->query('photo_id');
        $this->Photo->id = $id;
        if (!$this->Photo->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID!'
            ));
        }
        $published == 'true' ? $published = '1' : $published = '0';
        if ($this->Photo->saveField('published', $published)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Photo has been saved!'
            ));
        }
    }

    public function save_all() {
        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
        $shop_id= $shops[0]['shops']['id']; //User now have only one shop
        $data = $this->request->query('data');
        foreach ($data as $key => $value) {
            foreach ($value as $k => $val) {
                $photo = array('Photo' => array( 'shop_id'=>$shop_id, 'image' => $val['image'], 'title' => $val['title']));
                if ($val['id'] != '') {
                    //UPDATE
                    $this->Photo->id = $val['id'];
                    if (!$this->Photo->save($photo)) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Photo could not save'
                        ));
                    }
                } else {
                    //ADD NEW
                    $this->Photo->create();
                    if (!$this->Photo->save($photo)) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Photo could not save'
                        ));
                    }
                }
            }
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Data has been saved!'
        ));
    }

    public function save_order() {
        $data = $this->request->query('data');
        $sort = 1;
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $photo = array('Photo' => array(
                        'sort' => $sort
                ));
                if (($val['id'] != '')) {
                    //UPDATE
                    $this->Photo->id = $val['id'];
                    if (!$this->Photo->save($photo)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot update field'
                        ));
                    }
                }
                $sort++;
            }
        }
        return json_encode(array(
            'result' => 'Success',
            'msg' => 'Data has been saved'
        ));
    }

}
