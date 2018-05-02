<?php

App::uses('AppModel', 'Model');

class Group extends AppModel
{
    public $name = 'Group';
    public $actsAs = array('Containable');

    public $hasMany = array(
        'ShopGroup' => array(
            'className' => 'ShopGroup',
            'foreignKey' => 'group_id',
            'dependent' => true
        )
    );
}