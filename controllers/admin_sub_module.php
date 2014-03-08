<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Create Submodule
 * 
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Admin_Sub_Module extends Admin_Controller
{
	 protected $section = 'CreateSubModule';
	 
	 protected $validation_submodule_rules = array(
		array(
			'field'=>'basemodule',
			'label'=>'Base Module',
			'rules'=>'trim|required'),
		array(
			'field'=>'slug',
			'label'=>'Slug',
			'rules'=>'trim'),
		array(
			'field'=>'name',
			'label'=>'Sub Module Name',
			'rules'=>'trim|required'
			),
			
		array(
			'field'=>'position',
			'label'=>'Position',
			'rules'=>'trim|required',
			),
		array(
			'field'=>'database_table',
			'label'=>'Database Table',
			'rules'=>'trim|required',

			),
	
	
	);
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('eazycrudpyrocms');
		$this->load->model('detail_modules_m');
		$this->load->model('controllers/crud_admin_controller_m');
		$this->load->model('controllers/crud_front_controller_m');
		$this->load->model('models/crud_admin_model_m');
		$this->load->model('views/crud_view_m');
		$this->load->model('languages/lang_m');
		
		$this->load->library('form_validation');
		$this->load->library('easy_database_manage');
		$this->load->library('crud_field_type');
		$this->load->library('crud_script');
		
		$this->template
			->append_js('module::eazycrudpyrocms.js')
			->append_css('module::eazycrudpyrocms.css');
	}
	
	public function get_modules_name()
	{
		$list_files_folders = array();
		$list_folders		= array();
		$list_folder_name	= array();
		$position 			= $this->input->get('position');
		$directory 			= "addons/".$position."/modules/";
		$path = glob($directory . "*");
		
		if(!empty($path)){
			
			foreach($path as $folder_path){
			 	
				//get list files and folders in modules folder
				$list_files_folders = explode("/", $folder_path);
				$list_folders		= explode(".",end($list_files_folders));
				
				if(count($list_folders) < 2){
					
					if(end($list_files_folders) !="eazycrudpyrocms")
					{
						
						$list_folder_name[end($list_files_folders)] = end($list_files_folders); 
						
					}
					
				}
				 
			}
		}
		
		echo form_dropdown('basemodule',$list_folder_name,'');
		
	}
	
	public function create_submodule()
	{
		$list_folder_name = array();
		
		$list_folder_name[null] = "-- Select BaseModule --";
		
		$shortcut_detail = "";
		
		$list_table = Easy_Database_Manage::get_list_table();
		
		$this->form_validation->set_rules($this->validation_submodule_rules);
		
		if($this->form_validation->run()){
			
			$database_table 		= $this->input->post('database_table');
			$name 					= strtolower($this->input->post('name'));
			$base_module	 		= $this->input->post('basemodule');
			$role					= $this->input->post('role');
			$position				= $this->input->post('position');
			$slug					= $this->input->post('slug');
			$table_st_exists_file	= Easy_Database_Manage::check_structure_exists_file($database_table);
			
			$data = array(
				'redirect_url'				=> "admin/".$base_module."/".$slug,
				'class_prefix'				=> "admin_".$slug,
				'module_name'				=> $name,
				'module_slug'				=> $slug,
				'base_module'				=> $base_module,
				'sub_module'				=> "sub_module",
				'database_table'			=> $database_table,
				'uri_segment'				=> 5,
				'module_role'				=> $role,
				'check_db_table_exists_file'=> $table_st_exists_file,
				'module_position'			=> $position		
			);
			
			$base_module_path = "addons/".$data['module_position']."/modules/".strtolower($data['base_module']);
			
			if(file_exists($base_module_path))
			{
				$cate_routes				= $this->detail_modules_m->append_cate_routes($data['base_module'],$data['module_slug']);
				
				$sub_module_permission_lang = $this->lang_m->create_permission_lang($data['module_slug'],"submodule");
				
				if(file_exists($base_module_path.'/views/admin/'.$data['module_slug'])){
					
					$error_msg = "Sub Module Name ".$data['module_name']." already exists ";
					
					$this->session->set_flashdata('error',$error_msg);
					
					redirect('admin/eazycrudpyrocms/sub_module/create_submodule');
				}else{
					
					$views_admin_folder	= mkdir($base_module_path.'/views/admin/'.$data['module_slug'],0775);
					$partials_folder	= mkdir($base_module_path."/views/admin/".$data['module_slug']."/partials",0775);
					$tables_folder		= mkdir($base_module_path."/views/admin/".$data['module_slug']."/tables",0775);
				
					$this->create_controller_files($data,$base_module_path);
					
					$this->create_models_files($data,$base_module_path);
					
					$this->create_view_files($data,$base_module_path,$position);
					
					if(file_exists($base_module_path.'/language/english')){
						
						$eng_language_files		= fopen($base_module_path.'/language/english/'.strtolower($data['module_slug']).'_lang.php',"w",0775);
						
						fwrite($eng_language_files,$this->lang_m->create_lang($data));
						
					}else{
						$eng_language_folder	= mkdir($base_module_path.'/language/english',0777);
						
						$eng_language_files		= fopen($base_module_path.'/language/english/'.strtolower($data['module_slug']).'_lang.php',"w",0775);
						
						fwrite($eng_language_files,$this->lang_m->create_lang($data));
						
					}
					if(file_exists($base_module_path.'/language/thai')){
						
						$thai_language_files	= fopen($base_module_path.'/language/thai/'.strtolower($data['module_slug']).'_lang.php',"w",0775);
						
						fwrite($thai_language_files,$this->lang_m->create_lang($data));
						
					}else{
						
						$thai_language_folder	= mkdir($base_module_path.'/language/thai',0777);
						
						$thai_language_files	= fopen($base_module_path.'/language/thai/'.strtolower($data['module_slug']).'_lang.php',"w",0775);
						
						fwrite($thai_language_files,$this->lang_m->create_lang($data));
					}
				
					file_put_contents($base_module_path.'/config/routes.php',$cate_routes, FILE_APPEND);
					
					file_put_contents($base_module_path.'/language/english/permission_lang.php',$sub_module_permission_lang, FILE_APPEND);
					
					file_put_contents($base_module_path.'/language/thai/permission_lang.php',$sub_module_permission_lang, FILE_APPEND);
					
					$sub_construct = $this->append_construct($base_module_path."/controllers/admin.php",$data);
					
					file_put_contents($base_module_path."/controllers/admin.php",$sub_construct);
					
					$sub_section = $this->append_sub_section($base_module_path.'/details.php',$data);
					
					file_put_contents($base_module_path.'/details.php',$sub_section);
					
					$sub_install = $this->append_install($base_module_path.'/details.php',$data);
					
					file_put_contents($base_module_path.'/details.php',$sub_install);
					
					$this->session->set_flashdata('success',"Create Module".$data['base_module']." Success");
					
					redirect('admin/'.$data['base_module']."/".$data['module_slug']);
				}

			}else{
				
				$error_msg = "Base Module Name ".$this->input->post('basecrud')." not create yet ";
				
				$this->session->set_flashdata('error',$error_msg);
				
				redirect('admin/eazycrudpyrocms/sub_module/create_submodule');
			}
			
		}

		$submodule_form = new stdClass;
		foreach	($this->validation_submodule_rules as $rule)
		{
			$submodule_form->{$rule['field']} = set_value($rule['field']);
		}
		$this->template
					->set('folder_name',$list_folder_name)
					->set('submodule_form',$submodule_form)
					->set('list_table',$list_table)
					->build('admin/submodule/form');
		
	}

	private function create_controller_files($data = array(),$base_module_path)
	{
		$controller_admin_files	= fopen($base_module_path."/controllers/admin_".$data['module_slug'].".php","w",0775);
		fwrite($controller_admin_files,$this->crud_admin_controller_m->admin_controller($data));
	}
	
	private function create_models_files($data = array(),$base_module_path)
	{
		$models_files			= fopen($base_module_path.'/models/'.strtolower($data['module_slug']).'_m.php',"w",0775);
		fwrite($models_files,$this->crud_admin_model_m->create_model($data));
	}

	private function create_view_files($data = array(),$base_module_path,$position)
	{
		$views_admin_files_form	= fopen($base_module_path."/views/admin/".$data['module_slug']."/form.php","w",0775);
		fwrite($views_admin_files_form,$this->crud_view_m->admin_form_view($data['database_table'],$data['module_slug'],$data['module_position'],$data['base_module'],'sub_module'));
		
		$filters_files			= fopen($base_module_path."/views/admin/".strtolower($data['module_slug'])."/partials/filters.php","w",0775);
		fwrite($filters_files,$this->crud_view_m->filters_view($data['module_slug']));
				
		$tables_files			= fopen($base_module_path."/views/admin/".strtolower($data['module_slug'])."/tables/table_body.php","w",0775);
		fwrite($tables_files,$this->crud_view_m->tables_view($data['base_module'],$data['module_slug'],$data['database_table'],$data['module_position']));
				
		$views_admin_files		= fopen($base_module_path."/views/admin/".strtolower($data['module_slug'])."/index.php","w",0775);
		fwrite($views_admin_files,$this->crud_view_m->admin_index_view("admin/".$data['base_module']."/".$data['module_slug'],$data['base_module'],$data['database_table']));
	}
	
	public function append_sub_section($file_target = null,$data = array())
	{
		
		$data_append 	= $this->detail_modules_m->categories_detail($data['base_module'],$data['module_slug']);
		
		$position 		= 'sections';

		return $this->_append_string($file_target,$data_append,$position);
			
	}
	
	public function append_install($file_target = null,$data = array())
	{

		$data_append 	= $this->detail_modules_m->get_install_function_detail($data['module_slug'],$data['database_table']);
		
		$position 		= "\$this->install_tables(\$tables_".$data['base_module'].")";

		return $this->_append_string($file_target,$data_append,$position);
	}
	
	public function append_construct($file_target = null,$data = array())
	{

		$data_append 	= "\$this->load->model('".$data['module_slug']."_m');
		\$this->lang->load('".$data['module_slug']."');";
		
		$position 		= "parent::__construct();";

		return $this->_append_string($file_target,$data_append,$position);
	}
	 
	private function _append_string($file_target,$data_append = "",$position = "")
	{
		$output 	= array();
		
		$lines 		= file($file_target);
		
		$key 		= $position;
		
		foreach ($lines as $lineNumber => $line)
		{
  		 	if (strpos($line,$key) !== false) {
  		 		
	  			 $output[]= $line."\n".$data_append ."\n";
	  			 
   		 	}else{
   		 		
    			 $output[] = $line;
				 
  	 	 	}
		}
		
		return $output;
	}
}
