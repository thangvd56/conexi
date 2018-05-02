<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Plan extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Plan is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This Plan is already exist'
            )
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
        'genre_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Role is required'
            )
        ),
        'plan_type' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Plan type is required'
            )
        )
    );
    public $hasOne = array(
        'PlanFunction' => array(
            'className' => 'PlanFunction',
            'dependent' => true
        )
    );

}
