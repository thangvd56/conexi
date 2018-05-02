<?php echo $this->Html->css('media_メニュー(2)'); ?>
<style>
    .col-md-3{
        float: right;
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-md-7">
            <h1 class="page-header">
                フォトギャラリー
            </h1>
<!--            <ol class="breadcrumb">
                <li class="active">
                    大カテゴリー追加
                </li>
            </ol>-->
             <div class="col-md-3">
                    <div class="form-group">
                        <?php
                        echo $this->Html->link('削除情報',
                            array('controller' => 'customers', 'action' => 'deleted?type=menu_photo'),
                            array('class' => 'btn btn-block btn_color right'));
                        ?>
                    </div>
             </div>
        </div>
    </div>
    <!-- /.row -->
    <div id="loading_menu_photo_list" class="col-xs-12 col-md-7 text-center hide">
        <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
    </div>
    <div id="fetch_photo_list"></div>
    <div class="col-xs-12 col-md-7 text-center">
        <div class="panel dotted-border btn-add">
            <div class="">
                +フォトギャラリーを追加します。
            </div>
        </div>
        <button type="button" class="btn btn-block btn_color btn_save">保存</button>
    </div>
</div>
<!--Modal publish confirm-->
<div id="ModalPublishConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <?php echo $this->Form->create('Photo', array('id' => 'form_publish')); ?>
            <div class="modal-body text-center">
                <input type="hidden" id="publish_id">
                <input type="hidden" id="publish_status">
                <label id="label-publish"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_publish" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="button" id="btn_confirm_publish" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!--Modal Delete-->
<div id="ModalDeleteConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-delete"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="ModalDeletePhoto" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('Photo', array('id' => 'delete_photo')); ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-delete-confirm"></label>
                <div class="clearfix">&nbsp;</div>

                <input type="checkbox" name="del_physical" value="1" id="physical_remove"><label for="physical_remove">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" id="photo_id" name="photo_id" value=""/>

                <div class="error-message" id="error-msg-delete"></div>
                <div class="hide delete_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_delete_photo" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
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
<script type="text/javascript">
    $(function () {

        //Fetch photo list
        function fetch_photo_list() {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photos', 'action' => 'fetch_photo_list')); ?>",
                beforeSend: function () {
                    $("#loading_menu_photo_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#fetch_photo_list").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_menu_photo_list").addClass("hide");
                }
            });
        }
        fetch_photo_list();

        //Delete photo
        $("form#delete_photo").submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photos', 'action' => 'index')); ?>",
                data: data + "&action=delete",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".delete_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $("#ModalDeletePhoto").modal("hide");
                        window.location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error :" + xhr.status);
                },
                complete: function () {
                    $(".delete_loading").addClass("hide");
                }
            });
        });

        //Publish
        $("#btn_confirm_publish").click(function () {
            var id = $("#publish_id").val();
            var publish = $("#publish_status").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photos', 'action' => 'index')) ?>",
                data: "&action=publish&published=" + publish + "&photo_id=" + id,
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
                    $(".save_loading").addClass("hide");
                    $("#ModalPublishConfirm").modal("hide");
                }
            });
        });

        //Cancel Publish
        $("#btn_cancel_publish").click(function () {
            var id = $("#publish_id").val();
            var publish = $("#publish_status").val();
            var toggle;
            publish === 'true' ? toggle = 'off' : toggle = 'on';
            $(".toggle" + id).bootstrapToggle(toggle);
            $("#ModalPublishConfirm").modal("hide");
        });

        //Show modal confirm when click save
        $(".btn_save").click(function () {
            $("#ModalSaveConfirm").modal("show");
        });

    });
</script>