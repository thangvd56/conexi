
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-8">
        <h1 class="page-header">マスター_本社＆店舗紐付け</h1>
        <ol class="breadcrumb">
            <li class="active">
            店舗と結びつける本社アカウントを選択してください。
            </li>
        </ol>
        <?php echo $this->Flash->render(); ?>
    </div>
</div>
<div class="row">
    <form class="form-inline">
        <div class="from-group">
            <div class="col-xs-12">
                <input type="text" class="form-control" name="keyword"/>
                <input type="submit" class="btn btn-default" value="検索"/>
            </div>
        </div>
    </form>
</div>
<br>
<?php if($data) :?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-8">
            <table class="table table-striped">
                <tr>
                    <th>会社名</th>
                    <th>住所</th>
                    <th>TEL</th>
                    <th></th>
                </tr>
                <?php foreach ($data as $key => $value) : ?>
                    <tr>
                        <td><?php echo h($value['User']['company_name']); ?></td>
                        <td><?php echo h($value['User']['address']); ?></td>
                        <td><?php echo h($value['User']['contact']); ?></td>
                        <td><?php echo $this->Html->link('選択', array(
                                'controller' => 'Users',
                                'action' => 'selectUser',
                                '?' => array('headquarter_id' => $value['User']['id'])
                                ), array(
                                    'class' => 'btn btn-default',
                                    'data-id' => $value['User']['id'],
                                    'data-company-name' => h($value['User']['company_name']),
                                    'data-contact' => h($value['User']['contact']),
                                    'data-address' => h($value['User']['address'])
                            )); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
<?php endif;