<?php
/*
 * Model News
 * Created 13/ November/2015
 * Channeth
 */
App::uses('AppModel', 'Model');

class NewsDelivery extends AppModel
{
    public $useTable    = 'news_deliveries';
    public $primary_key = 'id';

    public $belognTo    = array(
        'News' => array(
            'className' => 'News',
            'foreign_key' => 'news_id'
        ),
    );
}