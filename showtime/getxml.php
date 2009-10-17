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
	
	//global $wpdb;	
	//$siteurl	 = get_option ('siteurl');	
/*
	$galleryID = $_GET['id'];
	$source = $_GET['source'];
	$sourcehd = $_GET['sourcehd'];
	$ord = ($reverse=='on') ? 'DESC' : 'ASC';
*/

	$attr = attribute_escape($_GET['attr']);
	
	$attributes = explode('*', $attr);
		foreach($attributes as $attribute){
			if($attribute){
				$i = strpos($attribute, '^');
				if ($i){
					$key = substr($attribute, 0, $i);
					$val = substr($attribute, $i+1); 
					$result[$key]=$val;
				}
			}
		}
		
		
		
	
	if ($result['id'])
		$galleryID = (int) ($result['id']);
		
	else if ($result['flickr']) {
		$flickr = ($result['flickr']);
		$flickr = decode($flickr);
	}
	
	else if ($result['picasa']) {
		$picasa = ($result['picasa']);
		$picasa = decode($picasa);
	}
	
	$order = 'ASC';
	if ($result['order']=='reverse')
		$order = 'DESC';

	$source = 'medium';
	if ($result['source'])
		$source = $result['source'];

	$sourcehd = 'large';
	if ($result['sourcehd'])
		$sourcehd = $result['sourcehd'];



	echo '<xml generator="showTime WP plugin">'; 
	echo '<options />';

	if ($galleryID) {
		
		
		
	
		if ( $images = get_children(array( 
			'post_parent' => $galleryID,	//null=any
			'post_type' => 'attachment', 
			'post_mime_type' => image,
			'order' => $order,
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
		
		
		
		
	
	} elseif ($flickr) {
		if (!function_exists('MagpieRSS')) { // Check if another plugin is using RSS, may not work
			include_once (ABSPATH . WPINC . '/rss.php');
			error_reporting(E_ALL);
		}

		$rss = @ fetch_rss($flickr);
		
		if ($rss) {
	    	$imgurl = "";
			$items = array_slice($rss->items, 0, 30);
			
			if ($rss->feed_type == "RSS") {
				$content = "summary";
			} else {
				$content = "atom_content";
			}
			
	    	foreach ( $items as $item ) {
				echo '<img ';
				if ( $item['title'] )
					echo 'title="'.htmlspecialchars($item['title']).'" ';
				
				preg_match('<img src="([^"]*)" [^/]*/>', $item[$content],$imgUrlMatches);
				$imgurl = $imgUrlMatches[1];
				$imgurl = str_replace("_m.jpg", ".jpg", $imgurl);
	
				echo 'src="'.$imgurl.'" ';
				echo '/>';
			
			}
	     }
	     



	} elseif ($picasa) {


		class PicasaRss {
		 
		    private $xml;
		 
		    function __construct ($rss) {
		        $xmlstr = file_get_contents ($rss);
		        $this->xml = new SimpleXMLElement($xmlstr);
		    }
		 
		    function get () {
		        $res = array ();
		        foreach ($this->xml->channel->item as $item) {
		            $image = new PicasaImg ((string) $item->title, (string) $item->enclosure['url']);
		            $res[] = $image->get ();
		        }
		        return $res;
		    }
		 
		    function title () {
			return (string) $this->xml->channel->title;
		    }
		 
		    function description () {
			return (string) $this->xml->channel->description;
		    }
		 
		}
		 
		class PicasaImg {
		 
		    var $title;
		    var $url;
		 
		    function __construct ($title, $url) {
		        if (!defined ("THUMB_SIZE")) define ("THUMB_SIZE", "s144");
		        if (!defined ("IMAGE_SIZE")) define ("IMAGE_SIZE", "s800");
		        if (!defined ("FULL_SIZE")) define ("FULL_SIZE", "s1600");
		        $this->title = $title;
		        $this->url = $url;
		    }
		 
		    function getURL ($size) {
		        $url = parse_url ($this->url);
		        $path = split ('/', $url['path']);
		        $path[count ($path) - 1] = $size . '/' . $path[count ($path) - 1];
		        return $url['scheme'] . '://' . $url['host'] . '/' . implode ('/', $path);
		    }
		 
		    function get () {
		        return array (
		            'title' => $this->title,
		            'description' => $this->description,
		            'image' => $this->getURL (IMAGE_SIZE),
		            'hd' => $this->getURL (FULL_SIZE),
		        );
		 
		    }
		}
		

		$prs = new PicasaRss ($picasa);
		 
		
		 
		foreach ($prs->get () as $item) {
		 
		        echo '<img src="'.htmlspecialchars($item['image']).'" title="'.htmlspecialchars($item['title']).'" alt="'.htmlspecialchars($item['description']).'" />';
		        echo '<hd src="'.htmlspecialchars($item['hd']).'" />';		 
		}




	}

	echo "</xml>";
	
?>