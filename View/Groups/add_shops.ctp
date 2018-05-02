
<div class="row">
    <div class="col-xs-12 col-lg-8">
        <h1 class="page-header">店舗管理　＞　グループ管理　＞　グループ編集</h1>
        <?php echo $this->Flash->render(); ?>
        <?php
            echo $this->Form->create('Group', array(
                'role' => 'form',
                'id' => 'form-update-group'
            ));
            echo $this->Form->hidden('Group.id', array(
                'value' => $group['Group']['id'],
                'id' => 'group-id'
            ));
            echo $this->Form->input('Group.name', array(
                'type' => 'text',
                'value' => h($group['Group']['name']),
                'id' => 'group-name',
                'class' => 'form-control',
                'label' => false
            ));
        ?>
        <br />
        <div id="shop-within-group">
            <?php
                if (isset($shops) && !empty($shops)) :
                    for ($i = 0; $i < count($shops); $i++) : ?>
                        <div class="shops-group">
                            <?php
                                echo $this->Form->hidden('shop_id.', array(
                                    'value' => $shops[$i]['Shop']['id'],
                                    'class' => 'shop-id',
                                    'id' => false
                                ));
                                echo $this->Form->hidden('status.', array(
                                    'value' => 'keep',
                                    'class' => 'shop-status',
                                    'data-shopid' => $shops[$i]['Shop']['id'],
                                    'id' => false
                                ));
                            ?>
                        </div>
                        <div class="row row-shop-item item-id-<?php echo $shops[$i]['Shop']['id']; ?>">
                            <div class="col-xs-9">
                                <ul>
                                    <li><?php echo h($shops[$i]['Shop']['shop_name']); ?></li>
                                </ul>
                            </div>
                            <div class="col-xs-3">
                                <button type="button" class="btn btn-danger btn-xs remove-shop" data-toggle="popover" data-shopid="<?php echo $shops[$i]['Shop']['id']; ?>">削除</button>
                            </div>
                        </div>
            <?php
                    endfor;
                else:
            ?>
            <div class="shops-group"></div>
            <?php
                endif;
            ?>
        </div>
        <div class="col-xs-12 col-lg-12 col-sm-12 text-center">
            <div class="panel dotted-border" id="btn-add">+ 店舗追加</div>
            <button type="submit" class="btn btn-block btn_color btn-save ">保存</button>
        </div>
        <?php $this->Form->end(); ?>
    </div>
</div>
<div class="modal fade" id="shop-list-modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header"><?php echo h($group['Group']['name']); ?> に追加する店舗を<br>選択してください。</div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-add-shops" data-dismiss="modal">追加</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        confirmYesNo('remove-shop', '本当によろしいですか？', 'left');
        
        $('body').on('click', '.confirm-no', function() {
            $(this).parents('.popover').prev('button').trigger('click');
        });

        $('body').on('click', '.confirm-yes', function() {
            var shopId = $(this).parents('.popover').prev('button').attr('data-shopid');
            
            $('.shop-status').each(function() {
                if ($(this).attr('data-shopid') === shopId) {
                    if ($(this).val() === 'new') {
                        $(this).parent().remove();
                        $('.item-id-' + shopId).remove();
                    } else {
                        $('.item-id-' + shopId).hide();
                        $(this).val('remove');
                    }
                }
            });
        });

        $('#btn-add').click(function() {
            var shopId = [];
            var id;
            if ($('.shop-id').length) {
                $('.shop-id').each(function() {
                    shopId.push($(this).val());
                });
                id = shopId.join();
            }
            $.ajax({
                url: URL + 'Shops/shopsNoGroup/',
                method: 'GET',
                data: {shop_id: id},
                dataType: 'html',
                success: function(data) {
                    $('#shop-list-modal .modal-body').html(data);
                    $('#shop-list-modal').modal('show');
                }
            });
        });

        $('body').on('click', '.btn-add-shops', function() {
            $('.is-checked-shop').each(function() {
                if ($(this).is(':checked')) {
                    var shopId = $(this).val();
                    var shopName = $(this).attr('data-shopname');
                    var addShop = '<div class="shops-group">';
                        addShop += '<input type="hidden" name="data[Group][shop_id][]" value="'+ shopId +'" class="shop-id">';
                        addShop += '<input type="hidden" name="data[Group][status][]" value="new" class="shop-status" data-shopid="'+ shopId +'">';
                    addShop += '</div>';
                    addShop += '<div class="row row-shop-item item-id-'+ shopId +'">';
                            addShop += '<div class="col-xs-9">';
                                addShop += '<ul>';
                                    addShop += '<li>'+ shopName +'</li>';
                                addShop += '</ul>';
                            addShop += '</div>';
                            addShop += '<div class="col-xs-3">';
                                addShop += '<button type="button" class="btn btn-danger btn-xs remove-shop" data-toggle="popover" data-shopid="'+ shopId +'">削除</button>';
                            addShop += '</div>';
                        addShop += '</div>';
                    $('#shop-within-group').append(addShop);
                }
            });
            confirmYesNo('remove-shop', '本当によろしいですか？', 'left');
        });


//        $('#shop-list-modal').on('click','.btn-shops', function() {
//            var checked_shop = $('#shop-list-modal input:checked');
//            $.each(checked_shop, function(index, element) {
//                var clone = $('tr:last').clone();
//                if ($(clone).length > 0) {
//                    $(clone).find('.shop_id').val($(element).val());
//                    $(clone).find('.deleted').remove();
//                    $(clone).find('td:first li').html($(element).parent().find('label').text());
//                    $.each($(clone).find('input[type=hidden]'), function(index2, element2) {
//                        var name = $(element2).attr('name');
//                        var new_name = name.substring(0, 12)+(parseInt(name.substring(12, name.indexOf("]", 11))) + 1)+name.substring(name.indexOf("]", 11), name.length);
//                        $(element2).attr('name', new_name);
//                    });
//                    $(clone).show();
//                    $('table').append(clone);
//                } else {
//                    var tr = $('<tr>').append($('<input type="hidden" name="data[Group][0][shop_id]" class="shop_id">').val($(element).val()));
//                    var td1 = $('<td>').append($('<li>').text($(element).parent().find('label').text()));
//                    var td2 = $('<td>').append(
//                        $('<input type="button">').val('削除')
//                        .addClass('btn btn-danger btn-xs remove-shop')
//                        .attr('data-toggle', 'popover')
//                        .attr('data-placement', 'left')
//                        .attr('data-trigger', 'focus')
//                        .attr('data-html', 'true')
//                        .attr('data-content', '本当によろしいですか？<br> <input type="button" value="いいえ" class="btn btn-no"><input type="button" value="はい" class="btn btn-ok">')
//                    );
//                    $('table').append($(tr).append($(td1)).append($(td2)));
//                }
//            });
//            $('[data-toggle="popover"]').popover();
//        });

//        $('table').on('click','.popover .btn-ok',function(){
//            var row = $(this).parent().parent().parent().parent();
//            if ($(row).find('.deleted').length > 0) {
//                $(row).find('.deleted').val(1);
//                $(row).hide();
//            } else {
//                $(row).remove();
//            }
//        });
//
//        $('table').on('click','.popover .btn-no',function() {
//            var popover = $(this).parent().parent().parent().find('[data-toggle="popover"]');
//            $(popover).trigger('click');
//        });
//
//        $('[data-toggle="popover"]').popover();
    });
</script>