<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Genre extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        'genre' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Ip is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This Ip is already exist'
            ),
        ),
        'remarks' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Remarks is required'
            ),
            'min_length' => array(
                'rule' => array('minLength', '5'),
                'message' => 'Remarks must be at least 5 characters',
            )
        ),
    );
    public $hasOne = array(
        'Tag' => array(
            'className' => 'Tag',
            'dependent' => true
        ),
        'GenreFunction' => array(
            'className' => 'GenreFunction',
            'dependent' => true
        )
    );

}
