
<?php
if ($this->request->action === 'create') {
    $url = $this->Html->url('/reservations/create/'.$user_id);
} else {
    $url = $this->Html->url('/reservations/edit/'.$reservation_id);
}
?>

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header text-primary"><?php echo RESERVATION_MANAGEMENT; ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id="calendar"></div>
    </div>
</div>
<?php
echo $this->Form->create('Reservation', array('class' => 'form-horizontal'));
echo $this->Form->input('Reservation.start', array('class' => 'startDate', 'type' => 'hidden'));
echo $this->Form->input('Reservation.end', array('class' => 'endDate', 'type' => 'hidden'));
echo $this->Form->input('Reservation.chair_id', array('class' => 'chairId', 'type' => 'hidden'));
echo $this->Form->input('is_create', array('class' => 'is_create', 'type' => 'hidden', 'value' => $is_created));
echo $this->Form->input('date', array('class'=> 'dateSelected', 'value' => str_replace(',', '-', $date), 'type' => 'hidden'));
$cls = '';
$cmp = '';
if ($this->request->action == 'edit') {
    $cls = ' common-dl-space';
    $cmp = ' drop-space';
}
?>
<div class="row<?php echo $cls; ?>">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <button type="button" class="btn margin-top-12 btn-block btn-back btn_color"><?php echo BACK; ?></button>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6<?php echo $cmp; ?>">
        <?php
            echo $this->Form->submit(NEXT, array('class' => 'btn margin-top-12 btn-block btn_color'));
        ?>
    </div>    
</div>
<?php echo $this->Form->end(); ?>
<script>
    $(function() {
        <?php if ($this->request->action === 'create') { ?>
            setTimeout(function() {
                if ($.cookie('appointment_is_refresh') == 1) {
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

        $('form').submit(function (e) {
            if ($('.chairId').val() === '0' || $('.chairId').val() === '') {
                alert('タイムラインを選択してください。');
                e.preventDefault();
                return false;
            } else {
                $(window).off('beforeunload');
                $(window).off('unload');
            }
        });

        var unsaved = false;
        if ($('.chairId').val() === '0' || $('.chairId').val() === '') {
            unsaved = false;
        } else {
            unsaved = true;
        }

//        if (window.history && window.history.pushState) {
//            window.history.pushState('back', null, null);
//            $(window).on('popstate', function() {
//                setTimeout(call_back(), 200);
//            });
//        }

        call_back();
        
        $('.btn-back').on('click', function() {
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

        $(window).on('beforeunload', function(e) {
            var confirmationMessage = '別ページへ遷移すると入力した予約内容が破棄されます。';
            e.returnValue = confirmationMessage;
            $.cookie('appointment_is_refresh', 1, { path: '/' });
            $.cookie('reservation_uncomplete', 1, { path: '/' });
            return confirmationMessage;
        });

        function clearReservation(id){
            $.ajax({
                url: URL + 'reservations/deleteReservation/?id=' + id,
                type: 'post',
                dataType: 'json',
                success: function (data) {
                   if (data) {
                        $.cookie('appointment_is_refresh', 0, { path: '/' });
                        $.cookie('reservation_uncomplete', 0, { path: '/' });
                        $(window).off('beforeunload');
                        window.location.href = '<?php echo Router::url("/", true); ?>';
                   }
                }
            });
        }

        var timeLine = <?php echo $timeline; ?>;

        var chair;
        <?php if (empty($chair)):?>
        chair = '';
        <?php else: ?>
        chair = <?= json_encode($chair) ?>;
        <?php endif; ?>
        var resources = [];
        $.each(chair, function(index, value) {
            resources[index] = {
                id: value['Chair']['id'],
                room: value['Chair']['chair_name'],
                capacity: value['Chair']['capacity']
            };
        });

        var just_drop = false;
        var existing_event_id = [];
        $('#calendar').fullCalendar({
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            resourceAreaWidth: 200,
            editable: true,
            aspectRatio: 1.5,
            selectable: true,
            defaultView: 'timelineDay',
            slotLabelFormat: 'H:mm',
            minTime: '8:00',
            maxTime: '23:45',
            slotDuration: '00:15:00',
            slotEventOverlap: false,
            eventOverlap: false,
            unselectAuto: true,
            eventLimit: true,
            droppable: true,
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
                {labelText: 'テーブル', field: 'room'},
                {labelText: '定員', field: 'capacity'}
            ],
            resources: resources,
            select: function (start, end, jsEvent, view, resources) {
                console.log(start);
                if (!just_drop) {
                    var all_events = $('#calendar').fullCalendar('clientEvents');
                    var event_is_exist = false;
                    $.each(all_events, function(index, event) {
                        if (event.resourceId === resources.id) {
                            if (new Date(start) < new Date(event.start) && new Date(event.start) < new Date(end) ||
                            new Date(start) < new Date(event.end) && new Date(event.end) < new Date(end)) {
                                event_is_exist = true;
                            }
                        }
                        if (jQuery.inArray(event.id, existing_event_id) < 0) {
                            event_is_exist = true;
                        }
                    });
                    
                    if (!event_is_exist) {
                        var new_event = {
                            resourceId: resources.id,
                            title: start.format('H:mm') + ' - ' + end.format('H:mm'),
                            start: start.format('H:mm'),
                            end: end.format('H:mm'),
                            color : 'green'
                        };
                        $('#calendar').fullCalendar('renderEvent',
                            new_event,
                            true
                        );

                        InitText(start, end, resources.id);
                    }
                    $('#calendar').fullCalendar('unselect');
                }
                just_drop = false;
                $('#calendar').fullCalendar('unselect');
            },
            eventDragStart: function(event, jsEvent, ui, view) {
                $('#calendar').fullCalendar('unselect');
            },
            eventDragStop: function(event, jsEvent, ui, view) {
                $('#calendar').fullCalendar('unselect');
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    just_drop = true;
                }
            },
            eventDrop: function (event, delta, revertFunc) {
                updateEvent(event);
            },
            eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
                updateEvent(event);
            }
        });
        //go to selected date from previouse page(create page)
        $('#calendar').fullCalendar( 'gotoDate', '<?php echo str_replace(',', '-', $date); ?>');

        existing_events(timeLine);

        function existing_events(timeLine) {
            if ($(timeLine).length > 0) {                
                $.each(timeLine, function(index, value) {
                    var start = new Date(moment(value.start_date, moment.ISO_8601));
                    var end = new Date(moment(value.end_date, moment.ISO_8601));
                    start = start.format('H:MM');
                    end = end.format('H:MM');
                    var existing_event = {
                            id: value.id,
                            resourceId: value.seat_id,
                            title: start + ' - ' + end,
                            start: start,
                            end: end,
                            color: 'green'
                    };

                    if (value.text !== 'Edit') {
                        existing_event_id[index] = value.id;
                        $.extend(existing_event,{
                            editable: false,
                            color: '#a9a9a9'
                        });
                        if (value.text !== '') {
                            $.extend(existing_event,{
                                title: value.text
                            });
                        }
                    }
                    $('#calendar').fullCalendar('renderEvent',
                        existing_event,
                        true
                    );
                });
            }
        }

        $('.fc-prev-button').on('click', function(){
            render_existing_event();
        });

        $('.fc-next-button').on('click', function(){
           render_existing_event();
        });
        
        function updateEvent(event) {
            event.title = event.start.format('H:mm') + ' - ' + event.end.format('H:mm');
            $('#calendar').fullCalendar('updateEvent', event);
            InitText(event.start, event.end, event.resourceId);
        }

        function render_existing_event() {
            $('#calendar').fullCalendar( 'removeEvents');
            var reservation_date = $('#calendar').fullCalendar('getDate');
            $('.dateSelected').val(reservation_date.format('YYYY-MM-DD'));
            $.ajax({
                type: 'get',
                data: {'date': reservation_date.format('YYYY-MM-DD')},
                url: '<?php echo Router::url('/', true); ?>reservations/schedule_list/',
                dataType: 'json',
                'Content-Type': 'application/json',
                success: function(data) {
                    existing_events(JSON.parse(data.timeline));
                }
            });
        }

        function InitText(startDate, endDate, chairId) {
            var start = startDate.format('YYYY-MM-DD H:mm:s');
            var end = endDate.format('YYYY-MM-DD H:mm:s');
            $('.startDate').val(start);
            $('.endDate').val(end);
            $('.chairId').val(chairId);
        }
    });
</script>
