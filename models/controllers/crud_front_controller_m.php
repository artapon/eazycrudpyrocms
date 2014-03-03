<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Crud_Front_Controller_M extends MY_Model
{
		
	public function create_public_controller($data = array())
	{
		$file_detail =
	 "<?php defined('BASEPATH') or exit('No direct script access allowed');
	 
	 
class ".ucfirst($data['module_slug'])." extends Public_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		\$this->load->model('".$data['module_slug']."_m');
		\$this->load->model('".$data['module_slug']."_front_m');
		\$this->lang->load('".$data['module_slug']."');
		\$this->load->library('pagination');
		
	}
	
	public function index()
	{
		
		\$config = array();
		\$config['base_url']			= site_url(\"".$data['module_slug']."/page/\");
		\$config['total_rows']			= count(\$this->".$data['module_slug']."_front_m->get_".$data['module_slug']."_all());
		\$config['per_page']			= 5;
		\$config['uri_segment']			= 2;
		\$config['display_pages'] 		= TRUE;
		\$config['first_link'] 			= TRUE;
		\$config['last_link'] 			= TRUE;
		\$this->pagination->initialize(\$config);
		\$pagination					= \$this->pagination->create_links();\n";
		
		
		if($data['enable_cache']=='yes'){
			$file_detail .= 
				"\$".$data['module_slug']." = \$this->pyrocache->model('".$data['module_slug']."_front_m','get_".$data['module_slug']."_all',array(\$config['per_page']));\n";
		}else{
			$file_detail .= 
				"\$".$data['module_slug']." = \$this->".$data['module_slug']."_front_m->get_".$data['module_slug']."_all(\$config['per_page']);\n";
		}
	
		$file_detail .= 
		"\$this->template
				->title(\$this->module_details['name'])
				->set_breadcrumb(lang('".$data['module_slug'].".name'))
				->set('".$data['module_name']."',\$".$data['module_slug'].")
				->set('pagination', \$pagination)
				->build('index');
	}
	
	public function page(\$page = 0)
	{
		
		\$config = array();
		\$config['base_url']			= site_url(\"".$data['module_slug']."/page/\");
		\$config['total_rows']			= count(\$this->".$data['module_slug']."_front_m->get_".$data['module_slug']."_all());
		\$config['per_page']			= 5;
		\$config['uri_segment']			= 3;
		\$config['display_pages'] 		= TRUE;
		\$config['first_link'] 			= TRUE;
		\$config['last_link'] 			= TRUE;
		\$this->pagination->initialize(\$config);
		\$pagination					= \$this->pagination->create_links();\n";
		
		if($data['enable_cache'] == 'yes')
		{
			
			$file_detail .= 
				"\$".$data['module_slug']." = \$this->pyrocache->model('".$data['module_slug']."_front_m','get_".$data['module_slug']."_all',array(\$config['per_page'],\$page));\n";
				
		}else{
			
			$file_detail .= 
				"\$".$data['module_slug']." = \$this->".$data['module_slug']."_front_m->get_".$data['module_slug']."_all(\$config['per_page'],\$page);\n";
				
		}
		
		$file_detail .="
		\$this->template
			->title(\$this->module_details['name'])
			->set_breadcrumb(lang('".$data['module_slug'].".name'))
			->set('".$data['module_slug']."',\$".$data['module_slug'].")
			->set('pagination', \$pagination)
			->build('index');
	}
	
}";
		
		return $file_detail;
	}	
		
}
	