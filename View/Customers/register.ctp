<?php
echo $this->Html->css('customerLedger');
echo $this->Html->css('font-awesome.min');
echo $this->Html->css('http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
echo $this->Html->script(array('datepicker-ja'));
?>

<div class="row">
    <div class=" col-lg-12">
        <h1 class="page-header" style="border-bottom:5px solid #eee;">顧客登録</h1>
    </div>
    <?php 
    $role = $this->Session->read('Auth.User.role');
    if ($role == ROLE_HEADQUARTER) : ?>
        <div class="col-xs-12 col-sm-4">
            <div class="form-group">
                <?php
                    echo $this->Form->input('shop_name', array(
                        'id'   => 'select_shop',
                        'type' => 'select',
                        'name' => 'shop_name',
                        'class' => 'shop_name form-control',
                        'label' => false,
                        'options' => $shop,
                        'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                    ));
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class=" col-xs-12">
        <div class="pull-left">
            <h4>総顧客 : <span id="count_customer"></span> <h4>
        </div>
            <div class="">
                <?php
                echo $this->Html->link('削除情報', array('controller' => 'customers', 'action' => 'deleted?type=user'), array(
                    'class' => 'btn btn-success pull-right back_button mgb10',
                    'style' => 'width:160px; margin-left:7px;'
                ));
                ?>
                <button type="button" class="btn btn-success back_button pull-right mgb10" id="btn_register" data-toggle="modal" data-target="#ModalRegister">登録</button>
            </div>
        <br><br><br>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered" id="reservation">
        <thead>
            <tr style="font-size:10pt;">
                <th>姓　名</th>
                <th>セイ メイ</th>
                <th>電話番号</th>
                <th style="width:130px">生年月日</th>
                <th style="width:50px;">性別 </th>
                <th style="width:80">地域 </th>
               <!-- <th>会員番号 </th> -->
                <th>メールアドレス </th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div class="modal fade" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content col-md-10 col-md-offset-1">
            <div class="modal-header">
                <button type="button" class="close closeSmallModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">本当によろしいですか？</h4>
            </div>
            <div class="modal-body text-center"​>
                <div class="clearfix">&nbsp;</div>
                <input type="hidden" name="user_id" id="user_id">
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
                <div class="clearfix">&nbsp;</div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-6 col-md-6"><button type="button" class="btn btn-block btn_color background" data-dismiss="modal">閉じる</button></div>
                    <div class="col-xs-6 col-md-6"><button type="button" id="btn_delete_confirm" class="btn btn-block btn_color but_design">はい</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ModalRegister" style="margin-top:20px;" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content" >
            <?php
            echo $this->Form->create('register', array(
                'id' => 'form_register',
                'class' => 'form-inline',
                'style' => 'margin-bottom:35px;',
                'type' => 'get'
                    )
            );
            echo $this->Form->hidden('Shop.shop_id', ['id' => 'shop-id']);
            ?>
            <div class="modal-header border">
                <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="memoLabel">顧客登録</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding:0 25px;">
                    <div class="col-sm-12">
                        <input type="hidden" name="customer_id">
                        <div class="row">
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('text', array(
                                    'class' => 'form-control',
                                    'name' => 'lastname',
                                    'placeholder' => '姓',
                                    'required' => TRUE,
                                    'allowEmpty' => FALSE,
                                    'label' => false));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('text', array(
                                    'class' => 'form-control',
                                    'name' => 'firstname',
                                    'required' => TRUE,
                                    'allowEmpty' => FALSE,
                                    'placeholder' => '名',
                                    'label' => false));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('text', array(
                                    'class' => 'form-control',
                                    'name' => 'lastname_kana',
                                    'placeholder' => 'セイ',
                                    'label' => false));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('text', array(
                                    'class' => 'form-control',
                                    'name' => 'firstname_kana',
                                    'placeholder' => 'メイ',
                                    'label' => false));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('text', array(
                                    'class' => 'form-control',
                                    'name' => 'contact',
                                    'placeholder' => '電話番号',
                                    'label' => false));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('date', array(
                                    'class' => 'bd_picker form-control icon_select',
                                    'id' => 'date_picker',
                                    'placeholder' => '生年月日',
                                    'label' => false,
                                    'name' => 'birthday'
                                ));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('gender', array(
                                    'class' => 'form-control disable icon_select',
                                    'id' => 'txt_gender',
                                    'name' => 'gender',
                                    'empty' => SELECT_GENDER,
                                    'placeholder' => '性別',
                                    'options' => unserialize(GENDER),
                                    'label' => false
                                ));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('area', array(
                                    'class' => 'form-control disable icon_select',
                                    'id' => 'txt_area',
                                    'name' => 'area_id',
                                    'placeholder' => '地域',
                                    'options' => $area,
                                    'label' => false
                                ));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('text', array(
                                    'class' => 'form-control ',
                                    'name' => 'membership_id',
                                    'label' => false,
                                    'placeholder' => '会員番号'
                                ));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                echo $this->Form->input('email', array(
                                    'class' => 'form-control',
                                    'name' => 'email',
                                    'label' => false,
                                    'placeholder' => 'メールアドレス',
                                    'style' => 'width: 100%;margin-top:7px;'
                                ));
                                ?>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-sm-12" style="text-align:center;padding:0 24px;">
                    <span id="error" style="color:red;"></span>
                </div>
                <div style="position:relative;">
                    <center>
                        <button type="button" class="btn btn-success back_button btn_back" data-dismiss="modal" aria-label="Close">戻る</button>
                        <input type="submit" class="btn btn-success back_button btn_save" value="保存">
                        <div id="save_loading" class="hide" style="position: absolute;right:15%; bottom: 5px;"><?php echo $this->Html->image('loading.gif'); ?>節約...</div
                    </center>
                </div>

            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<style>
    .modal-custom{
        position: relative;
        top: 0;
        left:0;
        width: 60%;
        margin-left: 0px;
        margin-top: 30px !important;
        margin: 0 auto;
    }
    .modal-custom .text{
        margin:7px 0;
    }
    .modal-custom input[type="text"], .modal-custom select{
        width:100% !important;
    }
    .delete-view{
        background:#5e5e5e !important; box-shadow:0 4px rgba(51, 51, 51, 0.89);
    }
</style>

<script>
    $(document).ready(function (e) {

        $("#date_picker").change(function () {
            return false;
        });
        $("#select_shop").change(function () {
            ListData();
        });
        $("#date_picker").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            yearRange: "1803:2090",
            defaultDate: "1980-01-01"
        });
        $('#txt_area').prepend('<option value="">地域</option>');

        ListData();

        $('body').on('click', '.btn_delete', function (e) {
            var user_id = $(this).closest('tr').attr('data-id');
            $('#user_id').val(user_id);
        });

        $('body').on('click', '#btn_delete_confirm', function (e) {
            var user_id = $('#user_id').val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'customers', 'action' => 'delete_customer')) ?>",
                dataType: "json",
                data: {id: user_id},
                beforeSend: function () {
                    $('#delete_loading').addClass('hide');
                },
                success: function (data) {
                    if (data.result == true) {
                        $('#ModalDelete').modal('hide');
                        ListData();
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $('delete_loading').removeClass('hide');
                }
            });
        });

        $('body').on('click', '#btn_update', function () {
            var user_id = $(this).closest('tr').attr('data-id');
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'customers', 'action' => 'customer_detail')) ?>",
                dataType: "json",
                data: {customer_id: user_id},
                success: function (data) {
                    if (data.result == true) {
                        if (data.user != null) {
                            var user = data.user.User;
                            var form = $('#form_register');
                            console.log(user);
                            $('#ModalRegister').modal('show');
                            $(form).find('[name="customer_id"]').val(user.id);
                            $(form).find('[name="firstname"]').val(user.firstname);
                            $(form).find('[name="lastname"]').val(user.lastname);
                            $(form).find('[name="firstname_kana"]').val(user.firstname_kana);
                            $(form).find('[name="lastname_kana"]').val(user.lastname_kana);
                            $(form).find('[name="contact"]').val(user.contact);
                            $(form).find('#date_picker').val(user.birthday);
                            $(form).find('#txt_gender').val(user.gender);
                            $(form).find('#txt_area').val(user.area_id);
                            $(form).find('[name="membership_id"]').val(user.membership_id);
                            $(form).find('[name="email"]').val(user.email);

                        }
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                }
            });
        });

        $('body').on('click', '#btn_register', function () {
            clearForm();
            if ($('#select_shop').length) {
                $('#shop-id').val($('#select_shop').val());
            }
        });

        $('body').on('click', '.btn_save', function (e) {
            var form = $('#form_register');
            if ($(form)[0].checkValidity()) {
                e.preventDefault();
                $(form).ajaxForm({
                    beforeSend: function () {
                        $('#save_loading').removeClass('hide');
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.result == true) {
                            $('#ModalRegister').modal('hide');
                            ListData();
                        } else {
                            $('#ModalRegister span#error').text(data.message);
                        }
                    }, error: function (xhr, ajaxOptions, throwError) {
                        console.log("Error:" + xhr.status);
                        console.log(throwError);
                    },
                    complete: function () {
                        $('#save_loading').addClass('hide');
                    }
                }).submit();

            }

        });

        function clearForm() {
            var form = $('#form_register');
            $(form).find('[name="customer_id"]').val('');
            $(form).find('[name="firstname"]').val('');
            $(form).find('[name="lastname"]').val('');
            $(form).find('[name="firstname_kana"]').val('');
            $(form).find('[name="lastname_kana"]').val('');
            $(form).find('[name="contact"]').val('');
            $(form).find('#date_picker').val('');
            $(form).find('#txt_gender').val('');
            $(form).find('#txt_area').val('');
            $(form).find('[name="membership_id"]').val('');
            $(form).find('[name="email"]').val('');
            $('#ModalRegister span#error').val('');
        }

        function ListData() {
            var data = {};
            if ($('#select_shop').length > 0) {
                data = {'shop_id': $('#select_shop').val()};
            }
            $.ajax({
                url: "<?php echo Router::url(array(
                        'controller' => 'customers',
                        'action' => 'register_list',
                    )) ?>",
                dataType: "json",
                method: 'get',
                data: data,
                beforeSend: function () {

                },
                success: function (data) {
                    if (data.result == true) {
                        var users = data.users;
                        $('#count_customer').text(data.count_customers);
                        if (users != null) {
                            var result = '';
                            $.each(users, function (index, user) {
                                result += '<tr data-id="' + user.U.id + '">';
                                result += '<td><a href="<?php echo $this->webroot; ?>customers?id='+user.U.id+'">' + NotNull(user.U.lastname) + ' ' + NotNull(user.U.firstname) + '</a></td>';
                                result += '<td>' + NotNull(user.U.lastname_kana) + ' ' + NotNull(user.U.firstname_kana) + '</td>';
                                result += '<td>' + NotNull(user.U.contact) + '</td>';
                                result += '<td>' + NotNull(user.U.birthday) + '</td>';
                                result += '<td>' + NotNull(user.U.gender) + '</td>';
                                result += '<td>' + NotNull(user.A.name) + '</td>';
                                result += '<td>' + NotNull(user.U.email) + '</td>';
                                result += '<td style="border-right-color:white;"><div class="set_margin">';
                                result += '<button class="btn btn-success back_color" id="btn_update">編集</button>';
                                result += '</div></td>';
                                result += '<td><div class="set_margin">';
                                result += '<button class="btn btn-success back_color delete-view btn_delete" data-toggle="modal" data-target="#ModalDelete">削除</button>';
                                result += '</div></td>';
                                result += '</tr>';

                            });
                            $('.table tbody tr').remove();
                            $('.table tbody').append(result);
                        }
                    }
                }
                ,
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                }
            });
        }

        function NotNull(data) {
            if (data != null) {
                return data;
            } else {
                return '';
            }
        }

    });

</script>