
<?php
echo $this->Html->css('staff');
echo $this->Form->create('Staff', array(
    'id' => 'edit',
    'class' => 'form-horizontal',
    'type' => 'file'));

if ($this->request->query('shop_id')) {
    echo $this->Form->input('shop_id', array(
        'id'   => 'shop_id',
        'type' => 'hidden',
        'name' => 'shop_id',
        'value' => $this->request->query('shop_id')
    ));
}
?>

<div>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                アプリ作成 ＞ スタッフ紹介
            </h1>
        </div>
    </div>
    <br/>
    <?php
    if (empty($image)) {
        $hide = 'style="display:none"';
        $add = '';
    } else {
        $hide = "";
        $add = 'hide';
    }

    if (!isset($id)) {
        $id =  '';
    }

    if (!isset($name)) {
        $name = '';
    }

    if (!isset($position)) {
        $position = '';
    }

    if (!isset($hobby)) {
        $hobby = '';
    }

    if (!isset($introduction)) {
        $introduction = '';
    }

    if (!isset($image)) {
        $image = '';
    }

    $url = 'create';
    $action = 'save';

    if ($this->request->action != 'create') {
        $url = 'edit';
        $action = 'edit';
    }
    ?>
    <div class="row">
        <div class="col-md-7 change_margin">
            <div class="col-xs-3"><p class="word">画像</p></div>
            <div id="tblphoto">
                <div id="add_media" class="select_img sel_img add_media <?php echo $add ?>">
                    <div id="center_word">
                        <h3>+</h3>
                        <p>画像選択</p>
                    </div>
                </div>
                    <div id='<?php echo $id; ?>' <?php echo $hide ?> class='select_img show_img ui-state-default profile-pic'>
                        <?php echo "<input type='hidden' name='exist_img' value='" . $image . "'/>"; ?>
                        <div id='center_word'>
                            <?php echo $this->Html->image('/uploads/staffs/' . $image, array('style' => 'width:75px; height:75px')); ?>
                            <a href='javascript:void(0);'
                               data-toggle="modal"
                               data-target="#ModalDeletephoto"
                               data-name="<?php echo $image ?>"
                               id="<?php echo $id; ?>"
                               class='conf item_old edit'>
                                <i class="fa fa fa-trash-o fa-lg"></i>
                            </a>
                        </div>
                        <br/>
                    </div>
                    <div id="img_name" class="select_img show_img ui-state-default profile-pic hide">
                        <div id='center_word'>
                            <img name="" style="width:75px;height:75px;" src="" class="myphoto"/>
                            <a href='javascript:void(0);' class="delete edit"> <i class="fa fa fa-trash-o fa-lg"></i></a>
                            <input type="hidden" id="img_hidden_name1" name="image1" value="" />
                        </div>
                    </div>
                    <input id="name" data-id="1" class="upload" required="required" class="form-control col-md-3" accept=".png, .gif, .jpg"  name="data[App_Staff][file_image]" required="true" type="file" size=1 style="width:75px;display: none;">
                    <br/><br/>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <hr class="hr"/>
        </div>
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <div class="row">
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin-top:10px;">
                  名前
                </div>
                <div class="col-xs-10 col-sm-7 col-md-7 col-lg-7">
                    <?php
                    echo $this->Form->input('name', array(
                        'type' => 'text',
                        'label' => false,
                        'maxlength' => 15,
                        'name' => 'name',
                        'value' => $name,
                        'class' => 'form-control',
                        'placeholder' => '１０文字以内で名前を記載。',
                        'id' => 'name'
                    ));
                    ?>
                </div>
           </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <hr class="hr"/>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin-top:10px;">
                  役職
                </div>
                <div class="col-xs-10 col-sm-7 col-md-7 col-lg-7">
                    <?php
                    echo $this->Form->input('position', array(
                        'type' => 'text',
                        'label' => false,
                        'maxlength' => 15,
                        'name' => 'position',
                        'value' => $position,
                        'class' => 'form-control',
                        'placeholder' => '１０文字以内で名前を記載。',
                        'id' => 'position'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <hr class="hr"/>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin-top:10px;">
                  趣味
                </div>
                <div class="col-xs-10 col-sm-7 col-md-7 col-lg-7">
                    <?php
                    echo $this->Form->input('hobby', array(
                        'type' => 'text',
                        'label' => false,
                        'maxlength' => 15,
                        'name' => 'hobby',
                        'value' => $hobby,
                        'class' => 'form-control',
                        'placeholder' => '１０文字以内で名前を記載。',
                        'id' => 'hobby'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <hr class="hr"/>
        </div>
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <div class="row">
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="margin-top:10px;">
                  自己紹介
                </div>
                <div class="col-xs-10 col-sm-7 col-md-7 col-lg-7">
                    <?php
                    echo $this->Form->input('introduction', array(
                        'type' => 'textarea',
                        'label' => false,
                        'maxlength' => 300,
                        'value' => $introduction,
                        'name' => 'introduction',
                        'class' => 'form-control',
                        'placeholder' => '※３００字以内で紹介文を記載。',
                        'id' => 'introduction'
                    ));
                    ?>
                </div>
            </div>
        </div>
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <hr class="hr"/>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
             <div class="col-xs-6 col-md-6"><button type="button" id="btn_back" class="btn btn-block btn_color">戻る</button></div>
             <div class="col-xs-6 col-md-6"> <button id="btn_save" type="button" class="btn btn-block btn_color">保存</button></div>
        </div>
    </div> <!-- close row -->
    <input type="hidden" id="staff_id" name="staff_id" value="<?php echo $id ?>" />
    <?php
    echo $this->Form->end();
    ?>
</div>
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">変更内容を保存します。本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design"​  style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="ModalDeletephoto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('Staff', array('id' => 'delete_photo')); ?>
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
        //Prevent page leave without save
        var unsaved = false;
        $(':input').change(function () {
            unsaved = true;
        });

        $('body').on('change', '.upload', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.myphoto').attr('src', e.target.result);
                    $('#img_name').removeClass('hide');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        $('.delete').click(function () {
            $('#img_name').addClass('hide');
            $('.myphoto').attr('src', '');
        });

        // Add new photo
        $('#add_media').click(function () {
            $('.upload').trigger('click');
        });

        //Click on trash
        $('.conf').click(function () {
            var $image_name = $(this).attr('data-name');
            var $image_id = $(this).attr('id');
            $('.image_name').val($image_name);
            $('.image_id').val($image_id);
        });

        //Delete photo
        $('form#delete_photo').on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            var image_id = $('.image_id').val();
            $.ajax({
                url: URL + 'staffs/edit',
                data: data + '&action=delete',
                beforeSend: function () {
                    $('#delete_loading').removeClass('hide');
                },
                success: function () {
                    $('#delete_loading').addClass('hide');
                    $('#ModalDeletephoto').modal('hide');
                    $('#add_media').removeClass('hide');
                    $('#' + image_id).remove();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                    $('#delete_loading').addClass('hide');
                }
            });
        });

        $('#btn_save').click(function () {
            $('#ModalSaveConfirm').modal('show');
        });

        $('body').on('click', '#btn_confirm_save', function (e) {
            e.preventDefault();
            var data = $('#edit').serialize();
            $.ajax({
                url: URL + 'staffs/<?php echo $url; ?>',
                data: data + "&action=<?php echo $action; ?>",
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
                    unsaved=false;
                    window.location.replace(URL + 'users/view/app-staffs');
                }
            });
        });

        $('form#edit').on('change', '.upload', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            upload_photo(id);
        });

        function upload_photo(id) {
            //var path = $('.sample_img').attr('src');
            $('.form-horizontal').ajaxForm({
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    //$("#loading" + id).removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === 'error') {
                    } else {
                        $('#img_hidden_name' + id).val(respond.image);
                    }
                    $('.upload').val('');
                    //prevent_leave_page(true);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                }

            }).submit();
        }

        //Prevent page leave unsave
         window.onbeforeunload = function () {
            if (unsaved) {
                return 'Your data not yet save, if you leave page your data will lost!';
            }
        }
        $('form').submit(function () {
            window.onbeforeunload = null;
        });

        $('#btn_back').click(function () {
            window.location.replace(URL +'users/view/app-staffs');
        });
    });
</script>