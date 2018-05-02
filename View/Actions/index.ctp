
<style>
    .canvasjs-chart-credit {
        display: none;
    }
    .count_graph {
        margin-top: 8px;
    }
    #datepicker {
        margin-bottom: 10px;
    }
    .row-common-space {
        margin-top: 15px;
        margin-bottom: 15px;
    }
</style>

<div class="col-xs-12 col-md-12"><h1 class="page-header">アプリ作成 ＞ 解析</h1></div>
<?php
    echo $this->Form->create('actions', array(
        'role' => 'form',
        'name' => 'form_calendar',
        'type' => 'get',
    ));

    $year_select = array();
    for ($i = 2016; $i <= date('Y'); $i++) {
        $year_select[$i] = $i . ' 年';
    }
    $end_year = end($year_select);
    $end_year = str_replace(' 年', '', $end_year);
    $d = new DateTime($end_year);
    $d->modify('+1 year');
    $year_select[$d->format('Y')] = $d->format('Y 年');
    arsort($year_select);

if ($this->Session->read('Auth.User.role') === ROLE_HEADQUARTER) { ?>
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-2">
            <div class="form-group">
                <?php
                    echo $this->Form->input('select_shop', array(
                        'type' => 'select',
                        'class' => 'form-control',
                        'label' => false,
                        'options' => $shops,
                        'templates' => array(
                            'inputContainer' => '{{content}}',
                        ),
                        'value' => $this->request->query('shop_id') ? $this->request->query('shop_id') : '',
                    ));
                ?>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 pull-right">
            <?php
                echo $this->Form->input('datePicker', array(
                    'label' => false,
                    'class' => 'form-control',
                    'id' => 'datepicker',
                    'type' => 'select',
                    'options' => $year_select, //$year_list,
                    'value' => $this->request->query('year') ? $this->request->query('year') : date('Y')
                ));
            ?>

        </div>
    </div>
<?php
} else if ($this->Session->read('Auth.User.role') === ROLE_SHOP) { ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-2 pull-right">
            <?php
                echo $this->Form->input('datePicker', array(
                    'label' => false,
                    'class' => 'form-control',
                    'id' => 'datepicker',
                    'type' => 'select',
                    'options' => $year_select, //$year_list,
                    'value' => $this->request->query('year') ? $this->request->query('year') : date('Y')
                ));
            ?>
        </div>
    </div>
<?php
}
echo $this->Form->end(); ?>
<div class="row row-common-space">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <h4>ウェブ予約</h4>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
        <p id="web_total" class="count_graph">年計 <span></span></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">        
        <canvas id="web_reservation" style="height: 300px; width: 100%;"></canvas>
    </div>
</div>
<div class="row row-common-space">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <h4>電話発信数</h4>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
        <p id="phone_total" class="count_graph">年計 <span></span></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">        
        <canvas id="phone_number" style="height: 300px; width: 100%;"></canvas>
    </div>
</div>
<div class="row row-common-space">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <h4>インストール数</h4>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 text-right">
        <p id="intall_total" class="count_graph">年計 <span></span></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12">        
        <canvas id="user_installation" style="height: 300px; width: 100%;"></canvas>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        var reservationData = [0, 0, 0, 0, 0, 0, 0, 0, 0 ,0 ,0 , 0];
        var phoneNumberData = [0, 0, 0, 0, 0, 0, 0, 0, 0 ,0 ,0 , 0];
        var userInstallationData = [0, 0, 0, 0, 0, 0, 0, 0, 0 ,0 ,0 , 0];

        var totalReservationData = 0;
        var totalPhoneNumberData = 0;
        var totalInstallationData = 0;

        $(window).load(function() {
            var objParam = {
                'year': $('#datepicker').val()
            };

            if ($('#actionsSelectShop').length) {
                objParam.shop_id = $('#actionsSelectShop').val();
            }

            $.get('<?php echo $this->Html->url('/actions/get_data_list'); ?>', objParam, function(response) {
                var chartLabel = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];
                var maxValueR = 0;
                var maxValueP = 0;
                var maxValueU = 0;
                if (response.data.Actions.web !== undefined) {
                    $.each(response.data.Actions.web, function (key, value) {
                        if (value.length > maxValueR) {
                            maxValueR = value.length;
                        }
                        totalReservationData += value.length;
                        reservationData[key - 1] = value.length;
                    });
                }
                $('#web_total span').append(totalReservationData + '件');
                webReservationGraph(reservationData, chartLabel, maxValueR);

                if (response.data.Actions.phone !== undefined) {
                    $.each(response.data.Actions.phone, function (key, value) {
                        if (value.length > maxValueP) {
                            maxValueP = value.length;
                        }
                        totalPhoneNumberData += value.length;
                        phoneNumberData[key - 1] = value.length;
                    });
                }
                $('#phone_total span').append(totalPhoneNumberData + '件');
                phoneNumberGraph(phoneNumberData, chartLabel, maxValueP);

                if (response.data.Users) {
                    $.each(response.data.Users, function (key, value) {
                        if (parseInt(value.count_install) > maxValueU) {
                            maxValueU = parseInt(value.count_install);
                        }
                        totalInstallationData += parseInt(value.count_install);
                        userInstallationData[value.month - 1] = parseInt(value.count_install);
                    });
                }
                $('#intall_total span').append(totalInstallationData + '件');
                userInstallationGraph(userInstallationData, chartLabel, maxValueU);
            }, 'json');
        });

        function maxValueYGraph(maxValue) {
            var obj = {};
            if (maxValue < 5) {

                return obj = {
                    ticks: {
                        max: 5,
                        min: 0,
                        stepSize: 1
                    }
                };
            } else {
                return obj =  {
                    ticks: {
                        max: maxValue + Math.ceil(((maxValue * 10) / 100))
                    }
                };
            }
        }

        //web reservation
        function webReservationGraph(reservationData, chartLabel, maxValue) {
            Chart.Bar('web_reservation', {
                type: 'bar',
                data: {
                    labels: chartLabel,
                    datasets: [{
                        data: reservationData,
                        backgroundColor: [
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes : [{
                            gridLines : {
                                display : false
                            }
                        }],
                        yAxes: [
                            maxValueYGraph(maxValue)
                        ]
                    }
//                    title: {
//                        display: true,
//                        text: 'ウェブ予約',
//                        position: 'top',
//                        fontSize: 16
//                    }
                }
            });
        }

        //phone number
        function phoneNumberGraph(phoneNumberData, chartLabel, maxValue) {
            Chart.Bar('phone_number', {
                type: 'bar',
                data: {
                    labels: chartLabel,
                    datasets: [{
                        data: phoneNumberData,
                        backgroundColor: [
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes : [{
                            gridLines : {
                                display : false
                            }
                        }],
                        yAxes: [
                            maxValueYGraph(maxValue)
                        ]
                    }
//                    title: {
//                        display: true,
//                        text: '電話発信数',
//                        position: 'top',
//                        fontSize: 16
//                    }
                }
            });
        }

        //user installation
        function userInstallationGraph(userInstallationData, chartLabel, maxValue) {
            Chart.Bar('user_installation', {
                type: 'bar',
                data: {
                    labels: chartLabel,
                    datasets: [{
                        data: userInstallationData,
                        backgroundColor: [
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(155, 187, 88, 1)',
                            'rgba(166, 146, 189, 1)',
                            'rgba(128, 197, 214, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes : [{
                            gridLines : {
                                display : false
                            }
                        }],
                        yAxes: [
                            maxValueYGraph(maxValue)
                        ]
                    }
//                    title: {
//                        display: true,
//                        text: 'インストール数',
//                        position: 'top',
//                        fontSize: 16
//                    }
                }
            });
        }

        $('#datepicker').change(function() {
            var yearParam = $(this).val();
            if ($('#actionsSelectShop').length) {
                yearParam += '&shop_id=' + $('#actionsSelectShop').val();
            }
            window.location.href = '<?php echo $this->Html->url('/actions?year='); ?>' + yearParam;
        });

        $('#actionsSelectShop').change(function() {
            window.location.href = '<?php echo $this->Html->url('/actions?year='); ?>' + $('#datepicker').val() + '&shop_id=' + $(this).val();
        });
    });
</script>