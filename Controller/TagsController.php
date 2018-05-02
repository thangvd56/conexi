<?php
App::uses('AppController', 'Controller');

class TagsController extends AppController
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
        'RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel('User');
        $this->loadModel('Shop');
        $this->Auth->allow('logout');
        $this->Auth->authorize = 'Controller';
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function index()
    {
        $this->loadModel('UserTag');
        $this->loadModel('ReservationTag');
        $user_id_list = array();
        $dataShop = $this->Shop->findByUserId($this->Auth->user('id'));
        $shop_id = $dataShop['Shop']['id'];
        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            $user_id_list = $this->User->getListIdbyParrentId($this->Auth->user('id'));
        } else {
            $user_id_list[] = $this->Auth->user('id');
        }
        $shops = $this->Shop->find('list', array(
            'fields' => array('Shop.id', 'Shop.shop_name'),
            'conditions' => array(
                'is_deleted <>' => 1,
                'user_id' => $user_id_list
            ),
            'recursive' => -1,
        ));

        $options['order'] = [
            'Tag.tag' => 'asc',
            'Tag.created' => 'desc',
        ];
        $options['recursive'] = -1;

        if ($this->Auth->user('role') === ROLE_HEADQUARTER) {
            reset($shops);
            $options['conditions'] = [
                'Tag.shop_id' => $this->request->query('shop_id') ? $this->request->query('shop_id') : key($shops),
            ];
        } else {
            $options['conditions'] = [
                'Tag.shop_id' => $shop_id
            ];
            
        }
        $tags = $this->Tag->getTagList($options);

        if ($tags) {
            if (isset($tags['user_tag'])) {
                foreach ($tags['user_tag'] as $key => $value) {
                    $count = $this->UserTag->find('count', array(
                        'conditions' => array(
                            'UserTag.tag_id' => $value['Tag']['id'],
                        ),
                    ));
                    $tags['user_tag'][$key]['Tag']['count'] = $count;
                }
            }

            if (isset($tags['reservation_tag'])) {
                foreach ($tags['reservation_tag'] as $key => $value) {
                    $count = $this->ReservationTag->find('count', array(
                        'conditions' => array(
                            'ReservationTag.tag_id' => $value['Tag']['id'],
                        ),
                    ));
                    $tags['reservation_tag'][$key]['Tag']['count'] = $count;
                }
            }
        }

        $this->set(compact('tags', 'shops'));
    }

    public function create()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }
        $this->layout = null;
        $this->autoRender = false;
        $response = $this->request->data;
        $dataShop = $this->Shop->findByUserId($this->Auth->user('id'));
        $shop_id = $dataShop['Shop']['id'];
        if (!isset($response['params']['shop_id'])) {
            $response['params']['shop_id'] = $shop_id;
        }
        $results = $this->Tag->savTag($response);

        if ($results) {
            return json_encode(array(
                'result' => 'exist',
                'msg' => 'Tag type and name already exist!'
            ));
        } else {
            $data['is_deleted'] = 0;
            $data['tag'] = $response['params']['tag_name'];
            $data['tag_type'] = $response['params']['tag_type'];
            $data['shop_id'] = $response['params']['shop_id'];
            if ($this->Tag->save($data)) {
                echo json_encode(array(
                    'result' => 'success',
                    'msg' => 'Tag have been save success!',
                ));
            }
        }
    }

    public function edit($id = null)
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }
        $this->layout = null;
        $this->autoRender = false;
        $response = $this->request->data;
        $dataShop = $this->Shop->findByUserId($this->Auth->user('id'));
        $shop_id = $dataShop['Shop']['id'];
        if (!isset($response['params']['shop_id'])) {
            $response['params']['shop_id'] = $shop_id;
        }
        $results = $this->Tag->savTag($response);

        if ($results) {
            return json_encode(array(
                'result' => 'exist',
                'msg' => 'Tag type and name already exist!'
            ));
        } else {
            $data['tag'] = $response['params']['tag_name'];
            $data['id'] = $response['params']['tag_id'];
            if ($this->Tag->save($data)) {
                echo json_encode(array(
                    'result' => 'success',
                    'msg' => 'Tag have been update success!',
                ));
            }
        }
    }

    public function save_tag()
    {
        $this->loadModel('Tag');
        if ($this->request->is('get')) {
            $tag_type = $this->request->query('tag_type');
            $tag_name = $this->request->query('tag_name');
            $tag_id   = $this->request->query('tag_id');
            $tag = $this->Tag->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'Tag.is_deleted <>' => 1,
                        'Tag.is_deleted' => null,
                    )
                ),
                'order' => array(
                    'Tag.created' => 'desc',
                )
            ));
            $i = 0;
            foreach ($tag as $key => $value) {
                if (($tag_type == $value['T']['tag_type']) && ($tag_name == $value['T']['tag'])) {
                    $i = 1;
                }
            }
            $action="save";
            if ($tag_id) {
                $action ="";
                $this->Tag->set('id', $tag_id);
            }
            $data_tag = array('Tag' => array(
                    'tag_type' => $tag_type,
                    'tag' => $tag_name
            ));
            if ($i == 0) {
                if ($this->Tag->save($data_tag)) {
                    return json_encode(array(
                        'result' => 'success',
                        'data' => $this->Tag->findById($this->Tag->id),
                        'action'=> $action,
                        'msg' => 'Tag has been saved success!'
                    ));
                }
            } else {
                return json_encode(array(
                'result' => 'exist',
                'msg' => 'Tag type and name already exist!'
            ));
            }
        }
    }

    public function delete()
    {
        $tag_id = $this->request->query('tag_id');
        $del_physical = $this->request->query('del_physical');
        $this->Tag->id = $tag_id;
        if ($del_physical == 1) {
            //Delete record from table
            $this->Tag->id = $tag_id;
            $this->Tag->delete();
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Tag has been deleted success!'
            ));
        } else {
            //update status is_deleted =1
            $this->Tag->id = $tag_id;
            $this->Tag->saveField('is_deleted', 1);
            return json_encode(array(
                'result' => 'success',
                'msg' => 'Tag has been deleted success!'
            ));
        }
    }
}