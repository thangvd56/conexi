<!--
* admin_index
* Created 13/ November/2015
* Vanda
-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo GENRE_MANAGEMENT ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></a></li>
            <li class="active"><?php echo GENRE_MANAGEMENT ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php echo $this->Form->create('Genre'); ?>
        <fieldset>
            <div class="form-group">
                <!--                <div class="col-lg-4">
                                 // <?php //echo $this->Form->input('Search', array('class' => 'form-control margin-l-m15', 'label' => false, 'placeholder' => 'Search...'));     ?>
                                </div>
                                <div class="col-lg-2">
                                 //   <?php //echo $this->Form->submit(SEARCH, array('class' => 'btn btn-default margin-l-m15'));     ?>
                                </div>-->
                <div class="col-lg-6 pull-right">
                    <?php
                    echo $this->Html->link(CREATE_NEW, array(
                        'controller' => 'genres',
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
                    <th class="table-header"><?php echo $this->Paginator->sort('genre', GENRE_NAME); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('created', CREATED); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('remarks', REMARKS); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><a href="javascript:void(0);"><?php echo OPERATION ?><a/></th>
                </tr>
            </thead>
            <?php foreach ($allGenres as $key => $value): ?>
                <tr>
                    <td><?php echo $value['Genre']['genre']; ?></td>
                    <td><?php echo $value['Genre']['created']; ?></td>
                    <td><?php echo $value['Genre']['remarks']; ?></td>
                    <td style='font-size:13px;'>
                        <?php
                        echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>' . EDIT, array(
                            'controller' => 'genres',
                            'action' => 'admin_edit',
                            $value['Genre']['id']), array(
                            'escape' => false));
                        echo str_repeat('&nbsp;', 4);
                        echo $this->Html->link('<i class="fa fa-trash-o"></i>' . DELETE, array(
                            'controller' => 'genres',
                            'action' => 'admin_delete',
                            $value['Genre']['id']), array(
                            'escape' => false,
                            'confirm' => '本当に ' . $value['Genre']['genre'] . ' を削除しますか？'));
                        echo str_repeat('&nbsp;', 4);
                        echo $this->Html->link('<i class="fa fa-wrench"></i>' . SET_FUNCTION, '/admin/genre_functions?genre_id=' . $value['Genre']['id'], array(
                            'escape' => false));
                        echo str_repeat('&nbsp;', 4);
                        echo $this->Html->link('<i class="fa fa-tags"></i>' . TAG_MANAGEMENT, '/admin/tags?genre_id=' . $value['Genre']['id'], array(
                            'escape' => false));
                        echo str_repeat('&nbsp;', 4);
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $this->element('pagination'); ?>
    </div>
</div>