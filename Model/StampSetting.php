<?php

App::uses('AppModel', 'Model');

class StampSetting extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
    );
    public $belongsTo = array(
        'Shop' => array(
            'className' => 'Shop',
            'foreignKey' => 'shop_id'
        )
    );
     public $hasMany = array(
            'Stamp' => array(
                'foreignKey' => 'stamp_setting_id',
            )
    );

    public function stampSettingExpire()
    {
        App::import('Model', 'Stamp');
        $Stamp = new Stamp();
        $this->recursive = -1;
        $stamp_setting = $this->find('all', array(
            'conditions' => array(
                'StampSetting.expire_day' => date('Y-m-d H:i').':00'
            )
        ));
        if (!empty($stamp_setting)) {
            foreach ($stamp_setting as $key1 => $value1) {
                if (empty($value1)) {
                    continue;
                }
                $Stamp->updateAll(array('delete_flag' => 1), array('stamp_setting_id' => $value1['StampSetting']['id']));
            }
        }
       
    }
}
