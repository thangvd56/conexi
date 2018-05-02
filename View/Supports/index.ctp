
<?php
echo $this->Html->css('userSetting');
echo $this->Html->css('support');

?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.3.2.js"></script>
<style>
    .change_style_d {
        border: 0;
        border-radius: 6px;
        background: #fff;
        width: 100%;
        cursor: pointer;
        padding-left: 3px;
        margin-bottom: 10px;
    }
    .support {
        background-color: #f5f5f5;
        border:solid 2px #f5f5f5;
    }
    .q_a{
        background-color: #f5f5f5;
        border:solid 2px #f5f5f5
    }
</style>
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-md-10">
            <h1 class="page-header">
                サポート
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    サポートについて
                </li>
            </ol>

        </div>
    </div>
    <!-- /.row -->
    <div class="row" id="content_plan">
        <div class="col-xs-12 col-md-10">
            <div class="support">
                <?php foreach ($support as $key => $value): ?>
                    <div class="panel-body change_style_d">
                        <div class="col-xs-4 col-md-3 add_border_right">
                            <?php //echo $this->Html->image('arrow.PNG', array('alt' => 'image')); ?>
                            &nbsp; <?php echo $value['Support']['title']; ?></div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"><p><?php echo $value['Support']['detail']; ?></p></div>
                        <div class="col-md-3"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <h3>Q&A</h3>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-xs-12 col-md-10">
            <div class="q_a">
                <?php
                $i = 5;
                foreach ($question_answer as $key => $value):
                    ?>
                    <div class="panel-body change_style_d" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>">
                        <div class="col-xs-9 col-md-9"><p class="question_word">Q</p> &nbsp; <?php echo $value['QuestionAnswer']['question']; ?></div>
                        <div class="col-xs-3 col-md-3">
                            <span class="glyphicon glyphicon-triangle-bottom right" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapseExample"></span>
                        </div>
                    </div>
                    <div style="background: gray;
                         color: #fff;
                         border-radius: 5px;
                         margin: -9px 0px 9px 0px;"
                         class="collapse"
                         id="collapse<?php echo $i; ?>">
                        <div class="card card-block" style="margin-left:-8px;">
                            <p class="question_word">A</p> &nbsp; <?php echo $value['QuestionAnswer']['answer']; ?>
                        </div>
                    </div>

                    <?php
                    $i++;
                endforeach;
                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('span.glyphicon-triangle-bottom').click(function () {
                $(this).toggleClass("glyphicon-triangle-top");
            });
//            $('div[data-toggle=\'collapse\']').click(function(e){
//                $(this).find('span.glyphicon-triangle-bottom').toggleClass("glyphicon-triangle-right");
//            });
        });
    </script>

