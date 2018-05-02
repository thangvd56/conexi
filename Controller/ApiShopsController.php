<?php
App::uses('File', 'Utility', 'User');

class ApiShopsController extends AppController
{

    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'index',
            'shop_info',
            'splash_image',
        ));
        $this->Auth->authorize = 'Controller';
        $this->autoRender = false;
    }

    public function index()
    {
        $this->loadModel('User');
        $users = $this->User->find('all');
        echo json_encode(array(
            'users' => $users
        ));
        
    }

    public function shop_info()
    {
        $this->loadModel('Shop');
        $this->loadModel('Media');
        $this->loadModel('UserShop');
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->request->query('user_id');
        $imagelink = '';
        $is_allow_notification = '0';

        $image = $this->Media->find('first', array(
            'conditions' => array(
                'external_id' => $shop_id,
                'model' => 'shops'
            )
        ));

        $user_shop = $this->UserShop->find('first', array(
            'conditions' => array(
                'UserShop.shop_id' => $shop_id,
                'UserShop.user_id' => $user_id,
            ),
            'recursive' => -1,
        ));

        if (!empty($user_shop)) {
            $value = ($user_shop['UserShop']['is_allow_notification'] == 'true') ? '1' : '0';
            $is_allow_notification = $value;
        }

        if ($image) {
            $imagelink = Router::url('/uploads/photo_informations/', true) .$image['Media']['file'];
        }

        $this->Shop->recursive = -1;
        $data = $this->Shop->findById($shop_id);
        if ($data) {
            echo json_encode(array(
                'shop_name' => $data['Shop']['shop_name'],
                'description' => $data['Shop']['description'],
                'introduction' => $data['Shop']['introduction'],
                'address' => $data['Shop']['address'],
                'phone' => $data['Shop']['phone'],
                'fax' => $data['Shop']['fax'],
                'email' => $data['Shop']['email'],
                'holidays' => $data['Shop']['holidays'],
                'business_hours' => $data['Shop']['openning_hours'],
                'hours_start' => $data['Shop']['hours_start'],
                'hours_end' => $data['Shop']['hours_end'],
                'web' => $data['Shop']['url'],
                'facebook' => $data['Shop']['facebook'],
                'twitter' => $data['Shop']['twitter'],
                'longtitute' => $data['Shop']['longtitute'],
                'latitute' => $data['Shop']['latitute'],
                'is_allow_notification' => $is_allow_notification,
                'image' => $imagelink,
                'success' => 1,
                'message' => 'successfully',
            ));
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Shop ID not exist',
            ));
        }
    }

    public function splash_image()
    {
        $shop_id = $this->request->query('shop_id');

        if (isset($shop_id) && !empty($shop_id)) {
            $this->loadModel('Shop');

            $image = $this->Shop->find('first', array(
                'fields' => array('*'),
                'conditions' => array(
                    'Shop.id' => $shop_id,
                )
            ));
            if ($image) {
                $img = explode('.', $image['Shop']['splash_image']);
                $img_ex = end($img);
                $str = str_replace($img_ex, '', $image['Shop']['splash_image']);
                $name = trim($str, '.');

                echo json_encode(array(
                    'splash_image_url' => Router::url('/uploads/photo_informations/', true) . $name . '-fit-300x300.' . $img_ex,
                    'success' => 1,
                    'message' => 'successfully',
                ));
            } else {
                echo json_encode(array(
                    'splash_image_url' => 'null',
                    'success' => 0,
                    'message' => 'Shop ID not exist',
                ));
            }
        }
    }
}