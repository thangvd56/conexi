<?php
/*
 * Controller Neww
 * Created 13/ November/2015
 * Channeth
 */

class SettingsController extends AppController
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
        'jpg',
        'mp4',
        '3pg',
        'mp3',
        'pdf');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(
            'logout', 'index'
        );
        $this->Auth->authorize = 'Controller';
    }
    public function index()
    {
        $this->loadModel('Shop');
        $this->loadModel('User');

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
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'save';
                    echo $this->save();
                    return false;
                case 'order';
                    echo $this->save_order();
                    return false;
            }
        }

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
        $setting = array();
        if ($shop) {
            $shop_id =$shop['Shop']['id'];
            $setting = $this->Setting->find('all',
                array(
                'conditions' => array('shop_id' => $shop_id ),
                'order' => array('Setting.function_index' => 'asc')
            ));
        }

        $this->set('shops', $shops);
        $this->set('setting', $setting);
        $this->set('id', 0);
    }

    public function reOrderSettings()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->disableCache();

            if (!empty($this->request->data['Settings']['function_index'])) {
                for ($i = 0; $i < count($this->request->data['Settings']['function_index']); $i++) {
                    $this->Setting->id = $this->request->data['Settings']['id'][$i];
                    $this->Setting->saveField('function_index', $this->request->data['Settings']['function_index'][$i], false);
                }
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Save order success!'
                ));
            }
        }
    }

        //Function setting save
    public function save()
    {
        $setting_id = $this->request->query('id');
        $status = $this->request->query('status');
        $status == 'true' ? $is_off= '1' : $is_off= '0';
        $this->Setting->id = $setting_id;
        if ($setting_id) {
            $this->Setting->saveField('active', $is_off);
        } 
    }
    //Function order image save
    public function save_order()
    {
        $id = $this->request->query('id');
        $index = $this->request->query('index');
        $this->Setting->id = $id;
        $data = array(
            'Setting' => array(
                'function_index' => $index
        ));
        if ($this->Setting->save($data)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Save order success!'
            ));
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Save order error!'
            ));
        }
    }

    public function changeFunctionName() {
        if (!$this->request->is('ajax')) {
            throw NotFoundException();
        }
        $this->autoRender = false;
        $this->Setting->id = $this->request->data('id');
        if (!$this->Setting->saveField('function_name', $this->request->data('name'))) {
            echo json_encode(array('success' => 0, 'message' => ''));
        }
        echo json_encode(array('success' => 1, 'message' => ''));
    }
}