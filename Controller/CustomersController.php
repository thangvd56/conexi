<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('AppController', 'Controller');

class CustomersController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('');
        $this->Auth->authorize = 'Controller';
        $this->loadModel('User');
        $this->loadModel('Shop');
        $this->loadModel('UserShop');
    }

    public $helpers = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash');

    public function index()
    {
        $this->loadModel('Shop');
        $user_id = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id = $this->User->getHeadquarterUserId($this->Auth->user('id'));
        } else if ($this->Auth->user('role') === ROLE_SHOP) {
            $user_id = $this->Auth->user('id');
        } else {
            throw new NotFoundException('User Id not found.');
        }

        $shop = $this->Shop->getShopIdByUser($user_id);

        $this->loadModel('Area');
        $area = $this->Area->find('list');
        $this->set('shop', $shop);
        $this->set('area', $area);

        $this->Shop->recursive = -1;
        $getShop = $this->Shop->find('all', array(
            'conditions' => array('Shop.user_id' => $this->Auth->user('id'))
        ));

        $conditions['AND'] = array('User.status' => 1, 'User.role' => 'user');

        if (!empty($getShop)) {
            $conditions['AND'] = array('UserShop.shop_id' => Hash::extract($getShop, '{n}.Shop.id'));
        }

        $this->User->recursive = -1;
        $user = $this->User->find('all', array(
            'fields' => array('*'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'left',
                    'conditions' => array('UserShop.user_id = User.id')
                )
            ),
            'contain' => array('UserShop'),
        ));

        $this->set('user', $user);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action = $this->request->query('action');
            if (!$action) {
                $action = $this->request->data('action');
            }

            $shop_id = $this->request->query('shop_id');
            if (empty($shop_id)) {
                $id = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                $shop_id = $id['Shop']['id'];
            }
            switch ($action) {
                case 'customer_info':
                    echo $this->customer_info($shop_id);
                    break;
                case 'delete_customer':
                    echo $this->delete_customer();
                    break;
                case 'save':
                    echo $this->save_customer();
                    break;
                case 'delete_permanantly':
                    echo $this->delete_permanantly();
                    break;
                case 'revert_customer':
                    echo $this->revert_customer();
                    break;
                case 'add_tag':
                    echo $this->add_tag();
                    break;
                case 'edit_tag':
                    echo $this->edit_tag();
                    break;
                case 'delete_tag':
                    echo $this->delete_tag();
                    break;
                case 'checkin':
                    echo $this->check_in();
                    break;
                case 'set_title_image':
                    echo $this->set_title_image();
                    break;
                case 'fetch_tags':
                    echo $this->fetch_tags();
                    break;
                case 'send_mail':
                    echo $this->send_model_id();
                    break;
                case 'assign_tag_to_user':
                    echo $this->assign_tag_to_user();
                    break;
                case 'remove_tag_from_user':
                    echo $this->remove_tag_from_user();
                    break;
            }
        }
    }

    public function result_search()
    {
        if ($this->request->is('ajax')) {
            $this->loadModel('Shop');
            $shop_id = null;
            if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
                $shop_id = $this->request->query('shop_id');
            } else if ($this->Auth->user('role') === ROLE_SHOP) {
                $shop = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                if (!empty($shop)) {
                    $shop_id = $shop['Shop']['id'];
                }
            } else {
                throw new NotFoundException('User Id not found.');
            }

            $keyword = $this->request->query('keyword');
            $conditions = array(
                'User.role' => ROLE_USER,
                'User.status' => 1,
                'UserShop.is_disabled <>' => 1,
                'UserShop.shop_id' => $shop_id,
            );
            $count = $this->User->getCountCustomer($conditions);
            if ($this->request->query('action') == 'search') {
                if (!empty($keyword)) {
                    $key_search = trim($keyword);
                    $conditions['OR'] = array(
                        'User.firstname LIKE ' => '%' . $key_search . '%',
                        'User.lastname LIKE ' => '%' . $key_search . '%',
                        'User.contact LIKE ' => '%' . $key_search . '%',
                        'User.membership_id LIKE ' => '%' . $key_search . '%',
                        'User.user_code LIKE ' =>  '%' . $key_search . '%'
                    );
                }

                $gender = $this->request->query('gender');
                $area = $this->request->query('area');
                $birthday = $this->request->query('birthday');
                if (!empty($gender)) {
                    $conditions['User.gender'] = $gender;
                }
                if (!empty($area)) {
                    $conditions['User.area_id'] = $area;
                }
                if (!empty($birthday)) {
                    $conditions['DATE(User.birthday)'] = $birthday;
                }
            }
        }

        $user = $this->User->find('all', array(
            'fields' => array('*'),
            'conditions' => $conditions,
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'left',
                    'conditions' => array(
                        'UserShop.user_id = User.id'
                    )
                )
            ),
            'contain' => array('UserShop'),
            'recursive' => -1
        ));
        $this->set('customer', $user);
        $this->set('count_result', $count);
        $this->set('totle_count', count($user));
        $this->layout = 'ajax';
    }

    public function customer_info($shop_id)
    {
        $id = $this->request->query('customer_id');
        $this->User->id = $id;
        $this->loadModel('Reservation');
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'invalid user ID'
            ));
        }

        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id,
                'User.status' => 1
            )
        ));
        $this->Reservation->recursive = -1;
        $reservation = $this->Reservation->find('first', array(
            'conditions' => array(
                'Reservation.user_id' => $id,
                'Reservation.is_checkin' => 0,
                'Reservation.status <>'=>'cancel',
                'Reservation.is_completed' => 1,
                'Reservation.is_deleted <>' => 1
            ),
            'order' => 'Reservation.date DESC'
        ));
        $is_checkin = 0;
        if (!empty($reservation)) {
            if (isset($reservation['Reservation']['is_checkin']) || $reservation['Reservation']['is_checkin']) {
                $is_checkin = 1;
            }
        } else {
            $is_checkin = 0;
        }
        return json_encode(array(
            'result' => 'success',
            'data' => $user,
            'count' => $this->Reservation->countIsCheckin($id, $shop_id),
            'is_checkin' => $is_checkin
        ));
    }

    public function customer_detail()
    {
        $this->autoRender = false;
        try {
            $id = $this->request->query('customer_id');
            $this->User->id = $id;
            if (!$this->User->exists()) {
                return json_encode(array(
                    'result' => false,
                    'msg' => 'invalid user ID'
                ));
            }
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $id,
                    'User.status' => 1
                ),
                'recursive' => -1
            ));

            return json_encode(array(
                'result' => true,
                'user' => $user,
            ));
        } catch (Exception $ex) {
            return json_encode(array(
                'result' => true,
                'message' => $ex->getMessage(),
            ));
        }
    }

    public function get_customer_image()
    { //this function might not use.
        $this->loadModel('SendMemoPicture');
        $this->loadModel('Shop');
        $this->loadModel('UserShop');
        $user_id = $this->request->query('customer_id');
        $shop_id = $this->UserShop->get_shopid($this->Auth->user('id'));
        $memo_pic = $this->SendMemoPicture->find('all', array(
            'conditions' => array(
                'AND' => array(
                    'shop_id' => $shop_id[0]['shops']['id'],
                    'user_id' => $user_id
                )
            ),
            'recursive' => -1
        ));
        $send_pic = $this->SendMemoPicture->find('all', array(
            'conditions' => array(
                'AND' => array(
                    'shop_id' => $shop_id[0]['shops']['id'],
                    'user_id' => $user_id,
                    'is_sent' => 1
                )
            ),
            'recursive' => -1
        ));

        $this->set('memo_pic', $memo_pic);
        $this->set('send_pic', $send_pic);
        $this->layout = 'ajax';
    }

    public function memo_image_album()
    {
        $this->loadModel('SendMemoPicture');
        $this->loadModel('Shop');
        $this->loadModel('UserShop');
        $user_id = $this->request->query('customer_id');
        $shop_id = $this->UserShop->get_shopid($this->Auth->user('id'));
        $memo_pic_album = $this->SendMemoPicture->find('all', array(
            'conditions' => array(
                'AND' => array(
                    'shop_id' => $shop_id[0]['shops']['id'],
                    'user_id' => $user_id
                )
            ),
            'recursive' => -1
        ));
        $this->set('memo_pic_album', $memo_pic_album);
        $this->layout = 'ajax';
    }

    public function register() {
        $this->loadModel('User');
        $this->loadModel('Area');
        $this->loadModel('Shop');

        $user_id = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        } else {
            $user_id = $this->Auth->user('id');
        }
        
        $shop_lst = $this->Shop->getShopIdByUser($user_id);

        $this->set('area', $this->Area->getAreaDropdown());
        $this->set('shop', $shop_lst);

        if ($this->request->is('ajax')) {
            $this->loadModel('UserShop');
            $this->autoRender = false;
            $rnd_id = str_replace('.', '', uniqid(rand(), 1));
            $model_id = substr($rnd_id, 10, 6);
            $id = $this->request->query('customer_id');
            $data = array(
                'User' => array(
                    'id' => $id,
                    'firstname' => $this->request->query('firstname'),
                    'lastname' => $this->request->query('lastname'),
                    'lastname_kana' => $this->request->query('lastname_kana'),
                    'firstname_kana' => $this->request->query('firstname_kana'),
                    'contact' => $this->request->query('contact'),
                    'birthday' => empty($this->request->query('birthday')) ? null : $this->request->query('birthday'),
                    'gender' => $this->request->query('gender'),
                    'area_id' => $this->request->query('area_id'),
                    'membership_id' => $this->request->query('membership_id'),
                    'email' => $this->request->query('email'),
                    'role' => 'user',
                    'status' => 1,
            ));
            if (!$id) {
                $data['User']['model_id'] = $this->User->generateUniqueModelId();
                $data['User']['user_code'] = $this->User->generateUniqueUserCode();
            }
            if ($this->User->save($data)) {
                $customer_id = $this->User->getLastInsertId();
                if (!$id && $customer_id) {
                    $user_shop_id = $this->request->query('shop_id');
                    if ($this->Auth->user('role') === ROLE_SHOP) {
                        $shop = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                        if ($shop) {
                            $user_shop_id = $shop['Shop']['id'];
                        }
                    }

                    $this->UserShop->save(array(
                        'user_id' => $customer_id,
                        'shop_id' => $user_shop_id,
                        'type' => 'shop',
                        'is_allow_notification' => 1
                    ));
                }
                return json_encode(array(
                    'result' => true,
                    'message' => 'Customer has been saved'
                ));
            } else {
                return json_encode(array(
                    'result' => false,
                    'message' => 'Customer can not save'
                ));
            }
        }
    }

    public function register_list()
    {
        try {
            $this->autoRender = false;
            $this->loadModel('Shop');
            $this->loadModel('User');
            $shop = $this->Shop->find('first', array(
                'conditions' => array(
                    'user_id' => $this->Auth->user('id')
                ),
                'fields' => array('id'),
                'recursive' => -1
            ));
            $shop_id = $this->request->query('shop_id');

            if ($this->Auth->user('role') === ROLE_HEADQUARTER && $shop_id) {
                $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
                //check shop if it belong to this user
                if (!$this->Shop->ShopBelongTo($user_id_list, $shop_id)) {
                    throw new NotFoundException();
                }
            } else {
                $shop_id = $shop['Shop']['id'];
            }
            $users = $this->User->query('select * from users as U INNER JOIN user_shops as S on U.id = S.user_id'
                    . ' LEFT JOIN areas as A on U.area_id=A.id'
                    . ' where U.status=1 AND S.is_disabled!=1 AND S.shop_id=' . $shop_id
                    . ' ORDER BY U.created DESC');

            return json_encode(array(
                'users' => $users,
                'count_customers' => count($users),
                'shop_id' => $shop_id,
                'result' => true
            ));
        } catch (Exception $ex) {
            return json_encode(array(
                'message' => $ex->getMessage(),
                'result' => false
            ));
        }
    }

    public function delete_customer()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->layout = false;

            $id = $this->request->query('id');
            $error = array();

            $this->User->id = $id;
            if (!$this->User->exists()) {
                $error['result'] = false;
            } else {
                $this->loadModel('UserShop');
                if ($this->User->save(array('User' => array('status' => 0)))) {
                    $this->UserShop->updateAll(array('is_disabled' => 1), array('user_id' => $this->User->id));
                    $error['result'] = true;
                } else {
                    $error['result'] = false;
                }

                echo json_encode($error);
            }
        } else {
            new NotFoundException;
        }
    }

    public function save_customer()
    {
        $id = $this->request->query('id');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        $data = array(
            'User' => array(
                'firstname' => $this->request->query('firstname'),
                'lastname' => $this->request->query('lastname'),
                'lastname_kana' => $this->request->query('lastname_kana'),
                'firstname_kana' => $this->request->query('firstname_kana'),
                'birthday' => $this->request->query('birthday'),
                'gender' => $this->request->query('gender'),
                'area_id' => $this->request->query('area'),
                'email' => $this->request->query('email'),
                'contact' => $this->request->query('mobile'),
                'membership_id' => $this->request->query('membership_id'),
                'model_id' => $this->request->query('model_id'),
        ));
        if ($this->User->save($data)) {
            return json_encode(array('result' => 'success', 'msg' => 'Customer has been saved'));
        }
    }

    ////////////////////////////----Function delete list of user by Thary--//////////////////////
    public function deleted()
    {
        $this->loadModel('Area');
        $getShop = $this->Shop->find('all', array(
            'conditions' => array('Shop.user_id' => $this->Auth->user('id'))
        ));
        $is_shop = '';
        if (!empty($getShop)) {
            $shop = Hash::extract($getShop, '{n}.Shop.id');
            $arr_shop = '';
            for ($i = 0; $i < count($shop); $i++) {
                if ($i == 0) {
                    $arr_shop .= $shop[$i];
                } else {
                    $arr_shop .= ',' . $shop[$i];
                }
            }
            $is_shop = 'AND UserShop.shop_id in (' . $arr_shop . ')';
        }
        $sql = $this->User->query('SELECT U.*,A.*,UserShop.* FROM users U '
            . ' LEFT OUTER JOIN areas A on U.area_id = A.id'
            . ' LEFT OUTER JOIN user_shops UserShop on U.id=UserShop.user_id'
            . ' WHERE MONTH(U.modified) =' . date('m') . ' AND YEAR(U.modified)=' . date('Y')
            . ' AND (U.status= 0 OR U.status is null)'
            . $is_shop
            . ' ORDER by U.modified desc');
        return $sql;
    }

    public function fetch_deleted()
    {
        $getShop = $this->Shop->find('all', array(
            'conditions' => array('Shop.user_id' => $this->Auth->user('id'))
        ));
        $is_shop = '0';
        if (!empty($getShop)) {
            $shop = Hash::extract($getShop, '{n}.Shop.id');
            $arr_shop = '';
            for ($i = 0; $i < count($shop); $i++) {
                if ($i == 0) {
                    $arr_shop .= $shop[$i];
                } else {
                    $arr_shop .= ',' . $shop[$i];
                }
            }
            $is_shop = $arr_shop;
        }
        if ($this->request->is('ajax')) {
            $year = $this->request->query('year');
            $month = $this->request->query('month');
            $type = $this->request->query('type');
            $action = $this->request->query('action');
            switch ($action) {
                case 'default':
                    $data = $this->deleted();
                    break;
                case 'onchange':
                    $data = $this->get_delete_onchange($month, $year, $type, $is_shop);
                    break;
                case 'revert':
                    $data = $this->revert_delete_of_user();
                    break;
                case 'delete':
                    $data = $this->delete_permanantly_of_user();
                    break;
            }
        }

        $this->set('data', $data);
        $this->set('type', $type);
        $this->set('count', count($data));
        $this->layout = 'ajax';
    }

    public function get_delete_onchange($month, $year, $type, $is_shop)
    {
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $this->loadModel('SendMemoPicture');
        $this->loadModel('ReservationSendPicture');
        $this->loadModel('Photo');
        $this->loadModel('MenuCategory');
        $this->loadModel('Notice');
        $this->loadModel('Staff');
        $this->loadModel('Support');
        $this->loadModel('QuestionAnswer');

        if ($type == 'user') {
            $sql = $this->User->query('SELECT U.*,A.*,UserShop.* FROM users U '
                    . ' LEFT OUTER JOIN areas A on U.area_id = A.id'
                    . ' LEFT OUTER JOIN user_shops UserShop on U.id=UserShop.user_id'
                    . ' WHERE MONTH(U.modified) =' . $month . ' AND YEAR(U.modified)=' . $year
                    . ' AND (U.status= 0 OR U.status is null)'
                    . ' AND UserShop.shop_id in(' . $is_shop . ')'
                    . ' ORDER by U.modified desc');
        } else if ($type == 'reservation') {
            $sql = $this->User->query('SELECT U.id,U.firstname,U.lastname,R.*, T.* FROM reservations R '
                    . ' LEFT OUTER JOIN users U on R.user_id = U.id'
                    . ' LEFT OUTER JOIN reservation_tag RT on R.id = RT.reservation_id'
                    . ' LEFT OUTER JOIN tags T on RT.tag_id = T.id'
                    . ' WHERE MONTH(R.modified) =' . $month . ' AND YEAR(R.modified)=' . $year
                    . ' AND R.is_deleted = 1'
                    . ' AND R.shop_id in(' . $is_shop . ')'
                    . ' ORDER BY R.modified desc');
        } else if ($type == 'photo_send') {
            $sql = $this->Reservation->query('SELECT M.*,R.* FROM reservations R inner join '
                    . ' media as M on R.id = M.external_id'
                    . ' where M.model="reservations" and MONTH(M.modified) =' . $month . ' AND YEAR(M.modified)=' . $year
                    . ' AND M.is_deleted = 1'
                    . ' ORDER BY M.modified desc');
        } else if ($type == 'account_data') {
            //empty
            $sql = "";
        } else if ($type == 'menu_categories') {
            $sql = $this->MenuCategory->query('SELECT M.* FROM menu_categories M '
                    . ' WHERE MONTH(M.modified) =' . $month . ' AND YEAR(M.modified)=' . $year
                    . ' AND M.is_deleted = 1'
                    . ' AND M.shop_id in(' . $is_shop . ')'
                    . ' ORDER BY M.modified desc');
        } else if ($type == 'menu_photo') {
            $sql = $this->MenuCategory->query('SELECT P.* FROM photos P '
                    . ' WHERE MONTH(P.modified) =' . $month . ' AND YEAR(P.modified)=' . $year
                    . ' AND P.is_deleted = 1'
                    . ' AND P.shop_id in(' . $is_shop . ')'
                    . ' ORDER BY P.modified desc');
        } else if ($type == 'notification') {
            $sql = $this->Notice->query('SELECT N.* FROM news N '
                    . ' WHERE MONTH(N.modified) =' . $month . ' AND YEAR(N.modified)=' . $year
                    . ' AND N.is_deleted = 1 AND N.type="notice_settings"'
                    . ' AND N.user_id="' . $this->Auth->User('id') . '"'
                    . ' ORDER BY N.modified desc');
        } else if ($type == 'staff') {
            $sql = $this->Staff->query('SELECT S.* FROM staffs S '
                    . ' WHERE MONTH(S.modified) =' . $month . ' AND YEAR(S.modified)=' . $year
                    . ' AND S.is_deleted = 1'
                    . ' AND S.shop_id in(' . $is_shop . ')'
                    . ' ORDER BY S.modified desc');
        } else if ($type == 'support') {
            $sql = $this->Support->query('SELECT S.* FROM supports S '
                    . ' WHERE MONTH(S.modified) =' . $month . ' AND YEAR(S.modified)=' . $year
                    . ' AND S.is_deleted = 1'
                    . ' AND S.shop_id in(' . $is_shop . ')'
                    . ' ORDER BY S.modified desc');
        } else if ($type == 'question_answer') {
            $sql = $this->QuestionAnswer->query('SELECT Q.* FROM q_a_supports Q '
                    . ' WHERE MONTH(Q.modified) =' . $month . ' AND YEAR(Q.modified)=' . $year
                    . ' AND Q.is_deleted = 1'
                    . ' AND Q.shop_id in(' . $is_shop . ')'
                    . ' ORDER BY Q.modified desc');
        }
        return $sql;
    }

    public function revert_delete_of_user() {
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $this->loadModel('SendMemoPicture');
        $this->loadModel('ReservationSendPicture');
        $this->loadModel('Photo');
        $this->loadModel('MenuCategory');
        $this->loadModel('Notice');
        $this->loadModel('Staff');
        $this->loadModel('Support');
        $this->loadModel('QuestionAnswer');
        $this->loadModel('Media');
        $this->loadModel('UserShop');
        $id = $this->request->query('revert_id');
        $revert_type = $this->request->query('revert_type');
        if ($this->request->is('get')) {
            if ($revert_type == "user") { //Type user
                $this->User->id = $id;
                if (!$this->User->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid user ID'
                    ));
                }
                if ($this->User->save(array('User' => array('status' => 1)))) {
                    $user_shop_id = $this->request->query('user_shop_id');
                    $shop_id = $this->request->query('shop_id');
                    $this->UserShop->query('Delete From user_shops where id="' . $user_shop_id . '"');
                    $data = array('UserShop' => array(
                            'shop_id' => $shop_id,
                            'user_id' => $id,
                            'type' => 'shop',
                            'is_allow_notification' => 1,
                            'is_disabled' => 0
                    ));
                    $this->UserShop->save($data);
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'User has been reverted'
                    ));
                }
            } else if ($revert_type == 'reservation') { //Type Reservation
                $this->Reservation->id = $id;
                if (!$this->Reservation->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid reservation ID'
                    ));
                }
                if ($this->Reservation->save(array('Reservation' => array('is_deleted' => 0)))) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Reservation has been reverted'
                    ));
                }
            } else if ($revert_type == 'photo_send') { //Type Photo Send
                $this->Media->id = $id;
                if (!$this->Media->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid photo ID'
                    ));
                }
                $image = $this->Media->find('first', array(
                    'conditions' => array('id' => $id)
                ));
                $img_arr = explode(',', $image['Media']['file']);
                if (count($img_arr) > 1) {
                    $media = $this->Media->find('first', array(
                        'conditions' => array('id' => $img_arr[0]),
                        'recursive' => -1
                    ));
                    if ($media) {
                        if ($media['Media']['is_deleted'] == 1) {
                            $this->Media->save(array(
                                'id' => $media['Media']['id'],
                                'file' => $img_arr[1],
                                'is_deleted' => 0
                            ));
                            $this->Media->save(array(
                                'id' => $image['Media']['id'],
                                'file' => $media['Media']['id'] . ',' . $media['Media']['file']
                            ));
                        } else {
                            if ($this->Media->save(array(
                                        'id' => $img_arr[0],
                                        'file' => $media['Media']['file'] . ',' . $img_arr[1]
                                    ))) {
                                $this->Media->delete($id);
                                return json_encode(array(
                                    'result' => 'success',
                                    'msg' => 'Photo send has been reverted'
                                ));
                            }
                        }
                    } else {

                        $this->Media->create();
                        if ($this->Media->save(array(
                                    'id' => $img_arr[0],
                                    'file' => $img_arr[1],
                                    'created' => $image['Media']['modified'],
                                    'external_id' => $image['Media']['external_id'],
                                    'model' => 'reservations',
                                    'user_id' => $image['Media']['user_id']
                                ))) {
                            $this->Media->delete($id);
                            return json_encode(array(
                                'result' => 'success',
                                'msg' => 'Media has been reverted'
                            ));
                        }
                    }
                } else {
                    if ($this->Media->save(array('Media' => array('is_deleted' => 0)))) {
                        return json_encode(array(
                            'result' => 'success',
                            'msg' => 'Photo send has been reverted'
                        ));
                    }
                }
            } else if ($revert_type == 'menu_categories') { //Type Menu Category
                $this->MenuCategory->id = $id;
                if (!$this->MenuCategory->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid menu category ID'
                    ));
                }
                if ($this->MenuCategory->save(array('MenuCategory' => array('is_deleted' => 0)))) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Menu category has been reverted'
                    ));
                }
            } else if ($revert_type == 'menu_photo') { //Type Menu Photo
                $this->Photo->id = $id;
                if (!$this->Photo->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid menu photo ID'
                    ));
                }
                if ($this->Photo->save(array('Photo' => array('is_deleted' => 0)))) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Menu photo has been reverted'
                    ));
                }
            } else if ($revert_type == 'notification') { //Type Notification
                $this->Notice->id = $id;
                if (!$this->Notice->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid notice ID'
                    ));
                }
                if ($this->Notice->save(array('Notice' => array('is_deleted' => 0)))) {
                    $media = $this->Media->find('all', array(
                        'conditions' => array(
                            'Media.external_id' => $id,
                            'Media.model' => "news"
                        )
                    ));
                    for ($i = 0; $i < count($media); $i ++) {
                        $media_id = $media[$i]['Media']['id'];
                        $this->Media->id = $media_id;
                        //update status is_delete=0
                        $this->Media->saveField('is_deleted', 0);
                    }
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Notice has been reverted'
                    ));
                }
            } else if ($revert_type == 'staff') { //Type Staff
                $this->Staff->id = $id;
                if (!$this->Staff->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid staff ID'
                    ));
                }
                if ($this->Staff->save(array('Staff' => array('is_deleted' => 0)))) {

                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Staff has been reverted'
                    ));
                }
            } else if ($revert_type == 'support') { //Type Support
                $this->Support->id = $id;
                if (!$this->Support->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid support ID'
                    ));
                }
                if ($this->Support->save(array('Support' => array('is_deleted' => 0)))) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Support has been reverted'
                    ));
                }
            } else if ($revert_type == 'question_answer') { //Type Question and Answer
                $this->QuestionAnswer->id = $id;
                if (!$this->QuestionAnswer->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid question and answer ID'
                    ));
                }
                if ($this->QuestionAnswer->save(array('QuestionAnswer' => array(
                                'is_deleted' => 0)))) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Question and answer has been reverted'
                    ));
                }
            }
        }
    }

    public function delete_permanantly_of_user()
    {
        $this->loadModel('UserShop');
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $this->loadModel('ReservationTag');
        $this->loadModel('SendMemoPicture');
        $this->loadModel('Photo');
        $this->loadModel('MenuCategory');
        $this->loadModel('ApplicationMenuList');
        $this->loadModel('Notice');
        $this->loadModel('Staff');
        $this->loadModel('Support');
        $this->loadModel('QuestionAnswer');
        $this->loadModel('Media');
        $id = $this->request->query('delete_id');
        $delete_type = $this->request->query('delete_type');
        if ($this->request->is('get')) {
            $result = false;
            if ($delete_type == 'user') { //Type user
                $myUser = explode(',', $id);
                foreach ($myUser as $id) {
                    $this->User->id = $id;
                    if (!$this->User->exists()) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Invalid user ID'
                        ));
                    }
                    if ($this->User->delete()) {
                        $this->UserShop->deleteAll(array('UserShop.user_id' => $id, TRUE));
                        $result = true;
                    }
                }
                if ($result) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'User has been deleted'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'User has not deleted'
                    ));
                }
            } else if ($delete_type == 'reservation') { //Type Reservation
                $myReservation = explode(',', $id);
                foreach ($myReservation as $id) {
                    $this->Reservation->id = $id;
                    if (!$this->Reservation->exists()) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Invalid reservation ID'
                        ));
                    }
                    if ($this->Reservation->delete()) {
                        $this->ReservationTag->deleteAll(array('ReservationTag.reservation_id' => $id, TRUE));
                        $result = true;
                    }
                }
            } else if ($delete_type == 'photo_send') { //Type Photo Send
                $myPhotos = explode(',', $id);
                foreach ($myPhotos as $id) {
                    $this->Media->id = $id;
                    $image = $this->Media->query('SELECT file from media where id =' . $id . '');
                    $image_name = $image['Media']['file'];
                    $img_arr = explode(',', $image_name);
                    if (count($img_arr) > 1) {
                        $image_name = $img_arr[1];
                    }
                    if (!empty($image_name)) {
                        unlink(WWW_ROOT . 'uploads/reservation_send_photos/' . $image_name);
                    }
                    $this->Media->delete();
                }
            } else if ($delete_type == 'menu_categories') { //Type Menu Category
                $myMenuCategories = explode(',', $id);
                foreach ($myMenuCategories as $id) {
                    $this->MenuCategory->id = $id;
                    if (!$this->MenuCategory->exists()) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Invalid menu categories ID'
                        ));
                    }
                    //Get sub menu categories image
                    $appliCationMenuList = $this->MenuCategory->findById($id);
                    foreach ($appliCationMenuList['ApplicationMenuList'] as $key => $val) {
                        $sub_image = $val['image'];
                        if (!empty($sub_image)) {
                                unlink(WWW_ROOT . 'uploads/app_menu_lists/' . $image_name);
                        }
                    }
                    //Get menu categories image
                    $menu_categories = $this->MenuCategory->findById($id);
                    $menu_image = $menu_categories['MenuCategory']['image'];
                    if (!empty($menu_image)) {
                                unlink(WWW_ROOT . 'uploads/app_menus/' . $menu_image);
                        }
                    if ($this->MenuCategory->delete()) {
                        $this->ApplicationMenuList->deleteAll(array('ApplicationMenuList.menu_category_id' => $id, TRUE));
                        $result = true;
                    }
                }
                if ($result) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Menu Categories has been deleted'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Menu Categories has not deleted'
                    ));
                }
            } else if ($delete_type == 'notification') { //Type Notification
                $myNotication = explode(',', $id);
                foreach ($myNotication as $id) {
                    $this->Notice->id = $id;
                    if (!$this->Notice->exists()) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Invalid Notice ID'
                        ));
                    }
                    $media = $this->Media->find('all', array(
                        'conditions' => array(
                            'Media.external_id' => $id,
                            'Media.model' => "news"
                        )
                    ));
                    for ($i = 0; $i < count($media); $i ++) {
                        $media_id = $media[$i]['Media']['id'];
                        $image_name = $media[$i]['Media']['file'];
                        if (!empty($image_name)) {
                            unlink(WWW_ROOT . 'uploads/photo_notices/' . $image_name);
                        }
                        $this->Media->id = $media_id;
                        $this->Media->delete();
                    }
                    if ($this->Notice->delete()) {
                        $result = true;
                    }

                }
                if ($result) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Notice has been deleted'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Notice has not deleted'
                    ));
                }
            } else if ($delete_type == 'staff') { //Type Staff
                $myStaff = explode(',', $id);
                foreach ($myStaff as $id) {
                    $this->Staff->id = $id;
                    if (!$this->Staff->exists()) {
                        return json_encode(array(
                            'result' => 'error',
                            'msg' => 'Invalid Staff ID'
                        ));
                    }
                    //Get Staff image
                    $staff = $this->Staff->findById($id);
                    $staff_image = $staff['Staff']['image'];
                    if (!empty($staff_image)) {
                        unlink(WWW_ROOT . 'uploads/staffs/' . $staff_image);
                    }
                    if ($this->Staff->delete()){
                        $result = true;
                    }
                }
                if ($result) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Staff has been deleted'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Staff has not deleted'
                    ));
                }

            } else if ($delete_type == 'support') { //Type Support
                $this->Support->id = $id;
                if (!$this->Support->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid support ID'
                    ));
                }
                if ($this->Support->delete()) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Support has been deleted'
                    ));
                }
            } else if ($delete_type == 'question_answer') { //Type Question and Answer
                $this->QuestionAnswer->id = $id;
                if (!$this->QuestionAnswer->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid question and answer ID'
                    ));
                }
                if ($this->QuestionAnswer->delete()) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Question and answer has been deleted'
                    ));
                }
            }
        }
    }

    /////////////////////End Function delete list of user by Thary

    public function delete_permanantly()
    {
        $id = $this->request->query('customer_id');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        if ($this->User->delete($id)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Customer has been deleted'
            ));
        }
    }

    public function revert_customer()
    {
        $id = $this->request->query('customer_id');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        if ($this->User->save(array('User' => array('is_deleted' => 0)))) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Customer has been reverted'
            ));
        }
    }

    public function fetch_tags() {
        if ($this->request->is('ajax')) {
            //$this->autoRender = false;

            $this->loadModel('Tag');
//=============================================
            $user_id = $this->request->query('customer');
            $this->loadModel('UserShop');
            $shop_id = $this->UserShop->find('list', array(
                'fields' => 'UserShop.shop_id',
                'conditions' => array(
                    'UserShop.user_id' => $user_id
                    
                )
            ));

            $all_tag = $this->Tag->find('all', array(
                'conditions' => array(
                    'Tag.tag_type' => 'user_tag',
                    'Tag.is_deleted <>' => 1,
                    'Tag.shop_id' => $shop_id
                ),
                'recursive' => -1
            ));

            $this->loadModel('UserTag');
            $user_tag = $this->UserTag->find('all', array(
                'conditions' => array('UserTag.user_id' => $user_id),
                'recursive' => -1
            ));

            //$this->layout = 'fetch_tag_ajax';
            //$this->viewBuilder()->layout($this->layout);

            $user_ut = array();
            if (!empty($user_tag)) {
                foreach ($user_tag as $index => $ut) {
                    //echo($ut['UserTag']['tag_id'] . "</br>");
                    array_push($user_ut, $ut['UserTag']['tag_id']);
                }
            }

            $html = '';
            foreach ($all_tag as $key => $tag) {
                $selected = '';
                if (in_array($tag['Tag']['id'], $user_ut)) {
                    $selected = 'tag_operation_is_assign';
                }

                $html .= '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">';
                $html .= '<div class="form-group change_to_button tag_operation ' . $selected . '" id="' . $tag['Tag']['id'] . '">';
                $html .= $tag['Tag']['tag'];
                $html .= '</div>';
                $html .= '</div>';
            }

            echo $html;
        }
    }

    public function assign_tag_to_user() {
        if ($this->request->is('ajax')) {

            $this->loadModel('UserTag');
            $customer_id = $this->request->query('customer_id');
            $tag_id = $this->request->query("tag_id");
            $data = array(
                'user_id' => $customer_id,
                'tag_id' => $tag_id,
                'created' => "now()",
            );
            if ($this->UserTag->checkExist($customer_id, $tag_id)){
                if ( $this->UserTag->save($data) ) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'User tag has been saved'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'User tag could not saved'
                    ));
                }
            }else {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Already created'
                ));
            }
        }
    }

    public function remove_tag_from_user() {
        if ($this->request->is('ajax')) {

            $this->loadModel('UserTag');
            $customer_id = $this->request->query('customer_id');
            $tag_id = $this->request->query("tag_id");
            $conditions = array(
                'user_id' => $customer_id,
                'tag_id' => $tag_id,
            );
            if (!$this->UserTag->checkExist($customer_id, $tag_id)){
                if ( $this->UserTag->deleteAll($conditions) ) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Success assign tag to user'
                    ));
                } else {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Cannot assign tag to user'
                    ));
                }
            }else {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'Already remove tag from user'
                ));
            }
        }
    }
    
    public function add_tag() {
        $this->loadModel('Tag');
        $data = array('Tag' => array(
                'tag' => $this->request->query('tag'),
                'remark' => $this->request->query('remark')
        ));
        $this->Tag->create();
        if ($this->Tag->save($data)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Tag has been saved'
            ));
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Tag could not saved'
            ));
        }
    }

    public function edit_tag() {
        $this->loadModel('Tag');
        $tag = $this->request->query('tag');
        $remark = $this->request->query('remark');
        $id = $this->request->query('tag_id');
        $this->Tag->id = $id;
        if (!$this->Tag->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid tag ID'
            ));
        }
        $data = array('Tag' => array(
                'tag' => $tag,
                'remark' => $remark
        ));
        if ($this->Tag->save($data)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Tag has been updated'
            ));
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Tag could not updated'
            ));
        }
    }

    public function delete_tag() {
        $this->loadModel('Tag');
        $id = $this->request->query('tag_id');
        $del_physical = $this->request->query('del_physical');
        $this->Tag->id = $id;
        if (!$this->Tag->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid tag ID'
            ));
        }
        if ($del_physical == '1') {
            if ($this->Tag->delete($id)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Tag has been deleted'
                ));
            } else {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Tag could not deleted'
                ));
            }
        } else {
            if ($this->Tag->saveField('is_deleted', 1)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Tag has been deleted'
                ));
            } else {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Tag could not deleted'
                ));
            }
        }
    }

    public function check_in() {
        $user_id = $this->request->query('id');
        $this->User->id = $user_id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        $this->loadModel('Reservation');
        $reservation = $this->Reservation->find('first', array(
            'conditions' => array('Reservation.user_id' => $user_id),
            'order' => array('Reservation.date' => 'DESC')));

        if (count($reservation) == 0) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'User has no reservation'
            ));
        }
        $responnd = $this->Reservation->updateAll(
                array('is_checkin' => 1), array('Reservation.id' => $reservation['Reservation']['id'])
        );
        if ($responnd) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'User has been check in'
            ));
        } else {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'User cannot check in'
            ));
        }
    }

    public function set_title_image() {
        $this->loadModel('SendMemoPicture');
        $data = $this->request->data['SendMemoPicture'];
        foreach ($data as $key => $value) {
            $this->SendMemoPicture->id = $value['id'];
            $this->SendMemoPicture->save(array('SendMemoPicture' => array('is_sent' => 1)));
        }
    }

    public function send_model_id() {
        if ($this->request->is('ajax')) {
            $error = array();
            $this->autoRender = false;
            $this->loadModel('User');

            $client = $this->User->findByIdAndStatus($this->request->query('ci'), 1);

            if (empty($client)) {
                throw NotFoundException;
            }

            $modelId = $this->User->generateUniqueModelId(); //$this->User->generateModelId(6);
            if (!empty($client['User']['email'])) {

                $CakeEmail = new CakeEmail();
                $CakeEmail->from(EMAIL_FROM);
                $CakeEmail->to($client['User']['email']);
                $CakeEmail->subject('Reset New Model Id');
                $CakeEmail->emailFormat('text');
                $CakeEmail->template('new_model_id');
                $CakeEmail->viewVars(array(
                    'model_change' => $modelId,
                ));
                $response = array();
                try {
                    $response[] = $CakeEmail->send();
                } catch (Exception $e) {
                    $CakeEmail->reset();
                    $error[] = CakeEmail::deliver(EMAIL_FROM, SERVICE_NAME . ' : EMAIL ERROR - ' . $this->request->action, $e->getMessage(), array('from' => EMAIL_FROM));
                }
                $CakeEmail->reset();
            }

            if (empty($error)) {
                $this->loadModel('Log');
                $this->Log->create();
                $logs = array('user_id' => $client['User']['id'], 'external_id' => $client['User']['id'], 'type' => 'change_model_id', 'value' => $modelId);
                $this->Log->save(array('Log' => $logs));

                $this->User->id = $client['User']['id'];
                echo json_encode($this->User->save(array('User' => array('model_id_change' => $modelId))));
            }
        } else {
            throw NotFoundException;
        }
    }

}
