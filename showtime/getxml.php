<?php
	header("content-type:text/xml;charset=utf-8");
	error_reporting(E_ALL);
	
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
	$imgSize=get_option( 'st_imgsize' );
	
	//if ($id)
	//	$galleryID = (int) attribute_escape($result['id']);
	
	
	// DEBUG ONLY !!!!!!!	
	//$galleryID = 303;
	//print_r ($attributes);

	

	echo '<xml>\n';
	echo "<slideshow name='showtime' version='1' generator='showtime wordpress plugin'/>\n";

	if ( $images = get_children(array( 
		'post_parent' => $galleryID,	
		'post_type' => 'attachment', 
		'post_mime_type' => image
	))) ;
	
	foreach( $images as $image ) :  
	
		echo wp_get_attachment_link($image->ID, $imgSize);

		// get attachment in format [thumbnail|medium|full]
		// 
		// <a href=...><img src=... alt=... title=.../></a>
		//
		//
		
	
	endforeach;

	

	echo "</xml>";
	
?>