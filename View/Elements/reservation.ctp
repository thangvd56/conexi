<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>
</div>
<?php
    echo $this->Form->create('Reservation', array(
        'class' => 'form-horizontal',
        'autocomplete' => 'off'
    ));
?>
<div class="row">
    <div class="col-lg-12">
        <?php
            if ($this->request->action === 'create') {
                echo $this->Form->input('shop_id', array('value' => $shop_id, 'type' => 'hidden'));
                if (!empty($user_id)) {
                    echo $this->Form->input('user_id', array('value' => $user_id, 'type' => 'hidden'));
                }
            }
            $display_date = '';
            if (isset($this->request->data) && !empty($this->request->data['Reservation']['date'])) {
                $timestamp = strtotime($this->request->data['Reservation']['date']);
                $day = $this->HtmlFormat->japanese_day(date('l', $timestamp));               

                $d = explode('-', $this->request->data['Reservation']['date']);
                $display_date .= $d[0] . '年' . $d[1] . '月' . $d[2] . '日('. $day .')';
            }
        ?>
        <fieldset>
            <div class="form-group">
                <p class="col-md-12">お日にちはいつですか？</p>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-sm-12">
                    <?php
                        $place_holder = $this->HtmlFormat->japanese_day(date('l', strtotime(date('Y-m-d H:i:s'))));
                        echo $this->Form->input('c_date', array(
                            'class' => 'form-control txtToDate',
                            'label' => false,
                            'type' => 'text',
                            'placeholder' => date('Y').'年'.date('m').'月'.date('d').'日('.$place_holder.')',
                            'onfocus' => 'blur();',
                            'autocomplete' => 'off',
                            'value' => $display_date
                        ));
                        echo $this->Form->hidden('date', array('class' => 'hidden-date'));
                    ?> 
                </div>
            </div>
            <div class="form-group">
                <p class="col-md-12">人数は、何名様ですか？</p>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-sm-12">
                    <label class="control-label col-md-3" style="text-align: left" for="adult">大人</label>
                    <?php
                        echo $this->Form->input('adult', array(
                            'class' => 'form-control allow_key',
                            'label' => false,
                            'placeholder' => '0名',
                            'type' => 'text'
                        ));
                    ?> 
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-sm-12">
                    <label class="control-label col-md-3" style="text-align: left" for="adult">子供</label>
                    <?php
                        echo $this->Form->input('child', array(
                            'class' => 'form-control allow_key',
                            'label' => false,
                            'placeholder' => '0名',
                            'type' => 'text'
                        ));
                    ?>
                </div>
            </div>            
        </fieldset>        
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <?php
        echo $this->Form->submit(NEXT, array(
            'class' => 'btn btn_color button margin-top-10 btn-block'
        ));
        ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<?php
echo $this->Html->css('numberPickup');
echo $this->Html->css('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
echo $this->Html->script('numberPickup');

?>
<style>
    .ui-widget-header {
        background: #fff !important;
        border: none !important;
    }

    .ui-datepicker-prev, .ui-datepicker-next , .ui-datepicker-prev:hover, .ui-datepicker-next:hover{
        background: #92C47D;
        border-radius: 50%;
    }

    .close {
        color: #848484;
        position: absolute;
        right: 3px;
        top: 0;
    }

</style>
<script>
    $(document).ready(function() {
        var dateToday = new Date();
        $('.txtToDate').datepicker({
            numberOfMonths: 2,
            dateFormat: 'yy年mm月dd日(月)',
            minDate : dateToday,
            beforeShow: function(textbox, instance) {
                $('#ui-datepicker-div').css({'padding': '20px', 'width': '30em'}).hide();
            },
            onSelect: function(date) {
                var a = date.replace('年', '-');
                var b = a.replace('月', '-');
                var c = b.replace('日(月)', '');
                $('.hidden-date').val(c);

                var weekday = ['日', '月', '火', '水', '木', '金', '土'];
                var a = new Date(c);
                var formatD = date.replace('(月)', '(' + weekday[a.getDay()] + ')');
                $('.txtToDate').val(formatD);
            }
        }, $.datepicker.regional['ja']).datepicker({
            'setDate': new Date()
        });

        $('body').on('keypress', '.allow_key', function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });

        $('form').submit(function (e) {
            if (($('#ReservationAdult').val() === '0' ||
                    $('#ReservationAdult').val() === '') &&
                    ($('#ReservationChild').val() === '0' ||
                    $('#ReservationChild').val() === '')) {
                alert('人数を入力してください。');
                e.preventDefault();
                return false;
            }
        });

        $('#ReservationAdult').calculator('ReservationAdult');
        $('#ReservationChild').calculator('ReservationChild');

        $(window).on('beforeunload', function(e) {
            var confirmationMessage = '別ページへ遷移すると入力した予約内容が破棄されます。';
            e.returnValue = confirmationMessage;
            $.cookie('chair_is_refresh', 1, { path: '/' });
            $.cookie('reservation_uncomplete', 1, { path: '/' });
            return confirmationMessage;
        });

    <?php if ($this->Session->check('ReservationId') && $this->request->action === 'create') { ?>
        $(window).on('unload', function() {
            clearReservation(<?php echo $this->Session->read('ReservationId'); ?>);            
        });
    <?php } ?>

        if (window.history && window.history.pushState) {
            window.history.pushState('back', null, null);
            $(window).on('popstate', function() {
                setTimeout(call_back(), 200);
            });
        }

        $('.btn-back').click(function() {
            setTimeout(function () {
                call_back();
                window.history.go(-1);
            }, 100);
        });

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

        function clearReservation(id) {
            $.ajax({
                url: URL + 'reservations/deleteReservation/?id=' + id,
                type: 'post',
                dataType: 'json',
                success: function (data) {
                   if (data) {
                        $.cookie('chair_is_refresh', 0, { path: '/' });
                        $.cookie('reservation_uncomplete', 0, { path: '/' });
                        $(window).off('beforeunload');
                        window.location.href = '<?php echo Router::url("/", true); ?>';
                   }
                }
            });
        }

        $('#ReservationAdult, #ReservationChild').focus(function() {
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                document.activeElement.blur();
                $(this).blur();
            }
        });
    });
</script>