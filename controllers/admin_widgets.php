<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Create Basic Widgets Structure
 * 
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Admin_Widgets extends Admin_Controller {
	
	protected $validation_create_widgets = array(
			array(
				'field'=>'name',
				'label'=>'Name',
				'rules'=>'trim|required'
		
				),
			array(
				'field'=>'slug',
				'label'=>'Slug',
				'rules'=>'trim'
		
				),
			array(
				'field'=>'position',
				'label'=>'Position',
				'rules'=>'trim'
		
				),
			array(
				'field'=>'description',
				'label'=>'Description',
				'rules'=>'trim'
		
				),
			array(
				'field'=>'author',
				'label'=>'Author',
				'rules'=>'trim'
				),
			array(
				'field'=>'version',
				'label'=>'Version',
				'rules'=>'trim'
				),
			array(
				'field'=>'website',
				'label'=>'Website',
				'rules'=>'trim'
			),
			array(
				'field'=>'example',
				'label'=>'Example',
				'rules'=>''
			),
		);
		
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('widgets/crud_widget_m');
	}
	
	public function create_widget()
	{
		$target_folder 	= $this->input->post('position');
		$widget_name   	= $this->input->post('name');
		$folder_format	= $this->input->post('slug');
		$widget_example = $this->input->post('example');

		$this->form_validation->set_rules($this->validation_create_widgets);
		
		if($this->form_validation->run()){
			
			$widget_path = "addons/".$target_folder."/widgets/".strtolower($folder_format);
			
			if(file_exists($widget_path)){
				
				$this->session->set_flashdata('error',"Widget ".$widget_name." already exists ");
				
				redirect('admin/eazycrudpyrocms/form_create_widget');
				
			}else{
				
				mkdir($widget_path,0775);
				mkdir($widget_path."/views",0775);
				mkdir($widget_path."/css",0775);
				mkdir($widget_path."/js",0775);
				
				$controller  	= fopen($widget_path."/".strtolower($folder_format).".php","w",0775);
				$form			= fopen($widget_path."/views/form.php","w",0775);
				$display		= fopen($widget_path."/views/display.php","w",0775);
				
				if($widget_example == "example"){
					
					fwrite($controller,$this->crud_widget_m->widget_structure_ex($_POST));
					fwrite($form, $this->crud_widget_m->widget_form_detail());
					fwrite($display, $this->crud_widget_m->widget_display_detail());
					
				}else{
					
					fwrite($controller,$this->crud_widget_m->widget_structure($_POST));
					
				}
				
				$this->session->set_flashdata('success',"Create Widget ".$widget_name." Success");
				
				redirect('admin/widgets');
			}
			
		}

		$widgets_form = new stdClass;
		
		foreach	($this->validation_create_widgets as $rule)
		{
			$widgets_form->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';
		
		$this->template
			->set('widgets_form',$widgets_form)
			->build('admin/widget/form');
	}	
}
	