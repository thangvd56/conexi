<?php
/*
 * admin_view_shop
 * Modified 11/ November/2015
 * Channeth
 */
$status = '';
switch ($user['User']['status']) {
    case 0:
        $status = DEACTIVATED;
        break;
    case 1:
        $status = ACTIVATED;
        break;
    case 2:
        $status = SUSPENSED;
        break;
    case 3:
        $status = STOPPED;
        break;
}
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo $user['User']['username']; ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></li>
            <li>
                <?php
                $agent_id = $this->request->query('agent_id');
                if (isset($agent_id)) {
                    echo $this->Html->link(SHOP_OWNER_MANAGEMENT, '/admin/users?role=shop&agent_id=' . $agent_id);
                } else {
                    echo $this->Html->link(SHOP_OWNER_MANAGEMENT, '/admin/users?role=shop');
                }
                ?>
            </li>
            <li class="active"><?php echo $user['User']['username']; ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <form class="form-horizontal" role="form">
<!--            <div class="form-group">
                <label class="control-label col-sm-2" for="name"><?php //echo NAME ?> :</label>
                <div class="col-sm-8">
                    <?php
//                    echo $this->Form->input('name', array(
//                        'class' => 'form-control ',
//                        'label' => false,
//                        'id' => 'name',
//                        'value' => $user['User']['username']));
                    ?>
                </div>
            </div>-->
            <div class="form-group">
                <label class="control-label col-sm-2" for="id"><?php echo ID ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('id', array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'id',
                        'value' => $user['User']['username']));
                    ?>
                </div>
            </div>
            <div class="form-group">&nbsp;</div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="contact"><?php echo CONTACT ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('contact', array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'contact',
                        'value' => $user['User']['contact']));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="status"><?php echo STATUS ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('status', array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' =>
                        'status',
                        'value' => $status));
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>