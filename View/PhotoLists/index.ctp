<?php echo $this->Html->css('PhotoGalleryList'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-7">
            <h1 class="page-header">
                アプリ作成 - フォトギャラリー
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    フォトギャラリー追加
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div id="loading_photo_list" class="text-center hide col-lg-7">
            <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
        </div>
    </div>
    <div id="fetch_photo_list"></div>
    <div class="col-lg-7 text-center">
        <div class="panel dotted-border btn_add">
            <div class="">
                +新規追加
            </div>
        </div>
        <button type="button" class="btn btn-block btn_color  btn_save">保存</button>
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
    <?php echo $this->Form->create('PhotoList', array('id' => 'delete_photo_list')); ?>
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
<!--Modal publish confirm-->
<div id="ModalPublishConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo $this->Form->create('PhotoList', array('id' => 'published_photo_list')); ?>
            <div class="modal-body text-center">
                <input name="photo_id" type="hidden" id="publish_id">
                <input name="published" type="hidden" id="publish_status">
                <label id="label-publish"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_publish" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="submit" id="btn_confirm_publish" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!--Modal save confirm-->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">の変更内容を保存します。<br>本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
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

        //Alert confirm before save
        $(".btn_save").click(function () {
            $("#ModalSaveConfirm").modal("show");
        });

        //Alert confirm before delete
        $("body").on("click", ".btn_delete", function (e) {
            e.preventDefault();
            $("#ModalDeleteConfirm").modal("show");
            var title = $(this).attr("data-name");
            var id = $(this).attr("id");
            $("#photo_id").val(id);
            $("#label-delete").html(title + "を削除します。<br>この" + title + "を非表示BOX箱に移 <br>動します。<br>よろしいですか？");
            $("#label-delete-confirm").html(title + "を削除します本当によろしいですか？");
        });
        $("#btn_confirm_delete").click(function () {
            $("#ModalDeleteConfirm").modal("hide");
            $("#ModalDeletePhoto").modal("show");
        });

        //Fetch photo gallery list
        function fetch_photo_gallery_list() {
            var currentURL = document.URL;
            var photo_id = currentURL.split("photo_id=")[1];
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photo_lists', 'action' => 'fetch_photo_gallery_list')) ?>",
                dataType: "html",
                type: "get",
                data: "photo_id=" + photo_id,
                beforeSend: function () {
                    $("#loading_photo_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#fetch_photo_list").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $("#loading_photo_list").addClass("hide");
                }
            });
        }
        fetch_photo_gallery_list();

        //Delete photo
        $("form#delete_photo_list").on("submit", function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'photo_lists', 'action' => 'index')) ?>",
                data: data + "&action=delete",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".delete_loading").removeClass("hide");
                },
                success: function () {
                    $("#delete_loading").addClass("hide");
                    $("#ModalDeletePhoto").modal("hide");
                    window.location.reload();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    $(".delete_loading").addClass("hide");
                    console.log("Error: " + xhr.status);
                }
            });
        });

    });
</script>