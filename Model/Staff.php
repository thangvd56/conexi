<?php

App::uses('AppModel', 'Model');

class Staff extends AppModel {

    public $primary_key = 'id';
    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Name is required'
            ),
            'max_length' => array(
                'rule' => array('maxLength', '15'),
                'message' => 'Name must be less than or equal to 10 characters',
            )
        ),
        'introduction' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Introduction is required'
            ),
            'max_length' => array(
                'rule' => array('maxLength', '300'),
                'message' => 'Introduction must be less than or equal to 300 characters',
            )
        )
    );
    public $belongTo = array(
        'className' => 'Shop',
        'foreignKey' => 'shop_id'
    );
    public $hasMany = array(
           'Reservation' => array(
               'dependent' => true
           ),
       );
    public function getStaff($shop_id) {

        $staff = $this->find('all', array(
            'conditions' => array(
                'shop_id' => $shop_id,
                'published' => 1,
                //'is_at_work' => 1,
                'is_deleted <>' => 1
            ),
            'recursive' => -1,
            'order' => 'Staff.sort ASC'
        ));

        return $staff;
    }

}
