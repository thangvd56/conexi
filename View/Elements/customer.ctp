
<div class="row">
    <div class="col-xs-12">
    <div class="col-lg-4">
        <div class="form-group">
            <p class="add_font customer_name"></p>
        </div>
    </div>
    <div class="col-lg-8 disabled_border">
        <div class="form-group align">
            <p class="btn-default count_reservation">来店 <span class="badge reservation_num">0</span> 回目</p>
            <button type="button" class="btn btn-success back_button btn_checkin">チェックイン</button>
            <button type="button" class="btn btn-success back_button" onclick="sendPictureReservation()">写真管理</button>
            <button type="button" class="btn btn-success back_button" onclick="viewReservation()">予約管理</button>
        </div>
    </div>
    </div>
</div>
    <hr class="hr"/>
<div class="row">
    <div class="col-xs-12">
    <div class="col-lg-6">
        <div class="form-group">
            <p class="set_top">顧客情報</p>
            <div  class="text-center load_customer_info hide" style="margin-top:-46px;">
            <?php echo $this->Html->image('/uploads/loading.gif', array('style' => 'width: 35px;')).' ローディング'; ?>
            </div>
            <div  class="text-center save_customer_info hide" style="margin-top:-46px;">
            <?php echo $this->Html->image('/uploads/loading.gif', array('style' => 'width: 35px;')).' 保存中'; ?>
            </div>
        </div>
    </div>
    <?php
        echo $this->Form->create('customer', array(
            'id' => 'form_customer',
            'class' => 'form-horizontal',
            'inputDefaults' => array(
            'legend' => false,
            'label' => false,
            'div' => false
        )));
    ?>
    <div class="col-lg-6">
        <div class="form-group align form-back-button">
            <input type="hidden" id="customer_name">
            <button id="btn_delete" type="button" class="btn btn-success back_button last">削除</button>
            <button id="btn_save" type="submit" class="btn btn-success back_button last btn_save_customer">編集</button>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">姓</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('lastname',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'id' => 'txt_lastname',
                    'name' => 'lastname',
                    'placeholder' => '姓'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">名</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('firstname',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'id' => 'txt_firstname',
                    'name' => 'firstname',
                    'placeholder' => '名'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">セイ</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('lastname_kana',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'id' => 'txt_lastname_kana',
                    'name' => 'lastname_kana',
                    'placeholder' => 'セイ'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">メイ</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('firstname_kana',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'id' => 'txt_firstnamekana',
                    'name' => 'firstname_kana',
                    'placeholder' => 'メイ'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">生年月日</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('birthday',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'id' => 'txt_birthday',
                    'name' => 'birthday',
                    'placeholder' => '生年月日'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">性別</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('gender',
                    array(
                    'class' => 'form-control disable',
                    'id' => 'txt_gender',
                    'name' => 'gender',
                    'empty' => SELECT_GENDER,
                    'placeholder' => '性別',
                    'options' => unserialize(GENDER)
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">地域</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('area',
                    array(
                    'class' => 'form-control disable',
                    'id' => 'txt_area',
                    'name' => 'area',
                    'options' => $area,
                    'empty' => array('' => '未設定')
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">電話番号</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('mobile',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'id' => 'txt_mobile',
                    'name' => 'mobile',
                    'placeholder' => '電話番号'
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">機種変更</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('model_id_change',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'readonly' => true,
                    'id' => 'txt_model_id_chage',
                    'name' => 'model_id_change',
                    'placeholder' => '機種変更用暗証番号 '
                ));
                ?>
                <button id="sendPin" class="btn btn-success color back_color width but_design btn_send_modelid">暗証番号送信</button>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-sm-3 label_info">ユーザID</span>
            <div class="col-sm-9">
                <?php
                echo $this->Form->input('user_code',
                    array(
                    'class' => 'form-control',
                    'type' => 'text',
                    'id' => 'txt_model_id',
                    'name' => 'user_code',
                    'placeholder' => '識別番号',
                    'disabled' => true
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group top">
            <span class="col-md-3 label_info">メールアドレス</span>
            <div class="col-md-9">
                <?php
                echo $this->Form->input('email',
                    array(
                    'class' => 'form-control disable',
                    'type' => 'text',
                    'id' => 'txt_email',
                    'name' => 'email',
                    'placeholder' => 'メールアドレス'
                ));
                ?>
            </div>
        </div>
    </div>
    </div>
</div>
    <?php echo $this->Form->end(); ?>
    <!--Start Tags -->
    <div class="row">
        <div class="col-xs-12">
            <div class="col-lg-6 top">
                <div class="form-group">
                    <p class="set_top"> 顧客情報 </p>
                </div>
            </div>
        </div>
    </div>
