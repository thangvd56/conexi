<?php
/*
 * Model News
 * Created 13/ November/2015
 * Channeth
 */
App::uses('AppModel','NewsDelivery', 'Model');

class Notice extends AppModel
{
    public $name        = 'Notice';
    public $useTable    = 'news';
    public $primary_key = 'id';
    public $validate    = array(
        'title' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Title is required'
            ),
            'min_length' => array(
                'rule' => array('minLength', '5'),
                'message' => 'title must be at least 5 characters',
            )
        ),
        'message' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Content is required'
            )
        ),
        'target' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Target is required'
            )
        )
    );
    
}