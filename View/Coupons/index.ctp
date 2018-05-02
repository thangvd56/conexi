<div class="row">
    <div class="col-xs-12 col-lg-8">
        <h1 class="page-header">アプリ作成 ＞ クーポン一覧</h1>
        <?php echo $this->Flash->render(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-lg-8">
        <?php if ($this->Session->read('Auth.User.role') === ROLE_HEADQUARTER) : ?>
        <?php
            echo $this->Form->create('Coupons', [
                'class' => 'form-inline form-shop',
                'role' => 'form',
                'action' => 'index',
            ]);
        ?>
        <div class="form-group">
            <?php
                echo $this->Form->input('shop_id', [
                    'type' => 'select',
                    'class' => 'form-control',
                    'label' => false,
                    'options' => $shops,
                    'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : '',
                ]);
            ?>
        </div>
        <?php echo $this->Form->end(); ?>
        <?php endif; ?>
    </div>
</div><br>
<div clas="row">
    <div class="col-xs-12 col-lg-8" style="padding: 0px;">
        <div class="coupon-list">
            <?php if ($coupons) : foreach ($coupons as $item) : ?>
            <div class="coupon-wrap">
                <div class="col-lg-4 col-sm-4">
                    <?php echo $this->Html->image(
                        '/uploads/coupons/' . $item['Coupon']['image'], [
                            'class' => 'img-responsive',
                        ]);
                    ?>
                </div>
                <div class="col-lg-8 col-sm-8">
                    <div class="col-lg-8 col-sm-8">
                        <header>
                            <h2><?php echo $item['Coupon']['title']; ?></h2>
                            <p>
                                <?php
                                    $date = strtotime($item['Coupon']['end_date']);
                                    echo Date('Y-m-d', $date);
                                ?>
                            </p>
                        </header>
                    </div>
                    <div class="col-md-4 col-lg-4 pull-right" data-id="<?php echo $item['Coupon']['id']; ?>" data-shop="<?php echo $item['Coupon']['shop_id']; ?>">
                        <button class="btn btn-block btn_color btn_coupon" data-target="edit">編集</button>
                        <button class="btn btn-block btn_color btn_coupon" data-target="delete">削除</button>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-lg-8 col-md-12 col-sm-12 text-center">
        <div class="panel dotted-border" id="btn_add">
            <?php
                echo $this->Html->link('+ クーポン新規追加', array(
                    'controller' => 'coupons',
                    'action' => 'create',
                ), array(
                    'id' => 'btn_coupon',
                    'class' => 'create-new-coupon'
                )
                );
            ?>
        </div>
    </div>
</div>
<div class="modal fade" id="comfirm_message" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">確認</h4>
            </div>
            <div class="modal-body">本当にこのデータを削除しますか？</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-primary delete">Ok</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        <?php if ($this->Session->read('Auth.User.role') === ROLE_HEADQUARTER) { ?>
        $('.create-new-coupon').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var shopId = $('#CouponsShopId').val();
            location.href = url + '/?shopId=' + shopId;
        });
        <?php } ?>

        $('body').on('click', '.btn_coupon', function(e) {
            var id = $(this).parent('div').data('id');
            var shopId = $(this).parent('div').data('shop');
            if ($(this).data('target') == 'edit') {
                window.location.href = '<?php echo $this->Html->url('/coupons/edit/'); ?>' + id + '?shop_id=' + shopId;
            } else {
                $('#comfirm_message').find('.delete').attr('data-id', id);
                $('#comfirm_message').modal('show');
            }
        });

        $('body').on('click', '.delete', function(e) {
            window.location.href = '<?php echo $this->Html->url('/coupons/delete/'); ?>' + $(this).data('id');
        });

        $('body').on('change', function(e) {
            window.location.replace(URL + 'coupons/?shop_id=' + $('#CouponsShopId').val());
        });
    });
</script>