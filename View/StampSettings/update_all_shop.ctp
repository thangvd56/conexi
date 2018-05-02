
<style type="text/css">
    .icon_select {
        padding: 2px;
        background-image: url(http://aki.ovh/dl/down.png);
        background-repeat: no-repeat;
        background-position: 99.5% center;
    }
    .table>tbody>tr>td {
        border-top: 3px solid #efefef;
    }
    .disable_border, .no_border {
        border: 0 !important;
    }
    .change_width_input {
        width: 76px;
        font-size: 15px;
        text-align: right;
        border: 2px solid #aaa;
        border-radius: 5px;
        padding-right: 9px;
    }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <h1 class="page-header">アプリ作成 > スタンプ</h1>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <ol class="breadcrumb">
            <li class="active">
                スタンプ設定 <br><p class="sub_header">スタンプ30個でチケット一枚発行となります</p>
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 display-sm-12 display-md-6">
        <?php
            echo $this->Form->create('stamp_settings', array(
                'role' => 'form',
                'id' => 'form_stamp',
            ));
        ?>
        <table class="table">
            <tbody>
                <tr>
                    <td class="clear-left"><p class="word">スタンプカード枠数</p></td>
                    <td class="clear-right">
                        <?php
                            echo $this->Form->input('stamp_number', array(
                                'class' => 'form-control icon_select',
                                'id' => 'stamp_number',
                                'type' => 'select',
                                'options' => array(
                                    '15' => '15個',
                                    '20' => '20個',
                                    '25' => '25個',
                                    '30' => '30個',
                                    '35' => '35個',
                                    '40' => '40個',
                                    '45' => '40個',
                                    '50' => '50個',
                                ),
                                'label' => false,
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">アプリインストール時</p></td>
                    <td class="clear-right">
                        <?php
                            echo $this->Form->input('app_installation', array(
                                'class' => 'form-control icon_select',
                                'id' => 'app_installation',
                                'type' => 'select',
                                'options' => array(
                                    '0' => '0個',
                                    '1' => '1個',
                                    '2' => '2個',
                                    '3' => '3個',
                                    '4' => '4個',
                                    '5' => '5個',
                                    '6' => '6個',
                                    '7' => '7個',
                                    '8' => '8個',
                                    '9' => '9個',
                                    '10' => '10個',
                                ),
                                'label' => false,
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">アプリ起動時</p></td>
                    <td class="clear-right">
                        <?php
                            echo $this->Form->input('app_launch', array(
                                'class' => 'form-control icon_select',
                                'id' => 'app_launch',
                                'type' => 'select',
                                'options' => array(
                                    '0' => '0個',
                                    '1' => '1個',
                                    '2' => '2個',
                                    '3' => '3個',
                                    '4' => '4個',
                                    '5' => '5個',
                                    '6' => '6個',
                                    '7' => '7個',
                                    '8' => '8個',
                                    '9' => '9個',
                                    '10' => '10個',
                                ),
                                'label' => false,
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">来店時(ビーコン反応時)</p></td>
                    <td class="clear-right">
                        <?php
                            echo $this->Form->input('app_checkin', array(
                                'class' => 'form-control icon_select',
                                'id' => 'app_checkin',
                                'type' => 'select',
                                'options' => array(
                                    '0' => '0個',
                                    '1' => '1個',
                                    '2' => '2個',
                                    '3' => '3個',
                                    '4' => '4個',
                                    '5' => '5個',
                                    '6' => '6個',
                                    '7' => '7個',
                                    '8' => '8個',
                                    '9' => '9個',
                                    '10' => '10個',
                                ),
                                'label' => false,
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">特典画像文章</p></td>
                    <td class="clear-right">
                        <?php
                            echo $this->Form->input('benefit_image_sentence', array(
                                'class' => 'form-control',
                                'id' => 'benefit_image_sentence',
                                'label' => false,
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">特典詳細</p></td>
                    <td class="clear-right">
                        <?php
                            echo $this->Form->input('benefit_detail', array(
                                'class' => 'form-control',
                                'id' => 'benefit_detail',
                                'placeholder' => '20文字以内で入力してください',
                                'cols' => '30',
                                'rows' => '6',
                                'maxlength' => '256',
                                'value' => 'Hello',
                                'type' => 'textarea',
                                'label' => false,
                            ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="clear-left"><p class="word">有効期限</p></td>
                    <td class="clear-right">
                        <div class="panel-body disable_border">
                            <p>発行から
                                <?php
                                    echo $this->Form->input('valid_date', array(
                                        'class' => 'change_width_input',
                                        'id' => 'valid_date',
                                        'type' => 'number',
                                        'max' => '60',
                                        'min' => '10',
                                        'value' => '10',
                                        'onkeypress' => 'return false',
                                        'label' => false,
                                        'div' => false,
                                    ));
                                ?>
                                日間</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="clear-left clear-right">
                        <?php
                            echo $this->Form->hidden('group_id', array(
                                'value' => $this->request->query('group_id') ? $this->request->query('group_id') : '',
                                'id' => 'group_id',
                            ));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
            echo $this->Form->button('保存', array(
                'class' => 'btn btn-block btn_color btn_save',
            ));
        ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<div id="ModalConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label id="label-save">変更内容を保存します。本当によろしいですか？</label>
                <div class="clearfix">&nbsp;</div>
                <div id="save_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;保存中</div>
                <div class="text-center">
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_confirm_save" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(e) {
        $('body').on('click', '.btn_save', function(e) {
            e.preventDefault();
            $('#ModalConfirm').modal('show');
        });

        $('body').on('click', '#btn_confirm_save', function(e) {
            var form = $('body').find('#form_stamp').serialize();
            $.post('<?php echo $this->Html->url('/stamp_settings/update_all_shop'); ?>', form, function(data) {
                window.location.href = '<?php echo $this->Html->url('/stamp_settings'); ?>';
            });
        });
    });
</script>