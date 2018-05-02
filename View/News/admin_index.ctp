<!--
* admin_index
* Created 13/ November/2015
* Channeth
-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo NEWS_MENAGEMENT_FOR_AGENTS_AND_SHOPS ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></li>
            <li class="active"><?php echo NEWS_MENAGEMENT_FOR_AGENTS_AND_SHOPS ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <fieldset>
            <div class="form-group">
                <div class="col-lg-12 pull-right">
                    <?php
                    echo $this->Html->link(CREATE_NEW, '/admin/news/create', array(
                        'class' => 'btn btn-primary pull-right margin-r-m15'));
                    ?>
                </div>
            </div>
            <div>&nbsp;</div>
        </fieldset>
    </div>
    <div class="col-lg-12">
        <table id='table-agent' class="table table-striped">
            <thead>
                <tr>
                    <th class="table-header"><?php echo $this->Paginator->sort('title', NEW_TITLE); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('created', CREATED); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><?php echo $this->Paginator->sort('message', CONTENT); ?>&nbsp;<i class="fa fa-sort"></i></th>
                    <th class="table-header"><a href="javascript:void(0);"><?php echo OPERATION ?><a/></th>
                </tr>
            </thead>
            <?php if (isset($news)): ?>
                <?php foreach ($news as $key => $value): ?>
                    <tr>
                        <td><?php echo $value['News']['title']; ?></td>
                        <td><?php echo $value['News']['created']; ?></td>
                        <td><?php echo $value['News']['message']; ?></td>
                        <td style='font-size:13px;'>
                            <?php
                            echo$this->Html->link('<i class="fa fa-pencil-square-o"></i>' . EDIT, '/admin/news/edit/' . $value['News']['id'], array(
                                'escape' => false));
                            echo str_repeat('&nbsp;', 4);
                            echo$this->Html->link('<i class="fa fa-trash-o"></i>' . DELETE, '/admin/news/delete/' . $value['News']['id'], array(
                                'escape' => false,
                                'confirm' => '本当に ' . $value['News']['title'] . ' を削除しますか？'));
                            echo str_repeat('&nbsp;', 4);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php echo $this->element('pagination'); ?>
        <?php endif; ?>
    </div>
</div>
