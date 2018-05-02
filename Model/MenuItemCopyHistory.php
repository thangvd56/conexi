<?php

App::uses('AppModel', 'Model');

class MenuItemCopyHistory extends AppModel {

    public $name = 'MenuItemCopyHistory';
    public $belongsTo = array(
        'MenuCopyHistory' => array(
            'className' => 'MenuCopyHistory',
            'foreignKey' => 'copy_history_id'
        )
    );
}
