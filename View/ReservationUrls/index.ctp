<?php echo $this->Form->create('ReservationUrl', array('id' => 'form_reservationurl')); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-7">
            <h1 class="page-header">
                アプリ作成 > WEB予約URL設定
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    WEB予約URL設定 <br/><p class="sub_header">WEB予約用のURLを設定して下さい。設定すると独自の予約システムに飛びます。</p>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 change_margin">
            <div class="col-xs-6 col-md-3"><p class="word">URL設定</p></div>
            <div class="col-xs-6 col-md-9">
                <div class="form-group">
                    <?php
                    echo $this->Form->input('url', array(
                        'type' => 'url',
                        'name' => 'url',
                        'id' => 'url',
                        'value'=>$url,
                        'label' => false,
                        'class' => 'form-control',
                        'placeholder' => 'URLを入力してください'
                    ));
                    ?>
                </div>
            </div>
            <hr class="hr"/>
        </div>
        <div class="col-md-7 change_margin">
            <button id="btn_save" type="button" class="btn btn-block btn_color" >保存</button>
        </div>
    </div> <!-- close row -->
</div>
<?php echo $this->Form->end(); ?>
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
<script type="text/javascript">

    $(function () {
        $("#btn_save").click(function () {
            $("#ModalSaveConfirm").modal("show");
        });
        $("body").on("click", "#btn_confirm_save", function (e) {
            e.preventDefault();
            var data = $("#form_reservationurl").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'ReservationUrls', 'action' => 'index')); ?>",
                data: data + "&action=save",
                type: "get",
                beforeSend: function () {
                    $(".loading-item").addClass("hide");
                    $("#save_loading").removeClass("hide");
                },
                success: function () {
                    $("#loading_save").addClass("hide");
                    $("#ModalSaveConfirm").modal("hide");
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("error save_data");
                },
                complete: function () {
                    $("#save_loading").addClass("hide");
                    window.location.replace(URL + 'users/view/app-reservation-url');
                }
            });
        });
    });
</script>
