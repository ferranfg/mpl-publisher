<div class="wrap">

	<h2>{{__("Publisher")}}</h2>

	<hr />

	<form id="col-container" action="{{action}}" method="POST">

		<input type="hidden" name="action" value="publish_ebook">

		{{ wp_nonce_field | raw }}

		<div id="col-right">
			<div class="col-wrap">

				<h3>{{__("Contents")}}</h3>

				<table class="wp-list-table widefat fixed posts" style="background-color:rgba(255, 255, 255, 0.6)">
					<thead>
						<tr>
							<th class="manage-column column-cb check-column">
								<input id="cb-select-all-1" type="checkbox" checked="checked">
							</th>
							<th class="manage-column column-name">{{__("Chapter")}}</th>
						</tr>
					</thead>
					<tbody>
						{% for post in posts %}
							<tr>
								<th scope="row" class="check-column">
									<input type="checkbox" name="selected_posts[]" value="{{ post.ID }}" id="cb-select-{{ post.ID }}"  checked="checked">
								</th>
								<td class="name column-name">
									<strong><a href="{{ post.guid }}" target="_blank">{{ post.post_title }}</a></strong>
								</td>
							</tr>
						{% endfor %}
					</tbody>
					<tfoot>
						<tr>
							<th class="manage-column column-cb check-column">
								<input id="cb-select-all-2" type="checkbox" checked="checked">
							</th>
							<th class="manage-column column-name">{{__("Chapter")}}</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<div id="col-left">
			<div class="col-wrap">
				<div class="form-wrap">
					<h3>{{__("General details")}}</h3>

					<div class="form-field">
						<label for="book-identifier">{{__("Identifier (ISBN)")}}</label>
						<input name="identifier" id="book-identifier" type="text" value="" placeholder="ej: 9788494138805 E">
						<p>{{__("If your book doesn't have an ISBN, use a unique identifier")}}</p>
					</div>

					<div class="form-field">
						<label for="book-title">{{__("Book Title")}}</label>
						<input name="title" id="book-title" type="text" value="{{ site_name }}" placeholder="{{__('Book title')}}">
					</div>					

					<div class="form-field">
						<label for="book-authors">{{__("Book authors")}}</label>
						<input name="authors" id="book-authors" type="text" value="{{ current_user.display_name }}" placeholder="{{__('Book authors')}}">
						<p>{{__("Separate multiple authors with commas")}}</p>
					</div>

					<div class="form-field">
						<label for="book-editor">{{__("Publisher Name (Optional)")}}</label>
						<input name="editor" id="book-editor" type="text" value="" placeholder="{{__('Publisher Name (Optional)')}}">
					</div>

					<div class="form-field">
						<label for="format">{{__("Output format")}}</label>
						<select name="format" class="postform">
							<option value="EpubPublisher">EPUB 2.0</option>
						</select>
						<p>{{__("Currently, only EPUB 2.0 is available. Future versions will include Mobi, PDF, etc.")}}</p>
					</div>

					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="{{__('Publish')}}">
					</p>
				</div>
			</div>
		</div>
	</form>
</div>