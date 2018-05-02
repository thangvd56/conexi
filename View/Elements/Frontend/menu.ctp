<style type="text/css">
    #talkbubble {
        width: 35px;
        height: 35px;
        top: 10px;
        margin-left: 88px;
        background: rgb(255, 129, 0);
        position: absolute;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        border-radius: 18px;
    }
    .count{
        font-size: 12pt;
        position: absolute;
        top: 9px;
        margin-left: 8px;
        font-family: arial;
    }
</style>
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <?php 
        $controller = $this->request->controller;
        $action = $this->request->action;
    ?>
    <ul class="nav navbar-nav side-nav">
        <?php
            if ($this->Session->read('Auth.User.role') === ROLE_HEADQUARTER) {
                echo $this->element('Frontend/menu_items');
            }
        ?>
        <li>
            <?php echo $this->Html->link('来店履歴', array(
                'controller' => 'records',
                'action' => 'index'
                ), array(
                'class' => (
                    $controller == 'records' ||
                    $controller == 'reservations' ? 'active' : '')
                )); ?>
        </li>
         <li>
            <?php echo $this->Html->link('顧客登録', array(
                    'controller' => 'customers',
                    'action' => 'register'), array(
                    'class' => (
                        $controller == 'customers' &&
                        $action == 'register' ? 'active' : '')
                )); ?>
        </li>
        <li>
            <?php echo $this->Html->link('顧客台帳', array(
                    'controller' => 'customers',
                    'action' => 'index'
                ), array(
                    'class' => (
                        $controller == 'customers' &&
                        $action == 'index' ? 'active' : '')
                )); ?>
        </li>
        <li>
            <?php
                $action1 = array('index', 'advance_notification', 'visitNotification', 'birthdayNotification', 'create');
            ?>
            <a href="javascript:;" data-toggle="collapse" data-target="#demo1" <?php echo ($controller == 'notices' || $controller == 'news') && in_array($action, $action1) ? 'class="active"' : ''; ?>> 通知設定 <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="demo1" class="collapse <?php echo ($controller == 'notices' || $controller == 'news') && in_array($action, $action1) ? 'in' : ''; ?>">
                <li>
                     <?php
                    echo $this->Html->link('お知らせ通知', array(
                        'controller' => 'notices',
                        'action' => 'index'
                    ), array(
                        'class' => (
                            $controller == 'notices' ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('予約事前通知', array(
                        'controller' => 'news',
                        'action' => 'advance_notification'
                        ), array(
                        'class' => (
                            $controller == 'news' && ($action == 'advance_notification' || $action == 'create') ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('最終来店通知', array(
                        'controller' => 'news',
                        'action' => 'visitNotification'
                        ), array(
                        'class' => (
                            $controller == 'news' && $action == 'visitNotification' ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('誕生月通知', array(
                        'controller' => 'news',
                        'action' => 'birthdayNotification'
                        ), array(
                        'class' => (
                            $controller == 'news' && $action == 'birthdayNotification' ? 'active' : '')
                    ));
                    ?>
                </li>
            </ul>
        </li>
        <li>
            <?php 
                $class = ''; 
                $collapse = '';
                $controllers = array(
                    'app_informations',
                    'menu_categories',
                    'stamp_settings',
                    'application_menu_lists',
                    'staffs',
                    'chairs',
                    'coupons',
                    'settings',
                    'shops',
                    'tags',
                    'actions',
                );
                $actions = array(
                    'index',
                    'create',
                    'web_reservation',
                    'user_setting',
                    'app-menu-list',
                    'edit'
                );
                if (in_array ($controller, $controllers) && in_array ($action, $actions)){
                    $class = 'active';
                    $collapse = 'in';
                }
                if ($controller == 'stamp_settings' && $action == 'index') {
                    $class = '';
                    $collapse = '';
                }
            ?>
            <a href="javascript:;" data-toggle="collapse" data-target="#demo" class="<?php echo $class; ?>"> アプリ作成 <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="demo" class="collapse <?php echo $collapse; ?>">
                <li>
                    <?php
                    echo $this->Html->link('基本情報', array(
                        'controller' => 'app_informations',
                        'action' => 'index'
                        ), array(
                            'class' => (
                                $controller == 'app_informations' && $action == 'index' ? 'active' : '')
                    ));
                    ?>
                </li>
        <!--class="active"-->
                <li >
                    <?php
                    echo $this->Html->link('メニュー', array(
                        'controller' => 'menu_categories',
                        'action' => 'index'
                        ), array(
                            'class' => (
                                ($controller == 'menu_categories' || $controller == 'application_menu_lists') && ($action == 'index') ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('スタンプ', array(
                        'controller' => 'stamp_settings',
                        'action' => 'create'
                        ), array(
                            'class' => (
                                $controller == 'stamp_settings' && $action == 'create' ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                     <?php
                    echo $this->Html->link('スタッフ紹介', array(
                        'controller' => 'staffs',
                        'action' => 'index'
                        ), array(
                            'class' => (
                                $controller == 'staffs' && ($action == 'index' || $action == 'edit') ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                      echo $this->Html->link('クーポン', array(
                        'controller' => 'coupons',
                        'action' => 'index'
                          ), array(
                            'class' => (
                                $controller == 'coupons' && ($action == 'index' || $action == 'create') ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                      echo $this->Html->link('予約テーブル作成', array(
                        'controller' => 'chairs',
                        'action' => 'index'
                          ), array(
                            'class' => (
                                $controller == 'chairs' && $action == 'index' ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                     <?php
                      echo $this->Html->link('機能設定', array(
                        'controller' => 'settings',
                        'action' => 'index'
                          ), array(
                            'class' => (
                                $controller == 'settings' && $action == 'index' ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('ウェブ予約', array(
                        'controller' => 'shops',
                        'action' => 'web_reservation'
                        ), array(
                            'class' => (
                                $controller == 'shops' && $action == 'web_reservation' ? 'active' : '')
                    ));
                    ?>
                </li>
                <li>
                    <?php
                        echo$this->Html->link('タグ生成', array(
                        'controller' => 'tags',
                        'action' => 'index'
                        ), array(
                            'class' => (
                                $controller == 'tags' && $action == 'index' ? 'active' : '')
                        ));
                    ?>
                </li>
                <li>
                    <?php
                        echo$this->Html->link('解析', array(
                        'controller' => 'actions',
                        'action' => 'index'
                        ), array(
                            'class' => (
                                $controller == 'actions' && $action == 'index' ? 'active' : '')
                        ));
                    ?>
                </li>
            </ul>
        </li>
        <li>
            <?php
                echo$this->Html->link('ユーザー設定', array(
                'controller' => 'users',
                'action' => 'user_setting'
                ), array(
                    'class' => (
                        $controller == 'users' && $action == 'user_setting' ? 'active' : '')
                ));
            ?>
        </li>
        <li>
            <?php
            echo$this->Html->link('サポート、Q＆A', array(
                'controller' => 'supports',
                'action' => 'index'
                ), array(
                    'class' => (
                        $controller == 'supports' && $action == 'index' ? 'active' : '')
                ));
            ?>
        </li>
        <li>
            <a id="btn_logout" href="javascript:void(0);">ログアウト</a>
        </li>

    </ul>
</div>

<script type="text/javascript">

    $(function () {
        $("#btn_logout").click(function () {
            window.location.replace("<?php echo $this->Html->url(array('controller' => 'logoutconfirms', 'action' => 'index')); ?>");
        });
    });

</script>