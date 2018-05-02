<?php
echo $this->Html->css('customerLedger');
echo $this->Html->css('visitRecord');
$yesterday = date('Y-m-d', strtotime("-1 day"));
$previous_day = substr($yesterday, 8, 2);
$month_of_previous_day = substr($yesterday, 5, 2);
echo $this->Html->script('bootstrap-datepicker1.6.1.js');
?>

<style>
    @charset "utf-8";
    .btn-block:hover{
        color:white;
    }
    #dtLabel:hover{
        cursor: pointer;
    }
    .row-right,.row-left:hover{
        cursor: pointer;
    }
    .nav_cal{
        font-size: 11pt;
    }
</style>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <h1 class="page-header">
                来店履歴
            </h1>
            <div class="form-inline form-custom">
                <?php
                $role = $this->Session->read('Auth.User.role');
                if ($role == ROLE_HEADQUARTER) : ?>
                    <div class="form-group">
                        <?php
                            echo $this->Form->input('shop_id', array(
                            'id'   => 'shop_id',
                            'type' => 'select',
                            'name' => 'shop_id',
                            'class' => 'shop_name form-control',
                            'label' => false,
                            'options' => $shop,
                            'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : ''
                            ));
                        ?>
                    </div>
                <?php 
                else : 
                    echo $this->Form->input('shop_id', array(
                    'id'   => 'shop_id',
                    'type' => 'hidden',
                    'name' => 'shop_id',
                    'value' => $shop_id
                    ));
                endif; ?>
                    <div class="form-group">
                        <?php
                        $select_option = array(
                            'today' => '当日予約',
                            'monthly' => '月別',
                            'schedule' => '当日テーブル'
                        );
                        echo $this->Form->input('option', array(
                            'id' => 'select_option',
                            'type' => 'select',
                            'class' => 'form-control',
                            'options' => $select_option,
                            'label' => false
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        $year_select = array();
                        for ($i = 2016; $i <= date('Y'); $i++) {
                            $year_select[$i] = $i;
                        }
                        $end_year = end($year_select);
                        $d = new DateTime($end_year);
                        $d->modify('+1 year');
                        $year_select[$d->format('Y')] = $d->format('Y');
                        
                        echo $this->Form->input('year', array(
                            'id' => 'select_year',
                            'type' => 'select',
                            'class' => 'form-control',
                            'options' => $year_select,
                            'default' => date('Y'),
                            'label' => false
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                        for ($j = 1; $j <= 12; $j++) {
                            $month_select[$j] = $j . '月';
                        }
                        echo $this->Form->input('month', array(
                            'id' => 'select_month',
                            'type' => 'select',
                            'class' => 'form-control',
                            'options' => $month_select,
                            'default' => date('m'),
                            'label' => false
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                            $str_shop_id = '';
                            if (isset($shop_id) && $role == ROLE_HEADQUARTER) {
                                $str_shop_id = '?shop_id='.$shop_id;
                            }
                            echo $this->Html->link('新規予約', array(
                                'controller' => 'reservations',
                                'action' => 'create/'.$str_shop_id),
                                array(
                                    'class' => 'btn btn-block btn_color button')
                            );
                        ?>
                    </div>
                    <div class="form-group">
                        <?php
                            if (isset($shop_id) && $role == ROLE_HEADQUARTER) {
                                $str_shop_id = '&shop_id='.$shop_id;
                            }
                            echo $this->Html->link('削除情報', array(
                                'controller' => 'customers',
                                'action' => 'deleted?type=reservation'.$str_shop_id),
                                array(
                                    'class' => 'btn btn-block btn_color')
                            );
                        ?>
                    </div>
            </div>
            <div class="row nav_cal">
                <div class="col-xs-12">
                    <span class="row-left"><</span>
                    <div id="dtLabel">Today</div>
                    <span class="row-right">></span>
                </div>
            </div>
            <span class="calIcon" class="glyphicon" style="display:none;"></span>
        </div>
    </div>
    <div id="record_loading" class="hide text-center"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;ローディング</div>
    <div id="record_list"></div>

    <div class="row">
        <div class="col-lg-12">
            <div id="calendar"></div>
        </div>
    </div>

<div class="modal fade" id="modalCheckin" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content col-md-10 col-md-offset-1">
            <div class="modal-header border">
                <button type="button" class="close closeSmallModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-xs-12">
                        <h4  class="modal-title" id="checkinLabel"></h4>
                    </div>
                </div>
            </div>
            <div class="hide checkin_loading" style="margin-left: 45%;margin-bottom: -78px;"><?php echo $this->Html->image('loading.gif'); ?></div>
            <div class="modal-footer">
                <div class="form-group bottom_set">
                    <div class="col-xs-6 col-sm-6 col-md-6 text-center">
                        <div class="panel-default btn_cancel_checkin">
                            <div class="icon change_height">
                                <div class="menu-icon cancel"></div>
                                <p class="menu-icon-text">キャンセル</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-center">
                        <input type="hidden" class="customer_id">
                        <div class="panel-default btn_checkin_customer">
                            <div class="icon change_height">
                                <div class="menu-icon checkin"></div>
                                <p class="menu-icon-text">チェックイン</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal Delete Reservation-->
<div id="ModalDeleteReservation" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php
                echo $this->Form->create('Reservation', array('id' => 'delete_reservation'));
                ?>
                <div class="clearfix">&nbsp;</div>
                <div id="label-delete-confirm" style="font-weight: bold"></div>
                <div class="clearfix">&nbsp;</div>
                <input type="checkbox" name="del_physical"  id="del1" class="hide" /><div style="font-weight: bold" class="hide">&nbsp;物理的に削除する。</div> <br>
                <input type="hidden" name="reservation_id" class="reservation_id"/>
                <div class="error-message" id="error-msg-delete"></div>
                <div class="hide delete_loading" style="font-weight: bold"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="submit" id="btn_confirm_delete" value="はい" class="btn btn-success but_design" style="width: 100px;">
                </div>
                <div class="clearfix">&nbsp;</div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<div id="event-reservation" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="clearfix">&nbsp;</div>
                <table>
                    
                </table>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <input type="button" value="閉じる" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                </div>
                <div class="clearfix">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(function () {

    var curDate = new Date();

    if ($('#select_option').val() == 'monthly') {
        $('.row-left').addClass('prev_records_by_month');
        $('.row-right').addClass('next_records_by_month');
        dateChangeText($('#select_option').val(), curDate.getMonth());
    } else if ($('#select_option').val() == 'today') {
        $('.row-left').addClass('prev_records_by_day');
        $('.row-right').addClass('next_records_by_day');
        $('#select_month, #select_year').prop('disabled', true);
        dateChangeText($('#select_option').val(), curDate.getDate());
    }

    function dateChangeText(option, number) {
        if (option == 'today') {
            $("#dtLabel").text(number + '日 ');
        } else if (option == 'monthly') {
            $("#dtLabel").text(number + '月 ');
        }
    }
  
    // Daily record view
    $('.row-left').on('click', function(){
        if ($(this).hasClass('prev_records_by_day')) {
            var option = 'today';
            var year = $("#select_year").val();
            var shop = $("#select_shop").val();

            curDate.setDate(curDate.getDate() - 1);
            var month = curDate.getMonth() + 1;
            var pre_day = curDate.getDate();
            fetch_record_by_next_pre_day(option,year, month, pre_day,shop);
            
            dateChangeText(option, curDate.getDate());
            console.log(curDate.getDate() + "==" + curDate);
        } else if ($(this).hasClass('prev_records_by_month')) {
            var option = 'monthly';
            var year = $("#select_year").val();
            var shop = $("#select_shop").val();
            var pre_day = '';

            curDate.setMonth(curDate.getMonth() - 1);
            var month = curDate.getMonth() + 1;
            
            fetch_record_by_next_pre_day(option,year, month, pre_day,shop);
            dateChangeText(option, month);
            console.log(curDate.getMonth() + "==" + curDate);
        }
        return false;
    });

    $('.row-right').on('click', function(){
        if ($(this).hasClass('next_records_by_day')) {
            var option = 'today';
            var year = $("#select_year").val();
            var shop = $("#select_shop").val();

            curDate.setDate(curDate.getDate() + 1);
            var month = curDate.getMonth() + 1;
            var pre_day = curDate.getDate();

            fetch_record_by_next_pre_day(option,year, month, pre_day,shop);

            dateChangeText(option, curDate.getDate());

        } else if ($(this).hasClass('next_records_by_month')) {
            var option = 'monthly';
            var year = $("#select_year").val();
            var shop = $("#select_shop").val();
            var pre_day = '';

            curDate.setMonth(curDate.getMonth() + 1);
            var month = curDate.getMonth() + 1;
            fetch_record_by_next_pre_day(option,year, month, pre_day,shop);

            dateChangeText(option, month);
        }
        return false;
    });

    //Fetch record by date
    $("#select_year, #select_month").on("change", function () {
        var myDay = new Date();
        var option =$("#select_option").val();
        var year = $("#select_year").val();
        var month = $("#select_month").val();
        var today = myDay.getDate();
        var shop  = $("#shop_id").val();
        fetch_record_by_onchange(option, year, month,today,shop);
     });

     $("#shop_id").on("change", function () {
        window.location.replace(URL+"records/?shop_id="+$("#shop_id").val());
     });

    $("#select_month").on("change", function () {
        var option =$("#select_option").val();
        var year = $("#select_year").val();
        var month = $("#select_month").val();
        var today = '';
        var shop  = $("#shop_id").val();
        fetch_record_by_onchange(option, year, month,today,shop);

        curDate.setMonth(month - 1);
        dateChangeText(option, month);
     });

    $("#select_option").on("change", function () {
        if ($(this).val() == 'today') {
            $('.row-left').removeClass('prev_records_by_month');
            $('.row-right').removeClass('next_records_by_month');
            $('.row-left').addClass('prev_records_by_day');
            $('.row-right').addClass('next_records_by_day');
            var myDay = new Date();
            var option = 'today';
            var year = $("#select_year").val();
            var month = $("#select_month").val();
            var today = myDay.getDate();
            var shop  = $("#shop_id").val();

            fetch_record_by_onchange(option, year, month,today,shop);

            curDate = myDay;
            dateChangeText(option, curDate.getDate());
            $('#select_month, #select_year').prop('disabled', true);
            //$('#select_year').removeAttr('disabled');
            $('.nav_cal, #record_list').show();
            $('#calendar').hide();

        } else if ($(this).val() == 'monthly') {
            $('.row-left').removeClass('prev_records_by_day');
            $('.row-right').removeClass('next_records_by_day');
            $('.row-left').addClass('prev_records_by_month');
            $('.row-right').addClass('next_records_by_month');

            var option = 'monthly';
            var year = $("#select_year").val();
            var month = $("#select_month").val();
            var today = '';
            var shop  = $("#shop_id").val();
            fetch_record_by_onchange(option, year, month,today,shop);

            curDate.setMonth(month - 1);
            dateChangeText(option, curDate.getMonth() + 1);
            $('#select_month, #select_year').removeAttr('disabled');
            $('.nav_cal, #record_list').show();
            $('#calendar').hide();
            
        } else if ($(this).val() == 'schedule') {
            $('#select_month, #select_year').prop('disabled', true);
            $('.nav_cal, #record_list').hide();
            $('#calendar').show();
            render_existing_event();
        }
    });

    function fetch_record_by_next_pre_day(option,year, month, next_pre_day,shop) {
        $.ajax({
        url: "<?php echo Router::url(array('controller' => 'records', 'action' => 'fetch_records'))?>",
        type: "get",
        data: "action=onchange&option=" + option + "&year=" + year + "&month=" + month + "&today=" + next_pre_day + "&shop_id=" + shop,
        beforeSend: function () {
            $("#record_list").html('<div class="text-center"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;ローディング</div>');
        },
        success: function (respond) {
            $("#record_list").html(respond);
        },
        error: function (xhr, ajaxOptions, throwError) {
            console.log("Error:" + xhr.status);
        }
        });
    }
	
    //Fetch records show default
    function fetch_records() {
    
        $.ajax({
        url: "<?php echo Router::url(array('controller' => 'records', 'action' => 'fetch_records')) ?>",
        type: "get",
        data: "action=default&shop_id="+$("#shop_id").val(),
        beforeSend: function () {
            $("#record_loading").removeClass("hide");
        },
        success: function (respond) {
            $("#record_list").html(respond);
        },
        error: function (xhr, ajaxOptions, throwError) {
            console.log("Error:" + xhr.status);
        },
        complete: function () {
            $("#record_loading").addClass("hide");
        }
        });
    }

    fetch_records();

    function fetch_record_by_onchange(option, year, month,today,shop) {
        $.ajax({
        url: "<?php echo Router::url(array('controller' => 'records', 'action' => 'fetch_records')) ?>",
        type: "get",
        data: "action=onchange&option=" + option + "&year=" + year + "&month=" + month + "&today=" + today + "&shop_id=" + shop,
        beforeSend: function () {
            $("#record_list").html('<div class="text-center"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;ローディング</div>');
        },
        success: function (respond) {
            $("#record_list").html(respond);
        },
        error: function (xhr, ajaxOptions, throwError) {
            console.log("Error:" + xhr.status);
        }
        });
    }

    //Check in user
    $("body").on("click", ".btn_checkin_customer", function () {
        var id = $(".customer_id").val();
        $.ajax({
        url: "<?php echo Router::url(array('controller' => 'records', 'action' => 'index')); ?>",
        type: "get",
        data: "action=checkin&id=" + id+'&shop_id='+$("#shop_id").val(),
        dataType: "json",
        beforeSend: function () {
            $(".checkin_loading").removeClass("hide");
        },
        success: function (respond) {
            if (respond.result === "success") {
            $("#modalCheckin").modal("hide");
                var option =$("#select_option").val();
                var year = $("#select_year").val();
                var month = $("#select_month").val();
                var today = curDate.getDate();
                var shop  =$("#shop_id").val();
                fetch_record_by_onchange(option, year, month,today,shop);
            }
        },
        error: function (xhr, ajaxOptions, throwError) {
            console.log("error:" + xhr.status);
        },
        complete: function () {
            $(".checkin_loading").addClass("hide");
        }
        });
    });
	
    //Cancel Reservation
    $("body").on("click", ".btn_cancel_checkin", function () {
        var id = $(".customer_id").val();
        $.ajax({
        url: "<?php echo Router::url(array('controller' => 'records', 'action' => 'index')); ?>",
        type: "get",
        data: "action=cancel&id=" + id +'&shop_id='+$("#shop_id").val(),
        dataType: "json",
        beforeSend: function () {
            $(".checkin_loading").removeClass("hide");
        },
        success: function (respond) {
            if (respond.result === "success") {
            $("#modalCheckin").modal("hide");
                var option =$("#select_option").val();
                var year = $("#select_year").val();
                var month = $("#select_month").val();
                var today = curDate.getDate();
                var shop  =$("#shop_id").val();
                fetch_record_by_onchange(option, year, month,today,shop);
            }
        },
        error: function (xhr, ajaxOptions, throwError) {
            console.log("error:" + xhr.status);
        },
        complete: function () {
            $(".checkin_loading").addClass("hide");
        }
        });
    });
	
    $("body").on("click", ".get_reservation_id", function () {
        var id = $(this).attr('id');
        $(".reservation_id").val(id);
        var target_date = $(this).attr('targat-date');
        var tr = $(this).parent().parent();
        var username = tr.find('td:eq(1)').attr('title');
        //$("#label-delete-confirm").html(target_date + " を削除します本当によろしいですか？");
        $("#label-delete-confirm").html(username + ' 様の ' + target_date + ' の予約を削除します本当によろしいですか？');
    });

    $('#del1').change(function () {//do something when the user clicks the box
        var val = "";
        if (this.checked) {
            $("#del1").val(1);
        } else {
            $("#del1").val(val);
        }
    });

    $("form#delete_reservation").submit(function (e) {
        e.preventDefault();
        var $is_del = $("#del1").val();
        var data = $(this).serialize();
        $.ajax({
        url: "<?php echo Router::url(array('controller' => 'records', 'action' => 'index')); ?>",
        data: data + "&action=delete&del_physical=" + $is_del,
        dataType: "json",
        type: "get",
        beforeSend: function () {
        $(".delete_loading").removeClass("hide");
           $(".error-message, .success-message").html("");
        },
        success: function (respond) {
        if (respond.result === "success") {
        $("#ModalDeleteReservation").modal("hide");
            $("#" + $(".reservation_id").val()).remove();
            $('#del1').prop('checked', false);
        } else {
        $("#error-msg-delete").html(respond.msg);
        }
        },
        error: function (xhr, ajaxOptions, throwError) {
            console.log("Error: " + xhr.status);
        },
        complete: function () {
            $(".delete_loading").addClass("hide");
        }
        });
     });

    var chair = <?php echo empty($chair) ? '[]' : json_encode($chair); ?>;
    var resources = [];
    $.each(chair, function(index, value) {
        resources[index] = {
            id:value["Chair"]["id"],
            room: value["Chair"]["chair_name"],
            capacity: value["Chair"]["capacity"]
        };
    });

    function render_existing_event() {
        $('#calendar').fullCalendar( 'removeEvents');
        var reservation_date = $('#calendar').fullCalendar('getDate');
        $('.dateSelected').val(reservation_date.format('YYYY-MM-DD'));
        var data = {'date': reservation_date.format('YYYY-MM-DD')};
        if ($("#shop_id").length > 0) {
            data['shop_id'] = $("#shop_id").val();
        }
        $.ajax({
            type: 'get',
            data: data,
            url: '<?php echo Router::url("/", true); ?>reservations/schedule_list/',
            dataType: 'json',
            'Content-Type': 'application/json',
            success: function(data) {
                existing_events(JSON.parse(data.timeline));
            }
        });
    }

    var existing_event_id = [];
    $('#calendar').fullCalendar({
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            resourceAreaWidth: 200,
            editable: false,
            selectable: false,
            defaultView: 'timelineDay',
            slotLabelFormat: 'H:mm',
            minTime: '8:00',
            maxTime: '23:45',
            slotDuration: "00:15:00",
            slotEventOverlap: false,
            eventOverlap: false,
            unselectAuto: false,
            eventLimit: true,
            droppable: false,
            height: 'auto',
            lang: 'jp',
            eventColor: 'green',
            titleFormat: 'YYYY年 M月 D日',
            businessHours: {
                dow: [1, 2, 3, 4, 5, 6, 7],
                start: '08:00',
                end: '23:45'
            },
            header: {
                left: 'prev, title, next',
                center: '',
                right: ''
            },
            resourceLabelText: '',
            resourceColumns: [
                {labelText: '診察室', field: 'room'},
                {labelText: '定員', field: 'capacity'}
            ],
            resources: resources,
            eventClick: function(calEvent, jsEvent, view) {
                $('#event-reservation table').html(
                    '<tr><td class = "text-right">姓　　名：</td><td class = "text-left">' +calEvent.customerName+'</td></tr>'+
                    '<tr><td class = "text-right">予約人数：</td><td class = "text-left">' +calEvent.number_of_reservation+'</td></tr>'+
                    '<tr><td class = "text-right">予約タグ：</td><td class = "text-left">' +calEvent.tagName+'</td></tr>'+
                    '<tr><td class = "text-right">スタッフ：</td><td class = "text-left">' +calEvent.staffName+'</td></tr>'
                );
                $('#event-reservation').modal('show');
            }
        });

        function existing_events(timeLine) {
            if ($(timeLine).length > 0) {
                $.each(timeLine, function(index, value) {
                    var start = new Date(value['start_date']);
                    var end = new Date(value['end_date']);
                    var existing_event = {
                            id: value['id'],
                            resourceId: value['seat_id'],
                            title: start.format('HH:MM') + ' - ' + end.format('HH:MM'),
                            start: start,
                            end: end,
                            color: 'green',
                            customerName: value['user_name'],
                            staffName: value['staff_name'],
                            tagName: value['tag_name'],
                            number_of_reservation: value['number_of_reservation']
                    };

                    $('#calendar').fullCalendar('renderEvent', 
                        existing_event,
                        true
                    );
                });
            }
        }

        $('.fc-prev-button').click(function(){
            render_existing_event();
        });

        $('.fc-next-button').click(function(){
           render_existing_event();
        });
        $('#calendar').hide();
    });

</script>