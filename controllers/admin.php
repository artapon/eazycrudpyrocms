<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Create Base Module
 * 
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Admin extends Admin_Controller {
	
	protected $section = 'CreateBaseModule';
	
	protected $validation_rules = array(
		array(
			'field'=>'module_name',
			'rules'=>'trim|required'),
			
		array(
			'field'=>'module_version',
			'rules'=>'trim|required'
			),
		array(
			'field'=>'database_table',
			'rules'=>'trim|required'),
		array(
			'field'=>'module_version',
			'rules'=>'trim|required')
		);
	
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('eazycrudpyrocms');
		$this->load->model('detail_modules_m');
		$this->load->model('controllers/crud_admin_controller_m');
		$this->load->model('controllers/crud_front_controller_m');

		$this->load->model('models/crud_admin_model_m');
		$this->load->model('models/crud_front_model_m');
		$this->load->model('views/crud_view_m');
		$this->load->model('languages/lang_m');
		

		$this->load->library('form_validation');
		$this->load->library('easy_database_manage');
		$this->load->library('crud_field_type');
		$this->load->library('crud_script');
		
	}
	
	public function index()
	{
		$this->template
			->set_partial('shortcuts', 'admin/partials/shortcuts')
			->append_js('module::eazycrudpyrocms.js')
			->append_css('module::eazycrudpyrocms.css')
			->build('admin/menu');
	}
	
	public function base_crud()
	{
		
		$list_table = Easy_Database_Manage::get_list_table();
		
		$this->template
			->set_partial('shortcuts', 'admin/partials/shortcuts')
			->set('list_table',$list_table)
			->build('admin/form');
	}
	public function create()
	{
		$this->form_validation->set_rules($this->validation_rules);
		if($this->form_validation->run()){
			
			$modulename 					= strtolower($this->input->post('module_name'));
			$moduleversion					= $this->input->post('module_version');
			$database_table					= $this->input->post('database_table');
			$position_folder				= $this->input->post('position');
			$role							= $this->input->post('role');
			$table_st_exists_file			= Easy_Database_Manage::check_structure_exists_file($database_table);
			$enable_cache					= $this->input->post('enable_cache');
			$slug							= $this->input->post('slug');
			
			if(file_exists('addons/default/modules/'.$slug)||file_exists('addons/shared_addons/modules/'.$slug)){
				
				$error_msg = "Module ".ucfirst($modulename)." already exists ";
				
				$this->session->set_flashdata('error',$error_msg);
				
				redirect('admin/eazycrudpyrocms/base_crud');
				
			}else{
				// check table is exists
				if(!Easy_Database_Manage::check_table_exists($database_table))
				 {
					 $error_msg = "Table ".$database_table." doesn't exist ";
					 
					 $this->session->set_flashdata('error',$error_msg);
					 
					 redirect('admin/eazycrudpyrocms/base_crud');
				 }
				 
				$module_path			= "addons/".$position_folder."/modules/".$slug;
				
				mkdir($module_path,0775);
				mkdir($module_path.'/config',0775);
				mkdir($module_path.'/controllers',0775);
				mkdir($module_path.'/css',0775);
				mkdir($module_path.'/css/images',0775);
				mkdir($module_path.'/js',0775);
				mkdir($module_path.'/language',0775);
				mkdir($module_path.'/models',0775);
				mkdir($module_path.'/views',0775);
				mkdir($module_path.'/language/english',0775);
				mkdir($module_path.'/language/thai',0775);
				mkdir($module_path.'/views/admin',0775);
				mkdir($module_path.'/views/admin/'.$slug,0775);
				mkdir($module_path.'/views/admin/'.$slug.'/partials',0775);
				mkdir($module_path.'/views/admin/'.$slug.'/tables',0775);
				//mkdir($module_path.'/uploads',777);
				
				$routes_file		= fopen($module_path.'/config/routes.php',"w",0775);
				fwrite($routes_file,$this->detail_modules_m->create_routes($modulename));

				/// Controller Files
				$data = array(
						'redirect_url'				=> "admin/".$slug,
						'class_prefix'				=> "admin",
						'module_name'				=> $modulename,
						'module_slug'				=> $slug,
						'base_module'				=> Null,
						'sub_module'				=> "",
						'database_table'			=> $database_table,
						'uri_segment'				=> 4,
						'module_role'				=> $role,
						'check_db_table_exists_file'=> $table_st_exists_file,
						'module_position'			=> $position_folder,
						'enable_cache'				=> $enable_cache
						
				);
				
				
				$css_files		 				= fopen($module_path.'/css/'.$slug.'.css',"w",0775);
				fwrite($css_files,"maincss");
				
				$js_files						= fopen($module_path.'/js/'.$slug.'.js',"w",0775);
				fwrite($js_files,"mainjs");
				
				$eng_language_files				= fopen($module_path.'/language/english/'.$data['module_slug'].'_lang.php',"w",0775);
				fwrite($eng_language_files,$this->lang_m->create_lang($data));
				
				$thai_language_files			= fopen($module_path.'/language/thai/'.$slug.'_lang.php',"w",0775);
				fwrite($thai_language_files,$this->lang_m->create_lang($data));
					
				$eng_permission_language_files	= fopen($module_path.'/language/english/permission_lang.php',"w",0775);
				fwrite($eng_permission_language_files,$this->lang_m->create_permission_lang($data['module_slug'],"basemodule",'en'));
					
				$thai_permission_language_files	= fopen($module_path.'/language/thai/permission_lang.php',"w",0775);
				fwrite($thai_permission_language_files,$this->lang_m->create_permission_lang($data['module_slug'],"basemodule",'th'));
				
				$this->create_controller_files($data,$module_path);
				
				// Create Model Files
				$this->create_models_files($data,$module_path);
				
				// Create View Files
				$this->create_view_files($data,$module_path,$position_folder);
				
				$detail_files				= fopen($module_path.'/details.php',"w",0775);
				fwrite($detail_files,$this->detail_modules_m->create_details($data,$_POST));
				
				$plugin_file				= fopen($module_path.'/plugin.php',"w",0775);
				fwrite($plugin_file,$this->detail_modules_m->create_plugin($modulename));
//
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/js/blog_form.js",					$module_path."/js/form.js");
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/tablsorter/css/images/asc.gif"		,$module_path."/css/images/asc.gif");
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/tablsorter/css/images/bg.gif"		,$module_path."/css/images/bg.gif");
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/tablsorter/css/images/desc.gif"		,$module_path."/css/images/desc.gif");
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/tablsorter/css/table_style.css"		,$module_path."/css/table_style.css");
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/tablsorter/js/jquery.tablesorter.js"	,$module_path."/js/jquery.tablesorter.js");
				
				
				$this->session->set_flashdata('success',"Create Module ".$modulename." Success");
				
				redirect('admin/addons/modules');
		
			}
		}
			
		$admin_crud = new stdClass;
		
		foreach	($this->validation_rules as $rule)
		{
			$admin_crud->{$rule['field']} = set_value($rule['field']);
		}
			
		$this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';

		$list_table = Easy_Database_Manage::get_list_table();
			
		$this->session->set_flashdata('error',lang('eazycrudpyrocms.create_error'));
			
		$this->template
				->append_js('jquery/jquery.tagsinput.js')
				->append_css('jquery/jquery.tagsinput.css')
				->set('list_table',$list_table)
				->set('admin_crud',$admin_crud)
				->build('admin/form');
			
	}
	
	private function create_controller_files($data = array(),$module_path)
	{
		
		$controller_admin_files	= fopen($module_path.'/controllers/admin.php',"w",0775);
		fwrite($controller_admin_files,$this->crud_admin_controller_m->admin_controller($data));
				
		$controller_public_file	= fopen($module_path.'/controllers/'.$data['module_slug'].'.php',"w",0775);
		fwrite($controller_public_file,$this->crud_front_controller_m->create_public_controller($data));
				
	}
	
	private function create_view_files($data = array(),$module_path,$position_folder)
	{
	
		$filters_file		= fopen($module_path."/views/admin/".$data['module_slug']."/partials/filters.php","w",0775);
		fwrite($filters_file,$this->crud_view_m->filters_view($data['module_name']));
				
		$table_body_file	= fopen($module_path."/views/admin/".$data['module_slug']."/tables/table_body.php","w",0777);
		fwrite($table_body_file,$this->crud_view_m->tables_view(NULL,$data['module_slug'],$data['database_table'],$position_folder));
				
		$front_index_file	= fopen($module_path.'/views/index.php',"w",0775);	
		fwrite($front_index_file,$this->crud_view_m->front_view($data['module_slug'],$data['database_table']));
				
		$admin_index_file	= fopen($module_path."/views/admin/".$data['module_slug']."/index.php","w",0775);
		fwrite($admin_index_file,$this->crud_view_m->admin_index_view("admin/".$data['module_slug'],$data['module_slug'],$data['database_table']));
				
		$admin_form_file	= fopen($module_path."/views/admin/".$data['module_slug']."/form.php","w",0775);
		fwrite($admin_form_file,$this->crud_view_m->admin_form_view($data['database_table'],$data['module_slug'],$position_folder));
				
		$admin_preview_file	= fopen($module_path."/views/admin/".$data['module_slug']."/preview.php","w",0775);
		fwrite($admin_preview_file,$this->crud_view_m->admin_preview_view($data['module_slug']));	
				
	}

	private function create_models_files($data = array(),$module_path)
	{
		
		$models_file			= fopen($module_path.'/models/'.$data['module_slug'].'_m.php',"w",0775);
		fwrite($models_file,$this->crud_admin_model_m->create_model($data));
				
		$front_model_file		= fopen($module_path.'/models/'.$data['module_slug'].'_front_m.php',"w",0775);
		fwrite($front_model_file,$this->crud_front_model_m->create_front_model($data));
		
	}
	
	//----------------------------------------------------- This Line For Test function ----------------------------------------//
	public function show_table_sturcture()
	{
		$str_input_html  = "";
		$table = "default_blog";
		$table = $this->db->escape_str($table);
		//$sql = "DESCRIBE `$table`";
		//$desc = $this->db->where('Field','sorts')->query($sql)->row();
		$desc = Easy_Database_Manage::get_table_desc($table);
		
		foreach($desc as $row)
		{
			echo Easy_Database_Manage::field_lenght($row->Type);
		}
		echo "<pre>";
		echo print_r($desc);
		echo "</pre>";
	
	}

}

	
	