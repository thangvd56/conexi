<?php

echo $this->Paginator->counter('<label>全 <span class="badge">{:count}</span> 件 ページ {:page} of {:pages}</label><br/>');
echo '<div class="paging">';
echo $this->Paginator->prev('< ' . __(PREVIOUS), array(), null, array('class' => 'prev disabled'));
echo $this->Paginator->numbers(array('separator' => '', 'modulus' => 4));
echo $this->Paginator->next(__(NEXT) . ' >', array(), null, array('class' => 'next disabled',));
echo '<br/><br/>';
echo '</div>';
