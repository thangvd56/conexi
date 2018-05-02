<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $this->fetch('title'); ?>
        </title>
        <?php
        echo $this->Html->css(array(
            'bootstrap.min',
            'jquery-ui.min.css',
            'common.css',
            'font-awesome.min',
            'custom',
            'bootstrap-timepicker.min'
        ));
        echo $this->Html->script(array(
            '//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js',
            'bootstrap.min',
            'bootstrap-timepicker.min'
        ));
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <?php echo $this->element('Backend/header') ?>
                <div id="navbar" class="navbar-collapse collapse">
                    <?php echo $this->element('Backend/hidden_menu') ?>
                    <p class="navbar-text navbar-right"><?php echo $this->Session->read('Auth.User.name'); ?></p>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 sidebar hidden-xs hidden-sm hidden-print" style="margin-top:-6px;">
                    <?php echo $this->element('Backend/menu') ?>
                </div>
                <div class="col-md-10 main">
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
        </div>
    </body>
</html>
<!--
Created 18/ November/2015
Channeth
-->
<script>
    $(document).ready(function () {
        var role = location.search.split('role=')[1];
        var path_name = "<?php echo $this->request->controller; ?>";
        if (role) {
            if (role == 'shop' || role.substring(0, 4) == 'shop') {
                $("li").removeClass("active");
                $('.shop').addClass('active');
            } else if (role == 'agent' || role.substring(0, 5) == 'agent') {
                $("li").removeClass("active");
                $('.agent').addClass('active');
            }
        } else {
            if (path_name == 'users') {
                $("li").removeClass("active");
                $('.user').addClass('active');
            } else if (path_name == 'news') {
                $("li").removeClass("active");
                $('.news').addClass('active');
            } else if (path_name == 'ips') {
                $("li").removeClass("active");
                $('.ip').addClass('active');
            } else if (path_name == 'genres' || path_name == 'genre_functions' || path_name == 'tags' ) {
                $("li").removeClass("active");
                $('.genre').addClass('active');
            } else if (path_name == 'plans' || path_name == 'plan_functions') {
                $("li").removeClass("active");
                $('.plan').addClass('active');
            }else if (path_name == 'supports') {
                $("li").removeClass("active");
                $('.support').addClass('active');
            }
        }
    });
</script>