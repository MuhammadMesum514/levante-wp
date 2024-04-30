(function ($) {
    "use strict";

    $(window).on('elementor/frontend/init', function () {
        var $select2 = $('select.select2');
        if ($select2.length) {
            $select2.select2({
                theme: 'classic',
                dropdownAutoWidth: true,
                width: '100%',
            });
        }
        $('#elementor-controls').css({"background-color":"red"});
    });

})(jQuery);