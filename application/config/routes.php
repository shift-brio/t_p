<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$user_routes  = [
	'saveDetails','changePass','saveProf','removeProfs','follow'
];
for ($i=0; $i < sizeof($user_routes); $i++) { 
	$route[$user_routes[$i]] = 'user/'.$user_routes[$i];
}
$home_routes  = [
	'postArticle','shareWith','searcher', 'rateUs','loadSupport','askSupport','getCode','recoverAccount'
];
for ($i=0; $i < sizeof($home_routes); $i++) { 
	$route[$home_routes[$i]] = 'home/'.$home_routes[$i];
}
$api_routes  = [
	'likePost','comment','delete','readNotif','saveCountries'
];
for ($i=0; $i < sizeof($api_routes); $i++) { 
	$route[$api_routes[$i]] = 'api/'.$api_routes[$i];
}
$auth_routes = [
	'save_details','logout','logger'
];
for ($i=0; $i < sizeof($auth_routes); $i++) { 
	$route[$auth_routes[$i]] = 'auth/'.$auth_routes[$i];
}
$edit_routes = [
	'saveEdits','editNotify','getNotifs','supportList'
];
for ($i=0; $i < sizeof($edit_routes); $i++) { 
	$route[$edit_routes[$i]] = 'editor/'.$edit_routes[$i];
}
$support_routes = [
	'supportList','uSupport','adminSupport'
];
for ($i=0; $i < sizeof($support_routes); $i++) { 
	$route[$support_routes[$i]] = 'support/'.$support_routes[$i];
}

$col_routes = $this->config->item("tables");
for ($i=0; $i < sizeof($col_routes); $i++) { 
	$route[$col_routes[$i]] = 'home/column/'.$col_routes[$i];
}
/*posts*/
$route['view/(:any)'] = 'home/article/$1';
$route['posts/view/(:any)'] = 'home/article/$1';
$route['article/(:any)'] = 'home/article/$1';
/*users*/
$route['profiles/(:any)'] = 'user/user/$1';
$route['writer/(:any)'] = 'user/user/$1';
/*default*/
$route['default_controller'] = 'home';
/*error*/
$route['404'] = 'error/_404';
$route['404_override'] = 'error/_404';