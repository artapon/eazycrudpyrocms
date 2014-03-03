<?php defined('BASEPATH') or exit('No direct script access allowed');
	 
	 
class Admin_Test_function extends Admin_Controller
{
		
	protected $section = 'test_function';// controll create button
	
	
	protected $test_function_validation_rules = array(
							array(
								'field'=>'title',
								'label'=>'lang:test_function.title_label',
								'rules'=>'required|trim'));
	
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('moduletest');
		$this->load->library('form_validation');
		
		$this->template
			->append_js('module::jquery.tablesorter.js')
			->append_css('module::table_style.css');
			
	}
	
	public function index()
	{
		$str_input_html  = "";
		$table = "default_module_test";
		$table = $this->db->escape_str($table);
		//$sql = "DESCRIBE `$table`";
		//$desc = $this->db->where('Field','sorts')->query($sql)->row();
		$desc = Easy_Database_Manage::get_table_desc($table);
		echo "<pre>";
		echo print_r($desc);
		echo "</pre>";
	}

	//----------------------------------------------------- This Line For Test function ----------------------------------------//
	public function show_table_sturcture()
	{
		$str_input_html  = "";
		$table = "default_module_test";
		$table = $this->db->escape_str($table);
		//$sql = "DESCRIBE `$table`";
		//$desc = $this->db->where('Field','sorts')->query($sql)->row();
		$desc = Easy_Database_Manage::get_table_desc($table);
		echo "<pre>";
		echo print_r($desc);
		echo "</pre>";
	
	}
	
	
	
	public function show_table(){
		echo "<pre>";
		print_r($this->db->list_tables());
	}
	
	public function export_excel()
	{
		// create a simple 2-dimensional array
	$data = array(
        array ('Name', 'Surname'),
        array('Schwarz', 'Oliver'),
        array('Test', 'Peter')
        );
		
		//$data = $this->db->get('test')->result_array();
		$this->load->library('Excel-Generation/excel_xml');
		$this->excel_xml->addArray($data);
		$this->excel_xml->generateXML('my-test');
	}
	
	public function export_excel2()
	{
		//load our new PHPExcel library
		$this->load->library('phpexcel');
		$this->load->view('admin/function/xls_format');
	}
	
	public function copy_directory( $source, $destination ) 
	{
		if ( is_dir( $source ) ) {
			@mkdir( $destination );
			$directory = dir( $source );
			while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
				if ( $readdirectory == '.' || $readdirectory == '..' ) {
					continue;
				}
				$PathDir = $source . '/' . $readdirectory; 
				if ( is_dir( $PathDir ) ) {
					$this->copy_directory( $PathDir, $destination . '/' . $readdirectory );
					continue;
				}
				copy( $PathDir, $destination . '/' . $readdirectory );
			}
 
			$directory->close();
		}else {
			copy( $source, $destination );
		}
	}
	
	public function install_lib_excel_export()
	{
		$this->load->model('function/create_function_m');
		$excel_lib_path	= "./addons/shared_addons/libraries";
		if(file_exists($excel_lib_path)){
			$this->copy_directory(realpath(dirname(__FILE__) . '/..')."/libraries/Excel-Generation",$excel_lib_path);
		}
		
	}
		
	
				
}