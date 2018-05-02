
        <?php
            $controller = $this->request->controller;
            $action = $this->request->action;
            $active_class = ''; 
            $collapse_class = '';
            $controllers = array(
                'news',
                'groups',
                'menuCopies',
                'coupons',
                'stamp_settings',
                'Users',
            );
            $actions = array(
                'index',
                'copy',
                'create_notification',
                'confirm',
                'update_all_shop',
                'addShops'
            );

            if (in_array($controller, $controllers) && in_array($action, $actions)) {
                $active_class = 'active';
                $collapse_class = 'in';
            }
            if ($controller == 'coupons' && $action == 'index') {
                $collapse_class = '';
                $active_class = '';
            }
        ?>
<!--        <li> this menu stopped using for temporary
            <?php 
//                echo $this->Html->link('店舗選択', array(
//                        'controller' => 'Shops',
//                        'action' => 'shopList'
//                    ), array(
//                        'class' => $controller == 'Shops' && $action == 'shopList' ? 'active': ''
//                    )
//                );
            ?>
        </li>-->
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#hq-collape" class="<?php echo $active_class; ?>"> 店舗管理 <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="hq-collape" class="collapse <?php echo $collapse_class; ?>">
                <li>
                     <?php
                        echo $this->Html->link('グループ管理', array(
                                'controller' => 'groups',
                                'action' => 'index',
                            ), array(
                                'class' => $controller == 'groups' && ($action == 'index' || $action == 'addShops') ? $active_class: ''
                            )
                        );
                    ?>
                </li>
                <li>
                     <?php
                        echo $this->Html->link('メニューコピー', array(
                                'controller' => 'menuCopies',
                                'action' => 'index'
                            ), array(
                                'class' => $controller == 'menuCopies' && $action == 'index' ? $active_class: ''
                            )
                        );
                    ?>
                </li>
                <li>
                     <?php
                        echo $this->Html->link('クーポンコピー', array(
                                'controller' => 'coupons',
                                'action' => 'copy'
                            ), array(
                                'class' => $controller == 'coupons' && $action == 'copy' ? $active_class: ''
                            )
                        );
                    ?>
                </li>
                <li>
                     <?php
                        echo $this->Html->link('スタンプ一括管理', array(
                                'controller' => 'stamp_settings',
                                'action' => 'index'
                            ), array(
                                'class' => $controller == 'stamp_settings' && ($action == 'index' || $action == 'update_all_shop') ? $active_class: ''
                            )
                        );
                    ?>
                </li>
                <li>
                     <?php
                        echo $this->Html->link('通知一括送信', array(
                                'controller' => 'news',
                                'action' => 'create_notification',
                            ), array(
                                'class' => $controller == 'news' && $action == 'create_notification' ? $active_class: ''
                            )
                        );
                    ?>
                </li>
                <li>
                     <?php
                        echo $this->Html->link('店舗ID管理', array(
                                'controller' => 'Users',
                                'action' => 'confirm',
                            ), array(
                                'class' => $controller == 'Users' && $action == 'confirm' ? $active_class: ''
                            )
                        );
                    ?>
                </li>
            </ul>
        </li>