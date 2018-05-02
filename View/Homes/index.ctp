
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/'); ?></li>
            <li>
                <?php echo $this->Html->link(RESERVATION_MANAGEMENT, '/reservations'); ?>
            </li>
            <li class="active"><?php echo ($this->request->action == 'edit') ? EDIT : CREATE_NEW ?></li>
        </ol>
    </div>
    <div class="col-lg-5">
        <?php
        echo $this->Form->create('User', array(
            'class' => 'form-horizontal'));
        ?>
        <fieldset>
            <div class="form-group">
                <p class="col-md-12">人数は、何名様ですか？</p>
                <div class="col-sm-12">
                    <label class="control-label col-sm-5" style="text-align: left" for="adult">顧客名</label>
                    <?php
                    echo $this->Form->input('User.username', array(
                        'class' => 'form-control isKatana getUser',
                        'label' => false,
                        'placeholder' => '顧客名',
                        'data-search-in' => 'name',
                        'data-data' => $this->Html->url('/', true).'reservations/list_user?param=name',
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
                        'class' => 'form-control isMobile getContact',
                        'label' => false,
                        'placeholder' => '電話番号',
                        'data-search-in' => 'contact',
                        'data-data' => $this->Html->url('/', true).'reservations/list_user?param=contact',
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
                <br/>
                <div class="col-sm-6">
                    <?php
                    echo $this->Html->link(BACK, 'javascript::void(0)', array(
                        'class' => 'btn btn-default margin-top-10 btn-block btn-back'));
                    ?>
                </div>
                <div class="col-sm-6">
                    <?php
                    echo $this->Html->link('新規顧客として予約登録', '#', array(
                        'class' => 'btn btn-primary margin-top-10 btn-block getFormUser'
                        ));
                    ?>
                </div>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div><!-- /.End column find user -->

    <div class="col-lg-5 col-lg-offset-1">
        <div class="form-horizontal">
            <fieldset>
                <div class="form-group">
                    <p class="col-md-12" style="text-align: center;">検索結果</p>
                    <div class="col-sm-12">
                        <label class="control-label col-sm-5" style="text-align: left" for="adult">顧客名</label>
                        <?php
                        echo $this->Form->input('username', array('class' => 'form-control isKatana', 'label' => false, 'placeholder' => '顧客名', 'type' => 'text'));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label col-sm-5" style="text-align: left" for="adult">電話番号</label>
                        <?php
                        echo $this->Form->input('phone', array('class' => 'form-control isMobile', 'label' => false, 'placeholder' => '電話番号',  'type' => 'text'));
                        ?>
                    </div>
                </div>
            </fieldset>
        </div>
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

            echo $this->Form->input('User.firstname_kana', array(
                'class' => 'form-control isKatana',
                'label' => false,
                'placeholder' => 'ファーストネーム',
                'type' => 'text'
            ));
            echo '<p class="error"></p>';

            echo $this->Form->input('User.lastname_kana', array(
                'class' => 'form-control isKatana',
                'label' => false,
                'placeholder' => '苗字',
                'type' => 'text'
            ));
            echo '<p class="error"></p>';

            echo $this->Form->input('User.contact', array(
                'class' => 'form-control',
                'label' => false,
                'placeholder' => 'モバイル',
                'type' => 'text'
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
                                <p class="menu-icon-text btn btn-primary submit">セーブ</p>
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
    }
</style>
<script>
    $(document).ready(function(){
        $('.isKatana').keyup(function () {
            var VAL = $(this).val();
            if (!isKana(VAL)) {
              $(this).val( $(this).val().slice(0, -1));
            }
         });

        $('.getUser').flexdatalist({
            minLength: 0,
            valueProperty: '*',
            selectionRequired: true,
            visibleProperties: ['name'],
            searchIn: 'name',
            data: URL + 'homes/list_user?param=name'
        });

        $('.getContact').flexdatalist({
            minLength: 0,
            valueProperty: '*',
            selectionRequired: true,
            visibleProperties: ['contact'],
            searchIn: 'contact',
            data: URL + 'homes/list_user?param=contact'
        });

        $('.getUser').on('select:flexdatalist', function() {
            console.log($(this).val());
        });

        $('.getContact').on('select:flexdatalist', function() {
            console.log($(this).val());
        });


        $('.btn-back').click(function() {
            window.history.back();
        });

        $('.getFormUser').click(function() {
            $('#registerUser').modal('show');
            clearUserInfo();
        });

        $('.submit').on('click', function (e) {
            if (checkUserInfo() === true) {
                return false;
            }
            
            $('.submit').text('読み込んでいます...');
            e.preventDefault();
            var that = $('form.userFormCreate');
            $('.errorMsg').text('');
            $.ajax({
                url: URL + 'users/create',
                data: that.serializeArray(),
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    $('.submit').text('セーブ');
                    if (data) {
                        console.log(data);
                        $('#registerUser').modal('hide');
                        clearUserInfo();
                    }
                }
            });
        });


    });

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

        return err;
    }

    function clearUserInfo() {
        $('#UserFirstnameKana').val('');
        $('#UserLastnameKana').val('');
        $('#UserContact').val('');
        
        $('#UserFirstnameKana').parent('div').next('.error').text('');
        $('#UserLastnameKana').parent('div').next('.error').text('');
        $('#UserContact').parent('div').next('.error').text('');
    }



    function isKana(value) {
        if(value.match( /^[ァ-ン]+$/ )) {
          return true;
        }
        return false;
    }
</script>