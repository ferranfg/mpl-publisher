=== MPL - Publisher ===
Contributors: ferranfg
Donate link: http://ferranfigueredo.com/
Tags: ebook, epub, book, pdf, kindle, mobi, ibook, publish, writer, selfpub, self-publish, author
Requires at least: 3.5.0
Tested up to: 4.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

MPL - Publisher is a plugin to create an ebook from your WordPress posts. You can publish your ebook like: ePub, pdf, kindle books, iPad ebook, Mobi

== Description ==

MPL - Publisher is a plugin to create an ebook from your WordPress posts. The plugin’s main purpose is to help writers solving the *how to create an ebook* problem the simplest possible way, easing the process of converting your ebook to ePub, pdf, kindle books, Mobi... etc.

The plugin is still in an early development stage (check the features section and roadmap to future releases) and open to any comments, bugs or issues you may have.

= Features =

For now, these are the current features:

- Select chapters (posts) to include in your eBook.
- Set basic information about your book: Title, Description, Authors, ISBN, Publisher, Book Cover.
- Download your eBook as EPUB2.0 or EPUB3.0
- Basic filter and sort your chapters individually.

= Roadmap =

Future releases will include, at least, the next functionalities:

- Multiple output formats (PDF Print-Ready pending).
- Advanced search with complex filters to improve the chapter selection.
- Add extra and edit current content
- Multiple design selection

= Requirements =

- PHP 5.4.0 or higher
- WordPress 3.5.0 or higher

For further information, visit the [MPL-Publisher plugin's homepage](https://ferranfigueredo.com/mpl-publisher/)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the uncompressed folder `mpl-publisher` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Tools - Publisher in your admin menu

== Frequently Asked Questions ==

= I'm getting an error! =

If you see something like:

`Parse error: syntax error, unexpected '[' in ../wp-content/plugins/mpl-publisher/vendor/illuminate/support/helpers.php on line 365`

This errors is related with the installed PHP version on your server. Please, make sure you follow the [recommended WordPress requirements](https://wordpress.org/about/requirements/)

If you have any other error, please use the [MPL - Publisher support forum](https://wordpress.org/support/plugin/mpl-publisher)

= Which output formats are supported? =

Currently we only support EPUB2.0 and EPUB3.0. You can choose it from the Output format's select menu.

= What about other formats? =

In future releases we will add support to other formats like PDF. Keep updated.

== Screenshots ==

1. Default screen
2. Android Amazon Kindle
3. iPad iBooks
4. iPad Amazon Kindle

== Changelog ==

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

= 1.7.0 =
- Add book publication's date field and editable language field