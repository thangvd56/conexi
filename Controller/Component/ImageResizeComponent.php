<?php

class ImageResizeComponent extends Component
{
    public $new_file_path;
    public $new_width;
    // 生成された画像サイズ
    public $new_height;
    private $img_fname_arr;
    // [.]で分割されたファイル名-配列
    private $img_ext;
    private $temp_image;
    private $create_img_width;
    // 指定した画像サイズ
    private $create_img_height;
    private $temp_image_width;
    // アップされた画像サイズ
    private $temp_image_height;
    private $img_create;
    private $src_file_realpath;
    private $new_file_realpath;
    private $client;
    protected $rootRealpath;
    protected $url;

    /*
     * Uploads an image and its thumbnail into $folderName/big and $folderName/small respectivley.
     * the generated thumnail could either have the same aspect ratio as the uploaded image, or could
     * be a zoomed and cropped version.

     * Directions:
     * In view where you upload the image, make sure your form creation is similar to the following
     * create('FurnitureSet',array('type' => 'file')); ?>
     *
     * In view where you upload the image, make sure that you have a file input similar to the following
     * file('Image/name1'); ?>
     *
     * In the controller, add the component to your components array
     * var $components = array("Image");
     *
     * In your controller action (the parameters are expained below)
     * $image_path = $this->Image->upload_image_and_thumbnail($this->data,"name1",573,80,"sets",true);
     * this returns the file name of the result image. You can store this file name in the database
     *
     * Note that your image will be stored in 2 locations:
     * Image: /webroot/img/$folderName/big/$image_path
     * Thumbnail: /webroot/img/$folderName/small/$image_path
     *
     * Finally in the view where you want to see the images
     * image('sets/big/'.$furnitureSet['FurnitureSet']['image_path']);
     * where "sets" is the folder name we saved our pictures in, and $furnitureSet['FurnitureSet']['image_path'] is the file name we stored in the database

     * Parameters:
     * $data: CakePHP data array from the form
     * $datakey: key in the $data array. If you used file('Image/name1'); ?> in your view, then $datakey = name1
     * $imgscale: the maximum width or height that you want your picture to be resized to
     * $thumbscale: the maximum width or height that you want your thumbnail to be resized to
     * $thumbnailsMedium: the maximum width or height that you want your thumbnail Medium to be resized to
     * $folderName: the name of the parent folder of the images. The images will be stored to /webroot/img/$folderName/big/ and /webroot/img/$folderName/small/
     * $square: a boolean flag indicating whether you want square and zoom cropped thumbnails, or thumbnails with the same aspect ratio of the source image
     */

    public function upload_video($file, $model = null)
    {

        $video_ext = array('mp4', 'avi', 'flv', 'wmv', 'ogv', 'mpg', 'ogg', 'webm');

        if ($model == "items") {
            $tempuploaddir = "uploads/media_items";
        } else if ($model == "venues") {
            $tempuploaddir = "uploads/media_venues";
        } else {
            $tempuploaddir = "uploads/media_plans";
        }
        if (!is_dir($tempuploaddir)) mkdir($tempuploaddir, 0777, true);

        $filetype = strtolower($this->getFileExtension($file['name']));

        // Copy the image into the temporary directory
        $id_unic  = str_replace(".", "", strtotime("now"));
        $filename = $id_unic.$this->generateRandomString(15).md5(microtime().mt_rand());

        settype($filename, "string");
        $filename .= ".";
        $filename .= $filetype;
        if (!in_array($filetype, $video_ext)) {
            return '';
        } else {
            move_uploaded_file($file['tmp_name'], $tempuploaddir.'/'.$filename);
            return $filename;
        }
    }

    public function upload($data, $sizes = null, $type = null)
    { // $create_img_width, $create_img_height
        if (strlen($data['name']) > 4) {
            $tempuploaddir = "uploads";
            if (!is_dir($tempuploaddir)) mkdir($tempuploaddir, 0777, true);

            $filetype = strtolower($this->getFileExtension($data['name']));

            if (($filetype != "jpeg") && ($filetype != "jpg") && ($filetype != "gif")
                && ($filetype != "png")) {

                return;
            } else {
// Get the image size
                $imgsize = $data['tmp_name'];
            }

// Generate a unique name for the image (from the timestamp)
            $id_unic  = str_replace(".", "", strtotime("now"));
            $filename = $id_unic.$this->generateRandomString(15).md5(microtime().mt_rand());

            settype($filename, "string");
            $filename .= ".";
            $filename .= $filetype;
            $tempfile = $tempuploaddir."/$filename";

            if (is_uploaded_file($data['tmp_name'])) {
                // Copy the image into the temporary directory
                if (!copy($data['tmp_name'], "$tempfile")) {
                    print "Error Uploading File!.";
                    exit();
                } else {
                    $temp = $tempuploaddir.'/'.$filename;
                    if ($sizes) {
                        foreach ($sizes as $key => $value) {
                            $type = (isset($value['type']) && in_array($value['type'],
                                    array('fit'))) ? $value['type'] : 'fit';
                            $this->disp_resize_img_path($temp,
                                intval($value['width']),
                                intval($value['height']), $type);
                        }
                    }
                    unlink($tempfile);
                    return str_replace('-', '', $filename);
                }
            }

// Image uploaded, return the file name
            return str_replace('-', '', $filename);
        }
    }
    /*
     * Deletes the image and its associated thumbnail
     * Example in controller action: $this->Image->delete_image("1210632285.jpg","sets");
     *
     * Parameters:
     * $filename: The file name of the image
     * $folderName: the name of the parent folder of the images. The images will be stored to /webroot/img/$folderName/big/ and /webroot/img/$folderName/small/
     */

    function getFileExtension($str)
    {

        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l   = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    // Image resize
    public function disp_resize_img_path($src_img_path, $create_img_width,
                                         $create_img_height, $type = 'fit')
    {

        /* realpath変換 */

        if (substr($src_img_path, 0, 1) == "/") {
            $src_file_realpath = $this->rootRealpath.$src_img_path;
        } else {
            $src_file_realpath = str_replace($this->url, $this->rootRealpath,
                $src_img_path);
        }

        /* ファイルの存在チェック */

        // MODIFIED
        $src_file_realpath = $src_img_path;

        if (!is_file($src_file_realpath)) {
            return 'noimage';
        } else {

            /* 画像情報 */
            $t = explode('/', $src_img_path);
            $ex = '';
            if (is_array($t)) {
                $ex = end($t);
            }

            $img_fname_arr = explode(".", $src_img_path);

            // 拡張子を除いた画像ファイル名（ファイル名に「.」「.jpg」が入っている時にも対応）
            $img_first_name = $img_fname_arr[0];

            for ($i = 1; $i < (count($img_fname_arr) - 1); $i++) {
                $img_first_name .= ".".$img_fname_arr[$i];
            }

            // 画像拡張子（小文字）
            $img_ext = strtolower($img_fname_arr[count($img_fname_arr) - 1]);

            switch ($type) {
                case 'fit' :
                    $create_img_width  = intval($create_img_width);
                    $create_img_height = intval($create_img_height);
                    $img_create        = (is_numeric($create_img_width) && is_numeric($create_img_height))
                            ? 'on' : 'off';
                    break;
                case 'resize' :
                    $create_img_width  = intval($create_img_width);
                    $create_img_height = intval($create_img_height);
                    $img_create        = (is_numeric($create_img_width) && is_numeric($create_img_height))
                            ? 'on' : 'off';
                    break;
                case 'wfit' :
                    $create_img_width  = intval($create_img_width);
                    $create_img_height = 'auto';
                    $img_create        = ( is_numeric($create_img_width)) ? 'on'
                            : 'off';
                    break;
                case 'hfit' :
                    $create_img_width  = 'auto';
                    $create_img_height = intval($create_img_height);
                    $img_create        = ( is_numeric($create_img_height)) ? 'on'
                            : 'off';
                    break;
                case 'resizefit' :
                    $create_img_width  = intval($create_img_width);
                    $create_img_height = intval($create_img_height);
                    $img_create        = (is_numeric($create_img_width) && is_numeric($create_img_height))
                            ? 'on' : 'off';
                    break;
                default :
                    $img_create        = 'off';
            }

            // 生成画像パス
            if ($img_create == 'on') {
                $new_file_path = $img_first_name.'-'.$type.'-'.$create_img_width.'x'.$create_img_height.'.'.$img_ext;
                //作成される画像ファイル名
            } else {
                $new_file_path = $src_img_path;
                //作成されなかった時は元画像
            }

            /* 画像処理 */

            if (is_file($new_file_path)) {
                return $new_file_path;
            } elseif (!is_file($new_file_path) && ($img_ext == 'jpg' || $img_ext
                == 'gif' || $img_ext == 'png' || $img_ext == 'jpeg')) {

                switch (exif_imagetype($src_img_path)) {
                    case 1:
                        $temp_image = ImageCreateFromGIF($src_file_realpath);
                        break;
                    case 2:
                        $temp_image = ImageCreateFromJPEG($src_file_realpath);
                        break;
                    case 3:
                        $temp_image = ImageCreateFromPNG($src_file_realpath);
                        break;
                }

                //拡張子により処理を分岐
//                switch ($img_ext) {
//                    case 'jpg' :
//                        $temp_image = ImageCreateFromJPEG($src_file_realpath);
//                        break;
//                    case 'jpeg' :
//                        $temp_image = ImageCreateFromJPEG($src_file_realpath);
//                        break;
//                    case 'gif' :
//                        $temp_image = ImageCreateFromGIF($src_file_realpath);
//                        break;
//                    case 'png' :
//                        $temp_image = ImageCreateFromPNG($src_file_realpath);
//                        break;
//                }

                // アップされた画像のサイズ
                $temp_image_width  = ImageSX($temp_image);
                //横幅（px）
                $temp_image_height = ImageSY($temp_image);
                //縦幅（px）

                /* - fit - */
                if ($type == 'fit') {

                    // 対象画像-case横長
                    if (($temp_image_width / $temp_image_height) > ($create_img_width
                        / $create_img_height)) {
                        $new_height = $create_img_height;
                        $rate       = $new_height / $temp_image_height;
                        //縦横比
                        $new_width  = $rate * $temp_image_width;
                        $x          = ($create_img_width - $new_width) / 2;
                        $y          = 0;

                        // 対象画像-case縦長
                    } else {
                        $new_width  = $create_img_width;
                        $rate       = $new_width / $temp_image_width;
                        //縦横比
                        $new_height = $rate * $temp_image_height;
                        $x          = 0;
                        $y          = ($create_img_height - $new_height) / 2;
                    }

                    $new_image    = ImageCreateTrueColor($create_img_width,
                        $create_img_height);
                    //空画像
                    //Transparent Background
                    imagealphablending($new_image, false);
                    $transparency = imagecolorallocatealpha($new_image, 0, 0, 0,
                        127);
                    imagefill($new_image, 0, 0, $transparency);
                    imagesavealpha($new_image, true);
//
//                    // Drawing over
//                    $black = imagecolorallocate($new_image, 0, 0, 0);
//                    imagefilledrectangle($new_image, 25, 25, 75, 75, $black);
//                    header('Content-Type: image/png');
//                    imagepng($new_image);

                    /* - resize - */
                } elseif ($type == 'resize') {

                    // 対象画像-サイズが収まる場合
                    if (($temp_image_width < $create_img_width) && ($temp_image_height
                        < $create_img_height)) {
                        $new_width  = $temp_image_width;
                        $new_height = $temp_image_height;
                        $x          = 0;
                        $y          = 0;

                        // 対象画像-case横長
                    } elseif (($temp_image_width / $temp_image_height) > ($create_img_width
                        / $create_img_height)) {
                        $new_width  = $create_img_width;
                        $rate       = $new_width / $temp_image_width;
                        //縦横比
                        $new_height = $rate * $temp_image_height;
                        $x          = 0;
                        $y          = 0;

                        // 対象画像-case縦長
                    } else {
                        $new_height = $create_img_height;
                        $rate       = $new_height / $temp_image_height;
                        //縦横比
                        $new_width  = $rate * $temp_image_width;
                        $x          = 0;
                        $y          = 0;
                    }

                    $new_image = ImageCreateTrueColor($new_width, $new_height);
                    //空画像

                    /* - wfit - */
                } elseif ($type == 'wfit') {

                    // 対象画像 : create_img_widthは数値
                    $new_width  = $create_img_width;
                    $rate       = $new_width / $temp_image_width;
                    //縦横比
                    $new_height = $rate * $temp_image_height;
                    $x          = 0;
                    $y          = 0;

                    $new_image = ImageCreateTrueColor($new_width, $new_height);
                    //空画像

                    /* - hfit - */
                } elseif ($type == 'hfit') {

                    // 対象画像 : create_img_heightは数値
                    $new_height = $create_img_height;
                    $rate       = $new_height / $temp_image_height;
                    //縦横比
                    $new_width  = $rate * $temp_image_width;
                    $x          = 0;
                    $y          = 0;

                    $new_image = ImageCreateTrueColor($new_width, $new_height);
                    //空画像

                    /* - resizefit - */
                } elseif ($type == 'resizefit') {

                    // 対象画像-case横長
                    if (($temp_image_width / $temp_image_height) > ($create_img_width
                        / $create_img_height)) {
                        $new_width  = $create_img_width;
                        $rate       = $new_width / $temp_image_width;
                        //縦横比
                        $new_height = $rate * $temp_image_height;
                        $x          = 0;
                        $y          = 0;

                        // 対象画像-case縦長
                    } else {
                        $new_height = $create_img_height;
                        $rate       = $new_height / $temp_image_height;
                        //縦横比
                        $new_width  = $rate * $temp_image_width;
                        $x          = 0;
                        $y          = 0;
                    }

                    $new_image = ImageCreateTrueColor($new_width, $new_height);
                    //空画像
                }

                $background      = imagecreatetruecolor($new_width, $new_height);
                $whiteBackground = imagecolorallocate($background, 255, 255, 255);
                imagefill($background, 0, 0, $whiteBackground);
                ImageCopyResampled($background, $temp_image, 0, 0, 0, 0,
                    $new_width, $new_height, $temp_image_width,
                    $temp_image_height);

                // realpathで画像生成
                if (substr($new_file_path, 0, 1) == "/") {
                    $new_file_realpath = $this->rootRealpath.$new_file_path;
                } else {
                    $new_file_realpath = str_replace($this->url,
                        $this->rootRealpath, $new_file_path);
                }

                ImageJpeg($background, $new_file_realpath, 100);

                //ImageJPEG($new_image, $new_file_realpath, 100);
                //3rd引数:クオリティー（0-100）

                imagedestroy($temp_image);
                imagedestroy($new_image);

                return $new_file_path;
            } else {
                return $new_file_path;
            }
        }
    }

    function generateRandomString($length = 10)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function rand_char($length)
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(mt_rand(33, 126));
        }
        return $random;
    }
}