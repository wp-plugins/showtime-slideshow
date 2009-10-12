<?php
	header("content-type:text/xml;charset=utf-8");
	//error_reporting(E_ALL);
	
	$root = (dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	
//	echo $root;
	
	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');
	} else {
		if (!file_exists($root.'/wp-config.php'))
			die;
		require_once($root.'/wp-config.php');
	}
	
	function get_out_now() { exit; }
	function decode($x) {
	    return html_entity_decode(rawurldecode(base64_decode($x)));
	} 
	add_action('shutdown', 'get_out_now', -1);
	

	$galleryID = $_GET['id'];
	
	//if ($id)
	//	$galleryID = (int) attribute_escape($result['id']);
	
	
	// DEBUG ONLY !!!!!!!	
	//$galleryID = 303;
	//print_r ($attributes);

		//global $post;
		//global $wp_version;
		//global $stOptions;
		
	
	$height = get_option( 'st_height' );
	$width = get_option( 'st_width' );
	$transition = get_option( 'st_transition' );
	$transitiontime = get_option( 'st_transitiontime' );
	$transitionease = get_option( 'st_easeFunc' ).get_option( 'st_ease' );
	$rotationtime = get_option( 'st_rotatetime' );
	
	$showcontrols = get_option( 'st_showcontrols' );
	$fullscreen = get_option( 'st_fullscreen' );
	$showtext = get_option( 'st_showtext' );
	$autoplay = get_option( 'st_autoplay' );
	$shuffle = get_option( 'st_shuffle' );
	$reverse = get_option( 'st_reverse' );
	$scale = get_option( 'st_scalemode' );

	$bgcolor=get_option( 'st_bgcolor' );
	$imgSize=get_option( 'st_imgsize' );
	$imgFullSize=get_option( 'st_imgfullsize' );
	

	extract(shortcode_atts(array(
		'width'				=> $width,
		'height'         	=> $height,
		'rotationtime'		=> $rotationtime,
		'transition'		=> $transition,
		'transitiontime'	=> $transitiontime,
		'transitionease'	=> $transitionease,
		'autoplay'			=> $autoplay,
		'showcontrols'		=> $showcontrols,
		'fullscreen'		=> $fullscreen,
		'showtext'			=> $showtext,
		'shuffle'			=> $shuffle,
		'reverse'			=> $reverse,
		'scale'				=> $scale,
		'source'			=> $imgSize,
		'sourcehd'			=> $imgFullSize,
		'bgcolor'			=> $bgcolor
	), $attr));

	
	//$order = checkOn($reverse) ? 'DESC' : 'ASC';


	

	echo '<xml generator="showTime WP plugin">'; 
	echo '<options width="'.$width.'" height="'.$height.'" transition="'.$transition.'" transitionease="'.$transitionease.'" rotationtime="'.$rotationtime.'" transitiontime="'.$transitiontime.'" autoplay="'.$autoplay.'" showcontrols="'.$showcontrols.'" fullscreen="'.$fullscreen.'" showtext="'.$showtext.'" shuffle="'.$shuffle.'" scale="'.$scale.'"  />';

	if ( $images = get_children(array( 
		'post_parent' => $galleryID,	//null=any
		'post_type' => 'attachment', 
		'post_mime_type' => image,
		'order' => 'ASC',
		'orderby' => 'menu_order'
	))) ;

	
	/*
	
	 get attachments in format [thumbnail|medium|full]
	 
	 <img width="" height="" src="" class="" alt="" title=""/>
	 <hd src=""/>
	
	*/

	foreach( $images as $image ) :  
	
		echo wp_get_attachment_image($image->ID, $imgSize);

		$hdsrc = wp_get_attachment_image_src($image->ID, $imgFullSize);
		echo '<hd src="'.$hdsrc[0].'" />';

	endforeach;

	

	echo "</xml>";
	
?>