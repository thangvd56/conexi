
<?php
echo $this->Html->css(array(
    'notification-notice',
    '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',
    'notice'
));

echo $this->Form->create('News',
    array(
    'id' => 'edit',
    'class' => 'form-horizontal',
    'role' => 'form',
    'type' => 'file'));

echo $this->Form->input('shop_id', array(
    'id'   => 'shop_id',
    'type' => 'hidden',
    'name' => 'shop_id',
    'label' => false,
    'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
));
?>
<style>
    .img-box {
        margin: 5px;
    }
</style>
<!-- Page Heading -->
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <h1 class="page-header">
            通知設定 > お知らせ通知
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                お知らせ通知 <br/><p class="sub_header">こちらの通知は、全体へのお知らせや絞り込みの通知に使えます。</p>
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">配信先対象</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                $destination = unserialize(NEWS_FILTER);
                echo $this->Form->input('destination_target',
                    array(
                    'type' => 'select',
                    'options' => $destination,
                    'class' => 'form-control icon_select select_val',
                    'label' => false,
                    'name' => 'destination_target',
                    'id' => 'destination_target',
                    'div' => false,
                    'value' => isset($destination_target) ? $destination_target : '',
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$display = 'style="display: none;"';
if (isset($destination_target) && $destination_target === 'filter') {
    $display = 'style="display: block;"';
} else {
    $display = '';
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 display" <?php echo $display; ?>>
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target display" <?php echo $display; ?>>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">性別</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                $genderAll = array('全て対象' => '全て対象');
                $genders = array_merge(unserialize(GENDER), $genderAll);
                echo $this->Form->input('gender',
                    array(
                    'type' => 'select',
                    'options' => $genders,
                    'class' => 'form-control icon_select',
                    'label' => false,
                    'name' => 'gender',
                    'id' => 'gender',
                    'value' => isset($gender) ? $gender : '',
                    'div' => false
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 display" <?php echo $display; ?>>
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target display" <?php echo $display; ?>>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">通知対象</p></div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-6">
                <div id="radio">
                <?php
                $loop = 1;
                foreach (unserialize(NEWS_TARGET_AGE) as $key2 => $value2) : ?>
                    <label for="checkbox<?php echo $loop; ?>">
                        <input type="checkbox" class="mytarget" value="<?php echo $key2; ?>" name="target" id="checkbox<?php echo $loop; ?>"> <span><?php echo $value2; ?></span>
                    </label>
                <?php $loop++; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 display" <?php echo $display; ?>>
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target display" <?php echo $display; ?>>
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">地域 </p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                $a_id = isset($area_id) && !empty($area_id) ? $area_id : '';
                $a = 1;
                if (isset($show_area) && !empty($show_area)) {
                    foreach ($show_area as $key => $val) :
                        ?>
                        <?php                        
                        echo $this->Form->input('area',
                            array(
                            'type' => 'select',
                            'options' => $areas,
                            'class' => 'form-control icon_select area',
                            'label' => false,
                            'default' => $a_id,
                            'name' => 'area_id',
                            'id' => 'area['.$a.']',
                            'value' => $val['A']['area_id'],
                            'div' => false,
                            'empty' => array('' => '全ての地域')
                        ));
                        ?>
                        <?php
                        if ($a >= 2):
                            ?>
                            <div id="<?php echo $a; ?>" class="rmv">&nbsp;X</div>
                            <?php
                        endif;
                        ?>
                        <?php
                        $a++;
                    endforeach;
                } else {
                    echo $this->Form->input('area', array(
                        'type' => 'select',
                        'options' => $areas,
                        'class' => 'form-control icon_select area',
                        'label' => false,
                        'default' => $a_id,
                        'name' => 'area_id',
                        'div' => false,
                        'id' => 'area[1]',
                        'empty' => array('' => '全ての地域')
                    ));
                }
                ?>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <input type="button" id="btnarea" class="btn success" value="地域追加">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7" <?php echo $display; ?>>
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <input type="hidden" name="hdf_area" id="hdf_area" />
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">通知日</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                if (isset($date)) {
                    if ($date == '0000-00-00') {
                        $date = '';
                    }
                } else {
                    $date = '';
                }
                echo $this->Form->input('date_picker',
                    array(
                        'class' => 'form-control common-single-datepicker',
                        'value' => $date,
                        'label' => false,
                        'name' => 'date_picker',
                        'div' => false,
                        'id' => 'date_picker',
                        'div' => false
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="ms_time">時間</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                $hours = unserialize(TIME_NOTICES);
                if (!isset($time)) {
                    $time = '';
                }
                echo $this->Form->input('time', array(
                    'type' => 'select',
                    'options' => $hours,
                    'class' => 'form-control icon_select',
                    'label' => false,
                    'name' => 'time',
                    'id' => 'time',
                    'div' => false,
                    'value' => $time,
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="ms_title">タイトル</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                if (!isset($title)) {
                    $title = '';
                }
                echo $this->Form->input('title', array(
                    'class' => 'form-control',
                    'value' => $title,
                    'placeholder' => '２０文字以内で入力してください。',
                    'label' => false,
                    'name' => 'title',
                    'div' => false,
                    'id' => 'title'));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="ms_text">本文</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                if (!isset($message)) {
                    $message = '';
                }
                echo $this->Form->input('message', array(
                    'class' => 'form-control ',
                    'placeholder' => '※一行につき２０文字程度で「改行」を入れ ると見やすいレイアウトになります。',
                    'value' => $message,
                    'label' => false,
                    'name' => 'message',
                    'id' => 'message',
                    'div' => false,
                    'type' => 'textarea'));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;"/>
    </div>
</div>
<div class="row">
    <input type="hidden" name="item" id="item">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">画像</p></div>
            <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9" style="margin-bottom: 25px;" id="tblphoto">
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
                            <?php echo "<input type='hidden' name='exist_img[$i]' value='".$value['Media']['file']."'/>"; ?>
                            <div id='center_word'>
                                <?php
                                echo $this->Html->image('/uploads/photo_notices/'.$value['Media']['file'],
                                    array('style' => 'width:75px; height:75px'));
                                ?>
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
    <input type ="hidden" class="target" id ="target" name="target_hdf" value ="" />

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 top">
        <div class="row">
            <div class="col-xs-6 col-md-6"><button type="button" id="btn_back" class="btn btn-block btn_color">戻る</button></div>
            <div class="col-xs-6 col-md-6"><button type="button" id="btn_save" class="btn btn-block btn_color">保存</button></div>
        </div>
    </div>
</div>
<?php if (isset($id)) : ?>
<input type="hidden" id="id" name="id" value="<?php echo $id ?>" />
<?php endif; ?>
<?php echo $this->Form->end(); ?>

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
<script>
    $(function () {
        //Prevent page leave without save
        var unsaved = false;
        $(':input').change(function () {
            unsaved = true;
        });
        $('body').on('change', '.input', function () {
            unsaved = true;
        });
        $('body').on('change', '#date_picker', function () {
            unsaved = true;
        });

        //Function area
        var area = $('.cloneme').length;
        $('#btnarea').click(function () {
            var $clone = $('table.tbll tr.cloneme:first').clone();
                $clone.append("<td><div class='rmv'>&nbsp;X</div></td>");
                $('table.tbll').append($clone);
            var j = 1;
            $('.cloneme .area').each(function () {
                $(this).attr('name', 'area[' + j + ']');
                $(this).attr('id', 'area[' + j + ']');
                j++;
            });
            $('#hdf_area').val(area);
            unsaved = true;
            mybutton();
        });
        $('body').on('click', '.rmv', function () {
            $(this).parents(".cloneme").remove();
            var j = 1;
            $('.cloneme .area').each(function () {
                $(this).attr('name', 'area[' + j + ']');
                $(this).attr('id', 'area[' + j + ']');
                j++;
            });
            area = area - 1;
            $('#hdf_area').val(area);
            unsaved = true;
            mybutton();
        });
        $('#hdf_area').val(area);
        function mybutton() {
            if (area >= 5) {
                $('#btnarea').addClass('hide');
            } else {
                $('#btnarea').removeClass('hide');
            }
        }
        mybutton();
        $('.mytarget').each(function () {
            var val = $(this).attr('value');
            var val_target = '<?php echo isset($target) ? $target : ''; ?>';
            var array_target = val_target.split(',');
            for (var j = 0; j < array_target.length; j++) {
                var target = array_target[j];
                if (val == target) {
                    $('input:checkbox[name=target][value=' + val + ']').attr('checked', true);
                }
            }
        });

        $('body').on('change', '.select_val', function () {
            var val = $(this).val();
            if (val == 'filter') {
                $('.display').css('display', 'block');
            } else {
                $('.display').css('display', 'none');
            }
        });

        $('body').on('change', '.upload', function () {
            $(this).parent().find('img').remove();
            var image = $(this).parent();
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                if ($(this).val()) {
                    $(this).parents('.ui-state-default').find('.text-center').remove();
                }
                reader.onload = function (e) {
                    image.prepend("<img src='" + e.target.result + "' style='width:75px; height:75px' />");
                }
                $('#file_upload').hide();
                reader.readAsDataURL(this.files[0]);
            }
        });

        var index = $('.item').length + $('.item_old').length;
        //Remove photo
        $('body').on('click', '.remove', function () {
            index = index - 1;
            var check_type = $(this).parents('.ui-state-default').find('.id').val();
            if (check_type === undefined) {
                $(this).parents('.ui-state-default').remove();
            } else {
                $(this).parents('.ui-state-default').find('.delete').val(1);
                $(this).parents('.ui-state-default').hide();
            }
            $('#add_media').addClass('add_media');
            $('#item').val($('#item').val() - 1);
            unsaved = false;
        });
        //Add new photo
        $('.add_media').on('click', function () {
            if (index < 5) {
                var element = '';
                var last_item = $('.item').length;
                element = '<div id="img_name" class="select_img show_img ui-state-default profile-pic img-box">'
                        + '<div id="center_word">'
                        + '<img src="'+URL+'img/default.png" style="width:75px; height:75px">'
                        + '<input id="upload" data-id="' + last_item + '" class="upload" required="required" class="form-control col-md-3" accept=".png, .gif, .jpg" name="data[Notices][file_image]" required="true" type="file" size=1 style="width:75px; margin-top: -48px;position: absolute cursor: pointer; opacity: 0.001;">'
                        + '<a href="javascript:void(0);" data-name="" class="remove item edit"><i class="fa fa fa-trash-o fa-lg"></i></a>'
                        + '</div>'
                        + '<input type="hidden" id="img_hidden_name' + last_item + '" name="image[' + last_item + ']" value="" />'
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
            var $image_id = $(this).attr("id");
            $('.image_name').val($image_name);
            $('.image_id').val($image_id);
        });
        //Delete photo
        $('form#delete_photo').on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            var image_id = $('.image_id').val();
            $.ajax({
                url: URL + 'notices/edit',
                data: data + "&action=delete",
                beforeSend: function () {
                    $('#delete_loading').removeClass('hide');
                },
                success: function () {
                    $('#delete_loading').addClass('hide');
                    $('#ModalDeletephoto').modal('hide');
                    $('#' + image_id).remove();
                    index -= 1;
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                    $("#delete_loading").addClass('hide');
                }
            });
            unsaved = true;
        });
        $('#btn_save').click(function () {
            var target = [];
            $.each($('input[type="checkbox"]:checked'), function () {
                target.push($(this).val());
            });

            $('#target').val(target);
            if ($.trim($('#time').val()) === '') {
                alert('Please select time.');
                $("#ms_time").css({"color": "red"});
                return false;
            } else {
                $("#ms_time").css({"color": "black"});
                $('#ModalSaveConfirm').modal('show');
            }
            if ($.trim($('#title').val()) === '') {
                alert('必要なフィールドを確認してください');
                $("#ms_title").css({"color": "red"});
                return false;
            } else {
                $("#ms_title").css({"color": "black"});
            }            
            if ($.trim($('#message').val()) === '') {
                  alert('必要なフィールドを確認してください');
                  $("#ms_text").css({"color": "red"});
                return false;
            } else {
                $("#ms_text").css({"color": "black"});
                $('#ModalSaveConfirm').modal('show');
            }
        });
        $('body').on('click', '#btn_confirm_save', function (e) {
            e.preventDefault();
            var data = $('#edit').serialize();
            $.ajax({
                url: URL + 'notices/edit',
                data: data + "&action=edit",
                type: 'get',
                beforeSend: function () {
                    $('.loading-item').addClass('hide');
                    $('#save_loading').removeClass('hide');
                },
                success: function () {
                    $('#loading_save').addClass('hide');
                    $('#ModalSaveConfirm').modal('hide');
                    unsaved=false;
                    window.location.replace(URL +'users/view/notices');
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('error save_data');
                },
                complete: function () {
                    $('#save_loading').addClass('hide');
                    unsaved = false;
                }
            });
        });

        $('form#edit').on('change', '.upload', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            upload_photo(id);
        });
        function upload_photo(id) {
            $('.form-horizontal').ajaxForm({
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    // Do nothing
                },
                success: function (respond) {
                    if (respond.result === 'error') {
                        // Do nothing
                    } else {
                        $('#img_hidden_name' + id).val(respond.image);
                    }
                    $('.upload').val('');
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                },
                complete: function () {
                    $('#loading' + id).addClass('hide');
                }
            }).submit();
        }
        //Prevent page leave unsave
        window.onbeforeunload = function () {
            if (unsaved) {
                return 'Your data not yet save, if you leave page your data will lost! ';
            }
        }
        $('form').submit(function () {
            window.onbeforeunload = null;
        });
        $('#btn_back').click(function () {
            window.location.replace(URL +'users/view/notices');
        });
    });
</script>
