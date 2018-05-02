
$(document).ready(function () {
    $('#modal').on('show.bs.modal', function () {
        var modal = $(this);
        modal.find('.modal-title').text('パスワード変更');
    });
    $('#modalCheckin').on('show.bs.modal', function () {
        var modal = $(this);
        modal.find('.modal-title').text('XXXをチェックインさせる');
    });
    $('#modalMemo').on('show.bs.modal', function () {
        var modal = $(this);
        modal.find('.modal-title').text('XXXの写真を送信');
    });
    $('#modalReservation').on('show.bs.modal', function () {
        var modal = $(this);
        modal.find('.modal-title').text('XXX様の予約時間を変更します。');
    });
    $('#modalSlide').on('show.bs.modal', function () {
        var modal = $(this);
        modal.find('.modal-title').text('写真を使用する');
    });
    $('#modalSlideForm').on('show.bs.modal', function () {
        var modal = $(this);
        modal.find('.modal-title').text('XXXに写真を送信する XXXのメモ写真を保存する"');
    });
    $('#modalListImages').on('show.bs.modal', function () {
        var modal = $(this);
        modal.find('.modal-title').text('フォトアルバム');
    });
    var curDate = new Date();
    $('#calIcon').datepicker({
        format: "yyyy年M月dd日",
        weekStart: 1,
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', dateChanged);
    function dateChanged(e) {
        $("#dtLabel").text(e.format());
        curDate = e.date;
    }
    $("#calIcon").datepicker("setDate", new Date());
    $(".glyphicon-chevron-left").on("click", function () {
        curDate.setDate(curDate.getDate() - 1);
        $("#calIcon").datepicker("setDate", curDate);
    });
    $(".glyphicon-chevron-right").on("click", function () {
        curDate.setDate(curDate.getDate() + 1);
        $("#calIcon").datepicker("setDate", curDate);
    });
});