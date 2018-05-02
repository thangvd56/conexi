<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <?php echo $this->Html->charset(); ?>
        <title>
            ログイン
        </title>
        <?php
        echo $this->Html->css(array(
            'bootstrap.min',
            'dental',
            'login'
        ));
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
<!--    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid text-center">
                <div class="navbar-header" style="width: 100%;">
                    <a href="javascript:void(0);" class="navbar-brand" style="width: 100%;"><?php echo DENTAL_CLINIC_ASP ?></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                </div>/.navbar-collapse 
            </div>
        </nav>
        <div class="container">
            <div class="col-md-4"></div>
            <div class="col-lg-4" style="margin-top:70px;">
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
                        <?php echo $this->Session->flash() . '<br/>'; ?>
                        <?php echo $this->Form->input('username', array('class' => 'form-control user-signin', 'label' => false, 'id' => 'username', 'placeholder' => 'Username')); ?>
                        <?php echo $this->Form->input('password', array('class' => 'form-control password-signin', 'label' => false, 'id' => 'password', 'placeholder' => 'Password')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <?php echo $this->Form->submit('Login', array('class' => 'btn btn-primary btn-login width100percent')); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="col-md-4"></div>
        </div>
    </body>-->
    <body>
        <div id="wrapper">
            <div class="title">
                <p>Dental Clinic ASP </p>
                <?php echo $this->Html->image('header-right.png', array('class' => 'img_header')); ?>
            </div>
            <?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
            <div class="login">
                <div class="header_login">
                    <label>ログインしてください</label>
                </div>
                <?php echo $this->Session->flash() . '<br/>'; ?>
                <div class="form_login">
                    <div class="form-group">
                        <?php echo $this->Form->input('username', array(
                            'class' => 'form-control', 
                            'label' => false, 
                            'id' => 'username', 
                            'placeholder' => 'ID')); ?>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="PASSWORD">
                        <?php echo $this->Form->input('password', array(
                            'class' => 'form-control', 
                            'label' => false, 
                            'id' => 'password', 
                            'placeholder' => 'PASSWORD')); ?>
                    </div>
                    <?php echo $this->Form->submit('ログイン', array('class' => 'btn btn-warning color')); ?>
                    <div class="forget_pass"> 
                        <p>パスワードを忘れた方はこちら</p>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </body>
    <html
