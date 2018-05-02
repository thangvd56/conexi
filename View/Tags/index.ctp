
<?php $role = $this->Session->read('Auth.User.role'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <h1 class="page-header">アプリ作成 ＞ タグ作成</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-lg-8">
            <?php if ($role === ROLE_HEADQUARTER) : ?>
            <?php
                echo $this->Form->create('Tag', array(
                    'class' => 'form-inline form-tag',
                    'role' => 'form',
                ));
            ?>
            <div class="form-group">
                <?php
                    echo $this->Form->input('select_shop', array(
                        'type' => 'select',
                        'class' => 'form-control',
                        'label' => false,
                        'options' => $shops,
                        'templates' => array(
                            'inputContainer' => '{{content}}',
                        ),
                        'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : '',
                    ));
                ?>
            </div>
            <?php $this->Form->end(); ?>
            <?php endif; ?>
        </div>
    </div><br>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <ol class="breadcrumb"><li class="active">ユーザタグ	</li></ol>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4">
                <div class="form-group">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-xs-6 col-md-12">
                        <button type="button" data-type="user_tag" id="btn_sign_up" data-toggle="modal" data-target="#ModalCreateTag" class="btn btn-block btn_color" data-title="create">新規登録</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($tags['user_tag'])) : ?>
        <div class="record">
            <header>
                <div class="col-sm-4 col-lg-4"><h3>タグ名</h3></div>
                <div class="col-sm-4 col-lg-4"><h3>使用回数</h3></div>
                <div class="col-sm-4 col-lg-4"><h3>追加日</h3></div>
                <div class="clearfix"></div>
            </header>
            <?php foreach ($tags['user_tag'] as $tag) : ?>
                <div class="row user-tag-wrap" data-id="<?php echo $tag['Tag']['id']; ?>" data-name="<?php echo $tag['Tag']['tag']; ?>">
                    <div class="record-body">
                        <div class="col-sm-4 col-lg-4">
                            <button type="button" class="btn btn-block btn_color btn_edit" data-target="#ModalCreateTag" data-toggle="modal" data-type="user_tag" data-title="edit">
                                <?php echo $tag['Tag']['tag']; ?>
                            </button>
                        </div>
                        <div class="col-sm-4 col-lg-4"><?php echo $tag['Tag']['count']; ?></div>
                        <div class="col-sm-4 col-lg-4"><?php echo date('Y-m-d', strtotime($tag['Tag']['created'])); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="bt-tag"></div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <ol class="breadcrumb"><li class="active">予約タグ</li></ol>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4">
                <div class="form-group">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="col-xs-6 col-md-12">
                        <button type="button" data-type="reservation_tag" id="btn_sign_up" data-toggle="modal" data-target="#ModalCreateTag" class="btn btn-block btn_color" data-title="create">新規登録</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($tags['reservation_tag'])) : ?>
        <div class="record">
            <header>
                <div class="col-sm-4 col-lg-4"><h3>タグ名</h3></div>
                <div class="col-sm-4 col-lg-4"><h3>使用回数</h3></div>
                <div class="col-sm-4 col-lg-4"><h3>追加日</h3></div>
                <div class="clearfix"></div>
            </header>
            <?php foreach ($tags['reservation_tag'] as $tag) : ?>
                <div class="row user-tag-wrap" data-name="<?php echo $tag['Tag']['tag']; ?>" data-id="<?php echo $tag['Tag']['id']; ?>">
                    <div class="record-body">
                        <div class="col-sm-4 col-lg-4">
                            <button type="button" class="btn btn-block btn_color btn_edit" data-type="reservation_tag" data-toggle="modal" data-target="#ModalCreateTag" data-title="edit">
                                <?php echo $tag['Tag']['tag']; ?>
                            </button>
                        </div>
                        <div class="col-sm-4 col-lg-4"><?php echo $tag['Tag']['count']; ?></div>
                        <div class="col-sm-4 col-lg-4"><?php echo date('Y-m-d', strtotime($tag['Tag']['created'])); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<!-- Modal Create Tag-->
<form role="form" id="create_tag" onsubmit="return false;">
<?php echo $this->Form->create('Tag', array(
    'role' => 'form',
    'id' => 'create_tag',
    'onsubmit' => 'return false;',
)); ?>
    <div id="ModalCreateTag" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">予約タグ作成</h4>
                </div>
                <div class="modal-body">
                    <p>タグ名</p>
                    <?php
                        echo $this->Form->input('tag_name', array(
                            'class' => 'form-control',
                            'placeholder' => 'タグ名',
                            'label' => false,
                        ));
                    ?><br>
                    <?php echo $this->Form->input('tag_type', array('type' => 'hidden')); ?>
                    <?php echo $this->Form->input('action', array('type' => 'hidden')); ?>
                    <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
                    <div id="error_msg" style="color:red"></div>
                    <div class="clearfix">&nbsp;</div>
                    <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                    <input type="hidden" name="tag_id" id="tag_id" />
                </div>
                <div class="modal-footer">
                    <div class="col-xs-6 col-md-6">
                        <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <?php
                            echo $this->Form->submit('保存', array(
                                'class' => 'btn btn-block btn_color',
                                'id' => 'btn_create_tag',
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo $this->Form->end(); ?>

<!--Modal delete Tag-->
<div id="ModalDeleteTag" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <?php
                echo $this->Form->create('Tag', array(
                    'id' => 'delete_tag',
                    'role' => 'form',
                ));
            ?>
            <div class="modal-body text-center">
                <label id="label_tag">本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del1"><label for="del1">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" name="tag_id" class="tag_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <?php
                        echo $this->Form->button('キャンセル', array(
                            'class' => 'btn btn-success color back but_design2',
                            'id' => 'btn_cancel',
                            'data-dismiss' => 'modal',
                            'style' => 'width: 100px;',
                        ));
                    ?>
                    <?php
                        echo $this->Form->submit('はい', array(
                            'class' => 'btn btn-success color back_color but_design',
                            'style' => 'width: 100px;',
                        ));
                    ?>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(e) {
        $('body').on('click', '#btn_create_tag', function (e) {
            e.preventDefault();
            var url = '<?php echo $this->Html->url('/tags'); ?>';
            if ('<?php $role === ROLE_HEADQUARTER ?>') {
                url = '<?php echo $this->Html->url('/tags?shop_id='); ?>' + $('body').find('#TagSelectShop').val();
            }

            $('#ModalCreateTag').find('#error_msg').html('');
            var txt_name = $('#ModalCreateTag').find('#TagTagName').val();
            if (txt_name === '') {
                $('#ModalCreateTag').find('#error_msg').html('データが空です。');
            } else {
                var target = $('#ModalCreateTag').find('#TagAction').val();
                if (target === 'create') {
                    var params = {
                        shop_id: $('body').find('#TagSelectShop').val(),
                        tag_type: $('#ModalCreateTag').find('#TagTagType').val(),
                        tag_name: txt_name
                    };
                    $.post('<?php echo $this->Html->url('/tags/create'); ?>', { params }, function(data) {
                        if (data.result === 'exist') {
                            $('#ModalCreateTag').find('#error_msg').html('この名前は既に使用されています！');
                        } else {
                            window.location.href = url;
                        }
                    }, 'json');
                } else {
                    var params = {
                        shop_id: $('body').find('#TagSelectShop').val(),
                        tag_type: $('#ModalCreateTag').find('#TagTagType').val(),
                        tag_name: txt_name,
                        tag_id: $('#ModalCreateTag').find('#TagId').val()
                    };
                    $.post('<?php echo $this->Html->url('/tags/edit'); ?>', { params }, function(data) {
                        if (data.result === 'exist') {
                            $('#ModalCreateTag').find('#error_msg').html('この名前は既に使用されています！');
                        } else {
                            window.location.href = url;
                        }
                    }, 'json');
                }
            }
        });

        $('body').on('click', '.btn_edit', function(e) {
            var contents = $('body').find('#ModalCreateTag');
            var tag_name = $(this).closest('.user-tag-wrap').data('name');
            $(contents).find('#TagTagType').val($(this).data('type'));
            $(contents).find('#TagTagName').val(tag_name);
            $(contents).find('#TagAction').val($(this).data('title'));
            $(contents).find('#TagId').val($(this).closest('.user-tag-wrap').data('id'));
        });

        $('#TagSelectShop').on('change', function(e) {
            window.location.href = '<?php echo $this->Html->url('/tags?shop_id='); ?>' + $('#TagSelectShop').val();
        });

        $('body').on('click', 'button[data-dismiss="modal"]', function(e) {
             $('#ModalCreateTag').find('#error_msg').html('');
             $('#ModalCreateTag').find('#TagTagType').val('');
        });

        $('body').on('click', '#btn_sign_up', function(e) {
            $('#ModalCreateTag').find('#TagTagName').val('');
            $('#ModalCreateTag').find('#TagTagType').val($(this).data('type'));
            $('#ModalCreateTag').find('#TagAction').val($(this).data('title'));
        });
    });
</script>
