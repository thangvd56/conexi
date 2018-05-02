<?php $id = 1; ?>
<?php foreach ($user_list as $key => $value): ?>
    <div class="row user_list user_<?php echo $value['User']['id']; ?>" id="<?php echo 'user_'.$id; ?>">
        <div class="col-xs-12 col-md-8">
            <div class="panel radius">
                <table class="table table-bordered">
                    <tr>
                        <td class="col-md-2" title="Role"><?php echo $value['User']['role']; ?></td>
                        <td class="col-md-5" title="Username"><?php echo $value['User']['username']; ?></td>
                        <td class="col-md-5"><?php echo $value['User']['email']; ?></td>
                        <td class="col-md-5">
                            <div class="set_margin" style="width:220px">
                                <a href="javascript:void(0);"
                                   id="<?php echo $value['User']['id']; ?>"
                                   class="btn btn-success back_color get_user_id get_user_detail"
                                   data-name ="<?php echo $value['User']['username']; ?>"
                                   data-email ="<?php echo $value['User']['username']; ?>"
                                   data-toggle="modal"
                                   data-target="#ModalEdit">編集</a>
                                <?php if($current_user != $value['User']['id']): ?>
                                <a href="javascript:void(0);"
                                   id="<?php echo $value['User']['id']; ?>"
                                   data-user-shop ="<?php echo $value['UserShop']['shop_id']; ?>"
                                   data-shop-notification ="<?php echo $value['UserShop']['is_allow_notification'];?>"
                                   class="btn btn-success back_color2 get_user_id"
                                   data-name ="<?php echo $value['User']['username']; ?>"
                                   data-toggle="modal"
                                   data-target="#ModalDeleteUser">削除</a>
                                <?php else:?>
                                <a href="javascript:void(0);"
                                   id="<?php echo $value['User']['id']; ?>"
                                   class="btn btn-success back_color2 get_user_id"
                                   data-name ="<?php echo $value['User']['username']; ?>"
                                   data-toggle="modal"
                                   data-target="#">-----</a>
                                <?php endif;?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php $id++; ?>
<?php endforeach;