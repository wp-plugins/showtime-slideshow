<?php

class wordpress_showtime_slideshow {
	var $gid = 0;
	
	function wp_gallery_st_shortcut($attr, $type) {
		global $post;
		global $wp_version;
		global $slideshowOptions;
		
		$attributes = $attr;
		
		if ( $type )
			$slide = 1;
		else
			$slide = 0;
		
		$height = get_option( 'st_height' );
		$width = get_option( 'st_width' );
		$transition = get_option( 'st_transition' );
		$transitiontime = get_option( 'st_transitiontime' );
		$transitionease = get_option( 'st_easeFunc' ).'.'.get_option( 'st_ease' );
		$rotationtime = get_option( 'st_rotatetime' );
		
		$showcontrols = get_option( 'st_showcontrols' );
		$showtext = get_option( 'st_showtext' );
		$autoplay = get_option( 'st_autoplay' );
		$shuffle = get_option( 'st_shuffle' );
		$scale = get_option( 'st_scalemode' );
		
	
		extract(shortcode_atts(array(
			'id'         		=> $post->ID,
			'width'				=> $width,
			'height'         	=> $height,
			'rotationtime'		=> $rotationtime,
			'transition'		=> $transition,
			'transitiontime'	=> $transitiontime,
			'transitionease'	=> $transitionease,
			'autoplay'			=> $autoplay,
			'showcontrols'		=> $showcontrols,
			'showtext'			=> $showtext,
			'shuffle'			=> $shuffle,
			'scale'			=> $scale,
		), $attr));

		$id = intval($id);
/*		
		$backcolor = substr($backcolor, 1);
		$frontcolor = substr($frontcolor, 1);
		$lightcolor = substr($lightcolor, 1);
		$screencolor = substr($screencolor, 1);
		
		$shuffle = $this->checkOn( $shuffle );
		$overstretch = $this->checkOn( $overstretch );
		$shownavigation = $this->checkOn( $shownavigation );
		$usefullscreen = $this->checkOn( $usefullscreen );
*/
		
		if ( function_exists( 'gallery_shortcode' ) ) {
			$gallery = gallery_shortcode( $attributes );
		}
		
		$this->gid++;
		
		
		$attr = "id=".$post->ID;
		//."&imgsize=".$imgSize;
		//echo $attr;

		
		if ( $slide )
			$output = "
			
				<script type='text/javascript'>

					var flashvars = {};
					flashvars.xml = '" . plugins_url($path = $slideshowOptions->_pluginSubfolderName . '/showtime/getxml.php') . "?$attr';

					flashvars.width = '$width';
					flashvars.height = '$height';

					flashvars.rotationtime = '$rotationtime';
					flashvars.transitiontime = '$transitiontime';
					flashvars.transition = '$transition';
					flashvars.transitionease = '$transitionease';
					flashvars.autoplay = '$autoplay';
					flashvars.showcontrols = '$showcontrols';
					flashvars.showtext = '$showtext';
					flashvars.shuffle = '$shuffle';
					flashvars.scale = '$scale';

					flashvars.classid = 'st_".$this->gid."';
					

					var params = {};
					params.allowFullScreen = 'true';
					
					params.wmode = 'transparent';
					
					var attributes = {};		
					swfobject.embedSWF ('" . plugins_url($path = $slideshowOptions->_pluginSubfolderName . '/showtime/showtime.swf') . "', 'st_" . $this->gid . "', '$width', '$height', '9.0.0', false, flashvars, params, attributes);
				</script>
				
				<div class='showtime' id='st_" . $this->gid . "'>
					$gallery
				</div>
				
				\n";
		else
			$output = "	<div class='gallery' id='gallery_" . $this->gid . "'>
							$gallery
						</div>
					\n";
		
		return $output;
	}
	
	function checkOn( $string ) {
		
		if ( $string == "on" || $string == "true" || $string == "yes" ) {
			return "true";
		} else {
			return "false";
		}
		
	}
	
	function addToHeader() {
		global $slideshowOptions;
		wp_enqueue_script( 'swfobject', plugins_url($path = $slideshowOptions->_pluginSubfolderName . '/showtime/swfobject/swfobject.js'), false, '2.1' );
	}
	
	function encode($x) {
	    return base64_encode(rawurlencode($x));
	}

	
	function wp_gallery_st_own_shortcut($attr) {
		return $this->wp_gallery_st_shortcut($attr, 1);
	}
	
}

?>