<?php

App::uses('AppModel', 'Model');

class Chair extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
    );
    public $hasMany = array(
           'Reservation' => array(
               'dependent' => true
           ),
       );
    public function getChairData($mode = null, $shop_id = null)
    {

        $chair = $this->find('all', array(
            'fields' => array('Chair.chair_name', 'Chair.id', 'Chair.capacity'),
            'conditions' => array('Chair.is_deleted' => 0, 'Chair.shop_id' => $shop_id)
        ));

        $json['data'] = array();
        foreach ($chair as $key => $value) {
            $json['data'][$key]['key'] = $value['Chair']['id'];
            $json['data'][$key]['label'] = "''";
        }

        if ($mode === 'json') {
            return str_replace('"', '',json_encode($json['data']));
        } else {
            return $chair;
        }
    }

}
