<?php
App::uses('AppController', 'Controller');
class ApiAppMenuController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'index',
            'app_menu',
            'app_sub_menu'
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->autoRender = false;
    }

    public function app_menu()
    {
        $arr_menus = array();
        $this->loadModel('MenuCategory');
        try {
            $menus = $this->MenuCategory->find('all', array(
                'conditions' => array(
                    'shop_id' => $this->request->query('shop_id'),
                    'published <>' => 0,
                    'is_deleted <>' => 1
                ),
                'order' => array('sort' => 'ASC'),
                'recursive' => -1
            ));

            foreach ($menus as $value) {
                $arr = array(
                    'id' => $value['MenuCategory']['id'],
                    'image' => Router::url('/', true) . 'uploads/app_menus/' . $value['MenuCategory']['image'],
                    'title' => $value['MenuCategory']['title'],
                    'is_display_list' => $value['MenuCategory']['is_display_list'],
                );
                $arr_menus[] = $arr;
            }
            echo json_encode(array(
                'menus' => $arr_menus,
                'success' => 1,
                'message' => 'successful'
            ));

        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
        $this->autoRender = FALSE;
    }

    public function app_sub_menu()
    {
        try {
            $arr_menus = array();
            $this->loadModel('ApplicationMenuList');
            $menus = $this->ApplicationMenuList->find('all', array(
                'conditions' => array(
                    'menu_category_id' => $this->request->query('app_menu_id'),
                    'published <>' => 0,
                    'is_deleted <>' => 1
                ),
                'order' => array('sort'=>'ASC'),
                'recursive' => -1
            ));
            foreach ($menus as $value) {
                $arr = array(
                    'id' => $value['ApplicationMenuList']['id'],
                    'image' => Router::url('/', true) . 'uploads/app_menu_lists/' . $value['ApplicationMenuList']['image'],
                    'title' => $value['ApplicationMenuList']['title'],
                    'price' => $value['ApplicationMenuList']['price'],
                    'content' => $value['ApplicationMenuList']['content']
                );
                $arr_menus[] = $arr;
            }
            echo json_encode(array(
                'sub_menus' => $arr_menus,
                'success' => 1,
                'message' => 'Successful'
            ));
        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
        $this->autoRender = false;
    }
}
