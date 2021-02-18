(function ($) {

    'use strict';

    window.HW_config = {
        selector: ".release-notes",
        account: "7NBro7"
    };

    $(document).ready(function () {

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

        $('#book-amazon').on('change', function () {
            // If not empty text field
            if ($.trim($(this).val()) !== '') {
                $('#affiliate-form-field').slideDown();
            } else {
                $('#affiliate-form-field').slideUp();
            }
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

        twemoji.parse($('#mpl-wrapper').get(0));
    });

})(window.jQuery);