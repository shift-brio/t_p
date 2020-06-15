<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Loader extends CI_Loader
{    
    function view($view, $vars = array(), $return = FALSE)
    {
        $CI =& get_instance();

	    return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_prepare_view_vars($vars), '_ci_return' => $return));         
    }
    
    /*$CI =& get_instance();
    $CI->load->library("user_agent");
    $CI->load->model('posts_database');
	if (!isset($_SESSION['userid'])) {	
		$user = 'visitor';
    	$this->log($user);        	
    }
    private function log($user){ 
      $CI =& get_instance(); 	  
	  $p = $CI->posts_database->c_ip($CI->input->ip_address());
	  if ($p == true) {
	    $mode = 'repeat';
	  }
	  else{
	    $mode = 'original';
	  }  
	  $data['ip'] = $CI->input->ip_address();
	  $data['time'] = time();
	  $data['user'] = $user;
	  $data['id']   = '';
	  $data['item'] = 'site';
	  $data['type'] = 'access';
	  $data['mode'] = $mode;
	  $CI->posts_database->add('logs',$data);	  
	}*/
}  

?>