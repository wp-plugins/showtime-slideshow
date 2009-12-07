<?php
/*
Plugin Name: ShowTime Slideshow
Plugin URI: http://youtag.lu/showtime-wp-plugin/
Description:Display images attached to a post/page as animated slideshow. Choose among several smooth transition effects. Can also be set to use Flickr or Picasa RSS feeds. ShowTime requires Adobe Flash player 10 or higher.
Version: 1.6
Author: Paul Schroeder
Author URI: http://youtag.lu/

	Plugin framework based on a plugin by Thomas Stachl. 
	http://www.MyPlugins.org/
	
	If this plugin is not fitting your needs, you may want to try out 
	WordPress Gallery Slideshow.
	http://wordpress.org/extend/plugins/wordpress-gallery-slideshow/
	

    Copyright 2009 Youtag Graphic Design  (email : hello@youtag.lu)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



define('TXTDOMAIN', 'showtime');



if ( !class_exists( 'aframework' ) ) {
	require_once( WP_PLUGIN_DIR."/showtime-slideshow/include/adminframework.php" );
}
require_once( WP_PLUGIN_DIR."/showtime-slideshow/showtime/showtime.class.php" );

class st_options extends aframework {

	function _pluginDescription() {
		_e( '
		<h4>Shortcode</h4>		
		<p>Simply add the <code>[showtime]</code> (or [st]) shortcode to a post/page. All images associated to the post/page are automatically included in the showTime slideshow.</p><p>Use the WordPress Media Library to change the <i>title</i> and <i>caption</i> attributes of an image.</p>', TXTDOMAIN );
		_e( '<p>By default, the slideshow includes all images attached to a post/page using the settings defined on this page.</p>', TXTDOMAIN );
		_e( '
		<p>To use individual settings, use the following syntax: <code>[st parameter=value]</code>. <p>Example:</p><code>[st transition=flip autoplay=on]</code> where the name of the parameter is <span class=tag>parameter</span></p><br/><br/>

		<p>There are 3 special parameters:</p>
		<p><code>id=id_of_another_post/page</code></p>
		<p><code>picasa=url_of_picasa_feed</code></p>
		<p><code>flickr=url_of_flickr_feed</code></p><br/>
		
		<h4>Styling</h4>
		
		<p>The style class is <code>.showtime {}</code> Width and height parameters defined in the class will override plugin settings.</p><br/>

		<h4>Using ShowTime in Themes</h4>
		
		<p>Use the function <code>show_showtime("")</code> to hardcode a slideshow in your templates. This functions takes the same options as the shortcode, but separated by the "pipe" symbol. e.g. <code>show_showtime ("id=123|width=50|height=50")</code></p><br/>
		
		', TXTDOMAIN );
		
		_e( '<p><a href="http://www.youtag.lu/showtime-wordpress-plugin-demo/" target="_blank">Click here for demo</a></p>', TXTDOMAIN );
	}
	
	function _pluginInfo() {	
		echo '<img style="float:left; padding:5px 10px 0 10px" src="'.plugins_url($path = $this->_pluginSubfolderName . '/showtime/showtime.png').'" />';
		_e( '<p>ShowTime Slideshow is free for personal and non-profit websites. If you like the plugin on a commercial website, please consider a small <a href="http://youtag.lu/showtime-wp-plugin/" target="_blank">donation</a></p>', TXTDOMAIN );
		_e( '<p>For more information, FAQ, usage guide, comments and feedback, please visit <a href="http://youtag.lu/showtime-wp-plugin/" target="_blank">www.youtag.lu</a></p>', TXTDOMAIN );
		_e( '<p>If you need a custom version of the plugin, please drop a note on my <a href="http://youtag.lu/contact/" target="_blank">contact page</a> and I\'ll be happy to get back to you.</p>', TXTDOMAIN );
	}
	
	function _AddAdministrationAjax() {
		if ( is_admin() ) {
			wp_enqueue_style( 'adminframework', plugins_url($path = $this->_pluginSubfolderName . '/include/style.css') );
			wp_enqueue_script( 'postbox' );
			wp_enqueue_script( 'post' );
			wp_enqueue_script( 'farbtastic' );
			wp_enqueue_style( 'farbtastic' );
		}
	}
	
	function _pluginSettings() {
		?>
		<script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery('#colorpicker_screen').farbtastic('#st_bgcolor');
                jQuery('#st_bgcolor').focus( function(){
                    jQuery('#colorpicker_screen').show();
                });
                jQuery('#st_bgcolor').blur( function(){
                    jQuery('#colorpicker_screen').hide();
                });
            });
            jQuery(document).ready(function() {
                jQuery('#colorpicker_textscreen').farbtastic('#st_textbgcolor');
                jQuery('#st_textbgcolor').focus( function(){
                    jQuery('#colorpicker_textscreen').show();
                });
                jQuery('#st_textbgcolor').blur( function(){
                    jQuery('#colorpicker_textscreen').hide();
                });
            });
        </script>
        
            <table class="form-table" rowpadding="40">
                <tr>
                  <td valign="top">
                  	<p>
                  	<legend><strong style="padding:0; margin:0"><?php _e( 'Basic', TXTDOMAIN ); ?></strong></legend>
					</p>
                  </td>
                  <td>
                  
                  	<p>
                  	<label for="st_width"><? _e( 'Width (pixels) <div class=tag>width</div>', TXTDOMAIN ); ?></label>
                  	<?php $this->DisplayPluginOption( 'st_width' ); ?>
                  	</p>
                  	
					<p>
                  	<label for="st_height"><? _e( 'Height (pixels) <div class=tag>height</div>', TXTDOMAIN ); ?></label>
                  	<?php $this->DisplayPluginOption( 'st_height' ); ?>
                  	</p>
                  	
                  </td>
                  <td>
					
					<p>
					<label for="st_rotatetime"><? _e( 'Rotationtime (seconds) <div class=tag>rotationtime</div>', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_rotatetime' ); ?><br/>
					</p>
					
					<p>
					<label for="st_transitiontime"><? _e( 'Transitiontime (seconds) <div class=tag>transitiontime</div>', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_transitiontime' ); ?>			
					</p>
					
                  </td>
                </tr>
                
                <tr>
                
                  <td valign="top">
                	<p><legend><strong>Slideshow Options</strong></legend></p>
                  </td>
                  
                  <td valign="top">
                  
                    <p><label for="st_transition"><? _e( 'Transition Type  <div class=tag>transition</div>', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_transition' ); ?></p>

				    <p><label for="st_ease"><? _e( 'Transition Easing <div class=tag>transitionease</div>', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_easeFunc' ); ?><?php $this->DisplayPluginOption( 'st_ease' ); ?></p>

				  </td>
				  
				  <td valign="top">

                    <p><?php $this->DisplayPluginOption( 'st_autoplay' ); ?><label for="st_autoplay"><? _e( 'Start automatically  <div class=tag>autoplay</div>', TXTDOMAIN ); ?></label></p>		  
                    <p><?php $this->DisplayPluginOption( 'st_shuffle' ); ?><label for="st_shuffle"><? _e( 'Shuffle Images  <div class=tag>shuffle</div>', TXTDOMAIN ); ?></label></p>
					<p><?php $this->DisplayPluginOption( 'st_reverse' ); ?><label for="st_reverse"><? _e( 'Reverse Order  <div class=tag>reverse</div>', TXTDOMAIN ); ?></p> 					
					
				  </td>
				  
                </tr>


                
                <tr>
                
                  <td valign="top">
                	<p><legend><strong>Cursor & Text Options</strong></legend></p>
                  </td>
                  
                  <td valign="top">
 
                    <p><?php $this->DisplayPluginOption( 'st_showcontrols' ); ?><label for="st_showcontrols"><? _e( 'Display Cursor Controls <div class=tag>showcontrols</div>', TXTDOMAIN ); ?></label></p>

					<p>
					<label for="st_controls"><? _e( 'Control string <div class=tag>controls</div>', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_controls' ); ?>			
					</p>
					<p>The viewport is vertically divided into sections <br/>defined by this string.</p>
					<p>Each caracter stands for a different cursor control:</p>
					<ul>
					<li>0 = standard cursor / link cursor</li>
					<li>1 = left arrow (previous image)</li>
					<li>2 = toggle fullscreen</li>
					<li>3 = play / pause</li>
					<li>4 = right arrow (next image)</li>
					</ul>
					<p>Some examples: <code>14</code> <code>1234</code> <code>0004</code> <code>104</code> <code>134</code></p>




				  </td>

                  <td valign="top">

                    <p><?php $this->DisplayPluginOption( 'st_showtext' ); ?><label for="st_showtext"><? _e( 'Display Title  <div class=tag>showtext</div>', TXTDOMAIN ); ?></label></p>
                    <p><?php $this->DisplayPluginOption( 'st_showalt' ); ?><label for="st_showalt"><? _e( 'Display Caption <div class=tag>showalt</div>', TXTDOMAIN ); ?></label></p>

                    
                    <p><?php $this->DisplayPluginOption( 'st_textbg' ); ?><label for="st_textbg"><? _e( 'Display block behind text', TXTDOMAIN ); ?></label></p>
                    

                 <p><label for="st_textbgcolor"><? _e( 'Text Background Color  <div class=tag>textbgcolor</div>', TXTDOMAIN ); ?></label>
                 <?php $this->DisplayPluginOption( 'st_textbgcolor' ); ?>
                 <div id="colorpicker_textscreen" style="background:#F9F9F9;position:absolute;display:none;"></div></p>




                 <p><label for="st_css"><? _e( 'Style Sheet', TXTDOMAIN ); ?></label><br/>
				 <?php $this->DisplayPluginOption( 'st_css' ); ?></p>




                  </td>
                
                </tr>


                  <td valign="top">
                	<p><legend><strong>Display Options</strong></legend></p>
                  </td>
                  
                  <td valign="top">
 

                    <p><label for="st_scalemode"><? _e( 'Scale Mode <div class=tag>scale</div>', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_scalemode' ); ?></p>
					
                    <p><label for="st_imgsize"><? _e( 'Source Image (Window) <div class=tag>source</div>', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_imgsize' ); ?></p>
					
                    <p><label for="st_imgfullsize"><? _e( 'Source Image (Fullscreen) <div class=tag>sourcehd</div>', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_imgfullsize' ); ?></p>


				  </td>

                  <td valign="top">


	                 <p><label for="st_bgcolor"><? _e( 'Background Color  <div class=tag>bgcolor</div>', TXTDOMAIN ); ?></label>
	                 <?php $this->DisplayPluginOption( 'st_bgcolor' ); ?>
	                 <div id="colorpicker_screen" style="background:#F9F9F9;position:absolute;display:none;"></div></p>
	
	                 <p><label for="st_quality"><? _e( 'Display Quality <div class=tag>quality</div>', TXTDOMAIN ); ?></label>
					 <?php $this->DisplayPluginOption( 'st_quality' ); ?></p>
	
	                 <p><label for="st_wmode"><? _e( 'Window Mode <div class=tag>wmode</div>', TXTDOMAIN ); ?></label>
					 <?php $this->DisplayPluginOption( 'st_wmode' ); ?></p>
	
					
				    <p><label for="st_ease"><? _e( 'Alternative Content', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_alternative' ); ?></p>

                  </td>
                
                </tr>




                <tr>
                
                  <td valign="top">
                	<p><legend><strong>Link Options</strong></legend></p>
                  </td>
                  
                  <td valign="top">
 
                    <p><label for="st_link"><? _e( 'Link location <div class=tag>link</div>', TXTDOMAIN ); ?></label>
                    <?php $this->DisplayPluginOption( 'st_link' ); ?></p>

					<p>Adds a link to the image if cursor type is 0 <br/>(or no custom cursor)</p>
					<ul>	
					<li>parent = link to the post containing the image</li>
					<li>post = link to the image post</li>
					<li>image = link to the original image file</li>
					<li>description = link to a custom url <br/>specified in the description field of <br/>WP media uploader</li>
					</ul>
							
                  </td>

                  <td valign="top">

                    <p><label for="st_target"><? _e( 'Link target <div class=tag>target</div>', TXTDOMAIN ); ?></label>
                    <?php $this->DisplayPluginOption( 'st_target' ); ?></p>

					<p>Select link target</p>
					<ul>
					<li>_self = open links in same frame</li>
					<li>_blank = open links in a new window</li>
					</ul>


				  </td>

                
                </tr>




            </table>
            

  
            
			<div id="major-publishing-actions">


                    <input class="button" id="save-post" type="submit" name="plugin_options_reset" value="<?php _e( 'Restore Settings', TXTDOMAIN ); ?>" onclick='return( confirm( <?php _e( "Do you really want to restore the default settings?\nAll of your changes will be lost.", TXTDOMAIN ); ?> ) );' />

                    <input class="button" type="submit" name="plugin_options_uninstall" value="<?php _e( 'Uninstall', TXTDOMAIN ); ?>" onclick='return( confirm( <?php _e( "Do you really want to uninstall this plugin?\nAll of your changes will be permanently removed from the database.", TXTDOMAIN ); ?> ) );' />


            	<div id="publishing-action">
                	<input class="button-primary" type="submit" name="plugin_options_update" value="<?php _e( 'Save Settings', TXTDOMAIN ); ?>" />
                </div>
                <br class="clear" />
			</div>
                               
		<?php
	}
	
}

$stOptions = new st_options();
$showtime = new wordpress_showtime_slideshow();


$stOptions->Initialize(	'ShowTime Slideshow',
								'1.4',
								'showtime-slideshow',
								'showtime',
								'http://youtag.lu/showtime-wp-plugin/' );

$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
	                        	'st_height',
	                        	'320',
	                        	__( 'Height in pixels', TXTDOMAIN ) );
$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
	                        	'st_width',
	                        	'640',
	                        	__( 'Width in pixels', TXTDOMAIN ) );




$comboboxArray = array( 'Blur'=>'Blur', 'Fade'=>'Fade', 'Flash'=>'Flash', 'ZoomIn'=>'Zoomin', 'ZoomOut'=>'Zoomout', 'SlideDown'=>'SlideDown', 'SlideUp'=>'SlideUp', 'SlideLeft'=>'SlideLeft', 'SlideRight'=>'SlideRight', 'WipeH'=>'WipeH', 'WipeV'=>'WipeV', 'FlipH'=>'FlipH', 'FlipV'=>'FlipV', 'Flip'=>'Flip', 'KenBurns'=>'panandzoom' );

$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_transition',
								'Blur',
								'',
								$comboboxArray );


$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_scalemode',
								'showAll',
								'',
								array( 'noScale'=>'noScale', 'showAll'=>'showAll', 'exactFit'=>'exactFit', 'noBorder'=>'noBorder' ) );


$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_imgsize',
								'medium',
								'',
								array( 'thumbnail'=>'thumbnail', 'medium'=>'medium', 'large'=>'large', 'full'=>'full' ) );

$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_imgfullsize',
								'large',
								'',
								array( 'thumbnail'=>'thumbnail', 'medium'=>'medium', 'large'=>'large', 'full'=>'full' ) );



$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_quality',
								'autohigh',
								'',
								array( 'low'=>'low', 'autolow'=>'autolow', 'autohigh'=>'autohigh', 'best'=>'best' ) );

$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_wmode',
								'window',
								'',
								array( 'window'=>'window', 'opaque'=>'opaque', 'transparent'=>'transparent', 'direct'=>'direct', 'gpu'=>'gpu' ) );


$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
								'st_transitiontime',
								'1',
								__( 'Transition duration in seconds', TXTDOMAIN ) );



$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_easeFunc',
								'Linear',
								'',
								array(  'Elastic'=>'Elastic', 'Bounce'=>'Bounce', 'Linear'=>'Linear', 'Circular'=>'Circular', 'Cubic'=>'Cubic', 'Quadratic'=>'Quadratic', 
								'Quintic'=>'Quintic', 'Quartic'=>'quartic', 'Sine'=>'Sine', 'Exponential'=>'Exponential', 'Back'=>'Back' ) 
								);


$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_ease',
								'easeOut',
								'',
								array( 'EaseNone'=>'EaseNone','EaseOut'=>'EaseOut','EaseIn'=>'EaseIn','EaseInOut'=>'EaseInOut' )
								);

$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_alternative',
								'gallery',
								'',
								array( 'gallery'=>'WordPress Gallery','expressInstall'=>'Express Install' )
								);

$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_link',
								'',
								'',
								array( ''=>'','parent'=>'parent','post'=>'post','image'=>'image','description'=>'description' )
								);

$stOptions->AddOption(	$stOptions->OPTION_TYPE_COMBOBOX,
								'st_target',
								'_self',
								'',
								array( '_self'=>'_self','_top'=>'top','_parent'=>'_parent','_blank'=>'_blank' )
								);

$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
	                        	'st_bgcolor',
	                        	'#000000',
	                        	__( 'Background Color', TXTDOMAIN ) );

$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
	                        	'st_textbgcolor',
	                        	'#000000',
	                        	__( 'Text Background Color', TXTDOMAIN ) );


$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
								'st_rotatetime',
								'5',
								__( 'Display Slide duration in seconds', TXTDOMAIN ) );


$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_shuffle',
								$stOptions->CHECKBOX_UNCHECKED,
								__( 'Shuffle images.', TXTDOMAIN ) );
$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_reverse',
								$stOptions->CHECKBOX_UNCHECKED,
								__( 'Reverse Order', TXTDOMAIN ) );
$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_showtext',
								$stOptions->CHECKBOX_UNCHECKED,
								__( 'Display title', TXTDOMAIN ) );
$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_showalt',
								$stOptions->CHECKBOX_UNCHECKED,
								__( 'Display description', TXTDOMAIN ) );
								
$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTAREA,
								'st_css',
								'p {
	marginLeft: 15;
	marginRight: 15;
	valign: bottom;
	textAlign: left;
	color: #ffffff;
	font-family: _sans, Helvetica, Arial;
	fontWeight:normal;
	kerning: true;
	leading: 0;
}
.description {
	fontSize: 17;
}
h1 {
	fontSize: 28;
}
a {
	color: #ccccff;
}
a:hover {
	color: #ffffff;
	textDecoration: underline;
}
.spacer {
	fontSize: 12;
}
.cursor {color: #ffffff;}
.circle {color: #ffffff;}
.shadow {color: #000000; strength: 0; blur: 2;}
',
								__( 'Style Sheet for title and caption', TXTDOMAIN ) );
								
								
$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_showcontrols',
								$stOptions->CHECKBOX_CHECKED,
								__( 'Show the navigation buttons.', TXTDOMAIN ) );

$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
								'st_controls',
								'1234',
								__( 'Cursor String.', TXTDOMAIN ) );


$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_textbg',
								$stOptions->CHECKBOX_UNCHECKED,
								__( 'Display block behind text.', TXTDOMAIN ) );

								
$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_autoplay',
								$stOptions->CHECKBOX_CHECKED,
								__( 'Start playing automatically.', TXTDOMAIN ) );

$stOptions->RegisterOptions( __FILE__ );

$stOptions->AddAdministrationPageBlock(	'block-info',
	                                        	__( 'About', TXTDOMAIN ),
	                                        	$stOptions->CONTENT_BLOCK_TYPE_SIDEBAR,
	                                        	array($stOptions, '_pluginInfo'),
												1 );
$stOptions->AddAdministrationPageBlock(	'block-description',
	                                        	__( 'Usage', TXTDOMAIN ),
	                                        	$stOptions->CONTENT_BLOCK_TYPE_SIDEBAR,
	                                        	array($stOptions, '_pluginDescription'),
												1 );
$stOptions->AddAdministrationPageBlock(	'block-settings',
	                                        	__( 'Settings', TXTDOMAIN ),
	                                        	$stOptions->CONTENT_BLOCK_TYPE_MAIN,
	                                        	array($stOptions, '_pluginSettings'),
												1 );
$stOptions->RegisterAdministrationPage(	$stOptions->PARENT_MENU_MEDIA,
	                                         	$stOptions->ACCESS_LEVEL_ADMINISTRATOR,
	                                         	__( 'ShowTime Slideshow', TXTDOMAIN ),
	                                         	__( 'ShowTime Options Page', TXTDOMAIN ),
	                                         	'showtime' );

add_action( 'init', array($stOptions, '_AddAdministrationAjax' ) );
//add_action( 'admin_head' , array($stOptions, 'AddHoverFunctionality')	);
add_action( 'wp_head', array($showtime, 'addToHeader'), 1);

add_shortcode('slide', array($showtime, 'wp_gallery_st_own_shortcut'));
add_shortcode('showtime', array($showtime, 'wp_gallery_st_own_shortcut'));
add_shortcode('st', array($showtime, 'wp_gallery_st_own_shortcut'));

function show_showtime($attr) {
	global $showtime;
	$result = '';
	$attr .= "&slide=1";
	$attributes = explode('|', $attr);
		foreach($attributes as $attribute){
			if($attribute){
				// find the position of the first '='
				$i = strpos($attribute, '=');
				// if not a valid format ('key=value) we ignore it
				if ($i){
					$key = substr($attribute, 0, $i);
					$val = substr($attribute, $i+1); 
					$result[$key]=$val;
				}
			}
		}
	echo $showtime->wp_gallery_st_shortcut($result, 1);
}



// Add settings link on plugin page
function plugin_settings_link($links) {
  $settings_link = '<a href="upload.php?page=showtime">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'plugin_settings_link' );


?>