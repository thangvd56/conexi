
<div class="row">
    <div class="col-md-12 col-sm-12 col-lg-8">
        <h1 class="page-header">マスター_本社＆店舗紐付け</h1>
    </div>
</div>
<?php if(isset($selected_shop)) : ?>
    <?php echo $this->form->create(); ?>
    <div class="row">
        <div class="col-xs-6">
            <div class="panel panel-success panel-headquarter">
                <div class="panel-heading">本社アカウント
                    <?php echo $this->Form->input('headquarter_id', array(
                        'type' => 'hidden',
                        'value' => $headquarter_user['User']['id']
                    )); ?>
                    <?php echo $this->Form->input('save', array(
                        'type' => 'hidden',
                        'value' => 1
                    )); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>店舗名</th>
                            <th>住所</th>
                            <th>TEL</th>
                        </tr>
                        </thead>
                        <tr>
                            <td><?php echo h($headquarter_user['User']['company_name']); ?></td>
                            <td><?php echo h($headquarter_user['User']['address']); ?></td>
                            <td><?php echo h($headquarter_user['User']['contact']); ?></td>
                        </tr>
                    </table>
                    <div class="text-center">
                        <input type="button" class="btn btn-default reselect-headquarter" value="再選択">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="panel panel-success panel-shop">
                <div class="panel-heading">店舗アカウント</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>店舗名</th>
                                <th>住所</th>
                                <th>TEL</th>
                            </tr>
                        </thead>
                    <?php foreach($selected_shop as $key => $value) : ?>
                        <tr>
                            <?php echo $this->Form->input('user_id.'.$key, array(
                                'type' => 'hidden',
                                'class' => 'user_id',
                                'value' => $value['Shop']['user_id'],
                            )); ?>
                            <td><?php echo h($value['Shop']['shop_name']); ?></td>
                            <td><?php echo h($value['Shop']['address']); ?></td>
                            <td><?php echo h($value['Shop']['phone']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                    <div class="text-center">
                        <input type="button" class="btn btn-default reselect-shop" value="再選択">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <input type="button" class="btn btn-default" value="戻る">
            <input type="submit" class="btn btn-default" value="保存">
        </div>
    </div>
    
<?php echo $this->form->end(); 
endif; ?>
<div id="modal-for-headquarter" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">店舗と結びつける本社アカウントを選択してください。</h4>
                </div>
                <div class="modal-data"></div>
                <div class="text-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal-for-shops" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">「店舗」を選択してください。</h4>
                </div>
                <div class="modal-data"></div>
                <div class="text-center">
                    
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    <button type="button" class="btn btn-default btn-select" data-dismiss="modal">選択</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    $('.reselect-headquarter').click(function(){
        $.ajax({
            url: '<?php echo Router::url('/', true); ?>admin/users/headquarter/',
            method: 'POST',
            data: {},
            dataType: 'html',
            success: function(data) {
                $('#modal-for-headquarter .modal-data').html($(data).find('table'));
                $('#modal-for-headquarter').modal('show');
            }
        });
    });

    $('.reselect-shop').click(function(){
        $.ajax({
            url: '<?php echo Router::url('/', true); ?>admin/users/selectUser/?headquarter_id='+$('#headquarter_id').val(),
            method: 'POST',
            data: {},
            dataType: 'html',
            success: function(data) {
                $('#modal-for-shops .modal-data').html($(data).find('table'));
                $('#modal-for-shops .modal-data table').find('a.btn').parent('td').remove();
                $('#modal-for-shops').modal('show');
            }
        });
    });

    $('#modal-for-headquarter').on('click', 'a', function(){
        event.preventDefault();
        var td = $('.panel-headquarter tbody td');
        $(td[0]).html($(this).attr('data-company-name'));
        $(td[1]).html($(this).attr('data-address'));
        $(td[2]).html($(this).attr('data-contact'));
        $('#headquarter_id').val($(this).attr('data-id'));
        $('#modal-for-headquarter').modal('hide');
    });

    $('#modal-for-shops').on('click', '.btn-select', function(){
       var selected_shops = $("#modal-for-shops").find("input:checked");
       if (selected_shops.length > 0) {
           $('.panel-shop table tbody').html('');
           $(selected_shops).each(function(index, value){
               var tr = $(value).parent().parent();
               $(tr).find('td').first().remove();
               $(tr).append($('<input type="hidden" name="data[shop_id]['+index+']">').val($(value).val()));
               $('.panel-shop table tbody').append(tr);
           });
       }
    });
</script>