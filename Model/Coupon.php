<?php
App::uses('AppModel', 'Model');

class Coupon extends AppModel
{
    public $image = '';
    public $primary_key = 'id';
    public $validate = array(
        'title' => array(
            'requried' => array(
                'rule' => 'notBlank',
                'message' => 'Title is required.',
                'last' => true
            ),
            'validCharacters' => array(
                'rule' => array('validateTitle', 15),
                'message' => 'Title must be 15 characters or less.',
                'last' => true
            ),
            'duplicateTitle' => array(
                'rule' => array('duplicateTitle'),
                'message' => 'Title for this shop is already exist.'
            )
        ),
        'start_date' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Start date is required.',
                'last' => true
            ),
            'validFormat' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Enter a valid date in YYYY-MM-DD format.'
            )
        ),
        'end_date' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'End date is required.',
                'last' => true
            ),
            'validFormat' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Enter a valid date in YYYY-MM-DD format.',
                'last' => true
            ),
            'validPeriod' => array(
                'rule' => array('validEndDate'),
                'message' => 'End date smaller than start date.'
            ),
            'expired' => array(
                'rule' => array('validateExpire'),
                'message' => 'End date was expired.'
            )
        ),
        'image' => array(
            'upload' => array(
                'rule' => 'uploadError',
                'message' => 'Uploading image error. Please try again.',
                'allowEmpty' => true,
                'required' => false
            ),
            'extension' => array(
                'rule' => array(
                    'extension',
                    array('gif', 'jpeg', 'png', 'jpg')
                ),
                'allowEmpty' => true,
                'message' => 'Invalid image type.'
            ),
            'mimetype' => array(
                'rule' => array('mimeType', array('image/gif', 'image/jpeg', 'image/png', 'image/jpg')),
                'allowEmpty' => true,
                'message' => 'Image mime type not allow.'
            ),
            'size' => array(
                'rule' => array('fileSize', '<=', '2MB'),
                'allowEmpty' => true,
                'message' => 'Image is too large. It should be smaller than 2MB.'
            ),
            'processUpload' => array(
                'rule' => 'process_upload',
                'allowEmpty' => true,
                'message' => 'System error image cannot upload. Please try again.'
            )
        )
    );

    /**
     * Check if current shop coupon title is already exist.
     * @param array $check
     * @return boolean
     */
    public function duplicateTitle($check)
    {
        $conditions = array();
        if (isset($this->data['Coupon']['id']) && !empty($this->data['Coupon']['id'])) {
            array_push($conditions, array('Coupon.id' => $this->data['Coupon']['id']));
        } else {
            array_push($conditions, array('Coupon.shop_id' => $this->data['Coupon']['shop_id']));
        }

        $shopCoupons = $this->find('all', array(
            'fields' => array('Coupon.title', 'Coupon.id'),
            'conditions' => $conditions,
            'recursive' => -1
        ));

        $valid = true;
        if (!empty($shopCoupons)) {
            for ($i = 0; $i < count($shopCoupons); $i++) {
                //when coupon update.
                if (isset($this->data['Coupon']['id']) && !empty($this->data['Coupon']['id'])) {
                    if ($shopCoupons[$i]['Coupon']['id'] == $this->data['Coupon']['id']) {
                        continue;
                    }
                }
                if (strcmp($shopCoupons[$i]['Coupon']['title'], $check['title']) == 0) {
                    $valid = false;
                    break;
                }
            }
            return $valid;
        }
        return $valid;
    }

    /**
     * Check if end date is smaller than start date.
     * @param type $check
     * @return boolean
     */
    public function validEndDate($check)
    {
        $startDate = new DateTime($this->data['Coupon']['start_date']);
        $endDate = new DateTime($check['end_date']);
        if ($endDate < $startDate) {
            return false;
        }
        return true;
    }

    /**
     * Check if end date is smaller than current date.
     * @param type $check
     * @return boolean
     */
    public function validateExpire($check)
    {
        $current_date = strtotime(date('Y-m-d'));
        $end_date = strtotime($check['end_date']);
        if ($end_date < $current_date) {
            return false;
        }
        return true;
    }

    /**
     * check if title is Japanese 15 characters long.
     * @param string $check
     * @param integer $length
     * @return boolean
     */
    public function validateTitle($check, $length)
    {
        if (mb_strlen($check['title'], 'utf8') > $length) {
            return false;
        }
        return true;
    }

    public function process_upload($check = array())
    {
        if (!is_uploaded_file($check['image']['tmp_name'])) {
            return false;
        }
        $ext = substr(strrchr($check['image']['name'], '.'), 1);
        $fname = date('YmdHis') . '.' . $ext;
        if (!move_uploaded_file($check['image']['tmp_name'], WWW_ROOT . 'uploads' . DS . 'coupons' . DS . $fname)) {
            return false;
        }
        $this->image = $fname;
        $this->data[$this->alias]['image'] = $fname;
        return true;
    }
    
    /**
     * Create new coupon
     * @param array $request
     * @return boolean
     */
    public function createCoupon($request = array())
    {
        if (!empty($request['Coupon']['shop_id'])) {
            $data = array(
                'Coupon' => array(
                    'title' => $request['Coupon']['title'],
                    'description' => $request['Coupon']['description'],
                    'start_date' => $request['Coupon']['start_date'],
                    'end_date' => $request['Coupon']['end_date'],
                    'release_date' => $request['Coupon']['start_date'],
                    'remark' => $request['Coupon']['remark'],
                    'status' => 1,
                    'is_birthday' => 0,
                    'shop_id' => $request['Coupon']['shop_id'],
                    'image' => $request['Coupon']['image']
                )
            );

            $this->create();
            return $this->save($data, false);
        }
        return false;
    }

    public function changImageLink($data, $type)
    {
        if ($type === '0') {
            if ($data['Coupon']['image']) {
                $link = Router::url('/', true) . 'uploads/coupons/' . $data['Coupon']['image'];
            } else {
                $link = '';
            }
            $data['Coupon']['image'] = $link;
            $data['Coupon']['is_use'] = '0';
            return $data['Coupon'];
        } else {
            $arr = array();
            foreach ($data as $item) {
                if ($item['Coupon']['image']) {
                    $link = Router::url('/', true) . 'uploads/coupons/' . $item['Coupon']['image'];
                } else {
                    $link = '';
                }
                $item['Coupon']['image'] = $link;
                $item['Coupon']['is_use'] = '1';
                $arr[] = $item;
            }
            return $arr;
        }
    }
}