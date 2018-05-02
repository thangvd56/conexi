<?php
echo $this->Html->css('font-awesome.min');
echo $this->Html->css('dental');
echo $this->Form->create('News', array(
    'id' => 'new',
    'class' => 'form-horizontal',
    'role' => 'form',
    'type' => 'file',
    'novalidate' => true,
    'inputDefaults' => array(
        'legend' => false,
        'label' => false,
        'div' => false,
)));
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
    .upload:hover{
        cursor: pointer;
    }

</style>
<!-- Page Heading -->
<div class="row">
    <div class="col-md-7">
        <h1 class="page-header">
            通知設定 > 初回通知
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                初回通知 <br/><p class="sub_header">こちらの通知は、アプリをダウンロード時に一度だけ表示される通知
                    です。挨拶や店舗のコンセプトを通知すると効果的です。 </p><br/>
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-7 change_margin">
        <div class="col-xs-6 col-md-3"><p class="word">タイトル</p></div>
        <div class="col-xs-6 col-md-9">
            <div class="form-group">
                <?php
                echo $this->Form->input('title', array(
                    'class' => 'form-control',
                    'value' => $title,
                    'placeholder' => '２０文字以内で入力してください。',
                    'label' => false,
                    'name' => 'title',
                    'id' => 'title'));
                ?>
            </div>
        </div>
        <hr class="hr"/>
    </div>
    <div class="col-md-7 change_margin">
        <div class="col-xs-6 col-md-3"><p class="word">本文</p></div>
        <div class="col-xs-6 col-md-9">
            <div class="form-group">
                <?php
                echo $this->Form->input('message', array(
                    'class' => 'form-control ',
                    'placeholder' => '※一行につき２０文字程度で「改行」を入れ ると見やすいレイアウトになります。',
                    'value' => $msg,
                    'label' => false,
                    'name' => 'message',
                    'id' => 'message',
                    'type' => 'textarea'));
                ?>
            </div>
        </div>
        <hr class="hr"/>
    </div>
    <div class="col-md-10 change_margin">
        <div class="col-md-2"><p class="word">画像</p></div>
        <div class="col-xs-5 col-md-9" id="tblphoto">
            <div id="add_media" class="select_img sel_img add_media">
                <div id="center_word">
                    <h3>+</h3>
                    <p>画像を追加</p>
                </div>
            </div>
            <?php if (isset($media)): ?>
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
    <div class="col-md-7">
        <hr class="hr opacity_hr"/>
    </div>
    <input type="hidden" name="item" id="item">
    <div class="col-md-7 change_margin">
        <button type="button" class="btn btn-block btn_color" data-toggle="modal" data-target="#ModalSaveConfirm">保存</button>
    </div>
</div> <!-- close row -->
<?php echo $this->Form->end(); ?>
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">の変更内容を保存します。<br>本当によろしいですか？</label>
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
<!--Modal Delete Photo confirm-->
<div id="ModalDeletephoto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('Media', array('id' => 'delete_photo')); ?>
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
</div>
<script type="text/javascript" >

    $(function () {
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
        $("body").on("click", ".remove", function () {
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
        });
        //Add new photo
        $(".add_media").on("click", function () {
            if (index < 5) {
                var element = "";
                var last_item = $(".item").length;
                element = '<div id="img_name" class="select_img show_img ui-state-default profile-pic">'
                        + '<div id="center_word">'
                        + '<img src="'+URL+'img/default.png" style="width:75px; height:75px">'
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
        });
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
        });
        //Save information to db
        $("body").on("click", "#btn_confirm_save", function (e) {
            e.preventDefault();
            var data = $("#new").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'notification')); ?>",
                data: data + "&action=save",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $("#save_loading").removeClass("hide");
                },
                success: function () {
                    $("#loading_save").addClass("hide");
                    $("#ModalSaveConfirm").modal("hide");
                    window.location.reload();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("error save_data");
                },
                complete: function () {
                    $("#save_loading").addClass("hide");
                }
            });
        });
        //On Change image name send to server and store name in hidden
        $("form#new").on("change", ".upload", function (e) {
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
                        //Store image name in hidden field
                        $("#img_hidden_name" + id).val(respond.image);
                    }
                    $(".upload").val("");
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading" + id).addClass("hide");
                }
            }).submit();
        }
    });

</script>