<?php
	header("content-type:text/xml;charset=utf-8");

	echo '<?xml version="1.0" encoding="utf-8" ?>';
	echo '<slideshow generator="showTime WP plugin">'; 

	error_reporting(E_NONE);
	ini_set("display_errors", 'OFF');
	
	$root = (dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	
	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');
	} else {
		if (!file_exists($root.'/wp-config.php'))
			die($root.'/wp-load.php not found</xml>');
		require_once($root.'/wp-config.php');
	}
	
	
	function cleanup($s)
	{
		$s = str_replace("&", "&amp;",$s);
		$s = str_replace(">", "&gt;",$s);
		$s = str_replace("<", "&lt;",$s);
		$s = str_replace('"', "&quot;",$s);
		$s = str_replace("'", "&apos;",$s);
		return $s;
	}	
	
	
	function get_out_now() { exit; }
	function decode($x) {
	    return html_entity_decode(rawurldecode(base64_decode($x)));
	} 
	add_action('shutdown', 'get_out_now', -1);
	


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
		

	
	$galleryID = $flickr = $picasa = '';
	
	if (!empty($result['id']))
		$galleryID = ($result['id']);
		
	else if (!empty($result['flickr'])) {
		$flickr = ($result['flickr']);
		$flickr = decode($flickr);
	}
	
	else if (!empty($result['picasa'])) {
		$picasa = ($result['picasa']);
		$picasa = decode($picasa);

		if(!preg_match('/alt=/', $picasa))
			$picasa .= '&alt=rss';
	}
	
	$order = 'ASC';
	if (!empty($result['order']))
		$order = $result['order'] == 'reverse' ? 'DESC' : 'ASC';

	$source = 'medium';
	if (!empty($result['source']))
		$source = $result['source'];

	$sourcehd = 'large';
	if (!empty($result['sourcehd']))
		$sourcehd = $result['sourcehd'];

	$link = '';
	if (!empty($result['link']))
		$link = $result['link'];



	$css = get_option( 'st_css' );
	echo "<css>".cleanup($css)."</css>";


	if ($galleryID!='') {
		
		
		/*
		
		get attachments in format [thumbnail|medium|full]
		 
		<img src="" hdsrc="" title="" caption="" href="" />

		
		*/	
		if ( strtolower($galleryID) == 'all' ) $galleryID = 'null';	

	
		if ( $images = get_children(array( 
			'post_parent' => $galleryID,
			'post_type' => 'attachment', 
			'post_mime_type' => 'image',
			'order' => $order,
			'orderby' => 'menu_order'
		))) 
	
			foreach( $images as $image ) :  
			
				$src=wp_get_attachment_image_src($image->ID, $source);
				//print_r ($image);
				switch ($link)
				{
					case 'image': 		$href = $image->guid; break;
					case 'parent':		$href = get_permalink( $image->post_parent ); break;
					case 'post':		$href = get_attachment_link( $image->ID ); break;
					case 'description':	$href = $image->post_content; break;
					default : 			$href = '';
				}
				
				/*
				$meta = wp_get_attachment_metadata($image->ID);
			 	if($meta) {
				     $caption = '<ul>';
				     foreach($meta['image_meta'] as $key => $value) {
				         $caption .= '<li>' . $key . ': <strong>' . $value . '</strong></li>';
				     }
				     $caption .= '</ul>';
				}
				*/
				
				$caption = $image->post_excerpt;
							
				echo "<img ";
				echo "src='" . cleanup($src[0]) ."' ";
				echo "hdsrc='" . cleanup($image->guid) ."' ";
				echo "title='" . cleanup($image->post_title) ."' ";
				echo "caption='" . cleanup($caption) ."' ";
				echo "href='" . cleanup($href) ."' ";		
				echo "/>";
			
	
			endforeach;
		
		else
			
			echo '<error>No image found for post id '.$galleryID.'</error>';
		
		
		
		
	
	} elseif ($flickr!='') {
		if (!function_exists('MagpieRSS')) { // Check if another plugin is using RSS, may not work
			include_once (ABSPATH . WPINC . '/rss.php');
			error_reporting(E_ALL);
		}

		$rss = @ fetch_rss($flickr);
		
		if ($rss) {
	    	$imgurl = "";
			$items = array_slice($rss->items, 0, 100);
			
			if ($rss->feed_type == "RSS") {
				$content = "summary";
			} else {
				$content = "atom_content";
			}
			
	    	foreach ( $items as $item ) {
				echo '<img ';
				if ( !empty($item['title']) )
					echo 'title="'.htmlspecialchars($item['title']).'" ';
		
			/*
				if ( !empty($item['published']) )
				{
					$alt = strip_tags($item['published']);
					echo 'alt="'.$alt.'" ';
				}
			*/
				preg_match('<img src="([^"]*)" [^/]*/>', $item[$content],$imgUrlMatches);
				$imgurl = $hdurl = $imgUrlMatches[1];
				$imgurl = str_replace("_m.jpg", ".jpg", $imgurl);
//				$hdurl = str_replace("_m.jpg", "_b.jpg", $hdurl);
				
/*
$open = fopen ($hdurl, "r");
if ($open) {} else { $hdurl = $imgurl; }
fclose ($open);
*/
	
				echo 'src="'.$imgurl.'" ';
				echo '/>';
//		        echo '<hd src="'.$hdurl.'" />';				
			}
	     }
	     



	} elseif ($picasa!='') {


		class PicasaRss {
		 
		    var $xml;
		    
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
		            //'description' => $this->description,
		            'image' => $this->getURL (IMAGE_SIZE),
		            'hd' => $this->getURL (FULL_SIZE),
		        );
		 
		    }
		}
		

		$prs = new PicasaRss ($picasa);
		 
		
		 
		foreach ($prs->get () as $item) {
		 
		        echo '<img src="'.htmlspecialchars($item['image']).'" title="'.htmlspecialchars($item['title']).'" ';
		        echo    'hdsrc="'.htmlspecialchars($item['hd']).'" />';		 
		}




	}

	echo "</slideshow>";
	
?>