<?php
App::uses('AppController', 'Controller');

class ApplicationMenuListsController extends AppController
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
        'FileUpload',
        'RequestHandler');
    public $fileType = array(
        'gif',
        'jpeg',
        'png',
        'jpg');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                $action = $this->request->query('action');
                switch ($action) {
                    case 'publish':
                        echo $this->publish();
                        return false;
                    case 'delete':
                        echo $this->delete();
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

        $menu_id = $this->request->query('menu_id');
        if (empty($menu_id)) {
            $this->redirect(array(
                'controller' => 'menu_categories',
                'action' => 'index'
            ));
        }
    }

    public function fetch_app_menu_list()
    {
        $this->loadModel('MenuCategory');
        $menu_id = $this->request->query('menu_id');
        $conditions = array(
            'is_deleted <>' => 1,
            'menu_category_id' => $menu_id
        );
        $app_menu_list = $this->ApplicationMenuList->find('all', array(
            'conditions' => $conditions,
            'order' => array('sort' => 'asc'),
            'recursive' => -1
        ));
        $menu_category = $this->MenuCategory->findById($menu_id);

        if ($menu_category) {
            $this->set('is_display_list', $menu_category['MenuCategory']['is_display_list']);
        }
        $this->set('app_menu_list', $app_menu_list);
        $this->layout = 'ajax';
    }

    public function upload_image()
    {
        $image = $this->request->data['ApplicationMenuList']['file_image'];
        echo $this->FileUpload->upload_image($image, 'app_menu_lists');
    }

    public function save_all()
    {
        $data = $this->request->query('data');
        $menu_cate_id = $this->request->query('menu_id');
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $app_menu_list = array('ApplicationMenuList' => array(
                    'id' => $val['id'],
                    'image' => $val['image'],
                    'title' => $val['title'],
                    'content' => $val['content'],
                    'price' => $val['price'],
                    'menu_category_id' => $menu_cate_id
                ));

                if (empty($app_menu_list['ApplicationMenuList']['content'])) {
                    unset($app_menu_list['ApplicationMenuList']['content']);
                }

                if (($val['id'] != '')) {
                    //UPDATE
                    $this->ApplicationMenuList->id = $val['id'];
                    if (!$this->ApplicationMenuList->save($app_menu_list)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot update field'
                        ));
                    }
                } else {
                    //ADD NEW
                    $this->ApplicationMenuList->create();
                    if (!$this->ApplicationMenuList->save($app_menu_list)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot add new field',
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

    public function save_order()
    {
        $data = $this->request->query('data');
        $sort = 1;
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $menu_app_list = array('ApplicationMenuList' => array(
                        'sort' => $sort
                ));
                if (($val['id'] != '')) {
                    //UPDATE
                    $this->ApplicationMenuList->id = $val['id'];
                    if (!$this->ApplicationMenuList->save($menu_app_list)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot update field'
                        ));
                    }
                } else {
                    //ADD NEW
                    $this->ApplicationMenuList->create();
                    if (!$this->ApplicationMenuList->save($menu_app_list)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot add new field'
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

    public function delete()
    {
        $id = $this->request->query('app_menu_id');
        $del_physical = $this->request->query('del_physical');
        $this->ApplicationMenuList->id = $id;
        if (!$this->ApplicationMenuList->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }

        if ($del_physical == 1) {
            //DELETE IMAGE
            $app_menu_list = $this->ApplicationMenuList->findById($id);
            $image = $app_menu_list['ApplicationMenuList']['image'];
            if (!empty($image) or file_exists(WWW_ROOT . 'uploads/app_menu_lists/' . $image)) {
                unlink(WWW_ROOT . 'uploads/app_menu_lists/' . $image);
            }
            //DELETE DATA
            $this->ApplicationMenuList->delete($id);
        } else {
            $this->ApplicationMenuList->saveField('is_deleted', 1);
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Delete successful'
        ));
    }

    public function publish()
    {
        $published = $this->request->query('published');
        $id = $this->request->query('app_menu_id');
        $this->ApplicationMenuList->id = $id;
        if (!$this->ApplicationMenuList->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID!'
            ));
        }
        $published == 'true' ? $published = '1' : $published = '0';
        if ($this->ApplicationMenuList->saveField('published', $published)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Application Menu List has been saved!'
            ));
        }
    }
}