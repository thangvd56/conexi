<?php
echo $this->Html->css('pastNotice');
?>
<style>
    .well{
        height: 400px;
        overflow: auto;
    }
    .col-md-3{
        float: right;
    }
</style>
<div>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
            <h1 class="page-header">
                通知設定 > お知らせ通知
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    お知らせ通知 <p class="sub_header">こちらの通知は、事前に設定した日にちと時間で通知を配信する事が
                        できます。 </p>
                </li>
            </ol>
            <?php $role = $this->Session->read('Auth.User.role'); ?>
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                    <div class="form-group">
                        <?php
                        echo $this->Html->link('削除情報',
                            array('controller' => 'customers', 'action' => 'deleted?type=notification'),
                            array('class' => 'btn btn-block btn_color right','style' => 'margin-left:15px;'));
                        ?>
                    </div>
             </div>
        </div>
    </div>
    <div class="row">
        <?php if ($role == ROLE_HEADQUARTER) : ?>
            <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                    <?php
                        echo $this->Form->input('shop_id', array(
                            'id'   => 'shop_id',
                            'type' => 'select',
                            'name' => 'shop_id',
                            'class' => 'shop_name form-control',
                            'label' => false,
                            'options' => $shop,
                            'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                        ));
                    ?>
                </div>
            </div>
        <?php
        else : 
            echo $this->Form->input('shop_id', array(
                'id'   => 'shop_id',
                'type' => 'hidden',
                'name' => 'shop_id',
                'label' => false,
                'value' => $shop_id ? $shop_id : ''
            ));
        endif; ?>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
            <div class="well" style="padding: 35px; margin-bottom: 20px;">
                <?php if (isset($new)): ?>
                    <?php foreach ($new as $key => $value): ?>
                        <?php
                        if ($value['News']['delivery_date_value'] == '0000-00-00') {
                            $note_date = "未設定";
                        } else {
                            $note_date = $value['News']['delivery_date_value'] ." ". substr($value['News']['delivery_time_value'], 0,-3) ;
                        }
                        ?>
                        <div class="panel row">
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 top">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <h4>日付</h4>
                                </div>
                                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7" style="border-left: 2px solid #efefef;">
                                    <h4><?php echo $note_date; ?></h4>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <?php
                                echo $this->Html->link(' 編集 ',
                                    '/notices/edit/'.$value['News']['id'].'?shop_id='.$shop_id,
                                    array('class' => 'btn btn-success back_color butt',
                                    'style' => 'width:90px;margin-left:20px'));
                                ?>
                            </div>
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" style="border-right: 2px solid #efefef;">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <h4>タイトル</h4>
                                </div>
                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" style="border-left: 2px solid #efefef;">
                                    <h4><?php echo $value['News']['title']; ?></h4>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-top:-10px;">
                                <button id="<?php echo $value['News']['id']; ?>" style="width:90px;margin-left:20px" class="btn btn-success back_color2 butt get_notice_id" data-toggle="modal" data-target="#ModalDeleteNotice">削除</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7 text-center" >
            <div class="panel dotted-border">
                <div class="" id="btn_add">
                    +通知日を追加する
                </div>
            </div>
        </div>
     </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
            <h1>過去の配信履歴</h1>
            <div class="well">
                <?php if (isset($past_notice)): ?>
                    <?php foreach ($past_notice as $key => $value): ?>
                        <?php
                        $d = new DateTime($value['News']['modified']); //Date have been delivered
                        $date = $d->format('Y/m/d H:i');
                        ?>
                        <?php
                        if ($value['News']['gender'] == '1') {
                            $gender = "男性";
                        } else if ($value['News']['gender'] == '0') {
                            $gender = "女性";
                        } else {
                            $gender = "全て対象";
                        }
                        if ($value['News']['destination_target'] != 'filter') {
                            $hide = "style='display:none;'";
                        } else {
                            $hide = "";
                        }
                        $val_target ="(" . $value['News']['target'] . ")歳";
                        ?>
                        <div class="panel height">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="width:150px"><h4>日付</h4></td>
                                    <td><h4><?php echo $date; ?></h4></td>
                                </tr>
                                <tr <?php echo $hide ?>>
                                    <td><h4>配信対象</h4></td>

                                    <td><h4><?php echo $gender ?> <?php echo $val_target; ?></h4></td>
                                </tr>
                                <tr>
                                    <td><h4>タイトル</h4></td>
                                    <td><h4><?php echo $value['News']['title']; ?></h4></td>
                                </tr>
                                <tr>
                                    <td><h4>本文</h4></td>
                                    <td>
                                        <h4>
                                             <?php echo $value['News']['message']; ?>
                                        </h4>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!--Modal Delete-->
<div id="ModalDeleteNotice" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
           <?php echo $this->Form->create('News', array('id' => 'delete_notice')); ?>
            <div class="modal-body text-center">
                <label id="label_notification_notice">本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical" value="1" id="del1"><label for="del1">&nbsp;完全に削除する</label> <br>
                <input type="hidden" name="notice_id" class="notice_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;Deleting...</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" id="btn_cancel_last_notice" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <button type="submit" class="btn btn-success color back_color but_design" style="width: 100px;">はい</button>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(".get_notice_id").click(function () {
            var id = $(this).attr('id');
            $(".notice_id").val(id);
        });
        $("form#delete_notice").submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'notices', 'action' => 'index')); ?>",
                data: data + "&action=delete",
                type: "get",
                dataType: "json",
                beforeSend: function () {
                    $(".success-message, .error-message").html("");
                    $("#delete_loading").removeClass("hide");
                },
                success: function (respond) {
                    if (respond.result === "success") {
                        $("#delete_loading").addClass("hide");
                        $("#ModalDeleteNotice").modal("hide");
                        window.location.reload(URL + "users/view/notices");
                    } else {
                        $("#error-msg-delete").html(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error: " + xhr.status);
                }
            });
        });

        $("#btn_add").click(function () {
            window.location.replace(URL + "users/view/notices-create/?shop_id="+$('#shop_id').val());
        });

        $("#shop_id").change(function () {
            window.location.replace(URL + "users/view/notices/?shop_id="+$(this).val());;
        });
    });
</script>