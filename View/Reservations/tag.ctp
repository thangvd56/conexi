<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>
</div>
<?php echo $this->Form->create('Reservation', array()); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="hScroll">
            <?php
            $tags = array();            
            if ($this->request->data) {
                if (isset($this->request->data['ReservationTag']) && !empty($this->request->data['ReservationTag'])) {
                    foreach ($this->request->data['ReservationTag'] as $keyTag => $valueTag) {
                        array_push($tags, $valueTag['tag_id']);
                    }
                }
            }

            foreach ($tag as $key => $value) :
            ?>
            <div class="col-sm-4">
                <div class="staffBackground">
                    <div class="media">
                        <div class="media-body">
                            <div class="text-right" style="float:right">
                                <div class="checkboxStaff">
                                    <?php
                                    $check = '';
                                    if (in_array($value['Tag']['id'], $tags)) {
                                        $check = 'checked';
                                    }
                                    echo $this->Form->input('ReservationTag.tag_id.', array(
                                        'label' => false,
                                        'div' => false,
                                        'id' => false,
                                        'checked' => $check,
                                        'value' => $value['Tag']['id'],
                                        'type' => 'checkbox',
                                        'class' => 'tagChecked'));
                                    ?>
                                    <label for="checkStaff" class="<?php echo $check; ?>"></label>
                                </div>
                            </div>
                            <p class="text-center" style="margin-top: 10px;"><?php echo $value['Tag']['tag']; ?></p>
                        </div>
                  </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <button type="button" class="btn btn-back btn_color margin-top-10 btn-block"><?php echo BACK; ?></button>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
        <?php
        echo $this->Form->submit(NEXT, array('class' => 'btn btn_color margin-top-10 btn-block'));
        ?>
    </div>
</div>
<?php  echo $this->Form->end(); ?>
<?php echo $this->Html->css('reservation'); ?>
<script>
    $(function() {
        <?php if ($this->request->action === 'create') { ?>
            setTimeout(function() {
                if ( $.cookie('tag_is_refresh') == 1 ) {
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

        $('.checkboxStaff').click(function() {
            var is_checked = $(this).find('label');
            if (is_checked.hasClass('checked')) {
                $(this).find('.tagChecked').prop('checked', false);
                is_checked.removeClass('checked');
            } else {
                is_checked.addClass('checked');
                $(this).find('.tagChecked').prop('checked', true);
            }
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
        call_back();
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


        $(window).on('beforeunload', function(e){
            var confirmationMessage = '別ページへ遷移すると入力した予約内容が破棄されます。';
            e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
            $.cookie('tag_is_refresh', 1, { path: '/' });
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
                        $.cookie('reservation_uncomplete', 0, { path: '/' });
                        $(window).off('beforeunload');
                        window.location.href = '<?php echo Router::url("/", true); ?>';
                   }
                }
            });
        }
        $(document).ready(function() {
            same_height();
        });
        var a;
        $(window).resize(function() {
            clearTimeout(a);
            a = setTimeout(function(){
              same_height();
            },100);
        });
        function same_height() {
            var element = $('.media');
            var element_height = $(element[0]).outerHeight();
            for (var i = 1; i < element.length; i++) {
                if ($(element[i]).outerHeight() > element_height) {
                    element_height = $(element[i]).outerHeight();
                }
                console.log($(element[i]).outerHeight());
            }
            $('.staffBackground').css({'min-height': (element_height + 30) + 'px'});
        }
    });
</script>