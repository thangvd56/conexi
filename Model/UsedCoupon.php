<?php

App::uses('AppModel', 'Model');

class UsedCoupon extends AppModel
{
    public $primary_key = 'id';

    public function couponIsUse($params)
    {
        if (isset($params['coupon_id']) && isset($params['user_id'])) {
            if ($this->save($params)) {
                return true;
            }
        }
    }
}