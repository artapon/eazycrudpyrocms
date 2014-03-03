<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */


class detail_modules_m extends MY_Model{
	
	protected $real_table = array();
	protected $sql_array;
	protected $field_type = array();
	protected $constraint = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('easy_database_manage');
	}
	
	
	public function create_details($data = array(),$input = array())
	{
		$menu 			= $this->input->post('menu');
		$upgrade 		= $this->input->post('upgrade');
		$help	 		= $this->input->post('help');
		$drop_db		= $this->input->post('database_drop');
		$file_detail =
	 "<?php defined('BASEPATH') or exit('No direct script access allowed');
		
		
class Module_".ucfirst($data['module_slug'])." extends Module {
	
	public \$version ='".$input['module_version']."';
	
	public function info()
	{
		return array(
			'name' => array(
				'en'=>'".ucfirst($data['module_slug'])."',
			),
			'description' => array(
				'en'=>'".$input['description']."'
			),
			'frontend'	=>".$input['fontend'].",
			'backend'	=>".$input['backend'].",
			'menu'		=>'content',
			'skip_xss'	=>".$input['skip_xss'].",
			
			".$this->create_module_role($data['module_role'])."
			
			'sections'=>array(
			
				'".$data['module_slug']."'=>array(
						'name'=>'".trim($data['module_slug']).".name',
						'uri' =>'admin/".$data['module_slug']."',
				
				'shortcuts'	=>array(
					'create'=> array(
						'name'=>'".$data['module_slug']."_create_title',
						'uri' =>'admin/".$data['module_slug']."/create_".$data['module_slug']."',
						'class'=>'add'
					
					),
				),
			),
			
			)
		);
			
	}
	
	public function install()
	{

		".$this->get_install_function_detail($data['module_slug'],$data['database_table'])."
		return TRUE;
	}
	
	public function uninstall()
	{	
		".$this->get_uninstall_function_detail($data['module_slug'],$this->real_table,$drop_db)."
	}
	
	public function upgrade(\$old_version)
	{
		return ".$upgrade.";
	}
	
	public function help()
	{
		return \"".$help."\";
	}
	
	
	public function  delete_data_in_folder(\$str)
	{
        if(is_file(\$str)){
            return @unlink(\$str);
        }
        elseif(is_dir(\$str)){
            \$scan = glob(rtrim(\$str,'/').'/*');
            foreach(\$scan as \$index=>\$path){
                \$this->delete_data_in_folder(\$path);
            }
            return @rmdir(\$str);
        }
    }
	
}";
		
		return $file_detail;
		
	}

	public function get_install_function_detail($module_name,$table_name)
	{
		
		$this->real_table = Easy_Database_Manage::get_real_table($table_name);
		
		$desc = Easy_Database_Manage::get_table_desc($table_name);
		
		$file_detail = "\$this->dbforge->drop_table('".$this->real_table."');
		
		\$tables_".$module_name." = array(
			'".$this->real_table."' =>array(
		";
		// explode Type =>int(11) to 11
		foreach($desc as $row){
			
			$field_type  = explode("(",$row->Type);
			
			if(count($field_type) >1 )
			{
				
				$this->constraint = explode(')',end($field_type));

				// if structure is auto_increment or primary key
				if($row->Extra == "auto_increment" || $row->Key == "PRI")
				{
					
					if($row->Key == "PRI" && $row->Extra != "auto_increment"){
						
						$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).",  'primary' => true ),\n";
					}elseif($row->Extra == "auto_increment" && $row->Key != "PRI"){
						// get first array for real type
						$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).", 'auto_increment' => true ),\n";
					}else{
						$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).", 'auto_increment' => true ,  'primary' => true  ),\n";
					}
					
				}elseif(strtoupper(reset($field_type)) == "ENUM" || strtoupper(reset($field_type)) == "SET"){
					
					if(is_numeric($row->Default) || !empty($row->Default))
					{
						
						$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>array(".reset($this->constraint)."),'default' => 'draft' ),\n";
						
					}else{
						
						$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>array(".reset($this->constraint)."),'default' => 'draft' ),\n";
						
					}
					
				}else{
					
					if(is_numeric($row->Default) || !empty($row->Default)){
						// get first array for real type
						if(strpos($row->Field,'file') !== false){
							
							$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).", 'default' => ".$row->Default.",'null' => true),\n";
								
						}else{
							
							$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).", 'default' => ".$row->Default."),\n";
							
						}
						
					}else{
						// if empty default value if type == INT default should numeric
						if(strtoupper(reset($field_type)) == "INT"){
							 
							$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).", 'default' =>0),\n";
							
						}else{
							
							if(strpos($row->Field,'file') !== false){
								
								$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).", 'default' => '".$row->Default."','null' => true),\n";
								
							}else{
								
								$file_detail .= "'".$row->Field."' => array('type' => '".strtoupper(reset($field_type))."', 'constraint' =>".reset($this->constraint).", 'default' => '".$row->Default."'),\n";
							}
						}
						
					}
					
						
				}
					
			}else{

				$file_detail .= "'".$row->Field."' => array('type'=>'".$row->Type."'),\n";		
			}
			
		}

		$file_detail .= ")
		
		);";
		
		$file_detail .= "\$this->install_tables(\$tables_".$module_name.");";
		
		return $file_detail;
	}
	
	public function get_uninstall_function_detail($modulename,$table_name,$drop_db){
		
		if($drop_db ==1){
			$file_detail = "\$".$modulename."_sql = \$this->dbforge->drop_table('".$table_name."');\n";
			$file_detail .= "return \$".$modulename."_sql;";
		}else{
			$file_detail = "return True;";
		}
	
		return $file_detail;
	}
	
	public function create_module_role($module_role = array())
	{
		
		$file_detail = "";
		
		if($module_role){
			$file_detail .= "'roles' =>array(";
			for($i=0;$i<count($module_role);$i++)
			{
				$file_detail .= "'".$module_role[$i]."',";
			}
			
			$file_detail = substr($file_detail,0,-1);
			$file_detail .= "),";
			
		}
		
		
		return $file_detail;
		
	}
	
	
	public function categories_detail($module_name=null,$cate_title=null)
	{
		$file_detail = "
//------------------------- Begin Short Cut ------------------//
			'".$cate_title."'=>array(
					'name'=>'".ucfirst($cate_title)."',
					'uri' =>'admin/".$module_name."/".$cate_title."',
					
					'shortcuts' => array(
						'create' => array(
						'name'=>'".$cate_title."_create_title',
						'uri'=>'admin/".$module_name."/".$cate_title."/create_".$cate_title."',
						'class'=>'add'
						),
				
					),

				),
//------------------------- End Short Cut-----------------------//
";
		return $file_detail;
	}
	
	
	
	
	
	public function create_plugin($module_name = null)
	{
		$file_detail = 
		"<?php defined('BASEPATH') or exit('No direct script access allowed');
		
		
		
		
class Plugin_".ucfirst($module_name)." extends Plugin
{
							
						
					
				
			
		
	
}";
	return $file_detail;

	}
	

	public function create_routes($module_name = null)
	{
		$file_detail = 
		"<?php defined('BASEPATH') or exit('No direct script access allowed');
		
\$route['".$module_name."/index']			= 'admin$1';



\$route['".$module_name."/index/(:any)']	= '".$module_name."';";

		return $file_detail;	
		
		
	}
	
	public function append_cate_routes($base_module_name=null,$module_cate_name=null)
	{
		$file_detail =
		"\n\$route['".$base_module_name."/admin/".$module_cate_name."(:any)?'] = 'admin_".$module_cate_name."\$1';";
		
		return $file_detail;
	}
	
}
