<!--
* admin_index_agent
* Modified 11/ November/2015
* Channeth
-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo AGENT_MANAGEMENT ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></li>
            <li class="active"><?php echo AGENT_MANAGEMENT ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php
        echo $this->Form->create('User', array(
            'type' => 'get'));
        ?>
        <fieldset>
            <div class="form-group">
                <div class="col-lg-4">
                    <?php
                    echo $this->Form->input('Search', array(
                        'class' => 'form-control margin-l-m15',
                        'label' => false,
                        'placeholder' => SEARCH . '...'));
                    ?>
                    <input type="hidden" name="role" value="agent" />
                </div>
                <div class="col-lg-2">
                    <?php
                    echo $this->Form->submit(SEARCH, array(
                        'class' => 'btn btn-default search margin-l-m15',
                        'id' => 'btn-search-agent'));
                    ?>
                </div>
                <div class="col-lg-6 pull-right">
                    <?php
                    echo $this->Html->link(CREATE_NEW, '/admin/users/create?role=agent', array(
                        'class' => 'btn btn-primary pull-right margin-r-m15'));
                    ?>
                </div>
            </div>
            <div>&nbsp;</div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="col-lg-12">
        <table id='table-agent' class="table table-striped">
            <thead>
                <tr>
                    <th class="table-header"><?php echo $this->Paginator->sort('name', AGENT_NAME); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('created', CREATED); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('contact', CONTACT); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('status', STATUS); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><a href="javascript:void(0);"><?php echo OPERATION ?><a/></th>
                </tr>
            </thead>
            <?php if (isset($allUsers)): ?>
                <?php foreach ($allUsers as $key => $value): ?>
                    <?php
                    $status = '';
                    switch ($value['User']['status']) {
                        case 0:
                            $status = '<span class="label label-warning">' . DEACTIVATED . '</span>';
                            break;
                        case 1:
                            $status = '<span class="label label-success">' . ACTIVATED . '</span>';
                            break;
                        case 2:
                            $status = '<span class="label label-info">' . SUSPENSED . '</span>';
                            break;
                        case 3:
                            $status = '<span class="label label-danger">' . STOPPED . '</span>';
                            break;
                    }
                    ?>
                    <tr>
                        <td><?php echo $value['User']['name']; ?></td>
                        <td><?php echo $value['User']['created']; ?></td>
                        <td><?php echo $value['User']['contact']; ?></td>
                        <td><?php echo $status; ?></td>
                        <td style='font-size:13px;'>
                            <?php
                            echo $this->Html->link('<i class="fa fa-pencil-square-o"></i> ' . EDIT, '/admin/users/edit/' . $value['User']['id'] . '?role=agent', array(
                                'escape' => false));
                            echo str_repeat('&nbsp;', 4);
                            echo $this->Html->link('<i class="fa fa-info-circle"></i> ' . DISPLAY, '/admin/users/view/' . $value['User']['id'] . '?role=agent', array(
                                'escape' => false));
                            echo str_repeat('&nbsp;', 4);
                            echo$this->Html->link('<i class="fa fa-trash-o"></i> ' . DELETE, '/admin/users/delete/' . $value['User']['id'] . '?role=agent', array(
                                'escape' => false,
                                'confirm' => '本当に ' . $value['User']['name'] . ' を削除しますか？'));
                            echo str_repeat('&nbsp;', 4);
                            echo$this->Html->link('<i class="fa fa-check"></i> ' . ACTIVATE, '/admin/users/activate/' . $value['User']['id'] . '?role=agent', array(
                                'escape' => false,
                                'confirm' => '本当に ' . $value['User']['name'] . ' をアクティブにしますか？'));
                            echo '<br/>';
                            echo$this->Html->link('<i class="fa fa-shopping-cart"></i> ' . SHOP_OWNER_MANAGEMENT, '/admin/users?role=shop&agent_id=' . $value['User']['id'], array(
                                'escape' => false));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php echo $this->element('pagination'); ?>
        <?php endif; ?>
    </div>
</div>