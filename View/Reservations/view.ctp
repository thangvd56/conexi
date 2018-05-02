
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary">予約管理リストページ</h3>
    </div>
    
    <div>
        <div class="col-md-6">
            <span class="name">
            <?php echo $this->Html->link(h($user['User']['lastname'].' '.$user['User']['firstname']), '/customers/?id='.$user['User']['id'], array('class' => '')); ?>
            </span>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php echo $this->Html->link(CREATE_NEW, '/reservations/create/'.$user['User']['id'], array('class' => 'btn btn-block btn_color')); ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php echo $this->Html->link('削除情報', array('controller' => 'customers', 'action' => 'deleted?type=reservation'), array('class' => 'btn btn-block btn_color')); ?>
            </div>
        </div>
        
            
    </div>
    <div class="col-lg-12 table-responsive">
        <?php echo $this->Session->flash(); ?>
        <table class="table table-bordered">
        <thead>
            <tr style="font-size:10pt;" class="bg-color">
                <th>日付</th>
                <th>時間</th>
                <th>人数</th>
            <!-- <th>内容</th>-->
                <th>予約タグ</th>
                <th>担当名</th>
                <th>テーブル</th>
                <th>金額</th>
                <th>予約変更</th>
                <th>キャンセル</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!isset($reservation) || empty($reservation)) { ?>
            <tr class="empty">
            <td colspan="9">この来店履歴には予約データがありません。</td>
            </tr>
            <?php } else { ?>
                <?php foreach ($reservation as $key => $value) { ?>
            <tr class="<?php echo ($value['Reservation']['is_checkin']) ? 'checkedIn' : ''; ?>">
            <td><?php echo str_replace('-', '/', $value['Reservation']['date']); ?></td>
            <td>
                <?php if (!empty($value['Reservation']['start']) && $value['Reservation']['end']) { ?>
                <?php echo date('H:i', strtotime($value['Reservation']['start'])); ?> ～ <?php echo date('H:i', strtotime($value['Reservation']['end'])); ?></td>
                <?php } ?>
            <td><?php echo ($value['Reservation']['child'] + $value['Reservation']['adult']); ?></td>
            <td>
                 <?php
                                $tags = Hash::extract($value['ReservationTag'], '{n}.Tag.tag');
                                echo implode(',', $tags);
                ?>
            </td>
            <td><?php echo h($value['Staff']['name']); ?></td>
            <td><?php echo h($value['Chair']['chair_name']); ?></td>
            <td><?php echo $value['Reservation']['treatment_cost'].'円'; ?></td>
            <?php if (!$value['Reservation']['is_checkin']) { ?>
            <td class="text-center">
                <?php echo $this->Html->link('予約変更', '/reservations/edit/'. $value['Reservation']['id'], array('class' => 'btn back_color btn-success')); ?>
            </td>
            <td class="text-center"><?php echo $this->Html->link('削除', '/reservations/delete/'. $value['Reservation']['id'], array('class' => 'btn btn-success back_color btn-delete')); ?></td>
            <?php } else { ?>
            <td class="text-center">
                <?php if ($value['Reservation']['status'] === 'visit_only') {
                    echo $this->Html->link('予約変更', '/reservations/edit/'. $value['Reservation']['id'], array('class' => 'btn btn-success back_color', 'style' => 'color:white; background-color:red;'));
                }else{ ?>
                    <span style="font-weight:bold">来店済み</span>
                <?php } ?>
            </td>
            <td class="text-center"><?php echo $this->Html->link('削除', '/reservations/delete/'. $value['Reservation']['id'], array('class' => 'btn btn-success back_color btn-delete')); ?></td>
            <?php } ?>
            </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    </div>
</div>

<style>
    .empty{
        background : #F1F1F1;
    }
    .btn-gray {
        background: #F1F1F1;
    }
    .mgb10 {
        margin-bottom: 10px;
    }
    .name {
        font-weight: bold;
        font-size: 16px;
    }
    .checkedIn {
        background: #CEF6CE;
    }
</style>
<script>
    $(function () {
        $('.btn-delete').on('click', function(e) {
            if (!confirm('本当によろしいですか？')) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>