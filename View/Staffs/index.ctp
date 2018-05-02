<?php echo $this->Form->create('Staff',
    array('type' => 'file', 'id' => 'form_staff'));
?>
<?php
echo $this->Html->css('media_index');
?>
<style>
    .btn-move{
        background: transparent;
        border: none;
        outline: 0;
        margin-top: 20px;
    }
    .col-md-3{
        float: right;
    }
    .scroll_bord{
       margin-top: 13px;
       margin-left: 441px;
    }
    @media(max-width:800px){
    .scroll_bord {
        top: 95px;
        margin-right: 15px;
    }
    .icon_down{
        margin-top: 40px;
    }
}
</style>

    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
            <h1 class="page-header">
                アプリ作成 ＞ スタッフ紹介
            </h1>
            <?php $role = $this->Session->read('Auth.User.role'); ?>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="padding-right: 0px;">
                    <div class="form-group">
                        <?php
                        echo $this->Html->link('削除情報',
                            array('controller' => 'customers', 'action' => 'deleted?type=staff'),
                            array('class' => 'btn btn-block btn_color right'));
                        ?>
                    </div>
             </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
            <?php echo $this->Session->flash(); ?>
        </div>
    </div>
<?php if ($role == ROLE_HEADQUARTER) : ?>
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <?php
                echo $this->Form->input('shop_id', array(
                'id'   => 'shop_id',
                'type' => 'select',
                'name' => 'shop_id',
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
    <div class="collection">
        <?php
        foreach ($staff as $key => $value) :
            if ($value['Staff']['published'] == '1') {
                $is_check = 'checked';
            } else {
                $is_check = '';
            }
            if ($value['Staff']['is_at_work'] == '1') {
                $is_at_work = 'checked';
            } else {
                $is_at_work = '';
            }
            
            ?>
            <div class="item item-sort">
                <div class="row">
                    <?php
                        echo $this->Form->hidden('Staff.id.', array('id' => 'staffs-id-' . $value['Staff']['id'], 'class' => 'staffs-id', 'value' => $value['Staff']['id']));
                        echo $this->Form->hidden('Staff.sort.', array('id' => 'staffs-sort-' . $value['Staff']['id'], 'class' => 'staffs-sort', 'value' => $value['Staff']['sort']));
                    ?>
                    <div class="col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <?php
                                    if (!empty($value['Staff']['image'])) {
                                        echo $this->Html->image('/uploads/staffs/'.$value['Staff']['image'],
                                            array(
                                            'class' => 'img-responsive img-center',
                                            'alt' => $value['Staff']['name'],
                                        ));
                                    } else {
                                        echo '<img src="'+URL+'img/noimage.jpg" class="img-responsive img-center">';
                                    }
                                    ?>
                                </div>
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                    <form role="form">
                                        <div class="form-group">
                                            <label class="header"><?php echo $value['Staff']['name']; ?></label><br/>
                                            <div class="icon-top">
                                                <button value='up' class="btn-move glyphicon glyphicon-triangle-top"></button>
                                            </div>

                                            <?php echo $this->Html->image('scroll_bord.png', array('class' => 'scroll_bord')); ?>
                                            
                                            <div class="icon_down">
                                                <button value='down' class="btn-move glyphicon glyphicon-triangle-bottom"></button>
                                            </div>
                                        </div>
                                        <button class="btn btn-success color specific_height">役職</button>&nbsp;<?php echo $value['Staff']['position'] ?>&nbsp;
                                        <br/> <br/>
                                        <p><?php echo $value['Staff']['introduction'] ?></p>
                                        <br/>
                                    </form><br/>
                                    <div id='button_toggle'>
                                        <?php
                                        echo $this->Html->link(' 編集 ',
                                            '/staffs/edit/'.$value['Staff']['id'],
                                            array('class' => 'btn btn-success color back_color but_design'));
                                        ?>
                                        <input type="checkbox"
                                               class="publish publish<?php echo $value['Staff']['id'] ?>"
                                               id="<?php echo $value['Staff']['id'] ?>" <?php echo $is_check; ?>
                                               data-toggle="toggle"
                                               data-on="<div class='toggle_on'></div> 公開"
                                               data-off="<div class='toggle_off'></div> 非公開"
                                               data-onstyle="warning">
                                        <input type="button" id="<?php echo $value['Staff']['id']; ?>" class="btn btn-success color back but_design2 get_staff_id"  value="削除" data-toggle="modal" data-target="#ModalDeleteStaff"/>
                                        <input type="checkbox"
                                               class="at_work at_work<?php echo $value['Staff']['id'] ?>"
                                               id="<?php echo $value['Staff']['id'] ?>" <?php echo $is_at_work; ?>
                                               data-toggle="toggle"
                                               data-on="<div class='toggle_on'></div> 出勤中"
                                               data-off="<div class='toggle_off'></div> 退勤"
                                               data-onstyle="warning">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php endforeach; ?>
    </div>
<?php //echo $this->Html->script('jquery-sort');  ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-center">
            <div class="panel dotted-border">
                <div class="" id="bth_add">
                    +スタッフ新規追加
                </div>
            </div>
            <button type="submit" class="btn btn-block btn_color" id="submit-change-order">保存</button>
            <br/><br/>
        </div>
    </div>

<?php echo $this->Form->end(); ?>
<!--Modal Delete-->
<div id="ModalDeleteStaff" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--        Modal content-->
        <div class="modal-content">
            <?php echo $this->Form->create('Staff',
                array('id' => 'delete_staff'));
            ?>
            <div class="modal-body text-center">
                <label id="label_notification_notice">本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del1"><label for="del1">&nbsp;完全に削除する</label> <br>
                <input type="hidden" name="staff_id" class="staff_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除...</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_last_notice" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <button type="submit" class="btn btn-success color back_color but_design" style="width: 100px;">はい</button>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!--Modal publish confirm-->
<div id="ModalPublishConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <?php
            echo $this->Form->create('Staff', array('id' => 'publish_staff'));
            ?>
            <div class="modal-body text-center">
                <input type="hidden" id="publish_status">
                <input type="hidden" id="publish_id">
                <label id="label_publish"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_publish" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="button" id="btn_publish_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
<?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!--Modal is_at_work confirm-->
<div id="ModalAtWorkConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <?php
            echo $this->Form->create('Staff', array('id' => 'at_work_staff'));
            ?>
            <div class="modal-body text-center">
                <input type="hidden" id="at_work_status">
                <input type="hidden" id="at_work_id">
                <label id="label_at_work"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_at_work" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="button" id="btn_at_work_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
<?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<input type="hidden" id="cancel_id" name="cancel_id"/>
<script type="text/javascript">
    $(function () {
        var unsaved = false;
        $('body').on('change', '.input', function () {
            unsaved = true;
        });

        $('.get_staff_id').click(function () {
            var id = $(this).attr('id');
            $('.staff_id').val(id);
        });
        
        $('form#delete_staff').submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'staffs', 'action' => 'index')); ?>",
                data: data + "&action=delete",
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $('.success-message, .error-message').html('');
                    $('#delete_loading').removeClass('hide');
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $('#delete_loading').addClass('hide');
                        $('#ModalDeleteStaff').modal('hide');
                        window.location.reload();
                    } else {
                        $('#error-msg-delete').html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error: ' + xhr.status);
                }
            });
        });
   
          $('form#form_staff').on('change', '.publish', function () {
                var id = $(this).attr('id');
                var publish = $(this).prop('checked');
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'staffs', 'action' => 'index')); ?>",
                data:"&action=publish&publish=" + publish + "&staff_id=" + id,
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $('.save_loading').removeClass('hide');
                },
                succee: function (respond) {
                    console.log(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                },
                complete: function () {
                }
            });
            unsaved = true;
        });
  
        $('form#form_staff').on('change', '.at_work', function () {
            var at_work = $(this).prop('checked');
            var id = $(this).attr('id');
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'staffs', 'action' => 'index')) ?>",
                data:"&staff_id=" + id + "&action=at_work&at_work=" + at_work,
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $('.save_loading').removeClass('hide');
                },
                success: function (respond) {
                    console.log(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $('.save_loading').addClass('hide');
                }
            });
            unsaved = true;
        });

        function reArrangeSort() {
            if ($('.item-sort').length) {
                $('.collection .staffs-sort').each(function(index, value) {
                    $(this).val(index + 1);
                });
            }            
        }

        function moveUp(item) {
            var prev = item.prev();
            if (prev.length == 0)
                return;
            prev.css('z-index', 999).css('position', 'relative').animate({
                top: item.height()
            }, 250);
            item.css('z-index', 1000).css('position', 'relative').animate({
                top: '-' + prev.height()
            }, 300, function () {
                prev.css('z-index', '').css('top', '').css('position', '');
                item.css('z-index', '').css('top', '').css('position', '');
                item.insertBefore(prev);
                reArrangeSort();
            });
            unsaved = true;            
        }

        function moveDown(item) {
            var next = item.next();
            if (next.length == 0)
                return;
            next.css('z-index', 999).css('position', 'relative').animate({
                top: '-' + item.height()
            }, 250);
            item.css('z-index', 1000).css('position', 'relative').animate({
                top: next.height()
            }, 300, function () {
                next.css('z-index', '').css('top', '').css('position', '');
                item.css('z-index', '').css('top', '').css('position', '');
                item.insertAfter(next);
                reArrangeSort();
            });
            unsaved = true;
        }

        $('body').on('click', '#submit-change-order', function() {
            $('#form_staff').submit();
        });

        $('body').on('click', '.btn-move', function (e) {
            e.preventDefault();
            var btn = $(this);
            var val = btn.val();
            if (val == 'up') {
                moveUp(btn.parents('.item'));
            } else {
                moveDown(btn.parents('.item'));                
            }
        });

        //Prevent page leave unsave
        <?php if ($role !== ROLE_HEADQUARTER) : ?>
            window.onbeforeunload = function () {
                if (unsaved) {
                    return 'Your data not yet save! ';
                }
            };
        <?php endif; ?>

        $('form').submit(function () {
            window.onbeforeunload = null;
        });

        $('#bth_add').click(function () {
            window.location.replace(URL + 'users/view/app-staff-create'<?php if ($role == ROLE_HEADQUARTER) : ?>+'/?shop_id='+$('#shop_id').val()<?php endif; ?>);
        });

        $('#shop_id').on('change', function () {
            window.location.replace(URL + 'users/view/app-staffs/?shop_id=' + $('#shop_id').val());
        });
    });
</script>