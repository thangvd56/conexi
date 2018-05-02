 
<?php
if ($reservation):
    $i = 0;
    foreach ($reservation as $key => $value):
        ?>
        <div class="col-xs-12 col-md-12">
            <div class="panel radius">
                <table class="table table-bordered">
                    <tr>
                        <td><?php echo $value['User']['lastname'] . "." . $value['User']['firstname'] ?></td>
                        <td><?php echo $value['Reservation']['date'] ?></td>
                        <td style="width:20%"><?php echo substr($value['Reservation']['start'],0, 5)?>～<?php echo substr($value['Reservation']['end'],0, 5); ?></td>
                        <td><?php echo $value['Reservation']['description'] ?>
                            <div class="set_margin">
                                <button class="btn btn-success back_color2 get_revert" data-id="<?php echo $value['Reservation']['id']; ?>" data-toggle="modal" data-target="#ModalRevertConfirm">元に戻す</button>
                                <button class="btn btn-success back_color2 get_delete" data-id="<?php echo $value['Reservation']['id']; ?>" data-toggle="modal" data-target="#ModalDeleteConfirm">削除</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
        $i++;
    endforeach;
endif;
