<?php defined('BASEPATH') or exit('No direct script access allowed');


$route['eazycrudpyrocms/index']						 = 'admin$1';
$route['eazycrudpyrocms/admin/news(:any)?'] 		 = 'admin_news$1';
$route['eazycrudpyrocms/admin/themes(:any)?'] 		 = 'admin_themes$1';
$route['eazycrudpyrocms/admin/widgets(:any)?'] 		 = 'admin_widgets$1';
$route['eazycrudpyrocms/admin/sub_module(:any)?'] 	 = 'admin_sub_module$1';
$route['eazycrudpyrocms/admin/problemlog(:any)?'] 	 = 'admin_Problemlog$1';

?>
