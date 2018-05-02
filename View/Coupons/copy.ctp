
<div class="row">
    <div class="col-xs-12">
        <h1 class="page-header">店舗管理　＞　クーポンコピー</h1>
        <?php echo $this->Flash->render(); ?>
    </div>
</div>

<?php 
echo $this->Form->create('Coupon', array(
    'class' => 'form',
));
?>
<div class="row">
    <div class="col-xs-6"><h4>クーポンのコピー元を選択してください。</h4></div>
    <div class="col-xs-6"><h4>クーポンのコピー先を選択してください。</h4></div>
</div>
<?php foreach ($data as $key => $value) : ?>
<div class="row">
    <div class="col-xs-6">
        <pre>
            <?php echo $value['Group']['name'];
                echo $this->Form->hidden($key.'.group_id', array(
                    'value' => $value['Group']['id']
                ));
            ?>
        </pre>
        <div class="group-adding">
        <?php
            foreach ($value['Shops'] as $key2 => $value2) {
                echo $this->Form->input($key.'.fromShop', array(
                    'type' => 'radio',
                    'hiddenField' => false,
                    'escape' => false,
                    'options' => array(
                        $value2['Shop']['id'] => $value2['Shop']['shop_name']
                    ),
                    'class' => 'fromShop'
                ));
            }
        ?>
        </div>
    </div>
    <div class="col-xs-6">
        <pre><?php echo $value['Group']['name']; ?></pre>
        <div class = "group-adding">
        <ul>
            <li class="check-all">全選択</li>
            <li class="uncheck-all">全解除</li>
        </ul>
        <?php
            echo $this->Form->hidden($key.'.copy_all');
            foreach ($value['Shops'] as $key2 => $value2) {
                echo $this->Form->input($key.'.toShop.'.$key2, array(
                    'type' => 'checkbox',
                    'class' => 'toShop-checkbox toShop',
                    'hiddenField' => false,
                    'value' => $value2['Shop']['id'],
                    'label' => $value2['Shop']['shop_name']
                ));
            }
        ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
<div class="row">
    <div class="col-xs-12 text-center">
        <input type="button" class="btn copy-history btn_color" value="コピー履歴"/>
        <input type="button" class="btn submit btn_color" value="コピー実行" data-toggle="modal" data-target="#confirm-modal">
    </div>
</div>

<?php echo $this->Form->end(); ?>
<div id="confirm-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label>本当によろしいですか？</label>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal">
                    <input type="button" id="btn_confirm" value="はい" class="btn btn-success color back_color but_design">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="history-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="data"></div>
                <div class="text-center">
                    <input type="button" value="閉じる" class="btn btn-success color back but_design2" data-dismiss="modal">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.fromShop').on('click', function() {
            $('.toShop').parent().show();
            if ($(this).is(':checked')) {
                var id = parseInt($(this).val());
                $('.toShop').each(function() {
                    if (parseInt($(this).val()) === id) {
                        $(this).parent().hide();
                    }
                });
            }
        });

        $('.check-all').click(function() {
            $(this).parent().parent().find('.toShop-checkbox').prop('checked', true);
            $(this).parent().parent().find(':hidden').val(1);
        });
        
        $('.uncheck-all').click(function() {
            $(this).parent().parent().find('.toShop-checkbox').prop('checked', false);
            $(this).parent().parent().find(':hidden').val(0);
        });

        $('.toShop-checkbox').change(function() {
            var checkboxs = $(this).parent().parent().find('.toShop-checkbox');
            $.each($(checkboxs), function( index, value ) {
                if($(this).is(':checked')) {
                    $(this).parent().parent().find(':hidden').val(1);
                } else {
                    $(this).parent().parent().find(':hidden').val(0);
                    return false;
                }
            });
        });

        $('#confirm-modal #btn_confirm').click(function() {
            $("#CouponCopyForm").submit();
        });

        $('.copy-history').click(function() {
            $.ajax({
                url: URL + 'coupons/copy_log/',
                method: 'POST',
                data: {},
                dataType: 'html',
                success: function(data) {
                    $('#history-modal .data').html(data);
                    $('#history-modal').modal('show');
                    $('[data-toggle="popover"]').popover();
                }
            });
        });

        $('#history-modal').on('click', '.popover .btn-ok', function() {
            $.ajax({
                url: URL + 'coupons/rolback/',
                method: 'POST',
                data: {'copy_id': $(this).attr('data-copy-id'), 'shop_id': $(this).attr('data-shop-id')},
                dataType: 'html',
                success: function(data) {
                    window.location = URL + 'coupons/copy/';
                }
            });
        });

        $('.modal-dialog').on('click', '.popover .btn-no', function() {
            var popover = $(this).parent().parent().parent().find('[data-toggle="popover"]');
            $(popover).trigger('click');
        });
    });
</script>