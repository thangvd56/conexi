
<div class="row">
    <div class="col-xs-12">
        <h1 class="page-header">店舗選択</h1>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 form-group">
        <?php
            echo $this->Form->create('shopList', [
                'type' => 'get',
                'class' => 'form-inline',
                'id' => 'form-search',
            ]);
        ?>
        <div class="form-group">
            <?php
                echo $this->Form->input('keyword', [
                    'label' => false,
                    'class' => 'form-control',
                    'placeholder' => '店舗名',
                    'value' => isset($this->request->query['keyword']) ? $this->request->query('keyword') : '',
                    'required' => false,
                ]);
            ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<?php if ($data) : ?>
<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>店舗名</th>
                    <th>店舗名(カナ)</th>
                    <th>TEL</th>
                    <th>E-mail</th>
                    <th>住所</th>
                    <th></th>
                </tr>
                <?php foreach ($data as $key => $value) : ?>
                <tr>
                    <td><?php echo $value['Shop']['id']; ?></td>
                    <td><?php echo $value['Shop']['shop_name']; ?></td>
                    <td><?php echo $value['Shop']['shop_kana']; ?></td>
                    <td><?php echo $value['Shop']['phone']; ?></td>
                    <td><?php echo $value['Shop']['email']; ?></td>
                    <td><?php echo $value['Shop']['address']; ?></td>
                    <td>
                        <?php 
                            echo $this->Html->link('基本情報', [
                                    'controller' => 'app_informations',
                                    'action' => 'index',
                                    "?" => array("shop_id" => $value['Shop']['id'])
                                ],[
                                    'class' => 'btn btn-default'
                                ]
                            );
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php echo $this->element('/pagination'); ?>
    </div>
</div>
<?php endif; ?>
