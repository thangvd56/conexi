<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="page-header">アプリ作成 ＞ ウェブ予約</h1>        
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <?php echo $this->Flash->render(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <?php echo $this->Flash->render(); ?>
        <?php
            echo $this->Form->create('Shop', array(
                'role' => 'form',
                'class' => 'form-shop',
            ));

            if ($this->Session->read('Auth.User.role') == ROLE_HEADQUARTER) { ?>
                <div class="form-group">
                    <?php
                        echo $this->Form->input('id', array(
                            'type' => 'select',
                            'class' => 'form-control',
                            'label' => false,
                            'options' => $shops,
                            'id' => 'ShopSelectShop',
                            'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : '',
                        ));
                    ?>
                </div>
        <?php
            } else if ($this->Session->read('Auth.User.role') == ROLE_SHOP) {
                echo $this->Form->hidden('id', array(
                    'value' => $shop['Shop']['id']
                ));
            }
        ?>
            <div class="form-group">
                <label>リンク先URL</label>
                <?php
                    echo $this->Form->input('web_reservation', array(
                        'class' => 'form-control',
                        'label' => false,
                        'value' => $reservation_url
                    ));
                ?>
            </div>
            <div class="form-group">
                <?php
                    echo $this->Form->button('保存', array(
                        'class' => 'btn_save btn btn-block btn_color',
                        'type' => 'submit'
                    ));
                ?>
            </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#ShopSelectShop').on('change', function() {
            window.location.href = '<?php echo $this->Html->url('/shops/web_reservation?shop_id='); ?>' + $(this).val();
        });
    });
</script>