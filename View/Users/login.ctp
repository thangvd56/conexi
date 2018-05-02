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
            <?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
            <div class="login">
                <div class="header_login">
                    <label>ログインしてください</label>
                </div>
                <?php echo $this->Session->flash() . '<br/>'; ?>
                <div class="form_login">
                    <?php
                        echo $this->Form->input('username', array(
                            'class' => 'form-control',
                            'label' => false,
                            'id' => 'username',
                            'placeholder' => 'ID'));
                    ?>
                    <?php
                        echo $this->Form->input('password', array(
                            'class' => 'form-control',
                            'label' => false,
                            'id' => 'password',
                            'placeholder' => 'PASSWORD'));
                        ?>
                    <?php echo $this->Form->checkbox('remember_me'); ?> ログイン情報を保存する
                    <?php echo $this->Form->submit('ログイン', array('class' => 'btn btn-warning color')); ?>
                    <div class="forget_pass">
                        <?php
                        echo $this->Html->link('パスワードを忘れた方はこちら', array(
                            'controller' => 'users',
                            'action' => 'forgot_password'
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </body>
    <html
