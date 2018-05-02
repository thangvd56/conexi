<?php echo $this->Html->css('media_menu'); ?>
<style>
    .dotted-border:hover{
        cursor: pointer;
    }
    .col-md-3{
        float: right;
    }
</style>
<div>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-sm-8">
            <h1 class="page-header">
                アプリ作成 > メニュー
            </h1>
            <div class="pull-right form-inline form-group">
                <?php
                    echo $this->Form->input('is_display_list', array(
                        'type' => 'radio',
                        'options' => array(1 => 'リスト表示',),
                        'checked' => isset($display_in_list) ? $display_in_list : 0,
                        'hiddenField' => false,
                        'class' => 'display-option',
                        'data-confirm' => 'メニューをリスト表示に切り替えますか？'
                    ));
                    echo $this->Form->input('is_display_list', array(
                        'type' => 'radio',
                        'options' => array(0 => 'アイコン表示',),
                        'checked' => isset($display_in_list) && !$display_in_list ? 1 : 0,
                        'hiddenField' => false,
                        'class' => 'display-option',
                        'data-confirm' => 'メニューをアイコン表示に切り替えますか？'
                    ));
                ?>
            </div>
            <?php $role = $this->Session->read('Auth.User.role'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-8">
            <div class="row">
                <?php if ($role == ROLE_HEADQUARTER) : ?>
                <div class="col-xs-12 col-sm-4">
                    <?php
                        echo $this->Form->input('shop_id', array(
                        'id'   => 'shop_id',
                        'type' => 'select',
                        'class' => 'shop_name form-control',
                        'label' => false,
                        'options' => isset($shops) ? $shops : array(),
                        'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                        ));
                    ?>
                </div>
                <?php endif; ?>
                <?php if(isset($shop_id)) {
                    echo $this->Form->input('shop_id', array(
                        'type' => 'hidden',
                        'id' => 'shop_id',
                        'value' => $shop_id
                    ));
                } ?>
                <div class="col-sm-3 pull-right">
                    <div class="form-group">
                        <?php
                        echo $this->Html->link('削除情報',
                            array('controller' => 'customers', 'action' => 'deleted?type=menu_categories'),
                            array('class' => 'btn btn-block btn_color right', 'id' => 'btnDelInfo'));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <!-- /.row -->
    <div id="loading_menu_category_list" class="text-center hide col-xs-12 col-md-8 col-sm-12 col-xs-12">
        <?php echo $this->Html->image('/uploads/loading.gif', array()).' ローディング'; ?>
    </div>
    <div id="fetch_menu_category_list"></div>
    <div class="row">
    <div class="col-xs-12 col-lg-8 col-sm-12 text-center">
        <div class="panel dotted-border" id="bth_add">
            <div class="">
                +メニュー新規追加
            </div>
        </div>
        <button type="button" class="btn btn-block btn_color add_menu" data-toggle="modal" data-target="#ModalSaveConfirm">保存</button>
    </div>
    </div>
</div><!-- container-fluid -->
<!--Modal publish confirm-->
<div id="ModalPublishConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <?php echo $this->Form->create('MenuCategory',
                array('id' => 'form_publish'));
            ?>
            <div class="modal-body text-center">
                <input type="hidden" id="publish_id">
                <input type="hidden" id="publish_status">
                <label id="label-publish"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_publish" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="button" id="btn_confirm_publish" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
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
<div id="ModalDeleteMenuCate" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('MenuCategory',
        array('id' => 'delete_menu_cate'));
    ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-delete-confirm"></label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del1" class="hide"><label for="del1" class="hide">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" name="menu_cate_id" class="menu_cate_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
<!--                <div class="clearfix">&nbsp;</div>-->
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
<?php echo $this->Form->end(); ?>
</div>
<div id="confirm-option" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-body text-center">
                <p>メニュー表示を <span></span> 表示に切り替えますか？</p>
                <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">いいえ</button>
                <button type="button" class="btn btn-default btn-ok">はい</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    $(function () {

        var unsaved = false;
        $(':input').change(function () {
            unsaved = true;
        });

        function fetch_menu_category_list() {
            var data = {};
            if ($('#shop_id').length > 0) {
                data = {'shop_id': $('#shop_id').val()};
            }

            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'menu_categories','action' => 'fetch_menu_category_list'))?>",
                method: 'get',
                data: data,
                type: 'html',
                beforeSend: function () {
                    $("#loading_menu_category_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#fetch_menu_category_list").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_menu_category_list").addClass("hide");
                }
            });
        }
        fetch_menu_category_list();
        //Alert confirm before delete
//        $("#btn_confirm_delete").click(function () {
//            $("#ModalDeleteMenuCate").modal("show");
//            $("#ModalDeleteConfirm").modal("hide");
//        });
        $('form#delete_menu_cate').on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'menu_categories','action' => 'index'))?>",
                data: data + "&action=delete",
                type: "get",
                beforeSend: function () {
                    $("#delete_loading").removeClass("hide");
                },
                success: function () {
                    $("#delete_loading").addClass("hide");
                    $("#ModalDeleteMenuCate").modal("hide");
                    unsaved = false;
                    //window.location.reload();
                    fetch_menu_category_list();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                    $("#delete_loading").addClass("hide");
                }
            });
              unsaved = false;
        });

//        $('#btn_confirm_publish').click(function () {
           $('body').on("change", ".publish", function () {
            //var id = $('#publish_id').val();
            var id = $(this).attr("id");
            var publish = $(this).prop('checked');
            //var publish = $("#publish_status").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'menu_categories','action' => 'index'))?>",
                data: "&action=publish&published=" + publish + "&menu_cate_id=" + id,
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
              unsaved = true;
        });

//        $('#btn_cancel_publish').click(function () {
//            var id = $("#publish_id").val();
//            var publish = $("#publish_status").val();
//            var toggle;
//            publish === 'true' ? toggle = 'off' : toggle = 'on';
//            $(".toggle" + id).bootstrapToggle(toggle);
//            $("#ModalPublishConfirm").modal("hide");
//        });

        $('body').on('click', '#btn_confirm_save', function () {
            var data = $("#menu_category").serialize();
            data = data + '&shop_id='+$('#shop_id').val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'menu_categories','action' => 'index'));?>",
                data: data + "&action=save",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $("#save_loading").removeClass("hide");
                },
                success: function () {
                    $("#loading_save").addClass("hide");
                    $("#ModalSaveConfirm").modal("hide");
                    unsaved = false;
                    //window.location.reload();
                    fetch_menu_category_list();
                    //location.reload();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("error save_data");
                },
                complete: function () {
                    $("#save_loading").addClass("hide");
                    unsaved = false;
                }
            });
        });
        //Prevent page leave unsave
        <?php if ($role !== ROLE_HEADQUARTER) : ?>
            window.onbeforeunload = function () {
                if (unsaved) {
                    return "Your data not yet save, if you leave page your data will lost! ";
                }
            }
        <?php endif ?>
        $('form').submit(function () {
            window.onbeforeunload = null;
        });

        $("#shop_id").on("change", function () {
            window.location.replace(URL+"users/view/app-menu/?shop_id="+$("#shop_id").val());
        });

        $(".display-option").on("click", function () {
            var text = $(this).attr('data-confirm');
            $('#confirm-option').find('p').html(text);
            $('#confirm-option').modal('show');
        });

        $("#confirm-option .btn-ok").on("click", function () {

            var data = {'shop_id': $('#shop_id').val()};
            $(".display-option").each(function(key, element){
                if($(element).is(':checked')) {
                    data['is_display_list'] = $(element).val();
                    return;
                }
            });
            $.ajax({
                url: "<?php echo Router::url('/', true);?>menuCategories/displayOption/",
                data: data,
                method: 'post',
                type: "json",
                success: function (respone) {
                    var obj = $.parseJSON(respone);
                    alert(obj.message);
                    $('#confirm-option').modal('hide');
                }
            });
        });

        $("#confirm-option .btn-cancel").on("click", function () {
            var radio = '';
            $(".display-option").each(function(key, element){
                if(!$(element).is(':checked')) {
                    radio = $(element);
                }
            });
            $(radio).trigger('click');
        });
    });
</script>