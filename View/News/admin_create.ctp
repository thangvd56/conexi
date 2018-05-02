<!--
* admin_create
* Created 13/ November/2015
* Channeth
-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo NEWS_MANEGEMENT_CREATE_NEW ?></h3>
    </div>
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><?php echo $this->Html->link(HOME, '/admin'); ?></li>
            <li><?php echo $this->Html->link(NEWS_MENAGEMENT_FOR_AGENTS_AND_SHOPS,
    '/admin/news'); ?></li>
            <li class="active"><?php echo CREATE_NEW ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <?php
        echo $this->Form->create('News',
            array(
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
            'type' => 'file'));
        ?>
        <fieldset>
            <div class="form-group">
                <label class="control-label col-sm-2" for="notification_type"><?php echo NOTICE_TYPE ?> :</label>
                <div class="col-sm-8">
                    <?php
                    $notice_type = array(
                        'Function addition' => '機能追加',
                        'Change'  => '変更',
                        'Trouble' => '不具合',
                        'Deals'   => 'お得情報',
                        'other'   => 'その他'
                        );
                    echo $this->Form->select('notice_type', $notice_type,
                        array(
                        'class' => 'form-control',
                        'id'    => 'notification_type',
                        'empty' => array(
                            ''  => '--Select--')));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="title"><?php echo NEW_TITLE ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('title',
                        array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'title'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="message"><?php echo CONTENT ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('message',
                        array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'message',
                        'type' => 'textarea'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="target"><?php echo TARGET ?> :</label>
                <div class="col-sm-8">
                    <?php
                    $target = array(
                        'agent' => AGENT,
                        'shop' => SHOP);
                    echo $this->Form->select('target', $target,
                        array(
                        'class' => 'form-control',
                        'id' => 'target',
                        'empty' => array(
                            '' => '--Select Target--')));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="target"><?php echo SERVICE_NAME; ?> :</label>
                <div class="col-sm-8">
<?php
echo $this->Form->input('service_name',
    array(
    'class' => 'form-control',
    'label' => false,
    'id' => 'service_name'));
?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="file"><?php echo MEDIA ?> :</label>
                <div class="col-sm-3">
                    <?php
                    echo $this->Form->input('file',
                        array(
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'file',
                        'type' => 'file',
                        'required' => true));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="start"><?php echo TIME ?> :</label>
                <div class="col-sm-3">
                    <?php
                    echo $this->Form->input('start',
                        array(
                        'class' => 'form-control',
                        'label' => false,
                        'id' => 'start',
                        'type' => 'text'));
                    ?>
                </div>
                <div class="col-sm-3">
<?php
echo $this->Form->input('end',
    array(
    'class' => 'form-control',
    'label' => false,
    'id' => 'end',
    'type' => 'text'));
?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="url"><?php echo URL ?> :</label>
                <div class="col-sm-8">
                    <?php
                    echo $this->Form->input('url',
                        array(
                        'class' => 'form-control ',
                        'label' => false,
                        'id' => 'url'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="NewsVisible"><?php echo VISIBLE ?> :</label>
                <div class="col-sm-1">
                    <?php
                    echo $this->Form->checkbox('visible',
                        array(
                        'class' => 'form-control width33',));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-4">
<?php
echo $this->Form->submit(CREATE_NEW,
    array(
    'class' => 'btn btn-primary margin-top-10'));
?>
                </div>
            </div>
        </fieldset>
<?php echo $this->Form->end(); ?>
    </div>
</div>
<script type="text/javascript">
    $('#end').timepicker();
    $('#start').timepicker();
</script>