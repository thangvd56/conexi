<?php

class ApiStaffsController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'index',
            'staff_info'
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->autoRender = false;
    }

    public function staff_info()
    {
        $this->loadModel('Staff');
        $shop_id = $this->request->query('shop_id');
        try {
            $staffs = $this->Staff->find('all', array(
                'conditions' => array(
                    'Staff.shop_id' => $shop_id,
                    'Staff.published <>' => 0,
                    'Staff.is_deleted <>' => 1
                ),
                'order' => array('Staff.sort'=>'ASC')
            ));
            $staff_arr = array();
            if ($staffs) {
                foreach ($staffs as $value) {
                    $staff = array(
                        'id' => $value['Staff']['id'],
                        'image' => HTTP . $_SERVER['HTTP_HOST'] . $this->webroot . 'uploads/staffs/' . $value['Staff']['image'],
                        'name' => $value['Staff']['name'],
                        'introduction' => $value['Staff']['introduction'],
                        'position' => $value['Staff']['position'],
                        'skillful' => $value['Staff']['skillful'],
                        'technique' => $value['Staff']['technique'],
                        'hobbies' => $value['Staff']['hobbies'],
                        'is_at_work' => $value['Staff']['is_at_work']
                    );
                    array_push($staff_arr, $staff);
                }
            }
            echo json_encode(array(
                'staffs' => $staff_arr,
                'success' => 1,
                'message' => 'Successful',
            ));
        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage(),
            ));
        }
        $this->autoRender = false;
    }
}