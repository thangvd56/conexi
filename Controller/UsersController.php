<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('AppController', 'Controller');

class UsersController extends AppController
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
        'RequestHandler',
        'ImageResize'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'logout',
            'forgot_password',
            'notification',
            'reset_password',
            'first_notification',
            'reservation_notification',
            'app_stamp',
            'app_staffs',
            'app_reservation_url',
            'app_messages'
        ));
        $this->Auth->authorize = 'Controller';
        $this->loadModel('UserShop');
        $this->loadModel('Shop');
        $this->loadModel('Setting');
        $this->loadModel('StampSetting');
    }

    public function admin_index()
    {
        $role = $this->request->query('role');
        $agent_id = $this->request->query('agent_id');
        if (isset($agent_id) && $role = 'shop') {
            $keyword = $this->request->query('Search');
            $this->Paginator->settings = array(
                'conditions' => array(
                    'OR' => array(
                        array('User.firstname LIKE' => '%'.$keyword.'%'),
                        array('User.lastname LIKE' => '%'.$keyword.'%'),
                        array('User.username LIKE' => '%'.$keyword.'%'),
                        array('User.created LIKE' => '%'.$keyword.'%'),
                        array('User.contact LIKE' => '%'.$keyword.'%'),
                        array('User.status LIKE' => '%'.$keyword.'%')
                    ),
                    'AND' => array(
                        array('User.role' => 'shop'),
                        array('User.status <> ' => 0),
                        array('User.agent_id' => $agent_id)
                    )
                ),
                'order' => 'User.created DESC',
                'recursive' => 1,
                'paramType' => 'querystring',
                'limit' => PAGE_LIMIT
            );
            //Prevent invalid page number
            try {
                $data = $this->Paginator->paginate('User');
                $this->set('allUsers', $data);
                $this->set('title_for_layout', SHOP_OWNER_MANAGEMENT);
            } catch (NotFoundException $e) {
                $this->redirect('/admin/users?role=shop&agent_id='.$agent_id);
            }
        } else if ($role == 'agent') {
            $keyword = $this->request->query('Search');
            $this->Paginator->settings = array(
                'conditions' => array(
                    'OR' => array(
                        array('User.firstname LIKE' => '%'.$keyword.'%'),
                        array('User.lastname LIKE' => '%'.$keyword.'%'),
                        array('User.username LIKE' => '%'.$keyword.'%'),
                        array('User.created LIKE' => '%'.$keyword.'%'),
                        array('User.contact LIKE' => '%'.$keyword.'%'),
                        array('User.status LIKE' => '%'.$keyword.'%'),
                        array("User.id IN (SELECT users.agent_id FROM users WHERE users.name like '%".$keyword."%' )")
                    ),
                    'AND' => array(
                        array('User.role' => 'agent'),
                    //array('User.is_deleted <> ' => 1)
                    )
                ),
                'order' => 'User.created DESC',
                'recursive' => 1,
                'paramType' => 'querystring',
                'limit' => PAGE_LIMIT
            );
            //Prevent invalid page number
            try {
                $data = $this->Paginator->paginate('User');
                $this->set('allUsers', $data);
                $this->set('title_for_layout', AGENT_MANAGEMENT);
            } catch (NotFoundException $e) {
                $this->redirect('/admin/users?role=agent');
            }
        } else if ($role == 'shop') {
            $keyword = $this->request->query('Search');
            $this->Paginator->settings = array(
                'conditions' => array(
                    'OR' => array(
                        array('User.firstname LIKE' => '%'.$keyword.'%'),
                        array('User.lastname LIKE' => '%'.$keyword.'%'),
                        array('User.username LIKE' => '%'.$keyword.'%'),
                        array('User.created LIKE' => '%'.$keyword.'%'),
                        array('User.contact LIKE' => '%'.$keyword.'%'),
                        array('User.status LIKE' => '%'.$keyword.'%'),
                    ),
                    'AND' => array(
                        array('User.role' => 'shop'),
//                      array('User.is_deleted <> ' => 1)
                        array('User.status <> ' => 0)
                    )
                ),
                'order' => 'User.created DESC',
                'recursive' => 1,
                'paramType' => 'querystring',
                'limit' => PAGE_LIMIT
            );
            //Prevent invalid page number
            try {
                $data = $this->Paginator->paginate('User');
                $this->set('allUsers', $data);
                $this->set('title_for_layout', SHOP_OWNER_MANAGEMENT);
            } catch (NotFoundException $e) {
                $this->redirect('/admin/users?role=shop');
            }
        } else {
            $keyword = $this->request->query('Search');
            $this->Paginator->settings = array(
                'conditions' => array(
                    'OR' => array(
                        array('User.firstname LIKE' => '%'.$keyword.'%'),
                        array('User.lastname LIKE' => '%'.$keyword.'%'),
                        array('User.username LIKE' => '%'.$keyword.'%'),
                        array('User.created LIKE' => '%'.$keyword.'%'),
                        array('User.contact LIKE' => '%'.$keyword.'%'),
                        array('User.status LIKE' => '%'.$keyword.'%'),
                    ),
                    'AND' => array(
                        array('User.role <>' => 'shop'),
                        array('User.status <> ' => 0)
                    )
                ),
                'order' => 'User.created DESC',
                'recursive' => 1,
                'paramType' => 'querystring',
                'limit' => PAGE_LIMIT
            );
            //Prevent invalid page number
            try {
                $data = $this->Paginator->paginate('User');
                $this->set('allUsers', $data);
                $this->set('title_for_layout', USER_MANAGEMENT);
            } catch (NotFoundException $e) {
                $this->redirect('/admin/users');
            }
        }
        $this->User->recursive = -1;
        $user_list = $this->User->find('all',
            array(
            'fields' => array('*'),
            'conditions' => array('User.status' => 1 ,'User.role' => 'shop'),
            'joins' => array(
                array(
                    'table' => 'shops',
                    'alias' => 'Shop',
                    'type' => 'inner',
                    'conditions' => array('User.id = Shop.user_id')
                )
            ),
            'contain' => array('Shop'),
        ));
        $this->set('user_list', $user_list);

    }

    public function admin_create()
    {
        $role = $this->request->query('role');
        pr($this->request->data);
        if (isset($agent_id) && $role == 'shop') {
            if ($this->request->is('post')) {
                $this->User->create();
                if ($this->User->save($this->request->data)) {
                    //Update user role as SHOP and set agent_id
                    $this->User->id = $this->User->getLastInsertId();
                    $this->User->save(array(
                        'User' => array(
                            'role' => 'shop',
                            'agent_id' => $agent_id
                        )
                    ));
                    return $this->redirect('/admin/users?role=shop&agent_id='.$agent_id);
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            }
            $this->set('title_for_layout', SHOP_OWNER_MANAGEMENT);
        } else if ($role == 'agent') {
            if ($this->request->is('post')) {
                $this->User->create();
                if ($this->User->save($this->request->data)) {
                    //Update user role as AGENT
                    $this->User->id = $this->User->getLastInsertId();
                    $this->User->saveField('role', 'agent');
                    return $this->redirect('/admin/users?role=agent');
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            }
            $this->set('title_for_layout', AGENT_MANAGEMENT);

        } else if ($role == 'shop') {

            if ($this->request->is('post')) {
                $this->User->create();
                if ($this->User->save($this->request->data)) {
                    //Update user role as SHOP
                    $this->User->id = $this->User->getLastInsertId();
                    if ($this->User->saveField('role', 'shop')) {
                        $data_shop =array('Shop'=>array(
                            'user_id'=>$this->User->getLastInsertId(),
                            'hours_start'=>'00:00',
                            'hours_end'=>'00:00'
                        ));
                        $this->Shop->create();
                        if($this->Shop->save($data_shop)){
                          $data_stampsetting = array('StampSetting'=>array(
                              'shop_id'=> $this->Shop->getLastInsertId(),
                              'stamp_number'=>10,
                              'app_installation'=>0,
                              'app_startup'=>0,
                              'visit' =>0,
                              'valid_date'=>10
                          ));
                        $this->StampSetting->create();
                        $this->StampSetting->save($data_stampsetting);
                        $arr_function_name = array(
                            'お知らせ',
                            'Myカルテ',
                            '会員証',
                            'Web予約',
                            'クーポン',
                            'スタンプ',
                            '店舗情報',
                            'スタッフ',
                            'メニュー',
                            'フォト',
                            'SNS',
                            '設定',
                            '店舗一覧',
                        );
                        $arr_function_tag = array(
                            'notice',
                            'my_medical_record',
                            'membership',
                            'web_reservations',
                            'coupon',
                            'stamp',
                            'store_information',
                            'staff',
                            'app_menu',
                            'photo_gallery',
                            'sns_share',
                            'setting',
                            'list_shop',
                        );
                        $count =1;
                        for($i=0; $i < count($arr_function_tag); $i++){
                            $data_setting = array('Setting'=>array(
                                'shop_id' => $this->Shop->getLastInsertId(),
                                'function_image' => 'menu'.($count).'.png',
                                'function_name' => $arr_function_name[$i],
                                'function_tag' => $arr_function_tag[$i],
                                'function_index' => ($count),
                                'active'=>1
                           ));
                            $this->Setting->create();
                            $this->Setting->save($data_setting);
                            $count++;
                        }
                      }
                    }
                    return $this->redirect('/admin/users?role=shop');
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            }
            $this->set('title_for_layout', SHOP_OWNER_MANAGEMENT);
        } else {
            $shop = $this->Shop->find('list',array('conditions' => array('Shop.is_deleted <>'=>1)));
            $this->set('shop_id',$shop);
            if ($this->request->is('post')) {
                $this->User->create();
                if ($this->User->save($this->request->data)) {
                    //User role set when create
                    $shop_id =$this->request->data['User']['shop_id'];
                    $data_user_shop=array('UserShop'=>array(
                        'shop_id'=>$shop_id,
                        'user_id'=>$this->User->getLastInsertId(),
                        'type'=>'shop',
                        'is_allow_notification'=>1
                    ));
                    if($this->request->data[User][role]=='user'){
                    $this->UserShop->create();
                    $this->UserShop->save($data_user_shop);
                    }
                    return $this->redirect('/admin/users');
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            }
            $this->set('title_for_layout', USER_MANAGEMENT);
        }
    }
    /*
     * Function admin_edit
     * Modified 11/ November/2015
     * Channeth
     */
    public function admin_edit($id = null)
    {
        $role = $this->request->query('role');
        $agent_id = $this->request->query('agent_id');
        if (isset($agent_id) && $role == 'shop') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            if ($this->request->is(array('post', 'put'))) {
                if ($this->User->save($this->request->data)) {
                    return $this->redirect('/admin/users?role=shop&agent_id='.$agent_id);
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            } else {
                $options = array(
                    'conditions' => array(
                        'User.'.$this->User->primaryKey => $id));
                $this->request->data = $this->User->find('first', $options);
            }
            $this->set('title_for_layout', SHOP_OWNER_MANAGEMENT);
        } else if ($role == 'agent') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            if ($this->request->is(array('post', 'put'))) {
                if ($this->User->save($this->request->data)) {
                    return $this->redirect('/admin/users?role=agent');
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            } else {
                $options = array(
                    'conditions' => array(
                        'User.'.$this->User->primaryKey => $id));
                $this->request->data = $this->User->find('first', $options);
            }
            $this->set('title_for_layout', AGENT_MANAGEMENT);
        } else if ($role == 'shop') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            if ($this->request->is(array('post', 'put'))) {
                if ($this->User->save($this->request->data)) {
                    return $this->redirect('/admin/users?role=shop');
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            } else {
                $options             = array(
                    'conditions' => array(
                        'User.'.$this->User->primaryKey => $id));
                $this->request->data = $this->User->find('first', $options);
            }
            $this->set('title_for_layout', SHOP_OWNER_MANAGEMENT);
        } else {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            if ($this->request->is(array('post', 'put'))) {
                if ($this->User->save($this->request->data)) {
                    return $this->redirect('/admin/users');
                } else {
                    $this->Flash->set(__(USER_COULD_NOT_BE_SAVE));
                }
            } else {
                $options             = array(
                    'conditions' => array(
                        'User.'.$this->User->primaryKey => $id));
                $this->request->data = $this->User->find('first', $options);
            }
            $this->set('title_for_layout', USER_MANAGEMENT);
        }
    }
    /*
     * Function admin_view
     * Modified 11/ November/2015
     * Channeth
     */
    public function admin_view($id = null)
    {
        if ($this->request->is('ajax')) {
            
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__(INVALID_USER));
        }
        $option = array(
            'conditions' => array(
                'User.'.$this->User->primaryKey => $id));
        $this->set('user', $this->User->find('first', $option));
        $this->set('title_for_layout', USER_MANAGEMENT);
    }
    /*
     * Function admin_delete_agent
     * Modified 10/ November/2015
     * Channeth
     */

    public function admin_delete($id = null)
    {
        $role     = $this->request->query('role');
        $agent_id = $this->request->query('agent_id');
        if (isset($agent_id) && $role == 'shop') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            $this->User->saveField('status', 0);
            return $this->redirect('/admin/users?role=shop&agent_id='.$agent_id);
        } else if ($role == 'agent') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            $this->User->saveField('status',0);
            return $this->redirect('/admin/users?role=agent');
        } else if ($role == 'shop') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            $this->User->saveField('status',0);
            return $this->redirect('/admin/users?role=shop');
        } else {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            $this->User->saveField('status', 0);
            return $this->redirect('/admin/users');
        }
    }

    /*
     * Function admin_activate_agent
     * Modified 11/ November/2015
     * Channeth
     */

    public function admin_activate($id = null)
    {
        $role     = $this->request->query('role');
        $agent_id = $this->request->query('agent_id');
        if (isset($agent_id) && $role == 'shop') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            $this->User->updateAll(
                array('status' => 1), array('id' => $id));
            return $this->redirect('/admin/users?role=shop&agent_id='.$agent_id);
        } else if ($role == 'agent') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            $this->User->updateAll(
                array('status' => 1), array('id' => $id));
            return $this->redirect('/admin/users?role=agent');
        } else if ($role == 'shop') {
            $this->User->id = $id;
            if (!$this->User->exists()) {
                throw new NotFoundException(__(INVALID_USER));
            }
            $this->User->updateAll(
                array('status' => 1), array('id' => $id));
            return $this->redirect('/admin/users?role=shop');
        }
    }

    public function login()
    {
        $this->layout = false;
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $user = $this->Session->read('Auth.User');
                $this->User->id = $user['id'];
                $this->User->set(array(
                    'last_time_login' => date('Y-m-d H:i:s')
                ));
                $this->User->save();
                // did they select the remember me checkbox?
                if ($this->request->data['User']['remember_me'] == 1) {
                    // remove "remember me checkbox"
                    unset($this->request->data['User']['remember_me']);

                    // hash the user's password
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);

                    // write the cookie
                    $this->Cookie->write('remember_me_cookie', $this->request->data['User'], true, '2 weeks');
                }
                if ($user['role'] =='admin') {
                    return $this->redirect('/admin/users?role=shop');
                } elseif ($user['role'] == ROLE_SHOP) {
                    return $this->redirect('/records');
                } elseif ($user['role'] == ROLE_HEADQUARTER) {
                    return $this->redirect('/records/');
                }
            }
            $this->Session->setFlash(__(INVALID_USERNAME_PASSWORD));
        }
    }
    /*
     * Function logout
     * Modified 10/ November/2015
     * Channeth
     */

    public function logout()
    {
        $this->Cookie->delete('remember_me_cookie');
        return $this->redirect($this->Auth->logout());
    }

    public function forgot_password()
    {
        $this->layout = false;
        if ($this->request->is('post')) {
            $email = $this->request->data['User']['email'];
            $user  = $this->User->find('all',
                array(
                'conditions' => array('email' => $email),
                'recursive' => -1
            ));
            if (count($user) == 0) {
                $this->Flash->set(EMAIL_NOT_EXIST);
                return false;
            }
            $token          = md5(rand(time(), true));
            $this->User->id = $user[0]['User']['id'];
            if ($this->User->save(array('User' => array('token' => $token)))) {
                $CakeEmail = new CakeEmail();
                $CakeEmail->from(EMAIL_FROM);
                $CakeEmail->to($email);
                $CakeEmail->subject(EMAIL_SUBJECT_RESET_PASSWORD);
                $CakeEmail->template('forget_password');
                $CakeEmail->viewVars(array(
                    'token' => $token
                ));
                if ($CakeEmail->send()) {
                    $this->User->saveField('password', null);
                    $this->Flash->set(EMAIL_HAS_BEEN_SENT);
                }
                $CakeEmail->reset();
            }
        }
    }

    public function reset_password()
    {
        $this->layout = false;
        if ($this->request->is('post')) {
            $token = $this->request->query('token');
            $user = $this->User->find('first',
                array(
                'conditions' => array('token' => $token),
                'recursive' => -1
            ));
            $this->User->id = $user['User']['id'];
            $password = $this->request->data['User']['password'];
            $confirm_password = $this->request->data['User']['confirm_password'];
            if ($password != $confirm_password) {
                $this->Flash->set(PASSWORD_MISMATCH);
                return false;
            }
            if (strlen($password) < 6) {
                $this->Flash->set(PASSWORD_AT_LEAST_6CHARACTORS);
                return false;
            }
            if ($this->User->saveField('password', $password)) {
                $this->User->saveField('token', null);
                $this->Flash->set(PASSWORD_HAS_BEEN_RESETED);
            } else {
                $this->Flash->set(PASSWORD_COULD_NOT_RESET);
            }
        }
    }

    //Function For Administrator
    public function admin_user_setting() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'delete':
                    echo $this->user_shop_delete();
                    return false;
                case 'edit':
                    echo $this->user_setting_edit();
                    return false;
                case 'detail':
                    echo $this->user_setting_detail();
                    return false;
                case 'change_password':
                    echo $this->user_setting_change_password();
                    return false;
            }
        }
    }
    //Function for admin list shop
    public function admin_user_shop_list()
    {
        $this->User->recursive = -1;
        $page = 1;
        if ($this->request->query('page')) {
            $page = $this->request->query('page');
        }
        $user_list = $this->User->find('all',
            array(
            'fields' => array('*'),
            'conditions' => array('User.status' => 1 ,'User.role in' => array(ROLE_SHOP, ROLE_HEADQUARTER)),
            'joins' => array(
                array(
                    'table' => 'shops',
                    'alias' => 'Shop',
                    'type' => 'inner',
                    'conditions' => array('User.id = Shop.user_id')
                )
            ),
            'contain' => array('Shop'),
            'limit' => PAGE_LIMIT,
            'page' => $page
        ));
        $this->set('user_list', $user_list);
        $this->layout = 'ajax';
    }
    //Function admin create new shop user
    public function admin_create_user_shop()
    {
        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->autoRender = false;
            $exist_user = $this->User->find('count',
                array(
                'conditions' => array('username' => $this->request->data['User']['username']),
                'recursive' => -1
            ));
            if ($exist_user > 0) {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'This username is already exit'
                ));
            }
            $data   = array('User' => array(
                    'username' => $this->request->data['User']['username'],
                    'email' => $this->request->data['User']['email'],
                    'password' => $this->request->data['User']['password'],
                    'role' => $this->request->data['User']['role'],
                    'status' => 1
                ),
            );
            if ($this->request->data['User']['role'] === ROLE_HEADQUARTER) {
                $data['User']['company_name'] = $this->request->data['User']['company_name'];
                $data['User']['contact'] = $this->request->data['User']['contact'];
                $data['User']['address'] = $this->request->data['User']['address'];
            }
            $this->User->create();
            if ($this->User->save($data)) {
                    //Update user role as SHOP
                    $data_shop = array('Shop' => array(
                        'user_id' => $this->User->getLastInsertId(),
                        'android_key' => $this->request->data['Shop']['android_key'],
                        'hours_start' => '00:00',
                        'hours_end' => '00:00'
                    ));
                    $this->Shop->create();
                    if($this->Shop->save($data_shop)){
                        if (!empty($this->request->data['Shop']['ios_ck_file']['name'])) {
                            $ios_ck_file_name = $this->Shop->getLastInsertId() . '_' . $this->request->data['Shop']['ios_ck_file']['name'];
                            $destFile = WWW_ROOT.'ios_push'.DS.'production/' . $ios_ck_file_name;
                            if (move_uploaded_file($this->request->data['Shop']['ios_ck_file']['tmp_name'], $destFile)) {
                                chmod($destFile, 0755);
                                $this->Shop->save(array(
                                    'id' => $this->Shop->getLastInsertId(),
                                    'ios_ck_file' => $ios_ck_file_name,
                                ));
                            }
                        }
                        $data_stampsetting = array('StampSetting'=>array(
                          'shop_id'=> $this->Shop->getLastInsertId(),
                          'stamp_number'=>10,
                          'app_installation'=>0,
                          'app_startup'=>0,
                          'visit' =>0,
                          'valid_date'=>10
                        ));
                    $this->StampSetting->create();
                    $this->StampSetting->save($data_stampsetting);
                    $arr_function_name = array(
                        'お知らせ',
                        'Myカルテ',
                        '会員証',
                        'Web予約',
                        'クーポン',
                        'スタンプ',
                        '店舗情報',
                        'スタッフ',
                        'メニュー',
                        'フォト',
                        'SNS',
                        '設定',
                        '店舗一覧',
                    );
                    $arr_function_tag = array(
                        'notice',
                        'my_medical_record',
                        'membership',
                        'web_reservations',
                        'coupon',
                        'stamp',
                        'store_information',
                        'staff',
                        'app_menu',
                        'photo_gallery',
                        'sns_share',
                        'setting',
                        'list_shop',
                    );
                    $count =1;
                    for($i=0; $i <count($arr_function_tag); $i++){
                        $data_setting = array('Setting'=>array(
                            'shop_id' => $this->Shop->getLastInsertId(),
                            'function_image' => 'menu'.($count).'.png',
                            'function_name' => $arr_function_name[$i],
                            'function_tag' => $arr_function_tag[$i],
                            'function_index' => ($count),
                            'active'=>1
                       ));
                        $this->Setting->create();
                        $this->Setting->save($data_setting);
                        $count++;
                    }
                  }
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'success create user'
                ));
            } else {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'User could not save'
                ));
            }
        }
    }

    public function admin_edit_user_shop()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $user  = $this->User->findById($this->request->data['User']['user_id']);
            $shop = $this->Shop->findByUserId($user['User']['id']);

            $username_existed = $this->User->find('count',
                array(
                'conditions' => array(
                    'username' => $this->request->data['User']['username'],
                    'status' => 1
                )
            ));
            if ($username_existed > 0 && $user['User']['username'] != $this->request->data['User']['username']) {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'This username is already exit'
                ));
            }
            if (empty($this->request->data['User']['password'])) {
                unset($this->request->data['User']['password']);
            }
            if (empty($this->request->data['Shop']['android_key'])) {
                unset($this->request->data['Shop']['android_key']);
            }
            if (!isset($this->request->data['Shop']['ios_ck_file']['name'])) {
                unset($this->request->data['Shop']['ios_ck_file']);
            } else {
                $ios_ck_file = $shop['Shop']['id'] . '_' . $this->request->data['Shop']['ios_ck_file']['name'];
                $target = WWW_ROOT.'ios_push'.DS.'production/' . $ios_ck_file;

                if (move_uploaded_file($this->request->data['Shop']['ios_ck_file']['tmp_name'], $target)) {
                    chmod($target, 0755);
                    $this->request->data['Shop']['ios_ck_file'] = $ios_ck_file;
                }
            }

            $this->User->id = $user['User']['id'];
            if ($this->User->save($this->request->data)) {
                $this->Shop->id = $shop['Shop']['id'];
                if ($this->Shop->save($this->request->data)) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Shop has been updated'
                    ));
                }
            }
        }
    }

    public function user_shop_delete()
    {
        $this->loadModel('Shop');
        $id = $this->request->query('user_id');
        $del_physical = $this->request->query('del_physical');
        $shop_id = $this->request->query('shop_id');
        $this->Shop->id = $shop_id;
        $this->User->id = $id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }
        if ($del_physical == 1) {
            if ($this->User->delete($id)) {
                $this->UserShop->delete($id);
            }
        } else {
            if ($this->User->saveField('status', 0)) {
                $this->Shop->saveField('is_deleted', 1);
            }
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Delete successful'
        ));
    }
    //---------------------------------Function for user
    public function user_setting()
    {
        $this->loadModel('Area');
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            switch ($action) {
                case 'save':
                    echo $this->user_setting_create();
                    return false;
                case 'delete':
                    echo $this->user_setting_delete();
                    return false;
                case 'edit':
                    echo $this->user_setting_edit();
                    return false;
                case 'detail':
                    echo $this->user_setting_detail();
                    return false;
                case 'change_password':
                    echo $this->user_setting_change_password();
                    return false;
            }
        }
        $this->Shop->recursive = -1;
        $getShop = $this->Shop->find('all',array(
            'conditions' => array('Shop.user_id' => $this->Auth->user('id'))));
        $conditions['AND'] = array('User.status' => 1);
        if (!empty($getShop)) {
            $conditions['AND'] = array('UserShop.shop_id' => Hash::extract($getShop,'{n}.Shop.id'));
        }
        $this->User->recursive = -1;
        $user_list = $this->User->find('all',
            array(
            'fields' => array('*'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'left',
                    'conditions' => array('UserShop.user_id = User.id','UserShop.is_disabled=0')
                )
            ),
            'contain' => array('UserShop'),
        ));

        $this->set('user_list', $user_list);
        $area    = $this->Area->find('list');
        $this->set('areas', $area);
        $shop_id = $this->User->query('SELECT shops.id,shops.shop_name FROM users inner JOIN shops ON users.id = shops.user_id '
            .'WHERE users.id="'.$this->Auth->user('id').'" and (shops.is_deleted is null OR shops.is_deleted <> 1)');
        $this->set('shops', $shop_id);
        $this->set('current_user', $this->Auth->User('id'));
    }

    public function user_setting_list()
    {
        $this->Shop->recursive = -1;
        $getShop = $this->Shop->find('all',array(
            'conditions' => array('Shop.user_id' => $this->Auth->user('id'))));
        $conditions['AND'] = array('User.status' => 1);
        if (!empty($getShop)) {
            $conditions['AND'] = array('UserShop.shop_id' => Hash::extract($getShop,'{n}.Shop.id'),'User.status' => 1);
        }
        $this->User->recursive = -1;
        $user_list = $this->User->find('all',
            array(
            'fields' => array('*'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'left',
                    'conditions' => array('UserShop.user_id = User.id','UserShop.is_disabled=0')
                )
            ),
            'contain' => array('UserShop'),
        ));
        $this->set('user_list', $user_list);
        $shop_id = $this->User->query('SELECT shops.id,shops.shop_name FROM users inner JOIN shops ON users.id = shops.user_id '
            .'WHERE users.id="'.$this->Auth->user('id').'" and (shops.is_deleted is null OR shops.is_deleted <> 1)');
        $this->set('shops', $shop_id);
        $this->set('current_user', $this->Auth->User('id'));
        $this->layout = 'ajax';
    }

    public function user_setting_create()
    {
        $this->loadModel('UserShop');
        $username   = $this->request->query('username');
        $exist_user = $this->User->find('count',
            array(
            'conditions' => array('username' => $username),
            'recursive' => -1
        ));
        if ($exist_user > 0) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'This username is already exit'
            ));
        }
        //Model_id radom
        $random_id_length = 6;
        $rnd_id = uniqid(rand(), 1);
        $rnd_id = strip_tags(stripslashes($rnd_id));
        $rnd_id = str_replace(".", "", $rnd_id);
        $rnd_id = strrev(str_replace("/", "", $rnd_id));
        $rnd_id = substr($rnd_id, 10, $random_id_length);
        $data = array('User' => array(
                'username' => $this->request->query('username'),
                'email' => $this->request->query('email'),
                'password' => $this->request->query('password'),
                'model_id' => $rnd_id,
                'is_news_notification' => 1,
                'is_medical_notification' => 1,
                'role' => 'user',
                'status' => 1
            ),
        );
        $this->User->create();
        if ($this->User->save($data)) {
            $last_id = $this->User->id;
            $data_user_shop = array('UserShop' => array(
                    'shop_id' => $this->request->query('shop_id'),
                    'user_id' => $last_id,
                    'type' => 'shop',
                    'is_allow_notification' => 'news',
                    'is_disabled' => 0
            ));
            if ($this->UserShop->save($data_user_shop)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'User has been saved'
                ));
            }
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'User could not save'
            ));
        }
    }

    public function user_setting_delete()
    {
        $this->loadModel('UserShop');
        $id = $this->request->query('user_id');
        $del_physical = $this->request->query('del_physical');
        $shop_id = $this->request->query('shop_id');
        $is_allow_notification = $this->request->query('is_notification');
        $this->UserShop->user_id = $id;
        $this->User->id = $id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }
        if ($del_physical == 1) {
            if ($this->User->delete($id)) {
                $this->UserShop->delete($id);
            }
        } else {
            if ($this->User->saveField('status', 0)) {
                $this->UserShop->query('Delete From user_shops where user_id="'.$id.'"');
                $data = array('UserShop' => array(
                        'shop_id' => $shop_id,
                        'user_id' => $id,
                        'type' => 'shop',
                        'is_allow_notification' => $is_allow_notification,
                        'is_disabled' => 1
                ));
                $this->UserShop->save($data);
            }
        }
        return json_encode(array(
            'result' => 'success',
            'msg' => 'Delete successful'
        ));
    }

    public function user_setting_edit()
    {
        $username = $this->request->query('username');
        $new_pwd = $this->request->query('password_update');
        $id = $this->request->query('user_id');
        $user_data = $this->User->findById($id);
        $old_pwd = $user_data['User']['password'];
        $exist_user = $this->User->find('count',
            array(
            'conditions' => array(
                'username' => $username,
                'status  ' => 1
            )
        ));
        if ($exist_user > 0 && $user_data['User']['username'] != $username) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'This username is already exit'
            ));
        }
        if (strlen($new_pwd) < 6 && strlen($new_pwd) > 0) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Password must be at least 6 characters'
            ));
        }
        $password = $old_pwd;
        if ($new_pwd != "") {
            $password = $new_pwd;
        }
        $data = array('User' =>
            array(
                'username' => $this->request->query('username'),
                'email' => $this->request->query('email'),
                'password' => $password
            )
        );
        $this->User->id = $id;
        if ($this->User->save($data)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'User has been updated'
            ));
        }
    }

    public function user_setting_detail()
    {
        $user_id = $this->request->query('user_id');
        $this->User->id = $user_id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid ID'
            ));
        }
        $data = $this->User->findById($user_id);
        if (!empty($data)) {
            return json_encode(array(
                'result' => 'success',
                'data' => array(
                    'username' => $data['User']['username'],
                    'email' => $data['User']['email']
                )
            ));
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'User not found'
            ));
        }
    }

    //Change password for current login user
    public function user_setting_change_password()
    {
        $user_id = $this->Auth->User('id');
        $this->User->id = $user_id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        $user_data  = $this->User->findById($user_id);
        $pwd_input  = $this->request->query('current_pwd');
        $store_hash = $user_data['User']['password'];
        $new_hash   = Security::hash($pwd_input, 'blowfish', $store_hash);
        //if $correct = 0 hash is match
        $correct    = strcmp($store_hash, $new_hash);
        if ($correct != 0) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid password'
            ));
        }
        $new_pwd = $this->request->query('new_pwd');
        $confirm_password = $this->request->query('confirm_pwd');

        if ($new_pwd != $confirm_password) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Password mismatchs'
            ));
        }
        $data_update = array('User' => array(
                'password' => $new_pwd
        ));
        if ($this->User->save($data_update)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Password has been changed'
            ));
        }
    }

    public function create() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            
            $shop_id = null;

            if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
                $shop_id = $this->request->data['User']['shop_id'];
            } else if ($this->Auth->user('role') === ROLE_SHOP) {
                $this->loadModel('Shop');
                $shops = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                $shop_id = $shops['Shop']['id'];
            }
            
            $this->User->create();
            $this->request->data['User']['model_id'] = $this->User->generateUniqueModelId();
            $this->request->data['User']['user_code'] = $this->User->generateUniqueUserCode();
            $this->request->data['User']['role'] = ROLE_USER;
            $user = $this->User->save($this->request->data);
            if ($user) {
                $data_user_shop = array('UserShop' => array(
                    'shop_id' => $shop_id,
                    'user_id' => $this->User->id,
                    'type' => ROLE_SHOP,
                    'is_allow_notification' => 1
                ));
                $this->UserShop->create();
                $this->UserShop->save($data_user_shop);

                echo json_encode($user);
            }
        }
    }

    public function admin_headquarter()
    {
        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
        }
        $conditions = array(
            'role' => ROLE_HEADQUARTER,
            'status' => 1
        );
        if ($this->request->query('keyword')) {
            $keyword = $this->request->query('keyword');
            $conditions['OR'] = array(
                'username like' => '%'.$keyword.'%',
                'firstname like' => '%'.$keyword.'%',
                'lastname like' => '%'.$keyword.'%',
                'lastname_kana like' => '%'.$keyword.'%',
                'firstname_kana like' => '%'.$keyword.'%',
                'contact like' => '%'.$keyword.'%'
            );
        }
        $users = $this->User->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));

        $this->set(array('data' => $users));
    }

    public function admin_selectUser()
    {
        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
        }
        $this->loadModel('Shop');

        $headquarter = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->request->query('headquarter_id'),
                'role' => ROLE_HEADQUARTER,
                'status' => 1,
            ),
            'recursive' => -1
        ));
        if (!$headquarter) {
            throw new NotFoundException();
        }

        $keyword = $this->request->query('keyword');
        $lst_user_id = $this->User->find('list', array(
            'conditions' => array(
                'role' => ROLE_SHOP,
                'status' => 1,
                'parent_id' => null,
            ),
            'recursive' => -1
        ));

        $conditions = array(
            'Shop.user_id' => $lst_user_id,
            'Shop.is_deleted <>' => 1,
        );

        if ($keyword) {
            $conditions['OR'] = array(
                'Shop.shop_name like' => '%'.$keyword.'%',
                'Shop.shop_kana like' => '%'.$keyword.'%',
                'Shop.address like' => '%'.$keyword.'%',
                'Shop.phone like' => '%'.$keyword.'%'
            );
        }

        $shop = $this->Shop->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));

        $this->set(array(
            'data' => $shop,
            'headquarter_user' => $headquarter,
        ));

    }

    public function admin_user_detail($id = null)
    {
        if ($this->request->is('ajax')) {
            $this->layout = 'ajax';
        }
        if($id) {
            $data = $this->User->findById($id);
            $this->set(array('data' => $data['User']));
        }
    }
    public function admin_confirm()
    {
        $this->loadModel('Shop');

        $headquarter = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->request->data('headquarter_id'),
                'role' => ROLE_HEADQUARTER,
                'status' => 1,
            ),
            'recursive' => -1
        ));

        if (!$headquarter) {
            throw new NotFoundException();
        }

        $shop = $this->Shop->find('all', array(
            'conditions' => array(
                'Shop.id'=> $this->request->data('shop_id')
            )
        ));
        if ($this->request->is('post') && $this->request->data('save')) {
            foreach ($this->request->data('user_id') as $key => $value) {
                $this->User->id = $value;
                $this->User->saveField('parent_id', $this->request->data('headquarter_id'));
            }
            $this->Session->setFlash('Success.', 'success');
            $Email = new CakeEmail();
            $Email->from(array('noreply@conexi.com' => 'Conexi'));
            $Email->to($headquarter['User']['email']);
            $Email->subject('Confirm');
            $Email->send('本社アカウントと店舗が紐付けされました。
管理画面にアクセスの上、承認してください。
URL：'.Router::url('/', true).'users/confirm/');
            $this->redirect(array('controller' => 'Users', 'action' => 'admin_headquarter'));
        }
        $this->set(array('selected_shop' => $shop, 'headquarter_user' => $headquarter,));

    }

    public function confirm()
    {
        if($this->Auth->user('role') !== ROLE_HEADQUARTER) {
            throw new NotFoundException();
        }
        $this->loadModel('Shop');
        $keyword = $this->request->query('keyword');
        if ($this->request->is('post')) {
            foreach ($this->request->data('user_id') as $key => $value) {
                $this->User->id = $value;
                if ($this->request->data('rejected')) {
                    $this->User->saveField('parent_id', null);
                } else {
                    $this->User->saveField('confirmed', 1);
                }
            }
            $this->Session->setFlash('Success.', 'success');
        }
        $users = $this->User->find('list', array(
            'conditions' => array(
                'User.parent_id' => $this->Auth->user('id'),
                'User.confirmed <>' => 1
            ),
            'recursive' => -1
        ));

        $conditions = array(
            'Shop.user_id' => $users,
            'Shop.is_deleted <>' => 1,
        );
        if ($keyword) {
            $conditions['OR'] = array(
                'Shop.shop_name like' => '%'.$keyword.'%',
                'Shop.shop_kana like' => '%'.$keyword.'%',
                'Shop.address like' => '%'.$keyword.'%',
                'Shop.phone like' => '%'.$keyword.'%'
            );
        }

        $shops = $this->Shop->find('all', array(
            'conditions' => $conditions
        ));

        $this->set(array('data' => $shops));
    }

    public function admin_get_edit()
    {
        if ($this->request->is('ajax')) {
            $this->User->recursive = -1;
            $user = $this->User->find('first', array(
                'fields' => array('User.*', 'Shop.*'),
                'conditions' => array('User.id' => $this->request->query('id')),
                'joins' => array(
                    array(
                        'table' => 'shops',
                        'alias' => 'Shop',
                        'type' => 'INNER',
                        'conditions' => array('Shop.user_id = User.id')
                    )
                ),
                'recursive' => -1
            ));

            if ($user) {
                unset($user['User']['password']);
                $this->request->data = $user;
                $this->set(compact('user'));
            }
        }
        $this->layout = 'ajax';
    }

    public function admin_update_user()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->disableCache();

            $request = $this->request->data;

            if (empty($request['User']['password'])) {
                unset($request['User']['password']);
            }

            $err = [];
            $file = '';
            if (!empty($request['Shop']['ios_ck_file']['name'])) {
                list($err, $file) = $this->ios_file_upload($request);
            }

            $this->User->set($request);
            if ($this->User->validates() && empty($err)) {
                $this->User->id = $request['User']['id'];
                $user = $this->User->save($request, false);
                
                if ($user) {
                    $this->loadModel('Shop');
                    if ($file) {
                        $request['Shop']['ios_ck_file'] = $file;
                    } else {
                        unset($request['Shop']['ios_ck_file']);
                    }
                    $this->Shop->id = $request['Shop']['id'];                    
                    $this->Shop->save($request, false);
                }

                return json_encode(array(
                    'status' => 'OK'
                ));
            }
            
            $error = $this->User->validationErrors;
            if (!empty($err['ios_ck_file'])) {
                $error = array_merge($error, $err);
            }
            return json_encode(array(
                'status' => 'ERROR',
                'message' => $error
            ));            
        }
    }
    
    private function ios_file_upload($request)
    {
        $msg = [];
        $file_name = '';
        if (!empty($request['Shop']['ios_ck_file']['name'])) {
            $ext = explode('.', $request['Shop']['ios_ck_file']['name']);
            $file_type = array('pem', 'ppk');
            if ((in_array(strtolower($ext[1]), $file_type))) {
                $file_name = date('YmdHis') . '_' . md5($ext[0]) . '.' . $ext[1];
                $destination = WWW_ROOT . 'ios_push' . DS . 'production/' . $file_name;
                if (move_uploaded_file($request['Shop']['ios_ck_file']['tmp_name'], $destination)) {
                    chmod($destination, 0755);
                    if (!empty($request['Shop']['old_file'])) {
                        $old_file = WWW_ROOT . 'ios_push' . DS . 'production/' . $request['Shop']['old_file'];
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                } else {
                    $msg['ios_ck_file'] = 'File upload error, please try again.';
                }
            } else {
                $msg['ios_ck_file'] = 'File not support.';
            }            
        }
        return array($msg, $file_name);
    }
}