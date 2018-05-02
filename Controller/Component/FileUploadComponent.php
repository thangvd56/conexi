<?php

class FileUploadComponent extends Component {
    public $components = array('ImageResize');
    public function upload_image($image, $folder,$mode=null) {
        $image_type = array('gif', 'jpeg', 'png', 'jpg');
        $uploadPath = WWW_ROOT . 'uploads/' . $folder;
        $image_ext = explode('.', $image['name']);
        if (!file_exists(WWW_ROOT . 'uploads/' . $folder)) {
            mkdir(WWW_ROOT . 'uploads/' . $folder, 0777, true);
        }
        $image_name = $image['name'];
        if (file_exists($uploadPath . '/' . $image_name)) {
            $image_name = date('His') . $image_name;
        }
        if (!empty($image['name'])) {
            if ((in_array(strtolower($image_ext[1]), $image_type))) {
                if (!(move_uploaded_file($image['tmp_name'], WWW_ROOT . 'uploads/' . $folder . '/' . $image_name))) {
                    return json_encode(array(
                        'result' => 'error',
                        'msg' => 'Could not upload image!'
                    ));
                } else {
                    $THUMBNAIL_SIZE_1 = unserialize(THUMBNAIL_SIZE_1);
                    $this->ImageResize->disp_resize_img_path(WWW_ROOT . 'uploads/' . $folder . '/' . $image_name, $THUMBNAIL_SIZE_1['width'], $THUMBNAIL_SIZE_1['height'], 'fit');
                    return json_encode(array(
                        'result' => 'success',
                        'image' => $image_name,
                        'msg' => 'Image Upload successfully'
                    ));
                }
            } else {
                return json_encode(array(
                    'result' => 'error',
                    'msg' => 'File type not allow!'
                ));
            }
        }
    }

}
