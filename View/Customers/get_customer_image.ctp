<p class="set_top">メモ写真</p>
<div class="div_well">
    <div id="myCarousel" class="carousel slide">
        <!-- Carousel items -->
        <div class="carousel-inner">
            <?php
            $items = 1;
            $active = 1;
            error_reporting(0); //Disable warning message
            foreach ($memo_pic as $key => $value):

                if ((5 % ($items - 1)) == 0 or ( ($items - 1) % 5) == 0) {
                    if ($active == 1) {
                        echo '<div class="item active"><div class="row">';
                    } else if ($items != 2) {
                        echo '<div class="item"><div class="row">';
                    }
                }
                echo '<div class="col-xs-3"><img src="'.$this->html->url('/', true).'uploads/send_memo_picture/'.$value['SendMemoPicture']['image'].'" alt="Image" class="img-responsive"></div>';
                if (($items % 5) == 0) {
                    echo '</div></div>';
                }
                if (count($memo_pic) == $items) {
                    echo '</div></div>';
                }
                $items++;
                $active++;
            endforeach;
            ?>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
    </div>
</div>

<p class="set_top">写真送信</p>
<div class="div_well">
    <div id="myCarousel1" class="carousel slide">
        <!-- Carousel items -->
        <div class="carousel-inner">
            <?php
            $send_items = 1;
            $send_active = 1;
            error_reporting(0); //Disable warning message
            foreach ($send_pic as $key => $send_value):

                if ((5 % ($send_items - 1)) == 0 or ( ($send_items - 1) % 5) == 0) {
                    if ($send_active == 1) {
                        echo '<div class="item active"><div class="row">';
                    } else if ($send_items != 2) {
                        echo '<div class="item"><div class="row">';
                    }
                }
                echo '<div class="col-xs-3"><img src="'.$this->html->url('/', true).'uploads/send_memo_picture/'.$send_value['SendMemoPicture']['image'].'" alt="Image" class="img-responsive"></div>';
                if (($send_items % 5) == 0) {
                    echo '</div></div>';
                }
                if (count($send_pic) == $send_items) {
                    echo '</div></div>';
                }
                $send_items++;
                $send_active++;
            endforeach;
            ?>
        </div>
        <a class="left carousel-control" href="#myCarousel1" data-slide="prev">‹</a>
        <a class="right carousel-control" href="#myCarousel1" data-slide="next">›</a>
    </div>
</div>

