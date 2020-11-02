=== MPL - Publisher ===
Contributors: ferranfg
Donate link: https://ferranfigueredo.com/
Tags: ebook, epub, book, pdf, kindle, mobi, ibook, publish, writer, selfpub, self-publish, author
Requires at least: 3.5.0
Tested up to: 5.5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

MPL - Publisher is a plugin to create an ebook from your WordPress posts. You can publish your ebook like: ePub, pdf, kindle books, iPad ebook, Mobi

== Description ==

MPL - Publisher is a plugin to create an ebook from your WordPress posts. The plugin’s main purpose is to help writers solving the *how to create an ebook* problem the simplest possible way, easing the process of converting your ebook to ePub, pdf, kindle books, Mobi... etc.

The plugin is still in an early development stage (check the features section and roadmap to future releases) and open to any comments, bugs or issues you may have. Use the [MPL-Publisher Support Forum](https://wordpress.org/support/plugin/mpl-publisher)

= Features =

For now, these are the current features:

- Select chapters (posts) to include in your eBook.
- Set basic information about your book: Title, Description, Authors, ISBN, Publisher, Book Cover.
- Download your eBook as EPUB2.0, EPUB3.0, Mobi, Markdown or Microsoft Word (docx)
- Add a widget to your sidebar to promote your book within your readers
- Promote your book using the shortcode `[mpl]` and their available options
- Basic filter and sort your chapters individually.
- Add aditional book chapters and edit current content
- Include your custom CSS styles into your books

= Roadmap =

Future releases will include, at least, the next functionalities:

- Multiple output formats (PDF Print-Ready and Mobi pending).
- Advanced search with complex filters to improve the chapter selection.
- Multiple design selection

= Requirements =

- PHP 5.4.0 or higher
- WordPress 3.5.0 or higher

For further information, visit the [MPL-Publisher plugin's homepage](https://mpl-publisher.ferranfigueredo.com/)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the uncompressed folder `mpl-publisher` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Tools - Publisher in your admin menu

To include a shortcode in your pages or posts

1. Add the `[mpl]` shortcode into 
2. Available options are `[mpl download=true]` to include a download button and `[mpl external=true]` to include your book's external links
3. Modify your shortcode's title usign `[mpl]Download my book[/mpl]`

== Frequently Asked Questions ==

= I'm getting an error! =

If you see something like:

`Parse error: syntax error, unexpected '[' in ../wp-content/plugins/mpl-publisher/vendor/illuminate/support/helpers.php on line 365`

This errors is related with the installed PHP version on your server. Please, make sure you follow the [recommended WordPress requirements](https://wordpress.org/about/requirements/)

If you have any other error, please use the [MPL - Publisher support forum](https://wordpress.org/support/plugin/mpl-publisher)

= Which output formats are supported? =

Currently we only support EPUB2.0, EPUB3.0 and Microsoft Word (docx). You can choose it from the Output format's select menu.

= What about other formats? =

In future releases we will add support to other formats like PDF. Keep updated.

== Screenshots ==

1. Default screen
2. Sidebar widget and shortcode
3. iPad iBooks
4. iPad Amazon Kindle
5. Android Amazon Kindle

== Changelog ==

= 1.14.0 =
- Allow filter posts by year (https://wordpress.org/support/topic/filter-posts-by-yearmonth/)
- Allow multiple themes loading and selection
- Added hooks for plugin extensions (pending documentation)
- Fix Markdown generation to include CSS files and fonts

= 1.13.0 =
- Added MOBI as output format (Basic field, not ready to production)
- Fix [Draft Book Posts?](https://wordpress.org/support/topic/draft-book-posts?replies=1) issue
- Fix "Read More" issue (Posts where printing only excerpts on the genereated eBook)
- Fix plugin page navbar and table styles
- Update plugin dependencies

= 1.12.0 =
- Added Microsoft Word (docx) as output format
- Custom CSS textarea under the Appearance tab

= 1.11.0 =
- Add MPL-Download eBook widget to promote your book with your readers
- Shortcode to include your MPL-Download eBook using `[mpl]`
- Added a "Links" tab to include related pages with your book, like Amazon or iBooks links
- Improved tab navigation on responsive mobile
- Fix language load_plugin_textdomain path

= 1.10.0 =
- Added Serbian translation. Thanks to Andrijana from [Web Hosting Geeks](http://webhostinggeeks.com/)
- Added Appearance's tab to preview and choose your book design
- New filter with content type selection
- Download book as a zip file with chapters in markdown format
- Fix automatic line breaks into HTML paragraphs

= 1.9.0 =
- Added French translation. Thanks to [zebulong](https://profiles.wordpress.org/zebulong)
- You can add specific content as a chapter to your book without needing to be an existing published post
- Added Copyright information field about your book
- Added default CSS classes to book.css to ensure styles on images once the book is published
- Added multiple tooltips to increase information about how the plugin works
- Fix CSS mpl-pubisher.css namespace
- Fix local timestamp on save information
- Fix saved image source

= 1.8.0 =
- Now you can save your changes for future publications
- The chapter's list shows also your private posts
- Review plugin's description and tags to improve visibility
- Fix mobile/responsive button display

= 1.7.0 =
- Improved admin's page navigation using tabs
- Add helping blocks to better understanding
- Add book publication's date field and editable language field
- Bug printing post's edit link

= 1.6.0 =
- Added authors filtering to chapter's selection
- Added tag filtering to chapter's selection
- Improve readme.txt description to provide server requirements information
- Pending 1.5.0 spanish and catalan translations

= 1.5.0 =
- Add Book Description
- Sets Blog Language as Book Language

= 1.4.4 =
- Fix "class not found" error

= 1.4.0 =
- Chapter's selection filtered by posts categories
- Fix HTTPS image request

= 1.3.0 =
- Added EPUB3.0 as output format
- Upload a Book Cover
- Added meta information about the generator
- Corrected Spanish and Catalan translations

= 1.2.0 =
- New default book style
- Added Spanish and Catalan translation
- Replace Twig with illuminate/view as view engine
- Fix duplicate ID chapter

= 1.1.0 =
- Sort individual chapters manually

= 1.0.0 =
- Initial release.

== Upgrade Notice ==

= 1.14.0 =
- Added MOBI as output format and multiple themes to be added!