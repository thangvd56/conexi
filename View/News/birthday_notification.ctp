<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <h1 class="page-header">
            通知設定 ＞ 誕生月通知
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <ol class="breadcrumb">
            <li class="active">
                <p class="sub_header">
                    お客様の誕生月の1日に自動送信される文章です。<br/>
                    誕生月に割引を行う場合、特典を記入しましょう。
                </p>
            </li>
        </ol>
    </div>
</div>
<?php
    echo $this->Form->create('couponNotification', array(
        'class' => 'form-horizontal',
        'novalidate' => true,
        'autocomplete' => 'off',
        'inputDefaults' => array(
            'legend' => false,
            'label' => false,
            'div' => false,
            'class' => 'form-control'),
        'enctype' => "multipart/form-data"
    ));
    echo $this->Form->hidden('News.id');
    echo $this->Form->hidden('News.is_disabled', array(
        'id' => 'hidden-is-disabled',
        'value' => isset($this->request->data['News']) &&
        isset($this->request->data['News']['is_disabled']) &&
        $this->request->data['News']['is_disabled'] ? 1 : 0
    ));
?> 
    <div class="form-group">
        <div class="col-sm-6">
        <?php if ($this->Session->read('Auth.User.role') === ROLE_HEADQUARTER) : ?>
            <?php
                echo $this->Form->input('shop_id', [
                    'type' => 'select',
                    'id' => 'shop_id',
                    'name' => 'shop_id',
                    'class' => 'form-control',
                    'label' => false,
                    'options' => $shops,
                    'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : '',
                ]);
            ?>
        <?php endif; ?>
        </div>
        <div class="col-sm-2 text-right custom-toggle">
            <?php echo $this->Form->checkbox('News.is_disabled', array(
                'hiddenField' => false,
                'name' => 'is-disabled-checkbox',
                'id' => 'is-disabled',
                'class' => 'form-control',
                'label' => false,
                'data-toggle' => 'toggle',
                'data-onstyle' => 'warning',
                'data-on' => '公開 <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>',
                'data-off' => '非公開 <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>'
            ));?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-8">
            <?php echo $this->Form->input('News.title', array(
                'class' => 'form-control',
                'label' => false,
                'placeholder' => 'タイトル'
            )); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8">
            <?php echo $this->Form->input('News.message', array(
                'type' => 'textarea',
                'class' => 'form-control col-sm-8',
                'label' => false,
                'placeholder' => '本文'
            )); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8 image-upload">
            <label for="news-image" class="upload-image">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                <span class="glyphicon-class">画像選択</span>
            </label>
            <?php
                echo $this->Form->input('News.image', array(
                    'type' => 'file',
                    'class' => 'hidden',
                    'onchange'=> 'readURL(this);',
                    'id' => 'news-image',
                ));
                if (isset($this->request->data['News']) && 
                isset($this->request->data['News']['image']) &&
                $this->request->data['News']['image']) {
                    echo $this->Html->image(
                        Router::url('/', true).'uploads/news/'.$this->request->data['News']['image'], array(
                        'alt' => 'image',
                        'id' => "coupon-image"
                    ));
                } else {
                    echo $this->Html->image(
                        '#', array(
                        'alt' => 'image',
                        'id' => "coupon-image",
                        'class' => 'hidden'
                    ));
                }
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8 image-upload">
            <button class="btn btn-default pull-right" type="submit" value="保存">保存</button>
        </div>
    </div>
<?php echo $this->Form->end(); ?>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#coupon-image')
                    .attr('src', e.target.result)
                    .width(100)
                    .removeClass('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    $('#is-disabled').change(function() {
        $('#hidden-is-disabled').val($(this).prop('checked')?0:1);
    });
    $("#shop_id").change(function () {
        window.location.replace(URL + "news/birthdayNotification/?shop_id="+$(this).val());;
    });
    <?php
        if (isset($this->request->data['News']) &&
            isset($this->request->data['News']['is_disabled']) &&
            $this->request->data['News']['is_disabled']) {
            echo '$("#is-disabled").bootstrapToggle("off")';
        } else {
            echo '$("#is-disabled").bootstrapToggle("on")';
        }
    ?>
</script>