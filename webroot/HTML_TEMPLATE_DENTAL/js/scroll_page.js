$(document).ready(function () {
    $('.icon_select').on('change', function () {
        if (this.value == '全て対象') {
            $(".display").css("display", "block");
        }
        else {
            $(".display").css("display", "none");
        }
    });
    $("#date-picker").datepicker({dateFormat: 'M. dd, yy'});

});