<?php
    echo $this->Html->css('visitRecord_Delete');
?>
<style type="text/css">
    .bg-color{
        background: #86cb34;
        color: #fff;
    }
</style>
    <!-- Page Heading -->
    <div>
        <div>
            <h1 class="page-header">
                完全削除情報
            </h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <?php
                         $getType = $this->request->query('type');
                         if(empty($getType)){
                           $getType ="user";
                         }
                         $type = array(
                            'user' => 'ユーザデータ',
                            'reservation'=> '予約データ',
                            'photo_send' => '写真送信データ',
                            'menu_categories' =>'メニューカテゴリデータ',
                            'notification' =>'通知データ',
                            'staff' =>'スタッフデータ',
                         );
                         echo $this->Form->input('type',
                                array(
                                'id' => 'type',
                                'type' => 'select',
                                'class' => 'form-control',
                                'options' => $type,
                                'label' => false,
                                 'value'=>$getType
                            ));
                        ?>
                    </div>
                </div>
                <div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php
                            $year_select           = array();
                            for ($i = 2016; $i <= date('Y'); $i++) {
                                $year_select[$i] = $i;
                            }
                            echo $this->Form->input('year',
                                array(
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
                            $months = array();
                            for ($i = 0; $i <4 ; $i++) {
                                $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
                                $months[date('m', $timestamp)] = date('m', $timestamp).'月';
                            }
                            echo $this->Form->input('month',
                                array(
                                'id' => 'select_month',
                                'type' => 'select',
                                'class' => 'form-control',
                                'options' => $months,
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
        <div class="row">
        <div id="record_loading" class="content2 hide">
            <div  class="text-center">
                <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
            </div>
        </div>
        <div id="result_search" ></div>
    </div>
<!--Modal Revert-->
<div id="ModalRevertConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-delete">本当によろしいですか？</label>
                <input type="hidden" name="revert_id" class="revert_id">
                <label id="label-revert"></label>
                <div id="revert_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_revert" value="元に戻す" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ModalDeleteConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
              <label id="label-delete">本当によろしいですか？<br/>&nbsp;完全に削除する</label>
                <input type="hidden" name="delete_id" class="delete_id">
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ModalDeleteAllConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-delete">
                    本当によろしいですか？<br/>&nbsp;完全に削除する
                </label>
                <div name="count" class="count_checked" style="color:red"></div>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_all_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_delete_all" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="get_check" id="get_check"/>
<input type="hidden" name="revert_type" id="revert_type"/>
<input type="hidden" name="delete_type" id="delete_type"/>
<input type="hidden" name="user_shop_id" class="user_shop_id"/>
<input type="hidden" name="shop_id" class="shop_id"/>
<script type="text/javascript">
    $(function () {

        function fetch_delete_list() {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'customers', 'action' => 'fetch_deleted')) ?>",
                type: "get",
                data: "action=default&type=user" ,
                beforeSend: function () {
                    $("#record_loading").removeClass("hide");
                },
                success: function (respond) {
                    $("#result_search").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#record_loading").addClass("hide");
                }
            });
        }
        //Function select checkbox and uncheck
        $('body').on('change', '#checkList', function () {
            $('input:checkbox').prop('checked', $(this).prop('checked'));
        });
        var year = $("#select_year").val();
        var month = $("#select_month").val();
        var type = $("#type").val();
        if(type =='user'){
            fetch_delete_list();
        }else{
            fetch_delete_list_by_change( year, month,type);
        }
        //Fetch delete list by date
        $('#select_year, #select_month,#type').on('change', function () {
             var year = $("#select_year").val();
             var month = $("#select_month").val();
             var type = $("#type").val();
            fetch_delete_list_by_change(year, month,type);
        });
         function fetch_delete_list_by_change(year, month,type) {
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'customers', 'action' => 'fetch_deleted')) ?>",
                type: "get",
                data: "action=onchange&year=" + year + "&month=" + month + "&type=" + type,
                beforeSend: function () {
                    $("#record_loading").removeClass("hide");
                },
                success: function (respond) {
                    $("#result_search").html(respond);
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function(){
                     $("#record_loading").addClass("hide");
                }
            });
        }
        $('body').on('click', '.get_revert', function () {
            var id = $(this).attr('data-id');
            var delete_type = $(this).attr('target-type');
            $('.revert_id').val(id);
            $("#revert_type").val(delete_type);
            if(delete_type=='user'){
                var user_shop_id =$(this).attr('data-user-shop');
                var shop_id =$(this).attr('data-shop-id');
                $('.user_shop_id').val(user_shop_id);
                $('.shop_id').val(shop_id);
            }
        });
        //Function Revert
        $('body').on('click', '#btn_confirm_revert', function () {
         var revert_id=$('.revert_id').val();
         var revert_type=$('#revert_type').val();
         var user_shop_id =$('.user_shop_id').val();
          var shop_id =$('.shop_id').val();
         $.ajax({
             url: "<?php echo Router::url(array('controller' => 'customers', 'action' => 'fetch_deleted')); ?>",
             data:"&action=revert&revert_id=" + revert_id + "&revert_type=" + revert_type + "&user_shop_id=" + user_shop_id + "&shop_id=" + shop_id,
             type: "get",
             beforeSend: function () {
                 $("#revert_loading").removeClass("hide");
             },
             success: function () {
                $("#ModalRevertConfirm").modal("hide");
                $("#"+ revert_type + "_"+ revert_id).remove();
             },
             error: function (xhr, ajaxOptions, throwError) {
                 console.log("Error:" + xhr.status);
             },
             complete: function () {
                 $("#revert_id").val("");
                 $("#revert_type").val("");
                 $("#revert_loading").addClass("hide");
             }
         });
        });
        //Function get id for delete
        $('body').on('click', '.get_delete', function () {
            var id = $(this).attr("data-id");
            var delete_type = $(this).attr("target-type");
            $(".delete_id").val(id);
            $("#delete_type").val(delete_type);
        });
        $("body").on("click", "#btn_confirm_delete", function () {
         var delete_id=$(".delete_id").val();
         var delete_type=$("#delete_type").val();
         $.ajax({
             url: "<?php echo Router::url(array('controller' => 'customers', 'action' => 'fetch_deleted')); ?>",
             data:"&action=delete&delete_id=" + delete_id + "&delete_type=" + delete_type,
             type: "get",
             beforeSend: function () {
                 $("#delete_loading").removeClass("hide");
             },
             success: function () {
                $("#ModalDeleteConfirm").modal("hide");
                $("#"+ delete_type + "_"+ delete_id).remove();
             },
             error: function (xhr, ajaxOptions, throwError) {
                 console.log("Error:" + xhr.status);
             },
             complete: function () {
                 $("#delete_id").val("");
                 $("#delete_type").val("");
                 $("#delete_loading").addClass("hide");
             }
         });
        });
        //Function  multi delete
        $('body').on('click', '.get_delete_all', function () {
            var type = $(this).attr('target-type');
            $("#delete_type").val(type);
            var get_check = [];
            var count_checked =0;
            $.each($('.'+ type +':checked'), function () {
                get_check.push($(this).val());
                count_checked++;
            });
            $('#get_check').val(get_check);
            if($('#get_check').val() =="") {
               $('.count_checked').html('( '+ count_checked +' ) ');
               $('#btn_confirm_delete_all').addClass('disabled');
            }else{
               $('.count_checked').html('( '+ count_checked +' )');
               $('#btn_confirm_delete_all').removeClass('disabled');
            }
        });
        //Functin multi delete
        $('body').on('click', '#btn_confirm_delete_all', function () {
         var delete_id=$("#get_check").val();
         var delete_type=$("#delete_type").val();
         $.ajax({
             url: "<?php echo Router::url(array('controller' => 'customers', 'action' => 'fetch_deleted')); ?>",
             data:"&action=delete&delete_id=" + delete_id + "&delete_type=" + delete_type,
             type: "get",
             beforeSend: function () {
                 $("#delete_all_loading").removeClass("hide");
             },
             success: function () {
                $("#ModalDeleteAllConfirm").modal("hide");
                var arr_user = delete_id.split(",");
                for (var i=0;i<arr_user.length;i++){
                     $("#"+ delete_type + "_"+ arr_user[i]).remove();
                }
             },
             error: function (xhr, ajaxOptions, throwError) {
                 console.log("Error:" + xhr.status);
             },
             complete: function () {
                 $("#get_check").val("");
                 $("#delete_type").val("");
                 $("#delete_all_loading").addClass("hide");
             }
         });
        });
    });

</script>