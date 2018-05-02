
<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">店舗ID管理</h1>
        <?php echo $this->Flash->render(); ?>
        <pre>店舗と本社の紐付けをご確認くださいませ。</pre>
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
<?php if(isset($data)) :?>
    <?php echo $this->form->create('User', array('action' => 'confirm')); ?>
        <?php echo $this->Form->input('rejected', array(
            'type' => 'hidden',
            'value' => 0,
            array('id' => 'rejected')
        )); ?>
        <div class="row">
            <div class="col-md-12">
                <input type="checkbox" id="check-all"><label for="check-all">全て選択</label>
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
                            <td><?php echo $this->Form->input('user_id.'.$key, array(
                                    'type' => 'checkbox',
                                    'class' => 'user_id',
                                    'hiddenField' => false,
                                    'value' => $value['Shop']['user_id'],
                                    'label' => false,
                                    'div' => false
                                )); ?>
                            </td>
                            <td><?php echo h($value['Shop']['shop_name']); ?></td>
                            <td><?php echo h($value['Shop']['address']); ?></td>
                            <td><?php echo h($value['Shop']['phone']); ?></td>
                            <td>
                                <?php echo $this->Html->link('情報閲覧', array(
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
            <div class="col-md-12 text-center">
                <input type="button" class="btn btn-default btn-reject" value="拒否">
                <input type="button" class="btn btn-default btn-confirm" value="承認">
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
    $('.btn-reject').click(function(){
        $('#rejected').val(1);
        $('#UserConfirmForm').submit();
    });
    $('.btn-confirm').click(function(){
        $('#rejected').val(0);
        $('#UserConfirmForm').submit();
    });
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
    $('#check-all').change(function() {
        if($(this).prop('checked')) {
            $('.user_id').prop('checked', true);
        } else {
            $('.user_id').prop('checked', false);
        }
    });
</script>