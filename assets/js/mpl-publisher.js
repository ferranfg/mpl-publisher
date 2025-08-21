(function ($) {

    'use strict';

    $(document).ready(function () {
        var $mpl = $('#mpl-wrapper');

        // Disable on other pages
        if ($mpl.length == 0) return;

        $mpl.areYouSure();

        $('.chosen').chosen();

        $('#chapter-list').sortable({
            handle: '.chapter-handle',
            items: 'tr',
            helper: function(e, item) {
                if (!item.hasClass('ui-selected')) {
                    item.addClass('ui-selected').siblings().removeClass('ui-selected');
                }

                var $selectedRows = $('#chapter-list tr.ui-selected');
                var $helperContainer = $('<div/>');
                var $helperTable = $('<table>').addClass(item.closest('table').attr('class'));

                $selectedRows.each(function() {
                    $(this).clone().appendTo($helperTable);
                });

                item.data('multidrag_other', $selectedRows.not(item));

                return $helperContainer.append($helperTable);
            },
            start: function(e, ui) {
                var $otherSelectedRows = ui.item.data('multidrag_other');

                if ($otherSelectedRows && $otherSelectedRows.length > 0) {
                    $otherSelectedRows.hide();
                }
            },
            stop: function(e, ui) {
                var $otherSelectedRows = ui.item.data('multidrag_other');

                if ($otherSelectedRows && $otherSelectedRows.length > 0) {
                    var $currentItem = ui.item;

                    $otherSelectedRows.each(function() {
                        var $this = $(this);
                        $this.insertAfter($currentItem);
                        $this.show();
                        $currentItem = $this;
                    });
                }

                ui.item.removeData('multidrag_other');
            }
        });

        // Make the chapter list selectable
        $("#chapter-list").selectable({
            filter: 'tr',
        });

        // Prevent click on checkbox from triggering selectable
        $('#chapter-list input[type="checkbox"]').on('click', function(e) {
            e.stopPropagation();
        });

        // Prevent click on links in row-actions from triggering selectable
        $('#chapter-list .row-actions a').on('click', function(e) {
            e.stopPropagation();
        });

        $('#upload-btn').on('click', function(e) {
            // wp media object
            var image = window.wp.media().open().on('select', function() {
                var selected = image.state().get('selection').first();
                // hidden field
                $('#book-cover-id').val(selected.get('id'));
                // image next button
                $('#book-cover-placeholder').attr('src', selected.get('url'));
            });

            e.preventDefault();
        });

        $('[data-toggle=tooltip]').tooltip();

        $('[data-toggle=change-tab]').on('click', function(e) {
            var href = $(this).attr('href');
            // Triggers the original tab change
            $('.nav-tab-wrapper').find('a[href=' + href + ']').tab('show');

            e.preventDefault();
        });

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
                localStorage.setItem('mpl_introjs_v2', true);
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            });

            mplIntroJs.onexit(function() {
                localStorage.setItem('mpl_introjs_v2', true);
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            });

            if (localStorage.getItem('mpl_introjs_v2') === null) {
                mplIntroJs.start();
            }

            $('#mpl-introjs').on('click', function(e) {
                e.preventDefault();
                mplIntroJs.start();
            });
        }

        $('.generate-button').on('click', function (e) {
            // Ad free experience for premium users
            if ($mpl.data('is-premium')) return;

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

        $('.mpl-duplicate-post').on( 'click', function( e ) {
            e.preventDefault();
            var $spinner = $(this).next('.spinner');
            $spinner.css('visibility', 'visible');

            // Create the data to pass
            var data = {
                action: 'mpl_duplicate_post',
                original_id: $(this).data('post-id'),
                security: $(this).attr('rel')
            };

            $.post(ajaxurl, data, function(response) {
                window.location.href = window.location.href + '&mpl_duplicated_id=' + response;
            });
        });
    });

})(window.jQuery);