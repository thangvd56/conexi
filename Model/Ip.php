<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Ip extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        'ip' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Ip is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This Ip is already exist'
            ),
        ),
        'ramarks' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Ramarks is required'
            ),
            'min_length' => array(
                'rule' => array('minLength', '5'),
                'message' => 'Ramarks must be at least 5 characters',
            )
        ),
    );

}
