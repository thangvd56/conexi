<?php echo $this->Html->css('media_menu'); ?>
<style>
    .btn_add{
        cursor: pointer;
    }
    .btn-move{
        background: transparent;
        border: none;
        outline: 0;
        top: -5px
    }
    .scroll_bord{
        margin-top: 10px;
    }
    .icon_down {
        margin-top: 45px;
    }
</style>
<div>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-8">
            <h1 class="page-header">
                アプリ作成 ＞ メニュー ＞ メニュー追加
            </h1>
        </div>
    </div>
    <!-- /.row -->
    <div id="loading_app_menu_list" class="col-lg-8 text-center">
        <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
    </div>
    <div id="fetch_app_menu_list"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-center">
                <div class="panel dotted-border btn_add">
                    <div style="text-align:center">
                        +メニュー新規追加
                    </div>
            </div>
        </div>
    </div>
<!--        <button type="button" id="btn_save" class="btn btn-block btn_color add_sub" data-toggle="modal" data-target="#ModalSaveConfirm">保存</button>-->
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <button type="button" id="btn_back" class="btn btn-block btn_color">戻る</button>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <button type="button" id="btn_save" class="btn btn-block btn_color add_sub" data-toggle="modal" data-target="#ModalSaveConfirm">保存</button>
            </div>
            </div>
        </div>
    </div>
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
                    <input type="button" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ModalDeleteAppMenuList" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php echo $this->Form->create('ApplicationMenuList', array('id' => 'delete_app_menu_list')); ?>
                <label id="label-delete-confirm">本当によろしいですか？</label>
                <input type="checkbox" name="del_physical" value="1" id="del1" class="hide"><label for="del1" class="hide">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" id="app_menu_list_id" name="app_menu_id" value=""/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
    
</div>
<script type="text/javascript">
    $(function () {
        var unsaved = false;
        $(':input').change(function () {
            unsaved = true;
        });
        function fetch_app_menu_list() {
            var currentURL = document.URL;
            var menu_id = currentURL.split("menu_id=")[1];
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'application_menu_lists', 'action' => 'fetch_app_menu_list')); ?>",
                type: "get",
                dataType: "html",
                data: "menu_id=" + menu_id,
                beforeSend: function () {
                    $("#loading_app_menu_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#fetch_app_menu_list").html(respond);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log("Error:" + xhr.status + " fetch_app_menu_list");
                },
                complete: function () {
                    $("#loading_app_menu_list").addClass("hide");
                }

            });
        }

        fetch_app_menu_list();

        $("form#delete_app_menu_list").on("submit", function (e) {
            e.preventDefault();
            var id = $("#app_menu_list_id").val();
            if( id === "" ){
                window.location.reload();
                return false;
            }
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'application_menu_lists', 'action' => 'index')) ?>",
                data: data + "&action=delete",
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $("#delete_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $("#ModalDeleteAppMenuList").modal("hide");
                        unsaved = false;
                        fetch_app_menu_list();
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete:function(){
                    $("#delete_loading").addClass("hide");
                }
            });
        });

//      $("#btn_confirm_publish").click(function () {
        $("body").on("change", ".publish", function () {
//            var id = $("#publish_id").val();
//            var publish = $("#publish_status").val();
            var publish = $(this).prop('checked');
            var id = $(this).attr("id");
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'application_menu_lists', 'action' => 'index')) ?>",
                data: "&action=publish&published=" + publish + "&app_menu_id=" + id,
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

        $("#btn_confirm_save").click(function () {
            var data = $("#menu_list").serialize();
            var currentURL = document.URL;
            var menu_id = currentURL.split("menu_id=")[1];
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'application_menu_lists', 'action' => 'index')) ?>",
                data: data + "&action=save&menu_id=" + menu_id,
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $("#save_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalSaveConfirm").modal("hide");
                    }
                    unsaved = true;
                    fetch_app_menu_list();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#save_loading").addClass("hide");
                }
            });
        });

        $('.btn_add').click(function () {
            if (!$('#menu_list').hasClass('form-inline')) {
                return;
            }
            if ($('#menu_list .row').length > 0) {
                var clone = $('#menu_list .row:last').clone();
                $.each($(clone).find('input[type!=button]'), function(index2, element2) {
                    var name = $(element2).attr('name');
                    var new_name = name.substring(0, 26)+(parseInt(name.substring(26, name.indexOf("]", 26))) + 1)+name.substring(name.indexOf("]", 26), name.length);

                    $(element2).val('');
                    $(element2).attr('id', '');
                    $(element2).attr('name', new_name);
                });
                $(clone).find('input[type=button]').attr('data-id', '');
                $('#menu_list').append(clone);
            } else {
                $row = $('<div>').addClass('row add-margin-bottom');
                $col = $('<div>').addClass('col-lg-8');
                $image = $('<input type="hidden">').attr('name', 'data[ApplicationMenuList][0][image]');
                $id = $('<input type="hidden">').attr('name', 'data[ApplicationMenuList][0][id]');
                $content = $('<input type="hidden">').attr('name', 'data[ApplicationMenuList][0][content]').val('');
                $col.append($id).append($image).append($content);
                $form_group1 = $('<div>').addClass('form-group')
                        .append($('<label>').addClass('col-xs-12').text('タイトル'))
                        .append($('<div>').addClass('input text required')
                            .append($('<input type="text">').addClass('form-control').attr('name', 'data[ApplicationMenuList][0][title]'))
                        );
                $form_group2 = $(' <div>').addClass('form-group')
                        .append($('<label>').addClass('col-xs-12').text('金額'))
                        .append($('<div>').addClass('input text required')
                            .append($('<input type="text">').addClass('form-control').attr('name', 'data[ApplicationMenuList][0][price]'))
                        );
                $form_group3 = $(' <div>').addClass('form-group')
                        .append($('<label>').addClass('col-xs-12 hide-text').text('削除'))
                        .append($('<input type="button">').addClass('btn btn-default btn_delete')
                            .attr('data-toggle', '#ModalDeleteAppMenuList').val('削除')
                            .attr('data-id', '')
                        );
                $col.append($form_group1).append($form_group2).append($form_group3);
                $row.append($col);
                $('#menu_list').append($row);
            }
        });
    });
</script>