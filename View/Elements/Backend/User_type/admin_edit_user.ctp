<!--
* admin_edit_user
* Modified 12/ November/2015
* Channeth
-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo USER_MANAGEMENT_EDIT ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></li>
            <li>
                <?php
                echo $this->Html->link(USER_MANAGEMENT, '/admin/users');
                ?>
            </li>
            <li class="active"><?php echo EDIT ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php
        echo $this->Form->create('User', array(
            'class' => 'form-horizontal'));
        ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="username"><?php echo USERNAME ?> :</label>
            <div class="col-sm-8">
                <?php
                echo $this->Form->input('username', array(
                    'class' => 'form-control ',
                    'label' => false,
                    'id' => 'username'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="password_update"><?php echo PASSWORD ?> :</label>
            <div class="col-sm-8">
                <?php
                echo $this->Form->input('password_update', array(
                    'class' => 'form-control ',
                    'label' => false,
                    'id' => 'password_update',
                    'type' => 'password'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="role"><?php echo ROLE ?> :</label>
            <div class="col-sm-4">
                <?php
                $role = array(
                    'admin' => ADMINISTRATOR,
                    //'agent' => AGENT_USER,//
                    //'shop' => SHOP_USER,//
                    'user' => NORMAL_USER);
                echo $this->Form->select('role', $role, array(
                    'class' => 'form-control',
                    'id' => 'role',
                    'empty' => array(
                        '' => SELECT_ROLE)));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="status"><?php echo STATUS ?> :</label>
            <div class="col-sm-4">
                <?php
                $status = array(
                    '0' => DEACTIVE,
                    '1' => ACTIVATE);
                echo $this->Form->select('status', $status, array(
                    'class' => 'form-control',
                    'id' => 'status',
                    'empty' => array(
                        '' => SELECT_STATUS)));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2"></label>
            <div class="col-sm-4">
                <?php
                echo $this->Form->submit(SAVE_USER, array(
                    'class' => 'btn btn-primary margin-top-10'));
                ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>