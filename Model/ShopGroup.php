<?php

App::uses('AppModel', 'Model');

class ShopGroup extends AppModel
{
    public $name = 'ShopGroup';

    public $belongsTo = array(
        'Group' => array(
            'className' => 'Group',
            'foreignKey' => 'group_id'
        )
    );
    public $actsAs = array('Containable');
}
