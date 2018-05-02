<?php
echo $this->Html->css('newsSetting');
echo $this->Form->create('News', array(
    'id' => 'last_visit',
    'class' => 'form-horizontal',
    'role' => 'form'
));
?>
<style>
    .dotted-border:hover{
        cursor: pointer;
    }
    .deliver{
        color:red;
    }
</style>
<!-- Page Heading -->
<div class="row">
    <div class="col-md-8">
        <h1 class="page-header">
            通知設定 > 最終来店日通知一覧
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                定期通知／通知日追加 <p class="sub_header">こちらの通知は、最終来店日から数日経ったお客様に通知を自動で送る 事ができます。開封率の高い18:00に通知が届きます。 </p>
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-8  contain">
        <?php
        if ($news) {
            foreach ($news as $key => $value):
                $is_disabled = $value['News']['is_disabled'];
                if ($is_disabled == "0" || $is_disabled == NULL) {
                    $is_checked = '';
                } else {
                    $is_checked = 'checked';
                }
                ?>
                <div id="Removecontent<?php echo $value['News']['id']; ?>" class="panel modify_height modify_height">
                    <div class="col-xs-9 col-md-9 top">
                        <div class="col-xs-4 col-md-4 add_border">
                            <h3>日付</h3>
                        </div>
                        <div class="col-xs-4 col-md-8">
                            <h3 id="delivery_date<?php echo $value['News']['id']; ?>"><?php echo $value['News']['last_visit_notice_value'] ?> 日後</h3>
                        </div>
                    </div>
                    <div class="col-xs-9 col-md-9 bottom">
                        <div class="col-xs-4 col-md-4 add_border">
                            <h3>タイトル</h3>
                        </div>
                        <div class="col-xs-4 col-md-8">
                            <h3><?php echo $value['News']['title'] ?></h3>
                        </div>
                    </div>
                    <div class="list_button">
                        <input type="checkbox" data-delivery="<?php echo $value['News']['last_visit_notice_value']; ?>" class="last_notice last_notice<?php echo $value['News']['id']; ?>" <?php echo $is_checked; ?> data-toggle="toggle" id="<?php echo $value['News']['id']; ?>" data-onstyle="warning" data-on="<div class='toggle_on'></div> 公開" data-off="<div class='toggle_off'></div> 非公開">
                        <br/>
                        <?php
                        echo $this->Html->link('編集', 'last_visit_notification_edit/' . $value['News']['id'] . '', array('class' => 'btn btn-success back_color', 'style' => 'margin-top:10px'));
                        ?>
                        <button type="button" data-id="<?php echo $value['News']['id']; ?>" class="btn btn-success back_color2 butt" data-toggle="modal" data-target="#ModalDeleteNotificationConfirm" >削除</button>
                    </div>
                </div>
                <?php
            endforeach;
        }
        ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<div class="col-xs-12 col-md-8 text-center">
    <div class="panel dotted-border">
        <div class="" id="bth_add">
            +通知日を追加する
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<div id="ModalLastNotificationConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
<!--        Modal content-->
        <div class="modal-content">
            <?php echo $this->Form->create('News'); ?>
            <div class="modal-body text-center">
                <input type="hidden" id="hdf_last_visit_notice_id">
                <input type="hidden" id="hdf_is_disabled">
                <label id="label_notification_notice"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_last_notice" value="キャンセル" class="btn btn-success color back but_design2" style="width: 100px;">
                    <input type="button" id="btn_confirm_last_notice_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<div id="ModalDeleteNotificationConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--        Modal content-->
        <div class="modal-content">
            <?php echo $this->Form->create('News'); ?>
            <div class="modal-body text-center">
<!--                <input type="hidden" id="hdf_last_visit_notice_id">-->
                <input type="hidden" id="hdf_del_notice_id">
                <input type="hidden" id="hdf_is_disabled">
                <label id="label_notification_notice">本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" id="del1"><label for="del1">&nbsp;物理的に削除する。</label> <br>
                <div class="hide save_loading"><?php echo $this->Html->image('loading.gif'); ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_last_notice" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_delete_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!--Modal Delete-->
<!--<div id="ModalDeleteNotificationConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        Modal content
        <div class="modal-content">
<?php //echo $this->Form->create('News'); ?>
            <div class="modal-body text-center">
                <input type="hidden" id="hdf_del_notice_id">
                <label id="label_notification_notice"> 本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div class="hide save_loading"><?php //echo $this->Html->image('loading.gif');   ?></div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_delete_last_notice" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
<?php //echo $this->Form->end(); ?>
        </div>
    </div>
</div>-->

<script type="text/javascript">
    $(function () {
        //Function show modal delivery date (on/off)
        $("form#last_visit").on("change", ".last_notice", function () {
            var id = $(this).attr('id');
            var delivery_date = $(this).attr('data-delivery');
            if (delivery_date =="0" || delivery_date == null || delivery_date =="") {
                $("#delivery_date" + id).addClass('deliver');
                var toggle = 'off';
                $(".last_notice" + id).bootstrapToggle(toggle);
            } else {
                var status = $(this).prop('checked');
                $("#label_notification_notice").html(" 本当によろしいですか？");
                $("#hdf_last_visit_notice_id").val(id);
                $("#hdf_is_disabled").val(status);
                $("#ModalLastNotificationConfirm").modal("show");
            }
        });
        //Function Cancel delivery date (on/off)
        $("#btn_cancel_last_notice").click(function () {
            var id = $("#hdf_last_visit_notice_id").val();
            var status = $("#hdf_is_disabled").val();
            var toggle;
            status === 'true' ? toggle = 'off' : toggle = 'on';
            $(".last_notice" + id).bootstrapToggle(toggle);
            $("#ModalLastNotificationConfirm").modal("hide");
        });
        //Function update last visit notification (on/off)
        $("body").on("click", "#btn_confirm_last_notice_save", function (e) {
            e.preventDefault();
            var $news_id = $("#hdf_last_visit_notice_id").val();
            var $is_disabled = $("#hdf_is_disabled").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'last_visit_notification')); ?>",
                data: "&action=save&news_id=" + $news_id + "&is_disabled=" + $is_disabled,
                type: "get",
                beforeSend: function () {
                    $(".save_loading").removeClass("hide");
                },
                success: function () {
                    $(".save_loading").addClass("hide");
                    $("#ModalLastNotificationConfirm").modal("hide");
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("error save_data");
                },
                complete: function () {
                    $(".save_loading").addClass("hide");
                    window.location.reload();
                }
            });
        });
        $("#btn_confirm_delete").click(function () {
            $("#ModalDeleteNotificationConfirm").modal("show");
        });
        $('form#last_visit').on('click', '.back_color2', function () {
            var id = $(this).attr('data-id');
            $("#hdf_del_notice_id").val(id);
        });
        $('#del1').change(function () {//do something when the user clicks the box
            var val = "";
            if (this.checked) {
                $("#del1").val(1);
            } else {
                $("#del1").val(val);
            }
        });
        //Function delete last visit notification
        $("body").on("click", "#btn_confirm_delete_save", function (e) {
            e.preventDefault();
            var $news_id = $("#hdf_del_notice_id").val();
            var $is_del = $("#del1").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'last_visit_notification')); ?>",
                data: "&action=delete&id=" + $news_id + "&del_physical=" + $is_del,
                type: "get",
                beforeSend: function () {
                    $(".save_loading").removeClass("hide");
                },
                success: function () {
                    $(".save_loading").addClass("hide");
                    $("#ModalDeleteNotificationConfirm").modal("hide");
                    $("#Removecontent" + $news_id).remove();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("error save_data");
                },
                complete: function () {
                    $(".save_loading").addClass("hide");
                }
            });
        });

        $("#bth_add").click(function () {
            window.location.replace(URL + "users/view/last_visit_notification_create");
        });
    });

</script>