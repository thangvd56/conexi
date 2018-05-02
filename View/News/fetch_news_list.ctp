<?php
echo $this->Html->css('custom');
?>

<?php $id = 1; $i =0; ?>
<table>
    <tbody>
        <?php if (isset($news_search)): ?>
            <?php foreach ($news_search as $key => $value): ?>
                <tr class="row news_list" id="<?php echo 'news_' . $id; ?>">
                    <td>
                        <?php
                        echo $this->Html->link(date('Y/m/d h:i', strtotime($value['NewsDelivery'][0]['delivered_date'])), 'detail/' . $value['News']['id'],array('style' => 'color:#555'));
                         ?>
                    </td>
                    <td>
                        <?php 
                         if($value['NewsDelivery'][0]['is_read'] == 0 || $value['NewsDelivery'][0]['is_read'] == ""):
                        ?>
                        <input type="hidden" class="is_read" id="<?php echo $value['NewsDelivery'][0]['id'];?>"/>
                        <a href="<?php echo $this->Html->url('/'); ?>news/detail/<?php echo $value['News']['id']; ?>">
                            <?php echo $this->Html->image('news.png', array('alt' => 'image new')); ?>
                        </a>
                             <?php endif;?>
                        <?php
                            echo $this->Html->link($value['News']['title'], 'detail/' . $value['News']['id'],array('style' => 'color:#555'));
                        ?>
                     </td>
                     <td>
                        <?php 
                            echo substr($value['News']['message'],0,20);
                        ?>
                     </td>
                     <td>
                        <?php 
                            echo $value['News']['admin_news_type'];
                        ?> 
                     </td>
                    <td>
                        <?php 
                         if($value['NewsDelivery'][0]['is_read'] == 0 || $value['NewsDelivery'][0]['is_read'] == ""):
                        ?>
                        
                       <button class="btn btn-success btn_back_color delete" id="<?php echo $value['News']['id'];?>">削除</button>
                        <?php endif;?>
                    </td>
                </tr>
                <?php $id++;$i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>