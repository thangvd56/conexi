<?php
    echo $this->Html->css('stamp');
    echo $this->Form->create('StampSetting', array(
        'id' => 'form_stamp',
        'class' => 'form-horizontal',
        'type' => 'file',
    ));
?>

<style type="text/css">
    .select_img1 {
        border-radius: 13px;
        width: 115px;
        height: 45px;
        border: dotted;
        margin-bottom: -2px;
    }
    .select_img {
        margin-bottom: 5px;
        border-radius: 7px;
        width: 107px;
        height: 105px;
        border: dotted;
        display: inline-table;
    }
    #center_word {
        text-align: center;
        margin-top:2px;
    }
    .show_img {
        width: 65px;
        height: 65px;
        border: none;
        background: rgb(233, 233, 233);
    }
    .profile-pic {
        position: relative;
        display: inline-block;
        top: -45px;
        margin-left: 150px;
    }

    .profile-pic:hover .edit {
        display: block;
    }

    .edit {
        padding-top: 7px;
        padding-right: 7px;
        position: absolute;
        right: 0;
        top: 0;
        display: none;
    }

    .edit a {
        color: #000;
    }
</style>

<?php
    $stamp_number = array();

    for ($i = 10; $i <= 50; $i += 5) {
        $stamp_number[$i] = $i.'個';
    }

    $app_installation = array();
    $app_launch       = array();
    $app_shop_visit   = array();

    for ($j = 0; $j <= 10; $j++) {
        $app_installation[$j] = $j.'個';
        $app_launch[$j]       = $j.'個';
        $app_checkin[$j]   = $j.'個';
    }
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <h1 class="page-header">
            アプリ作成  >  スタンプ
        </h1>
    </div>
</div>

<?php
$role = $this->Session->read('Auth.User.role');
if ($role == ROLE_HEADQUARTER) : ?>
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <?php
                echo $this->Form->input('shop_name', array(
                'id'   => 'select_shop',
                'type' => 'select',
                'name' => 'shop_name',
                'class' => 'shop_name form-control',
                'label' => false,
                'options' => $shops,
                'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                ));
            ?>
        </div>
    </div>
    <br/>
<?php endif; ?>
<!--add table here-->
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <table class="table">
            <tbody>
                <?php if (isset($stamp['StampSetting'])) : ?>
                <tr>
                    <td class="clear-left"><p class="word">スタンプカード枠数</p></td>
                    <td class="clear-right">
                        <?php
                        echo $this->Form->input('stamp_number',
                            array(
                            'type' => 'select',
                            'options' => $stamp_number,
                            'class' => 'form-control icon_select',
                            'value' => $stamp['StampSetting']['stamp_number'],
                            'label' => false,
                            'name' => 'stamp_number',
                            'id' => 'stamp_number',
                            )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">アプリインストール時</p></td>
                    <td class="clear-right">
                        <?php
                        echo $this->Form->input('app_installation',
                            array(
                            'type' => 'select',
                            'options' => $app_installation,
                            'class' => 'form-control icon_select',
                            'value' => $stamp['StampSetting']['app_installation'],
                            'label' => false,
                            'name' => 'app_installation',
                            'id' => 'app_installation'
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">アプリ起動時</p></td>
                    <td class="clear-right">
                        <?php
                        echo $this->Form->input('app_launch',
                            array(
                            'type' => 'select',
                            'options' => $app_launch,
                            'class' => 'form-control icon_select',
                            'value' => $stamp['StampSetting']['app_launch'],
                            'label' => false,
                            'name' => 'app_launch',
                            'id' => 'app_launch',
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">来店時（チェックイン時）</p></td>
                    <td class="clear-right">
                        <?php
                        echo $this->Form->input('app_checkin',
                            array(
                            'type' => 'select',
                            'options' => $app_checkin,
                            'class' => 'form-control icon_select',
                            'value' => $stamp['StampSetting']['app_checkin'],
                            'label' => false,
                            'name' => 'app_checkin',
                            'id' => 'app_checkin'
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">特典画像文章</p></td>
                    <td class="clear-right">
                        <?php
                        echo $this->Form->input('sentence',
                            array(
                            'class' => 'form-control',
                            'name' => 'benefit_image_sentence',
                            'placeholder' => '',
                            'value' => $stamp['StampSetting']['benefit_image_sentence'],
                            'label' => false,
                            'id' => 'benefit_image_sentence'));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">特典詳細</p></td>
                    <td class="clear-right">
                        <?php
                        echo $this->Form->input('benefit_detail',
                            array(
                            'class' => 'form-control ',
                            'placeholder' => '20文字以内で入力してください',
                            'name' => 'benefit_detail',
                            'max' => 20,
                            'value' => $stamp['StampSetting']['benefit_detail'],
                            'label' => false,
                            'id' => 'benefit_detail',
                            'type' => 'textarea'));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">有効期限</p></td>
                    <td class="clear-right">
                        <div class="panel-body disable_border">
                            <p>発行から
                                <?php
                                    echo $this->Form->input('valid_date', array(
                                        'class' => 'change_width_input',
                                        'label' => false,
                                        'div' => false,
                                        'type' => 'number',
                                        'value' => $stamp['StampSetting']['valid_date'],
                                        'id' => 'valid_date',
                                        'name' => 'valid_date',
                                        'max' => '60',
                                        'min' => '10',
                                        'onkeypress' => 'return false',
                                    ));
                                ?>
                                日間</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="clear-left clear-right">
                        <?php
                            echo $this->Form->hidden('shop_id', array(
                                'value' => $id,
                                'id' => 'shop_id',
                            ));
                        ?>
                        <?php
                            echo $this->Form->hidden('id', array(
                                'value' => $stamp['StampSetting']['id'],
                                'id' => 'id',
                            ));
                        ?>
                        <button type="button" data-toggle="modal" data-target="#ModalSaveConfirm" class="btn btn-block btn_color">保存</button>
                    </td>
                </tr>
                 <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">変更内容を保存します。本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>

    <script type="text/javascript" >
        $(function () {
            //Prevent page leave without save
            var unsaved = false;
            $('.input').change(function () {
                unsaved = true;
            });
            $('.input').keypress(function () {
                unsaved = true;
            });

            //Browse image and preview before upload
            $('body').on('change', '.upload', function () {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.myphoto').attr('src', e.target.result);
                        $('#img_name').removeClass('hide');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
                unsaved = true;
            });

            $('.delete').click(function () {
                $('#img_name').addClass('hide');
                $('.myphoto').attr('src', '');
            });

            // Add new photo
            $('#add_stemp_img').click(function () {
                var check_image = $('#check_image').val();
                if (check_image == '') {
                    $('.upload').trigger('click');
                }
            });

            //Click on trash
            $('.conf').click(function () {
                var $image_name = $(this).attr('data-name');
                var $image_id = $(this).attr('id');
                $('.image_name').val($image_name);
                $('.image_id').val($image_id);
            });

            //Save information db
            $('body').on('click', '#btn_confirm_save', function (e) {
                e.preventDefault();
                var data = $('#form_stamp').serialize();
                $.ajax({
                    url: '<?php echo $this->Html->url('/stamp_settings/create/'); ?>',
                    data: data + '&action=save',
                    type: 'get',
                    beforeSend: function () {
                        $('.loading-item').addClass('hide');
                        $('#save_loading').removeClass('hide');
                    },
                    success: function () {
                        $('#loading_save').addClass('hide');
                        $('#ModalSaveConfirm').modal('hide');
                        $('#id').val('');
                    },
                    error: function (xhr, ajaxOptions, throwError) {
                        console.log('error save_data');
                    },
                    complete: function () {
                        $('#save_loading').addClass('hide');
                        unsaved = false;
                        window.location.replace(URL + 'users/stamp_settings/create');
                    }
                });
            });

            //On Change image name send to server and store name in hidden
            $('form#form_stamp').on('change', '.upload', function (e) {
                e.preventDefault();
                upload_photo();
            });

            function upload_photo() {
                $('.form-horizontal').ajaxForm({
                    dataType: 'json',
                    type: 'post',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (respond) {
                        if (respond.result === 'error') {
                        } else {
                            $('#img_hidden_name').val(respond.image);
                        }
                        $('.upload').val('');
                    },
                    error: function (xhr, ajaxOptions, throwError) {
                        console.log('Error:' + xhr.status);
                    }
                }).submit();
            }

            //Click on trash
            $('.conf').click(function () {
                var $image_name = $(this).attr('data-name');
                var $image_id = $(this).attr('id');
                $('.image_name').val($image_name);
                $('.image_id').val($image_id);
            });

            //Delete photo from db
            $('form#delete_photo').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var image_id = $('.image_id').val();
                $.ajax({
                    url: '<?php echo Router::url(array('controller' => 'stamps','action' => 'create')) ?>',
                    data: data + '&action=delete',
                    beforeSend: function () {
                        $('#delete_loading').removeClass('hide');
                    },
                    success: function () {
                        $('#delete_loading').addClass('hide');
                        $('#ModalDeletephoto').modal('hide');
                        $('#add_stemp_img').removeClass('hide');
                        $('#' + image_id).remove();
                        $('#check_image').val('');
                    },
                    error: function (xhr, ajaxOptions, throwError) {
                        console.log('Error:' + xhr.status);
                        $('#delete_loading').addClass('hide');
                    }
                });
            });

            //Prevent page leave unsave
            <?php if ($role !== ROLE_HEADQUARTER) : ?>
            window.onbeforeunload = function () {
                if (unsaved) {
                    return 'Your data not yet save, if you leave page your data will lost! ';
                }
            }
            <?php endif; ?>
            $('form').submit(function () {
                window.onbeforeunload = null;
            });
            $('#select_shop').on('change', function () {
                window.location.replace(URL + 'users/stamp_settings/create/?shop_id=' + $('#select_shop').val());
            });
        });
    </script>