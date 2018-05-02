<div class="record table-responsive">
    <table class="table table-bordered">
        <thead style="background-color: #86cb34;color: #fff;">
            <tr style="font-size:10pt;">
                <th>日付</th>
                <th>名前</th>
                <th>時間</th>
                <th>人数</th>
                <th>予約タグ</th>
                <th>担当名</th>
                <th>テーブル</th>
                <th>金額</th>
                <th>チェックイン</th>
                <th>予約変更</th>
                <th>キャンセル</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($data)): ?>
                <?php foreach ($data as $key => $value): ?>
                    <?php
                    $start = substr($value['Reservation']['start'], 0,5);
                    $end = substr($value['Reservation']['end'], 0, 5);
                    $is_checkin = $value['Reservation']['is_checkin'];
                    $is_checkin == 1 ? $style = "style='background-color: rgba(87, 228, 30, 0.18);font-size:9pt;'" : $style = "";
                    $tempDate = $value['Reservation']['date'];
                    $y = date('Y', strtotime($tempDate));
                    $m = date('m', strtotime($tempDate));
                    $d = date('d', strtotime($tempDate));
                    $D = date('D', strtotime($tempDate));
                    switch ($D) {
                        case 'Sun':
                            $day = SUNDAY;
                            break;
                        case 'Mon':
                            $day = MONDAY;
                            break;
                        case 'Tue':
                            $day = TUESDAY;
                            break;
                        case 'Wed':
                            $day = WEDNESDAY;
                            break;
                        case 'Thu':
                            $day = THURSDAY;
                            break;
                        case 'Fri':
                            $day = FRIDAY;
                            break;
                        case 'Sat':
                            $day = SATURDAY;
                            break;
                        default:
                            $day = '';
                            break;
                    }
                    ?>
                    <tr id="<?php echo $value['Reservation']['id']; ?>" <?php echo $style; ?>>
                        <td><?php echo $y . '/' . $m . '/' . $d . '(' . $day . ')'; ?></td>
                        <?php
                                $lastname = "";
                                $firstname ="";
                                if ($value['User']['lastname'] != "" && $value['User']['firstname'] !="") {
                                  $lastname = $value['User']['lastname'];
                                  $firstname = $value['User']['firstname'];
                                } else {
                                  $lastname = $value['User']['lastname_kana'];
                                  $firstname = $value['User']['firstname_kana'];
                                }
                        ?>
                        <td title="<?php echo $lastname .".". $firstname; ?>">
                            <?php
                                echo $this->Html->link($lastname.".". $firstname, array(
                                        'controller' => 'customers',
                                        'action' => 'index',
                                        '?' => array(
                                            'id' => $value['User']['id']
                                        )
                                    ), array(
                                        'class' => 'btn btn-success back_color text-center',
                                        'style' => 'width: auto;padding: 6px 10px;',
                                        'title' => $lastname.".".$firstname
                                    )
                                );
                             ?>
                        </td>
                        <?php if ($start) { ?>
                            <td><?php echo $start . '～' . $end; ?></td>
                        <?php } else {?>
                            <td></td>
                        <?php } ?>
                        <td>
                            <?php
                                if ($value['Reservation']['adult'] ==0 && $value['Reservation']['child']==0){
                                    echo "";
                                } else {
                                    echo ($value['Reservation']['adult'] + $value['Reservation']['child']);
                                }
                            ?>
                        </td>
                        <td title="Tag">
                            <?php
                                $tags = Hash::extract($value['ReservationTag'], '{n}.Tag.tag');
                                echo implode(',', $tags);
                            ?>
                        </td>
                        <td><?php echo $value['Staff']['name'] ?></td>
                        <td title="<?php echo $value['Chair']['chair_name']; ?>"><?php echo $value['Chair']['chair_name']; ?></td>
                        <td>
                            <?php
                                if ($value['Reservation']['treatment_cost']==0) {
                                    echo "";
                                } else {
                                    echo $value['Reservation']['treatment_cost'] . '円';
                                }
                            ?>
                        </td>
                        <?php if ($is_checkin == 1) {
//                            <!--Checked-in no reservation-->
                                if ($value['Reservation']['status'] =="visit_only") { ?>
                                  <td style="color:white"><?php echo $this->Html->link('治療内容を入力', array('controller' => 'reservations', 'action' => 'edit', $value['Reservation']['id']), array('class' => 'btn btn-danger text-center', 'style' => 'color:white; background-color:red; width: auto;padding: 6px 10px;')); ?></td>
                                  <td></td>
                                <?php } else { ?><!--Already Checked-in-->
                                  <td class="text-center"><span style="font-weight:bold">来店済み</span></td>
                                  <td></td>
                                  <?php }
                              } else { ?>
                            <td><button class="btn btn-success back_color btn_checkin text-center" target-title="<?php echo $lastname . '.' . $firstname; ?>" id="<?php echo $value['Reservation']['id']; ?>" style="width: auto;padding: 6px 11px;">チェックイン</button></td>
                            <td><?php echo $this->Html->link('予約変更', array('controller' => 'reservations', 'action' => 'edit', $value['Reservation']['id']), array('class' => 'btn btn-success back_color text-center', 'style' => 'width: auto;padding: 6px 10px;')); ?></td>
                        <?php } ?>
                        <td><button class="btn btn-success back_color get_reservation_id" targat-date="<?php echo $y . '/' . $m . '/' . $d . '(' . $day . ')'; ?>"  id="<?php echo $value['Reservation']['id']?>" data-toggle="modal" data-target="#ModalDeleteReservation">削除</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $(function () {

        //Alert modal and get user ID
        $(".btn_checkin").click(function () {
            var id = $(this).attr('id');
            $(".customer_id").val(id);
            $("#modalCheckin").modal("show");
            var title =$(this).attr('target-title');
            $("#checkinLabel").html(title + " をチェックインさせる");
        });
        //Hide modal
        $(".btn_cancel_checkin").click(function () {
            $("#modalCheckin").modal("hide");
        });

    });
</script>