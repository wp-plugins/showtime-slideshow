<?php
/*
Plugin Name: ShowTime Slideshow
Plugin URI: http://youtag.lu/showtime-wp-plugin/
Description:Displays all images within a post/page inside a stunning animated diaporama. Simplicity is the key of this plugin. No setup necessary. High quality transitions. Ken Burns "pan & zoom" effect. Full Screen mode. ShowTime requires Adobe Flash. If Flash is not installed, the default WordPress gallery is displayed. <a href="http://www.youtag.lu/showtime-wordpress-plugin-demo" target="_blank">demo</a>
Version: 0.5
Author: Paul Schroeder
Author URI: http://youtag.lu/

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



if ( !class_exists( 'adminframework' ) ) {
	require_once( "include/adminframework.php" );
}
require_once( "showtime/slideshow.class.php" );

class st_options extends adminframework {

	function _pluginDescription() {
		_e( '<p>Simply add the <code>[showtime]</code> shortcode to a post/page. All images associated to the post/page are automatically included in the showTime slideshow.</p><p>Use the WordPress Media Library to change the <i>title</i> and <i>caption</i> attributes of an image.</p>', TXTDOMAIN );
		_e( '<p>By default, the plugin displays all images related to a post/page as a slideshow using the settings defined on this page.</p>', TXTDOMAIN );
		_e( '<p>You may add individual settings to the shortcode using the following syntax:</p><p><code>[showtime<br/> width = < number ><br/> height = < number ><br/> rotationtime = < number ><br/> transitiontime = < number ><br/> transition = blur|fade|flash|zoomin|zoomout|<br/>slidedown|slideup|slideleft|slideright|<br/>wipeh|wipev|kenburns<br/> transitionease = back.easeout|... <br/>autoplay = on|off <br/>shuffle = on|off <br/>showcontrols = on|off <br/>showtext = on|off <br/>scale = noscale|showall|exactfit|noborder <br/>]</code></p>', TXTDOMAIN );
		_e( '<p><a href="http://www.youtag.lu/showtime-wordpress-plugin-demo/" target="_blank">Click here for demo</a></p>', TXTDOMAIN );
	}
	
	function _pluginInfo() {
		_e( '<p>The showTime gallery plugin is free for personal and commercial use.</p>', TXTDOMAIN );
		_e( '<p>For the latest information, comments and feedback, please visit <a href="http://youtag.lu/showtime/" target="_blank">www.youtag.lu/showtime</a></p>', TXTDOMAIN );
		_e( '<p>If you need a customized version of <i>ShowTime</i>, please drop a note on my <a href="http://youtag.lu/contact/" target="_blank">contact page</a> and I\'ll get back to you.</p>', TXTDOMAIN );
	}
	
	function _AddAdministrationAjax() {
		if ( is_admin() ) {
			//wp_enqueue_style( 'adminframework', plugins_url($path = $this->_pluginSubfolderName . '/include/style.css') );
			wp_enqueue_script( 'postbox' );
			wp_enqueue_script( 'post' );
			//wp_enqueue_script( 'farbtastic' );
			//wp_enqueue_style( 'farbtastic' );
		}
	}
	
	function _pluginSettings() {
		?>
        
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
					<p></p> 					
					
				  </td>
				  
                </tr>
                
                <tr>
                
                  <td valign="top">
                	<p><legend><strong>Display Options</strong></legend></p>
                  </td>
                  
                  <td valign="top">
                    <p><?php $this->DisplayPluginOption( 'st_showcontrols' ); ?><label for="st_showcontrols"><? _e( 'Display Cursor Controls', TXTDOMAIN ); ?></label></p>
                    <p><?php $this->DisplayPluginOption( 'st_showtext' ); ?><label for="st_showtext"><? _e( 'Display Image Title and <Alt> description', TXTDOMAIN ); ?></label></p>

				  </td>

                  <td valign="top">
                    <p><label for="st_imgSize"><? _e( 'Scale Mode', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_scalemode' ); ?></p>
					
                    <p><label for="st_imgSize"><? _e( 'Source Image Size', TXTDOMAIN ); ?></label>
					<?php $this->DisplayPluginOption( 'st_imgsize' ); ?></p>
					
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

$slideshowOptions = new st_options();
$slideshow = new wordpress_showtime_slideshow();


$slideshowOptions->Initialize(	'ShowTime Gallery Slideshow',
								'0.1',
								'showtime',
								'slideshow',
								'http://youtag.lu/' );

$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'st_height',
	                        	'320',
	                        	__( 'Height in pixels', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
	                        	'st_width',
	                        	'640',
	                        	__( 'Width in pixels', TXTDOMAIN ) );




$comboboxArray = array( 'Blur'=>'Blur', 'Fade'=>'Fade', 'Flash'=>'Flash', 'ZoomIn'=>'Zoomin', 'ZoomOut'=>'Zoomout', 'SlideDown'=>'SlideDown', 'SlideUp'=>'SlideUp', 'SlideLeft'=>'SlideLeft', 'SlideRight'=>'SlideRight', 'WipeH'=>'WipeH', 'WipeV'=>'WipeV', 'KenBurns'=>'panandzoom' );

$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_COMBOBOX,
								'st_transition',
								'KenBurns',
								'',
								$comboboxArray );


$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_COMBOBOX,
								'st_scalemode',
								'noBorder',
								'',
								array( 'noScale'=>'noScale', 'showAll'=>'showAll', 'exactFit'=>'exactFit', 'noBorder'=>'noBorder' ) );


$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_COMBOBOX,
								'st_imgsize',
								'medium',
								'',
								array( 'thumbnail'=>'Thumbnail', 'medium'=>'Medium', 'large'=>'Large', 'full'=>'Full' ) );


$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
								'st_transitiontime',
								'1.5',
								__( 'Transition duration in seconds', TXTDOMAIN ) );



$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_COMBOBOX,
								'st_easeFunc',
								'Cubic',
								'',
								array(  'Elastic'=>'Elastic', 'Bounce'=>'Bounce', 'Linear'=>'Linear', 'Circular'=>'Circular', 'Cubic'=>'Cubic', 'Quadratic'=>'Quadratic', 
								'Quintic (Strong)'=>'Quintic', 'Quartic'=>'quartic', 'Sine'=>'Sine', 'Exponential'=>'Exponential', 'Back'=>'Back' ) 
								);


$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_COMBOBOX,
								'st_ease',
								'easeOut',
								'',
								array( 'EaseOut'=>'Ease Out','EaseIn'=>'Ease In','EaseInOut'=>'Ease In and Out' )
								);



$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_TEXTBOX,
								'st_rotatetime',
								'5',
								__( 'Display Slide duration in seconds', TXTDOMAIN ) );


$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'st_shuffle',
								$slideshowOptions->CHECKBOX_UNCHECKED,
								__( 'Shuffle images.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'st_showtext',
								$slideshowOptions->CHECKBOX_UNCHECKED,
								__( 'Display caption', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'st_showcontrols',
								$slideshowOptions->CHECKBOX_CHECKED,
								__( 'Show the navigation buttons.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'st_usefullscreen',
								$slideshowOptions->CHECKBOX_CHECKED,
								__( 'Allow fullscreen mode.', TXTDOMAIN ) );
$slideshowOptions->AddOption(	$slideshowOptions->OPTION_TYPE_CHECKBOX,
								'st_autoplay',
								$slideshowOptions->CHECKBOX_CHECKED,
								__( 'Start playing automatically.', TXTDOMAIN ) );

$slideshowOptions->RegisterOptions( __FILE__ );

$slideshowOptions->AddAdministrationPageBlock(	'block-info',
	                                        	__( 'About', TXTDOMAIN ),
	                                        	$slideshowOptions->CONTENT_BLOCK_TYPE_SIDEBAR,
	                                        	array($slideshowOptions, '_pluginInfo'),
												1 );
$slideshowOptions->AddAdministrationPageBlock(	'block-description',
	                                        	__( 'Usage', TXTDOMAIN ),
	                                        	$slideshowOptions->CONTENT_BLOCK_TYPE_SIDEBAR,
	                                        	array($slideshowOptions, '_pluginDescription'),
												1 );
$slideshowOptions->AddAdministrationPageBlock(	'block-settings',
	                                        	__( 'Settings', TXTDOMAIN ),
	                                        	$slideshowOptions->CONTENT_BLOCK_TYPE_MAIN,
	                                        	array($slideshowOptions, '_pluginSettings'),
												1 );
$slideshowOptions->RegisterAdministrationPage(	$slideshowOptions->PARENT_MENU_MEDIA,
	                                         	$slideshowOptions->ACCESS_LEVEL_ADMINISTRATOR,
	                                         	__( 'ShowTime Gallery', TXTDOMAIN ),
	                                         	__( 'ShowTime Options Page', TXTDOMAIN ),
	                                         	'showtime',
											 	'showtime.png' );

add_action( 'init', array($slideshowOptions, '_AddAdministrationAjax' ) );
add_action( 'admin_head' , array($slideshowOptions, 'AddHoverFunctionality')	);
add_action( 'wp_head', array($slideshow, 'addToHeader'), 1);

add_shortcode('gallery', array($slideshow, 'wp_gallery_st_shortcut'));
add_shortcode('slide', array($slideshow, 'wp_gallery_st_shortcut'));
add_shortcode('showtime', array($slideshow, 'wp_gallery_st_own_shortcut'));

function show_showtime($attr) {
	global $slideshow;
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
	echo $slideshow->wp_gallery_st_shortcut($result, 1);
}



/*
	Adding the KFE button to the MCE toolbar. For some reason, WP 2.6 doesn't allow me to do this from within the KimiliFlashEmbed class.
*/
add_action( 'init', 'showTime_addbuttons');

function showTime_addbuttons() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
		return;
	}
	if ( get_user_option('rich_editing') == 'true') {
		add_filter( 'tiny_mce_version', 'tiny_mce_version', 0 );
		add_filter( 'mce_external_plugins', 'showtime_plugin', 0 );
		add_filter( 'mce_buttons', 'showtime_button', 0);
	}
}

// Break the browser cache of TinyMCE
/*
function tiny_mce_version( $version ) {
	global $ShowTime;
	return $version . '-kfe' . $ShowTime->version;
}
*/

// Load the custom TinyMCE plugin
function showtime_plugin( $plugins ) {
	$plugins['showtime'] = plugins_url($path = $slideshowOptions->_pluginSubfolderName.'/showtime/tinymce3/editor_plugin.js');
	return $plugins;
}

function showtime_button( $buttons ) {
	array_push( $buttons, 'separator', 'showTime' );
	return $buttons;
}


?>