<?php

App::uses('AppModel', 'Model');

class AppInformation extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        
    );
    public $belongsTo = array(
        'Shop' => array(
            'className' => 'Shop',
            'foreignKey' => 'shop_id'
        )
    );

}
