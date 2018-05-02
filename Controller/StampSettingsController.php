<?php

App::uses('AppController', 'Controller');

class StampSettingsController extends AppController
{
    public $helpers = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash',
    );

    public $components = array(
        'Paginator',
        'Flash',
        'RequestHandler',
    );

    public $fileType = array(
        'gif',
        'jpeg',
        'png',
        'jpg',
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
        $this->loadModel('Stamp');
        $this->loadModel('UserShop');
        $this->loadModel('Shop');
    }

    public function index()
    {
        $this->loadModel('User');
        $this->loadModel('Group');

        $groups = $this->Group->find('all', array(
            'conditions' => array(
                'Group.user_id' => $this->Auth->user('id') ? $this->Auth->user('id') : '',
            ),
            'recursive' => -1,
        ));

        $this->set('groups', $groups);
    }

    //Upload image to folder and return name
    public function upload_image()
    {
        $image = $this->request->data['Stamp']['file_image'];
        echo $this->FileUpload->upload_image($image, 'stamps');
    }

    public function delete_image()
    {
        $image_name = $this->request->query('image_name');
        $id         = $this->request->query('image_id');
        //Delete photo
        if (!empty($image_name)) {
            unlink(WWW_ROOT.'uploads/stamps/'.$image_name);
        }
        //Delete record
        $this->StampSetting->id = $id;
        $this->StampSetting->saveField('benefit_image_sentence', '');
    }

    public function create()
    {
        $this->loadModel('User');
        if ($this->request->is('ajax')) {
            
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'save';
                    echo $this->save_update_stamp();
                    return false;
                case 'delete';
                    echo $this->delete_image();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_update_stamp();
                return false;
            }
        }

        $shop_id = $this->request->query('shop_id');
        $user_id_list = array();
        $id = '';

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
            $id = $shop_id;
        } else {
            if (count($user_id_list) > 0) {
                reset($shops);
                $shop_id = key($shops);
                $id = $shop_id;
            }
        }

        $stamp = $this->StampSetting->find('first',
            array(
            'conditions' => array(
                'StampSetting.shop_id' => $shop_id
            ),
            'recursive' => -1
        ));

        $this->set(compact('stamp', 'shops', 'id'));
    }

    //Function stamp have only 1
    public function save_update_stamp()
    {
        $this->loadModel('StampSetting');
        $this->loadModel('Stamp');
        $this->loadModel('Shop');

        if ($this->request->is('get')) {
            $dt = new DateTime();
            $valid_date = $this->request->query('valid_date');
            $expire_day = date('Y-m-d', strtotime( $dt->format('Y/m/d').'+'.$valid_date.' days'));

            $stamp_setting = array('StampSetting' => array(
                'id' => $this->request->query['data']['StampSetting']['id'],
                'shop_id' => $this->request->query['data']['StampSetting']['shop_id'],
                'stamp_title' => $this->request->query('title'),
                'stamp_number' => $this->request->query('stamp_number'),
                'app_installation' => $this->request->query('app_installation'),
                'app_launch' => $this->request->query('app_launch'),
                'app_checkin' => $this->request->query('app_checkin'),
                'benefit_image_sentence' => $this->request->query('benefit_image_sentence'),
                'benefit_detail' => $this->request->query('benefit_detail'),
                'valid_date' => $valid_date,
                'expire_day' => date(''.$expire_day.' H:i')
            ));

            if ($this->StampSetting->save($stamp_setting)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Data has been saved'
                ));
            }
        }
    }

    public function update_all_shop()
    {
        if ($this->request->is('ajax')) {
            $this->layout = null;
            $this->autoRender = false;

            if ($this->request->data) {
                $this->loadModel('ShopGroup');
                $shop_groups = array();
                $shop_id_list = array();

                $request = $this->request->data['stamp_settings'];
                $group_id = $request['group_id'];

                $data['stamp_number'] = $request['stamp_number'];
                $data['app_installation'] = $request['app_installation'];
                $data['app_launch'] = $request['app_launch'];
                $data['app_checkin'] = $request['app_checkin'];
                $data['benefit_image_sentence'] = "'" . $request['benefit_image_sentence'] . "'" ;
                $data['benefit_detail'] = "'" . $request['benefit_detail'] . "'";
                $data['valid_date'] = $request['valid_date'];

                $shop_groups = $this->ShopGroup->find('list', array(
                    'conditions' => array(
                        'ShopGroup.group_id' => $group_id,
                    ),
                    'fields' => array('ShopGroup.shop_id'),
                ));
                if ($shop_groups) {
                    foreach ($shop_groups as $key => $value) {
                        $shop_id_list[$value] = $value;
                    }
                }

                $this->StampSetting->updateAll($data, array(
                    'StampSetting.shop_id' => $shop_id_list
                ));
            }
        }
    }
}