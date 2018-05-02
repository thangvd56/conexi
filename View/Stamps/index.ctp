<?php
 echo $this->Html->css('pastNotice');
?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <h1 class="page-header">
                アプリ作成 - スタンプ
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8">
            <div class="well">
                <?php if (isset($stamp_setting)): ?>
                    <?php foreach ($stamp_setting as $key => $value): ?>
                <table class="table table-bordered">
                            <tr>
                                <td>
                                    <?php
                                    if (!empty($value['StampSetting']['benefit_image_sentence'])) {
                                        echo $this->Html->image('/uploads/stamps/' . $value['StampSetting']['benefit_image_sentence'], array(
                                            'class' => 'img-responsive img-center',
                                            'style'=>'width:70px;height:70px',
                                            'alt' => $value['StampSetting']['stamp_title'],
                                        ));
                                    } else {
                                        echo '<img src="'+URL+'img/noimage.jpg" style="width:70px;height:70px" class="img-responsive img-center">';
                                    }
                                    ?></td>
                                <td style="width:300px"><h4><?php echo $value['StampSetting']['stamp_title']; ?></h4></td>
                                <td>
                                    <?php
                                    echo $this->Html->link(' 編集 ', '/stamps/edit/' . $value['StampSetting']['id'], array('class' => 'btn btn-success back_color butt', 'style' => 'width:92px;'));
                                    ?>
                                </td>

                            </tr>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 text-center" >
        <div class="panel dotted-border">
            <div class="" id="btn_add">
                + Add New
            </div>
        </div>
    </div>
</div><!-- container-fluid -->
<script>
    $(function () {

        $("#btn_add").click(function () {
            window.location.replace(URL + "users/view/app-stamp-create");
        });
    });
</script>