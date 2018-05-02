<?php

App::uses('AppModel', 'Model');

class Photo extends AppModel {

    public $primary_key = 'id';
    public $useTable = 'photos';
    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => 'notBlank',
                'messsage' => 'Title is required'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Title is already exist'
            )
        )
    );
    public $belongsTo = array(
        'Shop' => array(
            'clasName' => 'Shop',
            'foreignKey' => 'shop_id'
        ),
    );
    public $hasMany = array(
        'PhotoList' => array(
            'className' => 'PhotoList',
            'dependent' => true
        )
    );

}
