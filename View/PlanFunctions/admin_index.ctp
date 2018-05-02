<!--
Disable function
Modified 17/ November/2015
Channeth
-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo SET_FUNCTION ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></a></li>
            <li><?php echo $this->Html->link(PLAN_MANAGEMENT, '/admin/plans'); ?></a></li>
            <li class="active"><?php echo SET_FUNCTION ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php echo $this->Form->create('PlanFunction', array('class' => 'form-horizontal')); ?>
        <fieldset>
            <?php
            $function1 = '';
            $function2 = '';
            $function3 = '';
            $function4 = '';
            $function5 = '';
            $function = array();
            $function_push = array();
            foreach ($data as $key => $value) {
                $function = $value['PlanFunction']['function'];
                array_push($function_push, $function);
            }
            if (in_array('Function1', $function_push)) {
                $function1 = 'checked';
            }
            if (in_array('Function2', $function_push)) {
                $function2 = 'checked';
            }
            if (in_array('Function3', $function_push)) {
                $function3 = 'checked';
            }
            if (in_array('Function4', $function_push)) {
                $function4 = 'checked';
            }
            if (in_array('Function5', $function_push)) {
                $function5 = 'checked';
            }

            $function1_disable = true;
            $function2_disable = true;
            $function3_disable = true;
            $function4_disable = true;
            $function5_disable = true;
            $function_disable = array();
            $function_disable_push = array();
            foreach ($genre_data as $key => $value) {
                foreach ($value as $k => $val) {
                    $function_disable = $val['function'];
                    array_push($function_disable_push, $function_disable);
                }
            }
            if (in_array('Function1', $function_disable_push)) {
                $function1_disable = false;
            }
            if (in_array('Function2', $function_disable_push)) {
                $function2_disable = false;
            }
            if (in_array('Function3', $function_disable_push)) {
                $function3_disable = false;
            }
            if (in_array('Function4', $function_disable_push)) {
                $function4_disable = false;
            }
            if (in_array('Function5', $function_disable_push)) {
                $function5_disable = false;
            }
            ?>
            <div class="form-group">
                <label class="control-label col-sm-2" for="GenreFunctionFunction1"><?php echo FUNCTION1 ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->checkbox('function.', array(
                        'checked' => $function1,
                        'disabled' => array($function1_disable),
                        'value' => 'Function1',
                        'class' => 'form-control width33',
                        'id' => 'GenreFunctionFunction1',
                        'label' => false
                    ));
                    ?>

                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="GenreFunctionFunction2"><?php echo FUNCTION2 ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->checkbox('function.', array(
                        'checked' => $function2,
                        'disabled' => array($function2_disable),
                        'value' => 'Function2',
                        'class' => 'form-control width33',
                        'id' => 'GenreFunctionFunction2',
                        'label' => false
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="GenreFunctionFunction3"><?php echo FUNCTION3 ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->checkbox('function.', array(
                        'checked' => $function3,
                        'disabled' => array($function3_disable),
                        'value' => 'Function3',
                        'class' => 'form-control width33',
                        'id' => 'GenreFunctionFunction3',
                        'label' => false
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="GenreFunctionFunction4"><?php echo FUNCTION4 ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->checkbox('function.', array(
                        'checked' => $function4,
                        'disabled' => array($function4_disable),
                        'value' => 'Function4',
                        'class' => 'form-control width33',
                        'id' => 'GenreFunctionFunction4',
                        'label' => false
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="GenreFunctionFunction5"><?php echo FUNCTION5 ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->checkbox('function.', array(
                        'checked' => $function5,
                        'disabled' => array($function5_disable),
                        'value' => 'Function5',
                        'class' => 'form-control width33',
                        'id' => 'GenreFunctionFunction5',
                        'label' => false
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-4">
                    <?php echo $this->Form->submit(SAVE_FUNCTION, array(
                        'class' => 'btn btn-primary margin-top-10'));
                    ?>
                </div>
            </div>
        </fieldset>
<?php echo $this->Form->end(); ?>
    </div>
</div>