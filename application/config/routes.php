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
$route['default_controller'] = 'Admin_user/index';
$route['logout'] = 'Admin_user/logout';
$route['login'] = 'Admin_user/login';
$route['signup'] = 'Admin_user/signup';
$route['device-list'] = 'Admin_device/index';
$route['feedback'] = 'Admin_feedback/index';
$route['device-reading-view'] = 'Admin_device/get_device_reading_view';
$route['choose-chart'] = 'Admin_chart_selection/index';
$route['chart-analysis'] = 'Admin_chart_selection/get_chart_analysis_view';


#==============================================================================
############################## API CALL ROUTES ################################
#==============================================================================
$route['store-reading/(:any)/(:any)/(:any)']['get'] = 'Admin_device_api/store_device_reading/$1/$2/$3';
$route['store-gps-reading/(:any)/(:any)/(:any)/(:any)']['get'] = 'Admin_device_api/store_gps_device_reading/$1/$2/$3/$4';
$route['get-device-reading/(:any)/(:any)/(:any)']['get'] = 'Admin_device_api/get_device_reading/$1/$2/$3';
$route['get-device-reading/(:any)/(:any)']['get'] = 'Admin_device_api/get_device_reading/$1/$2';
$route['404_override'] = '';

$route['translate_uri_dashes'] = FALSE;



