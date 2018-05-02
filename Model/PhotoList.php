<?php

App::uses('AppModel', 'Model');

class PhotoList extends AppModel {

    public $primary_key = 'id';
    public $useTable = 'photo_lists';
    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => 'notBlank',
                'messsage' => 'Title is required'
            ),
        ),
        'content' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Content is required'
            )
        ),
        'price' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Price is required'
            )
        )
    );
    public $belongsTo = array(
        'Photo' => array(
            'clasName' => 'Photo',
            'foreignKey' => 'photo_id'
        )
    );

}
