=== ShowTime Slideshow ===

Contributors: youtag
Donate link: http://www.youtag.lu/showtime-wp-plugin/
Tags: wordpress, showtime, slideshow, gallery, animation, ken, burns, adobe, flash, images, photo, photos, picture, pictures, plugin, special, FX, slide, 3d, 3d, flip, blur, zoom, animation, photo, show, presentation, diashow, diaporama, smooth, dia
Requires at least: 2.7
Tested up to: 2.8.6
Stable tag: 1.6

This plugin displays all images attached to a post/page as an animated slideshow. 


== Description ==

Displays all pictures attached to a post/page as a slideshow. Simple setup. This plugin requires Adobe Flash, reverts nicely to default WordPress gallery if Flash is not found. Choose among high quality transitions. Ken Burns (pan&zoom) and many other effects. Full Screen mode.

* <a href="http://www.youtag.lu/showtime-wp-plugin/">Usage, description, discussions</a>
* <a href="http://www.youtag.lu/showtime-wordpress-plugin-demo/">Demo</a>


== Changelog ==

= 0.1 =
Released

= 0.5 =
Several minor Bug Fixes

= 0.6 =
Fixed issue with public plugin folder.

= 0.9 =
Fixed bug where Windows/Firefox does not load the player (removed bg transparency)
Performance optimizations
Esthetic changes in the flash core
WP Galleries are now replaced by Showtime slideshows

= 1.3 =
Many options added:
* specify a POSTID in shortcode
* select a background color
* window mode
* background color
* fullscreen image source
* turnoff fullscreen mode
* New Transitions : flip (horizontal & vertical)
* Reverse order

* Fixed image order
* Enhanced XML structure
* Improved aesthetics and cursor behaviour
* Improved Random generator

= 1.3.1 =
* Fixed shortcode attribute bug from 1.3 and other smaller issues

= 1.4 =
* Fixed transparency in FF/Win
* Improved Error handling
* All transition code rewritten
* Added 3-D Flip
* Added option to show/hide description
* Added Picasa RSS Feed option
* Added Flickr RSS Feed option

Flash Player 10 is now required !!!

= 1.5 =
* Standardized XML
* Fixed bug with FireFox where the slide show was not properly initialized when off screen
* Improved transition and easing code
* changed description and added settings link
* changed documentation and admin settings
* Many smaller bug fixes


= 1.6 =
* This version will change the aspect of your slide show captions. Please check details before upgrading.
* Added support for custom cursors
* Fixed bug with random images
* Caption styling fully customizable through CSS
* Added support for linking images
* Many smaller bug fixes


== FAQ ==

= How do I add a slideshow into my post/page ? =

In the body of the post/page, type <code>[showtime]</code> or <code>[st]</code>

= How do I get pictures into my slideshow ? =

ShowTime is using the WordPress built-in media library.
In the WordPress post editor, click <strong>Add an image</strong> and select the pictures to upload. This will "attach" the image to the current post. ShowTime automatically retrieves all images attached to a page or post.

= Can I link an image to a custom URL ? =

1. Go to Media > Library
2. Find the image you wish to add a link to and hit <strong>edit</strong>
3. Enter the URL in the description field and hit <strong>Update Media</strong>
4. In the post enter the shortcode <code>[st controls=1004 link=description]</code>

= Where can I modify default settings for this plugin ?

In your WP admin, select Media > ShowTime Slideshow

= What is the function to use in my templates ? =

<code>show_showtime("width=132|height=123|transition=blur|...")</code>

= Can I use the function outside of my post/page ? =

Sure! You can add the slideshow in the sidebar / header / footer ...

= Can I set an ID of a non-published/hidden post ? =

Sure! To find out the ID of a post, go to admin : Posts > Edit
Look at the status bar while you mouse over the title of the post in the list.
code example: <code>show_showtime("id=123")</code>

= How do I get the URL to my Picasa / Flickr Photo album RSS feed ? =

Go to your account, open album find a RSS link and copy link location.
shortcodes: [st flickr=feedURL] [st picasa=feedURL]



== OTHER ==

The showTime plugin is free for personal and non-profit websites. If you use the plugin on a commercial website, please consider a small contribution. Your donation enables me to continue development & support.

For up-to-date information, FAQ, usage guide, comments and feedback, please visit www.youtag.lu

If you need a customized version of ShowTime, please drop a note on my contact page and I'll be happy to help.



== Installation ==

1. Login to your WP-Admin
1. Go to Plugins > Add New
1. Search for Showtime Slideshow and install
1. Activate

To customize, go to Media -> ShowTime

== Screenshots ==

1. ShowTime Settings. Set default options for all slideshows here.

2. Example of a slideshow using "shortcode" options to override default settings.

