<?php

App::uses('AppModel', 'Model');

class UserShop extends AppModel {

    public $name = 'UserShop';
    public $useTable = 'user_shops';
    public $primary_key = 'id';
    public $actsAs = array('Containable');

    public $belongTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Shop' => array(
            'className' => 'Shop',
            'foreignKey' => 'shop_id'
        )
       
    );
    public $hasMany = array(
            'Reservation' => array(
                'foreignKey' => 'user_id',
            )
    );

    //get shop id by its customer id.
    public function getShopId($user_id)
    {
        return $this->find('first', array(
            'fields' => array('UserShop.shop_id'),
            'conditions' => array('UserShop.user_id' => $user_id, 'UserShop.is_disabled' => 0),
            'recursive' => -1
        ));
    }

    public function getAllCustomersByShopId($shop_id) {
        return $this->find('all', array(
            'conditions' => array(
                'is_disabled' => 0,
                'shop_id' => $shop_id
            ),
            'recursive' => -1
        ));
    }

    public function getSingleCustomer($user_id) {
        return $this->find('first', array(
            'conditions' => array(
                'UserShop.is_disabled' => 0,
                'UserShop.user_id' => $user_id
            ),
            'recursive' => -1
        ));
    }

    //In case has only one shop
    public function get_shopid($user_id) {
        $sql = $this->query("SELECT shop_id FROM user_shops WHERE user_id ='" . $user_id . "'");
        return $sql;
    }
    public function  get_usershop_id($user_id){
         $sql = $this->query("SELECT id FROM shops WHERE user_id ='" . $user_id . "'");
        return $sql;
    }
}
