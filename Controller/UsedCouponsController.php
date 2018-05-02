<?php

App::uses('AppController', 'Controller');

class UsedCouponsController extends AppController
{
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('api_index'));
    }

    public function api_index()
    {
        $this->layout = null;
        $this->autoRender = false;
        $results = $this->UsedCoupon->couponIsUse($this->request->query);

        if ($results) {
            echo json_encode(array(
                'success' => 1,
                'message' => 'data have been save success!',
            ));
        }
    }
}