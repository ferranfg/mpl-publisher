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

        $mpl.areYouSure();

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
            var $el = $(this);

            if ($el.data('theme-slug').startsWith('premium') && ! $mpl.data('is-premium')) {
                alert($mpl.data('alert-premium'));
            } else {
                $themes.removeClass('active');
                $el.addClass('active');

                // Updates hidden field
                $('input[name="theme_id"]').val($el.data('theme-id'));
            }
        });

        if (typeof twemoji === 'object') {
            twemoji.parse($mpl.get(0));
        }

        if (typeof introJs === 'function' && window.localStorage) {
            var mplIntroJs = introJs();

            mplIntroJs.oncomplete(function() {
                localStorage.setItem('mpl_introjs_v1', true);
            });

            mplIntroJs.onexit(function() {
                localStorage.setItem('mpl_introjs_v1', true);
            });

            if (localStorage.getItem('mpl_introjs_v1') === null) {
                mplIntroJs.start();
            }

            $('#mpl-introjs').on('click', function(e) {
                e.preventDefault();
                mplIntroJs.start();
            });
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

        var $editorContainer = $('#tui-image-editor-container');

        if ($editorContainer.length && typeof tui === 'object') {
            var height = Math.min(window.innerHeight - 180, 650);

            if ( ! $mpl.data('is-premium')) {
                var coverPremium = introJs().setOptions({
                    showButtons: false,
                    showBullets: false,
                    steps: [{
                        title: $mpl.data('title-premium'),
                        intro: $mpl.data('alert-premium'),
                    }]
                }).start();

                coverPremium.onexit(function() {
                    window.history.back();
                });
            }

            var imageEditor = new tui.ImageEditor('#tui-image-editor-container', {
                includeUI: {
                    loadImage: {
                        path: $mpl.data('base-url') + './assets/imgs/cover-placeholder.png',
                        name: 'SampleImage',
                    },
                    theme: {
                        'common.bi.image': $mpl.data('base-url') + './assets/imgs/mpl-logo-60x60.png',
                        'common.bisize.width': '26px',
                        'common.bisize.height': '26px',
                        'common.backgroundColor': '#ffff',
                        'common.border': '0px',
                        // Download
                        'downloadButton.backgroundColor': '#EE4035',
                        'downloadButton.border': '1px solid #EE4035',
                        // Submenu
                        'submenu.backgroundColor': '#1d2327',
                    },
                    menuBarPosition: 'bottom',
                    uiSize: {
                        height: height
                    }
                },
                cssMaxWidth: 312,
                cssMaxHeight: 500,
                usageStatistics: false,
            });

            window.onresize = function () {
                imageEditor.ui.resizeEditor();
            };
        }
    });

})(window.jQuery);