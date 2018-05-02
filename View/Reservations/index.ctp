<?php
echo $this->Html->css('visitRecord_Delete');
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <h1 class="page-header">
                来店履歴
            </h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div id="result" class="form-group">
                            <h3>削除データ情報 <?php echo $count_result; ?> 件</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php
                            $year_select = array();
                            for ($i = 2016; $i <= date('Y'); $i++) {
                                $year_select[$i] = $i;
                            }
                            echo $this->Form->input('year', array(
                                'id' => 'select_year',
                                'type' => 'select',
                                'class' => 'form-control',
                                'options' => $year_select,
                                'default' => date('Y'),
                                'label' => false
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php
                            $month_select = array();
                            for ($j = date('m'); $j <= date('m') + 3; $j++) {
                                $month_select[$j] = $j . '月';
                            }
                            echo $this->Form->input('month', array(
                                'id' => 'select_month',
                                'type' => 'select',
                                'class' => 'form-control',
                                'options' => $month_select,
                                'default' => date('m'),
                                'label' => false
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div id="result_search_temp" class="content2 hide">
            <div  class="text-center">
                <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
            </div>
        </div>
        <div id="result_search" class="hide"></div>
    </div>
</div>

<!--Modal Delete-->
<div id="ModalDeleteConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('reservation', array('id' => 'delete_reservation')); ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-delete">本当によろしいですか？<br/>&nbsp;物理的に削除する。</label>
                <input type="hidden" name="get_delete_id" id="get_delete_id">
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif');        ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_delete_reservation" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<!--Modal Revert-->
<div id="ModalRevertConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('reservation', array('id' => 'revert_reservation')); ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-delete">本当によろしいですか？<br/>&nbsp;元に戻す</label>
                <input type="hidden" name="get_revert_id" id="get_revert_id">
                <div class="error-message" id="error-msg-delete"></div>
                <div id="revert_loading" class="hide"><?php echo $this->Html->image('loading.gif');        ?>&nbsp;元に戻す</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_revert_reservation" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<script>
    $(function () {
        function fetch_result_search() {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'reservations', 'action' => 'result_search')) ?>",
                data: 'action=index',
                dataType: 'html',
                type: 'get',
                beforeSend: function () {
                    $('#result_search').addClass('hide');
                    $('#result_search_temp').removeClass('hide');
                },
                success: function (respond) {
                    $('#result_search').html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.statusText);
                },
                complete: function () {
                    $('#result_search').removeClass('hide');
                    $('#result_search_temp').addClass('hide');
                }
            });
        }

        fetch_result_search();

        function search_reservation() {
            var year = $('#select_year').val();
            var month = $('#select_month').val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'reservations', 'action' => 'result_search')) ?>",
                data: 'action=search&year=' + year + '&month=' + month,
                type: 'get',
                dataType: 'html',
                beforeSend: function () {
                    $("#result_search").addClass("hide");
                    $("#result_search_temp").removeClass("hide");
                },
                success: function (respond) {
                    $("#result_search").html(respond);
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#result_search").removeClass("hide");
                    $("#result_search_temp").addClass("hide");
                }
            });
        }
        $("#select_year, #select_month").on("change", function () {
            search_reservation();
        });

        $("body").on("click", ".get_delete", function () {
            var id = $(this).attr("data-id");
            $("#get_delete_id").val(id);
        });

        $("body").on("click", ".get_revert", function () {
            var id = $(this).attr("data-id");
            $("#get_revert_id").val(id);
        });

        $("body").on("click", "#btn_delete_reservation", function (e) {
            e.preventDefault();
            var data = $("#delete_reservation").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'reservations', 'action' => 'index')); ?>",
                data: data + "&action=delete",
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $("#delete_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalDeleteConfirm").modal("hide");
                        fetch_result_search();
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#get_delete_id").val("");
                    $("#delete_loading").addClass("hide");
                }
            });
        });

        $("body").on("click", "#btn_revert_reservation", function (e) {
            e.preventDefault();
            var data = $("#revert_reservation").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'reservations', 'action' => 'index')); ?>",
                data: data + "&action=revert",
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $("#revert_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalRevertConfirm").modal("hide");
                        fetch_result_search();
                        
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#get_revert_id").val("");
                    $("#revert_loading").addClass("hide");
                    fetch_result_search();
                }
            });
        });
    });

</script>