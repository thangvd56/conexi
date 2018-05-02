<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <?php
        echo $this->Form->create('User', array(
            'class' => 'form-horizontal select-user'));
        ?>
        <fieldset>
            <div class="form-group">
                <p class="col-md-12">※お名前検索はカタカナで入力してください。</p>
                <div class="col-sm-12">
                    <label class="control-label col-sm-5" style="text-align: left" for="adult">顧客名</label>
                    <?php
                        echo $this->Form->input('User.username', array(
                            'class' => 'form-control isKatana getUser auto-select',
                            'label' => false,
                            'placeholder' => '顧客名',
                            'data-search-in' => 'name',
                            'data-data' => $this->Html->url('/', true).'reservations/list_user?param=name&shop_id=' . $this->request->query('shop_id'),
                            'data-visible-properties' => '["name"]',
                            'data-selection-required' => 'true',
                            'data-value-property' => '*',
                            'data-min-length' => '0',
                            'type' => 'text'
                        ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label class="control-label col-sm-5" style="text-align: left" for="adult">電話番号</label>
                    <?php
                        echo $this->Form->input('User.contact', array(
                            'class' => 'form-control isMobile getContact auto-select',
                            'label' => false,
                            'placeholder' => '電話番号',
                            'data-search-in' => 'contact',
                            'data-data' => $this->Html->url('/', true).'reservations/list_user?param=contact&shop_id=' . $this->request->query('shop_id'),
                            'data-visible-properties' => '["contact"]',
                            'data-selection-required' => 'true',
                            'data-value-property' => '*',
                            'data-min-length' => '0',
                            'type' => 'text'
                        ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <p style="color: red;">お客様がアプリを持っている場合、ユーザIDを入力してください</p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label class="control-label col-sm-5" style="text-align: left" for="adult">ユーザーID</label>
                    <?php
                        echo $this->Form->input('User.user_code', array(
                            'class' => 'form-control isUserCode getUserCode auto-select',
                            'label' => false,
                            'data-search-in' => 'user_code',
                            'data-data' => $this->Html->url('/', true).'reservations/list_user?param=code&shop_id=' . $this->request->query('shop_id'),
                            'data-visible-properties' => '["user_code"]',
                            'data-selection-required' => 'true',
                            'data-value-property' => '*',
                            'data-min-length' => '0',
                            'type' => 'text'
                        ));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <br/>
                <div class="col-sm-6">
                    <button type="button" class="btn btn_color margin-top-10 btn-block btn-back"><?php echo BACK; ?></button>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo $this->Html->link('新規顧客として予約登録', '#', array(
                        'class' => 'btn btn_color margin-top-10 btn-block getFormUser'
                    ));
                    ?>
                </div>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div><!-- /.End column find user -->

    <div class="col-lg-6 col-md-6">
        <?php
        echo $this->Form->create('Reservation', array(
            'class' => 'form-horizontal reservations-form select-user'));
        ?>
            <fieldset>
            <?php 
                echo $this->Form->input('Reservation.user_id', array(
                    'class' => 'reservation-user-id',
                    'type' => 'hidden'
                ));
            ?>
                <div class="form-group">
                    <p class="col-md-12" style="text-align: center;">検索結果</p>
                    <div class="col-sm-12">
                        <label class="control-label col-sm-5" style="text-align: left" for="adult">顧客名</label>
                        <?php
                            echo $this->Form->input('username', array(
                                'class' => 'reservation-username form-control isKatana disable',
                                'label' => false,
                                'placeholder' => '顧客名',
                                'type' => 'text',
                                'readonly' => 'readonly'
                            ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label col-sm-5" style="text-align: left" for="adult">電話番号</label>
                        <?php
                            echo $this->Form->input('phone', array(
                                'class' => 'reservation-phone form-control isMobile disable',
                                'label' => false,
                                'placeholder' => '電話番号',
                                'type' => 'text',
                                'readonly' => 'readonly'
                            ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <p>&nbsp;</p>
                        <!-- blank to equal with left side -->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label col-sm-5" style="text-align: left" for="adult">ユーザーID</label>
                        <?php
                            echo $this->Form->input('user_code', array(
                                'class' => 'reservation-user-code form-control isUserCode disable',
                                'label' => false,
                                'placeholder' => 'ユーザーID',
                                'type' => 'text',
                                'readonly' => 'readonly'
                            ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <br/>
                    <div class="col-sm-6 col-sm-offset-6 remove-margin-top">
                        <?php
                        echo $this->Form->submit('このお客様で予約', array(
                            'class' => 'btn btn_color margin-top-10 btn-block'));
                        ?>
                    </div>
                </div>
            </fieldset>
        <?php echo $this->Form->end(); ?>
    </div><!-- /.End column user info -->
</div>

<div id="registerUser" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content col-md-10">
            <div class="modal-header border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title checkinLabel"></h4>
            </div>
            <?php
            echo $this->Form->create('User', array(
                'class' => 'form-horizontal userFormCreate'));
            echo $this->Form->input('User.role', array(
                'value' => 'user',
                'type' => 'hidden'
            ));

            if ($this->request->query('shop_id')) {
                echo $this->Form->hidden('shop_id', array('value' => $this->request->query('shop_id')));
            }

            echo $this->Form->input('User.lastname_kana', array(
                'class' => 'form-control isKatana',
                'label' => false,
                'placeholder' => '姓',
                'type' => 'text'
            ));
            echo '<p class="error"></p>';

            echo $this->Form->input('User.firstname_kana', array(
                'class' => 'form-control isKatana',
                'label' => false,
                'placeholder' => '名',
                'type' => 'text'
            ));
            echo '<p class="error"></p>';

            echo $this->Form->input('User.contact', array(
                'class' => 'form-control',
                'label' => false,
                'placeholder' => '電話番号',
                'type' => 'text',
                'id' => 'UserContact2'
            ));
            echo '<p class="error"></p>';

            echo $this->Form->end();
            ?>
            <div class="modal-footer">
                <div class="form-group bottom_set">
                    
                    <div class="col-xs-6 col-sm-6 col-md-6 text-center" data-dismiss="modal">
                        <div class="panel-default btn_cancel_checkin">
                            <div class="icon change_height">
                                <div class="menu-icon cancel operation"></div>
                                <p class="menu-icon-text btn btn-default">キャンセル</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-center">
                        <input type="hidden" class="customer_id">
                        <div class="panel-default btn_checkin_customer">
                            <div class="icon change_height">
                                <div class="menu-icon checkin"></div>
                                <p class="menu-icon-text btn btn-primary submitUser">セーブ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php
echo $this->Html->css('jquery.flexdatalist.min');
echo $this->Html->script('jquery.flexdatalist.min');
?>

<style>
    .error {
        margin-top: 15px;
        margin-bottom: 15px;
        color: red;
    }
</style>

<script>
    $(document).ready(function() {
        <?php if ($this->request->action === 'create') { ?>
            setTimeout(function() {
                if ( $.cookie('user_is_refresh') == 1 ) {
                    clearReservation(<?php echo $this->Session->read('ReservationId'); ?>);
                }
            }, 200);

            //Session timer expire
            setTimeout(function() {
                clearReservation(<?php echo $this->Session->read('ReservationId'); ?>);
            }, 150000);

            $(window).on('unload', function() {
                clearReservation(<?php echo $this->Session->read('ReservationId'); ?>);
            });
        <?php } ?>
           
        $('body').on('keypress', '.allow_key', function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });

        $('.isKatana').bind("enterKey",function(e){
            var me = $(this);
            var VAL = me.val();
            if (!isKana(VAL, e)) {
                alert('顧客検索はカタカナで入力してください。');
            }
        });
        
        $('.isKatana').keyup(function(e) {
            if (e.keyCode == 13) {
                $(this).trigger("enterKey");
            }
        });

        userList();
        contactList();
        codeList();
        function userList() {
            $('.getUser').flexdatalist({
                minLength: 0,
                valueProperty: '*',
                selectionRequired: true,
                visibleProperties: ['name'],
                searchIn: 'name',
                data: URL + 'reservations/list_user?param=name'
            });
        }
        
        function contactList() {
            $('.getContact').flexdatalist({
                minLength: 0,
                valueProperty: '*',
                selectionRequired: true,
                visibleProperties: ['contact'],
                searchIn: 'contact',
                data: URL + 'reservations/list_user?param=contact'
            });
        }

        function codeList() {
            $('.getUserCode').flexdatalist({
                minLength: 0,
                valueProperty: '*',
                selectionRequired: true,
                visibleProperties: ['user_code'],
                searchIn: 'user_code',
                data: URL + 'reservations/list_user?param=code'
            });
        }

        $('.getUser').on('select:flexdatalist', function() {
            clearSelections('user');
            fetch_user($(this).val());
        });

        $('.getContact').on('select:flexdatalist', function() {
            clearSelections('contact');
            fetch_user($(this).val());
        });

        $('.getUserCode').on('select:flexdatalist', function() {
            clearSelections('code');
            fetch_user($(this).val());
        });

        function clearSelections(type) {
            if (type == 'user') {
                $('.getContact').val('');
                $('.getUserCode').val('');
            } else if (type == 'contact') {
                $('.getUser').val('');
                $('.getUserCode').val('');
            } else if (type == 'code') {
                $('.getContact').val('');
                $('.getUser').val('');
            }
        }
        
//        if (window.history && window.history.pushState) {
//            window.history.pushState('back', null, null);
//            $(window).on('popstate', function() {
//                setTimeout(call_back(), 200);
//            });
//        }

        $('.btn-back').click(function() {
            setTimeout(function () {
                call_back();
                window.history.go(-1);
            }, 100);
        });

        function call_back() {
            $.cookie('chair_is_refresh', 0, { path: '/' });
            $.cookie('appointment_is_refresh', 0, { path: '/' });
            $.cookie('staff_is_refresh', 0, { path: '/' });
            $.cookie('tag_is_refresh', 0, { path: '/' });
            $.cookie('budget_is_refresh', 0, { path: '/' });
            $.cookie('user_is_refresh', 0, { path: '/' });
            $.cookie('confirm_is_refresh', 0, { path: '/' });
            $.cookie('reservation_uncomplete', 0, { path: '/' });
            $(window).off('beforeunload');
            $(window).off('unload');
        }

        $('form.reservations-form').submit(function (e) {
            if ($('.reservation-user-id').val() === '0' || $('.reservation-user-id').val() === '') {
                alert('ユーザーを選択するか、新しい一回を作成してください。');
                e.preventDefault();
                return false;
            } else {
                $(window).off('beforeunload');
                $(window).off('unload');
            }
        });

        $('.getFormUser').click(function() {
            $('#registerUser').modal('show');
            clearUserInfo();
        });

        $('.submitUser').on('click', function (e) {
            if (checkUserInfo() === true) {
                return false;
            }
            
            $('.submitUser').text('読み込んでいます...');
            e.preventDefault();
            var that = $('form.userFormCreate');
            $('.errorMsg').text('');
            $.ajax({
                url: URL + 'users/create',
                data: that.serializeArray(),
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    $('.submitUser').text('セーブ');
                    if (data) {
                        $('#registerUser').modal('hide');
                        clearUserInfo();
                        $('.reservation-user-id').val(data['User']['id']);
                        $('.reservation-username').val(data['User']['lastname_kana'] + ' ' + data['User']['firstname_kana']);
                        $('.reservation-phone').val(data['User']['contact']);
                        $('.reservation-user-code').val(data['User']['user_code']);
                        userList();
                        contactList();
                        codeList();
                    }
                }
            });
        });

        $(window).on('beforeunload', function(e) {
            var confirmationMessage = '別ページへ遷移すると入力した予約内容が破棄されます。';
            e.returnValue = confirmationMessage;
            $.cookie('user_is_refresh', 1, { path: '/' });
            $.cookie('reservation_uncomplete', 1, { path: '/' });
            return confirmationMessage;
        });

        var data;
        function fetch_user (value) {
            data = jQuery.parseJSON(value);
            $('.reservation-user-id').val(data['id']);
            $('.reservation-username').val(data['name']);
            $('.reservation-phone').val(data['contact']);
            $('.reservation-user-code').val(data['user_code']);
        }

        function isKana(value) {
            if(value.match( /^[ァ-ン]+$/ )) {
              return true;
            }
            return false;
        }

        function checkUserInfo() {
            var err = false;
            var message = '必須';
            if ($('#UserFirstnameKana').val() === '') {
                $('#UserFirstnameKana').parent('div').next('.error').text(message);
                err = true;
            }

            if ($('#UserLastnameKana').val() === '') {
                $('#UserLastnameKana').parent('div').next('.error').text(message);
                err = true;
            }

            if ($('#UserContact2').val() === '') {
                $('#UserContact2').parent('div').next('.error').text(message);
                err = true;
            }
            return err;
        }

        function clearUserInfo() {
            $('#UserFirstnameKana').val('');
            $('#UserLastnameKana').val('');
            $('#UserContact2').val('');

            $('#UserFirstnameKana').parent('div').next('.error').text('');
            $('#UserLastnameKana').parent('div').next('.error').text('');
            $('#UserContact2').parent('div').next('.error').text('');
        }

        function clearReservation(id){
            $.ajax({
                url: URL + 'reservations/deleteReservation/?id=' + id,
                type: 'post',
                dataType: 'json',
                success: function (data) {
                   if (data) {
                        $.cookie('appointment_is_refresh', 0, { path: '/' });
                        $.cookie('staff_is_refresh', 0, { path: '/' });
                        $.cookie('tag_is_refresh', 0), { path: '/' };
                        $.cookie('budget_is_refresh', 0, { path: '/' });
                        $.cookie('user_is_refresh', 0, { path: '/' });
                        $.cookie('reservation_uncomplete', 0, { path: '/' });
                        $(window).off('beforeunload');
                        window.location.href = '<?php echo Router::url("/", true); ?>';
                   }
                }
            });
        }
    });
</script>