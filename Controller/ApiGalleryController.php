<?php
class ApiGalleryController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'index',
            'gallery_info',
            'sub_gallery_info'
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->autoRender = false;
    }

    public function gallery_info()
    {
        try {
            $potos_arr = array();

            $this->loadModel('Photo');
            $photos = $this->Photo->find('all', array(
                'conditions' => array(
                    'shop_id' => $this->request->query('shop_id'),
                    'published <>' => 0,
                    'is_deleted <>' => 1
                ),
                'order' => array('sort'=>'ASC'),
                'recursive' => -1
            ));

            foreach ($photos as $value) {
                $arr = array(
                    'id' => $value['Photo']['id'],
                    'image' => Router::url('/', true) . 'uploads/photo_gallerise/' . $value['Photo']['image'],
                    'title' => $value['Photo']['title']
                );
                $potos_arr[] = $arr;
            }
            echo json_encode(array(
                'galleries' => $potos_arr,
                'success' => 1,
                'message' => 'Successful'
            ));

        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
        $this->autoRender = false;
    }

    public function sub_gallery_info()
    {
        try {
            $potos_arr = array();

            $this->loadModel('PhotoList');
            $photos = $this->PhotoList->find('all', array(
                'conditions' => array(
                    'PhotoList.photo_id' => $this->request->query('gallery_id'),
                    'PhotoList.published <>' => 0,
                    'PhotoList.is_deleted <>' => 1
                ),
                'order' => array('PhotoList.sort'=>'ASC')
            ));

            foreach ($photos as $value) {
                $arr = array(
                    'id' => $value['PhotoList']['id'],
                    'image' => Router::url('/', true) . 'uploads/photo_gallery_lists/' . $value['PhotoList']['image'],
                    'title' => $value['PhotoList']['title'],
                    'price' => $value['PhotoList']['price'],
                    'content' => $value['PhotoList']['content']
                );
                $potos_arr[] = $arr;
            }
            echo json_encode(array(
                'sub_galleries' => $potos_arr,
                'success' => 1,
                'message' => 'Successful'
            ));

        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
        $this->autoRender = false;
    }
}
