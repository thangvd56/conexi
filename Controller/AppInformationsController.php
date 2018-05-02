<?php

App::uses('AppController', 'Controller');

class AppInformationsController extends AppController
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
            'logout', 'news_index'
        );
        $this->Auth->authorize = 'Controller';
    }

    //Default loading app info
    public function index()
    {
        $this->loadModel('Shop');
        $this->loadModel('Media');
        $this->loadModel('User');

        $user_id = $this->Auth->user('id');
        $shop_id = $this->request->query('shop_id');
        $shop_data = $this->Shop->findByUserId($user_id);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete();
                    return false;
                case 'save';
                    echo $this->save_all();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_all();
                return false;
            }
        }

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

        $options['recursive'] = -1;
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            reset($shops);
            $options['conditions'] = array(
                'Shop.id' => $shop_id ? $shop_id : key($shops),
            );
        } else {
            $options['conditions'] = array(
                'Shop.id' => $shop_data ? $shop_data['Shop']['id'] : 0,
            );
        }
        $shop = $this->Shop->find('first', $options);

        //App Information get media
        $media = $this->Media->find('all', [
            'conditions' => [
                'model' => 'shops',
                'is_deleted <>' => [1, 'is null'],
                'external_id' => $shop ? $shop['Shop']['id'] : '',
            ],
            'order' => 'file_order asc'
        ]);

        $this->set(compact('shops', 'shop', 'media'));
    }

    //Upload image to folder and return name
    public function upload_image()
    {
        if (isset($this->request->data['App_Info'])) {
           $image = $this->request->data['App_Info']['file_image'];
        }
        if (isset($_FILES['splash_image'])) {
            $image =$_FILES['splash_image'];
        }
        if(!empty($image)){
            echo $this->FileUpload->upload_image($image, 'photo_informations');
        }
        
    }

    //Save information to db
    public function save_all()
    {
        $this->loadModel('Shop');
        $this->loadModel('Media');

        if ($this->request->is(array('get', 'post'))) {
            $h_start_min = $this->request->query('hours_start_min');
            $h_end_min = $this->request->query('hours_end_min');
            if ($this->request->query('hours_start_min') == '0') {
                $h_start_min = '00';
            }
            if ($this->request->query('hours_end_min') == '0') {
                $h_end_min = '00';
            }
            $hours_start = $this->request->query('hours_start').':'.$h_end_min;
            $hours_end = $this->request->query('hours_end').':'.$h_end_min;
            $openning_hours = $this->request->query('openning_hours');
            if ($this->request->query('hours_start') == '00' && 
                $this->request->query('hours_end') == '00' &&
                $this->request->query('hours_start_min') == '0' &&
                $this->request->query('hours_end_min') == '0') {
                $hours_start = '00:00';
                $hours_end = '00:00';
            }

            $data_app_info = array(
                'Shop' => array(
                    'introduction' => $this->request->query('introduction'),
                    'shop_name' => $this->request->query('shop_name'),
                    'shop_kana' => $this->request->query('shop_name_kana'),
                    'address' => $this->request->query('shop_address'),
                    'hours_start' => $hours_start,
                    'hours_end' => $hours_end,
                    'openning_hours' => $openning_hours,
                    'holidays' => $this->request->query('holidays'),
                    'phone' => $this->request->query('phone'),
                    'fax' => $this->request->query('fax'),
                    'url' => $this->request->query('url'),
                    'email' => $this->request->query('email'),
                    'facebook' => $this->request->query('facebook'),
                    'twitter' => $this->request->query('twitter'),
                    'latitute' => $this->request->query('latitute'),
                    'longtitute' => $this->request->query('longtitute'),
                    'splash_image' => $this->request->query('splash_hidden_name'),
                    'id' => $this->request->query('shop_id'),
                )
            );

            $last_id = '';
            if ($this->Shop->save($data_app_info)) {
                $last_id = $this->Shop->id;
            }
            if ($last_id) {
                $this->Media->deleteAll(array(
                    'Media.user_id' => $this->Auth->user('id'),
                    'Media.model' => 'shops',
                    'Media.external_id' => $last_id,
                ));
                $sort = $this->request->query('sort');
                if ($this->request->query('exist_img')) {
                    $old_img = $this->request->query('exist_img');
                    for ($j = 1; $j <= count($sort); $j ++) {
                        for ($jj = 1; $jj <= count($old_img); $jj ++) {
                            if ($sort[$j] == $old_img[$jj]) {
                                $old_pmg = array('Media' => array(
                                    'user_id' => $this->Auth->user('id'),
                                    'external_id' => $last_id,
                                    'model' => 'shops',
                                    'file_order' => $j,
                                    'file' => $old_img[$jj],
                                ));
                                $this->Media->create();
                                $this->Media->save($old_pmg);
                            }
                        }
                    }
                }
                $item = $this->request->query('item');
                $image = $this->request->query('image');
                $new = $this->request->query('new_img');
                if ($item > 0) {
                    for ($i = 1; $i <= count($sort); $i++) {
                        for ($ii = 0; $ii < $item; $ii++) {
                            if ($sort[$i] == $new[$ii]) {
                                $data_photo = array('Media' => array(
                                        'user_id' => $this->Auth->user('id'),
                                        'external_id' => $last_id,
                                        'model' => 'shops',
                                        'file_order' => $i,
                                        'file' => $image[$ii]
                                ));
                                $this->Media->create();
                                $this->Media->save($data_photo);
                            }
                        }
                    }
                }
            }
        }
    }

    //Delete Photo from db
    public function delete()
    {
        $image_name = $this->request->query('image_name');
        $id = $this->request->query('image_id');
        $mode = $this->request->query('mode');
        $splash_image_name = $this->request->query('splash_image_name');
        $shop_splash_id = $this->request->query('shop_splash_id');
        $this->loadModel('Media');
        $this->loadModel('Shop');
        if ($mode !='splash') {
            //Delete photo
            if (!empty($image_name)) {
                unlink(WWW_ROOT . 'uploads/photo_informations/' . $image_name);
            }
            //Delete record
            $this->Media->id = $id;
            $this->Media->delete();
        } else {
            if (!empty($splash_image_name)) {
                unlink(WWW_ROOT . 'uploads/photo_informations/' . $splash_image_name);
            }
            //Delete splash image
            $this->Shop->id = $shop_splash_id;
            $this->Shop->saveField('splash_image', '');
        }
    }
}