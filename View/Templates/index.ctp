<?php echo $this->Form->create('Template', array('id' => 'from_template')); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-8">
            <h1 class="page-header">
                アプリ作成 > 定型文設定
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    定型文追加
                    <div id="loading_template_list" class="text-center hide">
                        <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' Loading...'; ?>
                    </div>
                    <p class="sub_header">定型文を追加しておくと、カルテ機能が便利になります。</p>
                </li>
            </ol>
        </div>
    </div>
    <div class="row" >
        <!-- Template list here-->
        <div id="fetch_template_list"></div>
        <!-- End Template list-->     
        <div class="col-xs-12 col-md-8 text-center">          
            <div class="panel dotted-border" id="btn_add">
                <div class="">
                    +定型文を新規追加
                </div>
            </div>
<!--            <div id="save_loading" class="hide col-md-3 pull-left"><?php //echo $this->Html->image('loading.gif'); ?>&nbsp;Saving...</div>-->
            <input type="button" class="btn btn-block btn_color"​​ data-toggle="modal" data-target="#ModalSaveConfirm" value="保存">
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">     
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">の変更内容を保存します。<br>本当によろしいですか？</label>
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
<!--Modal Delete-->
<div id="ModalDeleteTemplate" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--        Modal content-->
        <div class="modal-content">
            <?php echo $this->Form->create('Template', array('id' => 'delete_template')); ?>
            <div class="modal-body text-center">
                <label id="label_notification_notice">本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del1"><label for="del1">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" name="template_id" class="template_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
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
<script type="text/javascript">
    $(function () {
        function fetch_tempate_lists() {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'templates', 'action' => 'fetch_template_lists')); ?>",
                dataType: 'html',
                beforeSend: function () {
                    $("#loading_template_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#fetch_template_list").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_template_list").addClass("hide");
                    $("form#from_template")[0].reset();
                }
            });
        }

        fetch_tempate_lists();

        $("form#delete_template").submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'templates', 'action' => 'index')); ?>",
                data: data + "&action=delete",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".error-message .success-message").html("");
                    $("#delete_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalDeleteTemplate").modal("hide");
                        $(".error-message .success-message").html("");
                        fetch_tempate_lists();
                    } else {
                        $("#error-msg-delete").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwErros) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#delete_loading").addClass("hide");
                }
            });
        });

       $("body").on("click", "#btn_confirm_save", function (e) {
            e.preventDefault();
            var data = $("#from_template").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'templates', 'action' => 'index')); ?>",
                data: data + "&action=save",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $("#save_loading").removeClass("hide");
                    $("#save_loading").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $("#success_save").html(respond.msg);
                        $("#success_save").removeClass("hide");                       
                        $("#ModalSaveConfirm").modal("hide");
                        //window.location.reload();
                        fetch_tempate_lists();
                    } else {
                        $("#error_save").html(respond.msg);
                        $("#error_save").removeClass("hide");
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#save_loading").addClass("hide");
                }
            });
        });
    });
</script>
