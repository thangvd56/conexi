<?php
App::uses('AppController', 'Controller');

class MenuCategoriesController extends AppController
{
    public $helpers    = array(
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
    public $fileType   = array(
        'gif',
        'jpeg',
        'png',
        'jpg');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
        $this->loadModel('UserShop');
    }

    public function index()
    {
        $this->loadModel('User');
        $this->loadModel('Shop');
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            if ($this->request->is('post')) {
                echo $this->upload_image();
            }
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete';
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
        $shop = array();
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
            }
        }

        if ($shop) {
            $conditions = array(
                'is_deleted <>' => 1,
                'shop_id' => $shop['Shop']['id']
            );

            $menu_categories = $this->MenuCategory->find('all',
                array(
                'conditions' => $conditions,
                'recursive' => -1));

            $display_in_list = 0;
            if ($menu_categories) {
                $display_in_list = $menu_categories[0]['MenuCategory']['is_display_list'];
            }

            if ($this->Auth->user('role') !== ROLE_HEADQUARTER) {
                $this->set('shop_id', $shop['Shop']['id']);
            }
            $this->set('shops', $shops);
            $this->set('menu_categories', $menu_categories);
            $this->set('display_in_list', $display_in_list);
        }
    }

    public function fetch_menu_category_list()
    {
        $this->layout = 'ajax';
        $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
        $shop_id= $shops[0]['shops']['id'];

        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $this->request->query('shop_id')) {
            $shop_id = $this->request->query('shop_id');
        }

        $conditions = array(
            'is_deleted <>' => 1,
            'shop_id'=>$shop_id
        );
        $menu_categories = $this->MenuCategory->find('all', array(
            'conditions' => $conditions,
            'order' => array('sort' => 'asc'),
            'recursive' => -1
        ));

        $this->set('menu_categories', $menu_categories);
    }

    public function upload_image()
    {
        $image = $this->request->data['MenuCategory']['file_image'];
        echo $this->FileUpload->upload_image($image, 'app_menus');
    }

    public function save_all()
    {
        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $this->request->query('shop_id')) {
            $shop_id = $this->request->query('shop_id');
        } else {
            $shops   = $this->UserShop->get_usershop_id($this->Auth->user('id'));
            $shop_id= $shops[0]['shops']['id']; //User now have only one shop
        }
        $data = $this->request->query('data');
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $menu_category = array('MenuCategory' => array(
                        'shop_id'=>$shop_id,
                        'id' => $val['id'],
                        'image' => $val['image'],
                        'title' => $val['title'],
                ));
                if (($val['id'] != '')) {
                    //UPDATE
                    $this->MenuCategory->id = $val['id'];
                    if (!$this->MenuCategory->save($menu_category)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot update field'
                        ));
                    }
                } else {
                    //ADD NEW
                    $this->MenuCategory->create();
                    if (!$this->MenuCategory->save($menu_category)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot add new field'
                        ));
                    }
                }
            }
        }
        return json_encode(array(
            'result' => 'Success',
            'msg' => 'Data has been saved'
        ));
    }

    public function save_order()
    {
        $data = $this->request->query('data');
        $sort = 1;
        foreach ($data as $key => $value) {
            foreach ($value as $key => $val) {
                $menu_category = array('MenuCategory' => array(
                        'sort' => $sort
                ));
                if (($val['id'] != '')) {
                    //UPDATE
                    $this->MenuCategory->id = $val['id'];
                    if (!$this->MenuCategory->save($menu_category)) {
                        return json_encode(array(
                            'result' => 'Error',
                            'msg' => 'Cannot update field'
                        ));
                    }
                } else {
                    //ADD NEW
                    $this->MenuCategory->create();
                    if (!$this->MenuCategory->save($menu_category)) {
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
        $id                     = $this->request->query('menu_cate_id');
        $del_physical           = $this->request->query('del_physical');
        $this->MenuCategory->id = $id;
        if (!$this->MenuCategory->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }
        if ($del_physical == 1) {
            //DELETE IMAGE
            $MenuCategory = $this->MenuCategory->findById($id);
            $image        = $MenuCategory['MenuCategory']['image'];
            if (!empty($image)) {
                unlink(WWW_ROOT.'uploads/app_menus/'.$image);
            }
            //DELETE DATA
            $this->MenuCategory->delete($id);
        } else {
            $this->MenuCategory->saveField('is_deleted', 1);
        }
    }

    public function publish()
    {
        $published              = $this->request->query('published');
        $id                     = $this->request->query('menu_cate_id');
        $this->MenuCategory->id = $id;
        if (!$this->MenuCategory->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID!'
            ));
        }
        $published == 'true' ? $published = '1' : $published = '0';
        if ($this->MenuCategory->saveField('published', $published)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Menu Category has been saved!'
            ));
        }
    }

    public function displayOption()
    {
        if (!$this->request->is('ajax')) {
            return new notFoundExaption();
        }
        $this->loadModel('Shop');
        $this->autoRender = false;
        if ($this->request->data('shop_id')) {
            $shop_id = $this->request->data('shop_id');
        } else {
            $shop = $this->Shop->findByUserId($this->Auth->user('id'));
            $shop_id= $shop['Shop']['id'];
        }

        $menus = $this->MenuCategory->find('all', array(
            'conditions' => array(
                'MenuCategory.shop_id' => $shop_id
            ),
            'recursive' => -1
        ));

        foreach ($menus as $key => $value) {
            $this->MenuCategory->id = $value['MenuCategory']['id'];
            //$value['MenuCategory']['is_display_list'] = $this->request->data('is_display_list');
            $this->MenuCategory->saveField('is_display_list', $this->request->data('is_display_list'));
        }

        echo json_encode(array(
            'result' => 'success',
            'message' => MESSAGE_UPDATE
        ));
    }
}