<ul class="nav nav-pills nav-stacked nav-sidebar">

    <li class="shop <?php echo $this->request->controller == 'Users' && $this->request->action == 'admin_index' ? 'active' : ''; ?>">
        <?php echo $this->Html->link('ユーザー設定', '/admin/users?role=shop', array('escape' => false,)); ?>
    </li>
    <li class="support <?php echo $this->request->controller == 'supports' ? 'active' : ''; ?>">
        <?php echo $this->Html->link('サポート、Q＆A', array('controller'=>'supports','action'=>'admin_index'), array('escape' => false,)); ?>
    </li>
    <li class="headquarter <?php echo $this->request->controller == 'Users' && $this->request->action == 'admin_headquarter' ? 'active' : ''; ?>">
        <?php echo $this->Html->link('本社管理ID設定', array('controller'=>'Users','action'=>'admin_headquarter'), array('escape' => false,)); ?>
    </li>
    <li class="logout">
     <?php echo $this->Html->link('<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> ログアウト', '/users/logout', array('escape' => false)); ?>
    </li>
</ul>