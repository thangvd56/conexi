<?php

App::uses('AppController', 'Controller');

class ShopsController extends AppController {

    public $helpers = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash');
    public $components = array(
        'Paginator',
        'Flash',
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
            if (!$action || $action === '') {
                $action = $this->request->data('action');
            }
            if ($action == 'create') {
                echo $this->create();
            } else if ($action == 'delete') {
                echo $this->delete();
            } else if ($action == 'published') {
                echo $this->published();
            } else if ($action == 'edit') {
                echo $this->edit();
            } else if ($action == 'detail') {
                echo $this->fetch_shop_info();
            }
        }
    }

    public function fetch_shop_list() {
        $shops_data = $this->Shop->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'is_deleted <>' => 1,
                    'is_deleted ' => null,
                )
            )
        ));
        $this->set('shops_data', $shops_data);
        $this->layout = 'ajax';
    }

    public function create() {
        $shop_name = $this->request->query('name');
        $conditions = array('name' => $shop_name);
        $shops = $this->Shop->find('count', array('conditions' => $conditions));
        if (trim($shop_name) == '') {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Shop name is required!'
            ));
        }
        if ($shops > 0) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Shop name is already exists!'));
        }
        $this->Shop->create();
        $data = array(
            'name' => $shop_name,
            'user_id' => $this->Auth->user('id')
        );
        if ($this->Shop->save($data)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Shop name has been saved!'));
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Shop could not save!'));
        }
    }

    public function published() {
        $shop_id = $this->request->query('shop_id');
        $published = $this->request->query('published');
        $this->Shop->id = $shop_id;
        if (!$this->Shop->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID!'
            ));
        }
        if ($published == '') {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Please select publish or private!'
            ));
        }
        $this->Shop->saveField('published', $published);
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Shop has been saved!'
        ));
    }

    public function delete() {
        $shop_id = $this->request->query('shop_id');
        $del_physical = $this->request->query('del_physical');
        $this->Shop->id = $shop_id;
        if (trim($del_physical) == '') {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Please select deleting role!'
            ));
        }
        if ($del_physical == 1) {
            $this->Shop->id = $shop_id;
            //DELETE IMAGE
            $shop = $this->Shop->findById($shop_id);
            if (file_exists(WWW_ROOT . 'uploads/' . $shop['AppInformation'][0]['image'])) {
                $image = $shop['AppInformation'][0]['image'];
                unlink(WWW_ROOT . 'uploads/' . $image);
            }
            //DELETE DATA
            if (!$this->Shop->exists()) {
                throw new NotFoundException(__('Invalid Shop ID'));
            }
            if ($this->Shop->delete($shop_id)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Shop has been deleted'
                ));
            } else {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Shop could not delete'
                ));
            }
        }
        if ($del_physical == 0) {
            $this->Shop->id = $shop_id;
            if (!$this->Shop->exists()) {
                throw new NotFoundException(__('Invalid Shop ID'));
            }
            if ($this->Shop->saveField('is_deleted', 1)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Shop has been deleted'
                ));
            } else {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Shop could not delete'
                ));
            }
        }
    }

    public function edit() {
        $image = $this->request->data['Shop']['image'];
        $image_name = $image['name'];
        $shop_id = $this->request->data['shop_id'];
        $introduction = $this->request->data['introduction'];
        $shop_name = $this->request->data['shop_name'];
        $shop_kana = $this->request->data['shop_kana'];
        $address = $this->request->data['address'];
        $business_hours_start = $this->request->data['business_hours_start'];
        $business_hours_end = $this->request->data['business_hours_end'];
        $holidays = $this->request->data['holidays'];
        $phone = $this->request->data['phone'];
        $fax = $this->request->data['fax'];
        $url = $this->request->data['url'];
        $email = $this->request->data['email'];
        $facebook = $this->request->data['facebook'];
        $twitter = $this->request->data['twitter'];
        $ios_download_link = $this->request->data['ios_download_link'];
        $android_download_link = $this->request->data['android_download_link'];

        $uploadPath = WWW_ROOT . 'uploads';
        $image_ext = explode('.', $image['name']);
        if (!file_exists(WWW_ROOT . 'uploads')) {
            mkdir(WWW_ROOT . 'uploads', 0777, true);
        }
        if (file_exists($uploadPath . '/' . $image_name)) {
            $image_name = date('His') . $image_name;
        }
        if ($image['name'] == '') {
            $image_name = '';
            $data_update = array(
                'user_id' => $this->Auth->user('id'),
                'introduction' => $introduction,
                'shop_name' => $shop_name,
                'shop_kana' => $shop_kana,
                'address' => $address,
                'business_hours_start' => $business_hours_start,
                'business_hours_start_type' => substr($business_hours_start, -2, 2),
                'business_hours_end' => $business_hours_end,
                'business_hours_end_type' => substr($business_hours_end, -2, 2),
                'holidays' => $holidays,
                'phone' => $phone,
                'fax' => $fax,
                'url' => $url,
                'email' => $email,
                'facebook' => $facebook,
                'twitter' => $twitter,
                'ios_download_link' => $ios_download_link,
                'android_download_link' => $android_download_link
            );
        } else {
            $data_update = array(
                'user_id' => $this->Auth->user('id'),
                'image' => $image_name,
                'introduction' => $introduction,
                'shop_name' => $shop_name,
                'shop_kana' => $shop_kana,
                'address' => $address,
                'business_hours_start' => $business_hours_start,
                'business_hours_start_type' => substr($business_hours_start, -2, 2),
                'business_hours_end' => $business_hours_end,
                'business_hours_end_type' => substr($business_hours_end, -2, 2),
                'holidays' => $holidays,
                'phone' => $phone,
                'fax' => $fax,
                'url' => $url,
                'email' => $email,
                'facebook' => $facebook,
                'twitter' => $twitter,
                'ios_download_link' => $ios_download_link,
                'android_download_link' => $android_download_link
            );
        }
        $data = array(
            'shop_id' => $shop_id,
            'user_id' => $this->Auth->user('id'),
            'image' => $image_name,
            'introduction' => $introduction,
            'shop_name' => $shop_name,
            'shop_kana' => $shop_kana,
            'address' => $address,
            'business_hours_start' => $business_hours_start,
            'business_hours_start_type' => substr($business_hours_start, -2, 2),
            'business_hours_end' => $business_hours_end,
            'business_hours_end_type' => substr($business_hours_end, -2, 2),
            'holidays' => $holidays,
            'phone' => $phone,
            'fax' => $fax,
            'url' => $url,
            'email' => $email,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'ios_download_link' => $ios_download_link,
            'android_download_link' => $android_download_link
        );

        $this->loadModel('AppInformation');
        $conditions = array('shop_id' => $shop_id);
        $app_info_count = $this->AppInformation->find('count', array('conditions' => $conditions, 'recursive' => -1));
        $app_info_data = $this->AppInformation->find('all', array('conditions' => $conditions, 'recursive' => -1));
        if ($app_info_count > 0) {
            if (!empty($image['name'])) {
                //CHECK OLD IMAGE
                foreach ($app_info_data as $key => $value) {
                    $old_image = $value['AppInformation']['image'];
                }
                if ((in_array(strtolower($image_ext[1]), $this->fileType))) {
                    //DELETE OLD IMAGE IF HAVE
                    if ((isset($old_image) && file_exists(WWW_ROOT . 'uploads/' . $old_image)) && !empty($old_image)) {
                        unlink(WWW_ROOT . 'uploads/' . $old_image);
                    }
                    //UPLOAD NEW IMAGE
                    if (!(move_uploaded_file($image['tmp_name'], WWW_ROOT . 'uploads/' . $image_name))) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Could not upload image!'
                        ));
                    }
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'File type not allow!'
                    ));
                }
            }
            //UPDATE DATA
            $this->AppInformation->id = $this->AppInformation->field('id', $conditions);
            if ($this->AppInformation->save(array('AppInformation' => $data_update))) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Shop information has been update!'
                ));
            } else {
                return json_encode(array(
                    'result' => 'error',
                    ' msg' => 'Shop information could not update'
                ));
            }
        } else {
            //ADD NEW PHOTO
            if (!empty($image['name'])) {
                if ((in_array(strtolower($image_ext[1]), $this->fileType))) {
                    if (!(move_uploaded_file($image['tmp_name'], WWW_ROOT . 'uploads/' . $image_name))) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Could not upload image!'
                        ));
                    }
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'File type not allow!'
                    ));
                }
            }
            //SAVE NEW DATA
            if ($this->AppInformation->save($data)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Shop information has been saved!'));
            } else {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Shop information could not save!'
                ));
            }
        }
    }

    public function fetch_shop_info() {
        $shop_id = $this->request->query('shop_id');
        $this->Shop->id = $shop_id;
        if (!$this->Shop->exists()) {
            throw new NotFoundException(__('Invalid shop ID'));
        }
        $shop_data = $this->Shop->findById($shop_id);
        if ($shop_data) {
            return json_encode(array(
                'result' => 'success',
                'data' => $shop_data
            ));
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Could not retrieve data'
            ));
        }
    }

    //Head Quater this function is stopped using for temporary
//    public function shopList() {
//        if ($this->Auth->user('role') !== ROLE_HEADQUARTER) {
//            throw new NotFoundException();
//        }
//        $this->loadModel('User');
//        $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
//        $data = array();
//        if ($user_id_list) {
//            $conditions = ['user_id' => $user_id_list];
//            if ($this->request->query('keyword')) {
//                $conditions['shop_name LIKE'] = '%'. $this->request->query('keyword') . '%';
//            }
//            $this->Paginator->settings = [
//                'conditions' => $conditions,
//                'limit' => PAGE_LIMIT,
//                'order' => 'shop_name',
//                'recursive' => -1
//            ];
//            $data = $this->Paginator->paginate('Shop');
//        }
//        $this->set(compact('data'));
//    }

    public function shopsNoGroup() {
        if ($this->Auth->user('role') !== ROLE_HEADQUARTER) {
            throw new NotFoundException();
        }
        $this->layout = 'ajax';
        $this->loadModel('User');
        $this->loadModel('Group');
        $this->loadModel('ShopGroup');
        $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        $group = $this->Group->find('list', array(
            'fields' => array('Group.id'),
            'conditions' => array(
                'Group.user_id' => $this->Auth->user('id'),
                'Group.deleted <>' => 1
            )
        ));

        $shop_group = $this->ShopGroup->find('list', array(
            'fields' => 'ShopGroup.shop_id',
            'conditions' => array(
                'ShopGroup.group_id' => $group
            )
        ));

        $data = array();
        $conditions = array('Shop.user_id' => $user_id_list);
        $shopId = $this->request->query('shop_id');
        $id = array();
        if (!empty($shopId)) {
            $id = explode(',', $shopId);
            $sId = array_unique(array_merge($id, $shop_group));
            array_push($conditions, array('NOT' => array('Shop.id' => $sId)));
        } else {
            array_push($conditions, array('NOT' => array('Shop.id' => $shop_group)));
        }
        if ($user_id_list) {
            $data = $this->Shop->find('all', array(
                'conditions' => $conditions,
//                array(
//                    'Shop.user_id' => $user_id_list,
//                    'Shop.id NOT' => $shop_group
//                ),
                'order' => 'shop_name',
                'recursive' => -1
            ));
        }
        $this->set(compact('data'));
    }

    public function web_reservation()
    {
        if (($this->Auth->user('role') !== ROLE_HEADQUARTER) && ($this->Auth->user('role') !== ROLE_SHOP)) {
            throw new NotFoundException();
        }

        $this->loadModel('User');
        $this->loadModel('Shop');
        $user_id = array();
        $reservation_url = '';

        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id = $this->User->getListIdbyParrentId($this->Auth->user('id'));
            $shops = $this->Shop->find('list', array(
                'fields' => array('Shop.id', 'Shop.shop_name'),
                'conditions' => array(
                    'is_deleted <>' => 1,
                    'user_id' => $user_id
                ),
                'recursive' => -1,
            ));
            if (!empty($this->request->query('shop_id'))) {
                $this->Shop->recursive -1;
                $shop = $this->Shop->findById($this->request->query('shop_id'));
                if (!empty($shop)) {
                    $reservation_url = $shop['Shop']['web_reservation'];
                }
                $this->set(compact('shop'));
            } else {
                if (!empty($shops)) {
                    foreach ($shops as $key => $sh) {
                        $this->Shop->recursive -1;
                        $default_shop = $this->Shop->findById($key);
                        if (!empty($default_shop)) {
                            $reservation_url = $default_shop['Shop']['web_reservation'];
                        }
                        break;
                    }
                }
            }
            $this->set(compact('shops'));
        } else if ($this->Auth->user('role') === ROLE_SHOP) {
            $user_id = $this->Auth->user('id');
            $this->Shop->recursive -1;
            $shop = $this->Shop->findByUserId($this->Auth->user('id'));
            if (!empty($shop)) {
                $reservation_url = $shop['Shop']['web_reservation'];
            }
            $this->set(compact('shop'));
        }
        $this->set(compact('reservation_url'));

        if ($this->request->is('post')) {
            $url = $this->request->data['Shop']['web_reservation'];
            if (!empty($url)) {
                filter_var($url, FILTER_SANITIZE_URL);
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $this->Shop->id = $this->request->data['Shop']['id'];
                    if ($this->Shop->saveField('web_reservation', $this->request->data['Shop']['web_reservation'], false)) {
                        $this->Session->setFlash(MESSAGE_SUCCESS, 'success');
                        return $this->redirect($this->referer());
                    }
                    return $this->Session->setFlash('Error updating reservation url. Please try again.', 'error');
                } else {
                    return $this->Session->setFlash('URLのみで入力してください。', 'error');
                }
            }
        }
    }

    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException();
        }
        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
        }
        $shop = $this->Shop->findById($id);
        $this->set(compact('shop'));
    }
}
