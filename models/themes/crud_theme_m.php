<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Crud_theme_m extends MY_Model{
	
	public function theme_details_structure($input = array())
	{
$file_detail = 
"<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_".ucfirst($input['slug'])." extends Theme {
			
		public \$name 			= '".ucfirst($input['name'])."';
   		public \$author 		= '".$input['author']."';
    	public \$website 		= '".$input['website']."';
    	public \$description 	= '".$input['description']."';
    	public \$version 		= '".$input['version']."';
    	public \$options 		= array(
    		'show_breadcrumbs' => array(
    			'title' 	=> 'Show Breadcrumbs',
				'description'   => 'Would you like to display breadcrumbs?',
				'default'       => 'yes',
				'type'          => 'radio',
				'options'       => 'yes=Yes|no=No',
				'is_required'   => true),
			);
    	
    	/*------ Example for theme Other options-----------
    	Ex Use
    	
    	{{ if theme:options:slider == 'yes' }}
        	<div class=\"under-container\">
			{{ theme:partial name='slider' }}
			</div>
        {{ endif }}
    	
    	public \$options = array(

        'slider' => array(
            'title'         => 'Layout Product',
            'description'   => 'Show Hide Slider in Products',
            'default'       => 'yes',
            'type'          => 'radio',
            'options'       => 'yes=Yes|no=No',
            'is_required'   => TRUE
        ),
      );*/
			
}/* End of file theme.php */";
		
		return $file_detail;
	}
	
	public function default_structure()
	{
		$file_detail ="
<!DOCTYPE html>
<!--[if lt IE 9]>             <html class=\"no-js ie lt-ie9\" lang=\"en-US\"><![endif]-->
<!--[if IE 9]>                <html class=\"no-js ie ie9\" lang=\"en-US\">   <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class=\"no-js no-ie\" lang=\"en-US\">    <!--<![endif]-->

	<!-- Head section -->
<head>
	 {{ theme:partial name='metadata' }}
</head>
<body>
Demo theme by Pyrocms Develop Helper
<nav>
	<ul>
		{{ navigation:links group=\"header\" }}
	</ul>
</nav>
<div class=\"body-content\">
{{ template:body }}
</div>




{{ theme:partial name=\"footer\" }}
</body>
</html>
		
		";
		return $file_detail;
	}
	
	public function metadata_structure($input = array())
	{
		$file_detail = "
<meta charset=\"utf-8\">

  	<!-- You can use .htaccess and remove these lines to avoid edge case issues. -->
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">

<title>{{ settings:site_name }} &raquo; {{ template:title }}</title>

<meta name=\"author\" content=\"".$input['author']."\">
<meta name=\"description\" content=\"\">
<meta name=\"keywords\" content=\"\">

  <!-- Mobile viewport optimized -->
 <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">		

<script type=\"text/javascript\">
	var SITE_URL	= \"{{url:base}}\";
	var THEME_PATH = \"{{theme:path}}\";
</script>
<!-- Use Html5Shiv in order to allow IE render HTML5 -->
<!--[if IE]><script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script><![endif]-->

<!--Jquery 1.9.1.min.js -->
{{ theme:js file=\"jquery.js\"}}
{{ theme:js file=\"fixed-old-js-version.js\"}}


{{ theme:css file=\"".$input['slug']."_main.css\"}}
<!-- this line include js or css in module -->
{{ template:metadata }}

<!-- Google Analytics -->
{{ integration:analytics }}
		
		";
		return $file_detail;
	}

	public function breadcrumbs_structure()
	{
		$file_detail = "
{{ if template:has_breadcrumbs }}
    {{ if theme:options:show_breadcrumbs == 'yes' }}
        <p class=\"breadcrumbs\">
        {{ template:breadcrumbs }}
			{{ if uri }}
				{{ url:anchor segments=uri title=name }}&nbsp;>&nbsp;
			{{ else }}
				<span class=\"current\">{{ name }}</span>
			{{ endif }}
		{{ /template:breadcrumbs }}
		</p>
	{{ endif }}
{{ endif }}
		";
		
		return $file_detail;
		
		
	}
}
	