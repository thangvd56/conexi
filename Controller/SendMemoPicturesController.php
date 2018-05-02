<?php

App::uses('AppController', 'Controller');

class SendMemoPicturesController extends AppController {

    public $components = array('FileUpload');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function index() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            if ($this->request->is('post')) {
                echo $this->upload_multiple();
            }
        }
    }

    public function upload_multiple() {
        $this->loadModel('SendMemoPicture');
        $data = $this->request->data['SendMemoPictures']['file'];
        $image_results = Array();
        for ($index = 0; $index < count($data); $index++) {
            $file = $this->request->data['SendMemoPictures']['file'][$index];
            $respond = json_decode($this->FileUpload->upload_image($file, 'send_memo_picture'));
            array_push($image_results, array(
                'path' => HTTP . $_SERVER['HTTP_HOST'] . $this->webroot . 'uploads/send_memo_picture/' . $respond->image
            ));
        }
        return json_encode(array(
            'result' => 'success',
            'images' => $image_results
        ));
    }



}
