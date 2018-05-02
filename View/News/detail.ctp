<?php
echo $this->Html->css('news');
echo $this->Html->css('custom');
?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-10">
            <h1 class="page-header">
                NEWS 管理者権限
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h3>通知履歴</h3>
            <?php
            echo (date('Y/m/d h:i', strtotime($delivered_date)));
            ?>
        </div>
        <div class="col-md-10">
            <table class="table table-responsive">
                <tr>
                    <td>通知種類</td>
                </tr>
                <tr>
                    <td style="border:solid 1px #F0F0F0">
                        <?php
                        echo $type;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>タイトル</td>
                </tr>
                <tr>
                    <td style="border:solid 1px #F0F0F0"><?php
                        echo $title;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>本分</td>
                </tr>
                <tr>
                    <td style="border:solid 1px #F0F0F0">
                        <?php
                        echo $message
                        ?>
                        <br/>
                        <?php
                        if ($image) :
                            foreach ($image as $key => $value) :
                            echo $this->Html->image('/uploads/photo_notices/'.$value['M']['file'], array(
                                'class' => 'img-responsive img-center',
                                'style' => 'width:150px; height:150px;',
                                'alt' => $title
                            ));
                            endforeach;
                        endif;
                        ?>
                    </td>
                </tr>
            </table>
            <input type="hidden" id="id" name='id' value="<?php echo $id; ?>"/>
                   <div class="col-md-4">
                <div class="col-xs-6 col-md-6"><button type="button" id="btn_return" class="btn btn-block btn_color">戻る</button></div>
            </div>
        </div>
    </div>
<script>
    
    $(function () {
        //Function hide notification after read
        function notification_read() {
            var id = $("#id").val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'news', 'action' => 'detail')) ?>",
                data: "&action=read&id=" + id,
                error: function (xhr) {
                    console.log("Error:" + xhr.status);
                }
            });
        }
        notification_read();
        $("#btn_return").click(function () {
            window.location.replace(URL + 'news');
        });
    });

</script>