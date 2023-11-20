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

//Front end routing start
$route['default_controller'] 				= 'welcome';
//$route['default_controller'] 				= 'Admin_dashboard/login';
//$route['home'] 							= 'website/Home';
$route['home/add_to_cart'] 					= 'website/Home/add_to_cart';
$route['home/delete_cart/(:any)'] 			= 'website/Home/delete_cart/$1';
$route['home/update_cart'] 					= 'website/Home/update_cart';
$route['home/apply_coupon'] 				= 'website/Home/apply_coupon';
$route['checkout'] 							= 'website/Home/checkout';
$route['view_cart'] 						= 'website/Home/view_cart';
$route['submit_checkout'] 					= 'website/Home/submit_checkout';
$route['category/(:any)/(:any)']			= 'website/Category/category_product/$1/$2';
$route['category/p/(:any)/(:any)']			= 'website/Category/category_product/$2';
$route['category/(:any)'] 					= 'website/Category/category_product/$1';
$route['product_details/(:any)/(:num)']		= 'website/Product/product_details/$2';
$route['category_product_search'] 			= 'website/Category/category_product_search';
$route['category_product/(:any)'] 			= 'website/Category/category_wise_product/$1';
$route['category_product/(:any)/(:num)'] 	= 'website/Category/category_wise_product/$1/$2';
$route['brand_product/list/(:any)']         = 'website/Product/brand_product/$1';
//Front end routing end

//Admin Dashboard Start
$route['admin'] 			= 'Admin_dashboard/login';
$route['autoupdate'] 			= 'Autoupdate';
$route['backend/autoupdate/update'] 			= 'Autoupdate/update';
$route['forget_admin_password'] 		= 'Admin_dashboard/forget_admin_password'; //ajax call
$route['admin_password_reset/(:num)'] = 'Admin_dashboard/admin_password_reset_form/$1';//after send email get link
$route['admin_password_update'] 		= 'Admin_dashboard/admin_password_update';

//Admin Dashboard End

//Customer dashboard and profile start
$route['forget_password_form'] 		= 'website/customer/Login/forget_password_form'; //martbd
$route['forget_password'] 		= 'website/customer/Login/forget_password'; //ajax call
$route['password_reset/(:num)'] = 'website/customer/Login/password_reset_form/$1';//after send email get link
$route['password_update'] 		= 'website/customer/Login/password_update';
$route['login'] 				= 'website/customer/Login';
$route['logout'] 				= 'website/customer/Customer_dashboard/Logout';
$route['do_login'] 				= 'website/customer/Login/do_login';
$route['signup'] 				= 'website/customer/Signup';
$route['user_signup'] 			= 'website/customer/Signup/user_signup';
$route['customer/customer_dashboard'] 	= 'website/customer/Customer_dashboard';
$route['customer/customer_dashboard/edit_profile'] 	 = 'website/customer/Customer_dashboard/edit_profile';
$route['customer/customer_dashboard/update_profile'] = 'website/customer/Customer_dashboard/update_profile';
$route['customer/customer_dashboard/change_password_form'] 	= 'website/customer/Customer_dashboard/change_password_form';
$route['customer/customer_dashboard/change_password'] = 'website/customer/Customer_dashboard/change_password';
$route['customer/customer_dashboard/wishlist'] = 'website/customer/customer_dashboard/wishlist';
$route['customer/customer_dashboard/wishlist_delete/(:any)'] = 'website/customer/customer_dashboard/wishlist_delete/$1';
//Customer dashboard and profile end

//Customer order start
$route['customer/order'] 		= 'website/customer/Corder/new_order';
$route['customer/insert_order'] = 'website/customer/Corder/insert_order';
$route['customer/order/manage_order'] = 'website/customer/Corder/manage_order';
//Customer order end

//Customer invoice start
$route['customer/invoice'] 	= 'website/customer/Cinvoice/manage_invoice';
$route['customer/invoice/invoice_inserted_data/(:any)'] = 'website/customer/Cinvoice/invoice_inserted_data/$1';
//Customer invoice end

//Link page 
$route['about_us'] 			= 'website/Setting/about_us';
$route['contact_us'] 		= 'website/Setting/contact_us';
$route['delivery_info'] 	= 'website/Setting/delivery_info';
$route['privacy_policy'] 	= 'website/Setting/privacy_policy';
$route['terms_condition'] 	= 'website/Setting/terms_condition';
$route['help'] 				= 'website/Setting/help';
$route['submit_contact'] 	= 'website/Setting/submit_contact';
//Link page end

$route['404_override'] 			= '';
$route['translate_uri_dashes'] 	= FALSE;



// store keeper
$route['store_keeper/Cproduct/(:any)'] = 'store_keeper/Cproduct/$1';
$route['store_keeper/Cproduct/(:any)/(:any)'] = 'store_keeper/Cproduct/$1/$1';
// $route['store_keeper/Cqrcode/(:any)'] = 'store_keeper/Cqrcode/$1';
// $route['store_keeper/Cqrcode/(:any)/(:any)'] = 'store_keeper/Cqrcode/$1/$1';