<?php if ($user): ?>
    <?php if ($user['User']['role'] == ROLE_HEADQUARTER): ?>
        <?php
        echo $this->Form->create('User', array('id' => 'form-edit-user', 'type' => 'file'));
        echo $this->Form->hidden('User.id', array('id' => 'user-id'));
        ?>
            <div class="form-group">
                <?php echo $this->Form->input('User.role', array(
                    'options' => unserialize(USER_ROLE),
                    'class' => 'form-control',
                    'label' => false,
                    'disabled' => true
                )); ?>
                <span class="help-block help-errors hide error_role"></span>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('User.username', array('class' => 'form-control', 'placeholder' => '氏名', 'required' => 'required', 'label' => false)); ?>
                <span class="help-block help-errors hide error_username"></span>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('User.company_name', array('class' => 'form-control', 'placeholder' => '会社名', 'label' => false)); ?>
                <span class="help-block help-errors hide error_company_name"></span>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('User.contact', array('class' => 'form-control', 'placeholder' => 'TEL', 'label' => false)); ?>
                <span class="help-block help-errors hide error_contact"></span>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('User.address', array('class' => 'form-control', 'placeholder' => '住所', 'label' => false)); ?>
                <span class="help-block help-errors hide error_address"></span>
            </div>
            <div class="form-group padding_formgroup">
                <div id="edit_loading" class="hide text-center"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <h5 id="pwd_info"> 新しいパスワードは6文字以上で入力してください。</h5>
                <?php echo $this->Form->email('User.email', array('class' => 'form-control', 'placeholder' => 'メールアドレス　例）info@cha-chat.jp', 'required' => 'required', 'label' => false)); ?>
                <span class="help-block help-errors hide error_email"></span>
            </div>
            <div class="form-group">
                <?php echo $this->Form->password('User.password', array('class' => 'form-control', 'placeholder' => 'パスワード　例) info1234', 'required' => 'required', 'label' => false)); ?>
                <span class="help-block help-errors hide error_password"></span>
            </div>
            <div class="form-group">
                <h5>モバイル用の許可キー</h5>
                <?php
                echo $this->Form->hidden('Shop.id', array('id' => 'shop-id'));
                echo $this->Form->input('Shop.android_key', array('type' => 'text','class' => 'form-control', 'placeholder' => 'android key', 'label' => false));
                ?>
                <span class="help-block help-errors hide error_android_key"></span>
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->hidden('Shop.old_file', array('id' => 'old-ios-key', 'value' => $this->request->data['Shop']['ios_ck_file']));
                echo $this->Form->file('Shop.ios_ck_file', array('class' => 'form-control'));
                ?>
                <span class="help-block help-errors hide error_ios_ck_file"></span>
            </div>
        <?php echo $this->Form->end(); ?>
    <?php elseif ($user['User']['role'] == ROLE_SHOP): ?>
        <?php
        echo $this->Form->create('User', array('id' => 'form-edit-user', 'type' => 'file'));
        echo $this->Form->hidden('User.id', array('id' => 'user-id'));
        ?>
            <div class="form-group">
                <?php echo $this->Form->input('User.role', array(
                    'options' => unserialize(USER_ROLE),
                    'class' => 'form-control',
                    'label' => false,
                    'disabled' => true
                )); ?>
                <span class="help-block help-errors hide error_role"></span>
            </div>
            <div class="form-group">
                <?php echo $this->Form->input('User.username', array('class' => 'form-control', 'placeholder' => '氏名', 'required' => 'required', 'label' => false)); ?>
                <span class="help-block help-errors hide error_username"></span>
            </div>            
            <div class="form-group padding_formgroup">
                <div id="edit_loading" class="hide text-center"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <h5 id="pwd_info"> 新しいパスワードは6文字以上で入力してください。</h5>
                <?php echo $this->Form->email('User.email', array('class' => 'form-control', 'placeholder' => 'メールアドレス　例）info@cha-chat.jp', 'required' => 'required', 'label' => false)); ?>
                <span class="help-block help-errors hide error_email"></span>
            </div>
            <div class="form-group">
                <?php echo $this->Form->password('User.password', array('class' => 'form-control', 'placeholder' => 'パスワード　例) info1234', 'required' => 'required', 'label' => false)); ?>
                <span class="help-block help-errors hide error_password"></span>
            </div>
            <div class="form-group">
                <h5>モバイル用の許可キー</h5>
                <?php
                echo $this->Form->hidden('Shop.id', array('id' => 'shop-id'));
                echo $this->Form->input('Shop.android_key', array('type' => 'text','class' => 'form-control', 'placeholder' => 'android key', 'label' => false));
                ?>
                <span class="help-block help-errors hide error_android_key"></span>
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->hidden('Shop.old_file', array('id' => 'old-ios-key', 'value' => $this->request->data['Shop']['ios_ck_file']));
                echo $this->Form->file('Shop.ios_ck_file', array('class' => 'form-control'));
                ?>
                <span class="help-block help-errors hide error_ios_ck_file"></span>
            </div>
        <?php echo $this->Form->end(); ?>
    <?php endif ?>
<?php else: ?>
<h4>User not found.</h4>
<?php endif;