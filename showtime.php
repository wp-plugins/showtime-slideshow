<?php
/*
Plugin Name: ShowTime Slideshow
Plugin URI: http://youtag.lu/showtime-wp-plugin/
Description:<strong>Displays all images attached to a post/page as animated slideshow. <a href="http://www.youtag.lu/showtime-wordpress-plugin-demo" target="_blank">demo</a></strong> * No setup necessary * Simply upload images in WordPress as usual * Future-proof (using wp functions where possible, no database tables added) * Clean transition effects including <strong>Ken Burns <em>pan & zoom</em> / blur / slide / flash / flip</strong> * Full Screen mode * ShowTime requires Adobe Flash. If Flash is not installed on the client system, the default WordPress gallery is displayed. <a href="./upload.php?page=showtime">Change default Options</a>
Version: 1.3
Author: Paul Schroeder
Author URI: http://youtag.lu/

Plugin framework based on the excellent Wordpress Gallery Slideshow by http://www.MyPlugins.org/

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
	require_once( "include/adminframework.php" );
}
require_once( "showtime/showtime.class.php" );

class st_options extends aframework {

	function _pluginDescription() {
		_e( '<p>Simply add the <code>[showtime]</code> shortcode to a post/page. All images associated to the post/page are automatically included in the showTime slideshow.</p><p>Use the WordPress Media Library to change the <i>title</i> and <i>caption</i> attributes of an image.</p>', TXTDOMAIN );
		_e( '<p>By default, the plugin displays all images related to a post/page as a slideshow using the settings defined on this page.</p>', TXTDOMAIN );
		_e( '<p>You may add individual settings to the shortcode using the following syntax:</p><p><code>[showtime<br/> id = < postid ><br/> width = < number ><br/> height = < number ><br/> rotationtime = < number ><br/> transitiontime = < number ><br/> transition = blur|fade|flash|zoomin|zoomout|<br/>slidedown|slideup|slideleft|slideright|<br/>wipeh|wipev|kenburns|fliph|flipv<br/> transitionease = back.easeout|... <br/>autoplay = on|off <br/>fullscreen = on|off <br/>reverse = on|off <br/>shuffle = on|off <br/>showcontrols = on|off <br/>showtext = on|off <br/>scale = noscale|showall|exactfit|noborder <br/>bgcolor = #000000 <br/>source = thumbnail|medium|large|full<br/>sourcehd = thumbnail|medium|large|full <br/> ]</code></p><p>Use the function <code>show_showtime()</code> to hardcode a slideshow in your templates. This functions takes the same options as the shortcode, but separated by the "pipe" symbol. e.g. <code>show_showtime (id=123|width=50|height=50)</code>', TXTDOMAIN );
		_e( '<p><a href="http://www.youtag.lu/showtime-wordpress-plugin-demo/" target="_blank">Click here for demo</a></p>', TXTDOMAIN );
	}
	
	function _pluginInfo() {
		_e( '<p>The showTime plugin is free for personal and non-profit websites. If you like the plugin on a commercial website, please consider a small <a href="http://youtag.lu/showtime-wp-plugin/" target="_blank">donation</a></p>', TXTDOMAIN );
		_e( '<p>For up-to-date information, FAQ, usage guide, comments and feedback, please visit <a href="http://youtag.lu/showtime-wp-plugin/" target="_blank">www.youtag.lu</a></p>', TXTDOMAIN );
		_e( '<p>If you need a customized version of <i>ShowTime</i>, please drop a note on my <a href="http://youtag.lu/contact/" target="_blank">contact page</a> and I\'ll get back to you.</p>', TXTDOMAIN );
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
                  	<label for="st_width"><? _e( 'Width (in pixels)', TXTDOMAIN ); ?></label><br/>
                  	<?php $this->DisplayPluginOption( 'st_width' ); ?>
                  	</p>
                  	
					<p>
                  	<label for="st_height"><? _e( 'Height (in pixels)', TXTDOMAIN ); ?></label><br/>
                  	<?php $this->DisplayPluginOption( 'st_height' ); ?>
                  	</p>
                  	
                  </td>
                  <td>
					
					<p>
					<label for="st_rotatetime"><? _e( 'Rotationtime (in seconds)', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_rotatetime' ); ?><br/>
					</p>
					
					<p>
					<label for="st_transitiontime"><? _e( 'Transitiontime (in seconds)', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_transitiontime' ); ?>			
					</p>
					
                  </td>
                </tr>
                
                <tr>
                
                  <td valign="top">
                	<p><legend><strong>Slideshow Options</strong></legend></p>
                  </td>
                  
                  <td valign="top">
                  
                    <p><label for="st_transition"><? _e( 'Transition Type', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_transition' ); ?></p>

				    <p><label for="st_ease"><? _e( 'Transition Easing', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_easeFunc' ); ?><?php $this->DisplayPluginOption( 'st_ease' ); ?></p>

				  </td>
				  
				  <td valign="top">

                    <p><?php $this->DisplayPluginOption( 'st_autoplay' ); ?><label for="st_autoplay"><? _e( 'Start automatically', TXTDOMAIN ); ?></label></p>		  
                    <p><?php $this->DisplayPluginOption( 'st_shuffle' ); ?><label for="st_shuffle"><? _e( 'Shuffle Images', TXTDOMAIN ); ?></label></p>
					<p><?php $this->DisplayPluginOption( 'st_reverse' ); ?><label for="st_reverse"><? _e( 'Reverse Order', TXTDOMAIN ); ?></p> 					
					
				  </td>
				  
                </tr>
                
                <tr>
                
                  <td valign="top">
                	<p><legend><strong>Display Options</strong></legend></p>
                  </td>
                  
                  <td valign="top">
                    <p><?php $this->DisplayPluginOption( 'st_showcontrols' ); ?><label for="st_showcontrols"><? _e( 'Display Cursor Controls', TXTDOMAIN ); ?></label></p>
                    
                    <p><?php $this->DisplayPluginOption( 'st_fullscreen' ); ?><label for="st_fullscreen"><? _e( 'Enable Fullscreen', TXTDOMAIN ); ?></label></p>
                    
                    <p><?php $this->DisplayPluginOption( 'st_showtext' ); ?><label for="st_showtext"><? _e( 'Display Image Title and <Alt> description', TXTDOMAIN ); ?></label></p>

                 <p><label for="st_bgcolor"><? _e( 'Background Color', TXTDOMAIN ); ?></label><br/>
                 <?php $this->DisplayPluginOption( 'st_bgcolor' ); ?>
                 <div id="colorpicker_screen" style="background:#F9F9F9;position:absolute;display:none;"></div></p>

                 <p><label for="st_quality"><? _e( 'Display Quality', TXTDOMAIN ); ?></label><br/>
				 <?php $this->DisplayPluginOption( 'st_quality' ); ?></p>

                 <p><label for="st_quality"><? _e( 'Window Mode [experimental]', TXTDOMAIN ); ?></label><br/>
				 <?php $this->DisplayPluginOption( 'st_wmode' ); ?></p>


				  </td>

                  <td valign="top">
                    <p><label for="st_imgSize"><? _e( 'Scale Mode', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_scalemode' ); ?></p>
					
                    <p><label for="st_imgSize"><? _e( 'Source Image (Window)', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_imgsize' ); ?></p>
					
                    <p><label for="st_imgFullSize"><? _e( 'Source Image (Fullscreen)', TXTDOMAIN ); ?></label><br/>
					<?php $this->DisplayPluginOption( 'st_imgfullsize' ); ?></p>
					
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
								'1.3',
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




$comboboxArray = array( 'Blur'=>'Blur', 'Fade'=>'Fade', 'Flash'=>'Flash', 'ZoomIn'=>'Zoomin', 'ZoomOut'=>'Zoomout', 'SlideDown'=>'SlideDown', 'SlideUp'=>'SlideUp', 'SlideLeft'=>'SlideLeft', 'SlideRight'=>'SlideRight', 'WipeH'=>'WipeH', 'WipeV'=>'WipeV', 'FlipH'=>'FlipH', 'FlipV'=>'FlipV', 'KenBurns'=>'panandzoom' );

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
								array( 'EaseOut'=>'Ease Out','EaseIn'=>'Ease In','EaseInOut'=>'Ease In and Out' )
								);

$stOptions->AddOption(	$stOptions->OPTION_TYPE_TEXTBOX,
	                        	'st_bgcolor',
	                        	'#000000',
	                        	__( 'Background Color', TXTDOMAIN ) );


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
								__( 'Display caption', TXTDOMAIN ) );
$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_showcontrols',
								$stOptions->CHECKBOX_CHECKED,
								__( 'Show the navigation buttons.', TXTDOMAIN ) );

$stOptions->AddOption(	$stOptions->OPTION_TYPE_CHECKBOX,
								'st_fullscreen',
								$stOptions->CHECKBOX_CHECKED,
								__( 'Enable Fullscreen mode', TXTDOMAIN ) );
								
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

add_shortcode('gallery', array($showtime, 'wp_gallery_st_shortcut'));
//add_shortcode('slide', array($showtime, 'wp_gallery_st_own_shortcut'));
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


/*
function myplugin_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "showtime_plugin");
     add_filter('mce_buttons', 'showtime_button');
   }
}
 
function register_myplugin_button($buttons) {
   array_push($buttons, "separator", "showTime");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function showtime_plugin($plugin_array) {
   $plugin_array['showtime'] = $path = $stOptions->_pluginSubfolderName.'/showtime-slideshow/showtime/tinymce3/editor_plugin.js';
   return $plugin_array;
}
 
// init process for button control
add_action('init', 'showtime_button');

*/


/* ---------- tinyMCE button ---------- */
/*
add_action( 'init', 'showTime_addbuttons');

function showTime_addbuttons() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
		return;
	}
	if ( get_user_option('rich_editing') == 'true') {
		add_filter( 'mce_external_plugins', 'showtime_plugin' );
		add_filter( 'mce_buttons', 'showtime_button' );
	}
}

// Load the custom TinyMCE plugin
function showtime_plugin( $plugins ) {
	$plugins['showtime'] = plugins_url($path = 'http://localhost:8888/youtagdesign/wp-content/plugins/showtime-slideshow/showtime/tinymce3/editor_plugin.js');
	return $plugins;
}

function showtime_button( $buttons ) {
	array_push( $buttons, '|', 'showTime' );
	return $buttons;
}

function my_refresh_mce($ver) {
  $ver += 3; // or $ver .= 3; or ++$ver; etc.
  return $ver;
}
add_filter('tiny_mce_version', 'my_refresh_mce');
*/


?>