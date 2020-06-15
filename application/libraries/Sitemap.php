<?php defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	 * Talkpoint Sitemap Gen 1.0.0
	 */
	define("URL", "https://www.talkpoint.online/");
	define ("OUTPUT_FILE", URL."sitemap.xml");
	define ("FREQUENCY", "daily");
	define ("PRIORITY", "0.5");
	define("main", '');			
	class  Sitemap 
	{		
		function __construct()
		{
			$CI = &get_instance();
			$CI->load->model("PostDatabase");			
			$CI->load->helper("url_helper");											
		}
		static function index(){
			$main  = '';			
			$main .= Sitemap::file_top();
			$main .= Sitemap::get_urls();
			Sitemap::prep_file($main);
			
		}
		static function file_top(){			
			$head = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
                 "<!-- Created with Talkpoint Sitemap Gen -->\n" .
                 "<!-- Date: " . date ("Y-m-d H:i:s") . " -->\n" .
                 "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n" .
                 "        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n" .
                 "        xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n" .
                 "        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n" .
                 "  <url>\n" .
                 "    <loc>" . URL . "</loc>\n" .
                 "    <changefreq>" . FREQUENCY . "</changefreq>\n" .
                 "  </url>\n";
            if (strlen(main) > 0) {
            	define('main', '');
            }
            return $head;
		}
		static function set_url($url  = false){
			if(!$url){return '';}
			$url_set =   "  <url>\n" .
		                 "    <loc>".$url."</loc>\n" .
		                 "    <changefreq>" . FREQUENCY . "</changefreq>\n" .
		                 "  </url>\n";
		    return $url_set;
		}
		static function append_to_main($url = false,$main){
			if ($url) {
				$main .= $url;
			}
		}
		static function get_urls(){
			$CI = &get_instance();
			$posts = $CI->PostDatabase->get_data('tp_post',false,false,'state',1);
			$x = '';
			if ($posts) {
				foreach ($posts as $post) {
					$u = URL."view/".$post['identifier'];
					$prep = Sitemap::set_url($u);
					$x .= $prep;									
				}
			}
			return $x;	
		}
		static function prep_file($data = false, $type = 'all'){
			if ($data) {				
				$file  = fopen ('./sitemap.xml', "w");
			    fwrite($file, $data);
				fclose($file);
			}else{
				return false;
			}
		}
	}