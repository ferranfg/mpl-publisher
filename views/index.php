<div class="about-wrap mpl">
	
	<div class="hero-home">
		<h1 id="mpl-logo">MPL - Publisher</h1>
		<p class="about-text"><?php _e("MPL - Publisher is a plugin to create an ebook from your WordPress posts. You can publish your ebook like: ePub, pdf, kindle books, iPad ebook, Mobi", "publishe"); ?></p>
	</div>

	<div class="headline-feature">
		<h2><?php _e("Here is a list of your books", "publisher"); ?>:</h2>
	</div>

	<div class="theme-browser rendered">

		<div class="themes">

			<?php foreach ($books as $index => $book): $book = new \MPL\Publisher\Book($book['data']); ?>
				<div class="theme">
					<?php if ($book->getImageSrc()): ?>
						<a href="<?php echo admin_url('tools.php?page=publisher&index='.$index); ?>" class="theme-screenshot">
							<img src="<?php echo $book->getImageSrc(); ?>" alt="<?php echo $book->getTitle(); ?>">
						</a>
					<?php else: ?>
						<a href="<?php echo admin_url('tools.php?page=publisher&index='.$index); ?>" class="theme-screenshot blank"></a>
					<?php endif; ?>
					<a href="<?php echo admin_url('tools.php?page=publisher&index='.$index); ?>" class="more-details"><?php _e("Book details", "publisher"); ?></a>
					<h3 class="theme-name"><?php echo $book->getTitle(); ?></h3>
					<div class="theme-actions">
						<a class="button button-primary load-customize hide-if-no-customize" href="<?php echo admin_url('tools.php?page=publisher&index='.$index); ?>"><?php _e("Edit book", "publisher"); ?></a>
					</div>	
				</div>
			<?php endforeach; ?>

			<div class="theme add-new-theme">
				<a href="<?php echo admin_url('tools.php?page=publisher&index='.count($books)); ?>">
					<div class="theme-screenshot"><span></span></div>
					<h3 class="theme-name"><?php _e("Add New Book", "publisher"); ?></h3>
				</a>
			</div>

		</div>
	</div>

	<br class="clear" />

</div>