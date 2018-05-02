<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo PLAN_MANAGEMENT_CREATE_NEW ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></a></li>
            <li><?php echo $this->Html->link(PLAN_MANAGEMENT, '/admin/plans'); ?></a></li>
            <li class="active"><?php echo CREATE_NEW ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php echo $this->Form->create('Plan', array('class' => 'form-horizontal')); ?>
        <fieldset>
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
                <label class="control-label col-sm-2" for="genre"><?php echo GENRE ?> :</label>
                <div class="col-sm-8">
                    <?php
                    $function_push = array();
                    $arr = array();
                    foreach ($allGenres as $key => $value) {
                        $arr[$value['Genre']['id']] = $value['Genre']['genre'];
                    }
                    array_push($function_push, $arr);
                    echo $this->Form->select('genre_id', $function_push, array(
                        'class' => 'form-control',
                        'id' => 'genre_id',
                        'empty' => array(
                            '' => SELECT_GENRE)));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="genre"><?php echo PLAN_TYPE ?> :</label>
                <div class="col-sm-8">
                    <?php
                    $plan_type = array('デモ', 'エコノミー', 'スタンダード', 'プレミアム');
                    echo $this->Form->select('plan_type', $plan_type, array(
                        'class' => 'form-control',
                        'id' => 'genre_id',
                        'empty' => array(
                            '' => SELECT_PLAN_TYPE)));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="remarks"><?php echo REMARKS ?> :</label>
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
                    echo $this->Form->submit(SAVE_PLAN, array(
                        'class' => 'btn btn-primary margin-top-10'));
                    ?>
                </div>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>