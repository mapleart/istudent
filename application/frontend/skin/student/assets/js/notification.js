var ls = ls ||
    {};

ls.notification = (function ($) {
    this.init = function () {
        jQuery(document).on('click', '[data-toggle="notification"]', function(e){
            var $iId = $(this).data('id');
            if(!$iId) return;

            var $notification = $(this).closest('.role-notification');
            ls.ajax(aRouter['notifications'] + 'ajax/delete/', {id:$iId}, function (data) {
                if (data.bStateError) {
                    ls.msg.error(data.sMsgTitle, data.sMsg);
                } else {
                    $notification.fadeOut('fast', function () {
                        $notification.remove();
                    });
                    $('.js-notificationCount').text(data.count);
                    //  ls.msg.notice(data.sMsgTitle, data.sMsg);
                }
            });
            return false;
        });
    };

    this.deleteAll = function () {
        ls.ajax(aRouter['notifications'] + 'ajax/delete_all/', {}, function (data) {
            if (data.bStateError) {
                ls.msg.error(data.sMsgTitle, data.sMsg);
            } else {
                location.reload();
            }
        });
        return false;
    };

    return this;
}).call(ls.notification ||
    {}, jQuery);

jQuery(document).ready(function () {
    ls.notification.init();
});