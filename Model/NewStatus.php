<?php

/*
 * Model NewsStatus
 * Created 13/ November/2015
 * Channeth
 */
App::uses('AppModel', 'Model');

class NewsStatus extends AppModel {

    public $name = 'NewsStatus';
    public $useTable = 'news_status';
    public $primary_key = 'id';
    public $belongsTo = array(
        'News' => array(
            'className' => 'News',
            'foreignKey' => 'news_id'
        )
    );

}
