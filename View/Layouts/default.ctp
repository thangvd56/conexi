<!DOCTYPE html>
<html lang="en">
    <head>
         <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/x-icon" href="<?php echo $this->Html->url('/'); ?>img/favicon.ico">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->Html->url('/'); ?>img/favicon.ico">
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <?php
        echo $this->Html->css(array(
            'fullcalendar.min',
            'scheduler.min',
            '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.min.css',
            'bootstrap.min',
            'font-awesome.min',
            'bootstrap-toggle.min',
            'dental',
            'responsive'
        ));
        echo $this->Html->script(array(
            '//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js',
            'bootstrap.min',
            'bootstrap-toggle.min',
            'jquery-ui',           
            'jquery-sort.js',
            'jquery.form.min',
            'jquery.cookie',
            'fm.scrollator.jquery',
            'moment.min',
            'fullcalendar.min',
            'scheduler.min',
            'lang-all',
            'date_formart',
            'Chart.min',
            'datepicker-ja',
            'custom'
        ));
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    
    <script>
        var URL = '<?php echo $this->Html->url('/'); ?>';
    </script>
    
<body>
        <div id="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="title">
                    <?php echo $this->Html->image('logo.png', [
                        'alt' => 'Logo',
                        'class' => "img-responsive img-logo"
                        ]); ?>
                </div>
                <div class="navbar-header pull-right">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
                <?php echo $this->element('Frontend/menu'); ?>
                <!-- /.navbar-collapse -->
            </nav>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    $(function () {
        jQuery('#date-picker').datepicker({dateFormat: 'M. dd, yy'});
        
        $('#btn_upgrad').click(function () {
            window.location.replace('http://www.conexi.jp/');
        });

        //common jquery ui datepicker apply to all single date display with minimun today selectable.
        $('.common-single-datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate : new Date(),
            beforeShow: function(textbox, instance) {
                $('#ui-datepicker-div').css({'padding': '20px', 'width': '30em'}).hide();
            }
        }, $.datepicker.regional['ja']).datepicker({
            'setDate': new Date()
        });
    });
</script>