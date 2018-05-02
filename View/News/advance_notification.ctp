<?php
echo $this->Html->css('notify');
echo $this->Html->css('stamp');
echo $this->Html->css('gips');
echo $this->Form->create('News', array(
    'id' => 'news',
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
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .temp_hide{
        display: none;
    }
</style>

<!-- Page Heading -->

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <h1 class="page-header">通知設定 > 予約事前通知</h1>
    </div>
</div>
<div class="row">
    <?php $role = $this->Session->read('Auth.User.role');
    if ($role == ROLE_HEADQUARTER) : ?>
    <div class="col-xs-12">
        <div class="col-xs-12 col-sm-4">
            <div class="form-group">
                <?php
                    echo $this->Form->input('shop_id', array(
                        'id' => 'shop_id',
                        'name' => 'shop_id',
                        'type' => 'select',
                        'class' => 'shop_name form-control',
                        'label' => false,
                        'options' => $shop,
                        'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                    ));
                ?>
            </div>
        </div>
    </div>
    
    <?php else :
        echo $this->Form->input('shop_id', array(
            'id' => 'shop_id',
            'name' => 'shop_id',
            'type' => 'hidden',
            'label' => false,
            'value' => isset($shop_id) ? $shop_id : ''
        ));
    ?>
    <?php endif; ?>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <ol class="breadcrumb">
            <li class="active">
                予約事前通知<br/><p class="sub_header">こちらの通知は、予約システムで予約を入れているお客様に事前に自動通知が届きます。</p>
            </li>
        </ol>
    </div>
</div>

<br/>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6 change_margin">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><div class="word">当日通知<br/><h5>(2時間前)</h5></div></div>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <?php
                echo $this->Html->image('/uploads/photo_notifications/', array('class' => 'hide temp_img', 'alt' => ''));
                $twohourbefore == '1' ? $is_check_two_hour = 'checked' : $is_check_two_hour = '';
                $onedaybefore == '1' ? $is_check_one_day = 'checked' : $is_check_one_day = '';
                ?>
                <input type="checkbox"
                       class="hour hour<?php echo $id; ?>"
                       data-id="<?php echo $id; ?>"
                       <?php echo $is_check_two_hour; ?>
                       data-title="<?php echo $twohourbefore == '1' ? DISABLE : ENABLE; ?>"
                       data-toggle="toggle"
                       data-onstyle="warning"
                       data-on="<div class='toggle_on'></div> 公開"
                       data-off="<div class='toggle_off'></div> 非公開">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <hr style="width: 100%;" class="hr"/>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6 change_margin">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><div class="word">前日通知<br/><h5>(予約時間)</h5></div></div>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <input type="checkbox"
                       class="day day<?php echo $id; ?>"
                       data-id="<?php echo $id; ?>"
                       <?php echo $is_check_one_day; ?>
                       data-title="<?php echo $onedaybefore == '1' ? DISABLE : ENABLE; ?>"
                       data-toggle="toggle"
                       data-onstyle="warning"
                       data-on="<div class='toggle_on'></div> 公開"
                       data-off="<div class='toggle_off'></div> 非公開">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <hr style="width: 100%;" class="hr"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6 change_margin">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><p class="word">タイトル</p></div>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <?php
                echo $this->Form->input('title', array(
                    'class' => 'form-control',
                    'value' => $title,
                    'name' => 'title',
                    'placeholder' => '２０文字以内で入力してください。',
                    'label' => false,
                    'id' => 'title',
                    'div' => false
                    )
                );
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <hr style="width: 100%;" class="hr"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6 change_margin">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><p class="word">本文</p></div>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                <?php
                echo $this->Form->input('message', array(
                    'class' => 'form-control ',
                    'placeholder' => '※一行につき２０文字程度で「改行」を入れ ると見やすいレイアウトになります。',
                    'value' => $msg,
                    'name' => 'message',
                    'label' => false,
                    'id' => 'message',
                    'type' => 'textarea',
                    'div' => false
                    )
                );
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <hr style="width: 100%;" class="hr"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6 change_margin">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><p class="word">画像</p></div>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="tblphoto">
                <div id="add_media" class="select_img sel_img add_media">
                    <div id="center_word">
                        <h3>+</h3>
                        <p>画像選択</p>
                    </div>
                </div>
                <?php if (!empty($media)): ?>
                    <?php $i = 1; ?>
                    <?php foreach ($media as $key => $value): ?>
                        <div id='<?php echo $value['Media']['id']; ?>' class='select_img show_img ui-state-default profile-pic'>
                            <?php echo "<input type='hidden' name='exist_img[$i]' value='" . $value['Media']['file'] . "'/>"; ?>
                            <div id='center_word'>
                                <?php echo $this->Html->image('/uploads/photo_notifications/' . $value['Media']['file'], array('style' => 'width:75px; height:75px')); ?>
                                <a href='javascript:void(0);'
                                   data-toggle="modal"
                                   data-target="#ModalDeletephoto"
                                   data-name="<?php echo $value['Media']['file']; ?>"
                                   id="<?php echo $value['Media']['id']; ?>"
                                   class='conf item_old edit'>
                                    <i class="fa fa fa-trash-o fa-lg"></i>
                                </a>
                            </div>
                        </div>
                        <?php $i ++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6 change_margin">
        <hr class="hr opacity_hr"/>
    </div>
</div>

<div class="row">
    <input type="hidden" name="item" id="item">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <button type="button" class="btn btn-block btn_color" data-toggle="modal" data-target="#ModalSaveConfirm">保存</button>
    </div>
</div> 
<?php 
    echo $this->Form->end(); 
?>
<!--Modal 2h publish confirm-->
<!--<div id="ModalHourNotificationConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        Modal content
        <div class="modal-content">
            <?php //echo $this->Form->create('News', array('id' => 'hour')); ?>
            <div class="modal-body text-center">
                <input type="hidden" id="hour_notification_id">
                <input type="hidden" id="hour_notification_status">
                <label id="label_notification_hour"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_hour" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="button" id="btn_confirm_hour" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
            <?php //echo $this->Form->end(); ?>
        </div>
    </div>
</div>-->
<!--Modal 1d publish confirm-->
<!--<div id="ModalDayNotificationConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        Modal content
        <div class="modal-content">
            <?php //echo $this->Form->create('News', array('id' => 'day')); ?>
            <div class="modal-body text-center">
                <input type="hidden" id="day_notification_id">
                <input type="hidden" id="day_notification_status">
                <label id="label_notification_day"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php //echo $this->Html->image('loading.gif'); ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_day" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="button" id="btn_confirm_day" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
            <?php //echo $this->Form->end(); ?>
        </div>
    </div>
</div>-->
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        Modal content
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">変更内容を保存します。本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal Delete Photo-->
<div id="ModalDeletephoto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('Media', array('id' => 'delete_photo')); ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
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
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<!--Prevent user leave page-->
<script type="text/javascript" >

    $(function () {
        //Prevent page leave without save
        var unsaved = false;
        $(':input').change(function () {
            unsaved = true;
        });
        //Browse image and preview before upload
        $('body').on('change', '.upload', function () {
            $(this).parent().find('img').remove();
            var image = $(this).parent();
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                if ($(this).val()) {
                    $(this).parents(".ui-state-default").find(".text-center").remove();
                }
                reader.onload = function (e) {
                    image.prepend("<img src='" + e.target.result + "' style='width:75px; height:75px' />");
                }
                $('#file_upload').hide();
                reader.readAsDataURL(this.files[0]);
            }
        });
        //Count image because upload up to 5
        var index = $(".item").length + $(".item_old").length;
        //Remove photo
        $('body').on("click", ".remove", function () {
            index = index - 1;
            var check_type = $(this).parents(".ui-state-default").find(".id").val();
            if (check_type === undefined) {
                $(this).parents(".ui-state-default").remove();
            } else {
                $(this).parents(".ui-state-default").find(".delete").val(1);
                $(this).parents(".ui-state-default").hide();
            }
            $("#add_media").addClass('add_media');
            $("#item").val($("#item").val() - 1);
            unsaved = false;
        });
        $('body').on("click", "#file_upload", function () {
            $("#upload").click();
        });
        // Add new photo
        $('.add_media').on("click", function () {
            if (index < 5) {
                var element = "";
                var last_item = $(".item").length;
                element = '<div id="img_name" class="select_img show_img ui-state-default profile-pic">'
                        + '<div id="center_word">'
                        + '<img src="'+ URL +'img/default.png" style="width:75px; height:75px">'
                        + '<input id="upload" data-id="' + last_item + '" class="upload" required="required" class="form-control col-md-3" accept=".png, .gif, .jpg" name="data[News][file_image]" required="true" type="file" size=1 style="width:75px; margin-top: -48px;position: absolute cursor: pointer; opacity: 0.001;">'
                        + '<a href="javascript:void(0);" data-name="" class="remove item edit"><i class="fa fa fa-trash-o fa-lg"></i></a>'
                        + '</div>'
                        + '<input type="hidden" id="img_hidden_name' + last_item + '" name="image[' + last_item + ']" value="" />'
                        + '</div>';
                index += 1;
                $("#tblphoto").append(element);
                $("#item").val(last_item + 1);
            } else {

                $("#add_media").removeClass('add_media');
            }
            unsaved = true;
        });
        //Click on trash
        $('.conf').click(function () {
            var $image_name = $(this).attr("data-name");
            var $image_id = $(this).attr("id");
            $(".image_name").val($image_name);
            $(".image_id").val($image_id);
        });
        //Delete photo
        $('form#delete_photo').on("submit", function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            var image_id = $(".image_id").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'notification')) ?>",
                data: data + "&action=delete",
                beforeSend: function () {
                    $("#delete_loading").removeClass("hide");
                },
                success: function () {
                    $("#delete_loading").addClass("hide");
                    $("#ModalDeletephoto").modal("hide");
                    $("#" + image_id).remove();
                    index -= 1;
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                    $("#delete_loading").addClass("hide");
                }
            });
            unsaved = true;
        });
        //Switch on/off for 2h before the reservation
//        $('form#news').on("change", ".hour", function () {
//            var status = $(this).prop('checked');
//            var title = $(this).attr('data-title');
//            var id = $(this).attr('data-id');
//            $("#label_notification_hour").html(title + " 本当によろしいですか？");
//            $("#hour_notification_status").val(status);
//            $("#hour_notification_id").val(id);
//            $("#ModalHourNotificationConfirm").modal("show");
//        });
        //Switch on/off 1d before the reservation
//        $('form#new').on("change", ".day", function () {
//            var status = $(this).prop('checked');
//            var title = $(this).attr('data-title');
//            var id = $(this).attr('data-id');
//            $("#label_notification_day").html(title + " 本当によろしいですか？");
//            $("#day_notification_status").val(status);
//            $("#day_notification_id").val(id);
//            $("#ModalDayNotificationConfirm").modal("show");
//        });
        //Save Switch on/off 2h before the reservation
        //$('#btn_confirm_hour').click(function () {
        $('form#news').on("change", ".hour", function () {
            //var notification_status = $("#hour_notification_status").val();
            var notification_status = $(this).prop('checked');
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'advance_notification')) ?>",
                data: "&action=notification_hour&notification_status=" + notification_status+'&shop_id='+$('#shop_id').val(),
                beforeSend: function () {
                    $(".save_loading").removeClass("hide");
                },
                success: function (respond) {
                    console.log(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    //$(".save_loading").addClass("hide");
                    //$("#ModalHourNotificationConfirm").modal("hide");
                }
            });
        });
        //Save Switch on/off 1d before the reservation
        //$('#btn_confirm_day').click(function () {
        $('form#news').on("change", ".day", function () {
            var notification_status = $(this).prop('checked');
            //var notification_status = $("#day_notification_status").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'advance_notification')) ?>",
                data: "&action=notification_day&notification_status=" + notification_status+'&shop_id='+$('#shop_id').val(),
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $(".save_loading").removeClass("hide");
                },
                success: function (respond) {
                    console.log(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    //$(".save_loading").addClass("hide");
                    //$("#ModalDayNotificationConfirm").modal("hide");
                }
            });
        });
        //Cancel 2h before the reservation
//        $('#btn_cancel_hour').click(function () {
//            var id = $("#hour_notification_id").val();
//            var status = $("#hour_notification_status").val();
//            var toggle;
//            status === 'true' ? toggle = 'off' : toggle = 'on';
//            $(".hour" + id).bootstrapToggle(toggle);
//            $("#ModalHourNotificationConfirm").modal("hide");
//        });
        //Cancel 1d before the reservation
//        $('#btn_cancel_day').click(function () {
//            var id = $("#day_notification_id").val();
//            var status = $("#day_notification_status").val();
//            var toggle;
//            status === 'true' ? toggle = 'off' : toggle = 'on';
//            $(".day" + id).bootstrapToggle(toggle);
//            $("#ModalDayNotificationConfirm").modal("hide");
//        });
        //Save information to db
        $('body').on('click', '#btn_confirm_save', function (e) {
            e.preventDefault();
            var data = $("#news").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'advance_notification')); ?>",
                data: data + "&action=save",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $("#save_loading").removeClass("hide");
                },
                success: function () {
                    $("#loading_save").addClass("hide");
                    $("#ModalSaveConfirm").modal("hide");
                    window.location.replace(URL + "users/view/advance_notification");
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("error save_data");
                },
                complete: function () {
                    $("#save_loading").addClass("hide");
                    unsaved = false;
                }
            });
        });
        //On Change image name send to server and store name in hidden
        $('form#news').on("change", ".upload", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            upload_photo(id);
        });
        function upload_photo(id) {
            $(".form-horizontal").ajaxForm({
                dataType: "json",
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
                success: function (respond) {
                    if (respond.result === 'error') {
                    } else {
                        $("#img_hidden_name" + id).val(respond.image);
                    }
                    $(".upload").val("");
                    // prevent_leave_page(true);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading" + id).addClass("hide");
                }
            }).submit();
        }
        //Prevent page leave unsave
        <?php if ($role !== ROLE_HEADQUARTER) : ?>
        window.onbeforeunload = function () {
            if (unsaved) {
                return "Your data not yet save, if you leave page your data will lost! ";
            }
        }
        <?php endif; ?>
        $('form').submit(function () {
            window.onbeforeunload = null;
        });

        $("#shop_id").change(function () {
            window.location.replace(URL + "users/view/advance_notification/?shop_id="+$(this).val());;
        });
    });

</script>