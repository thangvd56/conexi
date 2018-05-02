<?php
App::uses('AppController', 'Controller');

class ActionsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'api_create',
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function index()
    {
        $this->loadModel('User');
        $year_list = array();

        for ($i = date('Y') - 27; $i <= date('Y'); $i++) {
            $year_list[$i] = $i . ' 年';
        }
        krsort($year_list);
        $this->set(compact('year_list'));

        $this->loadModel('Shop');
        $this->loadModel('User');
        $user_id_list = array();
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
        $this->set(compact('shops'));
    }

    public function get_data_list()
    {
        if (!$this->request->is('ajax')) {
            throw new NotFoundException();
        }

        $this->loadModel('User');
        $this->loadModel('Shop');
        $this->loadModel('UserShop');
        $this->layout = null;
        $this->autoRender = false;

        $data = array();
        $user_list = array();
        $year = $this->request->query('year') ? $this->request->query('year') : date('Y');
        $shop = $this->request->query('shop_id');
        if ($this->Auth->user('role') == ROLE_SHOP) {
            $shop_id = $this->Shop->find('first', array(
                'fields' => array('Shop.id'),
                'conditions' => array(
                    'is_deleted <>' => 1,
                    'user_id' => $this->Auth->user('id'),
                ),
                'recursive' => -1,
            ));

            if (!empty($shop_id)) {
                $shop = $shop_id['Shop']['id'];
            } else {
                throw new NotFoundException('Shop not found.');
            }
        }

        $actions = $this->Action->groupActionByYear($year, $shop);
        $data['Actions'] = $actions;

        $conditions[] = array(
            'AND' => array(
                'User.status' => 1,
                'User.role' => 'user',
                'year(User.created)' => $year,
                'User.is_install_app' => 1,
            )
        );

        if ($shop) {
            $conditions[] = array(
                'AND' => array('UserShop.shop_id' => $shop)
            );
        }

        $users = $this->User->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'User.platform_type',
                'User.created',
                'count(User.is_install_app) as count_install',
            ),
            'joins' => array(
                array(
                    'table' => 'user_shops',
                    'alias' => 'UserShop',
                    'type' => 'inner',
                    'conditions' => array(
                        'UserShop.user_id = User.id',
                    )
                )
            ),
            'group' => 'month(User.created)',
            'recursive' => -1
        ));
        
        if ($users) {
            foreach ($users as $key => $user) {
                $user_list[] = $user['User'];
                $user_list[$key]['count_install'] = $user[0]['count_install'];
                $user_list[$key]['month'] = (int)date('m', strtotime($user['User']['created']));
            }
            $data['Users'] = $user_list;
        }

        if ($data) {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'retrieve all user',
                'data' => $data
            ));
        } else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'user data not be found!',
            ));
        }
    }

    public function api_create()
    {
        $this->loadModel('User');
        $this->layout = null;
        $this->autoRender = false;
        $user_id = $this->request->query('user_id');
        $shop_id = $this->request->query('shop_id');

        $this->User->recursive = -1;
        $user = $this->User->findById($user_id);
        if ($user) {
            $data = array(
                'user_id' => $user_id,
                'shop_id' => $shop_id,
                'type' => $this->request->query('type'),
                'date' => date('Y-m-d H:i:s'),
                'plateform' => $user['User']['platform_type']
            );
            if ($this->Action->save($data)) {
                echo json_encode(array(
                    'success' => 1,
                    'message' => 'data have been save!',
                ));
            } else {
                echo json_encode(array(
                    'success' => 0,
                    'message' => 'data can not save!',
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'the user_id and shop_id is not match!',
            ));
        }
    }
}