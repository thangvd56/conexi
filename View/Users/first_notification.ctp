<div class="container-fluid well">
    <div class="col-md-12 title">
        <?php echo$this->Html->link('初回通知', array('escape' => false)); ?>
    </div>
    <div class="col-md-10 text-center">
       <?php
        echo $this->Form->create('News', array(
            'class' => 'form-horizontal',
            'type'=>'file',
            'enctype'=>'multipart/form-data'));
        ?>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2">
                        こちらの通知は、アプリをダウンロード時に一度だけ表示される通知です。挨拶や店舗のコンセプトを通知すると効果的です。
                    </td>
                </tr>
                <tr>
                    <td class="col-md-2">タイトル</td>
                    <td class="col-md-4"> <?php
                        echo $this->Form->input('title', array(
                            'class' => 'form-control ',
                            'label' => false,
                            'id' => 'title',
                            'placeholder' => '２０文字以内で入力してください',
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
                            'required'=> true
                            ));
                        ?>
                </tr>
                <tr>
                    <td class="col-md-2 btn" colspan="2"><?php
                        echo $this->Form->submit('取消', array(
                            'class' => 'submit btn btn-primary margin-top-10'));
                        ?>
                        <?php
                        echo $this->Form->submit('保存', array(
                            'class' => 'submit btn btn-primary margin-top-10'));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
