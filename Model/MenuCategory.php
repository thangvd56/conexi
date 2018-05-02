<?php

App::uses('AppModel', 'Model');

class MenuCategory extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => 'notBlank',
                'messsage' => 'Title is required'
            )
        )
    );
    public $hasMany = array(
        'ApplicationMenuList' => array(
            'className' => 'ApplicationMenuList',
            'foreignKey' => 'menu_category_id',
            'dependent' => true
        )
    );

}
