function prevent_leave_page(warning) {
    if (warning) {
        window.onbeforeunload = function () {
            if (warning) {
                return "Your data not yet save, if you leave page your data will lost! ";
            }
        };
    }
    $('form').submit(function () {
        window.onbeforeunload = null;
    });
}

function confirmYesNo(btnClass, msg, placement) {
    var options = {
        placement: placement,
        title: '',
        html: 'true',
        content: '<p class="confirm-text">' + msg + '</p>' + '<div class="text-center"><button class="btn btn-default confirm-no" type="button">いいえ</button><button class="btn btn-default confirm-yes" type="button">はい</button></div>'
    };
    
    // Delete button popover confirmation
    $('.' + btnClass).popover(options);
}