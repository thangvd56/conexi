$(document).ready(function () {
    $('#scrollbox1').enscroll({
        verticalTrackClass: 'track1',
        verticalHandleClass: 'handle1',
        drawScrollButtons: true
    });
    $('.scrollbox4').enscroll({
        verticalTrackClass: 'track4',
        verticalHandleClass: 'handle4',
        minScrollbarLength: 28
    });
});