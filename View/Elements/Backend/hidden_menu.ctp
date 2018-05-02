<ul class="nav navbar-nav hidden-md hidden-lg">
<!--    <li  class="agent">
        <?php //echo$this->Html->link(AGENT_MANAGEMENT, '/admin/users?role=agent', array('escape' => false)); ?>
    </li>
    <li class="shop">
        <?php //echo$this->Html->link(SHOP_OWNER_MANAGEMENT, '/admin/users?role=shop', array('escape' => false)); ?>
    </li>
    <li class="user">
        <?php //echo$this->Html->link(USER_MANAGEMENT, '/admin/users', array('escape' => false)); ?>
    </li>
    <li class="news">
        <?php //echo$this->Html->link(NEWS_MANAGEMENT, '/admin/news', array('escape' => false)); ?>
    </li>
    <li class="ip">
        <?php //echo$this->Html->link(IP_ADDRESS_MENAGEMENT, '/admin/ips', array('escape' => false)); ?>
    </li>
    <li class="genre">
        <?php //echo$this->Html->link(GENRE_MANAGEMENT, array('controller' => 'genres', 'action' => 'admin_index'), array('escape' => false)); ?>
    </li>
    <li class="plan">
        <?php //echo$this->Html->link(PLAN_MANAGEMENT, array('controller' => 'plans', 'action' => 'admin_index'), array('escape' => false)); ?>
    </li>-->

    <li class="shop">
        <?php echo $this->Html->link('ユーザー設定', '/admin/users?role=shop', array('escape' => false,)); ?>
    </li>
    <li class="support">
        <?php echo $this->Html->link('サポート、Q＆A', array('controller'=>'supports','action'=>'admin_index'), array('escape' => false,)); ?>
    </li>
    <li class="genre">
        <?php echo $this->Html->link('予約・顧客識別タグ作成', array('controller' => 'tags', 'action' => 'index'), array('escape' => false)); ?>
    </li>
    <li class="logout">
     <?php echo $this->Html->link('<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> ログアウト', '/users/logout', array('escape' => false)); ?>
    </li>

</ul>