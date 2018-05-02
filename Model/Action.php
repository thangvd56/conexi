<?php

App::uses('AppModel', 'Model');

class Action extends Model
{
    public $name = 'Action';

    public function groupActionByYear($param = null, $shop_id = null)
    {
        $conditions = array();

        $conditions[] = array(
            'and' => array(
                'year(Action.date)' => $param,
            )
        );

        if ($shop_id) {
            $conditions[] = array(
                'and' => array(
                    'Action.shop_id' => $shop_id,
                )
            );
        }
        
        $response = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'Action.created',
                'Action.type',
                'Action.plateform'
            ),
            'recursive' => -1,
        ));

        $data = array();
        $web_reservation = array();
        $phone_reservation = array();
        if ($response) {
            foreach ($response as $key => $value) {
                if ($value['Action']['type'] === 'web_reservation') {
                    $web_reservation[] = $value['Action'];
                } else {
                    $phone_reservation[] = $value['Action'];
                }
            }

            if ($web_reservation || $phone_reservation) {
                foreach ($web_reservation as $key1 => $value1) {
                    $month = (int)date('m', strtotime($value1['created']));
                    $data['web'][$month][] = $value1;
                }
                ksort($data['web']);

                foreach ($phone_reservation as $key2 => $value2) {
                    $month = (int)date('m', strtotime($value2['created']));
                    $data['phone'][$month][] = $value2;
                }
                ksort($data['phone']);
            }
        }

        return $data;
    }
}