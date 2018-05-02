<?php
App::uses('File', 'Utility');

class ApiSnsSharesController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->RequestHandler->ext = 'json';
        $this->Auth->allow(array(
            'sns_share',
            'index'
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->loadModel('SnsShare');
        $shop_id = $this->request->query('shop_id');
        $data = $this->SnsShare->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'shop_id' => $shop_id
            )
        ));
        
        if (!$data) {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Error'
            ));
        } else {
            $param = array();
            foreach ($data as $value) {
                $param['id'] = $value['id'];
                $param['title'] = $value['title'];
                $param['description'] = $value['description'];
                $param['home_url'] = $value['home_page_url'];
                $param['ios_url'] = $value['ios_download_url'];
                $param['android_url'] = $value['android_download_url'];
            }
            echo json_encode(array(
                'id' => $param['id'],
                'title' => $param['title'],
                'description' => $param['description'],
                'home_ur' => $param['home_url'],
                'ios_url' => $param['ios_url'],
                'android_url' => $param['android_url'],
                'success' => 1,
                'message' => 'Successful'
            ));
        }
        $this->autoRender = false;
    }
}