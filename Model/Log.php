<?php

App::uses('AppModel', 'Model');

class Log extends AppModel{
    public $name = 'Log';
    public $useTable = 'logs';
    public $primary_key = 'id';
    
    
}