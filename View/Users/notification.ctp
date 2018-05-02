<div class="container-fluid well">
    <div class="col-md-12 title">
        <?php echo$this->Html->link('お知らせ通知', array('escape' => false)); ?>
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
                        This notification, you can use to inform and narrowing of notification to the entire
                    </td>
                </tr>
                <tr>
                    <td class="col-md-2">配信先対象</td>
                    <td class="col-md-4"> <?php
                        $status = array(
                            '店舗名2' => '店舗名2',
                            '店舗名3' => '店舗名3',
                            '店舗名4' => '店舗名4'
                        );
                        echo $this->Form->select('type', $status, array(
                            'class' => 'form-control',
                            'id' => 'type',
                            'empty' => array(
                                '店舗名1' => '店舗名1')
                        ));
                        ?></td>
                </tr>
                <tr>
                    <td class="col-md-2">通知日</td>
                    <td class="col-md-4">
                        <?php
                        echo $this->Form->input('date', array(
                            'class' => 'form-control',
                            'label' => false,
                            'id' => 'date',
                            'type' => 'text',
                            'value' => date('Y-m-d')
                        ));
                        ?></td>
                </tr>
                <tr>
                    <td class="col-md-2">時間</td>
                    <td class="col-md-4">  <?php
                        echo $this->Form->input('time', array(
                            'class' => 'form-control',
                            'label' => false,
                            'id' => 'time',
                            'type' => 'text'));
                        ?></td>
                </tr>
                <tr>
                    <td class="col-md-2">タイトル</td>
                    <td class="col-md-4"> <?php
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
                            'required' => true
                        ));
                        ?>
                </tr>
                <tr>
                    <td class="col-md-2 btn" colspan="2">
                        <?php
                        echo $this->Form->submit(__('編集', true), array(
                            'name' => 'cancel',
                            'div' => false,
                            'class' => 'submit btn btn-primary margin-top-10'
                        ));
                        ?>
                        <?php
                        echo $this->Form->submit(__('保存', true), array(
                            'name' => 'ok',
                            'div' => false,
                            'class' => 'submit btn btn-primary margin-top-10'
                        ));
                        ?>
                    </td>
                    <?php echo $this->Form->end(); ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $('#time').timepicker();
    $("#date").datepicker();
</script>
