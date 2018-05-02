<?php
    $arr = array(
        'introduction' => '',
        'shop_name' => '',
        'shop_kana' => '',
        'address' => '',
        'hours_start' => '',
        'hours_end' => '',
        'openning_hours' => '',
        'holidays' => '',
        'phone' => '',
        'fax' => '',
        'url' => '',
        'email' => '',
        'facebook' => '',
        'twitter' => '',
        'splash_image' => '',
        'latitute' => '',
        'longtitute' => '',
    );
    $shop = $shop ? $shop['Shop'] : $arr;

    echo $this->Form->create('Shop', array(
        'id' => 'app_info',
        'class' => 'form-horizontal',
        'role' => 'form',
        'type' => 'get'
    ));
?>
<style type="text/css">
    .hr {
        width:100% !important
    }
    .minus-padding-5 {
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-bottom: 5px !important;
    }
    .span-center {
        padding: 10px !important;
        text-align: center;
    }
</style>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <h1 class="page-header">
                アプリ作成 >  基本情報
            </h1>
        </div>
    </div>
    
    <?php
        $role = $this->Session->read('Auth.User.role');
        if ($role == ROLE_HEADQUARTER) : ?>
    <div class="row" style="margin-bottom: 30px;">
        <div class="col-xs-12 col-sm-4">
            <?php
                echo $this->Form->input('shop_id', array(
                    'id'   => 'select_shop',
                    'type' => 'select',
                    'name' => 'shop_id',
                    'class' => 'shop_id form-control',
                    'label' => false,
                    'options' => $shops,
                    'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                ));
            ?>
        </div>
    </div>
    <?php endif; ?>

    <?php
        $value = '';
        if (count($shops) == 1) {
            reset($shop);
            $value = key($shops);
        }
        echo $this->Form->input('shop_id', array(
            'type' => 'hidden',
            'name' => 'shop_id',
            'value' => $value,
        ));
    ?>        
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row" style="margin-bottom: 30px;">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">画像</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div id="add_media" class="select_img sel_img add_media">
                        <div id="center_word">
                            <h3>+</h3>
                            <p>画像を追加</p>
                        </div>
                    </div>
                    <span id="tblphoto" class="collection">
                        <?php if (isset($media)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($media as $key => $value): ?>
                                <div id="<?php echo $value['Media']['id']; ?>" class="select_img show_img ui-state-default profile-pic">
                                    <input type="hidden" class="drag1" name="sort[<?php echo $i; ?>]" value="<?php echo $value['Media']['file']; ?>">
                                    <input type="hidden" class="drag2" name="exist_img[<?php echo $i; ?>]" value="<?php echo $value['Media']['file']; ?>"/>
                                    <div class="image_hover" id="center_word">
                                        <?php
                                            echo $this->Html->image('/uploads/photo_informations/' . $value['Media']['file'], array(
                                                'style' => 'width:75px; height:75px'
                                            ));
                                        ?>
                                        <a href="#"
                                           data-toggle="modal"
                                           data-target="#ModalDeletephoto"
                                           data-name="<?php echo $value['Media']['file']; ?>"
                                           id="<?php echo $value['Media']['id']; ?>"
                                           class="conf item_old edit">
                                            <i class="fa fa fa-trash-o fa-lg"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                           data="left"
                                           class="left btn-move arrow_key">
                                            <i class="fa fa fa-arrow-left fa-lg"></i>
                                        </a>
                                        <a href="javascript:void(0);"
                                           data="right"
                                           class="right btn-move arrow_key">
                                            <i class="fa fa fa-arrow-right fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <input type="hidden" name="item" id="item">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">紹介文</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                        echo $this->Form->input('introduction', array(
                            'class' => 'form-control ',
                            'placeholder' => '紹介文を40文字以内で入力してください。',
                            'value' => $shop['introduction'],
                            'name' => 'introduction',
                            'maxlength'=>'40',
                            'label' => false,
                            'id' => 'introduction',
                            'type' => 'textarea'
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>      
    </div>
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">店舗名</p></div>
            <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                <div class="form-group">
                    <?php
                        echo $this->Form->input('shop_name', array(
                            'class' => 'form-control',
                            'value' => $shop['shop_name'],
                            'name' => 'shop_name',
                            'placeholder' => '店舗名をカタカナで入力してください。',
                            'label' => false,
                            'id' => 'shop_name'
                        ));
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">店舗名(カナ)</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('shop_name_kana', array(
                                'class' => 'form-control',
                                'value' => $shop['shop_kana'],
                                'name' => 'shop_name_kana',
                                'placeholder' => '店舗名を入力してください。',
                                'label' => false,
                                'id' => 'shop_name_kana'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">住所</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('shop_address', array(
                                'class' => 'form-control',
                                'value' => $shop['address'],
                                'name' => 'shop_address',
                                'placeholder' => '住所を入力してください。',
                                'label' => false,
                                'id' => 'shop_address'
                            ));
                        ?>
                    </div>
                </div>
           </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div  class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p id="bh" class="word">営業時間</p></div>
                <div  class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="row">
                        <div class="col-xs-2 col-sm-5 col-md-2 col-lg-2 minus-padding-5">
                            <?php
                                $hours = array(
                                    '00' => '00',
                                    '01' => '01',
                                    '02' => '02',
                                    '03' => '03',
                                    '04' => '04',
                                    '05' => '05',
                                    '06' => '06',
                                    '07' => '07',
                                    '08' => '08',
                                    '09' => '09',
                                    '10' => '10',
                                    '11' => '11',
                                    '12' => '12',
                                    '13' => '13',
                                    '14' => '14',
                                    '15' => '15',
                                    '16' => '16',
                                    '17' => '17',
                                    '18' => '18',
                                    '19' => '19',
                                    '20' => '20',
                                    '21' => '21',
                                    '22' => '22',
                                    '23' => '23'
                                );
                                $min   = array(
                                    '00' => '00',
                                    '05' => '05',
                                    '10' => '10',
                                    '15' => '15',
                                    '20' => '20',
                                    '25' => '25',
                                    '30' => '30',
                                    '35' => '35',
                                    '40' => '40',
                                    '45' => '45',
                                    '50' => '50',
                                    '55' => '55'
                                );
                                echo $this->Form->input('hours_start', array(
                                    'type' => 'select',
                                    'options' => $hours,
                                    'class' => 'form-control time field-date',
                                    'label' => false,
                                    'empty' => array('' => '--'),
                                    'name' => 'hours_start',
                                    'id' => 'hours_start',
                                    'value' => substr($shop['hours_start'], 0,2),
                                    'div' => false
                                ));
                            ?>
                        </div>
                        <div class="col-xs-2 col-sm-1 col-md-1 col-lg-1 minus-padding-5 span-center">
                            <span class="time">:</span>
                        </div>
                        <div class="col-xs-2 col-sm-5 col-md-2 col-lg-2 minus-padding-5">
                            <?php
                                echo $this->Form->input('hours_start_min', array(
                                    'type' => 'select',
                                    'options' => $min,
                                    'empty' => array('' => '--'),
                                    'class' => 'form-control time field-date',
                                    'label' => false,
                                    'name' => 'hours_start_min',
                                    'id' => 'hours_start_min',
                                    'value' => substr($shop['hours_start'], 3,2),
                                    'div' => false
                                ));
                            ?>
                        </div>
                        <div class="col-xs-2 col-sm-1 col-md-1 col-lg-1 minus-padding-5 span-center">
                            <span>~</span>
                        </div>
                        <div class="col-xs-2 col-sm-5 col-md-2 col-lg-2 minus-padding-5">
                            <?php
                                echo $this->Form->input('hours_end', array(
                                    'type' => 'select',
                                    'options' => $hours,
                                    'empty' => array('' => '--'),
                                    'class' => 'form-control time field-date',
                                    'label' => false,
                                    'name' => 'hours_end',
                                    'id' => 'hours_end',
                                    'value' => substr($shop['hours_end'], 0,2),
                                    'div' => false
                                ));
                            ?>
                        </div>
                        <div class="col-xs-2 col-sm-1 col-md-1 col-lg-1 minus-padding-5 span-center">
                            <span>:</span>
                        </div>
                        <div class="col-xs-2 col-sm-5 col-md-2 col-lg-2 minus-padding-5">
                            <?php
                                echo $this->Form->input('hours_end_min', array(
                                    'type' => 'select',
                                    'options' => $min,
                                    'empty' => array('' => '--'),
                                    'class' => 'form-control time field-date',
                                    'label' => false,
                                    'name' => 'hours_end_min',
                                    'id' => 'hours_end_min',
                                    'value' => substr($shop['hours_end'], 3, 2),
                                    'div' => false
                                ));
                            ?>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-xs-2 col-sm-5 col-md-2 col-lg-2 minus-padding-5">
                            <input type="button" id="btnreset" class="btn btn-success time" value="リセット">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div  class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p id="dh" class="word">営業時間直接入力</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('openning_hours', array(
                                'class' => 'form-control ',
                                'value' => $shop['openning_hours'],
                                'name' => 'openning_hours',
                                'placeholder' => '営業時間が複数ある場合に入力してください',
                                'label' => false,
                                'id' => 'openning_hours'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">定休日</p></div>
            <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                <div class="form-group">
                    <?php
                        echo $this->Form->input('holidays', array(
                            'class' => 'form-control change_height_butt',
                            'value' => $shop['holidays'],
                            'name' => 'holidays',
                            'placeholder' => '定休日を入力してください。',
                            'label' => false,
                            'id' => 'holidays'
                        ));
                    ?>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">TEL</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('phone', array(
                                'class' => 'form-control',
                                'value' => $shop['phone'],
                                'name' => 'phone',
                                'placeholder' => '電話番号を入力してください。',
                                'label' => false,
                                'id' => 'phone'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">FAX</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('fax', array(
                                'class' => 'form-control',
                                'value' => $shop['fax'],
                                'name' => 'fax',
                                'placeholder' => 'FAXを入力してください。',
                                'label' => false,
                                'id' => 'fax'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="hp">ホームページ</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('url', array(
                                'class' => 'form-control',
                                'value' => $shop['url'],
                                'name' => 'url',
                                'placeholder' => 'ex: http://tokyo.jp のURLを入力してください。',
                                'label' => false,
                                'id' => 'url',
                                'type' => 'url'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                    <p class="word" id="em">E-mail</p>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('email', array(
                                'class' => 'form-control',
                                'value' => $shop['email'],
                                'name' => 'email',
                                'placeholder' => '問い合わせ用アドレスを入力してください。',
                                'label' => false,
                                'id' => 'email',
                                'type' => 'email'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="fc">Facebook</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('facebook', array(
                                'class' => 'form-control',
                                'value' => $shop['facebook'],
                                'name' => 'facebook',
                                'placeholder' => 'ex: https://facebook.com/tokyo ページURLを入力してください。',
                                'label' => false,
                                'id' => 'facebook',
                                'type' => 'url'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="tw">Twitter</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('twitter', array(
                                'class' => 'form-control',
                                'value' => $shop['twitter'],
                                'name' => 'twitter',
                                'placeholder' => 'ex:https://twitter.com/tokyo ページURLを入力してください。',
                                'label' => false,
                                'id' => 'twitter',
                                'type' => 'url'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="tw">Splash</p></div>
                <div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('splash_image', array(
                                'class' => 'form-control upload_splash custom-file-input',
                                'value' => $shop['splash_image'],
                                'name' => '​splash_image',
                                'id' => 'splash_image',
                                'div' => false,
                                'label' => false,
                                'type' => 'file'
                            ));
                        ?>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="form-group">
                        <?php
                        if ($shop['splash_image']):
                            echo $this->Html->image('/uploads/photo_informations/' . $shop['splash_image'], array(
                                'style' => 'width:75px; height:75px',
                                'class' => 'change_image'
                            ));
                        ?>
                         <a href='#'
                            data-toggle="modal"
                            data-target="#ModalDeleteSplashphoto"
                            data-name="<?php echo $shop['splash_image']; ?>"
                            id="<?php echo $shop['id']; ?>"
                            class='splash'>
                             <i class="fa fa fa-trash-o fa-lg" title="削除"></i>
                         </a>
                        <?php else:
                            echo $this->Html->image('/img/default.png', array(
                               'style' => 'width:75px; height:75px',
                               'class' => 'change_image'
                            ));
                        endif;
                        ?>
                    </div>
                </div>
                <input type="hidden" id="splash_hidden_name" name="splash_hidden_name" value="<?php echo $shop['splash_image'];?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div style="padding: 0" class="col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-3 col-xs-4 col-sm-4 col-md-5 col-lg-5">
                    <div style="font-size:9pt">※スプラッシュ画像とは、アプリ読み込み時に表示される画像です。 PNG形式で300*300サイズですと見やすく表示されます。</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="tw">Map</p></div>
                <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9">
                    <div class="form-group">
                        <div id="map"></div>
                        <?php
                            echo $this->Form->input('latitute', array(
                                'class' => 'form-control',
                                'value' => $shop['latitute'],
                                'name' => 'latitute',
                                'type' => 'hidden',
                                'id' => 'lat'
                            ));
                            echo $this->Form->input('longtitute', array(
                                'class' => 'form-control',
                                'name' => 'longtitute',
                                'type' => 'hidden',
                                'value' => $shop['longtitute'],
                                'id' => 'lng'
                            ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"></div>
                <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('geo_address', array(
                                'class' => 'form-control',
                                'value' => '',
                                'name' => 'geo_address',
                                'id' => 'geo_address',
                                'div' => false,
                                'label' => false
                            ));
                        ?>
                    </div>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
                    <input type="button" id="btnaddress" class="btn btn-success" value="Locate">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <hr class="hr">
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <button type="button" id="save_app_info" class="btn btn-block btn_color get_check" data-toggle="modal" data-target="#ModalSaveConfirm">保存</button>
        </div>
    </div> <!-- close row -->

<?php echo $this->Form->end(); ?>
    <br/><br/>
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                 <label id="label-save">変更内容を保存します。本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div class="message-error hide" style="color:red">Please check red color!</div>
                <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design"​  style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal delete photo-->
<div id="ModalDeletephoto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php echo $this->Form->create('Media', array('id' => 'delete_photo')); ?>
                <label>写真を削除しますか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="hidden" name="image_name" class="image_name"/>
                <input type="hidden" name="image_id" class="image_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<!--Modal delete splash photo-->
<div id="ModalDeleteSplashphoto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php echo $this->Form->create('Media', array('id' => 'delete_splash_photo')); ?>
                <label>写真を削除しますか？</label>
                <div class="clearfix">&nbsp;</div>
                 <input type="hidden" name="splash_image_name" class="splash_image_name"/>
                <input type="hidden" name="shop_splash_id" class="shop_splash_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_splash_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_splash_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<style>
    #map { width:100%; height:200px; }
</style>
<?php
    echo $this->Html->css('notify');
    echo $this->Html->css('input_file');
    echo $this->Html->css('font-awesome.min');
    echo $this->Html->css('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
    echo $this->Html->css('app_info');
    echo $this->Html->script('jquery.geolocation.edit.min');
?>
<script type="text/javascript">
    $(function () {
        //Browse image and preview before upload
        $('body').on('change', '.upload_splash', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.change_image').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        var unsaved = false;
        $('.input').keypress(function() {
            unsaved = true;
        });

        $('body').on('change', '.input', function() {
            unsaved = true;
        });

        $('body').on('change', '#holiday', function() {
            unsaved = true;
        });

        $('body').on('click', '#btnreset', function() {
            $('#hours_start').val('');
            $('#hours_start_min').val('');
            $('#hours_end').val('');
            $('#hours_end_min').val('');
            $('#openning_hours').val('');
        });

        // Drag and Drop
        $('.collection').sortable({
            items: '.select_img',
            opacity: 0.7,
            cursor: 'move',
            stop: function () {
                var i = 1;
                $('.collection .drag1').each(function () {
                    $(this).attr('name', 'sort[' + i + ']');
                    i++;
                });
                unsaved = true;
            }
        });

        //Click To Right or Left
        $('body').on('click', '.btn-move', function (e) {
            e.preventDefault();
            var btn = $(this);
            var val = btn.attr('data');
            if (val == 'left') {
                moveToLeft(btn.parents('.select_img'));
            } else {
                moveToRight(btn.parents('.select_img'));
            }
        });

        //Move to left
        function  moveToLeft(item) {
            var left = item.prev();
            if (left.length == 0)
                return;
            left.css('z-index', 999).css('position', 'relative').animate({
                left: item.height()
            }, 250);
            item.css('z-index', 1000).css('position', 'relative').animate({
                left: '-' + left.height()
            }, 300, function () {
                left.css('z-index', '').css('left', '').css('position', '');
                item.css('z-index', '').css('left', '').css('position', '');
                item.insertBefore(left);
                setOrder();
            });
            unsaved = true;
        }

        //Move to right
        function moveToRight(item) {
            var left = item.next();
            if (left.length == 0)
                return;
            left.css('z-index', 999).css('position', 'relative').animate({
                left: '-' + item.height()
            }, 250);
            item.css('z-index', 1000).css('position', 'relative').animate({
                left: left.height()
            }, 300, function () {
                left.css('z-index', '').css('left', '').css('position', '');
                item.css('z-index', '').css('left', '').css('position', '');
                item.insertAfter(left);
                setOrder();
            });
            unsaved = true;
        }

        //Set Order
        function  setOrder() {
            var i = 1;
            $('.collection .drag1').each(function () {
                $(this).attr('name', 'sort[' + i + ']');
                i++;
            });
        }

        //Browse image and preview before upload
        $('body').on('change', '.upload', function () {
            $(this).parent().find('img').remove();
            var image = $(this).parent();
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                if ($(this).val()) {
                    $(this).parents('.ui-state-default').find(".text-center").remove();
                }
                reader.onload = function (e) {
                    image.prepend("<img src='" + e.target.result + "' style='width:75px; height:75px' />");
                };
                $('#file_upload').hide();
                reader.readAsDataURL(this.files[0]);
            }
        });

        //Count image because upload up to 5
        var index = $('.item').length + $('.item_old').length;
        $('body').on('click', '.remove', function () {
            index = index - 1;
            var check_type = $(this).parents('.ui-state-default').find('.id').val();
            if (check_type === undefined) {
                $(this).parents('.ui-state-default').remove();
                var i = 1;
                var j = 0;
                var k = 0;
                $('.collection .drag1').each(function () {
                    $(this).attr('name', 'sort[' + i + ']');
                    i++;
                });
                $('.collection .drag3').each(function () {
                    $(this).attr('name', 'new_img[' + j + ']');
                    j++;
                });
                $('.collection .drag4').each(function () {
                    $(this).attr('name', 'image[' + k + ']');
                    $(this).attr('id', 'img_hidden_name' + k);
                    k++;
                });
            } else {
                $(this).parents(".ui-state-default").remove();
            }
            $('#add_media').addClass('add_media');
            $('#item').val($('#item').val() - 1);
            unsaved = false;
        });

        // Add new photo
        $('.add_media').on('click', function () {
            if (index < 5) {
                var element = '';
                var last_item = $(".item").length;
                element = '<div id="img_name" class="select_img show_img ui-state-default profile-pic">'
                        + '<input type="hidden" class="drag1" id="sort' + last_item + '" name="sort[' + (index + 1) + ']" value="">'
                        + '<input type="hidden" class="drag3" id="new_img' + last_item + '" name="new_img[' + last_item + ']" value="">'
                        + '<div class="image_hover" id="center_word">'
                        + '<img src="'+ URL +'img/default.png" style="width:75px; height:75px">'
                        + '<input title="Browse" id="upload" data-id="' + last_item + '" class="upload" required="required" class="form-control col-md-3" accept=".png, .gif, .jpg" name="data[App_Info][file_image]" required="true" type="file" size=1 style="width:75px; margin-top: -48px;position: absolute cursor: pointer; opacity: 0.001;">'
                        + '<div class="uploading"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>'
                        + '<a href="#" class="remove item edit"><i class="fa fa fa-trash-o fa-lg"></i></a>'
                        + '<a href="#" data="left" class="left btn-move arrow_key"> <i class="fa fa fa-arrow-left fa-lg"></i></a>'
                        + '<a href="#" data="right" class="right btn-move arrow_key"><i class="fa fa fa-arrow-right fa-lg"></i></a>'
                        + '</div>'
                        + '<input type="hidden" class="drag4" id="img_hidden_name' + last_item + '" name="image[' + last_item + ']" value="" />'
                        + '</div>';
                index += 1;
                $('#tblphoto').append(element);
                $('#item').val(last_item + 1);
            } else {
                $('#add_media').removeClass('add_media');
            }
            unsaved = true;
        });

        //Click on trash
        $('.conf').click(function () {
            var $image_name = $(this).attr('data-name');
            var $image_id = $(this).attr('id');
            $('.image_name').val($image_name);
            $('.image_id').val($image_id);
        });

        //Click on trash splash image
        $('.splash').click(function () {
            var splash_image_name = $(this).attr('data-name');
            var shop_splash_id = $(this).attr('id');
            $('.splash_image_name').val(splash_image_name);
            $('.shop_splash_id').val(shop_splash_id);
        });

        //Delete photo from db
        $('form#delete_photo').on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            var image_id = $('.image_id').val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'app_informations','action' => 'index'))?>",
                data: data + "&action=delete&mode=shop_photo",
                beforeSend: function () {
                    $("#delete_loading").removeClass("hide");
                },
                success: function () {
                    $("#delete_loading").addClass("hide");
                    $("#ModalDeletephoto").modal("hide");
                    $("#" + image_id).remove();
                    var i = 1;
                    var j = 0;
                    $('.collection .drag1').each(function () {
                        $(this).attr('name', 'sort[' + i + ']');
                        $(this).siblings('.drag2').attr('name', 'exist_img[' + i + ']');
                        i++;
                        j++;
                    });
                    index -= 1;
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                    $("#delete_loading").addClass("hide");
                }
            });
            unsaved = true;
        });

        //Function delete splash image
         $('body').on('click','#btn_confirm_splash_delete', function () {
            var shop_splash_id = $('.shop_splash_id').val();
            var splash_image_name = $('.splash_image_name').val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'app_informations','action' => 'index'))?>",
                data:"&action=delete&shop_splash_id=" + shop_splash_id + "&splash_image_name=" + splash_image_name + "&mode=splash",
                beforeSend: function () {
                    $("#delete_splash_loading").removeClass("hide");
                },
                success: function () {
                    $("#delete_splash_loading").addClass("hide");
                    $("#ModalDeleteSplashphoto").modal("hide");
                    unsaved = false;
                    window.location.replace(URL + 'users/view/app-info');
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                    $("#delete_splash_loading").addClass("hide");
                }
            });
            unsaved = true;
        });

        $('.get_check').click(function(){
            var hidden_shop_id = $('body').find('#ShopShopId');
            if ($(hidden_shop_id).val() == '') {
                $(hidden_shop_id).val($('#select_shop').val());
            }
            
            $field_date = [];

            if ($('#hours_start').val() == '' &&
                    $('#hours_start_min').val() == '' &&
                    $('#hours_end').val() == '' &&
                    $('#hours_end_min').val() == ''
            ){
                $("#bh").css({"color": "black"});
                $("#dh").css({"color": "black"});
                $(".message-error").addClass("hide");
                $("#btn_confirm_save").attr("disabled",false);
            } else {
                $all_fields = [];
                $('.field-date').each(function() {
                    if ($(this).val() != '') {
                        $all_fields.push(1);
                    }
                });
                if ($all_fields.length == 4) {
                    $("#bh").css({"color": "black"});
                    $("#dh").css({"color": "black"});
                    $(".message-error").addClass("hide");
                    $("#btn_confirm_save").attr("disabled",false);
                } else {
                    $("#bh").css({"color": "red"});
                    $("#dh").css({"color": "red"});
                    $(".message-error").removeClass('hide');
                    $("#btn_confirm_save").attr("disabled","disabled");
                    alert('営業時間の開始時間と終了時間を全て選択してください。');
                    return false;
                }
            }

             var hp = $("#url").val();
             var fc =$("#facebook").val();
             var tw =$("#twitter").val();
             var em =$("#email").val();
             if(hp !=''){
                //Check Web page
               if (checkUrl(hp)) {
                   $("#hp").css({"color": "black"});
                   //$(".message-error-format").addClass("hide");
                   $("#btn_confirm_save").attr("disabled",false);
               }else{
                   $("#hp").css({"color": "red"});
                   alert("正しいフォーマットではありません。ご確認の上、再度ご入力ください。");
                   //$(".message-error-format").removeClass('hide');
                   $("#btn_confirm_save").attr("disabled","disabled");
                   return false;
               }
            }
            if(em !=''){
                //Check email
            if (checkEmail(em)) {
                $("#em").css({"color": "black"});
                $(".message-error-format").addClass("hide");
                $("#btn_confirm_save").attr("disabled",false);
            }else{
                $("#em").css({"color": "red"});
                 alert("正しいフォーマットではありません。ご確認の上、再度ご入力ください。");
                //$(".message-error-format").removeClass('hide');
                $("#btn_confirm_save").attr("disabled","disabled");
                return false;
            }
            }
            if(fc !=''){
                //Check facebook page
                if (checkUrl(fc)) {
                    $("#fc").css({"color": "black"});
                    $(".message-error-format").addClass("hide");
                    $("#btn_confirm_save").attr("disabled",false);
                }else{
                    $("#fc").css({"color": "red"});
                    alert("正しいフォーマットではありません。ご確認の上、再度ご入力ください。");
                    $("#btn_confirm_save").attr("disabled","disabled");
                     return false;
                }
            }

            if (tw != ''){
                //Check twitter page
                if (checkUrl(tw)) {
                    $('#tw').css({'color': 'black'});
                    $('.message-error-format').addClass('hide');
                    $('#btn_confirm_save').attr('disabled', false);
                } else {
                    $('#tw').css({'color': 'red'});
                    alert('正しいフォーマットではありません。ご確認の上、再度ご入力ください。');
                    $('#btn_confirm_save').attr('disabled', 'disabled');
                     return false;
                }
            }
        });

        //Function check url
        function checkUrl(url){
            //regular expression for URL
            var pattern = /^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/;
            if(pattern.test(url)){
                return true;
            } else {
                return false;
            }
        }

        //Function check email
        function checkEmail(email){
            //regular expression for email
            var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            if(pattern.test(email)){
                return true;
            } else {
                return false;
            }
        }

        //Save information to db
        $('body').on('click', '#btn_confirm_save', function (e) {
            e.preventDefault();
            var data = $('#app_info').serialize();
            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'app_informations','action' => 'index'));?>',
                data: data + "&action=save",
                type: 'get',
                beforeSend: function () {
                    $('.loading-item').addClass('hide');
                    $('#save_loading').removeClass('hide');
                },
                success: function () {
                    $('#loading_save').addClass('hide');
                    $('#ModalSaveConfirm').modal('hide');
                },
                error: function () {
                    console.log('error save_data');
                },
                complete: function () {
                    $('#save_loading').addClass('hide');
                    unsaved = false;
                    var url = 'users/view/app-info';
                    if ('<?php $role == ROLE_HEADQUARTER ?>') {
                        url = 'users/view/app-info?shop_id=' + $('#select_shop').val();
                    }
                    window.location.replace(URL + url);
                }
            });
        });

        //On Change image name send to server and store name in hidden
        $('form#app_info').on('change', '.upload', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            upload_photo(id);
        });

        function upload_photo(id) {
            $('.uploading').hide();
            $('.form-horizontal').ajaxForm({
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.uploading').show();
                },
                success: function (respond) {
                    $('.uploading').hide();
                    if (respond.result === 'error') {
                    } else {
                        $('#img_hidden_name' + id).val(respond.image);
                        $('#sort' + id).val(respond.image);
                        $('#new_img' + id).val(respond.image);
                    }
                    $('.upload').val('');
                },
                error: function (xhr, ajaxOptions, throwError) {
                    $('.uploading').hide();
                    console.log('Error:' + xhr.status);
                },
                complete: function () {
                    $('.uploading').remove();
                    $('#loading' + id).addClass('hide');
                }
            }).submit();
        }

        //Upload splash image
        $('form#app_info').on('change', '.upload_splash', function (e) {
            e.preventDefault();
            upload_splash($(this));
        });

        function upload_splash(obj) {
            var file_data = obj.prop('files')[0];
            var form_data = new FormData();
            form_data.append('splash_image', file_data);
            $.ajax({
                url: URL + 'app_informations/index',
                dataType: 'json',
                data: form_data,
                contentType: false,
                cache : false,
                processData: false,
                type: 'post',
                success: function (response) {
                   $('#splash_hidden_name').val(response.image);
                }
            });
        }
        
        //Prevent page leave unsave
        <?php if ($role !== ROLE_HEADQUARTER) : ?>
        window.onbeforeunload = function () {
            if (unsaved) {
                return 'Your data not yet save, if you leave page your data will lost! ';
            }
        };
        <?php endif; ?>

        $('form').submit(function () {
            window.onbeforeunload = null;
        });

        var lat = 35.83331696739763;
        var lgn = 138.04843204999997;

        if ($('#lat').val() === '' || $('#lng').val() === '') {
            $('#lat').val(lat);
            $('#lng').val(lgn);
        }
        $('#map').geolocate({
            lat: '#lat',
            lng: '#lng'
        });

        $('.change_image').click(function() {
            $('#splash_image').click();
        });

        $('#select_shop').on('change', function () {
            window.location.replace(URL + 'users/view/app-info/?shop_id=' + $('#select_shop').val());
        });

        $('#btnaddress').on('click', function() {
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 8,
              center: {lat: $('#lat').val(), lng: $('#lng').val()}
            });
            var geocoder = new google.maps.Geocoder();

            var address = $('#geo_address').val();
            geocoder.geocode({'address': address}, function(results, status) {
                if (status === 'OK') {
                    $('#lat').val(results[0].geometry.location.lat());
                    $('#lng').val(results[0].geometry.location.lng());
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                      map: map,
                      position: results[0].geometry.location
                    });
                    
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        });
    });
</script>