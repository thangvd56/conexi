<?php echo $this->Form->create('SnsShare', array('id' => 'form_sns')) ?>
<?php $this->request->data = $sns; ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-7">
            <h1 class="page-header">
                アプリ作成 > SNSシェア
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    SNSシェア情報編集 <br/><p class="sub_header">SNSシェア情報を登録しよう。</p>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 change_margin">
            <div class="col-xs-6 col-md-3"><p class="word">タイトル</p></div>
            <div class="col-xs-6 col-md-9">
                <div class="form-group">
                    <?php
                    echo $this->Form->input('title', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        'name'=>'title',
                        'id'=>'title',
                        'required' => true,
                        'label' => false,
                        'placeholder' => '１０文字以内で名前を記載。',
                    ));
                    ?>
                </div>
            </div>
            <hr class="hr"/>
        </div>
        <div class="col-md-7 change_margin">
            <div class="col-xs-6 col-md-3"><p class="word">本文</p></div>
            <div class="col-xs-6 col-md-9">
                <div class="form-group">
                    <?php
                    echo $this->Form->input('description', array(
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'name'=>'description',
                        'id'=>'description',
                        'label' => false,
                        'placeholder' => '※2００字以内で紹介文を記載。',
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
            <hr class="hr"/>
        </div>
        <div class="col-md-7 change_margin">
            <div class="col-xs-6 col-md-3"><p class="">ホームページURL</p></div>
            <div class="col-xs-6 col-md-9">
                <div class="form-group">                  
                    <?php
                    echo $this->Form->input('home_page_url', array(
                        'type' => 'url',
                        'class' => 'form-control',
                        'name'=>'home_page_url',
                        'id'=>'home_page_url',
                        'label' => false,
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
            <hr class="hr"/>
        </div>
        <div class="col-md-7 change_margin">
            <div class="col-xs-6 col-md-3"><p class="">iOS <br/> ダウンロードURL</p></div>
            <div class="col-xs-6 col-md-9">
                <div class="form-group">
                    <?php
                    echo $this->Form->input('ios_download_url', array(
                        'type' => 'url',
                        'class' => 'form-control',
                        'name'=>'ios_download_url',
                        'id'=>'ios_download_url',
                        'label' => false,
                        'readonly' => true,
                        'required' => true
                    ));
                    ?>
                </div>
            </div>
            <hr class="hr"/>
        </div>
        <div class="col-md-7 change_margin">
            <div class="col-xs-6 col-md-3"><p class="">Android<br/> ダウンロードURL</p></div>
            <div class="col-xs-6 col-md-9">
                <div class="form-group">
                    <?php
                    echo $this->Form->input('android_download_url', array(
                        'type' => 'url',
                        'class' => 'form-control',
                        'name'=>'android_download_url',
                        'id'=>'android_download_url',
                        'label' => false,
                        'readonly' => true,
                        'required' => true
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
<?php
echo $this->Form->end();
?>
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
            var data = $("#form_sns").serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'SnsShares', 'action' => 'index')); ?>",
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
                    window.location.replace(URL + 'users/view/app-sns');
                }
            });
        });
    });
</script>