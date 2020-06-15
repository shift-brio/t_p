<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class HTMLparser
{
	public $url  		= NULl;
	public $data 		= NULL;
	public $callback 	= FALSE;
	function __construct($callback = FALSE)
	{
		if ($callback)
		{
			$this->callback = $callback;
		}
	}

	function parse(){
		if ($url == NULL) {
			return null;
		}
		else{
			if (!isset($row)) {
				$raw = file_get_contents($url);
			}

			$xml = new  SimpleXmlElement($raw)
		}
	}
	function set_url($url)
	{
		$url = $url;
	}
	function get_data()
	{

	}
}
 ?>