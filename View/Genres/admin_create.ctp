<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo GENRE_MANAGEMENT_CREATE_NEW ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></a></li>
            <li><?php echo $this->Html->link(GENRE_MANAGEMENT, array('controller' => 'genres', 'action' => 'admin_index')); ?></a></li>
            <li class="active"><?php echo CREATE_NEW ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php
        echo $this->Form->create('Genre', array(
            'class' => 'form-horizontal'));
        ?>
        <fieldset>
            <div class="form-group">
                <label class="control-label col-sm-2" for="name"><?php echo NAME ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('genre', array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'genre'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="ramarks"><?php echo REMARKS ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('remarks', array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'remarks'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-4">
                    <?php
                    echo $this->Form->submit(CREATE_NEW, array(
                        'class' => 'btn btn-primary margin-top-10'));
                    ?>
                </div>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>