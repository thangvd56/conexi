
<?php
    echo $this->Form->create('News', array(
        'class' => 'form-horizontal',
        'id' => 'notification_form',
        'role' => 'form',
        'type' => 'file',
        'enctype' => "multipart/form-data"
    ));
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <h1 class="page-header">
            店舗管理 &gt; 通知一括送信
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <p class="sub_header">こちらの通知は、全体へのお知らせや絞り込みの通知に使えます。</p>
            </li>
        </ol>
        <?php echo $this->Flash->render(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">配信先対象</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('destination_target', array(
                        'class' => 'form-control icon_select select_val',
                        'id' => 'destination_target',
                        'type' => 'select',
                        'label' => false,
                        'div' => false,
                        'options' => array(
                            'all' => '全体',
                            'filter' => '絞り込み',
                        )
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">グル</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('group_id', array(
                        'class' => 'form-control icon_select',
                        'id' => 'group_id',
                        'type' => 'select',
                        'label' => false,
                        'div' => false,
                        'options' => $group_option
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row row-shop-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">店舗選択</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('shop_id', array(
                        'class' => 'form-control icon_select',
                        'id' => 'shop_id',
                        'type' => 'select',
                        'label' => false,
                        'div' => false,
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row row-shop-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">性別</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('gender', array(
                        'class' => 'form-control icon_select',
                        'id' => 'gender',
                        'type' => 'select',
                        'label' => false,
                        'div' => false,
                        'options' => array(
                            '男性' => '男性',
                            '女性' => '女性',
                            '全て対象' => '全て対象',
                        )
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">通知対象</p></div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-6">
                <div id="radio">
                    <label for="checkbox1">
                        <input type="checkbox"
                               class="mytarget" value="1-19"
                               name="data[News][age][0]" id="checkbox1"
                               <?php echo isset($this->request->data['News']['age'][0]) ? 'checked' : ''; ?>
                        > <span>～19歳</span>
                    </label>
                    <label for="checkbox2">
                        <input type="checkbox" 
                            class="mytarget" value="20-29"
                            name="data[News][age][1]" id="checkbox2"
                            <?php echo isset($this->request->data['News']['age'][1]) ? 'checked' : ''; ?>
                        > <span>20～29歳</span>
                    </label>
                    <label for="checkbox3">
                        <input type="checkbox" class="mytarget" value="30-39"
                               name="data[News][age][2]" id="checkbox3"
                               <?php echo isset($this->request->data['News']['age'][2]) ? 'checked' : ''; ?>
                        > <span>30～39歳</span>
                    </label>
                    <label for="checkbox4">
                        <input type="checkbox" class="mytarget"
                               value="40-49" name="data[News][age][3]" id="checkbox4"
                               <?php echo isset($this->request->data['News']['age'][3]) ? 'checked' : ''; ?>
                        > <span>40～49歳</span>
                    </label>
                    <label for="checkbox5">
                        <input type="checkbox" class="mytarget" value="50-59"
                               name="data[News][age][4]" id="checkbox5"
                               <?php echo isset($this->request->data['News']['age'][4]) ? 'checked' : ''; ?>
                        > <span>50～59歳</span>
                    </label>
                    <label for="checkbox6">
                        <input type="checkbox" class="mytarget" value="60-60"
                               name="data[News][age][5]" id="checkbox6"
                               <?php echo isset($this->request->data['News']['age'][5]) ? 'checked' : ''; ?>
                        > <span>60歳～</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">地域 </p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('selection', array(
                        'class' => 'form-control icon_select',
                        'id' => 'area',
                        'type' => 'select',
                        'label' => false,
                        'div' => false,
                        'options' => $area_option ? $area_option : array(),
                        'empty' => '全ての地域'
                    ));
                    echo $this->Form->input('area', array(
                        'type' => 'hidden',
                        'value' => isset($this->request->data['News']['area']) ? $this->request->data['News']['area'] : ''
                    ));
                ?>
                <div class="selected-area"></div>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <input type="button" id="btnarea" class="btn success" value="地域追加">
            </div>
        </div>
    </div>
</div>
<div class="row row-display">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 target">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">通知日</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('date_picker', array(
                        'class' => 'form-control',
                        'label' => false,
                        'div' => false,
                        'id' => 'date_picker',
                        'required' => 'required',
                        'value' => isset($this->request->data['News']['date_picker']) ? $this->request->data['News']['date_picker'] : ''
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">時間</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    $options = array(
                        '10:00:00' => '10:00',
                        '10:30:00' => '10:30',
                        '11:00:00' => '11:00',
                        '11:30:00' => '11:30',
                        '12:00:00' => '12:00',
                        '12:30:00' => '12:30',
                        '13:00:00' => '13:00',
                        '13:30:00' => '13:30',
                        '14:00:00' => '14:00',
                        '14:30:00' => '14:30',
                        '15:00:00' => '15:00',
                        '15:30:00' => '15:30',
                        '16:00:00' => '16:00',
                        '16:30:00' => '16:30',
                        '17:00:00' => '17:00',
                        '17:30:00' => '17:30',
                        '18:00:00' => '18:00',
                        '18:30:00' => '18:30',
                        '19:00:00' => '19:00',
                        '19:30:00' => '19:30',
                        '20:00:00' => '20:00',
                        '20:30:00' => '20:30',
                        '21:00:00' => '21:00',
                    );

                    echo $this->Form->input('time', array(
                        'class' => 'form-control icon_select',
                        'id' => 'time',
                        'type' => 'select',
                        'label' => false,
                        'div' => false,
                        'options' => $options,
                        'required' => 'required',
                        'value' => isset($this->request->data['News']['time']) ? $this->request->data['News']['time'] : ''
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 display" style="display: block;">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="ms_text">タイトル</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('title', array(
                        'type' => 'text',
                        'placeholder' => '２０文字以内で入力してください。',
                        'class' => 'form-control',
                        'id' => 'title',
                        'label' => false,
                        'div' => false,
                        'value' => isset($this->request->data['News']['title']) ? $this->request->data['News']['title'] : ''
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 display" style="display: block;">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word" id="ms_text">本文</p></div>
            <div class="col-xs-5 col-sm-5 col-md-6 col-lg-6">
                <?php
                    echo $this->Form->input('message', array(
                        'type' => 'textarea',
                        'placeholder' => '※一行につき２０文字程度で「改行」を入れ ると見やすいレイアウトになります。',
                        'class' => 'form-control',
                        'id' => 'message',
                        'cols' => 30,
                        'rows' => 6,
                        'label' => false,
                        'div' => false,
                        'value' => isset($this->request->data['News']['message']) ? $this->request->data['News']['message'] : ''
                    ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 display" style="display: block;">
        <hr class="hr" style="width:100%;">
    </div>
</div>
<div class="row">
    <input type="hidden" name="item" id="item">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 change_margin">
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3"><p class="word">画像</p></div>
            <div class="col-xs-8 col-sm-8 col-md-9 col-lg-9" style="margin-bottom: 25px;" id="tblphoto">
                <label for="news-image" class="upload-image">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <span class="glyphicon-class">画像選択</span>
                </label>
                <?php
                    echo $this->Form->input('file', array(
                        'type' => 'file',
                        'class' => 'hidden',
                        'onchange'=> 'readURL(this);',
                        'id' => 'news-image',
                        'label' => false,
                        'div' => false,
                    ));
                    if (isset($this->request->data['News']['file']['file'])) {
                        echo $this->Html->image(
                            $this->request->data['News']['file']['file'], array(
                            'alt' => 'image',
                            'id' => "image-file",
                            'style' => 'width:100px;'
                        ));
                    } else {
                        echo $this->Html->image(
                            '#', array(
                            'alt' => 'image',
                            'id' => "image-file",
                            'class' => 'hidden'
                        ));
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 top">
        <button type="submit" id="btn_save" class="btn btn-block btn_color">保存</button>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<script>
    (function(e) {
        var dateToday = new Date();
        var dataOptions = [];

        $('#date_picker').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate : dateToday
        });

        var tage = $('#destination_target').val();
        var group_id = $('#group_id').val();
        var area = $('#NewsArea').val();
        if (tage == 'all') {
            $('body').find('.row-display, .row-shop-display').hide();
        }
        if (group_id == 'all') {
             $('body').find('.row-shop-display').hide();
        } else {
            getShop(group_id);
            setTimeout(function(){
                <?php if (isset($this->request->data['News']['shop_id'])) {
                    echo '$("#shop_id").val('.$this->request->data['News']['shop_id'].');';
                }?>
            }, 800);
        }

        if (area !== '') {
            var area_id = area.split(",");
            $.each(area_id, function(index, value) {
                var div = $('<div>').addClass('area-item')
                    .attr('data-area_id', value)
                    .text($('#area option[value="'+value+'"]').text())
                    .append(' <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>');
                $('.selected-area').append(div);
            });
        }

        $('body').on('change', '#destination_target', function(e) {
            var value = $(this).val();
            if (value === 'filter') {
                $('body').find('.row-display').show();
            } else {
                $('body').find('.row-display, .row-shop-display').hide();
            }
        });

        $('body').on('click', '#btnarea', function(e) {
            var selected_val = $('#area').val();
            var selected_text =  $('#area').find(":selected").text();
            var items = $('.selected-area').children();
            var help = true;

            $.each(items, function (index, element){
                if ($(element).attr('data-area_id') == selected_val){
                    help = false;
                    return;
                }
            });
            if (help) {
                var div = $('<div>').addClass('area-item')
                    .attr('data-area_id', selected_val)
                    .text(selected_text)
                    .append(' <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>');
                $('.selected-area').append(div);
            }
        });

        $('body').on('click', '.area-item span', function(e) {
            $(this).parent().remove();
        });

        $('#btn_save').click(function(e) {
            var items = $('.selected-area').children();
            var area_id = [];

            $.each(items, function (index, element){
                area_id[index] = $(element).attr('data-area_id');
            });
            $('#NewsArea').val(area_id);

            //$('#notification_form').submit();
        });

        $('body').on('change', '#group_id', function(e) {
            var value = $(this).val();
            if (value != 'all') {
                getShop(value);
                $('body').find('.row-shop-display').show();
            } else {
                $('body').find('.row-shop-display').hide();
            }
        });

        function getShop(group_id) {
            $.get('<?php echo $this->Html->url('/news/get_shop_by_group_id'); ?>', 
            {'group_id': group_id}, function(data) {
                var shops = data.data;
                DisplayOption('#shop_id', shops, 'shops');
            }, 'json');
        }

        function DisplayOption(name, data, type) {
            if (data != null && data != undefined) {
                var element = '';
                element += '<option value="all">All Users</option>';
                if (type === 'shops') {
                    $.each(data, function(i, v) {
                        element += '<option value="' + v.id + '">' + v.shop_name + '</option>';
                    });
                }
                $('body').find(name).html(element);
                if ($(data).length <= 0) {
                    $('body').find(name).html('<option value="">No Shop</option>');
                }
            }
        }
    })();
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-file')
                    .attr('src', e.target.result)
                    .width(100)
                    .removeClass('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>