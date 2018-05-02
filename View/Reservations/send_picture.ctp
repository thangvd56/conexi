<?php
    echo $this->Html->css(array(
        'customerLedger',
        'font-awesome.min',
        'slick-1.6.0',
        'slick-theme'
    ));
    echo $this->Html->script(array(
        'jquery-migrate-1.2.1.min',
        'slick.min-1.6.0'
    ));
?>

<div class="row">
    <div class="col-xs-12 col-md-12">
        <h1 class="page-header text-primary">写真送信リストページ</h1>
    </div>
    <div class="col-xs-12 col-md-12">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <span class="name">
                    <?php echo $this->Html->link(h($user['User']['lastname'].' '.$user['User']['firstname']), '/customers/?id='.$user['User']['id'], array('class' => '')); ?>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <?php echo $this->Form->create('Reservation', array('type' => 'file', 'id' => 'form_upload', 'action' => 'upload_multiple')); ?>
                <input type="file" name="data[ReservationSendPictures][file][]" multiple id="multi_upload" class="hide">
                <input type="hidden" name="user_id" value="<?php echo $customer_id ?>" class="user_id">
                <input type="hidden" name="shop_id" value="<?php echo $shop_id ?>" class="shop_id">
                <input type="hidden" name="reservation_id" id="reservation_id">
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
    <div>
        
        &nbsp; &nbsp; &nbsp; &nbsp;
        <div class=" col-lg-12">
            <div class="pull-left">
                <div id="upload_loading" class="hide" style="text-align:right;"><?php echo $this->Html->image('loading.gif'); ?>&nbsp;ローディング... </div>
            </div>
            <div class="">
                <?php
                echo $this->Html->link('削除情報', array('controller' => 'customers', 'action' => 'deleted?type=photo_send'), array(
                    'class' => 'btn btn-success pull-right back_button mgb10',
                    'style' => 'width:21%; margin-left:7px;'
                    ));
                ?>
                <?php $disabled='';if($count_reservations==0){$disabled='disabled="disabled"';}?>
                 <button type="button" <?php echo $disabled; ?> style="width: 21%;" class="btn btn-success back_button pull-right mgb10 btnbrowse" id="<?php echo $customer_id ?>" data-target="#modalMemoPic">写真送信</button>
                
            </div>
        </div>
    </div>
</div>
<div id="record_list">
    <div class="record table-responsive">
        <?php echo $this->Session->flash(); ?>
        <table class="table table-bordered" id="reservation">
            <thead>
                <tr style="font-size:10pt;">
                    <th style="width: 200px;">送信日時</th>
                    <th style="width: 130px;">来院日時</th>
                    <th>写真一覧</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody >

            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content col-md-10">
            <div class="modal-header">
                <button type="button" class="close closeSmallModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">本当によろしいですか？</h4>
            </div>
            <div class="modal-body text-center"​>
                <div class="clearfix">&nbsp;</div>
                <div class="error-message" id="error-msg-delete"></div>
                <div id="delete_loading" class="hide"><?php echo $this->Html->image('loading.gif'); ?>&nbsp; 削除中...</div>
                <div class="clearfix">&nbsp;</div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-6 col-md-6"><button type="button" class="btn btn-block btn_color background" data-dismiss="modal">閉じる</button></div>
                    <div class="col-xs-6 col-md-6"><button type="button" id="btn_delete_confirm" class="btn btn-block btn_color but_design">はい</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Modal list Memo picture -->
<div id="modalListMemoPic" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-listMemo" role="document">
        <div class="modal-content" >
            <div class="modal-header border">
                <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="memoLabel">写真を送信する</h4>
            </div>
            <div class="modal-body">
                <section class="regular slider"></section>
                <div class="row">
                    <div class="col-sm-12 ">
                        <center><h3>来店履歴を選択</h3></center>
                        <div class="tablereservation table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <th>日付</th>
                                <th>担当名</th>
                                <th>時間</th>
                                <th>金額</th>
                                <!--<th>治療内容</th>-->
                                <th>人数</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-success back_button btn_back" data-toggle="modal" data-target="#modalMemoPic">戻る</button>
                    <button type="button" disabled="disabled" class="btn btn-success back_button btn_confirm_reservation">送信</button>
                </center>
            </div>
        </div>
    </div>
</div>

<div id="confirmSentPic" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-listMemo" style="width:70%;" role="document">
        <div class="modal-content" >
            <div class="modal-header border">
                <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="memoLabel">送信する写真はお間違いないでしょうか？</h4>
            </div>
            <div class="modal-body" >
                <div class="row" style="margin:auto 40px;">
                    <div class="col-sm-12">
                        <div class="image_list">

                        </div>
                    </div>
                    <div class="col-sm-12 ">
                        <center>
                            <h3><b>もし他人の写真を送信してしまった場合、<br>
                                    個人情報保護の視点からトラブルとなる可能性があります。<br>
                                    必ずお間違いないようにご確認くださいませ。<b></h3>
                        </center>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-success back_button btn_back" data-toggle="modal" data-target="#modalMemoPic">戻る</button>
                    <button type="button" class="btn btn-success back_button " id="btn_reservation">送信</button>
                    <?php
                    echo $this->Html->image('loading.gif', array('class' => 'loading_img hide',
                        'style' => 'width:50px;height:50px;'));
                    ?>
                </center>
            </div>
        </div>
    </div>
</div>

                                        
<style>
    .img-minWidth {
        min-width: 100px;
    }
    .empty{
        background : #F1F1F1;
    }
    .btn-gray {
        background: #F1F1F1;
    }
    .mgb10 {
        margin-bottom: 10px;
    }
    .modal-listMemo{
        position: relative;
        top: 50%;
        left:0;
        width: 80%;
        margin-left: 0px; 
        margin-top: 30px !important;
        margin: 0 auto;
    }
    .slider {
        width: 93%;
        margin: 10px auto 20px auto;
    }

    .slick-slide {
        margin: 0px 10px;
        height:130px !important;
    }

    .slick-slide img {
        width: 100%;
        height: 100%;
    }

    .slick-prev:before,
    .slick-next:before {
        color: black;
    }
    #modalListMemoPic .tablereservation {
        overflow-y: scroll;
        height: 235px;
    }

    #label_multiupload:before{
        display: none !important;
    }
/**
* Checkbox Four
*/
    .btn_add{
        cursor: pointer;
    }
    .modal_li {
        vertical-align: middle;
    }
    #confirmSentPic label:before{
        background-color: #000;
        color: gray;
        content: "X";
        display: block;
        border-radius: 50%;
        position: absolute;
        top: -11px;
        left: 87px;
        width: 25px;
        height: 25px;
        text-align: center;
        line-height: 28px;
        transition-duration: 0.4s;
        transform: scale(1);
        z-index: 99;
    }
</style>

<script>
    $(function () {

        $('body').on('click', '.get_click', function () {
            var data_id = $(this).closest('tr').attr('data-id');
            $('body').find('input[type="checkbox"]').each(function () {
                var id = $(this).closest('tr').attr('data-id');
                if (data_id != id) {
                    $(this).attr('checked', false);
                }
            });
        });

        $(".get_delete").click(function () {
            var reservation_id = $('#reservation_id').val();
            if (reservation_id == "") {
                $("#btn_delete_confirm").attr("disabled", "disabled");
            } else {
                $("#btn_delete_confirm").attr("disabled", false);
            }
            $('#del').val("");
            $('#del1').change(function () {
                var val = "";
                if (this.checked) {
                    $("#del1").val(1);
                } else {
                    $("#del1").val(val);
                }
            });
        });

        $("body").on("click", "#btn_delete_confirm", function () {
            var media = [];
            var checkboxs = $('#reservation').find("input[type='checkbox']:checked");
            $.each(checkboxs, function () {
                var imagepath = $(this).attr('data-id') + ',' + $(this).val();
                media.push(imagepath);
            });
            var del_physical = $('#del1:checked').val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'Reservations', 'action' => 'send_picture')); ?>",
                data: {action: 'delete', media: media, del_physical: del_physical},
                dataType: "json",
                type: "get",
                beforeSend: function () {
                    $(".error-msg-delete").html("");
                    $("#delete_loading").removeClass("hide");
                },
                success: function (data) {
                    console.log(data);
                    ListData();
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $("#delete_loading").addClass("hide");
                    $("#ModalDelete").modal("hide");
                }
            });
        });

        $(".btnbrowse").click(function () {
            $("#reservation_id").val("");
            $("#multi_upload").click();

        });

        $("body").on("click", ".btn_add", function () {
            var reservation_id = $(this).closest('tr').attr('data-id');
            $("#reservation_id").val(reservation_id);
            $("#multi_upload").click();
        })

        $("form#form_upload").on("change", "#multi_upload", function () {
            upload();
        });

        $('#modalListMemoPic .btn_back').click(function () {
            $("#modalListMemoPic").modal("hide");
        });

        function upload() {
            $("form#form_upload").ajaxForm({
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function () {
                    $('#upload_loading').removeClass('hide');
                },
                success: function (respond) {
                    console.log(respond);
                    if (respond.result === true) {
                        var reservation_id = $("#reservation_id").val();
                        if (reservation_id != null && reservation_id != "") {
                            $('#confirmSentPic').modal('show');
                            ListConfirm(respond.images);
                        } else {
                            $("#modalListMemoPic").modal("show");
                            ListMemoReservation(respond.images);
                        }
                    } else {
                        console.log(respond.msg);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                    console.log(throwError);
                },
                complete: function () {
                    $('#upload_loading').addClass('hide');
                    $('form#form_upload').find('input[type="file"]').val('');
                }
            }).submit();
        }

        function ListMemoReservation(images) {
            $('.regular').find('div').remove();
            $('.regular').removeClass('slick-initialized slick-slider');
            var user_id = $('input[name="user_id"]').val();
            if (images != undefined && images != null) {
                $.each(images, function (index, image) {
                    $('#modalListMemoPic .regular').append('<div><img class="img-responsive" src="' + image.path + '"/></div>');
                })
            }
            $(".regular").slick({
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 3
            });
            $('.modal').on('shown.bs.modal', function (e) {
                $('.regular').resize();
            });
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'Reservations', 'action' => 'get_reservations')) ?>",
                type: "get",
                data: {user_id: user_id},
                success: function (data) {
                    //console.log(data);
                    data = $.parseJSON(data);
                    var reservations = data.reservations;
                    $('#modalListMemoPic').find('table tbody tr').remove();
                    if (reservations != undefined && reservations != null) {
                        $.each(reservations, function (index, reservation) {
                            var tr = '<tr data-id="' + reservation.id + '">';
                            tr += '<td><input type="radio" value="' + reservation.id + '" name="radio"> ' + reservation.date + '</td>';
                            tr += '<td>' + reservation.staff_name + '</td>';
                            tr += '<td>' + reservation.time + '</td>';

                            tr += '<td>' + reservation.treatment_cost + '円</td>';
                            //tr += '<td>' + reservation.treatment_contents + '</td>';
                            tr += '<td>' + reservation.persion + '</td>';
                            tr += '</tr>';
                            $('#modalListMemoPic').find('table').append(tr);
                        })
                    }

                    $('.btn_confirm_reservation').on('click', function () {
                        $('#confirmSentPic').modal('show');
                        ListConfirm(images);
                    })
                }
                ,
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.statusText);
                },
                complete: function () {

                }
            });
        }

        function SaveMemoPictures(reservation_id) {
            var user_id = $('input[name="user_id"]').val();
            var image_chooses = [];
            var checkbox_images = $('#confirmSentPic').find('input[type="checkbox"]');
            $.each(checkbox_images, function () {
                image_chooses.push($(this).val());
            })

            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'Reservations', 'action' => 'save_reservation_photos')) ?>",
                dataType: "json",
                data: ({images: image_chooses, reservation_id: reservation_id, user_id: user_id}),
                beforeSend: function () {
                    $('#confirmSentPic .loading_img').removeClass('hide');
                },
                success: function (data) {
                    if (data.result == true) {
                        ListData();
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log("Error:" + xhr.status);
                },
                complete: function () {
                    $('#confirmSentPic').modal('hide');
                    $('#modalListMemoPic').modal('hide');
                    $('#confirmSentPic .loading_img').addClass('hide');
                }
            });
        }

        function ListConfirm(images) {
            var li_list = '';
            if (images != null) {
                $.each(images, function (index, image) {
                    li_list += '<li class="modal_li padding>">' +
                            '<input type="checkbox" class="main" value="' + image.path + '" id="cb_' + index + '" />' +
                            '<label for="cb_' + index + '" main-id="' + index + '" ></label>' +
                            '<img src="' + image.path + '" class="img-responsive img-minWidth" style="width:100px;height:100px;margin:5px;">' +
                            '</li>';
                })
            }
            $('#confirmSentPic .image_list').find('ul').remove();
            $('#confirmSentPic .image_list').html('<ul>' + li_list + '</ul>');
        }

        ListData();

        function ListData() {
            var user_id = $('.user_id').val();
            var shop_id = $('.shop_id').val();
            $.ajax({
                url: "<?php echo Router::url(array('controller' => 'Reservations', 'action' => 'send_picture')) ?>",
                type: "get",
                data: {action: 'list', customer_id: user_id, shop_id: shop_id},
                success: function (data) {
                    var reservations = $.parseJSON(data);
                    if (reservations != null) {
                        var result = '';
                        $.each(reservations, function (index, item) {
                            result += '<tr class="main" data-id="' + item[0]['id'] + '">';
                            result += '<td>';
                            $.each(item['M'], function (i, m) {
                                result += m['created'] + '<br>';
                            });
                            result += '</td>';
                            result += '<td>' + item[0]['date'] + '</td>';
                            result += '<td>';
                            result += '<div class="modal-body"><ul class="margin-top">';
                            $.each(item['M'], function (i, m) {
                                var photos = m['file'].split(',');
                                $.each(photos, function (pIndex, photo) {
                                    result += '<li class="modal_li padding" style="margin-right:10px;">';
                                    result += '<input type="checkbox" id="ch' + pIndex + i + index + '" name="chk_photo" data-id="' + m['id'] + '" class="main hide" value="' + photo + '" />';
                                    result += '<label for="ch' + pIndex + i + index + '" name="chk_photo" class="get_click" >';
                                    result += '<img src="<?php echo $this->webroot; ?>uploads/reservation_send_photos/' + photo + '" class="img-responsive img-minWidth" style="width:100px;height:100px">';
                                    result += '</label></li>';
                                });
                            });
                            result += '<li class="modal_li padding my_photo">';
                            result += '<img src="<?php echo $this->webroot; ?>img/photo_add.png" class="btn_add" style="width:40px;height:40px;">';
                            result += '<p style="font-size:10px;">写真を追加送信する場合は+ボタンをクリックしてください</p></li>';
                            result += '</ul><div></td>';
                            result += '<td> <br/>';
                            result += '<button type="button" class="btn btn-success color shadow get_delete" data-toggle="modal" data-target="#ModalDelete">削除</button>';
                            result += '<br/><p style="font-size:10px;">※削除する写真にチェックを入れ、削除ボ<br/>タンを押すことで写真の削除ができます。</p>';
                            result += '</td><tr>';
                        });
                        $('#reservation tbody').find('tr').remove();
                        $('#reservation tbody').append(result);
                    }                    
                },
                error: function (xhr, ajaxOptions, throwError) {
                    location.reload();
                }
            });
        }

        $('#confirmSentPic').on('click', 'label', function () {
            $(this).closest('li').remove();
        })
        $('#confirmSentPic').on('click', '.btn_back', function () {
            $('#confirmSentPic').modal('hide');
        })
        $('#modalListMemoPic').on('change', 'input[type="radio"]', function () {
            $('#modalListMemoPic').find('.btn_confirm_reservation').prop('disabled', false);

        });
        $('#btn_reservation').on('click', function (e) {
            e.preventDefault();
            var reservation_id = $("#reservation_id").val();
            if (reservation_id == null || reservation_id == "") {
                reservation_id = $('#modalListMemoPic').find('table tr').find('input[type="radio"]:checked').val();
            }
            SaveMemoPictures(reservation_id);
        });
    })


</script>