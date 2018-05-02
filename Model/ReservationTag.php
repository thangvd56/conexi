<?php

App::uses('AppModel', 'Model');

class ReservationTag extends AppModel {

    public $primary_key = 'id';
    public $name = 'ReservationTag';
    public $useTable = 'reservation_tag';
    public $belongsTo = array(
        'Tag' => array(
            'className' => 'Tag',
            'foreignKey' => 'tag_id'
        )
    );

    public function UpdateTag($tags = null, $reservation_id = null) {
        if (isset($tags)) {
            $this->deleteAll(array('ReservationTag.reservation_id' => $reservation_id), false);
            foreach ($tags as $keyTag => $valueTag) {
                if ($valueTag) {
                    $this->create();
                    $fiels = array('ReservationTag' => array('reservation_id' => $reservation_id, 'tag_id' => $valueTag));
                    $this->save($fiels);
                }
            }
        }
    }

}
