<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <h1 class="page-header">
            通知設定 ＞ 最終来店通知
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <ol class="breadcrumb">
            <li class="active">
                <p class="sub_header">最後にお客様が来店されてから指定の日数後に自動的に通知を送信します。</p>
            </li>
        </ol>
    </div>
</div>
<?php
    echo $this->Form->create('visiteNotification', array(
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

    $days = array();
    $times = array();
    for($i = 1; $i <= MAX_DAY; $i++) {
        $days[$i] = $i;
    }
    for($i = MIN_TIME; $i <= MAX_TIME; $i++) {
        if ($i < 10) {
            $times['0'.$i.':00:00'] = '0'.$i.':00';
        } else {
            $times[$i.':00:00'] = $i.':00';
        }
    }
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
    </div>
<?php if ($data) : ?>
<div class="lst-visit-notification">
    <?php foreach($data as $key => $value) : ?>
        <div class="visit-notification-item" data-key="<?php echo $key; ?>">
            <div class="form-group">
                <div class="col-sm-6 form-inline">
                    <label>来店から</label>
                    <?php
                        echo $this->Form->input('News.'.$key.'.duration', [
                            'type' => 'select',
                            'id' => 'day',
                            'class' => 'form-control news-duration',
                            'label' => false,
                            'options' => $days,
                            'value' => $value['News']['duration']
                        ]);
                    ?>
                    <label>日後</label>
                    <?php
                        echo $this->Form->input('News.'.$key.'.time', [
                            'type' => 'select',
                            'id' => 'time',
                            'class' => 'form-control news-time',
                            'label' => false,
                            'options' => $times,
                            'value' => $value['News']['time']
                        ]);
                    ?>
                    <label>時</label>
                </div>
                <div class="col-sm-2 text-right custom-toggle">
                    <?php
                        echo $this->Form->hidden('News.'.$key.'.id', array(
                            'value' => $value['News']['id'],
                            'class' => 'news-id'
                        ));
                        echo $this->Form->hidden('News.'.$key.'.is_disabled', array(
                            'value' => $value['News']['is_disabled'],
                            'id' => 'hidden-is-disabled'.$key,
                            'class' => 'news-is_disabled'
                        ));
                    ?>
                    <?php echo $this->Form->checkbox('News.'.$key.'.is_disabled', array(
                        'hiddenField' => false,
                        'name' => 'is-disabled-checkbox',
                        'id' => 'is-disabled'.$key,
                        'class' => 'form-control news-disabled',
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
                    <?php echo $this->Form->input('News.'.$key.'.title', array(
                        'class' => 'form-control news-title',
                        'label' => false,
                        'placeholder' => 'タイトル',
                        'value' => $value['News']['title']
                    )); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8">
                    <?php echo $this->Form->input('News.'.$key.'.message', array(
                        'type' => 'textarea',
                        'class' => 'form-control col-sm-8 news-message',
                        'label' => false,
                        'placeholder' => '本文',
                        'value' => $value['News']['message']
                    )); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 image-upload">
                    <label for="news-image<?php echo $key; ?>" class="upload-image news-image">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        <span class="glyphicon-class">画像選択</span>
                    </label>
                    <?php
                        echo $this->Form->input('News.'.$key.'.image', array(
                            'type' => 'file',
                            'class' => 'hidden news-file',
                            //'onchange'=> 'readURL(this);',
                            'id' => 'news-image'.$key,
                        ));
                        if (isset($value['News']) && 
                        isset($value['News']['image']) &&
                        $value['News']['image']) {
                            echo $this->Html->image(
                                Router::url('/', true).'uploads/news/'.$value['News']['image'], array(
                                'alt' => 'image',
                                'id' => "coupon-image".$key
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
        </div>
    <?php endforeach; ?>
</div>
<?php else : ?>
    <div class="lst-visit-notification">
        <div class="visit-notification-item" data-key="0">
            <div class="form-group">
                <div class="col-sm-6 form-inline">
                    <label>来店から</label>
                    <?php
                        echo $this->Form->input('News.0.duration', [
                            'type' => 'select',
                            'id' => 'day',
                            'class' => 'form-control news-duration',
                            'label' => false,
                            'options' => $days,
                        ]);
                    ?>
                    <label>日後</label>
                    <?php
                        echo $this->Form->input('News.0.time', [
                            'type' => 'select',
                            'id' => 'time',
                            'class' => 'form-control news-time',
                            'label' => false,
                            'options' => $times,
                        ]);
                    ?>
                    <label>時</label>
                </div>
                <div class="col-sm-2 text-right custom-toggle">
                    <?php 
                    echo $this->Form->hidden('News.0.id', array(
                        'class' => 'news-id'
                    ));
                    echo $this->Form->hidden('News.0.is_disabled', array(
                        'id' => 'hidden-is-disabled0',
                        'class' => 'news-is_disabled',
                        'value' => 1
                    ));
                    echo $this->Form->checkbox('News.0.is_disabled', array(
                        'hiddenField' => false,
                        'name' => 'is-disabled-checkbox',
                        'id' => 'is-disabled0',
                        'class' => 'form-control news-disabled',
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
                    <?php echo $this->Form->input('News.0.title', array(
                        'class' => 'form-control news-title',
                        'label' => false,
                        'placeholder' => 'タイトル',
                    )); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8">
                    <?php echo $this->Form->input('News.0.message', array(
                        'type' => 'textarea',
                        'class' => 'form-control col-sm-8  news-message',
                        'label' => false,
                        'placeholder' => '本文'
                    )); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 image-upload">
                    <label for="news-image0" class="upload-image news-image">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        <span class="glyphicon-class">画像選択</span>
                    </label>
                    <?php
                        echo $this->Form->input('News.0.image', array(
                            'type' => 'file',
                            'class' => 'hidden news-file',
                            //'onchange'=> 'readURL(this);',
                            'id' => 'news-image0',
                        ));
                        echo $this->Html->image(
                            '#', array(
                            'alt' => 'image',
                            'id' => "coupon-image0",
                            'class' => 'hidden'
                        ));
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <div class="form-group">
        <div class="col-sm-8 image-upload text-right">
            <?php if (count($data) <3) : ?>
            <button class="btn btn-default add-more" type="button" value="追加">追加</button>
            <?php endif; ?>
            <button class="btn btn-default" type="submit" value="保存">保存</button>
        </div>
    </div>

<?php echo $this->Form->end(); ?>

<script>
    $('body').on('change', '.news-file', function(){
        readURL(this);
    });

    function readURL(input) {
        
        if (input.files && input.files[0]) {
            
            var reader = new FileReader();
            reader.target_elem = $(input).parent().find('img');
//            reader.onload = function (e) {
//               $(reader.target_elem).attr('src', e.target.result);
//            };
            reader.onload = readSuccess;
            function readSuccess(evt) {
                $(reader.target_elem)
                    .attr('src', evt.target.result)
                    .removeClass('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    $('body').on('change', '.news-disabled', function() {
        $(this).parent().parent().find('.news-is_disabled').val($(this).prop('checked')?0:1);
    });

    $("#shop_id").change(function () {
        window.location.replace(URL + "news/visitNotification/?shop_id="+$(this).val());;
    });

    <?php if ($data) {
        foreach($data as $key1 => $value1) {
            if ($value1['News']['is_disabled']) {
                echo '$("#is-disabled'.$key1.'").bootstrapToggle("off");';
            } else {
                echo '$("#is-disabled'.$key1.'").bootstrapToggle("on");';
            }
        }
    }?>

    $('.add-more').click(function (){
        var items = $('.visit-notification-item');
        if ($(items).length >=2) {
            $(this).remove();
        }

        var clone = $('.visit-notification-item:last').clone();
        var key = $(clone).attr('data-key');
        key = parseInt(key)+1;
        $(clone).attr('data-key', key);
        $(clone).find('.news-id').val('').attr('name', 'data[News]['+key+'][id]').attr('id','News'+key+'Id');
        $(clone).find('.news-is_disabled').val(0).attr('name', 'data[News]['+key+'][is_disabled]').attr('id','hidden-is-disabled'+key);
        $(clone).find('.news-title').val('').attr('name', 'data[News]['+key+'][title]');
        $(clone).find('.news-message').val('').attr('name', 'data[News]['+key+'][message]');
        $(clone).find('.news-time').attr('name', 'data[News]['+key+'][time]');
        $(clone).find('.news-duration').val('1').attr('name', 'data[News]['+key+'][duration]');
        $(clone).find('.news-file')
                .attr('name', 'data[News]['+key+'][image]')
                .attr('id', 'news-image'+key);

        $(clone).find('.news-image').attr('for','news-image'+key);
        $(clone).find('img').attr('src', '').addClass('hidden');
        var checkbox = $(clone).find('input[type=checkbox]').attr('id', 'is-disabled'+key);
        var parents = $(checkbox).parent().parent();
        $(checkbox).parent().remove();
        $(parents).append(checkbox);
        $(checkbox).bootstrapToggle();
        $('.lst-visit-notification').append(clone);
    });
</script>