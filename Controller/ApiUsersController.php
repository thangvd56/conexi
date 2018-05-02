<?php
App::uses('File', 'Utility', 'User');

class ApiUsersController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'index',
            'login',
            'signup',
            'is_model_exist',
            'get_area',
            'update_membership',
            'update_device_token',
            'update_is_allow_notification',
            'check_agreement',
            'update_agreement',
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->loadModel('User');
        $users = $this->User->find('all');
        echo json_encode(array(
            'users' => $users
        ));
        $this->autoRender = false;
    }

    public function login()
    {
        $this->loadModel('User');
        $this->loadModel('UserShop');

        $model_id = $this->User->generateUniqueModelId();
        $user_code = $this->User->generateUniqueUserCode();

        $user_data = array(
            'model_id' => $model_id,
            'role' => ROLE_USER,
            'user_code' => $user_code
        );

        if ($this->User->save($user_data)) {
            $users = $this->User->find('first', array(
                'conditions' => array('User.model_id' => $model_id),
                'recursive' => -1,
            ));

            $user_shop = array(
                'user_id' => $users['User']['id'],
                'shop_id' => $this->request->data('shop_id'),
                'type' => 'shop',
                'is_allow_notification' => true,
                'is_disabled' => 0
            );
            $this->UserShop->save($user_shop);

            echo json_encode(array(
                'id' => $users['User']['id'],
                'model_id' => $model_id,
                'success' => 1,
                'message' => 'Sucessful',
                'user_code' => $user_code
            ));
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Login failed',
            ));
        }
        $this->autoRender = false;
    }

    public function signup()
    {
        $this->layout = null;
        $this->autoRender = false;

        if ($this->request->is(array('post', 'get'))) {
            $this->loadModel('User');
            $this->loadModel('Log');

            $memberShip_id = $this->request->data('membership_id');
            $firstname = $this->request->data('firstname');
            $lastname = $this->request->data('lastname');
            $lastname_kana = $this->request->data('lastname_kana');
            $firstname_kana = $this->request->data('firstname_kana');
            $contact = $this->request->data('contact');
            $email = $this->request->data('email');
            $area_id = $this->request->data('area_id');
            $birthdate = empty($this->request->data('birthdate')) ? null : $this->request->data('birthdate');
            $sex = $this->request->data('sex');
            $model_id = $this->request->data('model_id');
            $user_id = $this->request->data('user_id');

            if (empty($firstname) || empty($lastname)) {
                return json_encode(array(
                    'success' => 0,
                    'message' => 'First Name and Last Name are require.',
                ));
            }

            $user = $this->User->find('count', array(
                'conditions' => array(
                    'User.model_id' => h($model_id),
                ),
                'recursive' => -1
            ));

            if ($user == 1) {
                $data = $this->User->updateAll(array(
                    'User.firstname' => "'" . $firstname . "'",
                    'User.lastname' => "'" . $lastname . "'",
                    'User.firstname_kana' => "'" . $firstname_kana . "'",
                    'User.lastname_kana' => "'" . $lastname_kana . "'",
                    'User.contact' => "'" . $contact . "'",
                    'User.email' => "'" . $email . "'",
                    'User.area_id' => empty($area_id) ? NULL : $area_id,
                    'User.status' => 1,
                    'User.is_install_app' => 0,
                    'User.birthday' => "'" . $birthdate . "'",
                    'User.gender' => "'" . $sex . "'",
                    'User.completed' => 1,
                    'User.membership_id' => "'" . $memberShip_id . "'",
                ),
                array(
                    'User.model_id' => h($model_id),
                ));

                if ($data) {
                    // log table
                    $log = array(
                        'user_id' => $user_id,
                        'type' => 'register',
                        'value' => $model_id
                    );
                    $this->Log->save($log);

                    echo json_encode(array(
                        'success' => 1,
                        'message' => 'successfully update',
                    ));
                } else {
                    echo json_encode(array(
                        'success' => 0,
                        'message' => 'update failed',
                    ));
                }
            } else {
                echo json_encode(array(
                    'success' => 2,
                    'message' => 'Model id deos not match!',
                ));
            }
        }
    }

    public function is_model_exist()
    {
        $this->loadModel('User');
        $this->loadModel('Log');
        $this->loadModel('UserShop');

        $model_id = $this->request->query('model_id');
        $type = $this->request->query('type');
        $shop_id = $this->request->query('shop_id');
        $user_code = $this->request->query('user_code');
        $user_id = $this->request->query('user_id');
        $user_code_rnd = '';
        $conditions = array();

        $user_model_id_check = array();

        if ($type) {
            $conditions = array(
                'User.model_id' => $model_id,
                'User.user_code' => $user_code,
            );
        } else {
            $user_model_id_check = $this->User->find('first', array(
                'conditions' => array(
                    'User.model_id' => $model_id,
                ),
                'recursive' => -1,
                'fields' => 'model_id',
            ));
            $conditions = array(
                'User.id' => $user_id,
                'User.model_id' => $model_id,
            );
        }

        $user = $this->User->find('first', array(
            'conditions' => $conditions,
        ));

        if ($user) {
            $user_shop = $this->UserShop->find('first', array(
                'conditions' => array(
                    'user_id' => $user['User']['id'],
                    'shop_id' => $shop_id
                ),
                'recursive' => -1
            ));

            $arr_result = array(
                'completed' => $user['User']['completed'],
                'status' => $user['User']['status'],
                'success' => 1,
                'message' => 'Successful'
            );

            if ($user['User']['status'] == TRUE && $user_shop) {
                if ($type == 0) {
                    if ($user['User']['user_code'] !== '') {
                        $user_code_rnd = $user['User']['user_code'];
                        $arr_result['user_code'] = $user['User']['user_code'];
                    } else {
//                        $user_code_rnd = uniqid(rand(), 1);
//                        $user_code_rnd = strip_tags(stripslashes($user_code_rnd));
//                        $user_code_rnd = str_replace('.', '', $user_code_rnd);
//                        $user_code_rnd = strrev(str_replace('/', '', $user_code_rnd));
//                        $user_code_rnd = substr($user_code_rnd, 10, 5);
                        $user_code_rnd = $this->User->generateUniqueUserCode();

                        $get_user_data = $this->User->find('first', array(
                            'conditions' => array(
                                'User.model_id' => $model_id,
                            ),
                            'recursive' => -1,
                        ));
                        $update_user_code = array(
                            'user_code' => $user_code_rnd,
                            'id' => $get_user_data['User']['id'],
                        );
                        if ($this->User->save($update_user_code)) {
                            $arr_result['user_code'] = $user_code_rnd;
                        }
                    }
                    echo json_encode($arr_result);
                } else if ($type == 1) {
//                    $model_id_rnd = uniqid(rand(), 1);
//                    $model_id_rnd = strip_tags(stripslashes($model_id_rnd));
//                    $model_id_rnd = str_replace(".", "", $model_id_rnd);
//                    $model_id_rnd = strrev(str_replace("/", "", $model_id_rnd));
//                    $model_id_rnd = substr($model_id_rnd, 10, 6);
                    $model_id_rnd = $this->User->generateUniqueModelId();

                    $log = array(
                        'user_id' => $user['User']['id'],
                        'type' => 'old_model_id',
                        'value' => $user['User']['model_id'],
                    );

                    if ($this->Log->save($log)) {
                        $update_model = array(
                            'model_id' => $model_id_rnd,
                            'id' => $user['User']['id'],
                        );
                        if ($this->User->save($update_model)) {
                            echo json_encode(array(
                                'success' => 1,
                                'message' => 'success',
                                'model_id' => $model_id_rnd,
                                'completed' => $user['User']['completed'],
                                'status' => $user['User']['status'],
                                'id' => $user['User']['id'],
                            ));
                        }
                    } else {
                        echo json_encode(array(
                            'success' => 0,
                            'message' => 'Internal Erorr'
                        ));
                    }
                }
            } else {
                if ($type == 0) {
                    $arr_result['status'] = FALSE;
                    echo json_encode($arr_result);
                } else {
                    echo json_encode(array(
                        'success' => 0,
                        'message' => 'User Shop Invalid'
                    ));
                }
            }
        } else {
            if ($user_model_id_check) {
                echo json_encode(array(
                    'success' => 0,
                    'message' => 'Invalid Model ID'
                ));
            } else {
                echo json_encode(array(
                    'success' => 2,
                    'message' => 'Invalid Model ID'
                ));
            }
        }
        $this->autoRender = FALSE;
    }

    public function get_area()
    {
        $this->loadModel('Area');
        $area = $this->Area->find('all');
        if ($area) {
            $arr = array();
            foreach ($area as $value) {
                $data = $value['Area'];
                $arr_value = array(
                    'id' => $data['id'],
                    'name' => $data['name']
                );
                array_push($arr, $arr_value);
            }
            echo json_encode(array(
                'area' => $arr,
                'success' => 1,
                'message' => 'Sucessful'
            ));
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Somthing Went Wrong',
            ));
        }
        $this->autoRender = false;
    }

    public function update_membership()
    {
        $this->loadModel('User');
        $user_id = $this->request->query('user_id');
        $membership_id = $this->request->query('membership_id');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'id' => $user_id
            ),
            'recursive' => -1
        ));
        if ($user) {
            if ($this->User->save(array('id' => $user_id, 'membership_id' => $membership_id))) {
                echo json_encode(array(
                    'user' => TRUE,
                    'success' => 1,
                    'message' => 'Successful'
                ));
            }
        } else {
            echo json_encode(array(
                'user' => FALSE,
                'success' => 0,
                'message' => 'Invalid USER ID'
            ));
        }

        $this->autoRender = FALSE;
    }

    public function update_device_token()
    {
        $token = $this->request->query('device_token');
        $model_id = $this->request->query('model_id');
        $platform = $this->request->query('platform');
        $this->loadModel('User');
        $user = $this->User->find('first', array(
            'fields' => array('*'),
            'conditions' => array(
                'model_id' => $model_id,
                'model_id <>' => NULL
            ),
            'recursive' => -1
        ));        
        
        if ($user) {
            $reservation_badge = $user['User']['reservation_badge'];
            $user_update = array('User' => array(
                'token' => $token,
                'platform_type' => $platform,
                'reservation_badge' => 0
                )
            );
          
            $this->User->id = $user['User']['id'];
            if ($this->User->save($user_update)) {
                echo json_encode(array(
                    'success' => 1,
                    'message' => 'Successful',
                    'data' => array(
                        'reservation_badge' => $reservation_badge,
                    ),
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Invalid model ID'
            ));
        }
        $this->autoRender = false;
    }

    public function update_is_allow_notification()
    {
        $is_allow_notification = $this->request->query('is_allow_notification');
        $is_allow_notification = $is_allow_notification == 'true' ? 1 : 0;
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->request->query('user_id');
        $this->loadModel('UserShop');

        $user_shop = $this->UserShop->find('first', array(
            'conditions' => array(
                'user_id' => $user_id,
                'shop_id' => $shop_id
            ),
            'recursive' => -1
        ));

        if ($user_shop) {
            $user_shop_update = array(
                'id' => $user_shop['UserShop']['id'],
                'is_allow_notification' => $is_allow_notification
            );
            if ($this->UserShop->save($user_shop_update)) {
                echo json_encode(array(
                    'success' => 1,
                    'message' => 'Successful'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Invalid User ID OR Shop ID'
            ));
        }
        $this->autoRender = FALSE;
    }

    public function update_agreement()
    {
        $this->autoRender = false;
        $this->loadModel('User');

        $user_id = $this->request->query('user_id');
        $rule = $this->request->query('is_agree_rule');
        $policy = $this->request->query('is_agree_policy');
        $is_agree_rule = $rule === 'true' ? 1 : 0;
        $is_gree_policy = $policy === 'true' ? 1 : 0;

        $data = array(
            'id' => $user_id,
            'is_agree_rule' => $is_agree_rule,
            'is_agree_policy' => $is_gree_policy,
        );
        if ($this->User->save($data)) {
            echo json_encode(array(
                'success' => 1,
                'message' => 'successful',
            ));
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Invalid User ID OR Shop ID',
            ));
        }
    }

    public function check_agreement()
    {
        $this->layout = null;
        $this->autoRender = false;
        $this->loadModel('User');

        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->request->query('user_id'),
            ),
            'fields' => array(
                'User.is_agree_rule',
                'User.is_agree_policy',
            ),
            'recursive' => -1,
        ));
        if ($user) {
            $is_agree_rule = $user['User']['is_agree_rule'] == 'true' ? '1' : '0';
            $is_agree_policy = $user['User']['is_agree_policy'] == 'true' ? '1' : '0';

            echo json_encode(array(
                'success' => 1,
                'message' => 'successful',
                'data' => array(
                    'is_agree_rule' => $is_agree_rule,
                    'is_agree_policy' => $is_agree_policy,
                )
            ));
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'invalid user id',
            ));
        }
    }
}