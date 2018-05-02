<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class GenreFunction extends AppModel {

    public $primary_key = 'id';
    public $belongsTo = array(
        'Genre' => array(
            'className' => 'Genre',
            'foreignKey' => 'genre_id'
        )
    );

//    public $validate = array(
//        'tag' => array(
//            'required'=>array(
//                'rule' => 'notBlank',
//                'message' => 'Genre is required'
//            ),
//            'unique'=>array(
//                'rule' => 'isUnique',
//                'message' => 'This Genre is already exist'
//            ),
//        ),
//        'remark' => array(
//            'required'=>array(
//                'rule' => 'notBlank',
//                'message' => 'Remarks is required'
//            ),
//            'min_length'=>array(
//                'rule' => array('minLength', '5'),
//                'message' => 'Remarks must be at least 5 characters',
//            )
//        ),
//
//
//    );
}
