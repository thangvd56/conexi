<?php

class NoticesController extends AppController
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
        $this->Auth->allow(array(
            'logout', 'index'
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->loadModel('News');
        $this->loadModel('User');
        $this->loadModel('Shop');

        $user_id = array();
        $singUserId = null;
        $shop_id = $this->request->query('shop_id');
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id = $this->User->getHeadquarterUserId($this->Auth->user('id'));
        } else if ($this->Auth->user('role') === ROLE_SHOP) {
            $user_id = $this->Auth->user('id');
            $singUserId = $this->Auth->user('id');
        } else {
            throw new NotFoundException('User Id not found.');
        }

        $shop = $this->Shop->getShopIdByUser($user_id);
        if (empty($shop_id)) {
            if (!empty($shop)) {
                $shop_id = key($shop);
            }
            if (empty($singUserId)) {
                $this->Shop->recursive = -1;
                $selected_shop = $this->Shop->findById($shop_id);
                $singUserId = $selected_shop['Shop']['user_id'];
            }
        } else {
            //find user_id of the selected shop owner
            $this->Shop->recursive = -1;
            $selected_shop = $this->Shop->findById($shop_id);
            $singUserId = $selected_shop['Shop']['user_id'];
        }

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete();
                    return false;
            }
        }

        $past_notices = $this->News->find('all', array(
            'joins' => array(
                array(
                    'table' => 'news_deliveries',
                    'alias' => 'NewsDelivery',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'NewsDelivery.news_id = News.id'
                    )
                )
            ),
            'conditions' => array(
                'News.user_id' => $singUserId,
                'News.type' => NOTICE_TYPE_SETTING,
                'NewsDelivery.is_deleted <>' => 1
            ),
            'order' => array('News.modified' => 'DESC'),
            'recursive' => -1,
            'group' => array('NewsDelivery.news_id')
        ));

        $sent_id = array();
        if (!empty($past_notices)) { //currently there's no way to know whether the News is sent or not just do this.
            $sent_id = Hash::extract($past_notices, '{n}.News.id');
        }
        
        $conditions_news = array(
            'News.is_disabled <>' => 1,
            'OR' => array('News.is_deleted' => null, 'News.is_deleted <>' => 1),
            'News.user_id' => $singUserId,
            'News.type' => NOTICE_TYPE_SETTING
        );
        if (!empty($sent_id)) {
            $conditions_news['NOT'] = array('News.id' => $sent_id);
        }
        
        $news = $this->News->find('all', array(
            'conditions' => $conditions_news,
            'order' => array('DATE(News.delivery_date_value)' => 'DESC'),
            'recursive' => -1,
        ));

        $this->set('shop', $shop);
        $this->set('shop_id', $shop_id);
        $this->set('new', $news);
        $this->set('past_notice', $past_notices);
    }

//    public function index()
//    {
//        $this->loadModel('News');
//        $this->loadModel('User');
//        $this->loadModel('Shop');
//
//        $user_id_list = array();
//        $user_id = $this->Auth->user('id');
//        $shop_id = $this->request->query('shop_id');
//        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
//            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
//        } else {
//            $user_id_list[] = $this->Auth->user('id');
//        }
//        $shops = $this->Shop->find('list', array(
//            'fields' => array('Shop.id', 'Shop.shop_name'),
//            'conditions' => array(
//                'is_deleted <>' => 1,
//                'user_id' => $user_id_list
//            ),
//            'recursive' => -1,
//        ));
//
//        if ($this->request->is('ajax')) {
//            $this->autoRender = false;
//            $action = $this->request->query('action');
//            switch ($action) {
//                case 'delete';
//                    echo $this->delete();
//                    return false;
//            }
//        }
//
//        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $shop_id) {
//            //check shop if it belong to this user
//            if (!$this->Shop->ShopBelongTo($user_id_list, $shop_id)) {
//                throw new NotFoundException();
//            }
//            $shop = $this->Shop->findById($shop_id);
//            $user_id = $shop['Shop']['user_id'];
//        } else {
//            if (count($user_id_list) > 0) {
//                foreach($user_id_list as $key => $value) {
//                    $user_id = $value;
//                    break;
//                }
//            }
//            $shop = $this->Shop->findByUserId($user_id);
//            $shop_id = $shop ? $shop['Shop']['id'] : '';
//        }
//
//        $news = $this->News->find('all', array(
//            'conditions' => array(
//                'is_disabled <>' => 1,
//                'OR' => array('is_deleted' => null, 'is_deleted <>' => 1),
//                'user_id' => $user_id,
//                'type' => 'notice_settings'
//            ),
//            'ORDER' => 'delivery_date_value DESC',
//            'recursive' => -1,
//        ));
//
//        $past_notices = $this->News->find('all', array(
//            'conditions' => array(
//                'is_disabled' => 1,
//                'OR' => array(
//                    'is_deleted' => null,
//                    'is_deleted <>' => 1
//                ),
//                'user_id' => $user_id,
//                'type' => 'notice_settings'
//            ),
//            'ORDER' => 'modified DESC',
//            'recursive' => -1,
//        ));
//
//        $this->set('shop', $shops);
//        $this->set('shop_id', $shop_id);
//        $this->set('new', $news);
//        $this->set('past_notice', $past_notices);
//    }

    public function create()
    {
        $this->loadModel('Area');
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'save';
                    echo $this->save_notice();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_notice();
                return false;
            }
        }
        $area = $this->Area->getAreaDropdown();
        $this->set('areas', $area);
    }

    public function upload_image()
    {
        $image = $this->request->data['Notices']['file_image'];
        echo $this->FileUpload->upload_image($image, 'photo_notices');
    }

    public function save_notice()
    {
        $this->loadModel('Media');
        $this->loadModel('News');
        $this->loadModel('Shop');
        $this->loadModel('AreaNoticeSetting');
        if ($this->request->is('get')) {
            $new_id = $this->request->query('id');
            $user_id = $this->Auth->user('id');
            if ($this->Auth->user('role') == ROLE_HEADQUARTER && $this->request->query('shop_id')) {
                $this->Shop->recursive = -1;
                $shop = $this->Shop->findById($this->request->query('shop_id'));
                $user_id = $shop ? $shop['Shop']['user_id'] : $this->Auth->user('id');
            }
            $new = $this->News->find('first', array(
                'conditions' => array(
                    'News.user_id' => $user_id,
                    'News.id' => $new_id
                )
            ));
            $past = '';
            //If true function do update
            if ($new) {
                $this->AreaNoticeSetting->deleteAll(array('user_id' => $user_id,
                    'notice_id' => $new_id));
                $this->News->set('id', $new['News']['id']);
                $past = 1;
            }
            $destination_target = $this->request->query('destination_target');
            $date = $this->request->query('date_picker');
            $gender = $this->request->query('gender'); //set number 3 to define not select
            $target = 0; //set to 0
            if ($destination_target == 'filter') {
                $gender = $this->request->query('gender');
                $target = $this->request->query('target_hdf');
            }
            if ($date == '') {
                $date = '1-1-1990'; //If not select date
            }
            $data_news = array(
                'News' => array(
                    'destination_target' => $destination_target,
                    'area_id' => $this->request->query('area_id'),
                    'gender' => $gender,
                    'target' => $target,
                    'delivery_date_value' => $date,
                    'delivery_time_value' => $this->request->query('time'),
                    'title' => $this->request->query('title'),
                    'message' => $this->request->query('message'),
                    'type' => 'notice_settings',
                    'is_disabled'=>0,
                    'user_id' => $user_id
                )
            );
            $last_id = '';
            if ($this->News->save($data_news)) {
                $last_id = $this->News->id;
            }
            //For Area
            $area_count = $this->request->query('hdf_area');
            $area_value = $this->request->query('area');
            //For media
            $item = $this->request->query('item');
            $image = $this->request->query('image');
            if ($last_id) {
                for ($k = 1; $k <= $area_count; $k ++) {
                    $area_notice_setting = array(
                        'AreaNoticeSetting' => array(
                            'user_id' => $user_id,
                            'notice_id' => $last_id,
                            'area_id' => $area_value[$k]
                        )
                    );
                    $this->AreaNoticeSetting->create();
                    $this->AreaNoticeSetting->save($area_notice_setting);
                }

                $this->Media->deleteAll(array(
                    'Media.user_id' => $user_id,
                    'Media.model' => 'news', 
                    'Media.external_id' => $new_id
                ));
                if ($this->request->query('exist_img')) {
                    $old_img = $this->request->query('exist_img');
                    for ($j = 1; $j <= count($old_img); $j ++) {
                        $old_pmg = array(
                            'Media' => array(
                                'user_id' => $user_id,
                                'external_id' => $last_id,
                                'model' => 'news',
                                'file' => $old_img[$j]
                            )
                        );
                        $this->Media->create();
                        $this->Media->save($old_pmg);
                    }
                }
                for ($i = 0; $i < $item; $i++) {
                    $data_photo = array(
                        'Media' => array(
                            'user_id' => $user_id,
                            'external_id' => $last_id,
                            'model' => 'news',
                            'file' => $image[$i]
                        )
                    );
                    $this->Media->create();
                    $this->Media->save($data_photo);
                }
            } else {
                //If Insert fail to remove image in folder
                for ($ii = 0; $ii < $item; $ii++) {
                    if (!empty($image[$ii])) {
                        unlink(WWW_ROOT.'uploads/photo_notices/'.$image[$ii]);
                    }
                }
            }
        }
    }

    public function delete()
    {
        $this->loadModel('NewsDelivery');
        $this->loadModel('Media');
        $this->loadModel('News');
        $notice_id                = $this->request->query('notice_id');
        $del_physical             = $this->request->query('del_physical');
        $this->News->id           = $notice_id;
        $this->Media->external_id = $notice_id;
        $media                    = $this->Media->find('all',
            array(
            'conditions' => array(
                'Media.user_id' => $this->Auth->user('id'),
                'AND' => array(
                    'Media.external_id' => $notice_id,
                    'Media.model'=>"news"
                )
            ),
            'recursive' => -1
        ));
        if ($del_physical == 1) {
            
                for ($i = 0; $i < count($media); $i ++) {
                    $image_name = $media[$i]['Media']['file'];
                    $media_id   = $media[$i]['Media']['id'];
                    if (!empty($image_name)) {
                        unlink(WWW_ROOT.'uploads/photo_notices/'.$image_name);
                    }
                    //Delete record in media
                    $this->Media->id = $media_id;
                    $this->Media->delete();
                }
                //Delete record in table news
                $this->News->delete();
                $this->NewsDelivery->delete();
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Notice has been delete success!'
                ));
            
        } else {
            $this->News->saveField('is_deleted', 1);
            for ($i = 0; $i < count($media); $i ++) {
                $media_id        = $media[$i]['Media']['id'];
                $this->Media->id = $media_id;
                //update status is delete
                $this->Media->saveField('is_deleted', 1);
            }
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Notice has been deleted'
            ));
        }
    }

    public function edit($id = null)
    {
        $this->loadModel('News');
        $this->loadModel('Media');
        $this->loadModel('Area');
        $this->loadModel('AreaNoticeSetting');
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'edit';
                    echo $this->save_notice();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_notice();
                return false;
            }
        }
        if (empty($id)) {
            return $this->redirect(array(
                    'controller' => 'notices',
                    'action' => 'index'
            ));
        }
        $news = $this->News->find('all',
            array('conditions' => array('News.id' => $id)));
        if ($news) {
            $id                 = $news[0]['News']['id'];
            $destination_target = $news[0]['News']['destination_target'];
            $gender             = $news[0]['News']['gender'];
            $target             = $news[0]['News']['target'];
            $date               = $news[0]['News']['delivery_date_value'];
            $time               = $news[0]['News']['delivery_time_value'];
            $title              = $news[0]['News']['title'];
            $message            = $news[0]['News']['message'];
            $area_id            = $news[0]['News']['area_id'];

            $this->set('id', $id);
            $this->set('area_id', $area_id);
            $this->set('destination_target', $destination_target);
            $this->set('gender', $gender);
            $this->set('target', $target);
            $this->set('date', $date);
            $this->set('time', $time);
            $this->set('title', $title);
            $this->set('message', $message);
        }
        $media = $this->News->query('SELECT Media.* FROM news N '
            .'INNER JOIN media Media on N.id = Media.external_id WHERE Media.model="news" and (Media.is_deleted <> 1 OR Media.is_deleted is null) and Media.external_id='.$id.'');
        $this->set('media', $media);

        $arr_area = $this->Area->find('list');
        $this->set('areas', $arr_area);

        $area = $this->AreaNoticeSetting->query('SELECT * FROM area_notice_settings A WHERE A.user_id="'.$this->Auth->user('id').'" AND A.notice_id='.$id.'');
        $this->set('show_area', $area);
    }
}