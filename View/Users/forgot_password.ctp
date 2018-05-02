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
    <body>
        <div id="wrapper">
            <div class="title">
                <p>Dental Clinic ASP </p>
                <?php echo $this->Html->image('header-right.png', array('class' => 'img_header')); ?>
            </div>
            <?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
            <div class="login">
                <div class="header_login">
                    <label><?php echo FORGOT_PASSWORD; ?></label>
                </div>
                <div class="form_login">
                    <?php echo $this->Session->flash() . '<br/>'; ?>
                    <div class="form-group">
                        <?php
                        echo $this->Form->input('email', array(
                            'type' => 'email',
                            'required' => 'required',
                            'class' => 'form-control',
                            'label' => false,
                            'placeholder' => 'Email'));
                        ?>
                    </div>
                    <?php echo $this->Form->submit(SUBMIT_EMAIL, array('class' => 'btn btn-warning color')); ?>
                    <div class="forget_pass">
                        <?php
                        echo $this->Html->link(GOTO_LOGIN, array(
                            'controller' => 'users',
                            'action' => 'login'));
                        ?>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </body>
    <html
