(function ($) {

	'use strict';

	$(document).ready(function () {

		$('#chapter-list').sortable();

		$('#upload-btn').on('click', function(e) {
			// wp media object
			var image = window.wp.media().open().on('select', function() {
				var selected = image.state().get('selection').first();

				$('#book-cover').val(selected.get('url'));
				$('#book-cover-placeholder').attr('src', selected.get('url'));
			});

			e.preventDefault();
		});
	});

})(window.jQuery);