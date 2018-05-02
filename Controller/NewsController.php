<?php
class NewsController extends AppController
{
    public $helpers = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash'
    );

    public $components = array(
        'Paginator',
        'Flash',
        'FileUpload',
        'RequestHandler'
    );

    public $fileType = array(
        'gif',
        'jpeg',
        'png',
        'jpg',
        'mp4',
        '3pg',
        'mp3',
        'pdf',
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(
            'logout', 'news_index'
        );
        $this->Auth->authorize = 'Controller';
        $this->loadModel('Shop');
    }

    public function admin_index()
    {
        $this->Paginator->settings = array(
            'order' => 'News.created DESC',
            'recursive' => -1,
            'paramType' => 'querystring',
            'limit' => PAGE_LIMIT
        );

        //Prevent invalid page number
        try {
            $data = $this->Paginator->paginate('News');
            $this->set('news', $data);
            $this->set('title_for_layout', NEWS_MENAGEMENT_FOR_AGENTS_AND_SHOPS);
        } catch (NotFoundException $e) {
            $this->redirect('/admin/news');
        }
    }

    public function ex_test($mode = null)
    {
        if ($mode == 1) {
            $this->News->sendNews();
        } else {
            $this->News->sendReservationNotice();
        }
        $this->autoRender = false;
    }

    public function admin_create()
    {
        if ($this->request->is('post')) {
            //Check if file successfully upload save data
            if (!file_exists(WWW_ROOT . 'uploads/news')) {
                mkdir(WWW_ROOT . 'uploads/news', 0777, true);
            }
            $file = $this->data['News']['file'];
            $uploadPath = WWW_ROOT.'uploads/news';
            $file_ext = explode('.', $file['name']);
            $file_type = explode('/', $file['type']);
            if (in_array(strtolower($file_ext[1]), $this->fileType)) {
                if ($file['error'] == 0) { // No error
                    $fileName = $file['name'];
                    // Generate new name to avoid overwrite
                    if (file_exists($uploadPath . '/' . $fileName)) {
                        $fileName = date('His') . $fileName;
                    }
                    if (move_uploaded_file($file['tmp_name'],
                        WWW_ROOT.'uploads/news/'.$fileName)) {
                        $this->loadModel('Media');
                        $media = array(
                            'Media' => array(
                                'file' => $fileName,
                                'type' => $file_type[0]
                            )
                        );
                        $this->Media->create();
                        $this->Media->save($media);
                    }
                } else {
                    $this->Flash->set(__(IMAGE_CANNOT_SAVE));
                    return;
                }
            } else {
                $this->Flash->set(__(TYPE_NOT_ALLOW));
                return;
            }
            //Save News
            $this->News->create();
            if ($this->News->save($this->request->data)) {
                //Update News set user_id
                $this->News->id = $this->News->getLastInsertId();
                $this->News->save(array(
                    'News' => array(
                        'user_id' => $this->Auth->user('id')
                    )
                ));
                //Update medai set  news_id
                $this->Media->id = $this->Media->getLastInsertId();
                $this->Media->save(array(
                    'Media' => array(
                        'news_id' => $this->News->getLastInsertId()
                    )
                ));
                //Add NewStatus
                $this->loadModel('NewsStatus');
                $news_status = array(
                    'NewsStatus' => array(
                        'news_id' => $this->News->getLastInsertId(),
                        'user_id' => $this->Auth->user('id'),
                    )
                );
                $this->NewsStatus->create();
                $this->NewsStatus->save($news_status);
                return $this->redirect('/admin/news');
            } else {
                $this->Flash->set(__(NEWS_COULD_NOT_BE_SAVE));
            }
        }
        $this->set('title_for_layout', NEWS_MENAGEMENT_FOR_AGENTS_AND_SHOPS);
    }

    public function admin_view()
    {

    }

    public function admin_edit($id = null)
    {
        $this->News->id = $id;
        if (!$this->News->exists()) {
            throw new NotFoundException(__(INVALID_NEWS));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->News->save($this->request->data)) {
                //Delete old file , upload new file and update file name,type
                if (($this->request->data['News']['file_update']['size']) > 0) {
                    //Delete old file
                    $this->loadModel('Media');
                    $media = $this->Media->find('all', array(
                        'conditions' => array('news_id' => $id),
                        'recursive' => -1
                    ));
                    foreach ($media as $key => $value) {
                        $old_file = $value['Media']['file'];
                    }
                    if (isset($old_file) && file_exists(WWW_ROOT . 'uploads/news/' . $old_file)) {
                        unlink(WWW_ROOT . 'uploads/news/' . $old_file);
                    }
                    //Upload new file
                    if (!file_exists(WWW_ROOT . 'uploads/news')) {
                        mkdir(WWW_ROOT . 'uploads/news', 0777, true);
                    }
                    $file = $this->data['News']['file_update'];
                    $uploadPath = WWW_ROOT.'uploads/news';
                    $file_ext = explode('.', $file['name']);
                    $file_type = explode('/', $file['type']);
                    if (in_array(strtolower($file_ext[1]), $this->fileType)) {
                        if ($file['error'] == 0) { // No error
                            $fileName = $file['name'];
                            // Generate new name to avoid overwrite
                            if (file_exists($uploadPath . '/' . $fileName)) {
                                $fileName = date('His') . $fileName;
                            }
                            if (move_uploaded_file($file['tmp_name'],
                                WWW_ROOT . 'uploads/news/' . $fileName)) {
                                //Update file name
                                $this->loadModel('Media');

                                $count = $this->Media->find('count', array(
                                    'conditions' => array('news_id' => $id)
                                ));
                                if ($count > 0) { //Upate
                                    $this->Media->updateAll(
                                        array(
                                            'file' => "'" . $fileName . "'",
                                            'type' => "'" . $file_type[0] . "'"
                                        ),
                                        array('news_id' => $id)
                                    );
                                } else { // Add new
                                    $media = array(
                                        'Media' => array(
                                            'file' => $fileName,
                                            'type' => $file_type[0],
                                            'news_id' => $id
                                        )
                                    );
                                    $this->Media->create();
                                    $this->Media->save($media);
                                }
                            }
                        } else {
                            $this->Flash->set(__(IMAGE_CANNOT_SAVE));
                            return;
                        }
                    } else {
                        $this->Flash->set(__(TYPE_NOT_ALLOW));
                        return;
                    }
                }
                return $this->redirect('/admin/news');
            } else {
                $this->Flash->set(__(NEWS_COULD_NOT_BE_SAVE));
            }
        } else {
            $options = array('conditions' => array('News.' . $this->News->primaryKey => $id));
            $this->request->data = $this->News->find('first', array($options, 'recursive' => -1));
        }
    }

    public function admin_delete($id = null)
    {
        $this->loadModel('Media');
        $this->News->id = $id;
        if (!$this->News->exists()) {
            throw new NotFoundException(__(INVALID_NEWS));
        }
        //Delete old file
        $count = $this->Media->find('count', array(
            'conditions' => array('news_id' => $id),
            'recursive' => -1
        ));
        if ($count > 0) {
            $media = $this->Media->find('all', array(
                'conditions' => array('news_id' => $id),
                'recursive' => -1
            ));
            foreach ($media as $key => $value) {
                $old_file = $value['Media']['file'];
            }
            if (isset($old_file) && file_exists(WWW_ROOT . 'uploads/' . $old_file)) {
                unlink(WWW_ROOT . 'uploads/' . $old_file);
            }
        }
        //Delete record
        $this->News->delete($id);
        return $this->redirect('/admin/news');
    }

    public function news_index()
    {
        $dt = new DateTime();
        $date = $dt->format('Y-m-d');
        $this->loadModel('SendNews');
        $this->loadModel('NewsDelivery');

        $data = $this->News->find('all', array(
            'joins' => array(
                array(
                    'table' => 'news_deliveries',
                    'alias' => 'NewsDelivery',
                    'type' => 'INNER',
                    'conditions' => array('News.id = NewsDelivery.news_id')
                ),
            ),
            'conditions' => array(
                'NewsDelivery.user_id' => $this->Auth->user('id'),
                'NewsDelivery.is_published' => 1,
                'News.delivery_date_value <= ' => $date,
                'NewsDelivery.is_deleted <> ' => 1
            ),
            'order' => array(
                'NewsDelivery.is_read' => 1,
                'NewsDelivery.delivered_date' => 'desc'
            )
        ));
        $this->set('news_search', $data);
    }

    public function fetch_news_list()
    {
        $dt = new DateTime();
        $date = $dt->format('Y-m-d');
        $this->loadModel('NewsDelivery');
        $data = $this->News->find('all', array(
            'joins' => array(
                array(
                    'table' => 'news_deliveries',
                    'alias' => 'NewsDelivery',
                    'type' => 'INNER',
                    'conditions' => array('News.id = NewsDelivery.news_id')
                )
            ),
            'conditions' => array(
                'NewsDelivery.user_id' => $this->Auth->user('id'),
                'NewsDelivery.is_published' => 1,
                'News.delivery_date_value <= ' => $date,
                'NewsDelivery.is_deleted <> ' => 1
            ),
            'order' => array('NewsDelivery.is_read' => 1, 'NewsDelivery.id' => 'desc')
        ));
        $this->set('news_search', $data);
        $this->layout = 'ajax';
    }

    public function detail($id = null)
    {
        $this->loadModel('NewsDelivery');
        $this->loadModel('Media');
        //Hide notificatin after read
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'read':
                    echo $this->read_notification();
                    return false;
            }
        }
        $detail = $this->News->find('all', array(
            'joins' => array(
                array(
                    'table' => 'news_deliveries',
                    'alias' => 'NewsDelivery',
                    'type' => 'INNER',
                    'conditions' => array('News.id = NewsDelivery.news_id')
                )
            ),
            'conditions' => array(
                'NewsDelivery.user_id' => $this->Auth->user('id'),
                'News.id' => $id,
            )
        ));
        //News joint with media
        $media  = $this->Media->query('Select M.file from media as M where M.model="news" AND M.user_id="'.$this->Auth->user('id').'" AND M.external_id='.$id.'');
        if (!empty($detail) && $id) {
            $deliver_id = $detail[0]['NewsDelivery'][0]['id'];
            $delivered_date = $detail[0]['NewsDelivery'][0]['delivered_date'];
            $type = $detail[0]['News']['type'];
            $title = $detail[0]['News']['title'];
            $message = $detail[0]['News']['message'];
            //Set value to variable
            $this->set('id', $deliver_id);
            $this->set('delivered_date', $delivered_date);
            $this->set('type', $type);
            $this->set('title', $title);
            $this->set('message', $message);
        } else {
            $this->redirect(array('action' => '/'));
        }
        if ($media) {
            $type = $detail[0]['News']['type'];
            $this->set('image', $media);
        } else {
            $this->set('image', '');
        }
    }

    public function read_notification()
    {
        $this->loadModel('NewsDelivery');
        $read_id = $this->request->query('id');
        if ($this->request->is('get')) {
            if ($read_id) {
                $this->NewsDelivery->id = $read_id;
                $this->NewsDelivery->saveField('is_read', 1);
            } else {
                return json_encode(array(
                    'result' => $read_id,
                    'msg' => 'Invalid ID!'
                ));
            }
        }
    }

    //Default loading for notification
    public function notification()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete();
                    return false;
                case 'save';
                    echo $this->save_notification();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_notification();
                return false;
            }
        }
        $this->loadModel('Media');
        $news = $this->News->find('all', array(
            'conditions' => array(
                'News.user_id' => $this->Auth->user('id'),
                'News.type' => 'initial_notice'
            ),
            'recursive' => -1
        ));
        $id = '';
        if ($news != null) {
            $id = $news[0]['News']['id'];
            $title = $news[0]['News']['title'];
            $msg = $news[0]['News']['message'];
            $this->set('title', $title);
            $this->set('msg', $msg);
        } else {
            $this->set('title', '');
            $this->set('msg', '');
            $this->set('id', '');
        }
        //Initial notice get media
        $media = $this->Media->query('Select Media.* from media as Media where Media.model="news" AND Media.user_id="'.$this->Auth->user('id').'" AND Media.external_id='.$id.'');
        if ($media) {
            $this->set('media', $media);
        }
    }

    //Save and Update Notification
    public function save_notification()
    {
        $this->loadModel('Media');
        if ($this->request->is('get')) {
            $new = $this->News->find('first', array(
                'conditions' => array('News.user_id' => $this->Auth->user('id'),
                'News.type' => 'initial_notice')
            ));
            if ($new) {
                $this->News->set('id', $new['News']['id']);
            }
            $data_news = array('News' => array(
                'title' => $this->request->query('title'),
                'message' => $this->request->query('message'),
                'type' => 'initial_notice',
                'user_id' => $this->Auth->user('id')
            ));
            $last_id = '';
            if ($this->News->save($data_news)) {
                $last_id = $this->News->id;
            }
            $this->Media->deleteAll(array('Media.user_id' => $this->Auth->user('id'),
                'Media.model' => 'news', 'Media.external_id' => $last_id));
            $item = $this->request->query('item');
            if ($this->request->query('exist_img')) {
                $old_img = $this->request->query('exist_img');
                for ($j = 1; $j <= count($old_img); $j ++) {
                    $old_pmg = array('Media' => array(
                            'user_id' => $this->Auth->user('id'),
                            'external_id' => $last_id,
                            'model' => 'news',
                            'file' => $old_img[$j]
                    ));
                    $this->Media->create();
                    $this->Media->save($old_pmg);
                }
            }
            $image = $this->request->query('image');
            for ($i = 0; $i < $item; $i++) {
                $user_id    = $this->Auth->user('id');
                $data_photo = array('Media' => array(
                        'user_id' => $user_id,
                        'external_id' => $last_id,
                        'model' => 'news',
                        'file' => $image[$i]
                ));
                $this->Media->create();
                $this->Media->save($data_photo);
            }
        }
    }

    //Delete Photo from db and folder
    public function delete()
    {
        $image_name = $this->request->query('image_name');
        $id = $this->request->query('image_id');
        $this->loadModel('Media');
        //Delete photo
        if (!empty($image_name)) {
            unlink(WWW_ROOT.'uploads/photo_notifications/'.$image_name);
        }
        //Delete record
        $this->Media->id = $id;
        $this->Media->delete();
    }

    //Default Loading Reservation Notification
    public function advance_notification()
    {
        $this->loadModel('Media');
        $this->loadModel('UserShop');
        $this->loadModel('User');

        $user_id = $this->Auth->user('id');
        $shop_id = $this->request->query('shop_id');

        $user_id_list = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        } else {
            $user_id_list[] = $this->Auth->user('id');
        }
        $shop = $this->Shop->find('list',
            array(
            'fields' => array('Shop.id', 'Shop.shop_name'),
            'conditions' => array(
                'is_deleted <>' => 1,
                'user_id' => $user_id_list
            ),
            'recursive' => -1,
        ));

        if ($this->Auth->user('role') === ROLE_HEADQUARTER && $shop_id) {
            //check shop if it belong to this user
            if (!$this->Shop->ShopBelongTo($user_id_list, $shop_id)) {
                throw new NotFoundException();
            }
            $shop2 = $this->Shop->findById($shop_id);
            $user_id = $shop2['Shop']['user_id'];
        } else {
            if (count($user_id_list) > 0) {
                foreach($user_id_list as $key => $value) {
                    $user_id = $value;
                    break;
                }
            }
            $shop2 = $this->Shop->findByUserId($user_id);
        }

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete();
                    return false;
                case 'notification_hour';
                    echo $this->notification_hour();
                    return false;
                case 'notification_day';
                    echo $this->notification_day();
                    return false;
                case 'save';
                    echo $this->save_reservation_notice();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_reservation_notice();
                return false;
            }
        }
        $news = $this->News->find('all',
            array(
            'conditions' => array(
                'News.user_id' => $user_id,
                'AND' => array(
                    'News.type' => 'reservation_notice'
                )
            ),
            'recursive' => -1
        ));
        $id   = "";
        if ($news) {
            $id = $news[0]['News']['id'];
            $title = $news[0]['News']['title'];
            $msg = $news[0]['News']['message'];
            $this->set('id', $id);
            $this->set('title', $title);
            $this->set('msg', $msg);
            //Advance Notification
            $twohourbefore = $news[0]['News']['reservation_notice1'];
            $onedaybefore  = $news[0]['News']['reservation_notice2'];
            $this->set('twohourbefore', $twohourbefore);
            $this->set('onedaybefore', $onedaybefore);
        } else {
            $this->set('id', 0);
            $this->set('title', "");
            $this->set('msg', "");
            $this->set('twohourbefore', "");
            $this->set('onedaybefore', "");
        }
        //Reservation get media
        $media = "";
        if (!empty($id)) {
            $media = $this->Media->find('all', array(
                'conditions' => array(
                    'model' => 'news',
                    'is_deleted <>' => 1,
                    'external_id' => $id
                ),
                'recursive' => -1
            ));
        }

        $this->set('media', $media);
        $this->set('shop', $shop);
        if ($shop2) {
            $this->set('shop_id', $shop2['Shop']['id']);
        }
		//$this->News->sendReservationNotice();
		//$this->News->sendNews();
    }

    //Upload image to folder and return name
    public function upload_image()
    {
        $image = $this->request->data['News']['file_image'];
        echo $this->FileUpload->upload_image($image, 'photo_notifications');
    }

    //Save and Update Reservation Notification
    public function save_reservation_notice()
    {
        $this->loadModel('Media');
        $this->loadModel('NewsDelivery');
        if ($this->request->is(array('get', 'post'))) {
            $user_id = $this->Auth->user('id');
            if ($this->Auth->user('role') == ROLE_HEADQUARTER && $this->request->query('shop_id')) {
                $this->Shop->recursive = -1;
                $shop = $this->Shop->findById($this->request->query('shop_id'));
                $user_id = $shop ? $shop['Shop']['user_id'] : $this->Auth->user('id');
            }
            $new = $this->News->find('first',
                array('conditions' => array('News.user_id' => $user_id,
                    'News.type' => 'reservation_notice')));
            $reservation_notice = "";
            if ($new) {
                $this->News->set('id', $new['News']['id']);
                $reservation_notice = 1;
            }
            $data_news = array('News' => array(
                    'title' => $this->request->query('title'),
                    'message' => $this->request->query('message'),
                    'type' => 'reservation_notice',
                    'user_id' => $user_id
            ));
            $last_id   = "";
            if ($this->News->save($data_news)) {
                $last_id = $this->News->id;
                $this->Media->deleteAll(array('Media.user_id' => $user_id,
                    'Media.model' => 'news', 'Media.external_id' => $last_id));
                $item = $this->request->query('item');
                if ($this->request->query('exist_img')) {
                    $old_img = $this->request->query('exist_img');
                    for ($j = 1; $j <= count($old_img); $j ++) {
                        $old_pmg = array('Media' => array(
                                'user_id' => $user_id,
                                'external_id' => $last_id,
                                'model' => 'news',
                                'file' => $old_img[$j]
                        ));
                        $this->Media->create();
                        $this->Media->save($old_pmg);
                    }
                }
                $image = $this->request->query('image');
                for ($i = 0; $i < $item; $i++) {
                    $data_photo = array('Media' => array(
                        'user_id' => $user_id,
                        'external_id' => $last_id,
                        'model' => 'news',
                        'file' => $image[$i]
                    ));
                    $this->Media->create();
                    $this->Media->save($data_photo);
                }
            }
        }
    }

    //Switch on/off  2 hour before notification
    public function notification_hour()
    {
        $user_id = $this->Auth->user('id');
        if ($this->Auth->user('role') == ROLE_HEADQUARTER && $this->request->query('shop_id')) {
            $this->Shop->recursive = -1;
            $shop = $this->Shop->findById($this->request->query('shop_id'));
            $user_id = $shop ? $shop['Shop']['user_id'] : $this->Auth->user('id');
        }
        $notification_status = $this->request->query('notification_status');
        $notification_status == 'true' ? $notification_status = '1' : $notification_status
                = '0';
        $new = $this->News->find('first',
            array('conditions' => array('News.user_id' => $user_id,
                'News.type' => 'reservation_notice')));
        $reservation_hour    = "";
        if ($new) {
            $this->News->set('id', $new['News']['id']);
            $reservation_hour = 1;
        }
        $data_news = array('News' => array(
                'reservation_notice1' => $notification_status, //2h before the reservation
                'type' => 'reservation_notice',
                'user_id' => $user_id
        ));

        $this->News->save($data_news);
        //Save News Delivery
//        $data_news_deliveries = array(
//            'news_id' => $last_id,
//            'user_id' => $this->Auth->user('id'),
//            'delivered_date' => date("Y-m-d H:i:s")
//        );
//        if ($reservation_hour == "") {
//            $this->NewsDelivery->save($data_news_deliveries);
//        }else{
//            $new     = $this->News->find('first',array('conditions' => array('News.user_id' => $this->Auth->user('id'),'News.type' => 'reservation_notice')));
//            $two_hour=$new['News']['reservation_notice2'];
////----------If check reservation notice (1 day before OFF) And (2 hour before OFF) change is_publshised =0/Null
//        if(($notification_status =="" || $notification_status ==0 ) && ($two_hour=="" || $two_hour==0)){
//                $delivery = $this->NewsDelivery->find('first',array('conditions' => array('user_id' => $this->Auth->user('id'),'news_id' =>$last_id)));
//                $this->NewsDelivery->set('id',$delivery['NewsDelivery']['id']);
//                $this->NewsDelivery->saveField('is_published', NULL);
//            }
//        }
    }

    //Switch on/off  1 day before notification
    public function notification_day()
    {
        $this->loadModel('NewsDelivery');
        $user_id = $this->Auth->user('id');
        if ($this->Auth->user('role') == ROLE_HEADQUARTER && $this->request->query('shop_id')) {
            $this->Shop->recursive = -1;
            $shop = $this->Shop->findById($this->request->query('shop_id'));
            $user_id = $shop ? $shop['Shop']['user_id'] : $this->Auth->user('id');
        }
        $notification_status = $this->request->query('notification_status');
        $notification_status == 'true' ? $notification_status = '1' : $notification_status
                = '0';
        $new                 = $this->News->find('first',
            array('conditions' => array('News.user_id' => $user_id,
                'News.type' => 'reservation_notice')));
        if ($new) {
            $this->News->set('id', $new['News']['id']);
        }
        $data_news = array('News' => array(
                'reservation_notice2' => $notification_status, //1d before the reservation
                'type' => 'reservation_notice',
                'user_id' => $user_id
        ));
        $this->News->save($data_news);
//        //Save News Delivery
//        $data_news_deliveries = array(
//            'news_id' => $last_id,
//            'user_id' => $this->Auth->user('id'),
//            'delivered_date' => date("Y-m-d H:i:s")
//        );
//        if ($reservation_one_day == "") {
//            $this->NewsDelivery->save($data_news_deliveries);
//        }else{
//            $new     = $this->News->find('first',array('conditions' => array('News.user_id' => $this->Auth->user('id'),'News.type' => 'reservation_notice')));
//            $two_hour=$new['News']['reservation_notice1'];
////----------If check reservation notice (1 day before OFF) And (2 hour before OFF) change is_publshised =0/Null
//        if(($notification_status =="" || $notification_status ==0 ) && ($two_hour=="" || $two_hour==0)){
//                $delivery = $this->NewsDelivery->find('first',array('conditions' => array('user_id' => $this->Auth->user('id'),'news_id' =>$last_id)));
//                $this->NewsDelivery->set('id',$delivery['NewsDelivery']['id']);
//                $this->NewsDelivery->saveField('is_published', NULL);
//            }
//        }
    }
    //Default loading last visit notification
    public function last_visit_notification()
    {
        $news = $this->News->find('all',
            array(
            'conditions' => array(
                'user_id' => $this->Auth->user('id'),
                'type' => 'last_visit_notice',
                'OR' => array(
                    'is_deleted IS NULL',
                    'is_deleted <>' => 1
                )
            ),
            'order' => array('id' => 'desc'),
            'recursive' => -1
        ));
        if ($news) {
            $title                   = $news[0]['News']['title'];
            $id                      = $news[0]['News']['id'];
            $is_disabled             = $news[0]['News']['is_disabled'];
            $last_visit_notice_value = $news[0]['News']['last_visit_notice_value'];
            $this->set('is_disabled', $is_disabled);
            $this->set('last_visit_notice_value', $last_visit_notice_value);
            $this->set('id', $id);
            $this->set('title', $title);
            $this->set('news', $news);
        } else {
            $this->set('news', '');
        }
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action           = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete_last_visit_notice();
                    return false;
                case 'save';
                    echo $this->save_on_off_notification();
                    return false;
            }
        }
    }

    //Switch On/Off last visit notification
    public function save_on_off_notification()
    {
        $last_visit_notice_id = $this->request->query('news_id');
        $is_disable           = $this->request->query('is_disabled');
        $is_disable == 'true' ? $is_disable           = '1' : $is_disable           = '0';
        $new                  = $this->News->find('first',
            array('conditions' => array('News.user_id' => $this->Auth->user('id'),
                'News.type' => 'last_visit_notice', 'News.id' => $last_visit_notice_id)));
        if ($new) {
            $this->News->set('id', $new['News']['id']);
        }
        $data = array(
            'News' => array(
                'is_disabled' => $is_disable,
                'type' => 'last_visit_notice',
                'id' => $last_visit_notice_id,
                'user_id' => $this->Auth->user('id')
        ));
        if ($this->News->save($data)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Last visit notification has been saved!'
            ));
        }
    }

    //Delete last visit notification
    public function delete_last_visit_notice()
    {
        $this->loadModel('Media');
        $last_visit_notice_id = $this->request->query('id');
        $del_physical         = $this->request->query('del_physical');
        $this->News->id       = $last_visit_notice_id;
        $this->Media->news_id = $last_visit_notice_id;
        $media                = $this->Media->find('all',
            array(
            'conditions' => array(
                'Media.user_id' => $this->Auth->user('id'),
                'AND' => array(
                    'Media.external_id' => $last_visit_notice_id,
                    'Media.model' => 'news'
                )
            ),
            'recursive' => -1
        ));
        if ($del_physical == 1) {
            for ($i = 0; $i < count($media); $i ++) {

                $image_name = $media[$i]['Media']['file'];
                $media_id   = $media[$i]['Media']['id'];
                if (!empty($image_name)) {
                    unlink(WWW_ROOT.'uploads/photo_notifications/'.$image_name);
                }
                //Delete record in media
                $this->Media->id = $media_id;
                $this->Media->delete();
            }
            //Delete record in table news
            $this->News->id = $last_visit_notice_id;
            if ($this->News->delete()) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Delete last visit notification success!'
                ));
            }
        } else {
            $this->News->saveField('is_deleted', 1);
            for ($i = 0; $i < count($media); $i ++) {
                $media_id        = $media[$i]['Media']['id'];
                $this->Media->id = $media_id;
                //update status is delete
                $this->Media->saveField('is_deleted', 1);
            }
        }
    }

    //Save last visit notification
    public function last_visit_notification_create()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action           = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete();
                    return false;
                case 'save';
                    echo $this->save_last_notification();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_last_notification();
                return false;
            }
        }
    }

    //Update last visit notification
    public function last_visit_notification_edit($id = null)
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action           = $this->request->query('action');
            switch ($action) {
                case 'delete';
                    echo $this->delete();
                    return false;
                case 'save';
                    echo $this->save_last_notification();
                    return false;
            }
            if ($this->request->is('post')) {
                echo $this->upload_image();
                return false;
            } else if ($this->request->is('get')) {
                echo $this->save_last_notification();
                return false;
            }
        }
        $this->loadModel('Media');
        $news = $this->News->find('all',
            array('conditions' => array('News.id' => $id)));
        if (!empty($news) && $id) {
            //News
            $last_visit_notice_value = $news[0]['News']['last_visit_notice_value'];
            $title                   = $news[0]['News']['title'];
            $message                 = $news[0]['News']['message'];
            $this->set('last_visit_notice_value', $last_visit_notice_value);
            $is_disabled             = $news[0]['News']['is_disabled'];
            $this->set('is_disabled', $is_disabled);
            $this->set('title', $title);
            $this->set('message', $message);
            $this->set('id', $news[0]['News']['id']);
        } else {
            $this->redirect(array('action' => 'last_visit_notification_create'));
        }
        $media = $this->Media->find('all',
            array(
            'conditions' => array(
                'Media.user_id' => $this->Auth->user('id'),
                'AND' => array(
                    'Media.model' => 'news',
                    'Media.external_id' => $id
                )
            ),
            'recursive' => -1
        ));
        if ($media) {
            $this->set('media', $media);
        }
    }

    //Update last visit notification
    public function save_last_notification()
    {
        $this->loadModel('Media');
        if ($this->request->is('get')) {
            $new_id = $this->request->query('update_id');
            $new    = $this->News->find('first',
                array('conditions' => array('News.user_id' => $this->Auth->user('id'),
                    'News.type' => 'last_visit_notice', 'News.id' => $new_id)));
            if ($new) {
                $this->News->set('id', $new['News']['id']);
            }
            $last_visit_notice_value = $this->request->query('last_visit_notice_value');
            $is_disabled             = $this->request->query('is_disabled');
            $last_visit_notice_value == '' ? $last_visit_notice_value = '0' : $last_visit_notice_value;
            $is_disabled == '' ? $is_disabled             = '0' : $is_disabled             = '1';
            if ($last_visit_notice_value == '') {
                $is_disabled = 1;
            }
            $data_news = array('News' => array(
                    'last_visit_notice_value' => $last_visit_notice_value,
                    'is_disabled' => $is_disabled,
                    'title' => $this->request->query('title'),
                    'message' => $this->request->query('message'),
                    'type' => 'last_visit_notice',
                    'user_id' => $this->Auth->user('id')
            ));
            $last_id   = "";
            if ($this->News->save($data_news)) {
                $last_id = $this->News->id;
            }
            $this->Media->deleteAll(array('Media.user_id' => $this->Auth->user('id'),
                'Media.model' => 'news', 'Media.external_id' => $new_id));
            if ($this->request->query('exist_img')) {
                $old_img = $this->request->query('exist_img');
                for ($j = 1; $j <= count($old_img); $j ++) {
                    $old_pmg = array('Media' => array(
                            'user_id' => $this->Auth->user('id'),
                            'external_id' => $new_id,
                            'model' => 'news',
                            'file' => $old_img[$j]
                    ));
                    $this->Media->create();
                    $this->Media->save($old_pmg);
                }
            }
            $item  = $this->request->query('item');
            $image = $this->request->query('image');
            for ($i = 0; $i < $item; $i++) {
                $user_id    = $this->Auth->user('id');
                $data_photo = array('Media' => array(
                        'user_id' => $user_id,
                        'external_id' => $last_id,
                        'model' => 'news',
                        'file' => $image[$i]
                ));
                $this->Media->create();
                $this->Media->save($data_photo);
            }
        }
    }

    public function birthdayNotification() {
        $this->loadModel('User');
        $this->loadModel('Shop');

        $user_id_list = array();
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->Auth->user('id');
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

        if ($shops && $this->Auth->user('role') === ROLE_HEADQUARTER) {
            if (!$shop_id) {
                reset($shops);
                $shop_id = key($shops);
            }
            $shop = $this->Shop->findById($shop_id);
            if ($shop) {
                $user_id = $shop['Shop']['user_id'];
            }
        }
        $news = $this->News->find('first', array(
            'fields' => array('id', 'title', 'message', 'image', 'is_disabled'),
            'conditions' => array(
                'type' => BIRTHDAY_NOTIFICATION,
                'user_id' => $user_id
            ),
            'recursive' => -1,
        ));

        if ($this->request->is('post')) {
            $file_name = '';
            if (isset($this->request->data['News']) && $this->request->data['News']['image'] && !empty($this->request->data['News']['image']['name'])) {

                $file = $this->request->data['News']['image'];
                $name = $this->request->data['News']['image']['name'];
                $extansion = explode('.', $name);
                $file_name = strtotime(date('Y-m-d H:m:s')).'.'.$extansion[1];
                move_uploaded_file($file['tmp_name'], WWW_ROOT . 'uploads/news/' . $file_name);
            }
            $this->request->data['News']['image'] = $file_name;
            if ($this->request->data['News']['id']) {
                if (empty($this->request->data['News']['image'])) {
                    $this->request->data['News']['image'] = $news['News']['image'];
                }
                $this->News->id = $this->request->data['News']['id'];
                $resule = $this->News->save($this->request->data);
            } else {
                $this->request->data['News']['type'] = BIRTHDAY_NOTIFICATION;
                $this->request->data['News']['user_id'] = $user_id;
                $this->News->create();
                $resule = $this->News->save($this->request->data);
            }
            $this->request->data = $resule;
            if ($resule) {
                $this->Session->setFlash(MESSAGE_SUCCESS, 'success');
            } else {
                $this->Session->setFlash(MESSAGE_ERROR, 'error');
            }
        } else {
            $this->request->data = $news;
        }
        $this->set(array('shops' => $shops));
    }

    public function visitNotification()
    {
        $this->loadModel('User');
        $this->loadModel('Shop');

        $user_id_list = array();
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->Auth->user('id');
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

        if ($shops && $this->Auth->user('role') === ROLE_HEADQUARTER) {
            if (!$shop_id) {
                reset($shops);
                $shop_id = key($shops);
            }
            $shop = $this->Shop->findById($shop_id);
            if ($shop) {
                $user_id = $shop['Shop']['user_id'];
            }
        }
        $news = $this->News->find('all', array(
            'fields' => array('id', 'title', 'message', 'image', 'time', 'duration', 'is_disabled'),
            'conditions' => array(
                'type' => LAST_VISIT_NOTIFICATION,
                'user_id' => $user_id
            ),
            'recursive' => -1,
            'limit' => MAX_NOTIFICATION
        ));

        if ($this->request->is('post')) {
            $data = array();
            foreach ($this->request->data['News'] as $key => $value) {
                $file_name = '';
                if (isset($value['image']) && !empty($value['image']['name'])) {

                    $file = $value['image'];
                    $name = $value['image']['name'];
                    $extansion = explode('.', $name);
                    $file_name = strtotime(date('Y-m-d H:m:s')).'.'.$extansion[1];
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . 'uploads/news/' . $file_name);
                }
                $value['image'] = $file_name;
                if ($value['id']) {
                    if (!$value['image']) {
                        unset($value['image']);
                    }
                    $this->News->id = $value['id'];
                    $resule = $this->News->save($value);
                } else {
                    $value['type'] = LAST_VISIT_NOTIFICATION;
                    $value['user_id'] = $user_id;
                    $this->News->create();
                    $resule = $this->News->save($value);
                }
                $data[] = $resule;
            }
            if ($data) {
                $this->Session->setFlash(MESSAGE_SUCCESS, 'success');
                $this->redirect(array("controller" => "news", 
                      "action" => "visitNotification",
                      "?" => 'shop_id='.$shop_id));
            } else {
                $this->Session->setFlash(MESSAGE_ERROR, 'error');
            }
        }
        $this->set(array('data' => $news));
        $this->set(array('shops' => $shops));
    }

    public function create_notification()
    {
        if (!$this->Auth->user('role') === ROLE_HEADQUARTER) {
            throw new NotFoundException;
        }
        $this->loadModel('Group');
        $this->loadModel('Area');
        $this->loadModel('Shop');
        $this->loadModel('User');
        $this->loadModel('ShopGroup');
        $this->loadModel('AreaNoticeSetting');
        $lst_groups = $this->Group->find('list', array(
            'fields' => array('Group.id', 'Group.name'),
            'conditions' => array('Group.user_id' => $this->Auth->user('id'))
        ));
        $lst_area = $this->Area->find('list', array(
            'fields' => array('Area.id', 'Area.name'),
        ));
        $group_option = array('all' => '全ユーザ');
        foreach ($lst_groups as $key => $value) {
            $group_option[$key] = $value. ' 全ユーザ';
        }
        $group_option['no_group'] = 'No Group 全ユーザ';

        if ($this->request->is('post')) {
            $data = $this->request->data('News');
            
            $shops = array();
            $file_name = '';
            if ($data['file'] && !empty($data['file']['tmp_name'])) {
                //uploads/photo_notices/
                $file = $data['file'];
                $name = $data['file']['name'];
                $extansion = explode('.', $name);
                $file_name = strtotime(date('Y-m-d H:m:s')).'.'.$extansion[1];
                move_uploaded_file($file['tmp_name'], WWW_ROOT . 'uploads/photo_notices/' . $file_name);
                $this->request->data['News']['file']['file'] = Router::url('/', true).'uploads/photo_notices/' . $file_name;
            }

            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
            $conditions = array(
                'Shop.user_id' => $user_id_list,
                'Shop.is_deleted <>' => 1
            );
            $shops = array();
            $data_save = array();
            if ($data['destination_target'] == 'all') {
                $shops = $this->Shop->find('all', array(
                    'conditions' => $conditions,
                    'recursive' => -1
                ));
            } else {
                if (isset($data['age']) && $data['age']) {
                    $data_save['News']['taget'] = '';
                    foreach ($data['age'] as $value) {
                        if (empty($data_save['News']['taget'])) {
                            $data_save['News']['taget'] = $value;
                        } else {
                            $data_save['News']['taget'] = $data_save['News']['taget'].','.$value;
                        }
                    }
                }
                $data_save['News']['gender'] = $data['gender'];
                if ($data['group_id'] == 'no_group') {
                    if ($data['shop_id'] == 'all') {
                        $lst_group_id = $this->Group->find('list', array(
                            'fields' => array('Group.id'),
                            'conditions' => array(
                                'Group.user_id' => $this->Auth->user('id')
                            )
                        ));
                        $not_shop = $this->ShopGroup->find('list', array(
                            'fields' => array('ShopGroup.shop_id'),
                            'condition' => $lst_group_id
                        ));
                        $conditions['Shop.id not'] = $not_shop;
                    } elseif (!empty($data['shop_id'])) {
                        $conditions['Shop.id'] = $data['shop_id'];
                    }
                } else if ($data['group_id'] !== 'all' && !empty($data['group_id'])) {
                    if ($data['shop_id'] == 'all') {
                        $shop_ids = $this->ShopGroup->find('list', array(
                            'fields' => array('ShopGroup.shop_id'),
                            'condition' => array(
                                'ShopGroup.group_id' => $data['group_id']
                            )
                        ));
                        $conditions['Shop.id'] = $shop_ids;
                    } elseif (!empty($data['shop_id'])) {
                        $conditions['Shop.id'] = $data['shop_id'];
                    }
                }
                $shops = $this->Shop->find('all', array(
                    'conditions' => $conditions,
                    'recursive' => -1
                ));
            }

            if ($shops) {
                foreach($shops as $value) {
                    $data_save['News']['destination_target'] = $data['destination_target'];
                    $data_save['News']['user_id'] = $value['Shop']['user_id'];
                    $data_save['News']['title'] = $data['title'];
                    $data_save['News']['message'] = $data['message'];
                    $data_save['News']['delivery_date'] = 1;
                    $data_save['News']['delivery_date_value'] = $data['date_picker'];
                    $data_save['News']['delivery_time_value'] = $data['time'];
                    $data_save['News']['type'] = 'notice_settings';
                    $data_save['News']['image'] = $file_name;

                    $this->News->create();
                    $this->News->save($data_save);
                    if ($data['destination_target'] !== 'all' && $data['area']) {
                        $area_ids = explode(',', $data['area']);
                        foreach ($area_ids as $value1) {
                            $data_area['AreaNoticeSetting']['notice_id'] = $this->News->getLastInsertID();
                            $data_area['AreaNoticeSetting']['user_id'] = $value['Shop']['user_id'];
                            $data_area['AreaNoticeSetting']['area_id'] = $value1;
                            $this->AreaNoticeSetting->create();
                            $this->AreaNoticeSetting->save($data_area);
                        }
                    }
                }
                $this->Session->setFlash(MESSAGE_SUCCESS, 'success');
            } else {
                $this->Session->setFlash(MESSAGE_ERROR, 'error');
            }
        }
        $this->set(array('group_option' => $group_option));
        $this->set(array('area_option' => $lst_area));
    }

    public function get_data_list()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException;
        }
        $this->layout = null;
        $this->autoRender = false;
        $this->loadModel('Group');
        $this->loadModel('User');
        $this->loadModel('Area');
        $group_list = [];
        $area_list = [];

        $groups = $this->Group->find('all', array(
            'conditions' => array(
                'Group.user_id' => $this->Auth->user('id'),
            ),
            'recursive' => -1,
        ));
        if ($groups) {
            foreach ($groups as $key => $value) {
                $group_list[] = $value['Group'];
            }
        }

        $areas = $this->Area->find('all');
        if ($areas) {
            foreach ($areas as $key => $value) {
                $area_list[] = $value['Area'];
            }
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'success',
            'data' => array(
                'Groups' => $group_list,
                'Areas' => $area_list,
            )
        ));
    }

    public function get_shop_by_group_id()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException;
        }
        $this->layout = null;
        $this->autoRender = false;
        $this->loadModel('Group');
        $this->loadModel('ShopGroup');
        $this->loadModel('User');

        $group_id = $this->request->query('group_id');
        $shops =  array();

        $user_id_list = $this->User->find('list', array(
            'fields' => array('User.id'),
            'conditions' => array(
                'User.parent_id' => $this->Auth->user('id'),
                'User.confirmed' => 1,
            ),
        ));
        $lst_shop_id = $this->ShopGroup->find('list', array(
            'joins' => array( array(
                'table' => 'groups',
                'alias' => 'Group',
                'type' => 'INNER',
                'conditions' => array(
                    'Group.id = ShopGroup.group_id',
                )
            )),
            'conditions' => array(
                'Group.user_id' => $this->Auth->user('id'),
            ),
            'fields' => array('ShopGroup.shop_id')
        ));

        if ($group_id == 'no_group') {
            $shops = $this->Shop->find('all', array(
                'conditions' => array(
                    'Shop.id <>' => $lst_shop_id,
                    'Shop.user_id' => $user_id_list
                ),
                'recursive' => -1,
                'fields' => array('Shop.id', 'Shop.shop_name')
            ));
        } else {
            $shops = $this->Shop->find('all', array(
                'joins' => array(array(
                    'table' => 'shop_groups',
                    'alias' => 'ShopGroup',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Shop.id = ShopGroup.shop_id',
                    )
                )),
                'conditions' => array(
                    'Shop.user_id' => $user_id_list,
                    'ShopGroup.group_id' => $this->request->query('group_id')
                ),
                'recursive' => -1,
                'fields' => array('Shop.id', 'Shop.shop_name')
            ));
        }

        $data = array();
        foreach ($shops as $value) {
            $data[] = $value['Shop'];
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'success',
            'data' => $data,
        ));
    }
}