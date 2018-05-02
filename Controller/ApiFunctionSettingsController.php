<?php
App::uses('File', 'Utility');

class ApiFunctionSettingsController extends AppController
{

    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->RequestHandler->ext = 'json';
        $this->Auth->allow(array('index'));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->request->query('user_id');
        $arr_function_setting = array();
        $arr_image_media = array();

        $this->loadModel('Shop');
        $shop_info = $this->Shop->find('first', array(
            'conditions' => array('id' => $shop_id),
            'recursive' => -1
        ));

        $this->loadModel('NewsDelivery');
        $news_badge = $this->NewsDelivery->find('count', array(
            'conditions' => array(
                'NewsDelivery.is_read' => 0,
                'NewsDelivery.is_deleted' => 0,
                'NewsDelivery.user_id' => $user_id,
                'NewsDelivery.delivered_date <=' => date('Y-m-d H:i')
            )
        ));

        $this->loadModel('Reservation');
        $medical_badge = $this->Reservation->find('count', array(
            'conditions' => array(
                'AND' => array(
                    'Reservation.is_read' => 0,
                    'Reservation.user_id' => $user_id,
                    'Reservation.shop_id' => $shop_id,
                    'Reservation.is_completed' => 1,
                    'Reservation.is_deleted' => 0,
                    'Reservation.is_checkin' => 1
                )
            ),
            'recursive' => -1
        ));

        $this->loadModel('Media');
        $media = $this->Media->find('all', array(
            'conditions' => array(
                'AND' => array(
                    'external_id' => $shop_id,
                    'model' => 'shops'
                )),
            'order' => 'file_order ASC',
            'recursive' => -1
        ));

        $this->loadModel('FunctionSetting');
        $data = $this->FunctionSetting->find('all', array(
            'recursive' => -1,
            'conditions' => array(
                'AND' => array(
                    'shop_id' => $shop_id,
                    'active' => 1
                )),
            'order' => array('function_index ASC')
        ));

        foreach ($data as $key => $value) {
            $arr = array(
                'id' => $value['FunctionSetting']['id'],
                'function_image' => Router::url('/', true) . 'uploads/function_setting/' . $value['FunctionSetting']['function_image'],
                'function_name' => $value['FunctionSetting']['function_name'],
                'function_tag' => $value['FunctionSetting']['function_tag']
            );
            $arr_function_setting[] = $arr;
        }

        foreach ($media as $value) {
            $arr = array(
                'shop_image' => Router::url('/', true) . 'uploads/photo_informations/' . $value['Media']['file']
            );
            
            $arr_image_media[] = $arr;
        }

        if (!$data) {
            echo json_encode(array('success' => 0, 'message' => 'Error'));
        } else {
            $shop = $this->Shop->find('first', array(
                'conditions' => array(
                    'Shop.id' => $shop_id,
                ),
                'recursive' => -1,
            ));
            $web_reservation = $shop ? $shop['Shop']['web_reservation'] : '';
            echo json_encode(array(
                'introduction' => $shop_info['Shop']['introduction'],
                'shop_name' => $shop_info['Shop']['shop_name'],
                'phone' => $shop_info['Shop']['phone'],
                'image_url' => $arr_image_media,
                'web_reservation' => $web_reservation,
                'function_setting' => $arr_function_setting,
                'news_badge' => $news_badge,
                'medical_badge' => $medical_badge,
                'success' => 1,
                'message' => 'Successful'
            ));
        }
        $this->autoRender = false;
    }
}