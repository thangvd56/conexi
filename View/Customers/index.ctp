<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            顧客台帳
        </h1>
    </div>
</div>
<div class="row whole-content">
    <!-- From search-->
    <?php echo $this->Form->create('customer', array('id' => 'form_search')) ?>
    <div class="col-lg-4 change_width_col4">
        <div class="form-group">
            <h3>絞り込み検索</h3>
            <p>キーワード検索</p>
        </div>
        <div class="form-group top">
            <?php
            echo $this->Form->input('keyword', array(
                'class' => 'search form-control change_height_butt',
                'name' => 'keyword',
                'label' => false));
            ?>
        </div>
        <?php
        $role = $this->Session->read('Auth.User.role');
        if ($role == ROLE_HEADQUARTER) : ?>
        <div class="form-group top">
            <?php
            echo $this->Form->input('shop_name', array(
                'type' => 'select',
                'name' => 'shop_name',
                'class' => 'shop_name form-control icon_select change_height_butt',
                'label' => false,
                'options' => $shop
            ));
            ?>
        </div>
        <?php endif; ?>
        <div class="form-group top">
            <?php
            echo $this->Form->input('date', array(
                'class' => 'bd_picker form-control icon_select change_height_butt',
                'id' => 'date_picker',
                'placeholder' => '生年月日',
                'label' => false
            ));
            ?>
        </div>
        <div class="form-group top">
            <?php
               echo $this->Form->input('gender', array(
                   'type' => 'select',
                   'class' => 'form-control icon_select change_height_butt',
                   'id' => 'customerGender',
                   'name' => 'gender',
                   'empty' => SELECT_GENDER,
                   'placeholder' => '性別',
                   'options' => unserialize(GENDER),
                   'label' => false
               ));
            ?>
        </div>
        <div class="form-group top">
            <?php
                echo $this->Form->input('area', array(
                    'type' => 'select',
                    'class' => 'form-control icon_select change_height_butt',
                    'empty' => SELECT_AREA,
                    'label' => false,
                    'options' => $area
                ));
            ?>
        </div>
        <!-- /.panel -->
        <!-- Result search -->
        <div id="result_search" class="hide"></div>
        <div id="result_search_temp" class="well content2 hide">
            <div  class="text-center">
                <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
            </div>
        </div>
        <?php
            echo $this->Html->link('削除情報', array(
                'controller' => 'customers',
                'action' => 'deleted'), array(
                'class' => 'btn btn-block btn_color btn_deleted_list'
            ));
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
    <!-- End form search-->
    <div class="col-lg-8 no-border-left customerEditForm">
    <?php echo $this->element('customer'); ?>
    </div>
    <div id="loading_fetch_tag" class="well content2 hide">
        <div  class="text-center">
            <?php echo $this->Html->image('/uploads/loading.gif', array()) . ' ローディング'; ?>
        </div>
    </div>
    <div class="col-lg-8 no-border-left add_top style">
        <div id="fetch_tags" class="row"></div>
    </div>
    <style>
        .selected {
            background: gray;
        }
    </style>
</div>
<!--Modal Delete-->
<div id="ModalDeleteCustomerConfirm" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <label class="label-delete"></label>
                <div class="clearfix">&nbsp;</div>
                <div class="text-center">
                    <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;削除中</div>
                    <input type="button" value="キャンセル" class="btn btn-success color back but_design2" data-dismiss="modal" style="width: 100px;">
                    <input type="button" id="btn_delete_customer" value="はい" class="btn btn-success color back_color but_design" style="width: 100px;">
                </div>
            </div>
        </div>
    </div>
</div>

<!--Modal CheckIn-->
<div id="modalCheckin" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content col-md-10">
            <div class="modal-header border">
                <button type="button" class="close closeSmallModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title checkinLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p class="col-xs-2 control-label padding-word">店舗名</p>
                    <div class="col-xs-10">
                        <?php
                        echo $this->Form->input('shop_name', array(
                            'type' => 'select',
                            'id' => 'getclientList',
                            'name' => 'shop_name',
                            'class' => 'shop_name form-control icon_select change_height_butt',
                            'label' => false,
                            'options' => $shop
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <?php
            echo $this->Form->create('Reservation', array(
                'id' => 'CheckReservation',
                'novalidate' => true,
                'inputDefaults' => array(
                    'legend' => false,
                    'label' => false,
                    'div' => false
            )));
            ?>
            <div class="modal-footer">
                <div class="form-group bottom_set">
                    <div class="ReList">
                        
                    </div>
                    <div class="loadingList" style="display : none; text-align: center">
                        <?php echo $this->Html->image('/uploads/loading.gif'); ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-center" data-dismiss="modal">
                        <div class="panel-default btn_cancel_checkin">
                            <div class="icon change_height">
                                <div class="menu-icon cancel operation" data-type="cancel"></div>
                                <p class="menu-icon-text">キャンセル</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 text-center">
                        <input type="hidden" class="customer_id">
                        <div class="panel-default btn_checkin_customer">
                            <div class="icon change_height">
                                <div class="menu-icon checkin" data-type="checkin"></div>
                                <p class="menu-icon-text">チェックイン</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<?php
echo $this->Html->css('customerLedger');
echo $this->Html->css('gips');
echo $this->Html->css('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
echo $this->Html->css('customer');

echo $this->Html->script('customer');
echo $this->Html->script(array('datepicker-ja'));