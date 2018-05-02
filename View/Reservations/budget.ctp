<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>

    <div class="col-lg-12">
        <?php
        echo $this->Form->create('Reservation', array(
            'class' => 'form-horizontal'));

        ?>
        <fieldset>
            <div class="form-group">
                <p class="col-md-6 col-sm-12">金額を入力してください</p>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-sm-12">
                    <?php
                    echo $this->Form->input('treatment_cost', array(
                        'class' => 'form-control txtToDate allow_key',
                        'label' => false,
                        'type' => 'text',
                        'placeholder' => '￥'));
                    ?>
                    <br/>
                    <p>※未入力でも次へ進めます。</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-3 col-sm-6">
                    <button type="button" class="btn btn_color margin-top-10 btn-block btn-back"><?php echo BACK; ?></button>
                    <?php
//                    echo $this->Html->link(BACK, 'javascript::void(0)', array(
//                        'class' => 'btn btn_color margin-top-10 btn-block btn-back'));
                    ?>
                </div>
                <div class="col-md-3 col-sm-6">
                    <?php
                    echo $this->Form->submit(NEXT, array(
                        'class' => 'btn btn_color margin-top-10 btn-block'));
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
                if ( $.cookie('budget_is_refresh') == 1 ) {
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

        $('#ReservationTreatmentCost').calculator('ReservationTreatmentCost');
        $('body').on('keypress', '.allow_key', function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });

        $('form').submit(function (e) {
            $(window).off('beforeunload');
            $(window).off('unload');           
        });

//        if (window.history && window.history.pushState) {
//            window.history.pushState('back', null, null);
//            $(window).on('popstate', function() {
//                setTimeout(call_back(), 200);
//            });
//        }

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

        $(window).on('beforeunload', function(e){
            var confirmationMessage = '別ページへ遷移すると入力した予約内容が破棄されます。';
            e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
            $.cookie('budget_is_refresh', 1, { path: '/' });
            $.cookie('reservation_uncomplete', 1, { path: '/' });
            return confirmationMessage;              // Gecko, WebKit, Chrome <34
        });

        function clearReservation(id){
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
                        $.cookie('reservation_uncomplete', 0, { path: '/' });
                        $(window).off('beforeunload');
                        window.location.href = '<?php echo Router::url("/", true); ?>';
                   }
                }
            });
        }
    });

    $(' #ReservationTreatmentCost').focus(function() {
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            document.activeElement.blur();
            $(this).blur();
        }
    });
</script>
