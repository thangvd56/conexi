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
/*    .background{
        background:#424242;
        box-shadow: 0 7px #2e2e2e;
    }*/
    .padding_formgroup {
        padding-top: 20px;
    }
    .col-md-3{
        float: right;
    }
</style>
<?php  $items = count($user_list);?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <h1 class="page-header">
            ユーザー設定
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                ログインしているIDのパスワードを変更する事が出来ます。
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-block btn_color" data-toggle="modal" data-target="#ModalChangePassword">パスワード変更</button>
        </div>
    </div>
    <br>
        <h1>ユーザー追加</h1>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div id="loading_user_shop_list" class="text-center hide">
                <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div id="user_shop_list">
                
            </div>
        </div>
    </div>
    
    <div id="user_shop_list_hide" class="hide"></div>
    <div class="row row-space">
        <div class="col-md-12 col-sm-12 col-lg-8">
            <label style="width: 10%;margin-top: 5px;" class="pull-left"><?php echo '全 ' . $items.' 件'; ?></label>
            <div id="paginate" style="width:90%" style="pull-right"></div>
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 text-center">
            <div class="panel dotted-border" id="add_new_user" style="cursor: pointer;">
                <div class="">
                    +新しいユーザーを追加
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal change password-->
<div id="ModalChangePassword" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <?php echo $this->Form->create('User', array('id' => 'change_password_user')); ?>
        <div class="modal-content">
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
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button type="submit" class="btn btn-success btn-block btn_color" id="btn_create_user">送信</button>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
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
                <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                </div>
                <div class="col-xs-6 col-md-6">
                     <input type="submit" id="btn_confirm_delete" class="btn btn-success btn-block btn_color" value="はい">
                </div>
            </div>
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
        <?php echo $this->Form->create('User', array('id' => 'create_user', 'type' => 'file')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">新しいログインIDの追加</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="form-group">
                        <?php echo $this->Form->input('role', array(
                            'options' => unserialize(USER_ROLE),
                            'class' => 'form-control',
                            'label' => false
                        )); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->input('username', array('class' => 'form-control', 'placeholder' => '氏名', 'required' => 'required', 'label' => false)); ?>
                    </div>
                    <div class="headquater-role">
                        <div class="form-group">
                            <?php echo $this->Form->input('company_name', array('class' => 'form-control', 'placeholder' => '会社名', 'label' => false)); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('contact', array('class' => 'form-control', 'placeholder' => 'TEL', 'label' => false)); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $this->Form->input('address', array('class' => 'form-control', 'placeholder' => '住所', 'label' => false)); ?>
                        </div>
                    </div>
                    <div class="form-group" style="display: none">
                      <select id="shop_id" name="shop_id" class="form-control">
                        <?php
                        if (isset($shops)) :
                             foreach ($shops as $option): ?>
                                <option value="<?php echo $option['shops']['id']; ?>"><?php echo $option['shops']['shop_name']; ?></option>
                        <?php endforeach;
                            endif; ?>
                      </select>
                    </div>
                    <div class="form-group padding_formgroup">
                        <h5 id="pwd_info"> 新しいパスワードは6文字以上で入力してください。</h5>
                        <?php echo $this->Form->email('email', array('class' => 'form-control', 'placeholder' => 'メールアドレス　例）info@cha-chat.jp', 'required' => 'required', 'label' => false)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->password('password', array('class' => 'form-control', 'placeholder' => 'パスワード　例) info1234', 'required' => 'required', 'label' => false)); ?>
                    </div>
                    <div class="form-group">
                        <h5>モバイル用の許可キー</h5>
                        <?php echo $this->Form->input('android_key', array('class' => 'form-control', 'placeholder' => 'android key', 'label' => false)); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $this->Form->file('ios_ck_file', array('class' => 'form-control')); ?>
                    </div>
                    <div id="create_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                    <div class="error-message" id="error-msg-create"></div>
                    <div class="success-message" id="success-msg-create"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <?php echo $this->Form->button('キャンセル', array('class' => 'btn btn-block btn_color background', 'type' => 'button', 'data-dismiss' => 'modal')); ?>
                </div>
                <div class="col-xs-6 col-md-6">
                    <?php echo $this->Form->button('送信', array('class' => 'btn btn-success btn-block btn_color', 'id' => 'btn_create_user')); ?>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<!--Modal Edit-->
<!--<div id="ModalEdit" class="modal fade" role="dialog" data-backdrop="static" data-keyborad="false">
    <div class="modal-dialog">
        <?php echo $this->Form->create('User', array('id' => 'edit_user', 'type' => 'file')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">新しいログインIDの追加</h4>
            </div>
            <div class="modal-body">
                <input id="user-role" type="hidden" name="role" placeholder="role" class="form-control"/><br>
                <input type="text" name="username" placeholder="氏名" class="form-control username_update" required="required"/><br>
                <p class="pwd_info">新パスワードは6文字以上で入力してください。</p>
                <input type="email" name="email" placeholder="メールアドレス 例）info@cha-chat.jp" class="form-control email_update" required="required"/><br>
                <input type="password" id="password_update" name="password_update" placeholder="パスワード 例）info1234" class="form-control"/><br>
                <div id="edit_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <input type="hidden" name="user_id" class="user_id"/>
                <div class="error-message" id="error-msg-edit"></div>
                <div class="success-message" id="success-msg-edit"></div>

                <div class="form-group">
                    <h5>モバイル用の許可キー</h5>
                    <input type="text" name="android_key" class="form-control" id="edit_android_key" placeholder="android key">
                </div>
                <div class="form-group">
                    <?php echo $this->Form->file('ios_ck_file', array('class' => 'form-control', 'id' => 'edit_ios_ck_file')); ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button type="submit" class="btn btn-success btn-block btn_color" id="btn_create_user">送信</button>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>-->

<div id="ModalEdit" class="modal fade" role="dialog" data-backdrop="static" data-keyborad="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">新しいログインIDの追加</h4>
            </div>
            <div class="modal-body">
                <div id="edit-user-section"></div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-success btn-block btn_color" id="btn-update-user">送信</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->css('simplePagination');
echo $this->Html->script('jquery.form.min');
echo $this->Html->script('jquery.simplePagination');

?>
<script type="text/javascript">
    $(function () {
        $('body').on('click', '.edit-user', function () {
            var id = $(this).attr('data-id');
            $('#loading_user_shop_list').addClass('hide');
            $.ajax({
                url: '<?php echo Router::url(array(
                    'controller' => 'users',
                    'action' =>'admin_get_edit'
                    )) ?>',
                type: 'GET',
                dataType: 'HTML',
                data: {id: id},
                beforeSend: function () {
                    $('#loading_user_shop_list').removeClass('hide');
                },
                success: function (respond) {
                    $('#loading_user_shop_list').addClass('hide');
                    if (respond) {
                        $('#edit-user-section').html(respond);
                    }
                    $('#ModalEdit').modal('show');
                },
                error: function (xhr, ajaxOption, throwError) {
                    $('#loading_user_shop_list').addClass('hide');
                    if (errorThrown === 'Forbidden') {
                        if (confirm('Session timeout. Please login again.')) {
                            location.reload();
                        }
                    }
                }
            });
        });

        $('body').on('submit', '#form-edit-user', function(e) {
             e.preventDefault();
            var data = new FormData(this);
            $('.help-errors').addClass('hide').empty();
            $.ajax({
                url: '<?php echo Router::url(array(
                    'controller' => 'users',
                    'action' =>'admin_update_user'
                    )) ?>',
                type: 'POST',
                dataType: 'JSON',
                contentType: false,
                processData: false,
                data: data,
                cache: false,
                beforeSend: function () {
                    $('#edit_loading').removeClass('hide');
                },
                success: function (respond) {
                    $('#edit_loading').addClass('hide');
                    if (respond.status === 'OK') {
                        alert('User update success');
                        location.reload();
                    } else if (respond.status === 'ERROR') {
                        $.each(respond.message, function(key, value) {
                            if (key == 'ios_ck_file') {
                                $('.error_' + key).removeClass('hide').text(value);
                            } else {
                                $('.error_' + key).removeClass('hide').text(value[0]);
                            }
                        });
                    }
                },
                error: function (xhr, ajaxOption, throwError) {
                    $('#edit_loading').addClass('hide');
                    if (errorThrown === 'Forbidden') {
                        if (confirm('Session timeout. Please login again.')) {
                            location.reload();
                        }
                    }
                }
            });

        });

        $('body').on('click', '#btn-update-user', function () {
            $('#form-edit-user').submit();
        });

        $('body').on('change', '#ShopFile', function () {
            $('#form-edit-user').submit();
        });


        $('#paginate').pagination({
            items: "<?php echo $items; ?>",
            itemsOnPage: "<?php echo PAGE_LIMIT; ?>",
            cssStyle: 'light-theme',
            prevText: '前',
            nextText: '次',
            onPageClick: pageClick
        });

        function pageClick() {
            var currentPage = $("#paginate").pagination('getCurrentPage');
            var itemOnPage = "<?php echo PAGE_LIMIT; ?>";
            var start = ((currentPage - 1) * itemOnPage) + 1;
            var start_int = parseInt(start);
            var end = (currentPage) * itemOnPage;
            var end_int = parseInt(end);
            var new_respond = "";
            $.ajax({
                url: "<?php echo Router::url(array(
                    'controller' => 'users',
                    'action' =>'admin_user_shop_list'
                    )) ?>",
                dataType: "html",
                data: {'page': currentPage},
                beforeSend: function () {
                    $("#loading_user_shop_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#user_shop_list").html("");
                    $("#user_shop_list").html(respond);
                },
                error: function (xhr, ajaxOption, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_user_shop_list").addClass("hide");
                }
            });
        }
        $('#ModalDeleteUser').on('hidden.bs.modal', function () {
            $("body").css({"padding-right": "0px"});
        });
        $('#add_new_user').click(function () {
            $("#ModalAdd").modal("show");
        });
        $('#btn_confirm_delete').click(function () {
            $('#ModalDeleteConfirm').modal('hide');
            $('#ModalDeleteUser').modal('show');
        });

        function admin_user_shop_list() {
            var itemOnPage = "<?php echo ITEMS_ON_PAGE; ?>";
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'admin_user_shop_list')) ?>",
                dataType: "html",
                beforeSend: function () {
                    $("#loading_user_shop_list").removeClass("hide");
                },
                success: function (respond) {
                    $("#user_shop_list").html("");
                    $("#user_shop_list").html(respond);
                },
                error: function (xhr, ajaxOption, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#loading_user_shop_list").addClass("hide");
                }
            });
        }
        admin_user_shop_list();

        var _ck_;
        var file_ck_data;
        $('input[name=ios_ck_file]').on('change', function(event){
            _ck_ = event.target.files;
            file_ck_data = $(this).prop('files')[0];
        });

        $('form#create_user').submit(function (e) {
            e.preventDefault();

            var pwd = $("#UserPassword").val();
            if (pwd.length < 6) {
                $("#pwd_info").fadeOut(100);
                $("#pwd_info").fadeIn(100);
                $("#UserPassword").addClass("input_danger");
                return false;
            }
            var dataform = new FormData();
            dataform.append('data[User][username]', $('#UserUsername').val());
            dataform.append('data[User][email]', $('#UserEmail').val());
            dataform.append('data[User][password]', $('#UserPassword').val());
            dataform.append('data[User][role]', $('#UserRole').val());
            dataform.append('data[User][address]', $('#UserAddress').val());
            dataform.append('data[User][contact]', $('#UserContact').val());
            dataform.append('data[User][company_name]', $('#UserCompanyName').val());

            dataform.append('data[Shop][android_key]', $('#UserAndroidKey').val());
            dataform.append('data[Shop][ios_ck_file]', $('#UserIosCkFile').prop('files')[0]);

            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'admin_create_user_shop')); ?>",
                data: dataform,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                type: "post",
                beforeSend: function () {
                    $(".error-message").html("");
                    $(".success-message").html("");
                    $("#create_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        $("#success-msg-create").html(respond.msg);
                        $('form#create_user')[0].reset();
                        $("#user_shop_list").html("");
                        $("#paginate").pagination('drawPage', 1);
                        admin_user_shop_list();
                        $("#ModalAdd").modal("hide");
                    } else {
                        $("#error-msg-create").html(respond.msg);
                    }
                },
                // error: function (xhr, ajaxOptions, throwError) {
                //     console.log("Error: " + xhr.status);
                // },
                complete: function () {
                    $("#password").removeClass("input_danger");
                    $("#create_loading").addClass("hide");
                }
            });
        });

        $('form#edit_user').submit(function (e) {
            e.preventDefault();
            var pwd = $("#password_update").val();
            if (pwd.length > 0 && pwd.length < 6) {
                $(".pwd_info").fadeOut(100);
                $(".pwd_info").fadeIn(100);
                $("#password_update").addClass("input_danger");
                return false;
            }

            var dataform = new FormData();
            dataform.append('data[User][username]', $('.username_update').val());
            dataform.append('data[User][email]', $('.email_update').val());
            dataform.append('data[User][password]', $('#password_update').val());
            dataform.append('data[User][user_id]', $('.user_id').val());
            //dataform.append('data[User][role]', $('#user-role').val());

            dataform.append('data[Shop][android_key]', $('#edit_android_key').val());
            dataform.append('data[Shop][ios_ck_file]', $('#edit_ios_ck_file').prop('files')[0]);

            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'admin_edit_user_shop')) ?>",
                data: dataform,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                type: "post",
                beforeSend: function () {
                    $("#edit_loading").removeClass("hide");
                    $(".error-message, .success-message").html("");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        admin_user_shop_list();
                        $("#ModalEdit").modal("hide");
                        $("form#edit_user")[0].reset();
                    } else {
                        $("#error-msg-edit").html(respond.msg);
                    }
                },
                complete: function () {
                    $("#edit_loading").addClass("hide");
                    $("#password_update").removeClass("input_danger");
                }
            });
        });

        $('form#delete_user').submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'admin_user_setting')); ?>",
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

        $('form#change_password_user').submit(function (e) {
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
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'admin_user_setting')) ?>",
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
        $('body').on('click', '.get_user_id', function () {
            var id = $(this).attr("id");
            var shop_id =$(this).attr('data-user-shop-id');
            var name = $(this).attr("data-name");
            $(".user_id").val(id);
            $(".shop_id").val(shop_id);
            $(".error-message, .success-message").html("");
            $("#label-delete").html(name + "を削除します。<br>この" + name + "を非表示BOX箱に移 <br>動します。<br>よろしいですか？");
            $("#label-delete-confirm").html(name + "を削除します本当によろしいですか？");
        });
        $('body').on('click', '.get_user_detail', function () {
            var id = $(this).attr("id");
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'users', 'action' => 'user_setting')) ?>",
                data: "action=edit&user_id=" + id,
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

        $( "body" ).on('change', '#UserRole', function() {
            if ($(this).val() === 'headquarter') {
                $('.headquater-role').show();
            } else {
                $('#UserCompanyName').val('');
                $('#UserContact').val('');
                $('#UserAddress').val('');
                $('.headquater-role').hide();
            }
        });
    });
</script>