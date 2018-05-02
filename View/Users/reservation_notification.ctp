<div class="container-fluid well">
    <div class="col-md-12 title">
        <?php echo$this->Html->link('予約事前通知', array('escape' => false)); ?>
    </div>
    <div class="col-md-10 text-center">
        <?php
        echo $this->Form->create('News', array(
            'class' => 'form-horizontal',
            'type' => 'file',
            'enctype' => 'multipart/form-data'));
        ?>
        <table class="table table-bordered">

            <tbody>
                <tr>
                    <td colspan="2">
                        こちらの通知は、予約システムで予約を入れているお客様に事前に自動通知が届きます。
                    </td>
                </tr>
                <tr>
                    <td class="col-md-2">当日通知
                        （２時間前）</td>
                    <td class="col-md-4">     
                        <div class="btn-group btn-toggle"> 
                            <button class="toggle btn btn-default" name="day_notice" id="btn-day-notice1" value="1">ON</button>
                            <button class="toggle btn btn-primary active" name="day_notice" id="btn-day-notice2" value="0">OFF</button>
                            <input type="hidden" value="0" name="data[News][day_notice]" id="hid-day-notice"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-md-2">前日通知
                        （予約時間）</td>
                    <td class="col-md-4"> 
                        <div class="btn-group btn-toggle"> 
                            <button class="toggle btn btn-default" name="day_before_notice" id="btn-day-before-notice1" value="1">ON</button>
                            <button class="toggle btn btn-primary active" name="day_before_notice" id="btn-day-before-notice2" value="0">OFF</button>
                            <input type="hidden" value="0" name="data[News][day_before_notice]" id="hid-day-before-notice"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-md-2">２日前通知
                        （予約時間）</td>
                    <td class="col-md-4">
                        <div class="btn-group btn-toggle"> 
                            <button class="toggle btn btn-default" name="2nd_notice" id="btn-2nd-notice1" value="1">ON</button>
                            <button class="toggle btn btn-primary active" name="2nd_notice" id="btn-2nd-notice2" value="0">OFF</button>
                            <input type="hidden" value="0" name="data[News][2nd_notice]" id="hid-2nd-notice"/>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="col-md-2">タイトル</td>
                    <td class="col-md-4">  <?php
                        echo $this->Form->input('title', array(
                            'class' => 'form-control ',
                            'label' => false,
                            'placeholder' => '２０文字以内で入力してください'
                        ));
                        ?></td>
                </tr>
                <tr>
                    <td class="col-md-2">本文</td>
                    <td class="col-md-4"> <?php
                        echo $this->Form->input('message', array(
                            'class' => 'form-control ',
                            'label' => false,
                            'id' => 'message',
                            'placeholder' => '※1行につき20文字程度で「改行」を入れると見やすいレイアウトになります。',
                            'type' => 'textarea'));
                        ?></td>
                </tr>
                <tr>
                    <td class="col-md-2">画像</td>
                    <td class="col-md-4"> <?php
                        echo $this->Form->input('file', array(
                            'class' => 'form-control',
                            'label' => false,
                            'id' => 'file',
                            'type' => 'file',
                            'required' => true));
                        ?>
                </tr>
                <tr>
                    <td class="col-md-2 btn" colspan="2"><?php
                        echo $this->Form->submit('編集', array(
                            'class' => 'btn btn-primary margin-top-10'));
                        ?>
                        <?php
                        echo $this->Form->submit('保存', array(
                            'class' => 'btn btn-primary margin-top-10'));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script>
    $('.btn-toggle').click(function () {
        $(this).find('.btn').toggleClass('active');

        if ($(this).find('.btn-primary').size() > 0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }
        $(this).find('.btn').toggleClass('btn-default');
    });
    $(function () {
        $("#btn-day-notice1").click(function (e) {
            e.preventDefault();
            var val = $("#btn-day-notice1").val();
            $("#hid-day-notice").val(val);
        });
        $("#btn-day-notice2").click(function (e) {
            e.preventDefault();
            var val = $("#btn-day-notice2").val();
            $("#hid-day-notice").val(val);
        });

    });
    $(function () {
        $("#btn-day-before-notice1").click(function (e) {
            e.preventDefault();
            var val = $("#btn-day-before-notice1").val();
            $("#hid-day-before-notice").val(val);
        });
        $("#btn-day-before-notice2").click(function (e) {
            e.preventDefault();
            var val = $("#btn-day-before-notice2").val();
            $("#hid-day-before-notice").val(val);
        });

    });
    $(function () {
        $("#btn-2nd-notice1").click(function (e) {
            e.preventDefault();
            var val = $("#btn-2nd-notice1").val();
            $("#hid-2nd-notice").val(val);
        });
        $("#btn-2nd-notice2").click(function (e) {
            e.preventDefault();
            var val = $("#btn-2nd-notice2").val();
            $("#hid-2nd-notice").val(val);
        });

    });
</script>