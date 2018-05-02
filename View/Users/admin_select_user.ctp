
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-8">
        <h1 class="page-header">マスター_本社＆店舗紐付け</h1>
        <ol class="breadcrumb">
            <li class="active">
                <?php echo $headquarter_user['User']['company_name'] ?> と結びつける「店舗」を選択してください。
            </li>
        </ol>
    </div>
</div>

<div class="row">
    <form class="form-inline">
        <input type="hidden" name="headquarter_id" value="<?php if ($this->request->query('headquarter_id')) {
            echo $this->request->query('headquarter_id');} ?>">
        <div class="from-group">
            <div class="col-xs-12">
                <input type="text" class="form-control" name="keyword"/>
                <input type="submit" class="btn btn-default" value="検索"/>
            </div>
        </div>
    </form>
</div>
<br>
<?php if(isset($data)) :?>
    <?php echo $this->form->create('User', array('action' => 'admin_confirm')); ?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-8">
                <?php if ($this->request->query('headquarter_id')) {
                    echo $this->Form->input('headquarter_id', array(
                        'type' => 'hidden',
                        'value' => $this->request->query('headquarter_id')
                    ));
                } ?>
                <table class="table table-striped">
                    <tr>
                        <th></th>
                        <th>会社名</th>
                        <th>住所</th>
                        <th>TEL</th>
                        <th></th>
                    </tr>
                    <?php foreach ($data as $key => $value) : 
                        if (!$value['Shop']['shop_name']) {
                            continue;
                        }
                    ?>
                        <tr>
                            <?php echo $this->Form->input('user_id.'.$key, array(
                                'type' => 'hidden',
                                'class' => 'user_id',
                                'value' => $value['Shop']['user_id'],
                            )); ?>
                            <td><?php echo $this->Form->input('shop_id.'.$key, array(
                                    'type' => 'checkbox',
                                    'class' => 'user_id',
                                    'hiddenField' => false,
                                    'value' => $value['Shop']['id'],
                                    'label' => false,
                                    'div' => false
                                )); ?>
                            </td>
                            <td><?php echo h($value['Shop']['shop_name']); ?></td>
                            <td><?php echo h($value['Shop']['address']); ?></td>
                            <td><?php echo h($value['Shop']['phone']); ?></td>
                            <td><?php echo $this->Html->link('情報閲覧', array(
                                    'controller'=>'Shops',
                                    'action'=>'view',$value['Shop']['id']
                                    ), array(
                                        'class' => 'btn btn-default shop-view',
                                        'data-shop-id' => $value['Shop']['id']
                                )); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-8 text-center">
                <?php echo $this->Html->link('戻る', array(
                    'controller'=>'Users',
                    'action'=>'headquarter'
                    ), array(
                        'class' => 'btn btn-default'
                )); ?>
                <input type="submit" class="btn btn-default" value="選択">
            </div>
        </div>
    <?php echo $this->form->end(); ?>
<div id="view-shop" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="modal-data text-center"></div>
                <div class="text-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endif; ?>

<script>
    $('.shop-view').click(function(e){
        e.preventDefault();
        $.ajax({
            url: '<?php echo Router::url('/', true); ?>shops/view/'+$(this).attr('data-shop-id'),
            method: 'get',
            data: {},
            dataType: 'html',
            success: function(data) {
                $('#view-shop .modal-data').html(data);
                $('#view-shop').modal('show');
            }
        });
    });
</script>