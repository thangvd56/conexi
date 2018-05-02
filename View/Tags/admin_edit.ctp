<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo TAG_MANAGEMENT_EDIT ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <?php $genre_id = $this->request->query('genre_id'); ?>
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></a></li>
            <li><?php echo $this->Html->link(GENRE_MANAGEMENT, array('controller' => 'genres', 'action' => 'admin_index')); ?></a></li>
            <li><?php echo $this->Html->link(TAG_MANAGEMENT, '/admin/tags?genre_id=' . $genre_id); ?></a></li>
            <li class="active"><?php echo EDIT ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php echo $this->Form->create('Tag', array('class' => 'form-horizontal')); ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tag"><?php echo NAME ?> :</label>
            <div class="col-sm-8">
                <?php
                echo $this->Form->input('tag', array(
                    'class' => 'form-control ',
                    'label' => false,
                    'id' => 'tag'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="remark_update"><?php echo REMARKS ?> :</label>
            <div class="col-sm-8">
                <?php
                echo $this->Form->input('remark', array(
                    'class' => 'form-control ',
                    'label' => false,
                    'id' => 'remark'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2"></label>
            <div class="col-sm-4">
                <?php
                echo $this->Form->submit(SAVE_TAG, array(
                    'class' => 'btn btn-primary margin-top-10'));
                ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>