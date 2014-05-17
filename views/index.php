<div class="wrap">

	<h2>Publisher</h2>

	<form id="col-container" action="" method="POST">
		<div id="col-right">
			<div class="col-wrap">
				<table class="wp-list-table widefat fixed posts">
					<thead>
						<tr>
							<th class="manage-column column-cb check-column">
								<input id="cb-select-all-1" type="checkbox" checked="checked">
							</th>
							<th class="manage-column column-name sortable desc">
								<a href="">Capítulo</a>
							</th>
						</tr>
					</thead>
					<tbody>
						{% for post in posts %}
							<tr>
								<th scope="row" class="check-column">
									<input type="checkbox" name="selected_posts[]" value="{{ post.ID }}" id="cb-select-{{ post.ID }}"  checked="checked">
								</th>
								<td class="name column-name">
									<strong><a href="">{{ post.post_title }}</a></strong>
								</td>
							</tr>
						{% endfor %}
					</tbody>
					<tfoot>
						<tr>
							<th class="manage-column column-cb check-column">
								<input id="cb-select-all-1" type="checkbox" checked="checked">
							</th>
							<th class="manage-column column-name sortable desc">
								<a href="">Capítulo</a>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<div id="col-left">
			<div class="col-wrap">
				<div class="form-wrap">
					<h3>Información del libro</h3>

					<div class="form-field">
						<label for="book-identifier">Identificador (ISBN)</label>
						<input name="identifier" id="book-identifier" type="text" value="" placeholder="ej: 9788494138805 E">
						<p>Si no posees un ISBN, este valor puede ser un identificador único.</p>
					</div>

					<div class="form-field">
						<label for="book-title">Título</label>
						<input name="title" id="book-title" type="text" value="{{ site_name }}" placeholder="Título del libro">
					</div>					

					<div class="form-field">
						<label for="book-authors">Autores del libro</label>
						<input name="authors" id="book-authors" type="text" value="{{ current_user.display_name }}" placeholder="Autores del libro">
						<p>En caso de varios autores, separa sus nombres por comas.</p>
					</div>

					<div class="form-field">
						<label for="book-editor">Editor (Opcional)</label>
						<input name="editor" id="book-editor" type="text" value="">
					</div>

					<div class="form-field">
						<label for="format">Formato de salida</label>
						<select name="format" class="postform">
							<option value="EpubPublisher">EPUB 2.0</option>
						</select>
						<p>Escoge el formato con el que se publicará tu libro.</p>
					</div>

					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="Publicar">
					</p>
				</div>
			</div>
		</div>
	</form>
</div>