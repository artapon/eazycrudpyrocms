<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Crud_Blank_Controller_M extends MY_Model
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
	";
		
		
		$file_detail .= "\$this->template
			->title(\$this->module_details['name'])
			->set_breadcrumb(lang('".$data['module_slug'].".name'))
			->set('".$data['module_slug']."',\$".$data['module_slug'].")
			->build('index');
	}
	
	public function page(\$page = 0)
	{
	";
		
		
		$file_detail .="
		\$this->template
			->title(\$this->module_details['name'])
			->set_breadcrumb(lang('".$data['module_slug'].".name'))
			->set('".$data['module_slug']."',\$".$data['module_slug'].")
			->build('index');
	}
	
}";
		
		return $file_detail;
	}	
	
	
	public function create_admin_controller($data = array())
	{
		$str_call_lang ="";
		$str_create_function = "";
		$str_create_edit_funtion = "";
		$str_delete_function = "";
		if($data['base_module']){
			$str_call_lang = "\$this->lang->load('".$data['base_module']."');";
		}
		if($data['check_db_table_exists_file']['has_file'] == 'has_file'){
			$str_create_function 		= $this->create_create_function_has_file($data);
			$str_create_edit_funtion 	= $this->create_edit_function_has_file($data);
			$str_delete_function 		= $this->create_delete_function($data);
			
		}else{
			$str_create_function 		= $this->create_create_function($data);
			$str_create_edit_funtion 	= $this->create_edit_function($data);
			$str_delete_function 		= $this->create_delete_function($data);
		}

		$validation_field = substr($this->_admin_validation($data['module_name'],$data['database_table']),0,-1);
		
		
		$file_detail =
	 "<?php defined('BASEPATH') or exit('No direct script access allowed');
	 
	 
class ".ucfirst($data['class_prefix'])." extends Admin_Controller
{
		
	protected \$section = '".$data['module_name']."';// controll create button
	
	
	protected \$".$data['module_name']."_validation_rules = array(".$validation_field.");
	
	public function __construct()
	{
		parent::__construct();
		".$str_call_lang."
		\$this->load->model('".$data['module_name']."_m');
		\$this->lang->load('".$data['module_name']."');
		\$this->load->library('form_validation');
		
		\$this->template
			->append_js('module::jquery.tablesorter.js')
			->append_css('module::table_style.css');
			
	}
	".$this->create_admin_index($data['redirect_url'],$data['uri_segment'],$data['module_name'])."
		
	".$str_create_function."
		
	".$str_delete_function."
		
	".$str_create_edit_funtion."
		
	".$this->_table_action($data['redirect_url'],$data['module_name'],$data['module_role'])."
				
}";
	 
	 return $file_detail;
	 
	}
	
	public function _admin_validation($module_name="",$table_name="" )
	{

		$str_validation  = "";
		$desc = Easy_Database_Manage::get_table_desc($table_name);
		
		foreach($desc as $row)
		{
			if($row->Extra!="auto_increment"){
				if($row->Field =="sorts"){
					$str_validation.="
					array(
						'field'=>'".$row->Field."',
						'label'=>'lang:".$module_name.".".$row->Field."_label',
						'rules'=>'required|trim|numeric'),";
				}elseif(strpos($row->Field,'file') !== false){
					$str_validation.="
					array(
						'field'=>'".$row->Field."',
						'label'=>'lang:".$module_name.".".$row->Field."_label',
						'rules'=>'trim'),";		
				}elseif($row->Field =='created_on'){
					$str_validation.="
					array(
						'field'=>'".$row->Field."',
						'label'=>'lang:".$module_name.".".$row->Field."_label',
						'rules'=>'trim'),";	
				}elseif($row->Field =="date_pk"){
					$str_validation .= "
					array(
						'field'=>'".$row->Field."',
						'label'=>'lang:".$module_name.".".$row->Field."_label',
						'rules'=>'required|trim'),";
				}else{
					$str_validation .= "
							array(
								'field'=>'".$row->Field."',
								'label'=>'lang:".$module_name.".".$row->Field."_label',
								'rules'=>'required|trim'),";
				}
			}
		}



		return $str_validation;
	}
	
	public function _create_admin_index($pagination_url,$uri,$module_name){
		return "public function index()
		{
					
				
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';	
			
			\$this->template
				->title(\$this->module_details['name'])
				->append_js('admin/filter.js')
				->set_partial('filters', 'admin/".$module_name."/partials/filters')
				->set('pagination',\$pagination);
				
				\$this->input->is_ajax_request()
			 	? \$this->template->build('admin/".$module_name."/tables/table_body.php')
				: \$this->template->build('admin/".$module_name."/index');
		}";
	}
	
	
	
	public function _create_function($data = array())
	{
		return "public function create_".$data['module_slug']."()
		{
			\$this->form_validation->set_rules(\$this->".$data['module_slug']."_validation_rules);
			if(\$this->form_validation->run())
			{
				
				
			}
			
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';
			
			\$this->template
					->append_metadata(\$this->load->view('fragments/wysiwyg',\"\", TRUE))
					->append_js('jquery/jquery.tagsinput.js')
					->append_js('module::form.js')
					->append_css('jquery/jquery.tagsinput.css')
					->build('admin/".$data['module_slug']."/form');
				
			
		}";
	}
	
	public function create_create_function_has_file($data = array()){
				
		$module_path = "";
		if($data['sub_module']=="sub_module"){
			$module_path = $data['base_module'];
		}else{
			$module_path = $data['module_name'];
		}
		return "public function create_".$data['module_name']."()
		{
			\$this->form_validation->set_rules(\$this->".$data['module_name']."_validation_rules);
			if(\$this->form_validation->run())
			{
				\$config['upload_path'] = './uploads/".$module_path."/';
				\$config['allowed_types'] = 'jpg|png|gif|jpeg|pdf|zip';
				\$config['file_name'] = 	'".$data['module_name']."'.time();
				\$config['file_ext']	=\$this->get_file_extension(\$_FILES['".$data['module_name']."_file']['name']);
				\$this->load->library('upload', \$config);
				if(!\$this->upload->do_upload('".$data['module_name']."_file')){
                    \$this->session->set_flashdata('error',\$this->upload->display_errors());
					redirect('".$data['redirect_url']."');
				}else{
					if(\$id=\$this->".$data['module_name']."_m->create_".$data['module_name']."(\$_POST,\$config))
					{
						\$this->session->set_flashdata('success',lang('create_success'));
					
						\$this->session->set_flashdata('success',lang('edit_success'));
						if(\$this->input->post('btnAction') =='save_exit'){
							redirect('".$data['redirect_url']."');
						}elseif(\$this->input->post('btnAction') == 'save_new'){
							redirect('admin/".$data['module_name']."/create_".$data['module_name']."');
							
						}else{
							redirect('".$data['redirect_url']."/edit_".$data['module_name']."/'.\$id);
						}
						}else{
							\$this->session->set_flashdata('error',lang('create_error'));
					
						}
				
				}
			}
			// xammp error \"Creating default object from empty value\" but appserv not
			\$".$data['module_name']." = new stdClass;
			foreach	(\$this->".$data['module_name']."_validation_rules as \$rule)
			{
				\$".$data['module_name']."->{\$rule['field']} = set_value(\$rule['field']);
			}
			
			\$".$data['module_name']."->type='wysiwyg-advanced';
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';
			
			
			\$this->template
					->append_metadata(\$this->load->view('fragments/wysiwyg',\"\", TRUE))
					->append_js('jquery/jquery.tagsinput.js')
					->append_js('module::form.js')
					->append_css('jquery/jquery.tagsinput.css')
					->set('".$data['module_name']."',\$".$data['module_name'].")
					->build('admin/".$data['module_name']."/form');
				
			
		}
		public function get_file_extension(\$filename) 
        {
                \$ext =array();  
                if(!empty(\$filename)){
                        \$x = explode('.', \$filename);
                        \$ext ='.' . end(\$x);
                }else{
                        \$ext = \"\";
                }
       
        return \$ext;

        }";
	}
	
	
	public function _delete_function($data = array(),$role = array())
	{
		$role_detail = "";
		if(!empty($data['module_role'][2]))
		{
			$role_detail = "role_or_die('".$data['module_slug']."', '".$data['module_slug'][2]."');";
		}
		return "public function delete_".$data['module_slug']."(\$id = null)
		{
			".$role_detail."
			
			if(\$this->".$data['module_slug']."_m->delete_".$data['module_slug']."(\$id))
			{
				\$this->session->set_flashdata('success', lang('delete_success'));
				
				redirect('".$data['redirect_url']."');
				
			}else{
				
				\$this->session->set_flashdata('error',lang('delete_error'));
				
				redirect('".$data['redirect_url']."');
			}
		}";
	}
	
	public function _edit_function($data = array(),$role = array())
	{
		
		$role_detail = "";
		if(!empty($data['module_role'][1]))
		{
			$role_detail = "role_or_die('".$data['module_slug']."', '".$data['module_slug'][1]."');";
		}
		return "public function edit_".$data['module_slug']."(\$id = 0)
		{
			\$id OR redirect('".$data['redirect_url']."');
			
			".$role_detail."
			
			\$".$data['module_slug']." = \$this->".$data['module_slug']."_m->get_".$data['module_slug']."_by_id(\$id);
			
			\$this->form_validation->set_rules(\$this->".$data['module_slug']."_validation_rules);
			if(\$this->form_validation->run())
			{
				
			}
			
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';
			
			\$this->template
					->append_metadata(\$this->load->view('fragments/wysiwyg',\"\", TRUE))
					->append_js('jquery/jquery.tagsinput.js')
					->append_js('module::form.js')
					->append_css('jquery/jquery.tagsinput.css')
					->build('admin/".$data['module_slug']."/form');
		}";
		
	}
	public function create_edit_function_has_file($data = array())
	{
		$module_path = "";
		if($data['sub_module'] == "sub_module"){
			$module_path = $data['base_module'];
		}else{
			$module_path = $data['module_name'];
		}
		$role_detail = "";
		if(!empty($data['module_role'][1]))
		{
			$role_detail = "role_or_die('".$data['module_name']."', '".$data['module_role'][1]."');";
		}
		return "public function edit_".$data['module_name']."(\$id=0)
		{
			\$id OR redirect('".$data['redirect_url']."');
	
			".$role_detail."
			if(!empty(\$_POST['delete_file'])){
              \$this->".$data['module_name']."_m->del_file(\$id);
            }
			\$".$data['module_name']." = \$this->".$data['module_name']."_m->get_".$data['module_name']."_by_id(\$id);
			
			\$this->form_validation->set_rules(\$this->".$data['module_name']."_validation_rules);
			if(\$this->form_validation->run())
			{
				\$config['upload_path'] = './uploads/".$module_path."/';
				\$config['allowed_types'] = 'jpg|png|gif|jpeg|pdf|zip';
				\$config['file_name'] = '".$data['module_name']."'.time();
				\$config['file_ext']	=\$this->get_file_extension(\$_FILES['".$data['module_name']."_file']['name']);
				\$this->load->library('upload', \$config);
				 if(!\$this->upload->do_upload('".$data['module_name']."_file')){
                    \$config['file_name'] = \$".$data['module_name']."->".$data['check_db_table_exists_file']['Field'].";
					if(\$this->".$data['module_name']."_m->update_".$data['module_name']."(\$id,\$_POST,\$config))
					{
						\$this->session->set_flashdata('success',lang('edit_success'));
						
						
						if(\$this->input->post('btnAction') =='save_exit'){
							redirect('".$data['redirect_url']."');
						}elseif(\$this->input->post('btnAction') == 'save_new'){
							redirect('admin/".$data['module_name']."/create_".$data['module_name']."');
							
						}else{
							redirect('".$data['redirect_url']."/edit_".$data['module_name']."/'.\$id);
						}
						
					}else{
						\$this->session->set_flashdata('error',lang('edit_error'));
					
					}	
				}else{
					if(\$this->".$data['module_name']."_m->update_".$data['module_name']."(\$id,\$_POST,\$config))
					{
						\$this->session->set_flashdata('success',lang('edit_success'));
						if(\$this->input->post('btnAction') =='save_exit'){
							redirect('".$data['redirect_url']."');
						}elseif(\$this->input->post('btnAction') == 'save_new'){
							redirect('admin/".$data['module_name']."/create_".$data['module_name']."');
							
						}else{
							redirect('".$data['redirect_url']."/edit_".$data['module_name']."/'.\$id);
						}
					}else{
						\$this->session->set_flashdata('error',lang('edit_error'));
					
					}	
				}
			}
			foreach (\$this->".$data['module_name']."_validation_rules as \$key => \$field)
			{
				if (isset(\$_POST[\$field['field']]))
				{
					\$".$data['module_name']."->\$field['field'] = set_value(\$field['field']);
				}
			}
			
			\$".$data['module_name']."->type='wysiwyg-advanced';
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';
			
			\$this->template
					->append_metadata(\$this->load->view('fragments/wysiwyg',\"\", TRUE))
					->append_js('jquery/jquery.tagsinput.js')
					->append_js('module::form.js')
					->append_css('jquery/jquery.tagsinput.css')
					->set('".$data['module_name']."',\$".$data['module_name'].")
					->build('admin/".$data['module_name']."/form');
		}";
		
	}
	
	public function _table_action($redirect_url,$module_name,$role=array()){
		
		$role_detail = "";
		if(!empty($role[2]))
		{
			$role_detail = "role_or_die('".$module_name."', '".$role[2]."');";
		}
		
		$file_detail = 
	"public function table_action()
	{
		
		if(\$this->input->post('btnAction')==\"delete\"){
				
			".$role_detail."
			
			\$data = \$this->input->post('action_to');
			for(\$item=0;\$item<count(\$data);\$item++){
				if(\$this->".$module_name."_m->delete_".$module_name."(\$data[\$item]))
				{
					\$this->session->set_flashdata('success', lang('delete_success'));
				}else{
					\$this->session->set_flashdata('error',lang('delete_error'));
				}
			}
			
		}elseif(\$this->input->post('btnAction')==\"sort\"){
			if(\$this->".$module_name."_m->update_sort(\$_POST)){
						
				\$this->session->set_flashdata('success',\"Update Sort Success\");
			}else{
				\$this->session->set_flashdata('error',\"Update Sort Fail\");
			}
			
		}elseif(\$this->input->post('btnAction')==\"re-sort\"){
			if(\$this->".$module_name."_m->update_reset_sort(\$_POST)){	
				\$this->session->set_flashdata('success',\"Reset Sort Success\");
			}else{
				\$this->session->set_flashdata('error',\"Reset Sort Fail\");
			}
		}elseif(\$this->input->post('btnAction')==\"publish\"){
			\$data = \$this->input->post('action_to');
			for(\$item=0;\$item<count(\$data);\$item++){
				if(\$this->".$module_name."_m->update_draft_live(\$data[\$item])){	
					\$this->session->set_flashdata('success',\"Publish Live Success\");
				}else{
					\$this->session->set_flashdata('error',\"Publish Live Fail\");
				}
			}
		}
		redirect('".$redirect_url."');
	}";
	return $file_detail;
	}

//-------------------------------------------- Blank Controller ---------------------------------------------------//
	
	public function create_blank_admin_controller($module_name){
		
			$file_detail =
	 "<?php defined('BASEPATH') or exit('No direct script access allowed');
	 
	 
class Admin extends Admin_Controller
{
		
	protected \$section = '".$module_name."';// controll create button
	
	/* if have many menu in section
	 protected \$section = 'modulename';
	 Example */
	 
	 /* uncomment protected and uncomment in details.php*/
	 
	 /*
	 	protected \$section ='test_second_menu';
	 */
	
	
		public function __construct()
		{
			parent::__construct();
			
			\$this->load->model('".$module_name."_m');
			\$this->lang->load('".$module_name."');
			
		}
		
		public function index()
		{
				
			\$this->input->is_ajax_request() and \$this->template->set_layout(FALSE);
			
			\$this->template
				->title(\$this->module_details['name']);
				
				\$this->input->is_ajax_request()
			 	? \$this->template->build('admin/".$module_name."/tables/table_body.php')
				: \$this->template->build('admin/".$module_name."/index');
		}
		
		
		public function create_".$module_name."()
		{
			
					
		}
		
		public function edit_".$module_name."()
		{
		
			
		}
		
		public function delete_".$module_name."()
		{
			
			
			
			
			
		}
}";
		
		return $file_detail;
	}
	
	
		
}
	