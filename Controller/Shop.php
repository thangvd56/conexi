<?php

App::uses('AppModel', 'Model');

class Shop extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Shop name is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This shop name is already exist'
            ),
        ),
    );

    public $hasMany = array(
        'AppInformation' => array(
            'dependent' => true
        ),
        'StampSetting' => array(
            'dependent' => true
        ),
        'Staff' => array(
            'dependent' => true
        ),
        'Reservation' => array(
            'dependent' => true
        ),
        'UserShop' => array(
            'dependent' => true
        )
    );

    public function updateNews() {
        App::import('Model', 'News');
        $News = new News();
        $News->unbindModel(array('hasMany' => array('NewsStatus', 'NewsDelivery')));
        
        $dataNews = $News->find('all', array(
            'contain' => array(
                'User' => array(
                    'conditions' => array('User.status' => 1),
                )
            ),
            'conditions' => array('News.type' => 'notice_setting')
        ));

        foreach ($dataNews as $key1 => $value1) {
            if (empty($value1) || !isset($value1['User']['id']) || empty($value1['User']['id'])) {
                continue;
            }

            if ($value1['New']['delivery_date_value'] != date('Y-m-d')) {
                continue;
            }

            App::import('Model', 'Shop');
            $Shop = new Shop();

            $Shop->recursive = -1;
            $dataShop = $Shop->findByUserId($value1['User']['id']);

            if (empty($dataShop)) {
                continue;
            }

            App::import('Model', 'UserShop');
            $userShop = new UserShop();

            $userShop->recursive = -1;
            $dataUserShop = $userShop->findByShopIdAndIsDisabled($dataShop['Shop']['id'], 0);

            if (empty($dataUserShop)) {
                continue;
            }

            App::import('Model', 'NewsDelivery');
            $NewsDelivery = new NewsDelivery();

            $NewsDelivery->create();
            $saveNewsDeliveryFields = array('news_id' => $value1['News']['id'], 'user_id' => $value1['User']['id'], 'is_published' => 1);

            if ($NewsDelivery->save(array('NewsDelivery' => $saveNewsDeliveryFields))) {
                $News->id = $value1['News']['id'];
                $updateNewFields = array('is_disabled' => 1);
                $News->save(array('New' => $updateNewFields));
            }
        }
    }
}
