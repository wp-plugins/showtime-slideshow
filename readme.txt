=== ShowTime Slideshow ===

Contributors: youtag, mypluginsorg
Donate link: http://www.youtag.lu/showtime-wp-plugin/
Tags: wordpress, showtime, slideshow, gallery, animation, ken, burns, adobe, flash, images, photo, photos, picture, pictures, plugin, special, FX, slide, flip, blur, zoom, animation, photo, show, presentation
Requires at least: 2.7
Tested up to: 2.8.4
Stable tag: 1.3.1

This plugin displays all images attached to a post/page as an animated slideshow. 


== Description ==

Displays all pictures attached to a post/page as a slideshow. Simplicity is the key. No setup necessary. This plugin requires Adobe Flash, reverts nicely to default WordPress gallery if Flash is not found. Choose among high quality transitions. Ken Burns (pan&zoom) and many other effects. Full Screen mode.

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


== FAQ ==

= How do I add a slideshow into my post/page ? =

In the body of the post/page, type <code>[slideshow]</code>

= How do I get pictures into my slideshow ? =

While editing your post, click <strong>Add an image</strong> and select the pictures to upload.
That's all ! ShowTime automatically retrieves all images attached to a post.

 To customize, go to your admin, Media > ShowTime Slideshow

= What is the function to use in my templates ? =

<code>show_showtime(width=132|height=123|transition=blur|É)</code>

= Can I use the function outside of my post/page ? =

Sure! You can add the slideshow in the sidebar / header / footer É

= Can I set an ID of a non-published/hidden post ? =

Sure! To find out the ID of a post, go to admin : Posts > Edit
Look at the status bar while you mouse over the title of the post in the list.
code example: <code>show_showtime(id=123)</code>


== OTHER ==

The showTime plugin is free for personal and non-profit websites. If you use the plugin on a commercial website, please consider a small contribution. USD 5 / EUR 3.5 is a fair amount. Your donation entitles you for premium support.

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

