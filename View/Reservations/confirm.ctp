<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>
    
    <div class="col-lg-12">
        <?php
        echo $this->Form->create('Reservation', array('class' => 'form-horizontal', 'id' => 'form-confirm-reservation'));
        echo $this->Form->input('Reservation.is_completed', array('type' => 'hidden', 'value' => 1));
        echo $this->Form->input('Reservation.status', array('type' => 'hidden', 'value' => 'visit'));
        ?>
        <fieldset>
            <div class="form-group">
                <p class="col-md-8 col-sm-12">予約を確定しますか？</p>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-sm-12">
                    <table class="table tabl-hover table-bordered table-striped">
                        <?php
                        $reservation = $this->request->data['Reservation'];
                        $user = $User;
                        $date = '';
                        $staff = '';
                        $peopleNmber = '';
                        $startTime = '';
                        $endTime = '';
                        $tags = '';
                        $price = '';

                        if (!empty($reservation)) {
                            $date = $reservation['date'];
                            $peopleNmber = ($reservation['adult'] + $reservation['child']). '名';
                            $startTime = date('H:i', strtotime($reservation['start']));
                            $endTime = date('H:i', strtotime($reservation['end']));
                            $price = $reservation['treatment_cost'].'円';

                            if (isset($this->request->data['Staff']['name'])) {
                                $staff = $this->request->data['Staff']['name'];
                            }

                            if (!empty($this->request->data['ReservationTag'])) {
                                $tags = Hash::extract($this->request->data['ReservationTag'], '{n}.Tag.tag');
                                $tags = implode(',', $tags);
                            }
                        }
                        ?>
                        <tr>
                            <td>名前: <?php echo ' ' . $user['lastname_kana'] . ' ' . $user['firstname_kana']; ?></td>
                            <td class="">電話番号: <?php echo ' ' . $user['contact']; ?></td>
                        </tr>
                        <tr>
                            <td>日程</td>
                            <td class="dateTreat"><?php echo $date; ?></td>
                        </tr>
                         <tr>
                            <td>担当者名</td>
                            <td><?php echo $staff; ?></td>
                        </tr>
                         <tr>
                            <td> 人数</td>
                            <td><?php echo $peopleNmber; ?></td>
                        </tr>
                         <tr>
                            <td>始まり時間</td>
                            <td><?php echo $startTime; ?></td>
                        </tr>
                         <tr>
                            <td>終わり時間</td>
                            <td><?php echo $endTime; ?></td>
                        </tr>
                         <tr>
                            <td>タグ</td>
                            <td><?php echo $tags; ?></td>
                        </tr>
                         <tr>
                            <td>金額</td>
                            <td><?php echo $price; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-sm-6">
                    <button type="button" class="btn btn_color margin-top-10 btn-block btn-back"><?php echo BACK; ?></button>
                </div>
                <div class="col-md-4 col-sm-6">
                    <?php
                    echo $this->Form->submit('確定', array(
                        'class' => 'btn btn_color margin-top-10 btn-block',
                        'id' => 'confirm-reservation'
                    ));
                    ?>
                </div>
            </div>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<?php
echo $this->Html->css('numberPickup');
echo $this->Html->script('numberPickup');
?>

<style>
.jcalculator1, .jcalculator2 {
    right: -210px !important;
}
.close {
    color: #848484;
    position: absolute;
    right: 3px;
    top: 0;
}
</style>
<script>
    $(function() {
        <?php if ($this->request->action === 'create') { ?>
            setTimeout(function() {
                if ( $.cookie('confirm_is_refresh') == 1 ) {
                    clearReservation(<?php echo $this->Session->read('ReservationId'); ?>);
                }
            }, 200);

            //Session timer expire
            setTimeout(function() {
                clearReservation(<?php echo $this->Session->read('ReservationId'); ?>);
            }, 150000);

            $(window).on('unload', function() {
                clearReservation(<?php echo $this->Session->read('ReservationId'); ?>);
            });
        <?php } ?>

        $('.dateTreat').text(currentDate());

        $('#confirm-reservation').on('click', function (event) {
            event.preventDefault();
            var el = $(this);
            el.prop('disabled', true);
            $('#form-confirm-reservation').submit();
            setTimeout(function() {
                el.prop('disabled', false);
            }, 1000);
        });

        $(window).on('beforeunload', function(e) {
            var confirmationMessage = '別ページへ遷移すると入力した予約内容が破棄されます。';
            e.returnValue = confirmationMessage;
            $.cookie('confirm_is_refresh', 1, { path: '/' });
            $.cookie('reservation_uncomplete', 1, { path: '/' });
            return confirmationMessage;
        });

        $('.btn-back').click(function() {
            setTimeout(function () {
                call_back();
                window.history.go(-1);
            }, 100);
        });
        call_back();
        function call_back() {
            $.cookie('chair_is_refresh', 0, { path: '/' });
            $.cookie('appointment_is_refresh', 0, { path: '/' });
            $.cookie('staff_is_refresh', 0, { path: '/' });
            $.cookie('tag_is_refresh', 0, { path: '/' });
            $.cookie('budget_is_refresh', 0, { path: '/' });
            $.cookie('user_is_refresh', 0, { path: '/' });
            $.cookie('confirm_is_refresh', 0, { path: '/' });
            $.cookie('reservation_uncomplete', 0, { path: '/' });
            $(window).off('beforeunload');
            $(window).off('unload');
        }

        $('form').submit(function (e) {
            $(window).off('beforeunload');
            $(window).off('unload');
        });

        function currentDate() {
            var monthNames = ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];
            var d = '<?php echo $date; ?>';
            var d2 = d.split('-');
            var yy = d2[0]; var mm = d2[1];
            return yy + '年' + monthNames[mm-1] + d2[2] + '日';
        }

        function clearReservation(id) {
            $.ajax({
                url: URL + 'reservations/deleteReservation/?id=' + id,
                type: 'post',
                dataType: 'json',
                success: function (data) {
                   if (data) {
                        $.cookie('appointment_is_refresh', 0, { path: '/' });
                        $.cookie('staff_is_refresh', 0, { path: '/' });
                        $.cookie('tag_is_refresh', 0, { path: '/' });
                        $.cookie('budget_is_refresh', 0, { path: '/' });
                        $.cookie('user_is_refresh', 0, { path: '/' });
                        $.cookie('confirm_is_refresh', 0, { path: '/' });
                        $.cookie('reservation_uncomplete', 0, { path: '/' });
                        $(window).off('beforeunload');
                        window.location.href = '<?php echo Router::url("/", true); ?>';
                   }
                }
            });
        }
    });
</script>