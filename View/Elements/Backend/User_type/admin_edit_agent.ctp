<!--
* admin_edit_agent
* Modified 11/ November/2015
* Channeth
-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo AGENT_MANAGEMENT_EDIT ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></li>
            <li><?php echo $this->Html->link(AGENT_MANAGEMENT, '/admin/users?role=agent'); ?></li>
            <li class="active"><?php echo EDIT ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php
        echo $this->Form->create('User', array(
            'class' => 'form-horizontal'));
        ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="name"><?php echo NAME ?> :</label>
            <div class="col-sm-8">
                <?php
                echo $this->Form->input('name', array(
                    'class' => 'form-control ',
                    'label' => false,
                    'id' => 'name'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="id"><?php echo ID ?> :</label>
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
            <label class="control-label col-sm-2" for="contact"><?php echo CONTACT ?> :</label>
            <div class="col-sm-8">
                <?php
                echo $this->Form->input('contact', array(
                    'class' => 'form-control ',
                    'label' => false,
                    'id' => 'contact'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="status"><?php echo STATUS ?> :</label>
            <div class="col-sm-4">
                <?php
                $status = array(
                    '0' => DEACTIVE,
                    '1' => ACTIVE,
                    '2' => SUSPENSE,
                    '3' => STOP);
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