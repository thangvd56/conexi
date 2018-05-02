<?php
$items = 1;
error_reporting(0); //Disable warning message
foreach ($memo_pic_album as $key => $value):

    if ((4 % ($items - 1)) == 0 or ( ($items - 1) % 4) == 0) {
        if ($items != 2 && $items != 3) {
            echo '<ul class="margin-top">';
        }
    }
    echo '<li class="modal_li">';
    echo '<input type="checkbox" class="cb_select" data-image="'.$value['SendMemoPicture']['image'].'" data-id="'.$value['SendMemoPicture']['id'].'" id="cb' . $items . '">';
    echo '<label class="lb_select"  for="cb' . $items . '"><img src="' . $this->Html->url('/', true) . 'uploads/send_memo_picture/' . $value['SendMemoPicture']['image'] . '" alt="image" class="img-responsive"></label>';
    echo '</li>';

    if (($items % 4) == 0) {
        echo '</div>';
    }
    if (count($memo_pic_album) == $items && ($items % 4) != 0) {
        echo '</div>';
    }
    $items++;

endforeach;
?>
<script type="text/javascript">

</script>
