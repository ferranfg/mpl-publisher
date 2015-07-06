(function ($) {

	'use strict';

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

		$("[data-toggle=tooltip]").tooltip();
	});

})(window.jQuery);