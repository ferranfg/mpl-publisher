=== MPL-Publisher — Ebook & Audiobook Creator ===
Contributors: ferranfg
Donate link: https://wordpress.mpl-publisher.com/
Tags: ebook, audiobook, epub, kindle, docx
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 7.1.8
Stable tag: 2.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

MPL-Publisher 📚 creates an ebook, print-ready PDF book, EPUB for KDP, or Audiobook MP3 converting your WordPress posts.

== Description ==

MPL-Publisher 📚 creates an ebook, print-ready PDF book, EPUB for KDP, or Audiobook MP3 converting your WordPress posts.

If you are looking for a WordPress ebook generator or you are a self-publishing author ✍️, it will solve the "how to publish my digital book" problem, doing it in the simplest possible way 👌. Convert your posts to book, posts to ebook (EPUB compatible with Amazon Kindle Direct Publishing KDP), posts to PDF print-ready, posts to MS Word (Docx), posts to Audiobook (MP3)… etc.

Writing a successful book is a challenge by itself and self-publishing an ebook can be pretty painful without the right tools. But with Kindle Direct Publishing (Amazon's self-publishing platform), and other platforms of self-publishing, self-publishing a book, ebook, or audiobook can even be easy.

It is free to self-publish on the most popular book and ebook platforms, like Amazon Kindle Direct Publishing (KDP), NOOK Press, or KOBO. You just need a formatted ebook file to load to the self-publishing platforms. This is your completed, edited manuscript in specific formats: .epub for Amazon Kindle Direct Publishing (KDP) and other stores, or PDF for print-ready books.

With our plugin, you can create an ebook from your WordPress posts in a matter of seconds. If you already have a WordPress site, you are ready to start selling online your self-published books.

The plugin is full of features (check the features section and roadmap to future releases) and open to any comments, bugs, or issues you may have. Use the [MPL-Publisher Support Forum](https://wordpress.org/support/plugin/mpl-publisher/).

= Features =

For now, these are the current features:

- Convert your WordPress blog to unlimited ebooks per site with unlimited exports per ebook.
- Included advanced cover editor (only available for premium).
- Select individual chapters (posts, pages, and "secret chapters") to include in your ebook.
- Set basic information about your ebook: Title, Description, Authors, ISBN, Publisher and Book Cover.
- Create your ebook as EPUB 2.0 or EPUB 3.0, Markdown, MS Word (Docx), print-ready PDF, HTML for Kindle Direct Publishing, and Audiobook (mp3).
- Add a widget to your sidebar to promote your ebook to your readers.
- Promote your ebook using the shortcode [mpl] and their available options.
- Basic filter and sort your chapters individually.
- Add additional ebook chapters and edit current content.
- Include your custom CSS styles in your ebooks.

= Roadmap =

Future releases will include, at least, the next functionalities:

- Multiple professional designs are available.
- Sell directly your ebook to your audience.

= Other Plugins Compatibility =

We believe in WordPress as the tool for creating the web and, for this reason, MPL-Publisher is compatible with other plugin's custom post types. This will allow you to create books, ebooks and audiobooks without changing your current tools.

Some of these plugins have been tested by ourselves and guarantee proper compatibility. This list includes:

- [weDocs](https://wordpress.org/plugins/wedocs/) - Create great-looking documentation for your products. Organize your product documentation on your site, beautifully!
- [Echo Knowledge Base](https://wordpress.org/plugins/echo-knowledge-base/) - Instantly create an online self-service Knowledge Base for your users, customers, and employees.

Also, there is a fantastic community of plugins available if MPL-Publisher doesn't meet your criteria:

- [Print My Blog](https://wordpress.org/plugins/print-my-blog/) - Make printing your blog easy and impressive. For you & your visitors. One post or thousands.
- [DK PDF](https://wordpress.org/plugins/dk-pdf/) - DK PDF allows site visitors to convert posts and pages to PDF using a button.
- [Anthologize](https://wordpress.org/plugins/search/anthologize/) - Use the power of WordPress to transform your content into an ebook.

= Requirements =

- PHP 7.1.8 or higher
- WordPress 5.0 or higher

For further information, visit the [MPL-Publisher plugin's homepage](https://wordpress.mpl-publisher.com/).

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the uncompressed folder `mpl-publisher` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to MPL-Publisher in your admin menu

To include a shortcode in your pages or posts

1. Add the `[mpl]` shortcode into 
2. Available options are `[mpl download=true]` to include a download button and `[mpl external=true]` to include your ebook's external links
3. Modify your shortcode's title using `[mpl]Download My Book[/mpl]`

== Frequently Asked Questions ==

= I'm getting an error! =

If you see something like:

`Parse error: syntax error, unexpected '[' in ../wp-content/plugins/mpl-publisher/vendor/illuminate/support/helpers.php on line 365`

This error is related to the installed PHP version on your server. Please, make sure you follow the [recommended WordPress requirements](https://wordpress.org/about/requirements/)

If you have any other errors, please use the [MPL-Publisher support forum](https://wordpress.org/support/plugin/mpl-publisher)

= Which output formats are supported? =

Currently, we support EPUB 2.0 and EPUB 3.0, MS Word (Docx), Markdown, print-ready PDF, HTML for Kindle Direct Publishing, and Audiobook (mp3). You can choose it from the Output format's select menu.

= What about other formats? =

In future releases, we will add further improvements to our current formats. Keep updated.

== Screenshots ==

1. Default screen
2. Sidebar widget and shortcode
3. iPad iBooks
4. iPad Amazon Kindle
5. Android Amazon Kindle

== Changelog ==

= 2.4.1 =
- Remove Guzzle HTTP Client and replace with `wp_remote_post`

= 2.4.0 =
- Post featured image can be included in corresponding ebook chapter.
- Fix: Image alt attribute was removed and caused a validation error
- Minor readme.txt improvements

= 2.3.2 =
- Clean DIVI shortcodes. Fix: https://wordpress.org/support/topic/tags-divi-theme/
- Add support for WordPress 6.0
- Update readme.txt description

= 2.3.0 =
- Add "Book Subtitle" field and compatible with all output formats
- Update tabs disposition and move "ISBN" field to "Meta" tab
- Tested up to PHP 8.1

= 2.2.0 =
- Added content validation before creating an ebook. Validating content before ebook creation ensures your ebook will contain no HTML errors and will be compatible with all EPUB checkers.
- Change README tags, descriptions, and content

= 2.1.0 =
- Word: Improve TOC generation: link to titles and the page number.
- Word: Add ebook title and Author after cover image.

= 2.0.0 =
- New admin page design
- Removed MOBI from supported formats as it's outdated
- Improved UX on responsive, filters, and tutorial intro

= 1.34.1 =
- Fix error when custom post type is not available anymore

= 1.34.0 =
- Add support for more post types other than post, page, and ebook chapter
- Testing compatibility with other knowledge base plugins
- Retesting for WordPress 5.9 and PHP 8.0

= 1.33.0 =
- Decrease posts limitation to 50 results to improve performance
- Allow Premium users to edit max results per ebook
- Non-premium users can create a PDF and MP3 sample
- MPL-Publisher JSON format is now available for all users

= 1.32.1 =
- Apply "the_content" filter to mimic content displayed
- Fix "Apostrophe in description is repeatedly escaped"
- Remove banners and ads on the premium version

= 1.32.0 =
- Action to duplicate your posts as chapters and avoid editing original content
- Added JSON format to improve cross-compatibility with MPL-Publisher tools
- Removed Markdown format as it was mostly unused and unknown
- Update Spanish, French and Catalan translations + plugin readme description
- Increase tested up to WordPress 5.9

= 1.31.0 =
- Change UX on chapters list to look similar to MPL v2
- Remove get_authors and get_tags queries as they were unused
- Audiobook format now allows up to 20k words

= 1.30.4 =
- Update following plugin recommendations

= 1.30.3 =
- Validate, Sanitize and Escape User Data

= 1.30.2 =
- Increase posts results number limitation
- Update resources for self-publishers
- Fix cleaning options on plugin deactivation

= 1.30.1 =
- Update plugin URLs with brand new MPL-Publisher v2

= 1.30.0 =
- Selected chapters will be kept and ordered on filtered results
- Security risks avoided using Transient API to show admin notices
- Updated Amazon Kindle Direct Publishing (KDP) Mobi generation library
- Fix: Detect premium users on additional resources

= 1.29.2 =
- Fix cover image not being saved appropriately
- Added order option (ASC/DESC) on chapters list

= 1.29.0 =
- Added new cover editor (only for MPL-Publisher premium)
- Fix Word cover alignment and image size
- Change tabs literals and modified content (Links are now part of Metadata)
- Responsive improvements (buttons on XS and text size)

= 1.28.0 =
- Added Plain Text (txt) as a new output format
- Added tutorial/help section explaining main plugin features
- Warning message before leaving the page without saving changes
- Include TOC at the beginning of the document on MS Word (Docx) export
- Support for WordPress 5.8

= 1.27.0 =
- Fix error related to filter button not filtering results
- Downgrade min WordPress version to 5.0
- Allow br tags in ebook content
- List chapters even if there are over max results

= 1.26.0 =
- Added 2 new premium themes: Future and Romance ⭐
- Enable Gutenberg editor for ebook chapter post type

= 1.25.0 =
- From now on, you can manage multiples ebooks for the same site
- Added action to clear ebook information and start from scratch
- Fix error loading remote placeholder image
- Update PHP version requirements

= 1.24.0 =
- Introducing Marketplace: resources and ideas to help you boost your ebook sales
- Add image styles to keep alignment with WordPress editor
- Remove custom styles on the "Download my Book" widget
- Fix parsing date error on Word file generation
- Fix loading plugin translations and update them

= 1.23.0 =
- Added experimental feature to embed images into ebook contents
- Use `rem` instead of `px` on ebook styles
- Fix error on saving premium license key

= 1.22.0 =
- Tested up to WordPress 5.7 and PHP 8.0 (and bug fixing)
- Optimize database query when loading chapters

= 1.21.0 =
- Added a new ebook theme! Check it out at our "🎨 Appearance" tab
- Premium ⭐ version doesn't require a direct FTP upload anymore
- Better usability messages on saving and error ✅
- Improvements on our print-ready PDF premium formatting 📘

= 1.20.2 =
- Hotfix changes using emoji library

= 1.20.1 =
- Fix MOBI file generation
- Update emojis with open-sourced Tweemojis

= 1.20.0 =
- Updated search filters (filter posts and chapters by month)
- Added Spanish, French, and Catalan translations
- Premium publishers (print-ready PDF and Audiobook) generation improvements

= 1.19.0 =
- Fix EPUB 2.0 and EPUB 3.0 validation errors
- Clean HTML tags from content to prevent design errors
- Change default content sorting to OLDER chapters first
- Premium publishers (print-ready PDF and Audiobook) generation improvements

= 1.18.0 =
- Move menu position outside the "Tools" section
- Limit query results to avoid more than 150 results
- Minor usability improvements on self-publishing actions

= 1.17.1 =
- Fix annoying count error. Thanks [lholfve](https://wordpress.org/support/topic/warning-count-parameter-must-be/)!

= 1.17.0 =
- Added print-ready PDF as output format

= 1.16.0 =
- [MPL-Publisher Premium](https://wordpress.mpl-publisher.com) ⭐ it's available!
- Added Audiobook (mp3) as output format
- Pages can now be attached as ebook chapters
- UX improvements and lots of emojis

= 1.15.1 =
- Fixed error related to PHP min version

= 1.15.0 =
- Update to WordPress 5.5

= 1.14.0 =
- Allow filter posts by year (https://wordpress.org/support/topic/filter-posts-by-yearmonth/)
- Allow multiple themes loading and selection
- Added hooks for plugin extensions (pending documentation)
- Fix Markdown generation to include CSS files and fonts

= 1.13.0 =
- Added MOBI as output format (Basic field, not ready for production)
- Fix [Draft Book Posts?](https://wordpress.org/support/topic/draft-book-posts?replies=1) issue
- Fix "Read More" issue (Posts where printing only excerpts on the created ebook)
- Fix plugin page navbar and table styles
- Update plugin dependencies

= 1.12.0 =
- Added MS Word (Docx) as output format
- Custom CSS text area under the Appearance tab

= 1.11.0 =
- Add MPL-Download Book widget to promote your ebook to your readers
- Shortcode to include your MPL-Download ebook using `[mpl]`
- Added a "Links" tab to include related pages with your ebook, like Amazon or iBooks links
- Improved tab navigation on responsive mobile
- Fix language load_plugin_textdomain path

= 1.10.0 =
- Added Serbian translation. Thanks to Andrijana from [Web Hosting Geeks](http://webhostinggeeks.com/)
- Added Appearance tab to preview and choose your ebook design
- New filter with content type selection
- Create an ebook as a zip file with chapters in markdown format
- Fix automatic line breaks into HTML paragraphs

= 1.9.0 =
- Added French translation. Thanks to [zebulong](https://profiles.wordpress.org/zebulong)
- You can add specific content as a chapter to your ebook without needing to be an existing published post
- Added Copyright information field about your ebook
- Added default CSS classes to ebook.css to ensure styles on images once the ebook is published
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
- Add helping blocks to a better understanding
- Add ebook publication's date field and editable language field
- Bug printing post's edit link

= 1.6.0 =
- Added authors filtering to chapter's selection
- Added tag filtering to chapter's selection
- Improve readme.txt description to provide server requirements information
- Pending 1.5.0 Spanish and Catalan translations

= 1.5.0 =
- Add ebook Description
- Sets Blog Language as ebook Language

= 1.4.4 =
- Fix "Class not found" error

= 1.4.0 =
- Chapter's selection filtered by posts categories
- Fix HTTPS image request

= 1.3.0 =
- Added EPUB 3.0 as output format
- Upload an ebook Cover
- Added meta-information about the generator
- Corrected Spanish and Catalan translations

= 1.2.0 =
- New default ebook style
- Added Spanish and Catalan translation
- Replace Twig with illuminate/view as the view engine
- Fix duplicate ID chapter

= 1.1.0 =
- Sort individual chapters manually

= 1.0.0 =
- Initial release.