<?php
class ApiStampCardController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'stamp_card_info',
            'stamp_spent',
            'stamp_install',
            'stamp_launch',
            'stamp_share',
            'stamp_introduction'
        ));
        $this->Auth->authorize = 'Controller';
        $this->autoRender = false;
    }

    public function stamp_card_info()
    {
        $this->loadModel('Stamp');
        $this->loadModel('StampSetting');
        $user_id = $this->request->query('user_id');
        $shop_id = $this->request->query('shop_id');
        $stamp_setting = $this->StampSetting->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'StampSetting.shop_id' => $shop_id
            ),
            'recursive' => -1
        ));

        if ($user_id && $stamp_setting) {
            $stamps = $this->Stamp->find('all', array(
                'conditions' => array(
                    'Stamp.user_id' => $user_id,
                    'Stamp.count <>' => 0,
                    'Stamp.delete_flag <>' => 1
                ),
                'recursive' => -1
            ));
            $arr_stamps = array();
            foreach ($stamps as $value) {
                $value = $value['Stamp'];
                $arr_stamp = array(
                    'id' => $value['id'],
                    'stamp_id' => $value['stamp_setting_id'],
                    'stamp_type' => $value['stamp_type'],
                    'count' => $value['count']
                );
                array_push($arr_stamps, $arr_stamp);
            }
            $stamp_setting = $stamp_setting['StampSetting'];
            $arr_result = array(
                'stamp_title' => $stamp_setting['stamp_title'],
                'stamp_total' => $stamp_setting['stamp_number'],
                'app_install' => $stamp_setting['app_installation'],
                'app_launch' => $stamp_setting['app_launch'],
                'app_checkin' => $stamp_setting['app_checkin'],
                'benefit_detail' => $stamp_setting['benefit_detail'],
                'benefit_image_sentence' => $stamp_setting['benefit_image_sentence'],
                'expire_day' => $stamp_setting['expire_day'],
                'stamps' => $arr_stamps
            );
            echo json_encode(array(
                'stamp_info' => $arr_result,
                'success' => 1,
                'message' => 'Successful'
            ));
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Error'
            ));
        }
    }

    public function stamp_install()
    {
        $user_id = $this->request->query('user_id');
        $shop_id = $this->request->query('shop_id');
        $this->loadModel('StampSetting');
        $this->loadModel('Stamp');
        $this->loadModel('User');
        $this->loadModel('Quest');
        $this->loadModel('Log');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ),
            'recursive' => -1
        ));
        $setting = $this->StampSetting->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'StampSetting.shop_id' => $shop_id
            ),
            'recursive' => -1
        ));
        $stamp_count = $this->Stamp->find('all', array(
            'conditions' => array(
                'user_id' => $user_id,
                'delete_flag <>' => 1
            ),
            'recusive' => -1,
            'fields' => array('sum(Stamp.count) AS count')
        ));
        $count = 0;
        if ($stamp_count) {
            $count = $stamp_count[0][0]['count'];
        }

        if (!$setting) {
            echo json_encode(array(
                'stamp' => true,
                'success' => 1,
                'message' => 'No Stamp'
            ));
            exit();
        }

        if ($user) {
            $user = $user['User'];
            if ($user['status'] == 1) {
                if ($user['is_install_app'] == true) {
                    echo json_encode(array(
                        'stamp' => false,
                        'success' => 0,
                        'message' => 'user already installed app'
                    ));
                } else {
                    if ($setting['StampSetting']['stamp_number'] > $count) {
                        $stamp_arr = array(
                            'user_id' => $user_id,
                            'stamp_setting_id' => $setting['StampSetting']['id'],
                            'stamp_type' => 'app_installation',
                            'count' => $setting['StampSetting']['app_installation']
                        );
                        $this->Stamp->save($stamp_arr);
                        $this->Log->save(array(
                            'user_id' => $user_id,
                            'type' => 'app_installation',
                            'value' => 'got ' . $setting['StampSetting']['app_installation'] . ' stamps'
                        ));
                        $this->Quest->updateAll(
                            array('quest_app_installation' => true), array('user_id' => $user_id)
                        );
                        $this->User->updateAll(
                            array('is_install_app' => true), array('id' => $user_id)
                        );
                    }
                    echo json_encode(array(
                        'stamp' => true,
                        'success' => 1,
                        'message' => 'Successful'
                    ));
                }
            } else {
                echo json_encode(array(
                    'stamp' => false,
                    'success' => 0,
                    'message' => 'user not active'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Something Went Wrong!'
            ));
        }
    }

    public function stamp_launch()
    {
        $user_id = $this->request->query('user_id');
        $shop_id = $this->request->query('shop_id');
        $this->loadModel('StampSetting', array('recursive' => -1));
        $this->loadModel('Stamp', array('recursive' => -1));
        $this->loadModel('Log');
        $setting = $this->StampSetting->find('first', array(
            'conditions' => array('StampSetting.shop_id' => $shop_id),
            'recursive' => -1
        ));

        if ($setting) {
            $stamp = $this->Stamp->find('first', array(
                'conditions' => array(
                    'Stamp.user_id' => $user_id,
                    'Stamp.stamp_type' => 'app_launching'
                ),
                'order' => array('Stamp.id' => 'DESC'),
                'recursive' => -1
            ));
            if ($stamp) {
                $cDate = strtotime(date('Y-m-d H:i:s'));
                $oldDate = strtotime($stamp['Stamp']['created']);
                $timediff = $cDate - $oldDate;
                $stamp_count = $this->Stamp->find('all', array(
                    'conditions' => array(
                        'user_id' => $user_id,
                        'delete_flag <>' => 1
                    ),
                    'recusive' => -1,
                    'fields' => array('sum(Stamp.count) AS count')
                ));
                $count = 0;
                if ($stamp_count) {
                    $count = $stamp_count[0][0]['count'];
                }
                if ($timediff > 86400) {
                    if ($setting['StampSetting']['stamp_number'] > $count) {
                        $this->Stamp->save(array(
                            'user_id' => $user_id,
                            'stamp_setting_id' => $setting['StampSetting']['id'],
                            'stamp_type' => 'app_launching',
                            'count' => $setting['StampSetting']['app_launch']
                        ));
                        $this->Log->save(array(
                            'user_id' => $user_id,
                            'type' => 'app_launch',
                            'value' => 'got ' . $setting['StampSetting']['app_launch'] . ' stamps'
                        ));
                    }
                    echo json_encode(array(
                        'stamp' => true,
                        'success' => 1,
                        'message' => 'Successful'
                    ));
                } else {
                    echo json_encode(array(
                        'stamp' => false,
                        'success' => 0,
                        'message' => 'less than 24h launching'
                    ));
                }
            } else {
                $this->Stamp->save(array(
                    'user_id' => $user_id,
                    'stamp_setting_id' => $setting['StampSetting']['id'],
                    'stamp_type' => 'app_launching',
                    'count' => $setting['StampSetting']['app_launch']
                ));
                $this->Log->save(array(
                    'user_id' => $user_id,
                    'type' => 'app_launch',
                    'value' => 'got ' . $setting['StampSetting']['app_launch'] . ' stamps'
                ));
                echo json_encode(array(
                    'stamp' => true,
                    'success' => 1,
                    'message' => 'Successful'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Something Went Wrong!'
            ));
        }
    }

    public function stamp_share()
    {
        $user_id = $this->request->query('user_id');
        $shop_id = $this->request->query('shop_id');
        $type = $this->request->query('type');
        $this->loadModel('StampSetting', array('recursive' => -1));
        $this->loadModel('Stamp', array('recursive' => -1));
        $this->loadModel('Quest', array('recursive' => -1));
        $this->loadModel('Log');
        $quest = $this->Quest->find('first', array(
            'conditions' => array(
                'Quest.user_id' => $user_id
            ),
            'recursive' => -1
        ));
        $setting = $this->StampSetting->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'StampSetting.shop_id' => $shop_id
            ),
            'recursive' => -1
        ));
        $stamp_count = $this->Stamp->find('all', array(
            'conditions' => array(
                'user_id' => $user_id,
                'delete_flag <>' => 1
            ),
            'recusive' => -1,
            'fields' => array('sum(Stamp.count) AS count')
        ));
        $count = 0;
        if ($stamp_count) {
            $count = $stamp_count[0][0]['count'];
        }
        if ($quest && $setting) {
            $filename = 'quest_share_facebook';
            if ($type == 'line') {
                $filename = 'quest_share_line';
            } else if ($type == 'twitter') {
                $filename = 'quest_share_twitter';
            }
            if ($quest['Quest'][$filename] == false) {
                if ($setting['StampSetting']['stamp_number'] > $count) {
                    $this->Stamp->save(array(
                        'user_id' => $user_id,
                        'stamp_setting_id' => $setting['StampSetting']['id'],
                        'stamp_type' => 'sns_sharing',
                        'count' => $setting['StampSetting']['sns_sharing']
                    ));
                    $this->Log->save(array(
                        'user_id' => $user_id,
                        'type' => 'sns_sharing',
                        'value' => 'got ' . $setting['StampSetting']['sns_sharing'] . ' stamps'
                    ));
                    $this->Quest->updateAll(
                        array($filename => true), array('user_id' => $user_id)
                    );
                }
                echo json_encode(array(
                    'stamp' => true,
                    'success' => 1,
                    'message' => 'Successful'
                ));
            } else {
                echo json_encode(array(
                    'stamp' => false,
                    'success' => 0,
                    'message' => 'aready shared'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Something Went Wrong!'
            ));
        }
    }

    public function stamp_introduction()
    {
        $user_id = $this->request->query('user_id');
        $shop_id = $this->request->query('shop_id');
        $referer_id = $this->request->query('referer_id');
        $this->loadModel('StampSetting', array('recursive' => -1));
        $this->loadModel('Stamp', array('recursive' => -1));
        $this->loadModel('User', array('recursive' => -1));
        $this->loadModel('Log');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ),
            'recursive' => -1
        ));
        $referer = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $referer_id
            ),
            'recursive' => -1
        ));
        $setting = $this->StampSetting->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'StampSetting.shop_id' => $shop_id
            ),
            'recursive' => -1
        ));
        $stamp_count = $this->Stamp->find('all', array(
            'conditions' => array(
                'user_id' => $user_id,
                'delete_flag <>' => 1
            ),
            'recusive' => -1,
            'fields' => array('sum(Stamp.count) AS count')
        ));
        $count = 0;
        if ($stamp_count) {
            $count = $stamp_count[0][0]['count'];
        }
        if ($user && $setting && $referer) {
            $user = $user['User'];
            if ($user['status'] == true) {
                if ($setting['StampSetting']['stamp_number'] > $count) {
                    $this->Stamp->save(array(
                        'user_id' => $referer_id,
                        'stamp_setting_id' => $setting['StampSetting']['id'],
                        'stamp_type' => 'app_introduction',
                        'count' => $setting['StampSetting']['app_introduction']
                    ));
                    $this->Log->save(array(
                        'user_id' => $user_id,
                        'type' => 'app_introduction',
                        'value' => 'got ' . $setting['StampSetting']['app_introduction'] . ' stamps'
                    ));
                }
                echo json_encode(array(
                    'stamp' => true,
                    'success' => 1,
                    'message' => 'Successful'
                ));
            } else {
                echo json_encode(array(
                    'stamp' => false,
                    'success' => 0,
                    'message' => 'user not register yet'
                ));
            }
        } else {
            echo json_encode(array(
                'success' => 0,
                'message' => 'Something Went Wrong!'
            ));
        }
    }

    public function stamp_spent()
    {
        try {
            $user_id = $this->request->query('user_id');
            $this->loadModel('Stamp');
            $this->Stamp->updateAll(
                array('delete_flag' => 1),
                array('user_id' => $user_id)
            );
            return json_encode(array(
                'success' => 1,
                'message' => 'successful'
            ));
        } catch (Exception $ex) {
            return json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
    }
}