<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Create Besic Theme Structure
 * 
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Admin_Themes extends Admin_Controller {
	
	protected $validation_create_themes = array(
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
		);
		
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('themes/crud_theme_m');
	}
	
	public function create_theme_structure()
	{
		$target_folder 		= $this->input->post('position');
		$theme_name   		= $this->input->post('name');
		$folder_format		= $this->input->post('slug');
		
		$this->form_validation->set_rules($this->validation_create_themes);
		
		if($this->form_validation->run()){
			
			$theme_path = "addons/".$target_folder."/themes/".strtolower($folder_format);
			
			if(file_exists($theme_path)){
				
				$this->session->set_flashdata('error',"Theme ".$theme_name." already exists ");
				
				redirect('admin/eazycrudpyrocms/themes');
			
			}else{
				mkdir($theme_path,0775);
				mkdir($theme_path."/css",0775);
				mkdir($theme_path."/css/images",0775);
				mkdir($theme_path."/js",0775);
				mkdir($theme_path."/img",0775);
				mkdir($theme_path."/views",0775);
				mkdir($theme_path."/views/partials",0775);
				mkdir($theme_path."/views/layouts",0775);
				
				$details					= fopen($theme_path."/theme.php","w",0775);
				$css						= fopen($theme_path."/css/".$folder_format."_main.css","w",0775);
				$default_layout				= fopen($theme_path."/views/layouts/default.html","w",0775);
				$metadata					= fopen($theme_path."/views/partials/metadata.html","w",0775);
				$header						= fopen($theme_path."/views/partials/header.html","w",0775);
				$footer						= fopen($theme_path."/views/partials/footer.html","w",0775);
				$breadcrumbs				= fopen($theme_path."/views/partials/breadcrumbs.html","w",0775);
				
				fwrite($details,$this->crud_theme_m->theme_details_structure($_POST));
				fwrite($default_layout,$this->crud_theme_m->default_structure());
				fwrite($metadata,$this->crud_theme_m->metadata_structure($_POST));
				fwrite($breadcrumbs,$this->crud_theme_m->breadcrumbs_structure());
				
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/jquery/jquery-1.9.1.min.js",$theme_path."/js/jquery.js");
				copy(realpath(dirname(__FILE__) . '/..')."/libraries/files/jquery/jquery-migrate-1.0.0.js",$theme_path."/js/fixed-old-js-version.js");
				
				$this->session->set_flashdata('success',"Create Theme ".$theme_name." Success");
				
				redirect('admin/addons/themes');
				
			}
		}
		
		$theme_form = new stdClass;
		
		foreach	($this->validation_create_themes as $rule)
		{
			$theme_form->{$rule['field']} = set_value($rule['field']);
		}
		
		$this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';
		
		$this->template
			->set('theme_form',$theme_form)
			->build('admin/themes/form');
		
		
	}
	
	
}
