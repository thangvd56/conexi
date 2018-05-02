<?php

App::uses('AppModel', 'Model');

class Stamp extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
    );
    public $belongTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'StampSetting' => array(
            'foreignKey' => 'stamp_setting_id '
        )
    );
   
}
