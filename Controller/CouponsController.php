<?php

App::uses('File', 'Utility', 'AppController', 'Controller');

class CouponsController extends AppController
{
    public $components = array('Paginator');

    public function beforeFilter() {
        $this->loadModel('User');
        $this->loadModel('Shop');
        $this->loadModel('UsedCoupon');
        parent::beforeFilter();
        $this->Auth->allow(array(
            'api_index',
            'api_view',
        ));
        $this->Auth->authorize = 'Controller';

        if(($this->Auth->user('role') !== ROLE_HEADQUARTER) && ($this->Auth->user('role') !== ROLE_SHOP) && $this->request->query('api')) {
            throw new NotFoundException();
        }
    }

    public function index()
    {
        $user_id_list = array();
        $dataShop = $this->Shop->findByUserId($this->Auth->user('id'));
        $shop_id = $dataShop['Shop']['id'];
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

        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            reset($shops);
            $options['conditions'] = [
                'Coupon.shop_id' => $this->request->query('shop_id') ? $this->request->query('shop_id') : key($shops),
            ];
            $options['order'] = [
                'Coupon.is_birthday' => 'asc',
                'Coupon.end_date' => 'desc',
            ];
            $options['recursive'] = -1;
        } else {
            $options['conditions'] = [
                'Coupon.shop_id' => $shop_id
            ];
            $options['order'] = [
                'Coupon.is_birthday' => 'asc',
                'Coupon.end_date' => 'desc',
            ];
            $options['recursive'] = -1;
        }
        $coupons = $this->Coupon->find('all', $options);

        $this->set(compact('shops', 'coupons', 'shop_id'));
    }

    public function create()
    {
        $userId = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $userId = $this->User->getListIdbyParrentId($this->Auth->user('id'));
            $shops = $this->Shop->find('list', array(
                'fields' => array('Shop.id', 'Shop.shop_name'),
                'conditions' => array(
                    'is_deleted <>' => 1,
                    'user_id' => $userId
                ),
                'recursive' => -1,
            ));
            $this->set('shops', $shops);
        }

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->disableCache();

            ini_set('max_execution_time', 3000);

            $fields = array('title', 'start_date', 'end_date');
            if (isset($this->request->data['Coupon']['image']['name']) && !empty($this->request->data['Coupon']['image']['name'])) {
                array_push($fields, 'image');
            }

            $shop_id = null;
            if ($this->Auth->user('role') === ROLE_SHOP) { //seem useless or redundancy.
                $shop = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                if (!empty($shop)) {
                    $shop_id = $shop['Shop']['id'];
                    $this->request->data['Coupon']['shop_id'] = $shop['Shop']['id'];
                }
            } else {
                $shop_id = $this->request->data['Coupon']['shop_id'];
            }

            $this->Coupon->set($this->request->data);
            if ($this->Coupon->validates(array('fieldList' => $fields))) {
                $this->request->data['Coupon']['image'] = $this->Coupon->image;
                if ($this->Coupon->createCoupon($this->request->data)) {
                    if (!is_null($shop_id) && $this->request->data['Coupon']['notify_user'] == 1) {
                        $this->coupon_notification($shop_id);
                    }

                    $this->Coupon->image = '';
                    return json_encode(array(
                        'status' => 'OK'
                    ));
                }
                return json_encode(array(
                    'status' => 'ERROR',
                    'msg' => 'System error, please try again later.'
                ));
            }

            return json_encode(array(
                'status' => 'ERROR',
                'msg' => $this->Coupon->validationErrors
            ));
        }        
    }

    public function edit($id = null)
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->disableCache();
            ini_set('max_execution_time', 300);
            $fields = array('title', 'start_date', 'end_date');
            if (isset($this->request->data['Coupon']['image']['name']) && !empty($this->request->data['Coupon']['image']['name'])) {
                array_push($fields, 'image');
            } else {
                unset($this->request->data['Coupon']['image']);
            }

            $shop_id = null;
            if ($this->Auth->user('role') === ROLE_SHOP) { //seem useless or redundancy.
                $shop = $this->Shop->getOwnerShopId($this->Auth->user('id'));
                if (!empty($shop)) {
                    $shop_id = $shop['Shop']['id'];
                    $this->request->data['Coupon']['shop_id'] = $shop['Shop']['id'];
                }
            } else {
                $shop_id = $this->request->data['Coupon']['shop_id'];
            }

            $this->Coupon->set($this->request->data);
            if ($this->Coupon->validates(array('fieldList' => $fields))) {
                if ($this->Coupon->image != '') {
                    $this->request->data['Coupon']['image'] = $this->Coupon->image;
                } else {
                    unset($this->request->data['Coupon']['image']);
                }
                unset($this->request->data['Coupon']['shop_id']); //if not unset it. It will update to Zero!
                $this->Coupon->id = $this->request->data['Coupon']['id'];
                if ($this->Coupon->save($this->request->data, false)) {
                    if (!is_null($shop_id) && $this->request->data['Coupon']['notify_user'] == 1) {
                        $this->coupon_notification($shop_id);
                    }
                    $this->Coupon->image = '';
                    return json_encode(array(
                        'status' => 'OK'
                    ));
                }
                return json_encode(array(
                    'status' => 'ERROR',
                    'msg' => 'System error, please try again later.'
                ));
            }

            return json_encode(array(
                'status' => 'ERROR',
                'msg' => $this->Coupon->validationErrors
            ));            
        }

        $userId = array();
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $userId = $this->User->getListIdbyParrentId($this->Auth->user('id'));
            $shops = $this->Shop->find('list', array(
                'fields' => array('Shop.id', 'Shop.shop_name'),
                'conditions' => array(
                    'is_deleted <>' => 1,
                    'user_id' => $userId
                ),
                'recursive' => -1,
            ));
            $this->set('shops', $shops);
        }
        $this->Coupon->recursive = -1;
        $this->request->data = $this->Coupon->findById($id);
    }

    protected function coupon_notification($shop_id) //send notification to customers on create new or edit coupon.
    {
        $this->loadModel('UserShop');
        $this->loadModel('Shop');
        $this->loadModel('User');
        $this->loadModel('Reservation');
        $customers = $this->UserShop->getAllCustomersByShopId($shop_id);
        $shop_info = $this->Shop->getShopInfo($shop_id);
        if (!empty($customers) && !empty($shop_info)) {            
            foreach ($customers as $customer) {
                if ($customer['UserShop']['is_allow_notification'] == 1) {
                    $cust = $this->User->getSingleCustomer($customer['UserShop']['user_id']);
                    if ($cust) {                        
                        $reservation_badge = $this->Reservation->find('count', array(
                            'conditions' => array(
                                'AND' => array(
                                    'Reservation.is_read' => 0,
                                    'Reservation.user_id' => $cust['User']['id'],
                                    'Reservation.shop_id' => $shop_id,
                                    'Reservation.is_completed' => 1,
                                    'Reservation.is_deleted' => 0,
                                    'Reservation.is_checkin' => 1
                                )
                            ),
                            'recursive' => -1
                        ));

                        // Get unread from NewDelivery
                        $news_badge = $this->NewsDelivery->find('count', array(
                            'conditions' => array(
                                'NewsDelivery.user_id' => $cust['User']['id'],
                                'NewsDelivery.is_read' => 0,
                            )
                        ));
                        $userBadge = $cust['User']['reservation_badge'];
                        $totalBadge = (int)($userBadge + $news_badge + $reservation_badge);

                        if ($cust['User']['platform_type'] == ANDROID_PLATFORM) {
                            $this->User->send_android_notification($shop_info['Shop']['android_key'], $cust['User']['token'], NOTIFICATION_MSG_COUPON, $totalBadge);
                        } else if ($cust['User']['platform_type'] == IOS_PLATFORM) {
                            $this->User->send_ios_notification($shop_info['Shop']['ios_ck_file'], $cust['User']['token'], NOTIFICATION_MSG_COUPON, $totalBadge);
                        }
                    }
                }
            }
        }
    }

    public function delete($id = null)
    {
        $data = $this->Coupon->findById($id);
        $file = new File(WWW_ROOT . 'uploads/coupons/' . $data['Coupon']['image'], false, 0777);
        if ($this->Coupon->delete($id)) {
            $file->delete();
            $this->Session->setFlash(MESSAGE_DELETE, 'success');
            return $this->redirect('/coupons');
        }
    }

    public function copy()
    {
        if ($this->Auth->user('role') !== ROLE_HEADQUARTER) {
            throw new NotFoundException();
        }
        $this->loadModel('Group');

        $groups = $this->Group->find('all', array(
            'conditions' => array(
                'user_id' => $this->Auth->user('id')
            )
        ));

        //get shop of this each group
        foreach ($groups as $key => $values) {
            $list_shop_id = array();
            foreach ($values['ShopGroup'] as $key2 => $values2) {
                $list_shop_id[$key2] = $values2['shop_id'];
            }
            $groups[$key]['Shops'] = $this->Shop->find('all', array(
                'conditions' => array(
                    'id' => $list_shop_id
                ),
                'recursive' => -1
            ));
        }

        if ($this->request->is('post')) {
            if ($this->request->data('Coupon')) {
                $this->loadModel('CouponLog');
                $this->loadModel('CouponCopy');

                foreach ($this->request->data('Coupon') as $key => $value) {
                    if (isset($value['fromShop'])) {
                        $coupon_from_shop = $this->Coupon->find('all', array(
                            'conditions' => array(
                                'shop_id' => $value['fromShop']
                            )
                        ));
                        if (isset($value['toShop'])) {
                            $coupon_copy = array();
                            $coupon_copy['CouponCopy']['group_id'] = $value['group_id'];
                            $coupon_copy['CouponCopy']['from_shop_id'] = $value['fromShop'];
                            $coupon_copy['CouponCopy']['copy_all'] = $value['copy_all'];
                            $coupon_copy['CouponCopy']['to_shop_id'] = json_encode(array_values($value['toShop']));

                            $this->CouponCopy->create();
                            if ($this->CouponCopy->save($coupon_copy)) {
                                $copy_id = $this->CouponCopy->getLastInsertId();
                                foreach ($value['toShop'] as $key2 => $value2) {
                                    if ($value2 == $value['fromShop']) {
                                        continue;
                                    }
                                    //save log first befor eplace the new copy
                                    $shop_coupon = $this->Coupon->find('all', array(
                                        'conditions' => array(
                                            'shop_id' => $value2
                                        )
                                    ));
                                    $couponLog = array();
                                    foreach ($shop_coupon as $key3 => $value3) {
                                        unset($value3['Coupon']['id']);
                                        $data = array();
                                        $data['CouponLog'] = $value3['Coupon'];
                                        $data['CouponLog']['copy_id'] = $copy_id;
                                        array_push($couponLog, $data);
                                    }

                                    if ($couponLog) {
                                        $this->CouponLog->saveMany($couponLog, array('deep' => false));
                                    }
                                    // delete the existing
                                    $this->Coupon->deleteAll(array('shop_id' => $value2), true);
                                    // save the new copy
                                    foreach ($coupon_from_shop as $key4 => $value4) {
                                         unset($coupon_from_shop[$key4]['Coupon']['id']);
                                         $coupon_from_shop[$key4]['Coupon']['shop_id'] = $value2;
                                    }
                                    $result = $this->Coupon->saveMany($coupon_from_shop, array('deep' => false, 'validate' => false));
                                }
                            }
                        }
                    }
                }
                $this->Session->setFlash('Copy Completed.', 'success');
            }
        }

        $this->set(array('data' => $groups));
    }

    public function copy_log()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }
        $this->layout = 'ajax';
        $this->loadModel('Group');
        $this->loadModel('CouponCopy');
        $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        $Shops = $this->Shop->find('list', array(
            'conditions' => array(
                'user_id' => $user_id_list
            )
        ));

        $group_ids = $this->Group->find('list', array(
            'fields' => array('id'),
            'conditions' => array('Group.user_id' => $this->Auth->user('id'))
        ));

        $data = $this->CouponCopy->find('all', array(
            'conditions' => array(
                'from_shop_id' => $Shops,
                'group_id' => $group_ids
            ),
            'order' => 'created DESC'
        ));

        foreach ($data as $key => $value) {
            $group = $this->Group->findById($value['CouponCopy']['group_id']);
            if ($group) {
                $data[$key]['CouponCopy']['group_id'] = $group['Group']['id'];
                $data[$key]['CouponCopy']['group_name'] = $group['Group']['name'];
            }

            $from_shop = $this->Shop->findById($value['CouponCopy']['from_shop_id']);
            if ($from_shop) {
                $data[$key]['CouponCopy']['from_shop_name'] = $from_shop['Shop']['shop_name'];
            }
            $shop_ids = json_decode($value['CouponCopy']['to_shop_id']);

            foreach ($shop_ids as $key2 => $value2) {
                $to_shop = $this->Shop->findById($value2);
                if ($to_shop) {
                    $data[$key]['CouponCopy']['to_shop_name'][] = $to_shop['Shop']['shop_name'];
                }
            }
        }

        $this->set(array('data' => $data));
    }

    public function rolback()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }
        $this->autoRender = false;
        if ($this->request->data['shop_id']) {
            $this->loadModel('CouponLog');
            $shop_id = json_decode($this->request->data['shop_id']);
            foreach ($shop_id as $key => $value) {
                // delete the existing
                $this->Coupon->deleteAll(array('shop_id' => $value), false);
                $coupons = $this->CouponLog->find('all', array(
                    'conditions' => array(
                        'CouponLog.copy_id' => $this->request->data['copy_id'],
                        'CouponLog.shop_id' => $value
                    )
                ));

                if ($coupons) {
                    for ($i = 0; $i < count($coupons); $i++) {
                        $this->Coupon->create();
                        $dt = array(
                            'Coupon' => array(
                                'shop_id' => $coupons[$i]['CouponLog']['shop_id'],
                                'title' => $coupons[$i]['CouponLog']['title'],
                                'description' => $coupons[$i]['CouponLog']['description'],
                                'start_date' => $coupons[$i]['CouponLog']['start_date'],
                                'end_date' => $coupons[$i]['CouponLog']['end_date'],
                                'release_date' => $coupons[$i]['CouponLog']['release_date'],
                                'image' => $coupons[$i]['CouponLog']['image'],
                                'remark' => $coupons[$i]['CouponLog']['remark'],
                                'status' => $coupons[$i]['CouponLog']['status'],
                                'created' => date('Y-m-d H:i:s', strtotime($coupons[$i]['CouponLog']['created'])),
                                'modified' => date('Y-m-d H:i:s', strtotime($coupons[$i]['CouponLog']['modified']))
                            )
                        );
                        $this->Coupon->save($dt, false);
                    }
                }
            }
            $this->Session->setFlash('Restore Completed.', 'success');
        }
    }

    public function api_index()
    {
        $this->layout = null;
        $this->autoRender = false;
        $coupon_list = array();
        $arr = array();
        $this->Paginator->settings =  array(
            'conditions' => array(
                'Coupon.shop_id' => $this->request->query('shop_id'),
                'date(Coupon.release_date) <=' => date('y-m-d'),
                'date(Coupon.end_date) >=' => date('y-m-d'),
            ),
            'limit' => PAGE_LIMIT,
            'order' => array(
                'Coupon.is_birthday' => 'asc'
            ),
        );

        $used_coupons = $this->UsedCoupon->find('all', array(
            'conditions' => array(
                'UsedCoupon.user_id' => $this->request->query('user_id'),
            ),
            'recursive' => -1,
        ));

        $coupons = $this->Coupon->find('all', array(
            'conditions' => array(
                'Coupon.shop_id' => $this->request->query('shop_id'),
                'date(Coupon.release_date) <=' => date('y-m-d'),
                'date(Coupon.end_date) >=' => date('y-m-d'),
            ),
            'fields' => array(
                'Coupon.title',
                'Coupon.id',
                'Coupon.description',
                'Coupon.is_birthday',
                'Coupon.start_date',
                'Coupon.end_date',
                'Coupon.remark',
                'Coupon.image',
            ),
            'order' => array(
                'Coupon.is_birthday' => 'desc',
                'Coupon.end_date' => 'desc',
            ),
            'limit' => PAGE_LIMIT,
            'page' => $this->request->query('page') ? $this->request->query('page') : 1,
            'recursive' => -1,
        ));
        if ($coupons) {
            foreach ($coupons as $key => $item) {
                if ($used_coupons) {
                    foreach ($used_coupons as $use_coupon) {
                        if ($item['Coupon']['id'] == $use_coupon['UsedCoupon']['coupon_id']) {
                            array_push($arr, $item);
                            continue 2;
                        }
                    }
                }
                $coupon_list[] = $this->Coupon->changImageLink($item, '0');
            }
            $arr_used = $this->Coupon->changImageLink($arr, '1');
            foreach ($arr_used as $value) {
                $coupon_list[] = $value['Coupon'];
            }
        }
        $this->Paginator->paginate('Coupon');

        echo json_encode(array(
            'success' => 1,
            'message' => 'success',
            'total_page' => $this->params['paging']['Coupon']['pageCount'],
            'data' => $coupon_list,
        ));
    }

    public function api_view()
    {
        $this->layout = null;
        $this->autoRender = false;
        $coupon = $this->Coupon->find('first',array(
            'conditions' => array(
                'Coupon.shop_id' => $this->request->query('shop_id'),
                'Coupon.id' => $this->request->query('coupon_id'),
            ),
            'fields' => array(
                'Coupon.title',
                'Coupon.id',
                'Coupon.description',
                'Coupon.is_birthday',
                'Coupon.start_date',
                'Coupon.end_date',
                'Coupon.remark',
                'Coupon.image',
            ),
            'order' => array(
                'Coupon.is_birthday' => 'asc'
            )
        ));
        $link = '';
        if (!empty($coupon['Coupon']['image'])) {
            $link = Router::url('/', true) . 'uploads/coupons/' . $coupon['Coupon']['image'];
        }
        $coupon['Coupon']['image'] = $link;

        echo json_encode(array(
            'success' => 1,
            'message' => 'success',
            'data' => $coupon['Coupon'],
        ));
    }
}