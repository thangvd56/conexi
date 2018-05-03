//function moveUp(item) {
//    var prev = item.prev();
//    if (prev.length == 0)
//        return;
//    prev.css('z-index', 999).css('position', 'relative').animate({
//        top: item.height()
//    }, 250);
//    item.css('z-index', 1000).css('position', 'relative').animate({
//        top: '-' + prev.height()
//    }, 300, function () {
//        prev.css('z-index', '').css('top', '').css('position', '');
//        item.css('z-index', '').css('top', '').css('position', '');
//        item.insertBefore(prev);
//        sendOrderToServer();
//    });
//}
//function moveDown(item) {
//    var next = item.next();
//    if (next.length == 0)
//        return;
//    next.css('z-index', 999).css('position', 'relative').animate({
//        top: '-' + item.height()
//    }, 250);
//    item.css('z-index', 1000).css('position', 'relative').animate({
//        top: next.height()
//    }, 300, function () {
//        next.css('z-index', '').css('top', '').css('position', '');
//        item.css('z-index', '').css('top', '').css('position', '');
//        item.insertAfter(next);
//        sendOrderToServer();
//    });
//}
function sendOrderToServer() {
    var items = $(".collection").sortable('toArray');
    var itemList = jQuery.grep(items, function (n, i) {
        return (n !== "" && n != null);
    });
    $("#items").html(itemList);
}
$(".collection").sortable({
    items: ".select_img"
});
//$("body").on("click", ".btn-move", function (e) {
//    e.preventDefault();
//    var btn = $(this);
//    var val = btn.val();
//    if (val == 'up')
//        moveUp(btn.parents('.select_img'));
//    else
//        moveDown(btn.parents('.select_img'));
//});
var orderList = jQuery.grep($(".collection").sortable('toArray'), function (n, i) {
    return (n !== "" && n != null);
});
$("#items").html(orderList);
