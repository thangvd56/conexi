<?php
App::uses('AppModel', 'Model');

class Area extends AppModel
{
    public $name = 'Area';
    public $useTable = 'areas';
    public $primary_key = 'id';

    public function getAreaDropdown()
    {
        return $this->find('list', array(
            'fields' => array('Area.id', 'Area.name'),
            'recursive' => -1
        ));
    }
}
