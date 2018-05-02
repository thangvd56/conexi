<?php echo $this->Flash->render(); ?>
<?php $data = (isset($this->request->data['Coupon'])) ? $this->request->data['Coupon'] : ''; ?>
<div class="row">
    <div class="col-lg-8 col-md-8"><h1 class="page-header">アプリ作成 ＞ クーポン一覧 ＞ クーポン詳細</h1></div>
</div>
<div class="row">
    <div class="col-lg-8 col-md-8">
        <?php
            echo $this->Form->create('Coupon', [
                'role' => 'form',
                'class' => 'form-horizontal',
                'name' => 'coupon_form',
                'type' => 'file',
                'id' => 'formCoupons'
            ]);

            echo $this->Form->hidden('notify_user', array('class' => 'send-user-notify', 'value' => 0));

            if ($this->Session->read('Auth.User.role') == ROLE_HEADQUARTER && $this->request->params['action'] == 'create') { ?>
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <?php
                        echo $this->Form->input('Coupon.shop_id', [
                            'type' => 'select',
                            'class' => 'form-control select_shop',
                            'label' => false,
                            'options' => $shops,
                            'default' => $this->request->query('shopId')
                        ]);
                    ?>
                </div>
            </div>
        <?php
            } else if ($this->Session->read('Auth.User.role') == ROLE_HEADQUARTER && $this->request->params['action'] == 'edit') {
                echo $this->Form->hidden('Coupon.shop_id', array('value' => $this->request->query('shop_id'), 'id' => false));
            }

            if ($this->request->params['action'] == 'edit') {
                echo $this->Form->hidden('Coupon.id', array('id' => false));
            }
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">タイトル</label>
            <div class="col-sm-10">
                <?php
                    echo $this->Form->input('Coupon.title', [
                        'label' => false,
                        'class' => 'form-control',
                        'div' => false
                    ]);
                ?>
                <span class="help-block help-errors hide error_title"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">本文</label>
            <div class="col-sm-10">
                <?php
                    echo $this->Form->input('Coupon.description', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'div' => false
                    ]);
                ?>
                <span class="help-block help-errors hide error_description"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">利用期限</label>
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-5">
                        <?php
                            echo $this->Form->input('Coupon.start_date',[
                                'type' => 'text',
                                'class' => 'form-control icon_select',
                                'label' => false,
                                'id' => 'startDate',
                                'value' => isset($data['start_date']) ? $data['start_date'] : '',
                            ]);
                        ?>
                        <span class="help-block help-errors hide error_start_date"></span>
                    </div>
                    <div style="display:inline; float:left; padding-top:12px">&nbsp;～&nbsp;</div>
                    <div class="col-sm-5">
                        <?php
                            echo $this->Form->input('Coupon.end_date', [
                                'type' => 'text',
                                'class' => 'form-control icon_select',
                                'label' => false,
                                'id' => 'endDate',
                                'value' => isset($data['end_date']) ? $data['end_date'] : '',
                            ]);
                        ?>
                        <span class="help-block help-errors hide error_end_date"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group" style="display: none;">
            <label class="col-sm-2 control-label">公開日</label>
            <div class="col-sm-4">
                <?php
                    echo $this->Form->hidden('Coupon.release_date', array(
                        'id' => 'releaseDate',
                        'value' => isset($data['release_date']) ? $data['release_date'] : ''
                    ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">注意事項</label>
            <div class="col-sm-10">
                <?php
                    echo $this->Form->input('Coupon.remark',[
                        'class' => 'form-control',
                        'label' => false,
                    ]);
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">画像</label>
            <div class="col-sm-10">
                <div id="add_media" class="select_img sel_img add_media">
                    <?php if ($this->request->params['action'] === 'edit') : ?>
                    <?php $url = $this->webroot . 'uploads/coupons/' . $data['image']; ?>
                    <img class="img-responsive" id="preview" src="<?php echo $url; ?>"/>
                    <?php else : ?>
                    <div id="center_word" class="text-center">
                        <h3>+</h3>
                        <p>画像を追加</p>
                    </div>
                    <img class="img-responsive" id="preview" src=""/>
                    <?php endif; ?>
                    <?php
                        echo $this->Form->input('Coupon.image', [
                            'class' => '',
                            'label' => false,
                            'type' => 'file',
                        ]);
                    ?>
                </div>
                <span class="help-block help-errors hide error_image"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <?php
                    echo $this->Form->submit('保存', [
                        'class' => 'btn btn-block btn_color',
                        'formnovalidate' => true,
                        'id' => 'btnSubmitCoupon'
                    ]);
                ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<?php echo $this->HtmlModal->modalHeader('modalConfirmSave', '', ' modal-sm'); ?>
<div class="row">
    <div class="col-xs-12">
        <p>クーポンを通知として送信しますか？</p>
        <p class="loading hide text-center">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </p>
    </div>
</div>
<div class="row common-top-space">
    <div class="col-xs-6 text-right">
        <button type="button" class="btn btn-default" id="saveWithoutNotify">通知せず保存</button>
    </div>
    <div class="col-xs-6">
        <button type="button" class="btn btn-default" id="saveWithNotify">はい</button>
    </div>
</div>
<?php echo $this->HtmlModal->modalFooter(); ?>

<?php echo $this->HtmlModal->modalNoHeader('modalCompletedSave', ' modal-sm'); ?>
<div class="row common-space">
    <div class="col-xs-12">
        <p>クーポンを通知として送信しました。</p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-center">
        <button type="button" class="btn btn-default btn-confirm-completed" data-dismiss="modal">OK</button>
    </div>
</div>
<?php echo $this->HtmlModal->modalFooter(); ?>

<style>
    .ui-widget-header {
        background: #fff !important;
        border: none !important;
    }
    .ui-datepicker-prev, .ui-datepicker-next , .ui-datepicker-prev:hover, .ui-datepicker-next:hover {
        background: #92C47D;
        border-radius: 50%;
    }
    .close {
        color: #848484;
        position: absolute;
        right: 3px;
        top: 0;
    }
    .modal-header .close {
        margin-top: 5px;
        margin-right: 5px;
    }
</style>

<script>
    $(function () {
        var dateToday = new Date();
        $('#startDate, #endDate, #releaseDate').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate : dateToday,
            beforeShow: function(textbox, instance) {
                $('#ui-datepicker-div').css({'padding': '20px', 'width': '30em'}).hide();
            }
        }, $.datepicker.regional['ja']).datepicker({
            'setDate': new Date()
        });

        $('body').on('change', '#startDate', function() {
            $('#releaseDate').val($(this).val());
        });

        $('body').on('click', '.select_img', function(e) {
            $(this).find('input[type="file"]').click();
        });

        $('body').on('click', '.select_img input', function(e) {
            e.stopPropagation();
        });

        $('body').on('change', '.select_img input', function(e) {
            var parent = $(this).closest('.select_img');
            var input = document.getElementById('CouponImage');
            var fReader = new FileReader();

            $(parent).find('#center_word').remove();
            fReader.readAsDataURL(input.files[0]);
            fReader.onloadend = function(event){
                var img = document.getElementById('preview');
                img.src = event.target.result;
            };
        });

        $('body').on('change', function(e) {
            $('body').find('#CouponShopId').val($('.select_shop').val());
        });

        $('body').on('click', '#btnSubmitCoupon', function(e) {
            e.preventDefault();
            $('#modalConfirmSave').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('body').on('click', '#saveWithNotify', function() {
            if ($('.send-user-notify').val(1)) {
                $('#formCoupons').submit();
            }
        });

        $('body').on('click', '#saveWithoutNotify', function() {
            if ($('.send-user-notify').val(0)) {
                $('#formCoupons').submit();
            }
        });
        
        $('#formCoupons').on('submit', function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $('.help-errors').addClass('hide').empty();
            $('.loading').addClass('hide');
            var url = '<?php echo Router::url(array('controller' => 'coupons', 'action' => 'create')); ?>';
            <?php
                if ($this->request->params['action'] == 'edit') { ?>
                    url = $('#formCoupons').attr('action');
            <?php }
            ?>
            $.ajax({
                url: url,
                cache: false,
                data: data,
                type: 'POST',
                dataType: 'JSON',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.loading').removeClass('hide');
                },
                success: function(response) {
                    $('#modalConfirmSave').modal('hide');
                    $('.loading').addClass('hide');
                    if (response.status === 'OK') {
                        if (parseInt($('.send-user-notify').val()) === 1) {
                            $('#modalCompletedSave').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                        } else {
                            location.replace(document.referrer);
                        }
                    } else if (response.status === 'ERROR') {
                        $.each(response.msg, function(key, value) {
                            $('.error_' + key).removeClass('hide').text(value[0]);
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (errorThrown === 'Forbidden') {
                        if (confirm('Session timeout. Please login again.')) {
                            location.reload();
                        }
                    }
                }
            });
        });

        $('body').on('click', '.btn-confirm-completed', function() {
            location.replace(document.referrer);
        });
    });
</script>