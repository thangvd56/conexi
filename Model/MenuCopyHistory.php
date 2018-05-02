<?php

App::uses('AppModel', 'Model');

class MenuCopyHistory extends AppModel {

    public $name = 'MenuCopyHistory';
    public $hasMany = array(
        'MenuItemCopyHistory' => array(
            'className' => 'MenuItemCopyHistory',
            'foreignKey' => 'copy_history_id',
            'dependent' => true
        )
    );
}
