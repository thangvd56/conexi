$('#modal').on('show.bs.modal', function() {
    var modal = $(this);
    modal.find('.modal-title').text('パスワード変更');
});

$('#modalMemo').on('show.bs.modal', function() {
    var modal = $(this);
    modal.find('.modal-title').text('XXXの写真を送信');
});
    
function viewReservation() {
    //view reservation list of customer
    var ci = $('.customer_id').val();
    if (ci) {
        window.location = URL + 'reservations/view/' + ci;
    } else {
        alert('ユーザーを選択してください。');
    }
}

function sendPictureReservation() {
    //view reservation list of customer
    var ci = $('.customer_id').val();
    var shopId = '';
    if ($('.shop_name').length) {
        shopId = $('.shop_name').val();
    }
    if (ci) {
        window.location = URL + 'reservations/send_picture/' + ci + '/?shop_id=' + shopId;
    } else {
        alert('ユーザーを選択してください。');
    }
}

$(function () {
    $('.disable').prop('disabled', true);
    $('#txt_birthday, #date_picker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '1803:2090',
        defaultDate: '1980-01-01'
    }, $.datepicker.regional['ja']);

    //Check in
    $('.btn_cancel_checkin').click(function(e) {
        e.preventDefault();
        var that = $('#CheckReservation');
        var type = $(this).find('.operation').attr('data-type');
        var ci = $('.customer_id').val();
        $.ajax({
            url : URL + 'reservations/checkIn/' + ci + '?type=' + type,
            data : that.serializeArray(),
            type : 'post',
            dataType : 'json',
            success : function (data) {
                var message = '';
                if (data.result === 'Success') {
                    message = 'チェックインしまし';
                    $('#modalCheckin').modal('hide');
                } else {
                    message = 'エラー';
                }
               alert(message);
            }
        });
    });

    $('.btn_checkin').on('click', function() {
        var ci = $('.customer_id').val();
        var shopId = 0;
        if ($('#customerShopName').length) {
            shopId = $('#customerShopName').val();
            $('#getclientList').val(shopId);
        }
        getUnCheckInByClientId(ci, shopId);
    });

    $('#getclientList').on('change', function() {
        var ci = $('.customer_id').val();
        getUnCheckInByClientId(ci, $(this).val());
    });

    $('body').on('change', '#checkList', function() {
        $('input:checkbox').prop('checked', $(this).prop('checked'));
    });

    function getUnCheckInByClientId(ci, shopId) {
        if ('' === ci) {
            alert('ユーザーを選択してください。');
            return false;
        }

        $('.loadingList').show();
        $('#modalCheckin').modal('show');
        $('.ReList').html('');
        $.ajax({
            url: URL + 'reservations/getUnCheckInByClientId/' + ci,
            data : {shop : shopId},
            dataType: 'json',
            success: function (respond) {
                if (respond) {
                    var html = '<div calss="col-md-12"><table class="table tabl-bordered table-striped"><tbody>';
                    if (undefined !== respond[0]) {
                        $.each(respond[0], function(i, v) {
                            html += '<tr>';
                            html += '<td><input type="radio" class="reId" name="data[Reservation][id][]" value="'+ v.Reservation.id +'" /></td>';
                            html += '<td>' + v.Reservation.date + '</td>';
                            html += '<td>' + v.Staff.name + '</td>';
                            html += '<td>' + v.Reservation.start + '~' + v.Reservation.start + '</td>';
                            html += '<td>' + v.Reservation.treatment_contents + '</td>';
                            html += '<td>' + (parseInt(v.Reservation.adult) + parseInt(v.Reservation.child)) + '</td>';
                            html += '</tr>';
                        });
                    }

                    html += '</tbody></table></div>';
                    html += '</div>';
                    $('.ReList').append(html);
                    $('.loadingList').hide();
                }
            }
        });
    }

    $('body').on('click', '.btn_checkin_customer', function(e) {
        e.preventDefault();
        var that = $('#CheckReservation');
        var type = $(this).find('.checkin').attr('data-type');
        var ci = $('.customer_id').val();
        var shopId = $('#getclientList').val();
        $.ajax({
            url : URL + 'reservations/checkIn/' + ci + '?type=' + type + '&shop_id=' + shopId,
            data : that.serializeArray(),
            type : 'post',
            dataType : 'json',
            success : function(data) {
                var message = '';
                if (data.result === 'Success') {
                    message = 'チェックインしました。';
                    $('#modalCheckin').modal('hide');
                    $('#' + ci).click();
                } else {
                    message = 'エラー';
                }

               alert(message);
            }
        });
    });

    //Fetch result search
    function fetch_result_search() {
        var data = {'action': 'index'};
        if ($('#customerShopName').length > 0) {
            data['shop_id'] = $('#customerShopName').val();
        }

        $.ajax({
            url: URL +  'customers/result_search',
            data: data,
            dataType: 'html',
            type: 'get',
            beforeSend: function() {
                $('#result_search').addClass('hide');
                $('#result_search_temp').removeClass('hide');
            },
            success: function(respond) {
                $('#result_search').html(respond);
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log('Error:' + xhr.statusText);
            },
            complete: function() {
                $('#result_search').removeClass('hide');
                $('#result_search_temp').addClass('hide');
            }
        });
    }
    
    fetch_result_search();

    //Search customer
    function search_customer() {
        var keyword = $('.search').val();
        var shop_id = $('.shop_name').val();
        var birthday = $('.bd_picker').val();
        var gender = $('#customerGender').val();
        var area = $('#customerArea').val();
        $.ajax({
            url: URL + 'customers/result_search',
            data: 'action=search&keyword=' + keyword + '&shop_id=' + shop_id + '&birthday=' + birthday + '&gender=' + gender + '&area=' + area,
            type: 'get',
            dataType: 'html',
            beforeSend: function () {
                $('#result_search').addClass('hide');
                $('#result_search_temp').removeClass('hide');
            },
            success: function (respond) {
                $('#result_search').html(respond);
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Error:' + xhr.status);
            },
            complete: function () {
                $('#result_search').removeClass('hide');
                $('#result_search_temp').addClass('hide');
            }
        });
    }
    
    $('.search').keyup(function() {
        search_customer();
    });
    
    $('.shop_name, #date_picker, #customerGender, #customerArea').change(function() {
        search_customer();
    });

    //Delete customer
    $('#btn_delete').click(function() {
        var ci = $('.customer_id').val();
        if (ci === '') {
            alert('ユーザーを選択してください。');
            return false;
        }

        $('.label-delete').html('本当によろしいですか？');
        $('.label-delete-confirm').html('本当によろしいですか？');
        $('#ModalDeleteCustomerConfirm').modal('show');
    });


    $('#btn_delete_customer').click(function() {
        var ci = $('.customer_id').val();

        if (ci === '') {
            alert('ユーザーを選択してください。');
            $('#ModalDeleteCustomerConfirm').modal('hide');
            return false;
        }

        $.ajax({
            url: URL +  'customers/index',
            data: 'action=delete_customer&id=' + ci,
            type: 'get',
            dataType: 'json',
            beforeSend: function () {
                $('#delete_loading').removeClass('hide');
            },
            success: function (respond) {
                if (respond.result) {
                    $('#ModalDeleteCustomerConfirm').modal('hide');
                    window.location.reload();
                }
            }
        });
    });

    //Get customer info
    $('body').on('click', '.get_customer_info', function (e) {
        $('#result_search').find('.active').removeClass('active').attr('src', URL + 'img/arrow.PNG');
        var lastImage = $(this).find('img:last');
        lastImage.addClass('active');
        lastImage.attr('src', URL + 'img/arrow-down.PNG');

        var id = $(this).attr('id');
        var name = $(this).attr('data-name');
        $('.customer_id').val(id);
        $('#customer_name').val(name);
        $('.user_id').val(id);
        $('#btn_save').html('編集');
        var shopId = 0;
        if ($('#customerShopName').length) {
            shopId = $('#customerShopName').val();
        }
        get_customer_info(id, shopId);
        fetch_tags(id);
        history.pushState(null, null, URL + 'customers?id=' + id + '&shop_id=' + shopId);
        e.preventDefault();
    });
    
    function get_customer_info(id, shopId) {
        $.ajax({
            url: URL +  'customers/index',
            data: 'action=customer_info&customer_id=' + id + '&shop_id=' + shopId,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('.load_customer_info').removeClass('hide');
            },
            success: function(respond) {
                var user = respond.data.User;
                if (respond.result === 'success') {
                    $('#txt_lastname').val(user.lastname);
                    $('#txt_firstname').val(user.firstname);
                    $('#txt_lastname_kana').val(user.lastname_kana);
                    $('#txt_firstnamekana').val(user.firstname_kana);
                    $('#txt_birthday').val(user.birthday);

                    if ($('#txt_gender').find('option[value="'+ user.gender +'"]').val() == user.gender ) {
                        $('#txt_gender').find('option').removeAttr('selected');
                        $('#txt_gender').find('option[value="'+ user.gender +'"]').prop('selected', true);
                    }

                    if ($('#txt_area').find('option[value="'+ user.area_id +'"]').val() == user.area_id ) {
                        $('#txt_area').find('option').removeAttr('selected');
                        $('#txt_area').find('option[value="'+ user.area_id +'"]').prop('selected', true);
                    }

                    $('#txt_mobile').val(user.contact);
                    $('#txt_membership_id').val(user.membership_id);
                    $('#txt_model_id').val(user.user_code);
                    $('#customer_name').val(user.lastname + ' ' + user.firstname);
                    $('.checkinLabel').html(user.lastname + ' ' + user.firstname + 'をチェックインさせる');
                    $('.customer_id').val(user.id);
                    $('.reservation_num').html(respond.count);
                    $('#txt_model_id_chage').val(user.model_id_change);
                    $('#txt_email').val(user.email);

                    if ( (user.firstname == null && user.lastname == null) || (user.firstname == '' && user.lastname == '') ) {
                        $('.customer_name').html(user.lastname_kana + ' ' + user.firstname_kana);
                    } else {
                        $('.customer_name').html(user.lastname + ' ' + user.firstname);
                    }

                    if (respond.is_checkin == 0) {
                        $('.count_reservation').css('background-color', '#ccc');
                    } else {
                        if (respond.count > 0) {
                            $('.count_reservation').css('background-color', 'greenyellow');
                        }
                    }
                }
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log('Error:' + xhr.status);
            },
            complete: function () {
                $('.disable').prop('disabled', true);
                $('.load_customer_info').addClass('hide');
            }
        });
    }

    function get_customer_image(id) {
        $.ajax({
            url: URL + 'customers/get_customer_image',
            data: 'customer_id=' + id,
            beforeSend: function () {
                $('.user_image').html(URL + 'uploads/loading.gif');
            },
            success: function (respond) {
                $('.user_image').html(respond);
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log('Error:' + xhr.status);
            },
            complete: function () {

            }
        });
    }

    //Save customer
    $('.btn_save_customer').click(function (e) {
        e.preventDefault();
        var btn_text = $('#btn_save').html();
        if (btn_text === '編集') {
            $('#btn_save').html('保存');
            $('.disable').prop('disabled', false);
        }
        if (btn_text === '保存') {
            $('#btn_save').html('編集');
            var data = $('#form_customer').serialize();
            var id = $('.customer_id').val();
            $.ajax({
                url: URL + 'customers/index',
                data: data + '&action=save&id=' + id,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $('.save_customer_info').removeClass('hide');
                },
                success: function (respond) {
                    if (respond.result === 'success') {
                        fetch_result_search();
                        var first_name = $('#txt_lastname').val();
                        var last_name = $('#txt_firstname').val();
                        $('.customer_name').html(first_name + ' ' + last_name);
                    }
                },
                error: function (xhr, ajaxOptions, throwError) {
                    console.log('Error:' + xhr.status);
                },
                complete: function () {
                    $('.save_customer_info').addClass('hide');
                    $('.disable').prop('disabled', true);
                }
            });
        }
    });

    //Fetch tag
    function fetch_tags(id) {
        if (undefined === id) {
            id = '';
        }
        $.ajax({
            url: URL + 'customers/index?action=fetch_tags&customer=' + id,
            beforeSend: function() {
                $('#loading_fetch_tag').removeClass('hide');
            },
            success: function (respond) {
                $('#fetch_tags').html(respond);
            },
            error: function (xhr, ajaxOption, throwError) {
                console.log(xhr.status);
            },
            complete: function () {
                $('#loading_fetch_tag').addClass('hide');
            }
        });
    }

    function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    }

    fetch_tags(getUrlParameter('id'));

    //Show modal add tag
    $(".btn_add_tag").click(function () {
        $("#ModalAddTag").modal("show");
    });

    //Display bottn edit & delete on mouse hover
    $("body").on("mouseover", ".tag_operation", function () {
        var id = $(this).attr("id");
        $("#edit" + id + ", #delete" + id).css({"display": "block"});
    });
    $("body").on("mouseleave", ".tag_operation", function () {
        var id = $(this).attr("id");
        $("#edit" + id + ", #delete" + id).css({"display": "none"});
    });

    // On click button user tab assign tag to user
    $("body").on('click', '.tag_operation', function (e) {
        var this_s = $(this);
        e.preventDefault();
        var ci = $('.customer_id').val();
        var tag_id = $(this).attr('id');
        if( tag_id != '' && ci != '' ){
            if ($(this).hasClass('tag_operation_is_assign')) {

                $.ajax({
                    url: URL + "customers/index",
                    data: "action=remove_tag_from_user&customer_id=" + ci + "&tag_id=" + tag_id,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function () {
                        $("#loading_fetch_tag").removeClass("hide");
                    },
                    success: function (respond) {
                        this_s.removeClass('tag_operation_is_assign');
                        //$(this).addClass('');
                        //alert(respond['msg']);
                    },
                    error: function (xhr, ajaxOption, throwError) {
                        console.log(xhr.status);
                    },
                    complete: function () {
                        $("#loading_fetch_tag").addClass("hide");
                    }
                });
            } else {

                $.ajax({
                    url: URL + "customers/index",
                    data: "action=assign_tag_to_user&customer_id=" + ci + "&tag_id=" + tag_id,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function () {
                        $("#loading_fetch_tag").removeClass("hide");
                    },
                    success: function (respond) {
                        this_s.addClass('tag_operation_is_assign');
                        //alert(respond['msg']);
                    },
                    error: function (xhr, ajaxOption, throwError) {
                        console.log(xhr.status);
                    },
                    complete: function () {
                        $("#loading_fetch_tag").addClass("hide");
                    }
                });
            }
        }
    });

    //Add new tag
    $("body").on("click", "#btn_add_tag", function (e) {
        e.preventDefault();
        var data = $("#form_add_tag").serialize();
        $.ajax({
            url: URL + "customers/index",
            data: data + "&action=add_tag",
            type: "get",
            dataType: "json",
            beforeSend: function () {
                $("#save_loading").removeClass("hide");
            },
            success: function (respond) {
                if (respond.result === "success") {
                    $("#form_add_tag")[0].reset();
                    $("#ModalAddTag").modal("hide");
                    fetch_tags();
                }
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log("Error:" + xhr.status);
            },
            complete: function () {
                $("#save_loading").addClass("hide");
            }
        });
    });

    //Delete tag
    $("body").on("click", "#btn_delete_tag", function (e) {
        e.preventDefault();
        var data = $("#delete_tag").serialize();
        $.ajax({
            url: URL + "customers/index",
            data: data + "&action=delete_tag&",
            dataType: "json",
            beforeSend: function () {
                $("#delete_loading").removeClass("hide");
            },
            success: function (respond) {
                if (respond.result === "success") {
                    $("#ModalDeleteTag").modal("hide");
                }
                fetch_tags();
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log("Error:" + xhr.status);
            },
            complete: function () {
                $("#delete_loading").addClass("hide");
            }
        });
    });

    //Edit tag
    $("body").on("click", "#btn_edit_tag", function (e) {
        e.preventDefault();
        var data = $("#form_edit_tag").serialize();
        $.ajax({
            url: URL + "customers/index",
            data: data + "&action=edit_tag",
            dataType: "json",
            beforeSend: function () {
                $("#edit_tag_loading").removeClass("hide");
            },
            success: function (respond) {
                if (respond.result === "success") {
                    $("#ModalEditTag").modal("hide");
                }
                fetch_tags();
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log("Error:" + xhr.status);
            },
            complete: function () {
                $("#edit_tag_loading").addClass("hide");
            }
        });
    });

    // Send Mail
    $(".btn_send_modelid").click(function (e) {
        e.preventDefault();

        if (undefined !== $('#txt_lastname_kana').attr('disabled')) {
            alert('編集ボタンを押してください。');
            return false;
        }

        var ci = $('.customer_id').val();
        var email = $("#txt_email").val();
        var model_change = $("#txt_model_id_chage").val();
        $.ajax({
            url: URL + "customers/index",
            data: "&action=send_mail&emai=" + email + "&model_change=" + model_change + "&ci=" + ci,
            type: "get",
            dataType: "json",
            beforeSend: function () {
                $(".save_customer_info").removeClass("hide");
            },
            success: function (respond) {
                if (respond) {
                    get_customer_info(ci);
                }
            },
            error: function (xhr, ajaxOptions, throwError) {
                console.log("Error:" + xhr.status);
            },
            complete: function () {
                $(".save_customer_info").addClass("hide");
                $(".disable").prop('disabled', true);
                $("#btn_save").html("編集");
            }
        });
    });

    //Get user information after click on record page
    var user_id = document.URL.split("id=")[1] ? document.URL.split("id=")[1] : 0;
    if (user_id === 0) {
        return false;
    } else {
        $(".customer_id").val(user_id);
        get_customer_info(user_id);
    }

});
    
function isMacintosh() {
    return navigator.platform.indexOf('Mac') > -1;
}