<?php

App::uses('AppModel', 'Model');

class ApplicationMenuList extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => 'notBlank',
                'messsage' => 'Title is required'
            )
        ),
        'content' => array(
            'required' => array(
                'rule' => 'notBlank',
                'messsage' => 'Content is required'
            )
        ),
        'price' => array(
            'required' => array(
                'rule' => 'notBlank',
                'messsage' => 'Price is required'
            )
        )
    );
    public $belongsTo = array(
        'MenuCategory' => array(
            'className' => 'MenuCategory',
            'foreignKey' => 'menu_category_id'
        )
    );

}
