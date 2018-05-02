<?php
echo $this->Html->css('support');
echo $this->Html->script(array(
        'jquery-1.10.2',
        'bootstrap.min',
        'bootstrap-toggle.min'
    ));
?>
<style>
    .border{
        cursor: pointer;
    }
    .back_color_edit{
        background: #24CC24 !important;
        border: none;
        padding: 5px 24px 2px 25px;
        box-shadow: 0 4px #66AD59;
        margin: 3px;
    }
    .col-md-3{
        float: right;
    }
</style>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-8">
            <h1 class="page-header">
                サポート
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    サポートについて
                    <div id="loading_support_list" class="text-center hide">
                        <?php echo $this->Html->image('/uploads/loading.gif',
                            array()).' Loading...'; ?>
                    </div>
                </li>
            </ol>
        </div>
    </div>
     <br/>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-8">
<!--            <div class="well scrollbox4" id="content_plan">-->
                <div class="support" id="content_plan">
                <h5>特別サポートプラン</h5>
                <div id="fetch_support_list"></div>
            </div>
            <div class="border" id="btn_add_support">
                <h4>+サポートプランを追加する</h4>
            </div>
        </div>
    </div>
    <h3>Q&A</h3>
    <div id="loading_question_answer_list" class="text-center hide">
    <?php echo $this->Html->image('/uploads/loading.gif', array()).' Loading...'; ?>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-8">
<!--            <div class="well scrollbox4" id="content_q">-->
                <div class="q_a" id="content_q">
                <div class="wrap">
                    <div id="fetch_question_answer_list"></div>
                </div>
            </div>
            <div class="border" id="add_question_answer">
                <h4>Q&Aを追加する</h4>
            </div>
        </div>
    </div>
</div><!-- container-fluid -->
<!--Model Support -->
<div id="ModalSaveConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
            <label id="label-save">の変更内容を保存します。<br>本当によろしいですか？</label>
            <input type="hidden" name="hdf_support_id" id="hdf_support_id" value="">
            <input type="hidden" id="hdf_form_id" name="hdf_form_id" class="hdf_form_id"/>
            <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <input type="button" value="キャンセル" class="btn btn-block btn_color background cancel_edit" data-dismiss="modal">
                </div>
                <div class="col-xs-6 col-md-6">
                    <input type="submit" id="btn_confirm_save" value="はい" class="btn btn-success btn-block btn_color" >
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal Delete Support-->
<div id="ModalDeleteSupport" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <label id="label_support">本当によろしいですか？</label>
                <input type="checkbox" name="del_physical" value="1" id="del1" class="hide"><label for="del1" class="hide">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" id="support_id" name="support_id" class="support_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif');              ?>&nbsp;Deleting...</div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                </div>
                <div class="col-xs-6 col-md-6">
                     <input type="submit" id="btn_delete_support" class="btn btn-success btn-block btn_color" value="はい">
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal Question and Asnswer-->
<div id="ModalSaveQAConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <label id="label-save-qa">の変更内容を保存します。<br>本当によろしいですか？</label>
                 <input type="hidden" name="hdf_question_answer_id" id="hdf_question_answer_id" value="">
                <input type="hidden" id="hdf_question_answer_form_id" name="hdf_question_answer_form_id" class="hdf_question_answer_form_id"/>
                <div id="save_loading2" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <input type="button" value="キャンセル" class="btn btn-block btn_color background cancel_edit" data-dismiss="modal">
                </div>
                <div class="col-xs-6 col-md-6">
                    <input type="submit" id="btn_confirm_qa_save" value="はい" class="btn btn-success btn-block btn_color" >
                </div>
            </div>
        </div>
    </div>
</div>
<!--//Modal Delete Question and Answer-->
<div id="ModalDeleteQA" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <label id="label_qa">本当によろしいですか？</label>
                <input type="checkbox" name="del_physical" value="1" id="del2" class="hide"><label for="del2" class="hide">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" id="question_answer_id" name="question_answer_id" class="question_answer_id"/>
                <div class="error-message" id="error-msg-delete2"></div>
                <div id="delete_loading2" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                </div>
                <div class="col-xs-6 col-md-6">
                     <input type="submit" id="btn_delete_question_answer" class="btn btn-success btn-block btn_color" value="はい">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        //Fetch for support list
        function fetch_support_lists() {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'supports', 'action' => 'admin_fetch_support_lists')); ?>",
                dataType: 'html',
                beforeSend: function () {
                    $("#loading_support_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#fetch_support_list").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_support_list").addClass("hide");
                    //$("form#from_template")[0].reset();
                }
            });
        }
        fetch_support_lists();
        $('body').on('click', '.delete_support', function () {
            var id = $(this).attr('data-id');
            $('#support_id').val(id);
            //do something when the user clicks the box
            $('#del1').val("");
            $('#del1').change(function () {
                var val = "";
                if (this.checked) {
                    $("#del1").val(1);
                } else {
                    $("#del1").val(val);
                }
            });
        });
        $("body").on("click", "#btn_delete_support", function () {
            var support_id = $("#support_id").val();
            var del_physical = $("#del1").val();
            ;
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'supports', 'action' => 'admin_index')); ?>",
                data: "action=delete&support_id=" + support_id + "&del_physical=" + del_physical,
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".error-message .success-message").html("");
                    $("#delete_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalDeleteSupport").modal("hide");
                        $(".error-message .success-message").html("");
                        window.location.reload();
                        //fetch_support_lists();
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
            var form_id = $('#hdf_form_id').val();
            var data = $("#form_support" + form_id + "").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'supports', 'action' => 'admin_index')); ?>",
                data: data + "&action=save",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $("#save_loading").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $("#success_save").html(respond.msg);
                        $("#success_save").removeClass("hide");
                        $("#ModalSaveConfirm").modal("hide");
                        //window.location.reload();
                        fetch_support_lists();
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
        $('body').on('click', '.cancel_edit', function () {
            fetch_support_lists();
        });
        $("body").on("click", ".edit_support", function () {
            var id = $(this).attr('id');
            var form_id = $(this).attr('form_id');
            $(this).val('保存');
            $("#hdf_support_id").val(id);
            $("#hdf_form_id").val(form_id);
            $(this).addClass('save_edit');
            $('#support_plan' + id).removeAttr('readonly');
            $('#title' + id).removeAttr('readonly');
            $('#detail' + id).removeAttr('readonly');
        });
        $("body").on("click", ".save_edit", function () {
            $('#ModalSaveConfirm').modal('show');
        });
        //Fetch for question and answers
        function fetch_question_answer_lists() {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'supports', 'action' => 'admin_fetch_question_answer_lists')); ?>",
                dataType: 'html',
                beforeSend: function () {
                    $("#loading_question_answer_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#fetch_question_answer_list").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_question_answer_list").addClass("hide");
                }
            });
        }
        fetch_question_answer_lists();
        $('body').on('click', '.delete_question_answer', function () {
            var id = $(this).attr('data-id');
            $('#question_answer_id').val(id);
            //do something when the user clicks the box
            $('#del2').val("");
            $('#del2').change(function () {
                var val = "";
                if (this.checked) {
                    $("#del2").val(1);
                } else {
                    $("#del2").val(val);
                }
            });
        });
        $("body").on("click", "#btn_delete_question_answer", function () {
            var question_answer_id = $("#question_answer_id").val();
            var del_physical = $("#del2").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'supports', 'action' => 'admin_index')); ?>",
                data: "action=delete_question_answer&question_answer_id=" + question_answer_id + "&del_physical=" + del_physical,
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".error-message .success-message").html("");
                    $("#delete_loading2").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalDeleteQA").modal("hide");
                        fetch_support_lists();
                        fetch_question_answer_lists();
                    } else {
                        $("#error-msg-delete2").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwErros) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#delete_loading2").addClass("hide");
                }
            });
        });

        $("body").on("click", "#btn_confirm_qa_save", function (e) {
            e.preventDefault();
            var id = $('#hdf_question_answer_form_id').val();
            var data = $("#form_question_answer" + id + "").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'supports', 'action' => 'admin_index')); ?>",
                data: data + "&action=save_question_answer",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $("#save_loading2").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $("#success_save").html(respond.msg);
                        $("#success_save").removeClass("hide");
                        $("#ModalSaveQAConfirm").modal("hide");
                        fetch_question_answer_lists();
                    } else {
                        $("#error_save").html(respond.msg);
                        $("#error_save").removeClass("hide");
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#save_loading2").addClass("hide");
                }
            });
        });
        $('body').on('click', '.cancel_edit', function () {
            fetch_support_lists();
            fetch_question_answer_lists();
        });
        //Function enable field question and answer when click edit
        $("body").on("click", ".edit_question_answer", function () {
            var id = $(this).attr('id');
            $(this).val('保存');
            $("#hdf_question_answer_id").val(id);
            $(this).addClass('save_edit_question_answer'); //Add this class for click confirm modal save
            $('#question' + id).removeAttr('readonly');
            $('#answer' + id).removeAttr('readonly');
        });

        $("body").on("click", ".save_edit_question_answer", function () {
            $('#ModalSaveQAConfirm').modal('show');
        });

    });
</script>
