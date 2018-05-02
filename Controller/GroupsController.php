<?php

App::uses('AppController', 'Controller');

class GroupsController extends AppController
{
    public function beforeFilter()
    {
        $this->loadModel('User');
        parent::beforeFilter();
        $this->Auth->allow(array(
            'api_index',
        ));
        $this->Auth->authorize = 'Controller';
        if($this->Auth->user('role') !== ROLE_HEADQUARTER && $this->request->query('api')) {
            throw new NotFoundException();
        }
    }

    public function index()
    {
        $this->loadModel('Shop');
        $options['conditions'] = [
            'Group.user_id' => $this->Auth->user('id'),
        ];
        $groups = $this->Group->find('all', $options);
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
        $this->set('data', $groups);
    }

    /**
     * group name can be duplicated for other headquarter but not allow the same
     * group name for one headquarter
     * @return type
     * @throws NotFoundException
     */
    public function create()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }
        $this->autoRender = false;
        $data = $this->request->data;
        if ($data) {
            foreach ($data['names'] as $item) {
                if (empty($item)) {
                    continue;
                }
                $filterd = $this->Group->find('first', array(
                    'conditions' => array(
                        'name' => $item['name'],
                        'user_id' => $this->Auth->user('id')
                    ),
                ));
                if (!$filterd) {
                    if (!isset($item['id'])) {
                        $group['name'] = $item['name'];
                        $group['deleted'] = '0';
                        $group['user_id'] = $this->Auth->user('id');
                        $this->Group->create();
                        $this->Group->save($group);
                    } else {
                        $data1 = array('id' => $item['id'], 'name' => $item['name']);
                        $this->Group->save($data1);
                    }
                } else {
                    $this->Session->setFlash(DUPLICATE_DATA, 'error');
                }
            }
        } else {
            $this->Session->setFlash(EMPTY_DATA, 'error');
        }
        return $this->redirect('/groups');
    }

    public function addShops($group_id = null) {
        $group = $this->Group->find('first', array(
            'fields' => array(
                'Group.id', 'Group.name'
            ),
            'conditions' => array(
                'Group.id' => $group_id,
                'Group.user_id' => $this->Auth->user('id'),
                'Group.deleted' => 0
            ),
            
        ));
        
        if (empty($group)) {
            throw new NotFoundException();
        }

        if (!empty($group['ShopGroup'])) {
            $shop_id = Hash::extract($group, 'ShopGroup.{n}.shop_id');

            $this->loadModel('Shop');
            $shops = $this->Shop->find('all', array(
                'fields' => array('Shop.id', 'Shop.shop_name'),
                'conditions' => array(
                    'Shop.is_deleted' => 0,
                    'Shop.id' => $shop_id
                ),
                'recursive' => -1
            ));
            $this->set(compact('shops'));
        }

        $this->set(compact('group'));

        if ($this->request->is(array('post', 'put'))) {            
            $this->loadModel('Shop');
            $this->loadModel('ShopGroup');
            $existing_group = $this->Group->find('all', array(
                'conditions' => array(
                    'id <>' => $this->request->data['Group']['id'],
                    'user_id' => $this->Auth->user('id')
                ),
                'recursive' => -1
            ));

            if (!empty($existing_group)) {
                $duplicate_group = false;
                for ($i = 0; $i < count($existing_group); $i++) {
                    if (strcmp($existing_group[$i]['Group']['name'], $this->request->data['Group']['name']) == 0) {
                        $duplicate_group = true;
                        break;
                    }
                }
                
                if ($duplicate_group) {
                    return $this->Session->setFlash(DUPLICATE_DATA, 'error');
                } else {
                    $this->Group->id = $this->request->data['Group']['id'];
                    $this->Group->saveField('name', $this->request->data['Group']['name'], false);
                    
                    if (!empty($this->request->data['Group']['shop_id'])) {
                        for ($i = 0; $i < count($this->request->data['Group']['shop_id']); $i++) {
                            if ($this->request->data['Group']['status'][$i] == 'remove') {
                                $this->ShopGroup->deleteAll(array(
                                    'ShopGroup.shop_id' => $this->request->data['Group']['shop_id'][$i],
                                    'ShopGroup.group_id' => $this->request->data['Group']['id']
                                ));
                            } else if ($this->request->data['Group']['status'][$i] == 'new') {
                                $data['ShopGroup']['shop_id'] = $this->request->data['Group']['shop_id'][$i];
                                $data['ShopGroup']['group_id'] = $this->request->data['Group']['id'];
                                $this->ShopGroup->create();
                                $this->ShopGroup->save($data);
                            }
                        }
                    }
                    $this->Session->setFlash('Success', 'success');
                    $this->redirect(array('controller' => 'groups', 'action' => 'addShops', $group_id));
                }
            } else {
                $this->Group->id = $this->request->data['Group']['id'];
                $this->Group->saveField('name', $this->request->data['Group']['name'], false);

                if (!empty($this->request->data['Group']['shop_id'])) {
                    for ($i = 0; $i < count($this->request->data['Group']['shop_id']); $i++) {
                        if ($this->request->data['Group']['status'][$i] == 'remove') {
                            $this->ShopGroup->deleteAll(array(
                                'ShopGroup.shop_id' => $this->request->data['Group']['shop_id'][$i],
                                'ShopGroup.group_id' => $this->request->data['Group']['id']
                            ));
                        } else if ($this->request->data['Group']['status'][$i] == 'new') {
                            $data['ShopGroup']['shop_id'] = $this->request->data['Group']['shop_id'][$i];
                            $data['ShopGroup']['group_id'] = $this->request->data['Group']['id'];
                            $this->ShopGroup->create();
                            $this->ShopGroup->save($data);
                        }
                    }
                }
                $this->Session->setFlash('Success', 'success');
                $this->redirect(array('controller' => 'groups', 'action' => 'addShops', $group_id));
            }
        }
    }

    public function delete($id = null)
    {
        if ($this->Group->delete($id)) {
            $this->Session->setFlash(MESSAGE_DELETE, 'success');
            return $this->redirect('/groups');
        }
    }

    public function api_index()
    {
        $this->layout = null;
        $this->autoRender = false;
        $this->loadModel('Shop');
        $this->loadModel('ShopGroup');
        $this->loadModel('UserShop');
        $this->loadModel('Media');
        $group_list = $no_group = array();

        $shop = $this->Shop->find('first', array(
            'conditions' => array(
                'Shop.id' => $this->request->query('shop_id'),
            ),
            'recursive' => -1,
        ));

        $user = $this->User->findById($shop ? $shop['Shop']['user_id'] : '');
        $users = array();
        $groups = array();
        $lst_groups_id = array();
        $lst_shop_id_with_group = array();
        $lst_user_id = array();
        if ($user && $user['User']['parent_id']) {
            $users = $this->User->find('list', array(
                'conditions' => array(
                    'User.parent_id' => $user['User']['parent_id'],
                    'User.role' => ROLE_SHOP,
                    'User.confirmed' => 1,
                    'User.status' => 1,
                ),
                'fields' => 'User.id',
                'recursive' => -1,
            ));
            $groups = $this->Group->find('all', array(
                'conditions' => array(
                    'Group.user_id' => $user['User']['parent_id'],
                ),
                'recursive' => -1,
            ));
            $lst_groups_id = $this->Group->find('list', array(
                'conditions' => array(
                    'Group.user_id' => $user['User']['parent_id'],
                ),
                'fields' => 'id',
                'recursive' => -1,
            ));
            
        }

        $shop_groups = $this->ShopGroup->find('all', array(
            'recursive' => -1,
            'conditioins' => array(
                'ShopGroup.group_id' => $lst_groups_id
            )
        ));

        $lst_shop_id_with_group = $this->ShopGroup->find('list', array(
            'recursive' => -1,
            'conditioins' => array(
                'ShopGroup.group_id' => $lst_groups_id
            ),
            'fields' => array('ShopGroup.shop_id')
        ));
        $lst_user_id = $this->Shop->find('list', array(
            'conditions' => array(
                'Shop.id' => $lst_shop_id_with_group,
            ),
            'fields' => array('Shop.user_id'),
            'recursive' => -1,
        ));

        $shop_no_group = $this->Shop->find('all', array(
            'conditions' => array(
                'Shop.shop_name <>' => '',
                'Shop.is_deleted <>' => 1,
                'Shop.user_id' => $users,
                'Shop.user_id <>' => $lst_user_id,
            ),
            'fields' => array(
                'Shop.id',
                'Shop.introduction',
                'Shop.shop_name',
                'Shop.shop_kana',
                'Shop.address',
                'Shop.hours_start',
                'Shop.hours_end',
                'Shop.openning_hours',
                'Shop.holidays',
                'Shop.phone',
                'Shop.fax',
                'Shop.url',
                'Shop.email',
                'Shop.facebook',
                'Shop.line',
                'Shop.latitute',
                'Shop.longtitute',
                'Shop.splash_image',
            ),
            'recursive' => -1,
        ));

        foreach ($groups as $key1 => $value1) {
            $arr = array();
            foreach ($shop_groups as $key2 => $value2) {
                $arr = $this->checkUserById($arr, $value2, $value1, $users);
            }
            $group_list[] = array(
                'group' => $value1['Group']['name'],
                'shop' => $arr,
            );
            unset($arr);
        }

        foreach ($shop_no_group as $item1) {
            $user_shop = $this->UserShop->find('first', array(
                'conditions' => array(
                    'UserShop.shop_id' => $item1['Shop']['id'],
                    'UserShop.user_id' => $this->Auth->user('id'),
                ),
                'recursive' => -1,
            ));
            $item1['Shop']['business_hours'] = $item1['Shop']['openning_hours'];
            $item1['Shop']['web'] = $item1['Shop']['url'];
            $data02 = $user_shop ? $user_shop['UserShop']['is_allow_notification'] : '';
            $data022 = ($data02 == 'true') ? '1' : '0';
            $item1['Shop']['is_allow_notification'] = $data022;

            $media = $this->Media->find('first', array(
                'conditions' => array(
                    'external_id' => $item1['Shop']['id'],
                    'model' => 'shops',
                )
            ));
            if ($media) {
                $link = Router::url('/', true) . 'uploads/photo_informations/' . $media['Media']['file'];
            } else {
                $link = '';
            }
            unset($item1['Shop']['splash_image']);
            unset($$item1['Shop']['openning_hours']);
            unset($item1['Shop']['url']);

            $item1['Shop']['image'] = $link;
            $no_group[] = $item1['Shop'];
        }

        echo json_encode(array(
            'success' => '1',
            'message' => 'success',
            'data' => array('group' => $group_list, 'no_group' => $no_group),
        ));
    }

    public function checkUserById($arr, $shopGroup, $group, $user)
    {
        if ($shopGroup['ShopGroup']['group_id'] == $group['Group']['id']) {
            $shop = $this->Shop->find('all', array(
                'conditions' => array(
                    'Shop.id' => $shopGroup['ShopGroup']['shop_id'],
                    'Shop.shop_name <>' => '',
                    'Shop.is_deleted <>' => 1,
                    'Shop.user_id' => $user,
                ),
                'fields' => array(
                    'Shop.id',
                    'Shop.introduction',
                    'Shop.shop_name',
                    'Shop.shop_kana',
                    'Shop.address',
                    'Shop.hours_start',
                    'Shop.hours_end',
                    'Shop.openning_hours',
                    'Shop.holidays',
                    'Shop.phone',
                    'Shop.fax',
                    'Shop.url',
                    'Shop.email',
                    'Shop.facebook',
                    'Shop.line',
                    'Shop.twitter',
                    'Shop.latitute',
                    'Shop.longtitute',
                    'Shop.splash_image',
                ),
                'recursive' => -1,
            ));
            $shop[0]['Shop']['business_hours'] = $shop[0]['Shop']['openning_hours'];
            $shop[0]['Shop']['web'] = $shop[0]['Shop']['url'];
            $user_shop = $this->UserShop->find('first', array(
                'conditions' => array(
                    'UserShop.shop_id' => $shop[0]['Shop']['id'],
                    'UserShop.user_id' => $this->Auth->user('id'),
                ),
                'recursive' => -1,
            ));
            $data01 = $user_shop ? $user_shop['UserShop']['is_allow_notification'] : '';
            $data011 = ($data01 == 'true') ? '1' : '0';
            $shop[0]['Shop']['is_allow_notification'] = $data011;

            $media = $this->Media->find('first', array(
                'conditions' => array(
                    'external_id' => $shop[0]['Shop']['id'],
                    'model' => 'shops',
                )
            ));
            if ($media) {
                $link = Router::url('/', true) . 'uploads/photo_informations/' . $media['Media']['file'];
            } else {
                $link = '';
            }
            unset($shop[0]['Shop']['splash_image']);
            unset($shop[0]['Shop']['openning_hours']);
            unset($shop[0]['Shop']['url']);

            $shop[0]['Shop']['image'] = $link;
            if (!empty($shop)) {
                $arr[] = $shop[0]['Shop'];
            }
        }
        return $arr;
    }
}