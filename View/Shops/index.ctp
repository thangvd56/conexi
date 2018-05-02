<style>
    .btn-shop{
        margin-top: -5px;
        width: 100px;
    }
    .bootstrap-timepicker-widget{z-index:1151 !important;}
    .image{
        width: 100%;
        height: 100%;
    }
</style>
<div class="container-fluid well">
    <div class="row">
        <h2>店舗追加</h2>
    </div>
    <div id="fetch_shop_list"></div>
    <div class="pull-right">
        <div class="col-md-12 ">
            <button type="button" class="btn btn-default get_shop_id" data-toggle="modal" data-target="#ModalAddShop" id="btn-add">Add sequence shop</button>
        </div>
    </div>
</div>
<!-- Modal View-->
<div id="ModalView" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="image_block">
                        <img class="image" id="img_name" alt="" src="">
                        <?php
                        echo $this->Html->image('/uploads/', array(
                            'class' => 'image hide',
                            'alt' => '',
                            'id' => 'sample_img'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h2>紹介文が入ります</h2>
                    </div>
                </div>
                <div class="row">
                    <form class="form-horizontal">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_shop_name" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_address" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_time" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_holiday" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_phone" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_fax" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_url" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_email" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_facebook" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="txt_twitter" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Modal Edit-->
<div id="ModalEdit" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">店舗情報を埋めよう！</h4>
            </div>
            <?php
            echo $this->Form->create('Shop', array(
                'id' => 'edit_shop',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'type' => 'file'));
            ?>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2" >写真 :</label>
                            <div class="col-md-10">
                                <?php
                                echo $this->Form->input('image', array(
                                    'class' => 'form-control',
                                    'label' => false,
                                    'id' => 'image',
                                    'type' => 'file',
                                    'required' => false));
                                ?>
                                <input type="hidden" name="shop_id" class="shop_id"/>
                                <input type="hidden" name="action" value="edit"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >紹介文 :</label>
                            <div class="col-md-10">
                                <input type="text" name="introduction" id="introduction_edit" placeholder="100文字以内で入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >店舗名 :</label>
                            <div class="col-md-10">
                                <input type="text" name="shop_name" id="shop_name_edit" placeholder="店舗名入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >店舗名カナ :</label>
                            <div class="col-md-10">
                                <input type="text" name="shop_kana" id="shop_kana_edit" placeholder="店舗名を入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >住所 :</label>
                            <div class="col-md-10">
                                <input type="text" name="address" id="address_edit" placeholder="店舗住所入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >営業時間 :</label>
                            <div class="col-md-5">
                                <input type="text" id="start_time" name="business_hours_start" id="business_hours_start_edit" class="form-control"/>
                            </div>
                            <div class="col-md-5">
                                <input type="text" id="end_time" name="business_hours_end" id="business_hours_end_edit" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >定休日 :</label>
                            <div class="col-md-10">
                                <input type="text" name="holidays" id="holidays_edit" placeholder="定休日を入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >TEL :</label>
                            <div class="col-md-10">
                                <input type="text" name="phone" id="phone_edit" placeholder="電話番号を入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >FAX :</label>
                            <div class="col-md-10">
                                <input type="text" name="fax" id="fax_edit" placeholder="FAXを入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" >ホームページ :</label>
                            <div class="col-md-10">
                                <input type="text" name="url" id="url_edit" placeholder="HP URLを入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">E-mail :</label>
                            <div class="col-md-10">
                                <input type="text" name="email" id="email_edit" placeholder="問い合わせ用アドレスを入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Facebook :</label>
                            <div class="col-md-10">
                                <input type="text" name="facebook" id="facebook_edit" placeholder="facebookページURLを入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Twitter :</label>
                            <div class="col-md-10">
                                <input type="text" name="twitter" id="twitter_edit" placeholder="twitterページURLを入力してください" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">IOS DL :</label>
                            <div class="col-md-10">
                                <input type="text" name="ios_download_link" id="ios_download_link_edit" placeholder="Google play ﾀﾞｳﾝﾛｰﾄﾞURL" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">android DL :</label>
                            <div class="col-md-10">
                                <input type="text" name="android_download_link" id="android_download_link_edit" placeholder="App Store ﾀﾞｳﾝﾛｰﾄﾞURL" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2"></label>
                            <div class="col-md-10">
                                <div class="error-message" id="error-msg-edit"></div>
                                <div class="success-message" id="success-msg-edit"></div>
                                <div id="edit_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Saving...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">編集</button>
                <?php
                echo $this->Form->submit('保存', array(
                    'class' => 'btn btn-primary btn-shop',
                    'id' => 'btn-edit-shop',
                    'data-loading-text' => 'Saving'));
                ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!-- Modal Create Shop-->
<div id="ModalAddShop" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">A sequence store we will add new</h4>
            </div>
            <?php echo $this->Form->create('Shop', array('id' => 'create_shop')); ?>
            <div class="modal-body">
                <input type="text" name="name" placeholder="Enter shop name" class="form-control txt-shop-name"/>
                <div class="error-message" id="error-msg-create"></div>
                <div class="success-message" id="success-msg-create"></div>
                <div id="create_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Creating...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-right: 110px;width:100px;">キャンセル</button>
                <?php
                echo $this->Form->submit('Add', array(
                    'class' => 'btn btn-primary btn-shop',
                    'id' => 'btn-add-shop',
                    'data-loading-text' => 'Saving'));
                ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!-- Modal Delete Shop-->
<div id="ModalDeleteShop" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Do you want to delete shop?</h4>
            </div>
            <?php echo $this->Form->create('Shop', array('id' => 'delete_shop')); ?>
            <div class="modal-body">
                <input type="radio" name="del_physical" value="1" id="del1"><label for="del1">&nbsp;Delete physical.</label> <br>
                <input type="radio" name="del_physical" value="0" id="del0"><label for="del0">&nbsp;Delete logical.</label>
                <input type="hidden" name="shop_id" class="shop_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width:100px;">キャンセル</button>
                <?php
                echo $this->Form->submit('Delete', array(
                    'class' => 'btn btn-danger btn-shop',
                    'id' => 'btn-delete-shop',
                    'data-loading-text' => 'Deleting'));
                ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!-- Modal Published-->
<div id="ModalPublishedShop" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Do you want to published?</h4>
            </div>
            <?php echo $this->Form->create('Shop', array('id' => 'published_shop')); ?>
            <div class="modal-body">
                <input type="radio" name="published" value="1" id="published1"><label for="published1">&nbsp;Publish.</label> <br>
                <input type="radio" name="published" value="0" id="published0"><label for="published0">&nbsp;Private.</label>
                <input type="hidden" name="shop_id" class="shop_id"/>
                <div class="error-message" id="error-msg-publish"></div>
                <div id="publish_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Saving...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width:100px;">キャンセル</button>
                <?php
                echo $this->Form->submit('Save', array(
                    'class' => 'btn btn-primary btn-shop',
                    'id' => 'btn-publish-shop',
                    'data-loading-text' => 'Saving'));
                ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("#edit_loading").hide();
        $("#create_loading").hide();
        $("#delete_loading").hide();
        $("#publish_loading").hide();
        //FETCH SHOP LIST
        function fetch_shop_list() {
            $.ajax({
                url: '<?php echo Router::url(array('controller' => 'shops', 'action' => 'fetch_shop_list')); ?>',
                success: function (respond) {
                    $("#fetch_shop_list").html(respond);
                },
                error: function () {
                    console.log('Error fetch_shop_list');
                }
            });
        }
        fetch_shop_list();
        //CREATE SHOP
        $("#btn-add-shop").click(function () {
            $(this).button('loading');
        });
        $("form#create_shop").on("submit", function (e) {
            e.preventDefault();
            $("#create_loading").show();
            $(".error-message").html("");
            $(".success-message").html("");
            var data = $(this).serialize();
            $.ajax({
                url: "<?php $this->Html->url(array('controller' => 'shops', 'action' => 'index')); ?>",
                data: data + "&action=create",
                dataType: "json",
                success: function (respond) {
                    if (respond.result === 'error') {
                        $("#error-msg-create").html(respond.msg);
                        $("#success-msg-create").html('');
                    } else if (respond.result === 'success') {
                        $("#success-msg-create").html(respond.msg);
                        $("#error-msg-create").html('');
                        $(".txt-shop-name").val('');
                        fetch_shop_list();
                    }
                    $("#btn-add-shop").button('reset');
                    $("#create_loading").hide();
                },
                error: function () {
                    console.log('Error create_shop');
                    $("#btn-add-shop").button('reset');
                    $("#create_loading").hide();
                }
            });
        });
        //DELETE SHOP
        $("#btn-delete-shop").click(function () {
            $(this).button('loading');
        });
        $("form#delete_shop").on("submit", function (e) {
            e.preventDefault();
            $("#delete_loading").show();
            $(".error-message").html("");
            $(".success-message").html("");
            var data = $(this).serialize();
            $.ajax({
                url: "<?php $this->Html->url(array('controller' => 'shops', 'action' => 'index')); ?>",
                data: data + "&action=delete",
                dataType: "json",
                success: function (respond) {
                    if (respond.result === 'error') {
                        $("#error-msg-delete").html(respond.msg);
                    } else if (respond.result === 'success') {
                        $("#ModalDeleteShop").modal('hide');
                        $("#error-msg-delete").html("");
                        fetch_shop_list();
                    }
                    $("#btn-delete-shop").button('reset');
                    $("#delete_loading").hide();
                },
                error: function () {
                    console.log('Error delete-shop');
                    $("#btn-delete-shop").button('reset');
                    $("#delete_loading").hide();
                }
            });
        });
        //PUBLISHED SHOP
        $("#btn-publish-shop").click(function () {
            $(this).button('loading');
        });
        $("form#published_shop").on("submit", function (e) {
            e.preventDefault();
            $("#publish_loading").show();
            $(".error-message").html("");
            $(".success-message").html("");
            var data = $(this).serialize();
            $.ajax({
                url: "<?php $this->Html->url(array('controller' => 'shops', 'action' => 'index')); ?>",
                data: data + "&action=published",
                dataType: "json",
                success: function (respond) {
                    if (respond.result === 'error') {
                        $("#error-msg-publish").html(respond.msg);
                        $("#btn-publish-shop").button('reset');
                    } else if (respond.result === 'success') {
                        $("#error-msg-publish").html("");
                        $("#btn-publish-shop").button('reset');
                        $("#ModalPublishedShop").modal('hide');
                    }
                    $("#publish_loading").hide();
                },
                error: function () {
                    console.log("Error published_shop");
                    $("#btn-publish-shop").button('reset');
                    $("#publish_loading").hide();
                }
            });
        });
        //EDIT SHOP
        $("#btn-edit-shop").click(function () {
            $(this).button('loading');
        });
        $("form#edit_shop").on("submit", function (e) {
            e.preventDefault();
            $("#edit_loading").show();
            $(".error-message").html("");
            $(".success-message").html("");
            var data = new FormData(this);
            $.ajax({
                url: "<?php $this->Html->url(array('controller' => 'shops', 'action' => 'index')); ?>",
                data: data,
                cache: false,
                type: 'post',
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (respond) {
                    $("#btn-edit-shop").button("reset");
                    if (respond.result === "success") {
                        $("#success-msg-edit").html(respond.msg);
                        $("#error-msg-edit").html("");
                    } else if (respond.result === "error") {
                        $("#success-msg-edit").html("");
                        $("#error-msg-edit").html(respond.msg);
                    }
                    $("#edit_loading").hide();
                },
                error: function () {
                    console.log("Error edit_shop");
                    $("#btn-edit-shop").button('reset');
                    $("#edit_loading").hide();
                }
            });
        });
    });
</script>