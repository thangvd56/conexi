
<?php
if ($type == "user") {
    if ($data):
        echo ' <div class="col-xs-12 col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered">
                <tr class="bg-color">
                <td><input type="checkbox" id="checkList" style="text-center"/></td>
                <td>フルネーム</td>
                <td>生年月日</td>
                <td>性別</td>
                <td>地域</td>
                <td>日付</td>
                <td>
                    <button style="width: 176px !important;" class="btn btn-success color back_color2 get_delete_all" target-type='.$type.' data-id="" data-toggle="modal" data-target="#ModalDeleteAllConfirm">チェックした項目を削除</button>
                </td>
                </tr>
              ';
                foreach ($data as $key => $value):
                    ?>
                    <tr  id="<?php echo $type ."_". $value['U']['id']; ?>">
                        <td><input type="checkbox" class="reId <?php echo $type;?>" value="<?php echo $value['U']['id'];?>" /></td>
                        <td title="FullName"><?php echo $value['U']['lastname'].".".$value['U']['firstname'] ?></td>
                        <td style="width:20%" title="Birthday"><?php echo $value['U']['birthday']; ?></td>
                        <td title="Gender"><?php echo $value['U']['gender'] ?>
                        <td title="Area"><?php echo $value['A']['name'] ?></td>
                        <td title="Deleted"><?php echo $value['U']['modified']; ?></td>
                        <td>
                            <button class="btn btn-success back_color2 get_revert" target-type="<?php echo $type; ?>" data-user-shop="<?php echo $value['UserShop']['id']; ?>" data-shop-id="<?php echo $value['UserShop']['shop_id']; ?>" data-id="<?php echo $value['U']['id']; ?>" data-toggle="modal" data-target="#ModalRevertConfirm">元に戻す</button>
                            <button class="btn btn-success back_color2 get_delete" target-type="<?php echo $type; ?>" data-id="<?php echo $value['U']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteConfirm">削除</button>

                        </td>
                    </tr>
                    <?php
                endforeach;
        echo '  </table>
                </div>
                </div>';
    endif;
}else if ($type == 'reservation') {
    if ($data):
        echo ' <div class="col-xs-12 col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered">
                <tr>
                <tr class="bg-color">
                <td><input type="checkbox" id="checkList" style="text-center"/></td>
                <td>フルネーム</td>
                <td>日付</td>
                <td>時間</td>
                <td>予約タグ</td>
                <td>日付</td>
                <td>
                    <button style="width: 176px !important;" class="btn btn-success color back_color2 get_delete_all" target-type='.$type.' data-id="" data-toggle="modal" data-target="#ModalDeleteAllConfirm">チェックした項目を削除</button>
                </td>
                </tr>
              ';
                foreach ($data as $key => $value):
                    ?>
                    <tr id="<?php echo $type ."_". $value['R']['id']; ?>">
                        <td><input type="checkbox" class="reId <?php echo $type;?>" value="<?php echo $value['R']['id'];?>" /></td>
                        <td title="<?php echo $value['U']['lastname'].".".$value['U']['firstname'];?>"><?php echo substr($value['U']['lastname'].".".$value['U']['firstname'],0,10 );?></td>
                        <td><?php echo $value['R']['date'] ?></td>
                        <td><?php echo substr($value['R']['start'], 0, 5) ?>～<?php echo substr($value['R']['end'],0, 5); ?></td>
                        <td title="<?php echo $value['T']['tag'];?>">
                            <?php echo $value['T']['tag'];?>
                        </td>
                        <td><?php echo $value['R']['modified']; ?></td>
                        <td><div >
                                <button class="btn btn-success back_color2 get_revert" target-type="<?php echo $type; ?>" data-id="<?php echo $value['R']['id']; ?>" data-toggle="modal" data-target="#ModalRevertConfirm">元に戻す</button>
                                <button class="btn btn-success back_color2 get_delete" target-type="<?php echo $type; ?>" data-id="<?php echo $value['R']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteConfirm">削除</button>
                            </div>
                        </td>
                    </tr>
                    <?php
                endforeach;
        echo '  </table>
                </div>
                </div>';
    endif;
}else if ($type == 'photo_send') {
    if ($data):
        echo ' <div class="col-xs-12 col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered">
                <tr class="bg-color">
                <td><input type="checkbox" id="checkList" style="text-center"/></td>
                <td>来院日時</td>
                <td>治療内容</td>
                <td>写真一覧</td>
                <td>日付</td>
                <td><button style="width: 176px !important;" class="btn btn-success color back_color2 get_delete_all" target-type='.$type.' data-id="" data-toggle="modal" data-target="#ModalDeleteAllConfirm">チェックした項目を削除</button></td>
                </tr>
              ';
                foreach ($data as $key => $value):
                ?>
                    <tr id="<?php echo $type ."_". $value['M']['id'];  ?>">
                    <td><input type="checkbox" value="<?php echo $value['M']['id'];?>" class="<?php echo $type; ?>" style="text-center"/></td>
                    <td><?php echo $value['R']['date'];  ?></td>
                    <td style="width:20%"><?php echo substr($value['R']['treatment_contents'], 0, 40)  ?></td>
                    <?php
                        $img_arr = explode(',',$value['M']['file']);
                        $image = '';
                        if(count($img_arr)>1){
                            $image = $img_arr[1];
                        }else{
                            $image = $value['M']['file'];
                        }
                    ?>
                    <td style="width:20%"> <?php echo $this->Html->image('/uploads/reservation_send_photos/'.$image,array('style' => 'width:60px;height:60px'));?></td>
                    <td><?php echo $value['M']['created'];  ?></td>
                    <td>
                        <div class="set_margin">
                            <button class="btn btn-success back_color2 get_revert" target-type="<?php echo $type;  ?>" data-id="<?php echo $value['M']['id'];  ?>" data-toggle="modal" data-target="#ModalRevertConfirm">元に戻す</button>
                            <button class="btn btn-success back_color2 get_delete" target-type="<?php echo $type;  ?>" data-id="<?php echo $value['M']['id'];  ?>" data-toggle="modal" data-target="#ModalDeleteConfirm">削除</button>
                        </div>
                    </td>
                </tr>
                <?php
                endforeach;
    echo '</table>
          </div>
          </div>';
    endif;
} else if ($type == 'menu_categories') {
    if ($data):
        echo ' <div class="col-xs-12 col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered">
                 <tr class="bg-color">
                 <td><input type="checkbox" id="checkList" style="text-center"/></td>
                 <td>大カテゴリータイトル</td>
                 <td>写真</td>
                 <td>日付</td>
                <td><button style="width: 176px !important;" class="btn btn-success color back_color2 get_delete_all" target-type='.$type.' data-id="" data-toggle="modal" data-target="#ModalDeleteAllConfirm">チェックした項目を削除</button></td>
                </tr>
                ';
                foreach ($data as $key => $value):
                    ?>
                    <tr id="<?php echo $type ."_". $value['M']['id']; ?>">
                        <td><input type="checkbox" value="<?php echo $value['M']['id'];?>" class="<?php echo $type; ?>" style="text-center"/></td>
                        <td style="width:500px"><?php echo $value['M']['title']; ?></td>
                        <td>
                            <?php echo $this->Html->image('/uploads/app_menus/'.$value['M']['image'],array('style' => 'width:60px;height:60px'));?>
                        </td>
                        <td><?php echo $value['M']['modified']; ?></td>
                        <td>
                            <button class="btn btn-success back_color2 get_revert" target-type="<?php echo $type; ?>" data-id="<?php echo $value['M']['id']; ?>" data-toggle="modal" data-target="#ModalRevertConfirm">元に戻す</button>
                            <button class="btn btn-success back_color2 get_delete" target-type="<?php echo $type; ?>" data-id="<?php echo $value['M']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteConfirm">削除</button>
                        </td>
                    </tr>
                    <?php
                endforeach;
        echo '</table>
              </div>
              </div>';
    endif;
}else if ($type == 'notification') {
    if ($data):
        echo ' <div class="col-xs-12 col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered">
                 <tr class="bg-color">
                 <td><input type="checkbox" id="checkList" style="text-center"/></td>
                 <td>通知日</td>
                 <td>時間</td>
                 <td>タイトル</td>
                <td>日付</td>
                <td><button style="width: 176px !important;" class="btn btn-success color back_color2 get_delete_all" target-type='.$type.' data-id="" data-toggle="modal" data-target="#ModalDeleteAllConfirm">チェックした項目を削除</button></td>
                </tr>
                ';
                foreach ($data as $key => $value):
                    ?>
                    <tr id="<?php echo $type ."_". $value['N']['id']; ?>">
                        <td><input type="checkbox" value="<?php echo $value['N']['id'];?>" class="<?php echo $type; ?>" style="text-center"/></td>
                        <td><?php echo $value['N']['delivery_date_value']; ?></td>
                        <td><?php echo $value['N']['delivery_time_value'] ?></td>
                        <td style="width:20%"><?php echo substr($value['N']['title'],0, 40) ?></td>
                        <td><?php echo $value['N']['modified']?></td>
                        <td>
                            <button class="btn btn-success back_color2 get_revert" target-type="<?php echo $type; ?>" data-id="<?php echo $value['N']['id']; ?>" data-toggle="modal" data-target="#ModalRevertConfirm">元に戻す</button>
                            <button class="btn btn-success back_color2 get_delete" target-type="<?php echo $type; ?>" data-id="<?php echo $value['N']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteConfirm">削除</button>
                        </td>
                    </tr>
                    <?php
                endforeach;
        echo    '</table>
                </div>
                </div>';
    endif;
}else if ($type == 'staff') {
    if ($data):
        echo ' <div class="col-xs-12 col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered">
                 <tr class="bg-color">
                 <td><input type="checkbox" id="checkList" style="text-center"/></td>
                 <td> 名前 </td>
                 <td> 役職 </td>
                 <td> 画像 </td>
                 <td> 日付 </td>
                 <td><button style="width: 176px !important;" class="btn btn-success color back_color2 get_delete_all" target-type='.$type.' data-id="" data-toggle="modal" data-target="#ModalDeleteAllConfirm">チェックした項目を削除</button></td>
                </tr>
                ';
                foreach ($data as $key => $value):
                    ?>
                    <tr id="<?php echo $type ."_". $value['S']['id']; ?>">
                        <td><input type="checkbox" value="<?php echo $value['S']['id'];?>" class="<?php echo $type; ?>" style="text-center"/></td>
                        <td><?php echo $value['S']['name']; ?></td>
                        <td><?php echo $value['S']['position'] ?></td>
                        <td><?php echo $this->Html->image('/uploads/staffs/'.$value['S']['image'], array( 'style' => 'width:60px;height:60px')); ?> </td>
                        <td><?php echo $value['S']['modified'] ?></td>
                        <td>
                            <button class="btn btn-success back_color2 get_revert" target-type="<?php echo $type; ?>" data-id="<?php echo $value['S']['id']; ?>" data-toggle="modal" data-target="#ModalRevertConfirm">元に戻す</button>
                            <button class="btn btn-success back_color2 get_delete" target-type="<?php echo $type; ?>" data-id="<?php echo $value['S']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteConfirm">削除</button>
                        </td>
                    </tr>
                    <?php
                endforeach;
        echo '</table>
              </div>
              </div>';
    endif;
}
?>
