<?php
App::uses('AppController', 'Controller');

class StampsController extends AppController
{
    public $helpers    = array(
        'Js' => array('Jquery'),
        'Paginator',
        'Html',
        'Form',
        'Flash');
    public $components = array(
        'Paginator',
        'Flash',
        'Session',
        'FileUpload',
        'RequestHandler');
    public $fileType   = array(
        'gif',
        'jpeg',
        'png',
        'jpg');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->loadModel('StampSetting');
        $this->loadModel('Stamp');
        $stamp_settings = $this->StampSetting->find("all",
            array(
            "joins" => array(
                array(
                    "table" => "stamps",
                    "alias" => "Stamp",
                    "type" => "INNER",
                    "conditions" => array(
                        "StampSetting.id = Stamp.stamp_setting_id",
                    )
                ),
            ),
            'conditions' => array(
                'Stamp.user_id' => $this->Auth->user('id'),
            ),
            'order' => array('StampSetting.id' => 'desc'),
        ));
        $this->set('stamp_setting', $stamp_settings);
    }
}