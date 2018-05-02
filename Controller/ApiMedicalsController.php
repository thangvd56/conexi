<?php
App::uses('File', 'Utility', 'User');

class ApiMedicalsController extends AppController
{
    public $components = array('RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'latest',
            'reservation_info',
            'history_info',
            'photo_info',
            'set_read'
        ));
        $this->Auth->authorize = 'Controller';
    }

    public function latest()
    {
        $this->loadModel('Reservation');
        $this->loadModel('Shop');
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->request->query('user_id');
        $reservations = $this->Reservation->find('first', array(
            'recursive' => -1,
            'order' => array('created DESC'),
            'conditions' => array(
                'Reservation.shop_id' => $shop_id,
                'Reservation.user_id' => $user_id,
                'Reservation.is_completed' => 1,
                'Reservation.is_deleted' => 0,
                'Reservation.is_checkin' => 0
            )
        ));
        $history = $this->Reservation->find('first', array(
            'recursive' => -1,
            'order' => array('created DESC'),
            'conditions' => array(
                'Reservation.shop_id' => $shop_id,
                'Reservation.user_id' => $user_id,
                'Reservation.is_completed' => 1,
                'Reservation.is_deleted' => 0,
                'Reservation.is_checkin' => 1
            )
        ));

        $medical_badge = $this->Reservation->find('count', array(
            'conditions' => array(
                'AND' => array(
                    'Reservation.is_read' => 0,
                    'Reservation.user_id' => $user_id,
                    'Reservation.shop_id' => $shop_id,
                    'Reservation.is_completed' => 1,
                    'Reservation.is_deleted' => 0,
                    'Reservation.is_checkin' => 1
                )),
            'recursive' => -1
        ));
        $shop_info = $this->Shop->find('first', array(
            'fields' => array(
                'shop_name'
            ),
            'recursive' => -1,
            'conditions' => array(
                'id' => $shop_id,
            )
        ));
        $reservation = array();
        $history_array = array();
        $photo = array();

        if ($shop_info) {
            $shop_name = $shop_info['Shop']['shop_name'];
        }
        if ($reservations) {
            $reservation['shop_name'] = $shop_name;
            $reservation['date'] = $reservations['Reservation']['date'];
            $reservation['time'] = $reservations['Reservation']['start'];
        }
        if ($history) {
            $history_array['shop_name'] = $shop_name;
            $history_array['date'] = $history['Reservation']['date'];
            $history_array['time'] = $history['Reservation']['start'];

            $photo['short_description'] = $history['Reservation']['description'];
            $photo['shop_name'] = $shop_name;
            $photo['date'] = $history['Reservation']['date'];
            $photo['time'] = $history['Reservation']['start'];
        }

        echo json_encode(array(
            'reservations' => $reservation,
            'history' => $history_array,
            'photo' => $photo,
            'badge_medical' => $medical_badge,
            'success' => 1,
            'message' => 'Successful')
        );

        $this->autoRender = false;
    }

    public function history_info()
    {
        $this->loadModel('Reservation');
        $this->loadModel('Shop');
        $this->loadModel('Media');
        $this->loadModel('Staff');
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->request->query('user_id');
        $page_number = $this->request->query('page');
        if ($page_number > 0) {
            $page_number++;
        } else {
            $page_number = 1;
        }

        try {
            $shop_info = $this->Shop->find('first', array(
                'fields' => array(
                    'shop_name'
                ),
                'conditions' => array(
                    'id' => $shop_id,
                ),
                'recursive' => -1
            ));
            $shop_name = '';
            if (!empty($shop_info)) {
                $shop_name = $shop_info['Shop']['shop_name'];
            }
            $this->Reservation->recursive = -1;
            
            $order_check_in_date =
                'CASE
                    WHEN Reservation.checkin_date THEN Reservation.checkin_date
                 END';
           
            $this->Reservation->virtualFields['order_check_in_date'] = $order_check_in_date;

            $reservations = $this->Reservation->find('all', array(
                'order' => array(
                    'Reservation.date' => 'DESC',
                    'Reservation.order_check_in_date' => 'DESC', 
                    'Reservation.start' => 'DESC',
                ),
                'conditions' => array(
                    'AND' => array(
                        'shop_id' => $shop_id,
                        'user_id' => $user_id,
                        'is_deleted' => 0,
                        'is_completed' => 1,
                        'is_checkin' => 1,
                    )
                )
            ));

            if ($reservations) {
                $arr = array();
                foreach ($reservations as $value) {
                    
                    $image = $this->Media->find('all', array(
                        'recursive' => -1,
                        'conditions' => array(
                            'AND' => array(
                                'Media.model' => 'reservations',
                                'Media.external_id' => $value['Reservation']['id'],
                                'Media.is_deleted <>' => 1
                            )),
                    ));

                    $img_array = array();
                    foreach ($image as $k => $v) {
                        $img_eachs = explode(',', $v['Media']['file']);
                        foreach ($img_eachs as $img_each) {
                            $img = Router::url('/', true) . 'uploads/reservation_send_photos/' . $img_each;
                            array_push($img_array, $img);
                        }
                    }
                    $data = $value['Reservation'];
                    $staff = $this->Staff->find('first', array(
                        'conditions' => array(
                            'id' => $data['staff_id']
                        ),
                        'recursive' => -1
                    ));
                    $staff_name = "";
                    if ($staff) {
                        $staff_name = $staff['Staff']['name'];
                    }
                    $arr_value = array(
                        'id' => $data['id'],
                        'date' => $data['date'],
                        'contact_name' => $staff_name,
                        'shop_name' => $shop_name,
                        'person' => $data['adult'] + $data['child'],
                        'child' => $data['child'],
                        'is_read' => $data['is_read'],
                        'start_time' => $data['start'],
                        'end_time' => $data['end'],
                        'treatment_content' => $data['treatment_contents'],
                        'treatment_time' => round((strtotime($data['end']) - strtotime($data['start'])) / 60),
                        'treatment_cost' => $data['treatment_cost'],
                        'status' => $data['status'],
                        'images' => $img_array
                    );
                    array_push($arr, $arr_value);
                }
                echo json_encode(array(
                    'histories' => $arr,
                    'success' => 1,
                    'message' => 'Successful',
                ));
            } else {
                echo json_encode(array(
                    'histories' => array(),
                    'totalpage' => 0,
                    'success' => 1,
                    'message' => 'Successful',
                ));
            }
        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
        $this->autoRender = false;
    }

    public function reservation_info()
    {
        $this->loadModel('Reservation');
        $this->loadModel('Shop');
        $this->loadModel('Staff');
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->request->query('user_id');
        try {
            $shop_info = $this->Shop->find('first', array(
                'fields' => array(
                    'shop_name'
                ),
                'recursive' => -1,
                'conditions' => array(
                    'id' => $shop_id,
                )
            ));
            $shop_name = '';
            if (!empty($shop_info)) {
                $shop_name = $shop_info['Shop']['shop_name'];
            }
            $reservations = $this->Reservation->find('all', array(
                'order' => array('date' => 'DESC', 'start' => ' DESC', 'end' => ' DESC'),
                'conditions' => array(
                    'shop_id' => $shop_id,
                    'user_id' => $user_id,
                    'is_completed' => 1,
                    'is_deleted' => 0,
                    'is_checkin' => 0
                ),
                'recursive' => -1
            ));
            $history_array = array();
            foreach ($reservations as $value) {
                $data = $value['Reservation'];
                $staff = $this->Staff->find('first', array(
                    'conditions' => array(
                        'id' => $data['staff_id']
                    ),
                    'recursive' => -1
                ));
                $staff_name = "";
                if ($staff) {
                    $staff_name = $staff['Staff']['name'];
                }
                $arr_value = array(
                    'id' => $data['id'],
                    'date' => $data['date'],
                    'start_time' => $data['start'],
                    'end_time' => $data['end'],
                    'contact_name' => $staff_name,
                    'treatment_content' => $data['treatment_contents'],
                    'treatment_time' => round((strtotime($data['end']) - strtotime($data['start'])) / 60),
                    'treatment_cost' => $data['treatment_cost'],
                    'shop_name' => $shop_name,
                    'person' => $data['adult'] + $data['child'],
                    'child' => $data['child']
                );
                array_push($history_array, $arr_value);
            }

            echo json_encode(array(
                'reservations' => $history_array,
                'success' => 1,
                'message' => 'Successful',
            ));
        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
        $this->autoRender = false;
    }

    public function photo_info()
    {
        $this->loadModel('Reservation');
        $this->loadModel('Shop');
        $shop_id = $this->request->query('shop_id');
        $user_id = $this->request->query('user_id');
        try {
            $shop_info = $this->Shop->find('first', array(
                'fields' => array(
                    'shop_name'
                ),
                'recursive' => -1,
                'conditions' => array(
                    'id' => $shop_id,
                )
            ));
            $shop_name = $shop_info['Shop']['shop_name'];
            $reservations = $this->Reservation->find('all', array(
                'fields' => array(
                    'id',
                    'date',
                    'start',
                    'contact_name',
                    'treatment_contents',
                    'treatment_time',
                    'treatment_cost'
                ),
                'order' => 'Reservation.id DESC',
                'conditions' => array(
                    'shop_id' => $shop_id,
                    'user_id' => $user_id,
                    'is_completed' => 1,
                    'is_deleted' => 0
                )
            ));
            $arr = array();
            foreach ($reservations as $value) {
                $data = $value['Reservation'];
                $photos = $value['Media'];
                $arr_photo = array();
                foreach ($photos as $photo_value) {
                    $img_eachs = explode(',', $photo_value['file']);
                    foreach ($img_eachs as $img_each) {
                        $img = Router::url('/', true) . 'uploads/reservation_send_photos/' . $img_each;
                        array_push($arr_photo, $img);
                    }
                }
                $arr_value = array(
                    'id' => $data['id'],
                    'date' => $data['date'],
                    'shop_name' => $shop_name,
                    'start' => $data['start'],
                    'contact_name' => $data['contact_name'],
                    'treatment_content' => $data['treatment_contents'],
                    'treatment_time' => $data['treatment_time'],
                    'treatment_cost' => $data['treatment_cost'],
                    'images' => $arr_photo
                );
                array_push($arr, $arr_value);
            }
            echo json_encode(array(
                'photos' => $arr,
                'success' => 1,
                'message' => 'Successful',
            ));
        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage()
            ));
        }
        $this->autoRender = false;
    }

    public function set_read()
    {
        try {
            $this->loadModel('Reservation');
            $user_id = $this->request->query('user_id');
            $shop_id = $this->request->query('shop_id');
            $this->Reservation->updateAll(
                array('Reservation.is_read' => 1),
                array(
                    'Reservation.user_id' => $user_id,
                    'Reservation.shop_id' => $shop_id,
                    'Reservation.is_completed' => 1,
                    'Reservation.is_deleted' => 0,
                    'Reservation.is_checkin' => 1
                )
            );
            echo json_encode(array(
                'success' => 1,
                'message' => 'Successfully Update',
            ));
        } catch (Exception $ex) {
            echo json_encode(array(
                'success' => 0,
                'message' => $ex->getMessage(),
            ));
        }
        $this->autoRender = false;
    }
}