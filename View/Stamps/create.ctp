<?php
echo $this->Html->css('stamp');
echo $this->Form->create('StampSetting',
    array('id' => 'form_stamp', 'class' => 'form-horizontal', 'type' => 'file'));
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

    *</style>
    <?php
    $stamp_number = array();

    for ($i = 10; $i <= 50; $i += 5) {
        $stamp_number[$i] = $i.'個';
    }

    $app_installation = array();
    $app_launch       = array();
    //$share_via_sns = array();
    //$app_introduction = array();
    $app_shop_visit   = array();

    for ($j = 0; $j <= 10; $j++) {
        $app_installation[$j] = $j.'個';
        $app_launch[$j]       = $j.'個';
        //$share_via_sns[$j] = $j . '個';
        //$app_introduction[$j] = $j . '個';
        $app_shop_visit[$j]   = $j.'個';
    }
    ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                アプリ作成  >  スタンプ
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    スタンプ設定 <br/><p class="sub_header">スタンプ30個でチケット一枚発行となります</p>
                </li>
            </ol>
        </div>
    </div>
    <!--add table here-->
    <div class="row">
        <div class="col-md-6">
            <table class="table">
                <tbody>
                    <tr>
                        <td class="no_border"><p class="word">スタンプタイトル</p></td>
                        <td class="no_border">
                            <?php
                            echo $this->Form->input('title',
                                array(
                                'class' => 'form-control',
                                'name' => 'title',
                                'placeholder' => '',
                                'value' => $title,
                                'label' => false,
                                'id' => 'title'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><p class="word">スタンプカード枠数</p></td>
                        <td >
                            <?php
                            echo $this->Form->input('stamp_number',
                                array(
                                'type' => 'select',
                                'options' => $stamp_number,
                                'class' => 'form-control icon_select',
                                'value' => $stamp_number_val,
                                'label' => false,
                                'name' => 'stamp_number',
                                'id' => 'stamp_number',
                            ));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><p class="word">アプリインストール時</p></td>
                        <td >
                            <?php
                            echo $this->Form->input('app_installation',
                                array(
                                'type' => 'select',
                                'options' => $app_installation,
                                'class' => 'form-control icon_select',
                                'value' => $app_installation_val,
                                'label' => false,
                                'name' => 'app_installation',
                                'id' => 'app_installation'
                            ));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><p class="word">アプリ起動時</p></td>
                        <td>
                            <?php
                            echo $this->Form->input('app_launch',
                                array(
                                'type' => 'select',
                                'options' => $app_launch,
                                'class' => 'form-control icon_select',
                                'value' => $app_launch_val,
                                'label' => false,
                                'name' => 'app_launch',
                                'id' => 'app_launch',
                            ));
                            ?>
                        </td>
                    </tr>
<!--                    <tr>
                        <td><p class="word">SNSシェア</p></td>
                        <td>
                    <?php
//                            echo $this->Form->input('share_via_sns',
//                                array(
//                                'type' => 'select',
//                                'options' => $share_via_sns,
//                                'class' => 'form-control icon_select',
//                                'label' => false,
//                                'name' => 'share_via_sns',
//                                'id' => 'share_via_sns'
//                            ));
                    ?>
                        </td>
                    </tr>-->
<!--                    <tr>
                        <td><p class="word">アプリ紹介</p></td>
                        <td>
                    <?php
//                            echo $this->Form->input('app_introduction',
//                                array(
//                                'type' => 'select',
//                                'options' => $app_introduction,
//                                'class' => 'form-control icon_select',
//                                'label' => false,
//                                'name' => 'app_introduction',
//                                'id' => 'app_introduction'
//                            ));
                    ?>
                        </td>
                    </tr>-->
                    <tr>
                        <td><p class="word">来店時(ビーコン反応時)</p></td>
                        <td>
                            <?php
                            echo $this->Form->input('app_shop_visit',
                                array(
                                'type' => 'select',
                                'options' => $app_shop_visit,
                                'class' => 'form-control icon_select',
                                'value' => $app_shop_visit_val,
                                'label' => false,
                                'name' => 'app_shop_visit',
                                'id' => 'app_shop_visit'
                            ));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><p class="word">特典詳細</p></td>
                        <td>
                            <?php
                            echo $this->Form->input('benefit_detail',
                                array(
                                'class' => 'form-control ',
                                'placeholder' => '20文字以内で入力してください',
                                'name' => 'benefit_detail',
                                'max' => 20,
                                'value' => $benefit_detail_val,
                                'label' => false,
                                'id' => 'benefit_detail',
                                'type' => 'textarea'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><p class="word">スタンプ画像</p></td>
                        <td>
                            <?php
                            if ($benefit_image) {
                                $hide = "";
                            } else {
                                $hide = "style='display:none'";
                            }
                            ?>
                            <div class="panel-body select_img1" style="cursor:pointer;" id="add_stemp_img">
                                <p id="center"> +画像追加 </p>
                                <div id="img_name" class="select_img show_img ui-state-default profile-pic hide">
                                    <div id='center_word'>
                                        <img name="" style="width:60px;height:60px;" src="" class="myphoto"/>
                                        <a href='javascript:void(0);' class="delete edit"> <i class="fa fa fa-trash-o fa-lg"></i></a>
                                        <input type="hidden" id="img_hidden_name" name="image" value="" />
                                    </div>
                                </div>
                                <div id='<?php echo $id; ?>' <?php echo $hide ?> class="select_img show_img ui-state-default profile-pic">
                                    <?php echo "<input type='hidden' name='exist_img' value='".$benefit_image."'/>"; ?>
                                    <div id='center_word'>
                                        <?php
                                        echo $this->Html->image('/uploads/stamps/'.$benefit_image,
                                            array('style' => 'width:60px; height:60px'));
                                        ?>
                                        <a href='javascript:void(0);'
                                           data-toggle="modal"
                                           data-target="#ModalDeletephoto"
                                           data-name="<?php echo $benefit_image ?>"
                                           id="<?php echo $id; ?>"
                                           class='conf item_old edit'>
                                            <i class="fa fa fa-trash-o fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <input id="name" class="upload" required="required" class="form-control col-md-3" accept=".png, .gif, .jpg"  name="data[Stamp][file_image]" required="true" type="file" size=1 style="width:75px;display: none;">
                        </td>
                    </tr>
                    <tr>
                        <td><p class="word">有効期限</p></td>
                        <td>
                            <div class="panel-body disable_border">
                                <p>発行から
                                    <input class="change_width_input" id="valid_date" type="number" value="<?php echo $valid_date_val ?>" name="valid_date" max="60" min="10" onkeypress="return false">
                                    日間</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="stamp_id" id="stamp_id" value="<?php echo $id ?>">
                            <input type="hidden" id="check_image" name="check_image" value="<?php echo $benefit_image; ?>" />
                            <button type="button" data-toggle="modal" data-target="#ModalSaveConfirm" class="btn btn-block btn_color">保存</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- <input type="hidden" name="expire_day" id="expire_day"/>-->
<?php echo $this->Form->end(); ?>
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">の変更内容を保存します。<br>本当によろしいですか？</label>
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
<!--//Modal delete image confirm-->
<div id="ModalDeletephoto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('stamp', array('id' => 'delete_photo')); ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label><?php echo ARE_YOU_SURE_WANT_TO_DELETE_THIS_PHOTO ?></label>
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

    <script type="text/javascript" >

        $(function () {
            //Prevent page leave without save
            var unsaved = false;
            $(".input").change(function () {
                unsaved = true;
            });
            $(".input").keypress(function () {
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
                var check_image = $("#check_image").val();
                if (check_image == "") {
                    $('.upload').trigger('click');
                }
            });
            //Click on trash
            $(".conf").click(function () {
                var $image_name = $(this).attr("data-name");
                var $image_id = $(this).attr("id");
                $(".image_name").val($image_name);
                $(".image_id").val($image_id);
            });
            //Save information db
            $("body").on("click", "#btn_confirm_save", function (e) {
                e.preventDefault();
                var data = $("#form_stamp").serialize();
                $.ajax({
                    url: "<?php echo Router::url(array('controller' => 'stamps','action' => 'create')); ?>",
                    data: data + "&action=save",
                    type: "get",
                    beforeSend: function () {
                        $(".loading-item").addClass("hide");
                        $("#save_loading").removeClass("hide");
                    },
                    success: function () {
                        $("#loading_save").addClass("hide");
                        $("#ModalSaveConfirm").modal("hide");
                        $('#id').val("");
                    },
                    error: function (xhr, ajaxOptions, throwError) {
                        console.log("error save_data");
                    },
                    complete: function () {
                        $("#save_loading").addClass("hide");
                        unsaved = false;
                        window.location.replace(URL + 'users/view/app-stamp-create');
                    }
                });
            });
            //On Change image name send to server and store name in hidden
            $("form#form_stamp").on("change", ".upload", function (e) {
                e.preventDefault();
                upload_photo();
            });
            function upload_photo() {
                $(".form-horizontal").ajaxForm({
                    dataType: "json",
                    type: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (respond) {
                        if (respond.result === 'error') {
                        } else {
                            $("#img_hidden_name").val(respond.image);
                        }
                        $(".upload").val("");
                    },
                    error: function (xhr, ajaxOptions, throwError) {
                        console.log("Error:" + xhr.status);
                    }
                }).submit();
            }
            //Click on trash
            $(".conf").click(function () {
                var $image_name = $(this).attr("data-name");
                var $image_id = $(this).attr("id");
                $(".image_name").val($image_name);
                $(".image_id").val($image_id);
            });
            //Delete photo from db
            $("form#delete_photo").on("submit", function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var image_id = $(".image_id").val();
                $.ajax({
                    url: "<?php echo Router::url(array('controller' => 'stamps','action' => 'create')) ?>",
                    data: data + "&action=delete",
                    beforeSend: function () {
                        $("#delete_loading").removeClass("hide");
                    },
                    success: function () {
                        $("#delete_loading").addClass("hide");
                        $("#ModalDeletephoto").modal("hide");
                        $("#add_stemp_img").removeClass('hide');
                        $("#" + image_id).remove();
                        $("#check_image").val("");
                    },
                    error: function (xhr, ajaxOptions, throwError) {
                        console.log("Error:" + xhr.status);
                        $("#delete_loading").addClass("hide");
                    }
                });
            });
//            $("#valid_date").on("change", function () {
//                var date = new Date($("#start_date").val()),
//                        days = parseInt($("#valid_date").val(), 10);
//                if (!isNaN(date.getTime())) {
//                    date.setDate(date.getDate() + days);
//                    $("#expire_day").val(date.toInputFormat());
//                } else {
//                    alert("Invalid Date");
//                }
//            });
//            Date.prototype.toInputFormat = function () {
//                var yyyy = this.getFullYear().toString();
//                var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
//                var dd = this.getDate().toString();
//                return yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]); // padding
//            };
            //Prevent page leave unsave
            window.onbeforeunload = function () {
                if (unsaved) {
                    return "Your data not yet save, if you leave page your data will lost! ";
                }
            }
            $('form').submit(function () {
                window.onbeforeunload = null;
            });
        });
    </script>