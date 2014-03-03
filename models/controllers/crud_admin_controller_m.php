<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Crud_Admin_Controller_M extends MY_Model
{

	public function admin_controller($data = array())
	{
		$str_call_lang = "";
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
			
			$str_create_function 		= $this->_create_function($data);
			$str_create_edit_funtion 	= $this->_edit_function($data);
			$str_delete_function 		= $this->_delete_function($data);
		}

		$validation_field = substr($this->_admin_validation($data),0,-1);
		
		$file_detail =
	 "<?php defined('BASEPATH') or exit('No direct script access allowed');
	 
	 
class ".ucfirst($data['class_prefix'])." extends Admin_Controller
{
		
	protected \$section = '".$data['module_slug']."';// controll create button
	
	
	protected \$".$data['module_slug']."_validation_rules = array(".$validation_field.");
	
	public function __construct()
	{
		parent::__construct();
		".$str_call_lang."
		\$this->load->model('".$data['module_slug']."_m');
		\$this->lang->load('".$data['module_slug']."');
		\$this->load->library('form_validation');
		
		\$this->template
			->append_js('module::jquery.tablesorter.js')
			->append_css('module::table_style.css');
			
	}
	".$this->_admin_index($data)."
		
	".$str_create_function."
		
	".$str_delete_function."
		
	".$str_create_edit_funtion."
		
	".$this->_table_action($data)."
				
}";
	 
	 return $file_detail;
	 
	}
	
	private function _admin_validation($data = array())
	{

		$str_validation  = "";
		
		$desc = Easy_Database_Manage::get_table_desc($data['database_table']);
		
		foreach($desc as $row)
		{
			if($row->Extra != "auto_increment"){
				
				if($row->Field == "sorts" ){
					
					$str_validation.= "
						array(
							'field'=>'".$row->Field."',
							'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
							'rules'=>'required|trim|numeric".Easy_Database_Manage::field_lenght($row->Type)."'),";
						
				}elseif(strpos($row->Field,'file') !== false){
					
					$str_validation.= "
						array(
							'field'=>'".$row->Field."',
							'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
							'rules'=>'trim".Easy_Database_Manage::field_lenght($row->Type)."'),";		
						
				}elseif($row->Field == 'created_on'){
					
					$str_validation.= "
						array(
							'field'=>'".$row->Field."',
							'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
							'rules'=>'trim|numeric'),";
								
				}elseif($row->Field == "date_pk"){
					
					$str_validation .= "
						array(
							'field'=>'".$row->Field."',
							'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
							'rules'=>'required|trim".Easy_Database_Manage::field_lenght($row->Type)."'),";
							
				}elseif($row->Type == "text" || $row->Type == 'tinytext' || $row->Type == 'mediumtext' || $row->Type == 'longtext'){
							
							$str_validation .= "
								array(
									'field'=>'".$row->Field."',
									'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
									'rules'=>'trim'),";
									
				}else{
					
					if($row->Null == 'NO'){
						
						if(strpos($row->Type,'int') !== false){
							
							$str_validation .= "
								array(
									'field'=>'".$row->Field."',
									'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
									'rules'=>'required|numeric'),";
						
						}elseif(strpos($row->Type,'enum') !== false){
								
							$str_validation .= "
								array(
									'field'=>'".$row->Field."',
									'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
									'rules'=>'trim|required'),";
							
						}else{
							
							$str_validation .= "
								array(
									'field'=>'".$row->Field."',
									'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
									'rules'=>'required|trim".Easy_Database_Manage::field_lenght($row->Type)."'),";		
			
						}
						
						
					}else{
						
						if(strpos($row->Type,'int') !== false ){
							
							$str_validation .= "
								array(
									'field'=>'".$row->Field."',
									'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
									'rules'=>'required|numeric'),";
						}else{
							$str_validation .= "
								array(
									'field'=>'".$row->Field."',
									'label'=>'lang:".$data['module_slug'].".".$row->Field."_label',
									'rules'=>'trim".Easy_Database_Manage::field_lenght($row->Type)."'),";	
								
							}
						
					}
					
				}
			}
		}

		return $str_validation;
	}
	
	public function _admin_index($data = array())
	{
		return "
		public function index()
		{
				
			\$this->input->is_ajax_request() and \$this->template->set_layout(FALSE);
			
			\$total_rows = count(\$this->".$data['module_slug']."_m->get_".$data['module_slug']."_all());
			\$pagination = create_pagination('".$data['redirect_url']."/index', \$total_rows,\$this->input->post('f_limit'),".$data['uri_segment'].");
			\$".$data['module_slug']." = \$this->".$data['module_slug']."_m->limit(\$pagination['limit'],\$pagination['offset'])->get_".$data['module_slug']."_all();
			
			\$this->template
				->title(\$this->module_details['name'])
				->append_js('admin/filter.js')
				->set_partial('filters', 'admin/".$data['module_slug']."/partials/filters')
				->set('".$data['module_slug']."',\$".$data['module_slug'].")
				->set('pagination',\$pagination);
				
				\$this->input->is_ajax_request()
			 	? \$this->template->build('admin/".$data['module_slug']."/tables/table_body.php')
				: \$this->template->build('admin/".$data['module_slug']."/index');
		}";
	}
	
	public function _create_function($data = array())
	{
		return "
		public function create_".$data['module_slug']."()
		{
			
			\$this->form_validation->set_rules(\$this->".$data['module_slug']."_validation_rules);
			if(\$this->form_validation->run())
			{
				if(\$id=\$this->".$data['module_slug']."_m->create_".$data['module_slug']."(\$_POST))
				{
					\$this->session->set_flashdata('success',lang('create_success'));
					
					if(\$this->input->post('btnAction') =='save_exit'){
						
						redirect('".$data['redirect_url']."');
						
					}elseif(\$this->input->post('btnAction') == 'save_new'){
						
						redirect('admin/".$data['module_slug']."/create_".$data['module_slug']."');
							
					}else{
						
						redirect('".$data['redirect_url']."/edit_".$data['module_slug']."/'.\$id);
					}
				}else{
					\$this->session->set_flashdata('error',lang('create_error'));
					
				}
				
			}
			
			// xammp error \"Creating default object from empty value\" but appserv not
			\$".$data['module_slug']." = new stdClass;
			
			foreach	(\$this->".$data['module_slug']."_validation_rules as \$rule)
			{
				\$".$data['module_slug']."->{\$rule['field']} = set_value(\$rule['field']);
			}
			
			\$".$data['module_slug']."->type='wysiwyg-advanced';
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';
			
			\$this->template
					->append_metadata(\$this->load->view('fragments/wysiwyg',\"\", TRUE))
					->append_js('jquery/jquery.tagsinput.js')
					->append_js('module::form.js')
					->append_css('jquery/jquery.tagsinput.css')
					->set('".$data['module_slug']."',\$".$data['module_slug'].")
					->build('admin/".$data['module_slug']."/form');
				
			
		}";
	}
	
	public function create_create_function_has_file($data= array()){
				
		$module_path = "";
		if($data['sub_module'] == "sub_module"){
			
			$module_path = $data['base_module'];
			
		}else{
			
			$module_path = $data['module_name'];
		}
		return "
		public function create_".$data['module_name']."()
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
	
	
	public function _delete_function($data = array())
	{
		$role_detail = "";
		if(!empty($data['module_role'][2]))
		{
			$role_detail = "role_or_die('".$data['module_slug']."', '".$data['module_role'][2]."');";
		}
		return "
		public function delete_".$data['module_slug']."(\$id=null)
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
	
	
	
	public function _edit_function($data = array())
	{
		
		$role_detail = "";
		if(!empty($data['module_role'][1]))
		{
			$role_detail = "role_or_die('".$data['module_slug']."', '".$data['module_role'][1]."');";
		}
		return "public function edit_".$data['module_slug']."(\$id=0)
		{
			\$id OR redirect('".$data['redirect_url']."');
			
			".$role_detail."
			
			\$".$data['module_slug']." = \$this->".$data['module_slug']."_m->get_".$data['module_slug']."_by_id(\$id);
			
			\$this->form_validation->set_rules(\$this->".$data['module_slug']."_validation_rules);
			if(\$this->form_validation->run())
			{
				if(\$this->".$data['module_slug']."_m->update_".$data['module_slug']."(\$id,\$_POST))
				{
					\$this->session->set_flashdata('success',lang('edit_success'));
					
					if(\$this->input->post('btnAction') =='save_exit'){
						
						redirect('".$data['redirect_url']."');
						
					}elseif(\$this->input->post('btnAction') == 'save_new'){
						
						redirect('admin/".$data['module_slug']."/create_".$data['module_slug']."');
							
					}else{
						redirect('".$data['redirect_url']."/edit_".$data['module_slug']."/'.\$id);
					}
				}else{
					\$this->session->set_flashdata('error',lang('edit_error'));
					
				}	
			}
			
			foreach (\$this->".$data['module_slug']."_validation_rules as \$key => \$field)
			{
				if (isset(\$_POST[\$field['field']]))
				{
					\$".$data['module_slug']."->\$field['field'] = set_value(\$field['field']);
				}
			}
			
			\$".$data['module_slug']."->type='wysiwyg-advanced';
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';
			
			\$this->template
					->append_metadata(\$this->load->view('fragments/wysiwyg',\"\", TRUE))
					->append_js('jquery/jquery.tagsinput.js')
					->append_js('module::form.js')
					->append_css('jquery/jquery.tagsinput.css')
					->set('".$data['module_slug']."',\$".$data['module_slug'].")
					->build('admin/".$data['module_slug']."/form');
		}";
		
	}
	public function create_edit_function_has_file($data = array())
	{
		$module_path = "";
		if($data['sub_module'] == "sub_module"){
			
			$module_path = $data['base_module'];
			
		}else{
			
			$module_path = $data['module_slug'];
			
		}
		$role_detail = "";
		if(!empty($data['module_role'][1]))
		{
			$role_detail = "role_or_die('".$data['module_slug']."', '".$data['module_slug'][1]."');";
		}
		return "public function edit_".$data['module_slug']."(\$id = 0)
		{
			
			\$id OR redirect('".$data['redirect_url']."');
			".$role_detail."
			if(!empty(\$_POST['delete_file'])){
              \$this->".$data['module_slug']."_m->del_file(\$id);
            }
			\$".$data['module_slug']." = \$this->".$data['module_slug']."_m->get_".$data['module_slug']."_by_id(\$id);
			
			\$this->form_validation->set_rules(\$this->".$data['module_slug']."_validation_rules);
			if(\$this->form_validation->run())
			{
				\$config['upload_path'] = './uploads/".$module_path."/';
				\$config['allowed_types'] = 'jpg|png|gif|jpeg|pdf|zip';
				\$config['file_name'] = '".$data['module_slug']."'.time();
				\$config['file_ext']	=\$this->get_file_extension(\$_FILES['".$data['module_slug']."_file']['name']);
				\$this->load->library('upload', \$config);
				 if(!\$this->upload->do_upload('".$data['module_slug']."_file')){
				 	
                    \$config['file_name'] = \$".$data['module_slug']."->".$data['check_db_table_exists_file']['Field'].";
                    
					if(\$this->".$data['module_slug']."_m->update_".$data['module_slug']."(\$id,\$_POST,\$config))
					{
						\$this->session->set_flashdata('success',lang('edit_success'));
						
						
						if(\$this->input->post('btnAction') =='save_exit'){
							
							redirect('".$data['redirect_url']."');
							
						}elseif(\$this->input->post('btnAction') == 'save_new'){
							
							redirect('admin/".$data['module_slug']."/create_".$data['module_slug']."');
							
						}else{
							
							redirect('".$data['redirect_url']."/edit_".$data['module_slug']."/'.\$id);
						}
						
					}else{
						\$this->session->set_flashdata('error',lang('edit_error'));
					
					}	
				}else{
					if(\$this->".$data['module_slug']."_m->update_".$data['module_slug']."(\$id,\$_POST,\$config))
					{
						\$this->session->set_flashdata('success',lang('edit_success'));
						
						if(\$this->input->post('btnAction') =='save_exit'){
							
							redirect('".$data['redirect_url']."');
							
						}elseif(\$this->input->post('btnAction') == 'save_new'){
							
							redirect('admin/".$data['module_slug']."/create_".$data['module_slug']."');
							
						}else{
							
							redirect('".$data['redirect_url']."/edit_".$data['module_slug']."/'.\$id);
						}
					}else{
						\$this->session->set_flashdata('error',lang('edit_error'));
					
					}	
				}
			}
			foreach (\$this->".$data['module_slug']."_validation_rules as \$key => \$field)
			{
				if (isset(\$_POST[\$field['field']]))
				{
					\$".$data['module_slug']."->\$field['field'] = set_value(\$field['field']);
				}
			}
			
			\$".$data['module_name']."->type='wysiwyg-advanced';
			\$this->input->is_ajax_request() ? \$this->template->set_layout(FALSE) : '';
			
			\$this->template
					->append_metadata(\$this->load->view('fragments/wysiwyg',\"\", TRUE))
					->append_js('jquery/jquery.tagsinput.js')
					->append_js('module::form.js')
					->append_css('jquery/jquery.tagsinput.css')
					->set('".$data['module_slug']."',\$".$data['module_slug'].")
					->build('admin/".$data['module_slug']."/form');
		}";
		
	}
	
	public function _table_action($data = array()){
		
		$role_detail = "";
		if(!empty($data['module_role'][2]))
		{
			$role_detail = "role_or_die('".$data['module_slug']."', '".$data['module_role'][2]."');";
		}
		
		$file_detail = 
	"public function table_action()
	{
		
		if(\$this->input->post('btnAction')==\"delete\"){
				
			".$role_detail."
			
			\$data = \$this->input->post('action_to');
			for(\$item = 0;\$item < count(\$data);\$item++){
				
				if(\$this->".$data['module_slug']."_m->delete_".$data['module_slug']."(\$data[\$item]))
				{
					\$this->session->set_flashdata('success', lang('delete_success'));
					
				}else{
					
					\$this->session->set_flashdata('error',lang('delete_error'));
					
				}
			}
			
		}";
		if(Easy_Database_Manage::check_structure_exists_sorts($data['database_table'])){
		
			$file_detail .= "elseif(\$this->input->post('btnAction') == \"sort\"){
				if(\$this->".$data['module_slug']."_m->update_sort(\$_POST)){
							
					\$this->session->set_flashdata('success',\"Update Sort Success\");
				}else{
					\$this->session->set_flashdata('error',\"Update Sort Fail\");
				}
				
			}elseif(\$this->input->post('btnAction') == \"re-sort\"){
				
				if(\$this->".$data['module_slug']."_m->update_reset_sort(\$_POST)){
						
					\$this->session->set_flashdata('success',\"Reset Sort Success\");
					
				}else{
					
					\$this->session->set_flashdata('error',\"Reset Sort Fail\");
				}
			}";
		}
		$file_detail .= "elseif(\$this->input->post('btnAction')==\"publish\"){
			
			\$data = \$this->input->post('action_to');
			
			for(\$item = 0;\$item < count(\$data);\$item++){
				
				if(\$this->".$data['module_slug']."_m->update_draft_live(\$data[\$item])){
					
					\$this->session->set_flashdata('success',\"Publish Live Success\");
					
				}else{
					
					\$this->session->set_flashdata('error',\"Publish Live Fail\");
					
				}
			}
		}
		redirect('".$data['redirect_url']."');
	}";
	
	return $file_detail;
	}
		
}
	