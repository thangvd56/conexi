<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Tag extends AppModel {

    public $primary_key = 'id';

    public $validate = array(
        'tag' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Tag is required'
            )
        ),
    );

    public $belongsTo = array(
        'Genre' => array(
            'className' => 'Genre',
            'foreignKey' => 'genre_id'
        )
    );

   public $hasMany = array(
           'ReservationTag' => array(
               'dependent' => true
           ),
       );

    public function getTag($shop_id) {
        $tag = $this->find('all', array(
            'conditions' => array(
                'tag_type' => 'reservation_tag',
                'is_deleted <>' => 1,
                'shop_id' => $shop_id
            )
        ));
        return $tag;
    }

    public function getTagList($data)
    {
        $tags = $this->find('all', $data);
        $response = array();
        foreach ($tags as $tag) {
            if ($tag['Tag']['tag_type'] === 'user_tag') {
                $response['user_tag'][] = $tag;
            } else {
                $response['reservation_tag'][] = $tag;
            }
        }

        return $response;
    }

    public function savTag($data)
    {
        $options['conditions'] = array(
            'and' => array(
                'Tag.tag_type' => $data['params']['tag_type'],
                'Tag.tag' => $data['params']['tag_name'],
                'Tag.shop_id' => $data['params']['shop_id'],
            )
        );
        if (isset($data['params']['tag_id'])) {
            $options['conditions']['Tag.id <>'] = $data['params']['tag_id'];
            $options['recursive'] = -1;
        }
        $results = $this->find('all', $options);
        if ($results) {
           return true;
        } else {
            return false;
        }
    }
}
