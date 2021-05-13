(function ($) {

    'use strict';

    window.HW_config = {
        selector: ".release-notes",
        account: "7NBro7"
    };

    $(document).ready(function () {
        var $mpl = $('#mpl-wrapper');

        // Disable on other pages
        if ($mpl.length == 0) return;

        $('.chosen').chosen();

        $('#chapter-list').sortable();

        $('#upload-btn').on('click', function(e) {
            // wp media object
            var image = window.wp.media().open().on('select', function() {
                var selected = image.state().get('selection').first();
                // hidden field
                $('#book-cover').val(selected.get('id'));
                // image next button
                $('#book-cover-placeholder').attr('src', selected.get('url'));
            });

            e.preventDefault();
        });

        $('[data-toggle=tooltip]').tooltip();

        $('.nav-tab-select').on('change', function () {
            // Triggers the original tab change
            $('.nav-tab-wrapper li a').eq($(this).val()).tab('show');
        });

        $('.nav-book-select').on('change', function () {
            // Redirects to book page
            window.location = $(this).val();
        });

        var $themes = $('[data-toggle="book-theme"]');

        // On click updates hidden field
        $themes.on('click', function () {
            $themes.removeClass('active');
            // Mark current theme as selected
            var $el = $(this).addClass('active');
            // Updates hidden field
            $('input[name="theme_id"]').val($el.data('theme-id'));
        });

        if (typeof twemoji === 'object') {
            twemoji.parse($mpl.get(0));
        }

        $('.generate-button').on('click', function (e) {
            var width = Math.min(window.innerWidth - 40, 750);
            var height = Math.min(window.innerHeight - 180, 600);
            var isChrome = /chrome/i.test(navigator.userAgent);
            var iframe = $mpl.data('thickbox-url');
            var title = 'Almost ready 📚 Your book is being downloaded…';

            if (isChrome) {
                tb_show(title, iframe + '&TB_iframe=true&width=' + width + '&height=' + height);
            } else {
                var w = window.open(iframe, '', 'width=' + width + ',height=' + height);
                w.document.title = title;
            }
        });

        $('#marketplace-iframe').on('load', function () {
            $(this).iFrameResize();
        });
    });

})(window.jQuery);