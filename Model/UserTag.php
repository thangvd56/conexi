<?php

App::uses('AppModel', 'Model');

class UserTag extends AppModel {

    public $primary_key = 'id';
    public $useTable = 'user_tags';
    
    public $belongsTo = array(
        'User' , 'Tag'
    );

    public function checkExist($customer_id="", $tag_id=""){
    	$user_tag = $this->find('first', array(
            'conditions' => array(
                'user_id' => $customer_id,
                'tag_id' => $tag_id,
            ),
            'recursive' => -1,
        ));
    	return empty($user_tag)? true : false;
    }

}
