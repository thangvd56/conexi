<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title></title>
        <?php
        echo $this->Html->css(array(
            'bootstrap.min',
            'dental.css',
            'logout.css',
        ));
        ?>
        <script type="text/javascript">
            function goBack() {
                window.location.replace(document.referrer);
            }
            function logout() {
                window.location.replace("<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>");
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div class="title">
                <p>Dental Clinic ASP </p>
                <?php echo $this->Html->image('header-right.png', array('class' => 'img_header')); ?>
            </div>
            <div class="logout formCenter">
                <div class="">
                    <label>ログアウト</label>
<!--                    <p>ログアウトすると、次回ログイン時にIDとパスワードが必要になります。
                        IDとパスワードを控えた上で、ログアウトしてください。</p>-->
                </div>
                <div class="form_logout">
                    <div class="form-group confirmation_info">
                        <p>ログアウトします。 <br/> 本当に宜しいですか？</p>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                                        <button class="btn btn-warning set_width setWidthButton" onclick="goBack()">いいえ</button>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                                        <button class="btn btn-warning set_width setWidthButton" onclick="logout()">はい</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>