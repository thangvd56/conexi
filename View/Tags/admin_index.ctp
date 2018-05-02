
    <?php echo $this->Html->script(array(
        'jquery-1.10.2',
        'bootstrap.min',
        'bootstrap-toggle.min'
    )); ?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header text-primary">
                予約タグ作成
            </h3>            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="button" id="btn_sign_up" data-toggle="modal" data-target="#ModalCreateTag" class="btn btn-primary pull-right margin-r-m15">新規登録</button>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="record col-lg-12">
        <table id="mytble" class="table table-bordered">
            <thead>
                <tr>
                    <th>タイプ</th>
                    <th>タグ名</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($tag):
                    foreach ($tag as $key => $value):
                        ?>
                        <tr id="<?php echo $value['T']['id'] ?>">
                            <td><?php echo $value['T']['tag_type'] ?></td>
                            <td><?php echo $value['T']['tag'] ?></td>
                            <td style="width:100px;">
                                <button class="btn btn-success color back_color but_design get_edit" target-type="<?php echo $value['T']['tag_type'] ?>" target-name="<?php echo $value['T']['tag'] ?>" id="<?php echo $value['T']['id'] ?>" style="width:92px;" data-toggle="modal" data-target="#ModalCreateTag"> 編集 </button>
                            </td>
                            <td style="width:50px;">
                                <button class="btn btn-success color back but_design2 get_tag_id" id="<?php echo $value['T']['id'] ?>" data-toggle="modal" data-target="#ModalDeleteTag">削除</button>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Create Tag-->
<div id="ModalCreateTag" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <?php echo $this->Form->create('Tag',
            array('id' => 'create_tag')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">予約タグ作成</h4>
            </div>
            <div class="modal-body">
                <p>タイプ</p>
                <?php
                $tag_type = array(
                    'user_tag' => 'User Tag',
                    'reservation_tag' => 'Reservation Tag'
                );
                echo $this->Form->input('tag_type',
                    array(
                    'type' => 'select',
                    'class' => 'form-control',
                    'options' => $tag_type,
                    'label' => false,
                    'name' => 'tag_type',
                    'id' => 'tag_type',
                ));
                ?>
                <br>
                <p>タグ名</p>
                <input type="text" name="tag_name" id="tag_name" placeholder="タグ名" class="form-control"/><br>
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
                    <button type="button" class="btn btn-success btn-block btn_color" id="btn_create_tag">保存</button>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<div id="ModalDeleteTag" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <?php echo $this->Form->create('Tag',
                array('id' => 'delete_tag')); ?>
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <label id="label_tag">本当によろしいですか？</label>
                <input type="checkbox" name="del_physical" value="1" id="del1" class="hide"><label for="del1" class="hide">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" name="tag_id" class="tag_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-6 col-md-6">
                    <button type="button" class="btn btn-block btn_color background" data-dismiss="modal">キャンセル</button>
                </div>
                <div class="col-xs-6 col-md-6">
                    <button type="submit" class="btn btn-success btn-block btn_color" >はい</button>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<!--Modal delete Tag-->
<!--<div id="ModalDeleteTag" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
         Modal content
        <div class="modal-content">
            <?php //echo $this->Form->create('Tag',array('id' => 'delete_tag')); ?>
            <div class="modal-body text-center">
                <label id="label_tag">本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del1"><label for="del1">&nbsp;物理的に削除する。</label> <br>
                <input type="hidden" name="tag_id" class="tag_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <button type="submit" class="btn btn-success color back_color but_design" style="width: 100px;">はい</button>
                </div>
            </div>
            <?php //echo $this->Form->end(); ?>
        </div>
    </div>
</div>-->
<script type="text/javascript">

    $("body").on("click", "#btn_create_tag", function (e) {
        e.preventDefault();
        var data = $("#create_tag").serialize();
        $.ajax({
            url: "<?php echo Router::url(array('controller' => 'tags', 'action' => 'admin_index')); ?>",
            data: data + "&action=save",
            type: "get",
            beforeSend: function () {
                $("#save_loading").removeClass("hide");
            },
            success: function (respond) {
                var data = JSON.parse(respond);
                if (data.result === "success") {
                    $("#ModalCreateTag").modal("hide");
                    $("#error_msg").html("");
                    if(data.action ==='save'){
                        var str = '';
                        str += '<tr id="' + data.data.Tag.id + '"><td>' + data.data.Tag.tag_type + '</td>';
                        str += '<td>' + data.data.Tag.tag + '</td>';
                        str += '<td><button class="btn btn-success color back_color but_design get_edit" target-type="' + data.data.Tag.tag_type + '" target-name="' + data.data.Tag.tag + '" id="' + data.data.Tag.id + '" style="width:92px;" data-toggle="modal" data-target="#ModalCreateTag" >編集</button></td>';
                        str += '<td><button class="btn btn-success color back but_design2 get_tag_id" id="' + data.data.Tag.id + '" data-toggle="modal" data-target="#ModalDeleteTag">削除</button></td></tr>';
                        $("#mytble").prepend(str);
                        return false;
                    }else{
                        window.location.reload();
                    }
                } else {
                    $("#error_msg").html(data.msg);
                    //alert("Tag type and name already exist");
                }
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log("error save_data");
            },
            complete: function () {
                $("#save_loading").addClass("hide");
            }
        });
    });

    //Get tag_id for delete
    $("body").on('click', '.get_tag_id', function () {
        var id = $(this).attr('id');
        $(".tag_id").val(id);
    });

    $(".get_edit").click(function () {
        var id = $(this).attr('id');
        $("#tag_id").val(id);
        var tag = $(this).attr('target-name');
        $("#tag_name").val(tag);
        var tag_type = $(this).attr('target-type');
        $("#tag_type").val(tag_type);
    });

    $("#btn_sign_up").click(function () {
        $("#tag_id").val("");
        $("#tag_name").val("");
        $("#error_msg").html("");
    });

    //Delete tag
    $("form#delete_tag").submit(function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            url: "<?php echo Router::url(array('controller' => 'tags', 'action' => 'index')); ?>",
            data: data + "&action=delete",
            type: "get",
            dataType: "json",
            beforeSend: function () {
                $("#delete_loading").removeClass("hide");
            },
            success: function (respond) {
                if (respond.result === "success") {
                    $("#delete_loading").addClass("hide");
                    $("#ModalDeleteTag").modal("hide");
                    $("#" + $(".tag_id").val()).remove();

                } else {
                    $("#error-msg-delete").html(respond.msg);
                }
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log("Error: " + xhr.status);
            }
        });
    });

</script>
