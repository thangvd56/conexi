<?php

App::uses('AppController', 'Controller');

class PhotoListsController extends AppController {

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
                case 'save_sort':
                    echo $this->save_sort();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
            } else if ($this->request->is('get')) {
                echo $this->save_all();
            }
        }
        $photo_id = $this->request->query('photo_id');
        if (empty($photo_id)) {
            $this->redirect(array(
                'controller' => 'photos',
                'action' => 'index'
            ));
        }
    }

    public function fetch_photo_gallery_list() {
        $photo_id = $this->request->query('photo_id');
        $conditions = array(
            'OR' => array(
                'is_deleted is NUll',
                'is_deleted <>' => 1
            ),
            'AND' => array(
                'photo_id' => $photo_id
            )
        );
        $photo_list = $this->PhotoList->find('all', array(
            'conditions' => $conditions,
            'order' => array('sort' => 'asc'),
            'recursive' => -1
        ));
        $this->set('photo_list', $photo_list);
        $this->layout = 'ajax';
    }

    public function upload_image() {
        $image = $this->request->data['PhotoList']['file_image'];
        echo $this->FileUpload->upload_image($image, 'photo_gallery_lists');
    }

    public function save_all() {
        $data = $this->request->query('data');
        $photo_id = $this->request->query('photo_id');
        foreach ($data as $key => $value) {
            foreach ($value as $k => $val) {
                $photo_list = array('PhotoList' => array(
                        'photo_id' => $photo_id,
                        'image' => $val['image'],
                        'title' => $val['title'],
                        'content' => $val['content'],
                        'price' => $val['price']
                ));
                if ($val['id'] != '') {
                    //UPDATE
                    $this->PhotoList->id = $val['id'];
                    if (!$this->PhotoList->save($photo_list)) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Photo could not save'
                        ));
                    }
                } else {
                    //ADD NEW
                    $this->PhotoList->create();
                    if (!$this->PhotoList->save($photo_list)) {
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

    public function delete() {

        $id = $this->request->query('photo_id');
        $del_physical = $this->request->query('del_physical');
        $this->PhotoList->id = $id;
        if (!$this->PhotoList->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }
        if ($del_physical == 1) {
            //DELETE IMAGE
            $photo_list = $this->PhotoList->findById($id);
            $image = $photo_list['PhotoList']['image'];
            if (!empty($image) && file_exists(WWW_ROOT . 'uploads/photo_gallery_lists/' . $image)) {
                unlink(WWW_ROOT . 'uploads/photo_gallery_lists/' . $image);
            }
            //DELETE DATA
            $this->PhotoList->delete($id);
        } else {
            $this->PhotoList->saveField('is_deleted', 1);
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Delete successful'
        ));
    }

    public function publish() {
        $published = $this->request->query('published');
        $id = $this->request->query('photo_id');
        $this->PhotoList->id = $id;
        if (!$this->PhotoList->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID!'
            ));
        }
        $published == 'true' ? $published = '1' : $published = '0';
        if ($this->PhotoList->saveField('published', $published)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Photo has been saved!'
            ));
        }
    }

    public function save_sort() {
        $data = $this->request->query('data');
        $sort = 1;
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $photo = array('PhotoList' => array(
                        'sort' => $sort
                ));
                if (($val['id'] != '')) {
                    //UPDATE
                    $this->PhotoList->id = $val['id'];
                    if (!$this->PhotoList->save($photo)) {
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
