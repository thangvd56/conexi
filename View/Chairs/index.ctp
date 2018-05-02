
<?php
echo $this->Form->create('Chair', array('id' => 'form_chair'));
?>
<!-- Page Heading -->
<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-8">
        <h1 class="page-header">
            アプリ作成 ＞ 予約テーブル作成
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <div id="loading_chair_list" class="text-center hide">
                    <?php
                    echo $this->Html->image('/uploads/loading.gif', array()).'ローディング... ...';
                    ?>
                </div>
            </li>
        </ol>
    </div>
</div>
<?php 
$role = $this->Session->read('Auth.User.role');
if ($role == ROLE_HEADQUARTER) : ?>
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <?php
                echo $this->Form->input('shop_id', array(
                'id'   => 'shop_id',
                'type' => 'select',
                'name' => 'shop_id',
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
<div class="row" >
    <!-- Chair list here-->
    <div id="fetch_chair_list"></div>
    <!-- End Template list-->
    <div class="col-xs-12 col-md-12 col-lg-8 text-center">
        <div class="panel dotted-border" id="btn_add">
            <p>
                +テーブルを追加する
            </p>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12 top">
                <!--                <div class="col-xs-6 col-md-6"><button type="button" id="btn_back" class="btn btn-block btn_color">戻る</button></div>-->
                <button type="button" id="btn_save" data-toggle="modal" data-target="#ModalSaveConfirm" class="btn btn-block btn_color">保存</button>
                <br/>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">変更内容を保存します。本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div id="exist"  style="color:red;"></div>
                <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design"​   style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal Delete-->
<div id="ModalDeleteChair" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <?php
            echo $this->Form->create('Chair', array('id' => 'delete_chair'));
            ?>
            <div class="modal-body text-center">
                <label id="label_chair">本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del1"><label for="del1">&nbsp;完全に削除する</label> <br>
                <input type="hidden" name="chair_id" class="chair_id"/>
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
    <?php
    echo $this->Html->css('numberPickup');
    echo $this->Html->script('numberPickup');
    ?>

<script type="text/javascript">
    $(function () {
        fetch_chair_lists();

        function fetch_chair_lists() {
            var data = {};
            if ($('#shop_id').length > 0) {
                data = { 'shop_id': $('#shop_id').val()}
            }
            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'chairs', 'action' => 'fetch_chair_lists')); ?>',
                dataType: 'html',
                data: data,
                beforeSend: function () {
                    $('#loading_chair_list').removeClass('hide');
                },
                success: function (respond) {
                    $('#fetch_chair_list').html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                },
                complete: function () {
                    $('#loading_chair_list').addClass('hide');
                    $('form#form_chair')[0].reset();
                }
            });
        }

        $('form#delete_chair').submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'chairs', 'action' => 'index')); ?>',
                data: data + '&action=delete',
                dataType: 'json',
                type: 'get',
                beforeSend: function () {
                    $('.error-message .success-message').html('');
                    $('#delete_loading').removeClass('hide');
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $('#ModalDeleteChair').modal('hide');
                        $('#del1').prop('del_physical', false);
                        fetch_chair_lists();
                    } else {
                        $('#error-msg-delete').html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwErros) {
                    console.log('Error:' + xhr.status);
                },
                complete: function () {
                    $('#delete_loading').addClass('hide');
                }
            });
        });

        $('body').on('click', '#btn_confirm_save', function (e) {
            e.preventDefault();
            var data = $('#form_chair').serialize();
            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'chairs', 'action' => 'index')); ?>',
                data: data + '&action=save',
                dataType: 'json',
                type: 'get',
                beforeSend: function () {
                    $('#save_loading').removeClass('hide');
                    $('#save_loading').removeClass('hide');
                    $('.error-message, .success-message').html('');
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $('#success_save').html(respond.msg);
                        $('#success_save').removeClass('hide');
                        $('#ModalSaveConfirm').modal('hide');
                        $('#exist').addClass('hide');
                        //window.location.reload();
                        fetch_chair_lists();
                    } else if (respond.result === 'exist') {
                        $('#exist').html(respond.msg);
                        $('#exist').removeClass('hide');
                    } else {
                        $('#error_save').html(respond.msg);
                        $('#error_save').removeClass('hide');
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                },
                complete: function () {
                    //$("#exist").addClass("hide");
                    $('#save_loading').addClass('hide');
                }
            });
        });

        $('body').on('keypress', '.allow_key', function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });

         $('#shop_id').on('change', function () {
            window.location.replace(URL + 'users/view/operation-chair/?shop_id=' + $('#shop_id').val());
        });
    });
</script>