<?php
echo $this->Html->css('notify');
echo $this->Html->css('http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
echo $this->Form->create('Settings',
    array(
    'id' => 'function_setting',
    'class' => 'form-horizontal',
    'role' => 'form',
    'type' => 'file'));
?>
<style>
    ul li{
        display: inline;
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
        width: 80px;
        height: 80px;
        border: none;
        background: rgb(233, 233, 233);
    }
    .sel_img:hover{
        cursor: pointer;
    }

    .profile-pic {
        position: relative;
        display: inline-block;
        top:25px;
        margin-left: 4px;
        background-position: center top;
        background-repeat: no-repeat;
        background-size: 80%;
    }

    .profile-pic:hover .arrow_key {
        display: block;
    }
    .arrow_key {
        padding-top: 7px;
        padding-right: 4px;
        position: absolute;
        right: 0;
        top: -7px;
        display: none;
    }
    .arrow_key a {
        color: #000;
    }
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .temp_hide{
        display: none;
    }
    .form-group{
        width:100%
    }
    .image_hover:hover img{
        opacity:0.7;
    }
    .image_hover:hover input {
        display: block;
    }
    .image_hover input {
        position:absolute;
        display:none;
    }
    .image_hover .left {
        top:-10px;
        left:-61px;
    }
    .image_hover .right {
        top: -10px;
        left: 80%;
    }
    .btn-move{
        background: transparent;
        border: none;
        outline: 0;
    }
    .form-group{
        margin-left: 340px;
    }
    .hr{
        clear: both;
        position: relative;
        top: -9px;
        width: 100%;
        border-top: 1px solid #efefef;
    }
</style>
<div>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header" style="border-bottom:5px solid #eee;">
                アプリ作成 ＞ 機能設定
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
                //'empty' => SELECT_SHOP,
                'label' => false,
                'options' => $shops,
                'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                ));
            ?>
        </div>
    </div>
    <br/>
<?php endif; ?>
    <div class="row">
        <div class="col-md-6 change_margin">
            <div style="width:360px">
                <span id="tblphoto" class="collection">
                    <?php foreach ($setting as $key => $value): ?>
                        <div class='select_img show_img ui-state-default profile-pic'
                            style ="background-image: url('<?php echo $this->webroot . 'uploads/function_setting/' . $value['Setting']['function_image']; ?>');">
                            <?php 
                                echo $this->Form->hidden('Settings.function_index.', array(
                                    'id' => 'functions-sort-' . $value['Setting']['id'], 
                                    'class' => 'functions-sort', 
                                    'value' => $value['Setting']['function_index']
                                ));
                                echo $this->Form->hidden('Settings.id.', array(
                                    'id' => 'functions-id-' . $value['Setting']['id'], 
                                    'class' => 'functions-id', 
                                    'value' => $value['Setting']['id']
                                ));
                            ?>
                            
                            <div class="image_hover" id='center_word'>                              
                                <div class="function-name" style="font-size:8pt; position: absolute;font-weight: bold; color: rgb(74, 74, 74);bottom: 0; text-align: center; width: 100%;">
                                    <?php
                                        if ($value['Setting']['function_tag'] == 'my_medical_record') {
                                            echo $this->Form->input('function_name', array(
                                                'options' => unserialize(FUNCTION_NAME),
                                                'class' => 'form-control function-name-dropdown',
                                                'label' => false,
                                                'default' => $value['Setting']['function_name'],
                                                'data-id' => $value['Setting']['id']
                                            ));
                                        } else {
                                            echo $value['Setting']['function_name'];
                                        }
                                    ?>
                                </div>

                                <a href='javascript:void(0);'
                                   data="left"
                                   class='left btn-move arrow_key' title="Left">
                                    <i class="fa fa fa-arrow-left fa-lg"></i>
                                </a>
                                <a href='javascript:void(0);'
                                   data="right"
                                   class='right btn-move arrow_key' title="Right">
                                    <i class="fa fa fa-arrow-right fa-lg"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </span>
            </div>
        </div>
    </div>
    <br/><br/>
    <div class="row">
        <div class="col-md-6 change_margin">
            <hr class="hr"/>
        </div>
    </div>
    <input type="hidden" name="item" id="item">
    <?php foreach ($setting as $key => $value): ?>
        <div class="row">
            <div class="col-md-6 change_margin">
                <div class="col-xs-6 col-md-9"><p class="word"><?php echo $value['Setting']['function_name']; ?></p></div>
                <div class="col-xs-6 col-md-3">
                    <div class="form-group">
                        <input type="checkbox"
                               class="notice setting"
                               target="notice"
                               data-id="<?php echo $value['Setting']['id']; ?>"
                               <?php echo $value['Setting']['active'] ? 'checked': ''; ?>
                               data-title="<?php echo $value['Setting']['function_tag']; ?>"
                               data-toggle="toggle"
                               data-onstyle="warning"
                               data-on="<div class='toggle_on'></div> 公開"
                               data-off="<div class='toggle_off'></div> 非公開">
                    </div>
                </div>
                <hr class="hr"/>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="row">
        <div class="col-md-6 change_margin">
            <button type="button" class="btn btn-block btn_color" data-toggle="modal" data-target="#ModalSaveConfirm">保存</button>
        </div>
    </div> <!-- close row -->
</div>
<br/><br/>
<?php echo $this->Form->end(); ?>
<input type="hidden" name="menu_name" id="menu_name" >
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--        Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">変更内容を保存します。本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design"​  style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var unsaved = false;
        $('body').on('change', '.input', function(e) {
            unsaved = true;
        });

        //Cancel switch on/off function setting
        $('body').on('click', '#btn_cancel', function(e) {
            $('#function_setting_id').val('');
            var status = $('#function_setting_status').val();
            var toggle;
            var menu_name = $('#menu_name').val();
            status === 'true' ? toggle = 'off' : toggle = 'on';
            $('.' + menu_name).bootstrapToggle(toggle);
            $('#ModalFunctionSettingConfirm').modal('hide');
        });

        //Switch on/off function setting
        $('form#function_setting').on('change', '.setting', function (e) {
            var id = $(this).attr('data-id');
            var status = $(this).prop('checked');
            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'Settings', 'action' => 'index')); ?>',
                data: '&action=save&id=' + id + '&status=' + status,
                type: 'get',
                beforeSend: function () {
                    $('.save_loading').removeClass('hide');
                },
                success: function () {
                    $('.loading_save').addClass('hide');
                    $('#ModalFunctionSettingConfirm').modal('hide');
                    unsaved = true;
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('error save_data');
                },
                complete: function () {
                    $('.save_loading').addClass('hide');
                }
            });
        });

        //Save function image order
        $('body').on('click', '#btn_confirm_save', function (e) {
            var data = $('#function_setting').serializeArray();

            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'settings', 'action' => 'reOrderSettings')); ?>',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'JSON',
                beforeSend: function () {
                    $('#save_loading').removeClass('hide');
                },
                success: function (response) {
                    $('#loading_save').addClass('hide');
                    $('#ModalSaveConfirm').modal('hide');
                    unsaved = false;
                    location.reload();
                }
            });
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
            item.css('z-index', 1000).css({
                'position': 'relative',
                'margin-right': '4px'
            }).animate({ left: '-' + left.height() }, 300, function () {
                left.css('z-index', '').css('left', '').css('position', '');
                item.css('z-index', '').css('left', '').css('position', '');
                item.insertBefore(left);
                reOrderFunctions();
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
            item.css('z-index', 1000).css({
                'position': 'relative',
                'margin-left': '8px'
            }).animate({ left: left.height() }, 300, function () {
                left.css('z-index', '').css('left', '').css('position', '');
                item.css('z-index', '').css('left', '').css('position', '');
                item.insertAfter(left);
                reOrderFunctions();
            });
            unsaved = true;            
        }

        //Prevent page leave unsave
        window.onbeforeunload = function () {
            if (unsaved) {
                return 'Your data not yet save, if you leave page your data will lost! ';
            }
        };

        $('form').submit(function () {
            window.onbeforeunload = null;
        });

        $('.function-name').on('change', '.function-name-dropdown', function (e) {
            var id = $(this).attr('data-id');
            var name = $(this).val();
            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'Settings', 'action' => 'changeFunctionName')); ?>',
                data: {'id' : id, 'name' : name},
                type: 'post',
                success: function (response) {
                    console.log(response);
                }
            });
        });

        $('#select_shop').on('change', function (e) {
            window.location.replace(URL + 'function-setting/?shop_id=' + $('#select_shop').val());
        });

        function reOrderFunctions() {
            if ($('.functions-sort').length) {
                $('.collection .functions-sort').each(function(index, value) {
                    $(this).val(index + 1);
                });
            }
        }
        $('.select_img').on("touchstart", function (e) {
            "use strict"; //satisfy the code inspectors
        });
    });
</script>