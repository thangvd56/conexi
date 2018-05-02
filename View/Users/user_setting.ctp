<style>
    .butt{
        display: block;
        margin-top: 15px;
    }
    .back_color {
        background: #86CB34 !important;
        border: none;
        padding: 8px 30px 4px 33px;
        box-shadow: 0 4px #5f9520;
    }
    .back_color2 {
        margin-left: 23px;
        background: #5e5e5e !important;
        border: none;
        padding: 8px 30px 4px 33px;
        box-shadow: 0 4px rgba(51, 51, 51, 0.89);
    }
    .list_button {
        position: relative;
        top: 28px;
        left: 35px;
    }
    .radius {
        border-radius: 15px;
        text-align: center;
        padding: 5px 3px 3px 0px;
        color: #2c2c2c;
    }
    .set_margin {
        margin-top: -5px;
    }
    .dotted-border {
        border: dotted !important;
        height: 65px;
        border-radius: 15px;
        line-height: 2.5;
        font-size: 25.15px;
        opacity: .5;
    }
    .panel {
        border-radius: 15px;
    }
    .modal-content{
        border-radius: 25px;
    }
    .modal-title {
        text-align: center;
        font-size: 25px;
        margin-top: 15px;
    }
    .modal-footer{
        border: 0;
        padding: 0px 0px 25px 0px;
    }
    .modal-body{
        padding:0px 20px 0px 20px;
    }
    .modal-header{
        border:0;
    }
    .background{
        background:#424242;
        box-shadow: 0 7px #2e2e2e;
    }
    .padding_formgroup {
        padding-top: 20px;
    }
    .col-md-3{
        float: right;
    }
</style>
<?php $items = count($user_list); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-8">
            <h1 class="page-header">
<!--        ユーザー設定-->
            パスワード変更
            </h1>
<!--             <div class="col-md-3">
                    <div class="form-group">
                        <?php
//                        echo $this->Html->link('削除情報',
//                            array('controller' => 'customers', 'action' => 'deleted?type=user'),
//                            array('class' => 'btn btn-block btn_color right'));
                        ?>
                    </div>
             </div>-->
            <ol class="breadcrumb">
                <li class="active">
<!--                 パスワード変更 <p class="sub_header">ログインしているIDのパスワードを変更する事が出来ます。</p>-->
                     <p class="sub_header">ログインしているIDのパスワードを変更する事が出来ます。</p>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <button type="button" class="btn btn-block btn_color" data-toggle="modal" data-target="#ModalChangePassword">パスワード変更</button>
        </div>
    </div>
    <br>
<!--    <h1>ユーザー追加</h1>-->
<!--    <div class="row">
        <div class="col-xs-12 col-md-8">
            <div id="loading_user_setting_list" class="text-center hide">
                <?php //echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
            </div>
        </div>
    </div>-->
<!--<div id="user_setting_list"></div>
    <div id="user_setting_list_hide" class="hide"></div>-->
<!--    <div class="row">
        <div class="col-xs-12 col-md-8">
            <label style="width: 10%;margin-top: 5px;" class="pull-left"><?php //echo '全 ' . $items.' 件'; ?></label>
            <div id="paginate" style="width:90%" style="pull-right"></div>
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>
    <div class="row">
        <div class="col-xs-12 col-md-8 text-center">
            <div class="panel dotted-border" id="add_new_user" style="cursor: pointer;">
                <div class="">
                    +新しいユーザーを追加
                </div>
            </div>
        </div>
    </div>-->
</div>
<!-- Modal change password-->
<div id="ModalChangePassword" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        
        <div class="modal-content">
            <?php echo $this->Form->create('User', array('id' => 'change_password_user')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">パスワード変更</h4>
            </div>
            <div class="modal-body">
                <input type="password" name="current_pwd" required="required" id="current_pwd" placeholder="現在のパスワード" class="form-control"/><br>
                <p>新パスワードは6文字以上で入力してください。</p>
                <input type="password" name="new_pwd" required="required" id="new_pwd" placeholder="新しいパスワード" class="form-control"/><br>
                <input type="password" name="confirm_pwd" required="required" id="confirm_pwd" placeholder="新しいパスワード（確認" class="form-control"/>
                <div class="clearfix">&nbsp;</div>
                <div class="error-message" id="error_change_password"></div>
                <div class="success-message" id="success_change_password"></div>
                <div id="change_password_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">閉じる</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button type="submit" class="btn btn-block btn_color" id="btn_create_user">送信</button>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
        
    </div>
</div>
<!--Modal Delete-->
<div id="ModalDelete" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Do you want to delete?</h4>
            </div>
            <?php echo $this->Form->create('User', array('id' => 'delete_user')); ?>
            <div class="modal-body text-center">
                <input type="checkbox" name="del_physical" value="1" id="del1" class="hide"><label for="del1" class="hide">&nbsp;Delete physical.</label> <br>
                <input type="hidden" name="user_id" class="user_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width:100px;">キャンセル</button>
                <?php
                echo $this->Form->submit('Delete', array(
                    'class' => 'btn btn-danger btn_user'));
                ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!--Modal Delete-->
<div id="ModalDeleteConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!--Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="clearfix">&nbsp;</div>
                <label id="label-delete"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
                <div class="clearfix">&nbsp;</div>
            </div>
        </div>
    </div>
</div>
<div id="ModalDeleteUser" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <?php echo $this->Form->create('User', array('id' => 'delete_user')); ?>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="clearfix">&nbsp;</div>
                <label id="label-delete-confirm"></label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del_user" class="hide"><label for="del_user" class="hide">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" name="user_id" class="user_id"/>
                <input type="hidden" name="shop_id" class="shop_id"/>
                <input type="hidden" name="is_notification" class="is_notification"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div class="hide delete_loading"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_delete" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
                <div class="clearfix">&nbsp;</div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<!--Modal Add-->
<div id="ModalAdd" class="modal fade" role="dialog" data-backdrop="static" data-keyborad="false">
    <div class="modal-dialog">
        <?php echo $this->Form->create('User', array('id' => 'create_user')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">新しいログインIDの追加</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" id="txt1" placeholder="氏名" required="required">
                    </div>
                    <div class="form-group" style="display: none">
                      <select id="shop_id" name="shop_id" class="form-control">
                         <?php foreach ($shops as $option): ?>
                            <option value="<?php echo $option['shops']['id']; ?>"><?php echo $option['shops']['shop_name']; ?></option>
                          <?php endforeach;?>  
                      </select>
                    </div>
                    <div class="form-group padding_formgroup">
                        <h5 id="pwd_info"> 新しいパスワードは6文字以上で入力してください。</h5>
                        <input type="email" name="email" class="form-control" id="txt2" placeholder="メールアドレス　例）info@cha-chat.jp" required="required">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" id="password" placeholder="パスワード　例) info1234" required="required">
                    </div>
                    <div id="create_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                    <div class="error-message" id="error-msg-create"></div>
                    <div class="success-message" id="success-msg-create"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">閉じる</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button type="submit" class="btn btn-block btn_color" id="btn_create_user">送信</button>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<!--Modal Edit-->
<div id="ModalEdit" class="modal fade" role="dialog" data-backdrop="static" data-keyborad="false">
    <div class="modal-dialog">
        <?php echo $this->Form->create('User', array('id' => 'edit_user')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">新しいログインIDの追加</h4>
            </div>
            <div class="modal-body">
                <input type="text" name="username" placeholder="氏名" class="form-control username_update" required="required"/><br>
                <p class="pwd_info">新パスワードは6文字以上で入力してください。</p>
                <input type="email" name="email" placeholder="メールアドレス 例）info@cha-chat.jp" class="form-control email_update" required="required"/><br>
                <input type="password" id="password_update" name="password_update" placeholder="パスワード 例）info1234" class="form-control"/><br>
                <div id="edit_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <input type="hidden" name="user_id" class="user_id"/>
                <div class="error-message" id="error-msg-edit"></div>
                <div class="success-message" id="success-msg-edit"></div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">閉じる</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button type="submit" class="btn btn-block btn_color" id="btn_create_user">送信</button>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<?php
echo $this->Html->css('simplePagination');
echo $this->Html->script('jquery.simplePagination');
?>
<script type="text/javascript">
    $(function () {

        $("#paginate").pagination({
            items: "<?php echo $items; ?>",
            itemsOnPage: "<?php echo ITEMS_ON_PAGE; ?>",
            cssStyle: 'light-theme',
            prevText: '前',
            nextText: '次',
            onPageClick: pageClick
        });
        function pageClick() {
            var currentPage = $("#paginate").pagination('getCurrentPage');
            var itemOnPage = "<?php echo ITEMS_ON_PAGE; ?>";
            var start = ((currentPage - 1) * itemOnPage) + 1;
            var start_int = parseInt(start);
            var end = (currentPage) * itemOnPage;
            var end_int = parseInt(end);
            var new_respond = "";
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting_list')) ?>",
                dataType: "html",
                beforeSend: function () {
                    $("#loading_user_setting_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#user_setting_list_hide").html("");
                    $("#user_setting_list_hide").html(respond);
                    $(".user_list").each(function () {
                        var id = $(this).attr("id");
                        id = id.split("user_")[1];
                        var id_int = parseInt(id);
                        if (id_int >= start_int && id_int <= end_int) {
                            new_respond += $("#user_" + id)[0].outerHTML;
                        }
                    });
                    $("#user_setting_list").html("");
                    $("#user_setting_list").html(new_respond);
                },
                error: function (xhr, ajaxOption, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_user_setting_list").addClass("hide");
                }
            });
        }
        $('#ModalDeleteUser').on('hidden.bs.modal', function () {
            $("body").css({"padding-right": "0px"});
        });

        $("#add_new_user").click(function () {
            
            var shop="<?php echo count($shops); ?>";
            if(shop!=0){
              $("#ModalAdd").modal("show");
            }else{
                window.location.replace(URL + 'users/view/app-info');
            }
        });

        $("#btn_confirm_delete").click(function () {
            $("#ModalDeleteConfirm").modal("hide");
            $("#ModalDeleteUser").modal("show");
        });

        function user_setting_list() {
            var new_respond = "";
            var itemOnPage = "<?php echo ITEMS_ON_PAGE; ?>";
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting_list')) ?>",
                dataType: "html",
                beforeSend: function () {
                    $("#loading_user_setting_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#user_setting_list_hide").html("");
                    $("#user_setting_list_hide").html(respond);
                    $(".user_list").each(function () {
                        var id = $(this).attr("id");
                        id = id.split("user_")[1];
                        var id_int = parseInt(id);
                        if (id_int <= itemOnPage) {
                            new_respond += $("#user_" + id)[0].outerHTML;
                        }
                    });
                    $("#user_setting_list").html("");
                    $("#user_setting_list").html(new_respond);
                },
                error: function (xhr, ajaxOption, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_user_setting_list").addClass("hide");
                }
            });
        }
        user_setting_list();

        $("form#create_user").submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            var pwd = $("#password").val();
            if (pwd.length < 6) {
                $("#pwd_info").fadeOut(100);
                $("#pwd_info").fadeIn(100);
                $("#password").addClass("input_danger");
                return false;
            }
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting')); ?>",
                data: data + "&action=save",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".error-message").html("");
                    $(".success-message").html("");
                    $("#create_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $("#success-msg-create").html(respond.msg);
                        $('form#create_user')[0].reset();
                        $("#user_setting_list").html("");
                        $("#paginate").pagination('drawPage', 1);
                        user_setting_list();
                        
                    } else {
                        $("#error-msg-create").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $("#password").removeClass("input_danger");
                    $("#create_loading").addClass("hide");
                }
            });
        });

        $("form#delete_user").submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting')); ?>",
                data: data + "&action=delete",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".delete_loading").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalDeleteUser").modal("hide");
                        window.location.reload();
                    } else {
                        $("#error-msg-delete").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $(".delete_loading").addClass("hide");
                }
            });
        });

        $("form#edit_user").submit(function (e) {
            e.preventDefault();
            var pwd = $("#password_update").val();
            if (pwd.length > 0 && pwd.length < 6) {
                $(".pwd_info").fadeOut(100);
                $(".pwd_info").fadeIn(100);
                $("#password_update").addClass("input_danger");
                return false;
            }
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting')) ?>",
                data: data + "&action=edit",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $("#edit_loading").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#ModalEdit").modal("hide");
                        $("form#edit_user")[0].reset();
                        //user_setting_list();
                        window.location.reload();
                    } else {
                        $("#error-msg-edit").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $("#edit_loading").addClass("hide");
                    $("#password_update").removeClass("input_danger");
                }
            });
        });

        $("form#change_password_user").submit(function (e) {
            e.preventDefault();
            var new_pwd = $("#new_pwd").val();
            var con_pwd = $("#confirm_pwd").val();
            if (new_pwd !== con_pwd) {
                $("#error_change_password").html("Password mismatch");
                $("#new_pwd, #confirm_pwd").addClass("input_danger");
                return false;
            }
            if (new_pwd.length < 6) {
                $("#error_change_password").html("Password at least 6 characters");
                $("#new_pwd, #confirm_pwd").addClass("input_danger");
                return false;
            }
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting')) ?>",
                data: data + "&action=change_password",
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $("#change_password_loading").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#success_change_password").html(respond.msg);
                        $("#change_password_user")[0].reset();
                    } else {
                        $("#error_change_password").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $("#change_password_loading").addClass("hide");
                    $("#new_pwd, #confirm_pwd").removeClass("input_danger");
                }
            });
        });

        $("body").on("click", ".get_user_id", function () {
            var id = $(this).attr("id");
            var shop_id =$(this).attr('data-user-shop');
            var is_notification = $(this).attr('data-shop-notification');
            var name = $(this).attr("data-name");
            $(".user_id").val(id);
            $(".shop_id").val(shop_id);
            $(".is_notification").val(is_notification);
            $(".error-message, .success-message").html("");
            $("#label-delete").html(name + "を削除します。<br>この" + name + "を非表示BOX箱に移 <br>動します。<br>よろしいですか？");
            $("#label-delete-confirm").html(name + "を削除します本当によろしいですか？");
        });
        $("body").on("click", ".get_user_detail", function () {
            var id = $(this).attr("id");
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting')) ?>",
                data: "action=detail&user_id=" + id,
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $("#load_user_data").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $(".username_update").val(respond.data.username);
                        $(".email_update").val(respond.data.email);
                        $("#password_update").val("");
                    } else {
                        $("#error-msg-edit").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, thowError) {
                    console.log("Error: " + xhr.status);
                },
                complete: function () {
                    $("#load_user_data").addClass("hide");
                }
            });

        });
    });
</script>