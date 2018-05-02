<?php

App::uses('AppModel', 'Model');

class Setting extends AppModel {

    public $primary_key = 'id';
    public $name = 'Setting';
    public $useTable = 'function_settings';
    
}