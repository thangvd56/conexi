<?php

App::uses('AppController', 'Controller');

class MenuCopiesController extends AppController
{
    public $title_for_layout = 'メニューコピー';
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->authorize = 'Controller';

        if($this->Auth->user('role') !== ROLE_HEADQUARTER) {
            throw new NotFoundException();
        }
    }

    public function index()
    {
        $this->loadModel('Shop');
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

        $this->set(array('data' => $groups));
    }

    public function create()
    {
        if ($this->request->data('MenuCopy')) {
            $this->loadModel('MenuCategory');
            $this->loadModel('ApplicationMenuList');
            $this->loadModel('MenuCopyHistory');
            $this->loadModel('MenuItemCopyHistory');
            foreach ($this->request->data('MenuCopy') as $key => $value) {
                if (isset($value['fromShop'])) {
                    $menu_from_shop = $this->MenuCategory->find('all', array(
                        'conditions' => array(
                            'shop_id' => $value['fromShop']
                        )
                    ));

                    if (isset($value['toShop'])) {
                        $menu_copy = array();
                        $menu_copy['MenuCopy']['group_id'] = $value['group_id'];
                        $menu_copy['MenuCopy']['from_shop_id'] = $value['fromShop'];
                        $menu_copy['MenuCopy']['copy_all'] = $value['copy_all'];
                        $menu_copy['MenuCopy']['to_shop_id'] = json_encode($value['toShop']);

                        $this->MenuCopy->create();
                        if ($this->MenuCopy->save($menu_copy)) {
                            $copy_id = $this->MenuCopy->getLastInsertId();
                            foreach ($value['toShop'] as $key2 => $value2) {
                                if ($value2 == $value['fromShop']) {
                                    continue;
                                }
                                //save log first befor eplace the new copy
                                $menu_shop = $this->MenuCategory->find('all', array(
                                    'conditions' => array(
                                        'shop_id' => $value2
                                    )
                                ));
                                $lst_menu_id = array();
                                
                                foreach ($menu_shop as $key3 => $value3) {
                                    $menu_copy_history = array();
                                    $menu_copy_history['MenuCopyHistory']['copy_id'] = $copy_id;
                                    $menu_copy_history['MenuCopyHistory']['shop_id'] = $value3['MenuCategory']['shop_id'];
                                    $menu_copy_history['MenuCopyHistory']['image'] = $value3['MenuCategory']['image'];
                                    $menu_copy_history['MenuCopyHistory']['title'] = $value3['MenuCategory']['title'];
                                    $menu_copy_history['MenuCopyHistory']['published'] = $value3['MenuCategory']['published'];
                                    $menu_copy_history['MenuCopyHistory']['is_deleted'] = $value3['MenuCategory']['is_deleted'];
                                    $menu_copy_history['MenuCopyHistory']['sort'] = $value3['MenuCategory']['sort']?$value3['MenuCategory']['sort']:0;
                                    $menu_copy_history['MenuCopyHistory']['is_display_list'] = $value3['MenuCategory']['is_display_list'];
                                    $lst_menu_id[] = $value3['MenuCategory']['id'];
                                    $this->MenuCopyHistory->create();
                                    if ( $this->MenuCopyHistory->save($menu_copy_history)) {
                                        if ($value3['ApplicationMenuList']) {
                                            $menu_item_copy_history = array();
                                            $MenuCopyHistory_id = $this->MenuCopyHistory->getLastInsertId();
                                            foreach ($value3['ApplicationMenuList'] as $key5 => $value5) {
                                                $menu_item_copy_history['MenuItemCopyHistory']['copy_history_id'] = $MenuCopyHistory_id;
                                                $menu_item_copy_history['MenuItemCopyHistory']['shop_id'] = $value3['MenuCategory']['shop_id'];
                                                $menu_item_copy_history['MenuItemCopyHistory']['title'] = $value5['title'];
                                                $menu_item_copy_history['MenuItemCopyHistory']['content'] = $value5['content'];
                                                $menu_item_copy_history['MenuItemCopyHistory']['price'] = $value5['price'];
                                                $menu_item_copy_history['MenuItemCopyHistory']['image'] = $value5['image'];
                                                $menu_item_copy_history['MenuItemCopyHistory']['published'] = $value5['published'];
                                                $menu_item_copy_history['MenuItemCopyHistory']['is_deleted'] = $value5['is_deleted'];
                                                $menu_item_copy_history['MenuItemCopyHistory']['sort'] = $value5['sort'];
                                                $this->MenuItemCopyHistory->create();
                                                $this->MenuItemCopyHistory->save($menu_item_copy_history);
                                            }
                                        }
                                    }
                                }
                                // delete the existing
                                if ($this->MenuCategory->deleteAll(array('shop_id' => $value2), true)) {
                                   // $this->ApplicationMenuList->deleteAll(array('menu_category_id' => $lst_menu_id), false);
                                }
                                // save the new copy
                                foreach ($menu_from_shop as $key4 => $value4) {
                                     unset($value4['MenuCategory']['id']);
                                     $value4['MenuCategory']['shop_id'] = $value2;
                                     $this->MenuCategory->create();
                                     if ($this->MenuCategory->save($value4)) {
                                         $MenuCategory_id = $this->MenuCategory->getLastInsertId();
                                         if ($value4['ApplicationMenuList']) {
                                             foreach($value4['ApplicationMenuList'] as $key6 => $value6) {
                                                unset($value6['id']);
                                                $value6['menu_category_id'] = $MenuCategory_id;
                                                if (!$value6['sort']) {
                                                    $value6['sort'] = 0;
                                                }
                                                if (!$value6['content']) {
                                                    unset($value6['content']);
                                                }
                                                $this->ApplicationMenuList->create();
                                                $this->ApplicationMenuList->save($value6);
                                             }
                                         }
                                     }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->Session->setFlash('Copy Completed.', 'success');
        $this->redirect(array('controller' => 'menuCopies', 'action' => 'index'));
    }

    public function history()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }
        $this->layout = 'ajax';
        $this->loadModel('Shop');
        $this->loadModel('User');
        $this->loadModel('Group');
        $this->loadModel('ShopGroup');
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

        $data = $this->MenuCopy->find('all', array(
            'conditions' => array(
                'from_shop_id' => $Shops,
                'group_id' => $group_ids
            ),
            'order' => 'created DESC',
        ));

        foreach ($data as $key => $value) {
            $group = $this->Group->findById($value['MenuCopy']['group_id']);
            if ($group) {
                $data[$key]['MenuCopy']['group_id'] = $group['Group']['id'];
                $data[$key]['MenuCopy']['group_name'] = $group['Group']['name'];
            }

            $from_shop = $this->Shop->findById($value['MenuCopy']['from_shop_id']);
            if ($from_shop) {
                $data[$key]['MenuCopy']['from_shop_name'] = $from_shop['Shop']['shop_name'];
            }
            $shop_ids = json_decode($value['MenuCopy']['to_shop_id']);

            foreach ($shop_ids as $key2 => $value2) {
                $to_shop = $this->Shop->findById($value2);
                if ($to_shop) {
                    $data[$key]['MenuCopy']['to_shop_name'][] = $to_shop['Shop']['shop_name'];
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
        if ($this->request->data('shop_id')) {
            $this->loadModel('MenuCategory');
            $this->loadModel('ApplicationMenuList');
            $this->loadModel('MenuCopyHistory');
            $this->loadModel('MenuItemCopyHistory');
            $shop_id = json_decode($this->request->data('shop_id'));
            foreach ($shop_id as $key => $value) {
                $this->MenuCategory->deleteAll(array('shop_id' => $value), true);
                $menu = $this->MenuCopyHistory->find('all', array(
                    'conditions' => array(
                        'MenuCopyHistory.copy_id' => $this->request->data('copy_id'),
                        'MenuCopyHistory.shop_id' => $value
                    )
                ));
                if($menu) {
                    foreach ($menu as $key2 => $value2) {
                        //delete the existing
                        $data = array();

                        $data['MenuCategory']['shop_id'] = $value2['MenuCopyHistory']['shop_id'];
                        $data['MenuCategory']['image'] = $value2['MenuCopyHistory']['image'];
                        $data['MenuCategory']['title'] = $value2['MenuCopyHistory']['title'];
                        $data['MenuCategory']['published'] = $value2['MenuCopyHistory']['published'];
                        $data['MenuCategory']['is_deleted'] = $value2['MenuCopyHistory']['is_deleted'];
                        $data['MenuCategory']['sort'] = $value2['MenuCopyHistory']['sort']?$value2['MenuCopyHistory']['sort']:0;
                        $data['MenuCategory']['is_display_list'] = $value2['MenuCopyHistory']['is_display_list'];

                        //save the new copy
                        $this->MenuCategory->create();
                        if ($this->MenuCategory->save($data)) {
                            if ($value2['MenuItemCopyHistory']) {
                                $Menu_id = $this->MenuCategory->getLastInsertId();
                                foreach ($value2['MenuItemCopyHistory'] as $key3 => $value3) {
                                    $menu_item = array();
                                    $menu_item['ApplicationMenuList']['menu_category_id'] = $Menu_id;
                                    $menu_item['ApplicationMenuList']['title'] = $value3['title'];
                                    $menu_item['ApplicationMenuList']['content'] = $value3['content'];
                                    $menu_item['ApplicationMenuList']['price'] = $value3['price'];
                                    $menu_item['ApplicationMenuList']['image'] = $value3['image'];
                                    $menu_item['ApplicationMenuList']['published'] = $value3['published'];
                                    $menu_item['ApplicationMenuList']['is_deleted'] = $value3['is_deleted'];
                                    $menu_item['ApplicationMenuList']['sort'] = $value3['sort'];
                                    $this->ApplicationMenuList->create();
                                    $this->ApplicationMenuList->save($menu_item);
                                }
                            }
                        }
                    }
                }
            }
            $this->Session->setFlash('Restore Completed.', 'success');
            //$this->redirect(array('controller' => 'menuCopies', 'action' => 'index'));
        }
    }
}