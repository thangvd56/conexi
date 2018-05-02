
<?php echo $this->Html->css('pastNotice'); ?>

<style>
    .group-wrap {
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <h1 class="page-header">店舗管理 ＞ スタンプ管理</h1>
        </div>
    </div>
    <?php if ($groups) : foreach ($groups as $value) : ;?>
    <div class="row group-wrap">
        <div class="col-md-4 col-lg-4 col-xs-12">
            <?php
                echo $this->Html->link($value['Group']['name'],
                    array(
                        'controller' => 'stamp_settings',
                        'action' => 'update_all_shop',
                        '?' => array(
                            'group_id' => $value['Group']['id'],
                        )
                    ), array(
                        'class' => 'btn btn_color',
                        'style' => 'width: 100%;',
                    )
                );
            ?>
        </div>
    </div>
    <?php endforeach; endif; ?>
</div>
