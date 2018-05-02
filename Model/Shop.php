<?php
App::uses('AppModel', 'Model');

class Shop extends AppModel
{
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

    public function ShopBelongTo($user_id = null, $shop_id = null)
    {
        $count = $this->find('count', [
            'conditions' => [
            'is_deleted <>' => 1,
            'id' => $shop_id,
            'user_id' => $user_id
            ],
            'recursive' => -1,
        ]);
        if ($count <= 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * get shop dropdown or just shop belong to a headquarter or a user.
     * @param integer|array $user_id
     * @return array
     */
    public function getShopIdByUser($user_id = null)
    {
        return $this->find('list', array(
            'fields' => array('Shop.id', 'Shop.shop_name'),
            'conditions' => array(
                'is_deleted <>' => 1,
                'user_id' => $user_id
            ),
            'recursive' => -1,
        ));
    }

    /**
     * fetch single shop id based on user id.
     * @param integer $user_id
     * @return array
     */
    public function getOwnerShopId($user_id = null)
    {
        return $this->find('first', array(
            'fields' => array('Shop.id'),
            'conditions' => array(
                'is_deleted <>' => 1,
                'user_id' => $user_id
            ),
            'recursive' => -1,
        ));
    }

    public function getShopInfo($id)
    {
        return $this->find('first', array(
            'conditions' => array('Shop.id' => $id, 'Shop.is_deleted' => 0),
            'recursive' => -1
        ));
    }
}