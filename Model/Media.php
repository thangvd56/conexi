<?php

/*
 * Model Media
 * Created 13/ November/2015
 * Channeth
 */
App::uses('AppModel', 'Model');

class Media extends AppModel {

    public $name = 'Media';
    public $useTable = 'media';
    public $primary_key = 'id';
    public $validate = array(
        'file' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'File is required'
            )
        )
    );

}
