<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo IP_ADDRESS_MENAGEMENT ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></a></li>
            <li class="active"><?php echo IP_ADDRESS_MENAGEMENT ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php echo $this->Form->create('Ip'); ?>
        <fieldset>
            <div class="form-group">
                <!--<div class="col-lg-4">
                //<?php
//                echo $this->Form->input('Search',array(
//                    'class' => 'form-control margin-l-m15',
//                    'label'=>false,
//                    'placeholder'=>'Search...' ));
                ?>
                </div>
                <div class="col-lg-2">
                //    <?php //echo $this->Form->submit(SEARCH,array('class'=>'btn btn-default margin-l-m15'));      ?>
                </div>-->
                <div class="col-lg-6 pull-right">
                    <?php
                    echo $this->Html->link(CREATE_NEW, array(
                        'controller' => 'ips',
                        'action' => 'admin_create'), array(
                        'class' => 'btn btn-primary pull-right margin-r-m15'));
                    ?>
                </div>
                <div>&nbsp;</div>
            </div>
            <div>&nbsp;</div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="col-lg-12">
        <table id='table-agent' class="table table-striped">
            <thead>
                <tr>
                    <th class="table-header"><?php echo $this->Paginator->sort('ip', IP); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('created', CREATED); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('ramarks', REMARKS); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><a href="javascript:void(0);"><?php echo OPERATION ?><a/></th>
                </tr>
            </thead>
            <?php foreach ($allIps as $key => $value): ?>
                <tr>
                    <td><?php echo $value['Ip']['ip']; ?></td>
                    <td><?php echo $value['Ip']['created']; ?></td>
                    <td><?php echo $value['Ip']['ramarks']; ?></td>
                    <td style='font-size:13px;'>
                        <?php
                        echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>' . EDIT, array(
                            'controller' => 'ips',
                            'action' => 'admin_edit',
                            $value['Ip']['id']), array('escape' => false));
                        echo str_repeat('&nbsp;', 4);
                        echo $this->Html->link('<i class="fa fa-trash-o"></i>' . DELETE, array(
                            'controller' => 'ips',
                            'action' => 'admin_delete',
                            $value['Ip']['id']), array(
                            'escape' => false,
                            'confirm' => '本当に ' . $value['Ip']['ip'] . ' を削除しますか？'));
                        echo str_repeat('&nbsp;', 4);
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->element('pagination'); ?>
    </div>
</div>