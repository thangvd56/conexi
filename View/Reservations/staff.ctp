<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>

        <div class="col-md-12 hScroll row">
            <?php
            foreach ($staff as $key => $value) :
                if ($value['Staff']['published'] == '1') {
                    $is_check = 'checked';
                } else {
                    $is_check = '';
                }
                if ($value['Staff']['is_at_work'] == '1') {
                    $is_at_work = 'checked';
                } else {
                    $is_at_work = '';
                }
            ?>

            <div class="col-sm-6 col-md-4">
                <div class="staffBackground">
                    <div class="text-right" style="float:right">
                        <?php
                        $check = '';
                          if ($this->request->data) {
                              if ($this->request->data['Reservation']['staff_id'] == $value['Staff']['id']) {
                                  $check = 'checked';
                              }
                          }
                        ?>
                        <div class="checkboxStaff" data-id="<?php echo $value['Staff']['id']; ?>">
                            <label for="checkStaff" class="<?php echo $check; ?>"></label>
                        </div>
                    </div>
                    <div class="media">
                      <a class="pull-left" href="#">
                            <?php
                                if (!empty($value['Staff']['image'])) {
                                    echo $this->Html->image('/uploads/staffs/'.$value['Staff']['image'],
                                        array(
                                        'class' => 'img-responsive',
                                        'alt' => $value['Staff']['name'],
                                    ));
                                } else {
                                    echo '<img src="'+URL+'img/noimage.jpg" class="img-responsive img-center">';
                                }
                            ?>
                      </a>
                      <div class="media-body">
                          <h4 class="media-heading"><?php echo $value['Staff']['name']; ?></h4>
                        <button class="btn btn-success color specific_height ">役職</button> <span><?php echo $value['Staff']['position'] ?></span>
                        <p><?php echo $value['Staff']['introduction'] ?></p>
                     </div>
                  </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <div class="col-lg-12 margin-top-12">
        <?php echo $this->Form->create('Reservation', array()); ?>
        <div class="col-md-6">
            <button type="button" class="btn btn_color btn-back margin-top-10 btn-block"><?php echo BACK; ?></button>
            <?php
            //echo $this->Html->link(BACK, 'javascript::void(0);', array('class' => 'btn btn_color btn-back margin-top-10 btn-block'));
            ?>
        </div>

         <div class="col-md-6">
             <?php
                echo $this->Form->input('staff_id', array('type' => 'hidden', 'class' => 'staff_id'));
                echo $this->Form->submit(NEXT, array('class' => 'btn btn_color margin-top-10 btn-block'));
                echo $this->Form->end();
            ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    </div>
    
</div>

<style>
    .margin-top-12 {
        margin-top: 12px;
    }
    /**
 * Checkbox Four
 */
.checkboxStaff {
    background: #000;
    height: 40px;
    margin: 0;
    padding: 5px 12px;
    position: relative;
    width: 40px;
    top: 0;
    right: 0;
    border-radius: 100%;
    position: relative;
    -webkit-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
    -moz-box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
    box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
}
/**
 * Create the checkbox button
 */
.checkboxStaff label {
display: block;
width: 30px;
height: 30px;
border-radius: 100px;
-webkit-transition: all .5s ease;
-moz-transition: all .5s ease;
-o-transition: all .5s ease;
-ms-transition: all .5s ease;
transition: all .5s ease;
cursor: pointer;
position: absolute;
top: 5px;
left: 5px;
z-index: 1;
background: #fff;
-webkit-box-shadow:inset 0px 1px 3px rgba(0,0,0,0.5);
-moz-box-shadow:inset 0px 1px 3px rgba(0,0,0,0.5);
box-shadow:inset 0px 1px 3px rgba(0,0,0,0.5);
}
/**
 * Create the checked state
 */
.checkboxStaff .checked {
background: #787878;
}

.staffBackground{
    margin-top: 15px;
    background: #fff;
    padding: 10px;
    border-radius: 10px 10px 10px 10px;
    -moz-border-radius: 10px 10px 10px 10px;
    -webkit-border-radius: 10px 10px 10px 10px;
    border: 1px solid #f4f4f4;
    -webkit-box-shadow: 2px 0px 5px -2px rgba(0,0,0,0.75);
    -moz-box-shadow: 2px 0px 5px -2px rgba(0,0,0,0.75);
    box-shadow: 2px 0px 5px -2px rgba(0,0,0,0.75);
}

.img-responsive {
    width: 90px;
    height: 90px;
    margin-bottom: 10px;
}
.button.btn.btn-success.color {
    width: 80px !important;
}
.hScroll {
    padding-top: 10px;
    height: 300px;
    overflow-y: scroll;
}
.staffBackground .media {
    margin: 0 !important;
}
.staffBackground .media-body h4,
.staffBackground .media-body .btn,
.staffBackground .media-body span,
.staffBackground .media-body p {
    display: block;
    margin-bottom: 5px;
}
.staffBackground .media-body p {
    margin-bottom: 0;
}
</style>

<script>
    $(function() {
        <?php if ($this->request->action === 'create') { ?>
            setTimeout(function() {
                if ( $.cookie('staff_is_refresh') == 1 ) {
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
            var id = $(this).attr('data-id');
            var is_checked = $(this).find('label');
            if (is_checked.hasClass('checked')) {
                
            } else {
                $('.hScroll').find('.checked').removeClass('checked');
                is_checked.addClass('checked');
                $('.staff_id').val(id);
            }
        });

        $('form').submit(function (e) {
            if ($('.staff_id').val() === '0' || $('.staff_id').val() === '') {
                alert('担当者を選択してください。');
                e.preventDefault();
                return false;
            } else {
                $(window).off('beforeunload');
                $(window).off('unload');
            }
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
            $.cookie('staff_is_refresh', 1, { path: '/' });
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