<?php
/*
 * Function admin_index
 * Created 02/ December/2015
 * Channeth
 */
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
        $shop     = $this->Shop->find('all',
            array(
            'condition' => array('is_deleted <>' => 1),
            'recursive' => -1
        ));
        $arr_shop = array();
        foreach ($shop as $key => $value) {
            $arr_shop[$value['Shop']['id']] = $value['Shop']['shop_name'];
        }
        $this->loadModel('Area');
        $area = $this->Area->find('list');
        $this->set('shop', $arr_shop);
        $this->set('area', $area);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $action           = $this->request->query('action');
            if (!$action) {
                $action = $this->request->data('action');
            }
            switch ($action) {
                case 'customer_info':
                    echo $this->customer_info();
                    return false;
                case 'delete':
                    echo $this->delete_customer();
                    return false;
                case 'save':
                    echo $this->save_customer();
                    return false;
                case 'delete_permanantly':
                    echo $this->delete_permanantly();
                    return false;
                case 'revert_customer':
                    echo $this->revert_customer();
                    return false;
                case 'add_tag':
                    echo $this->add_tag();
                    return false;
                case 'edit_tag':
                    echo $this->edit_tag();
                    return false;
                case 'delete_tag':
                    echo $this->delete_tag();
                    return false;
                case 'checkin':
                    echo $this->check_in();
                    return false;
                case 'set_title_image':
                    echo $this->set_title_image();
                    return false;
                case 'send_mail':
                    echo $this->send_model_id();
                    return false;
            }
        }
    }

    public function result_search()
    {
        $this->loadModel('UserShop');
        if ($this->request->is('ajax')) {
            $action = $this->request->query('action');
            switch ($action) {
                case 'index':
                    $keyword    = '';
                    $conditions = array(
                        'User.status' => 1
                    );
                    break;
                case 'search':
                    $keyword    = $this->request->query('keyword');
                    $shop_id    = $this->request->query('shop_id');
                    $birthday   = $this->request->query('birthday');
                    $gender     = $this->request->query('gender');
                    $area       = $this->request->query('area');
                    $shop       = $this->UserShop->find('first',
                        array('conditions' => array('UserShop.shop_id' => $shop_id),
                        'recursive' => -1));
                    count($shop) > 0 ? $user_id    = $shop['UserShop']['user_id']
                                : $user_id    = '';
                    if ($birthday == '') {
                        $conditions = array(
                            'User.status' => 1,
                            'User.id LIKE' => '%'.$user_id.'%',
                            'User.gender LIKE' => '%'.$gender.'%',
                            'User.area_id LIKE' => '%'.$area.'%',
                        );
                    } else {
                        $conditions = array(
                            'User.status' => 1,
                            'User.id LIKE' => '%'.$user_id.'%',
                            'User.birthday LIKE' => '%'.$birthday.'%',
                            'User.gender LIKE' => '%'.$gender.'%',
                            'User.area_id LIKE' => '%'.$area.'%',
                        );
                    }
                    break;
            }
        }
        $user         = $this->User->find('all',
            array(
            'conditions' => array(
                'OR' => array(
                    'User.firstname LIKE' => '%'.$keyword.'%',
                    'User.lastname LIKE' => '%'.$keyword.'%',
                    'User.contact LIKE' => '%'.$keyword.'%',
                    'User.area_id LIKE' => '%'.$keyword.'%',
                    'User.email LIKE' => '%'.$keyword.'%',
                    'User.gender LIKE' => '%'.$keyword.'%',
                    'User.username LIKE' => '%'.$keyword.'%',
                    'User.username LIKE' => '%'.$keyword.'%',
                    'CONCAT(User.firstname," ",User.lastname) LIKE' => '%'.$keyword.'%',
                ),
                'AND' => $conditions
            )
        ));
        $this->set('customer', $user);
        $this->set('count_result', count($user));
        $this->layout = 'ajax';
    }

    public function customer_info()
    {
        $id             = $this->request->query('customer_id');
        $this->User->id = $id;
        $this->loadModel('Reservation');
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'invalid user ID'
            ));
        }
        $user              = $this->User->find('first',
            array(
            'conditions' => array(
                'User.id' => $id,
                'User.status' => 1
            ),
            'recursive' => -1
        ));
        $count_reservation = $this->Reservation->find('count',
            array(
            'conditions' => array('user_id' => $id, 'is_completed' => 1, 'is_checkin' => 1)
        ));
        $reservation       = $this->Reservation->find('first',
            array(
            'conditions' => array('user_id' => $id),
            'order' => 'Reservation.date DESC',
            'recursive' => -1
        ));
        if ($count_reservation > 0) {
            foreach ($reservation as $key => $value) {
                $is_checkin = $value['is_checkin'];
            }
        } else {
            $is_checkin = 0;
        }
        return json_encode(array(
            'result' => 'success',
            'data' => $user,
            'count' => $count_reservation,
            'is_checkin' => $is_checkin
        ));
    }

    public function get_customer_image()
    {
        $this->loadModel('SendMemoPicture');
        $this->loadModel('Shop');
        $this->loadModel('UserShop');
        $user_id  = $this->request->query('customer_id');
        $shop_id  = $this->UserShop->get_shopid($this->Auth->user('id'));
        pr($shop_id);
        exit;
        $memo_pic = $this->SendMemoPicture->find('all',
            array(
            'conditions' => array(
                'AND' => array(
                    'shop_id' => $shop_id[0]['shops']['id'],
                    'user_id' => $user_id
                )
            ),
            'recursive' => -1
        ));
        $send_pic = $this->SendMemoPicture->find('all',
            array(
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
        $user_id        = $this->request->query('customer_id');
        $shop_id        = $this->UserShop->get_shopid($this->Auth->user('id'));
        $memo_pic_album = $this->SendMemoPicture->find('all',
            array(
            'conditions' => array(
                'AND' => array(
                    'shop_id' => $shop_id[0]['shops']['id'],
                    'user_id' => $user_id
                )
            ),
            'recursive' => -1
        ));
        $this->set('memo_pic_album', $memo_pic_album);
        $this->layout   = 'ajax';
    }

    public function delete_customer()
    {
        $id             = $this->request->query('customer_id');
        $del_physical   = $this->request->query('del_physical');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        if ($del_physical == 1) {
            if ($this->User->delete($id)) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Customer has been deleted'
                ));
            }
        } else {
            if ($this->User->save(array('User' => array('status' => 0)))) {
                return json_encode(array(
                    'result' => 'success',
                    'msg' => 'Customer has been deleted'
                ));
            }
        }
    }

    public function save_customer()
    {
        $id             = $this->request->query('id');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        $data = array("User" => array(
                'firstname' => $this->request->query('firstname'),
                'lastname' => $this->request->query('lastname'),
                'lastname_kana' => $this->request->query('lastname_kana'),
                'firstname_kana' => $this->request->query('firstname_kana'),
                'birthday' => $this->request->query('birthday'),
                'gender' => $this->request->query('gender'),
                'area' => $this->request->query('area'),
                'mobile' => $this->request->query('mobile'),
                'membership_id' => $this->request->query('membership_id'),
                'model_id' => $this->request->query('model_id'),
        ));
        if ($this->User->save($data)) {
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Customer has been saved'
            ));
        }
    }

    ////////////////////////////----Function delete list of user by Thary--//////////////////////
    public function deleted()
    {
        $this->loadModel('Area');
        $sql = $this->User->query('SELECT U.*,A.* FROM users U '
            .' LEFT OUTER JOIN areas A on U.area_id = A.id'
            .' WHERE MONTH(U.created) ='.date('m').' AND YEAR(U.created)='.date('Y')
            .' AND (U.status= 0 OR U.status is null)'
            .' ORDER by U.created desc');
        return $sql;
    }

    public function fetch_deleted()
    {
        if ($this->request->is('ajax')) {
            $year   = $this->request->query('year');
            $month  = $this->request->query('month');
            $type   = $this->request->query('type');
            $action = $this->request->query('action');
            switch ($action) {
                case 'default':
                    $data = $this->deleted();
                    break;
                case 'onchange':
                    $data = $this->get_delete_onchange($month, $year, $type);
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
        $this->set("type", $type);
        $this->set('count', count($data));
        $this->layout = 'ajax';
    }

    public function get_delete_onchange($month, $year, $type)
    {
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $this->loadModel('SendMemoPicture');
        $this->loadModel('Photo');
        $this->loadModel('MenuCategory');
        $this->loadModel('Notice');
        $this->loadModel('Staff');
        $this->loadModel('Support');
        $this->loadModel('QuestionAnswer');

        if ($type == 'user') {
            $sql = $this->User->query('SELECT U.*,A.* FROM users U '
                .' LEFT OUTER JOIN areas A on U.area_id = A.id'
                .' WHERE MONTH(U.created) ='.$month.' AND YEAR(U.created)='.$year
                .' AND (U.status= 0 OR U.status is null)'
                .' ORDER by U.created desc');
        } else if ($type == 'reservation') {
            $sql = $this->User->query('SELECT U.id,U.firstname,U.lastname,R.* FROM reservations R '
                .' LEFT OUTER JOIN users U on R.user_id = U.id'
                .' WHERE MONTH(R.date) ='.$month.' AND YEAR(R.date)='.$year
                .' AND R.is_deleted = 1'
                .' ORDER BY R.created desc');
        } else if ($type == 'photo_send') {
            //empty
            $sql = "";
        } else if ($type == 'account_data') {
            //empty
            $sql = "";
        } else if ($type == 'menu_categories') {
            $sql = $this->MenuCategory->query('SELECT M.* FROM menu_categories M '
                .' WHERE MONTH(M.created) ='.$month.' AND YEAR(M.created)='.$year
                .' AND M.is_deleted = 1'
                .' ORDER BY M.created desc');
        } else if ($type == 'menu_photo') {
            $sql = $this->MenuCategory->query('SELECT P.* FROM photos P '
                .' WHERE MONTH(P.created) ='.$month.' AND YEAR(P.created)='.$year
                .' AND P.is_deleted = 1'
                .' ORDER BY P.created desc');
        } else if ($type == 'notification') {
            $sql = $this->Notice->query('SELECT N.* FROM news N '
                .' WHERE MONTH(N.created) ='.$month.' AND YEAR(N.created)='.$year
                .' AND N.is_deleted = 1 AND N.type="notice_settings"'
                .' ORDER BY N.created desc');
        } else if ($type == 'staff') {
            $sql = $this->Staff->query('SELECT S.* FROM staffs S '
                .' WHERE MONTH(S.created) ='.$month.' AND YEAR(S.created)='.$year
                .' AND S.is_deleted = 1'
                .' ORDER BY S.created desc');
        } else if ($type == 'support') {
            $sql = $this->Support->query('SELECT S.* FROM supports S '
                .' WHERE MONTH(S.created) ='.$month.' AND YEAR(S.created)='.$year
                .' AND S.is_deleted = 1'
                .' ORDER BY S.created desc');
        } else if ($type == 'question_answer') {
            $sql = $this->QuestionAnswer->query('SELECT Q.* FROM q_a_supports Q '
                .' WHERE MONTH(Q.created) ='.$month.' AND YEAR(Q.created)='.$year
                .' AND Q.is_deleted = 1'
                .' ORDER BY Q.created desc');
        }
        return $sql;
    }

    public function revert_delete_of_user()
    {
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $this->loadModel('SendMemoPicture');
        $this->loadModel('Photo');
        $this->loadModel('MenuCategory');
        $this->loadModel('Notice');
        $this->loadModel('Staff');
        $this->loadModel('Support');
        $this->loadModel('QuestionAnswer');
        $this->loadModel('Media');
        $this->loadModel('ReservationTag');
        $id          = $this->request->query('revert_id');
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
                    $reservation_tag = $this->ReservationTag->query('select R.* from reservation_tag as R where R.reservation_id='.$id.''
                        .' and R.is_deleted =1');
                    for ($t = 0; $t < count($reservation_tag); $t++) {
                        $this->ReservationTag->id = $reservation_tag[$t]['R']['id'];
                        $this->ReservationTag->saveField('is_deleted', 0);
                    }
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Reservation has been reverted'
                    ));
                }
            } else if ($revert_type == 'photo_send') { //Type Photo Send
                //Don't select where
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
                    $media = $this->Media->find('all',
                        array(
                        'conditions' => array(
                            'Media.external_id' => $id,
                            'Media.model' => "news"
                        )
                    ));
                    for ($i = 0; $i < count($media); $i ++) {
                        $media_id        = $media[$i]['Media']['id'];
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
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $this->loadModel('SendMemoPicture');
        $this->loadModel('Photo');
        $this->loadModel('MenuCategory');
        $this->loadModel('Notice');
        $this->loadModel('Staff');
        $this->loadModel('Support');
        $this->loadModel('QuestionAnswer');
        $this->loadModel('Media');

        $id          = $this->request->query('delete_id');
        $delete_type = $this->request->query('delete_type');
        if ($this->request->is('get')) {
            if ($delete_type == "user") { //Type user
                $this->User->id = $id;
                if (!$this->User->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid user ID'
                    ));
                }
                if ($this->User->delete()) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'User has been deleted'
                    ));
                }
            } else if ($delete_type == 'reservation') { //Type Reservation
                $this->Reservation->id = $id;
                if (!$this->Reservation->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid reservation ID'
                    ));
                }
                if ($this->Reservation->delete()) {
                    $reservation_tag = $this->ReservationTag->query('select R.* from reservation_tag as R where R.reservation_id='.$id.''
                        .' and R.is_deleted <> 1 OR R.is_deleted is null');
                    for ($r = 0; $r < count($reservation_tag); $r++) {
                        $this->ReservationTag->id = $reservation_tag[$r]['R']['id'];
                        $this->ReservationTag->delete();
                    }
                }
            } else if ($delete_type == 'photo_send') { //Type Photo Send
                //Don't select where
            } else if ($delete_type == 'menu_categories') { //Type Menu Category
                $this->MenuCategory->id = $id;
                if (!$this->MenuCategory->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid menu category ID'
                    ));
                }
                if ($this->MenuCategory->delete()) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Menu category has been deleted'
                    ));
                }
            } else if ($delete_type == 'menu_photo') { //Type Menu Photo
                $this->Photo->id = $id;
                if (!$this->Photo->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid menu photo ID'
                    ));
                }
                if ($this->Photo->delete()) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Menu photo has been deleted'
                    ));
                }
            } else if ($delete_type == 'notification') { //Type Notification
                $this->Notice->id = $id;
                if (!$this->Notice->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid notice ID'
                    ));
                }
                if ($this->Notice->delete()) {
                    $media = $this->Media->find('all',
                        array(
                        'conditions' => array(
                            'Media.external_id' => $id,
                            'Media.model' => "news"
                        )
                    ));
                    for ($i = 0; $i < count($media); $i ++) {
                        $media_id   = $media[$i]['Media']['id'];
                        $image_name = $media[$i]['Media']['file'];
                        if (!empty($image_name)) {
                            unlink(WWW_ROOT.'uploads/photo_notices/'.$image_name);
                        }
                        //update status is_delete=0
                        $this->Media->id = $media_id;
                        $this->Media->delete();
                    }
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Notice has been deleted'
                    ));
                }
            } else if ($delete_type == 'staff') { //Type Staff
                $this->Staff->id = $id;
                if (!$this->Staff->exists()) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Invalid staff ID'
                    ));
                }
                if ($this->Staff->delete()) {
                    return json_encode(array(
                        'result' => 'success',
                        'msg' => 'Staffs has been deleted'
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
        $id             = $this->request->query('customer_id');
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
        $id             = $this->request->query('customer_id');
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

    public function fetch_tags()
    {
        $this->loadModel('Tag');
        $tag = $this->Tag->find('all',
            array(
            'conditions' => array(
                'Tag.tag_type' => 'reservation_tag',
                'OR' => array(
                    'is_deleted <>' => 1,
                    'is_deleted IS NULL'
                )
            ),
            'recursive' => -1
        ));

        $this->loadModel('Reservation');
        $user_id         = $this->request->query('customer');
        $reservation_tag = $this->Reservation->find('first',
            array(
            'conditions' => array('Reservation.is_completed' => 1, 'Reservation.user_id' => $user_id),
            'order' => 'Reservation.created desc'
        ));

        $this->set('reservation_tag', $reservation_tag);
        $this->set('tag', $tag);
        $this->layout = 'ajax';
    }

    public function add_tag()
    {
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

    public function edit_tag()
    {
        $this->loadModel('Tag');
        $tag           = $this->request->query('tag');
        $remark        = $this->request->query('remark');
        $id            = $this->request->query('tag_id');
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

    public function delete_tag()
    {
        $this->loadModel('Tag');
        $id            = $this->request->query('tag_id');
        $del_physical  = $this->request->query('del_physical');
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

    public function check_in()
    {
        $user_id        = $this->request->query('id');
        $this->User->id = $user_id;
        if (!$this->User->exists()) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'Invalid user ID'
            ));
        }
        $this->loadModel('Reservation');
        $reservation = $this->Reservation->find('first',
            array(
            'conditions' => array('Reservation.user_id' => $user_id),
            'order' => array('Reservation.date' => 'DESC')));

        if (count($reservation) == 0) {
            return json_encode(array(
                'result' => 'error',
                'msg' => 'User has no reservation'
            ));
        }
        $responnd = $this->Reservation->updateAll(
            array('is_checkin' => 1),
            array('Reservation.id' => $reservation['Reservation']['id'])
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

    public function set_title_image()
    {
        $this->loadModel('SendMemoPicture');
        $data = $this->request->data['SendMemoPicture'];
        foreach ($data as $key => $value) {
            $this->SendMemoPicture->id = $value['id'];
            $this->SendMemoPicture->save(array('SendMemoPicture' => array('is_sent' => 1)));
        }
    }

    public function send_model_id()
    {
        $email        = $this->request->query('emai');
        $model_change = $this->request->query('model_change');

        $CakeEmail = new CakeEmail();
        $CakeEmail->from(EMAIL_FROM);
        $CakeEmail->to($email);
        $CakeEmail->subject(EMAIL_SUBJECT_RESET_PASSWORD);
        $CakeEmail->template('new_model_id');
        $CakeEmail->viewVars(array(
            'model_change' => $model_change
        ));
        if ($CakeEmail->send()) {
            echo json_encode(array(
                'result' => 'success',
                'msg' => 'Email has been send'
            ));
        } else {
            echo json_encode(array(
                'result' => 'failed',
                'msg' => 'Email cannot send'
            ));
        }
        $CakeEmail->reset();
    }
}