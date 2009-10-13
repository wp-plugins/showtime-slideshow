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
	$source = $_GET['source'];
	$sourcehd = $_GET['sourcehd'];
	$ord = ($reverse=='on') ? 'DESC' : 'ASC';


	

	echo '<xml generator="showTime WP plugin '.$attr.'">'; 
	echo '<options />';

	if ( $images = get_children(array( 
		'post_parent' => $galleryID,	//null=any
		'post_type' => 'attachment', 
		'post_mime_type' => image,
		'order' => $ord,
		'orderby' => 'menu_order'
	))) ;

	
	/*
	
	 get attachments in format [thumbnail|medium|full]
	 
	 <img width="" height="" src="" class="" alt="" title=""/>
	 <hd src=""/>
	
	*/

	foreach( $images as $image ) :  
	
		echo wp_get_attachment_image($image->ID, $source);

		$hdsrc = wp_get_attachment_image_src($image->ID, $sourcehd);
		echo '<hd src="'.$hdsrc[0].'" />';

	endforeach;

	

	echo "</xml>";
	
?>