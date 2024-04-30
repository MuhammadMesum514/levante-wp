;(function ($) {
    $(document).ready(function () {
        $(".homlisti-app-notice .notice-dismiss").on("click", function () {
            $.post(HomlistiAdminObj.ajaxUrl, {
                action: "homlist_app_notice_dismiss",
                dismiss: 1,
                nonce: HomlistiAdminObj.nonce
            }, function (data) {

            });
        });

    });
})(jQuery);