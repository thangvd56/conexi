<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo IP_ADDRESS_MENAGEMENT_CREATE_NEW ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></a></li>
            <li><?php echo $this->Html->link(IP_ADDRESS_MENAGEMENT, array('controller' => 'ips', 'action' => 'admin_index')); ?></a></li>
            <li class="active"><?php echo CREATE_NEW ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php
        echo $this->Form->create('Ip', array(
            'class' => 'form-horizontal'));
        ?>
        <fieldset>
            <div class="form-group">
                <label class="control-label col-sm-2" for="ip"><?php echo IP ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('ip', array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'ip'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="ramarks"><?php echo REMARKS ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('ramarks', array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'ramarks'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-4">
                    <?php
                    echo $this->Form->submit(SAVE_IP, array(
                        'class' => 'btn btn-primary margin-top-10'));
                    ?>
                </div>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>