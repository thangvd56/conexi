<div class="row">
    <div class="col-xs-12 col-lg-8">
        <h1 class="page-header">店舗管理 > グループ管理</h1>
        <ol class="breadcrumb"><li class="active">グループ名</li></ol>
        <?php echo $this->Flash->render(); ?>
        <?php
            echo $this->Form->create('Group', array(
                'role' => 'form',
                'id' => 'group_form',
            ));
        ?>
        <div class="group-list">
            <?php if ($data) :
                foreach ($data as $key => $value) : ?>
            <div class="row" data-id="<?php echo $value['Group']['id']; ?>" data-target="edit">
                <div class="col-sm-6 col-lg-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Aグループ" name="names[]" value="<?php echo $value['Group']['name']; ?>" readonly="readonly"/>
                    </div>
                    <ul>
                        <?php foreach($value['Shops'] as $key2 => $value2) : ?>
                            <li><?php echo $value2['Shop']['shop_name'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-xs-6 col-sm-3 col-lg-3">
                    <div class="form-group">
                        <?php
                            echo $this->Html->link('編集',
                                array(
                                    'controller' => 'groups',
                                    'action' => 'addShops', $value['Group']['id'],
                                ),
                                array('class' => 'btn btn_color btn-block')
                            );
                        ?>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-lg-3">
                    <div class="form-group">                        
                        <button class="btn btn_color btn-block" id="btn_delete" data-id="<?php echo $value['Group']['id']; ?>">削除</button>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
        <div class="row">
            <div class="col-xs-12 col-lg-12 col-sm-12 text-center">
                <div class="panel dotted-border" id="btn_add">+ グループ新規追加</div>
                <button type="button" class="btn btn-block btn_color btn_save ">保存</button>
            </div>
        </div>
        <?php $this->Form->end(); ?>
    </div>
</div>

<div id="confirm-save" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <label>変更内容を保存します。<br>本当によろしいですか？</label>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal">
                    <input type="button" id="btn_confirm" value="はい" class="btn btn-success color back_color but_design">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirm-delete" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
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

<script>
    $(function() {
        $('body').on('click', '#btn_add', function() {
            var element = '<div class="row" data-target="new">' +
                '<div class="col-sm-6 col-lg-6"><div class="form-group"><input type="text" class="form-control" placeholder="Aグループ" name="names"/></div></div>' +
                '<div class="col-xs-6 col-sm-3 col-lg-3"><div class="form-group"><button class="btn btn-block btn_color" disabled="disabled">編集</button></div></div>' +
                '<div class="col-xs-6 col-sm-3 col-lg-3"><div class="form-group"><button class="btn btn-block btn_color" id="btn_delete">削除</button></div></div>' +
            '</div>';
            $('body').find('.group-list').append(element);
        });

        $('body').on('click', '#btn_delete', function(e) {
            e.preventDefault();
            if ($(this).data('id')) {
                $('#confirm-delete #btn_confirm').attr('data-id', $(this).data('id'));
                $('#confirm-delete').modal('show');
            } else {
                $(this).closest('.row').remove();
            }
        });

        $('#confirm-delete').on('click', '#btn_confirm', function(e) {
            window.location.href = '<?php echo $this->Html->url('/groups/delete/'); ?>' + $(this).data('id');
        });

        $('body').on('click', '.btn_save', function(e) {
            $('#confirm-save').modal('show');
        });
        $('#confirm-save').on('click', '#btn_confirm', function(e) {
            var names = new Array();
            if ($('.group-list .row').length > 0) {
                $.each($('.group-list .row'), function(i, v) {
                    var txt = $(this).find('input[type="text"]');
                    if (!txt.attr('readonly')) {
                        var arr = {
                            id: $(this).data('id'),
                            name: txt.val()
                        };
                        names.push(arr);
                    }
                });
                $.post('<?php echo $this->Html->url('/groups/create'); ?>', { names }, function(e) {
                    window.location.href = '<?php echo $this->Html->url('/groups'); ?>';
                });
            }
        });
    });
</script>