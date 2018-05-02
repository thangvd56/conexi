<div class="table-responsive">
    <table class="table custom-table">        
        <?php $role = unserialize(USER_ROLE); foreach ($user_list as $key => $value): ?>
            <tr>
                <td title="ShopName">
                    <div class="vertical-border"><?php echo $value['Shop']['shop_name']; ?></div>
                </td>
                <td title="Shop_id">
                    <div class="vertical-border"><?php echo $value['Shop']['id']; ?></div>
                </td>
                <td title="Username">
                    <div class="vertical-border"><?php echo $value['User']['username']; ?></div>
                </td>

                <td>
                    <div class="vertical-border"><?php echo $value['User']['email']; ?></div>
                </td>
                <td title="Role">
                    <div class="vertical-border"><?php echo $role[$value['User']['role']]; ?></div>
                </td>
                <td width="21.7%">
                    <button type="button" style="margin-left: 2px;" class="btn btn-success back_color edit-user" data-id="<?php echo $value['User']['id']; ?>">編集</button>
                        <a href="#"
                        id="<?php echo $value['User']['id']; ?>"
                        data-user-shop-id ="<?php echo $value['Shop']['id']; ?>"
                        class="btn btn-success back_color2 get_user_id"
                        data-name ="<?php echo $value['User']['username']; ?>"
                        data-toggle="modal"
                        data-target="#ModalDeleteUser" style="margin-left: 14px">削除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>