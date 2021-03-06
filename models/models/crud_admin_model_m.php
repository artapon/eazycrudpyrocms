<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Crud_Admin_Model_M extends CI_Model{
	
	
	protected $status_str;
	protected $delete_str;
	protected $function_del_file_str;
	protected $update_str;
	protected $create_str;
	protected $sorts_str;
	protected $real_table;
	
	
	public function create_model($data = array())
	{
		
		$module_path = "";
		
		if($data['sub_module'] == "sub_module"){
			
			$module_path = $data['base_module'];
			
		}else{
			
			$module_path = $data['module_slug'];
		}
		
		$this->real_table 	= Easy_Database_Manage::get_real_table($data['database_table']);
		$desc 				= Easy_Database_Manage::get_table_desc($data['database_table']);
		$check_exist_file 	= Easy_Database_Manage::check_structure_exists_file($data['database_table']);
		
		foreach($desc as $row){
			
			if($row->Field == "status"){
				
				$this->status_str = "\$this->db->where(\"status\",\$this->input->post('f_status'));";
				
			}
		}
		
		if($check_exist_file['has_file']=='has_file'){
			
			$this->update_str 		= $this->_update_has_file($data);
			$this->create_str 		= $this->_create_has_file($data);
			$this->delete_str 		= $this->_delete_has_file($data);
			
		}else{
			
			$this->create_str = $this->_create_model($data);
			$this->update_str = $this->_update_model($data);
			$this->delete_str = $this->_delete_model($data);
			
		}
		
		$get_input  = substr($this->_get_input($data['database_table'],'create'),0,-1);
		
		$file_detail =
		 "<?php defined('BASEPATH') or exit('No direct script access allowed');
		 
		 
		 
class ".ucfirst($data['module_slug'])."_m extends MY_Model{
			
	public function _join_table()
	{
		/* Add join condition here 
		Ex \$this->db->select('".$this->real_table.".*,second_database.*');
			\$this->db->join('second_database', 'second_database.id = ".$this->real_table.".ref_second_database_id');
		*/";
		if($data['check_db_table_exists_file']['has_file'] == 'has_file')
		{
			$file_detail .= "
		\$this->db->select('".$this->real_table.".*,files.filename');
		\$this->db->join('files', 'files.id = ".$this->real_table.".".$data['check_db_table_exists_file']['Field']."','LEFT');
			";
			
		}
			
		$file_detail .= "
		return \$this->db->get('".$this->real_table."');
	}	
		
	
	public function get_".$data['module_slug']."_all()
	{
		if(\$this->input->post('f_keywords')){
			// Add Like condition here
		\n";
		if($data['check_db_table_exists_file']['has_file'] == 'has_file')
		{
			$file_detail .= "\$this->db->like(\"".$this->real_table.".".Easy_Database_Manage::get_table_desc_seccond_row($data['database_table'])."\",\$this->input->post('f_keywords'));\n";
			
		}else{
			
			$file_detail .= "\$this->db->like(\"".Easy_Database_Manage::get_table_desc_seccond_row($data['database_table'])."\",\$this->input->post('f_keywords'));\n";
			
		}
				
		$file_detail .="
		}
		
		if(\$this->input->post('f_status')){
			
			".$this->status_str."
		}
		
		return \$this->_join_table()->result();
		
	}
	
	public function get_".$data['module_slug']."_by_id(\$id)
	{\n";
		
	if($data['check_db_table_exists_file']['has_file'] == 'has_file')
	{
		$file_detail .= "\$this->db->where('".$this->real_table.".".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."',\$id);\n";
		
	}else{
		
		$file_detail .= "\$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."',\$id);\n";
		
	}
	
		
	$file_detail .="return \$this->_join_table()->row();
		
	}
	
	".$this->create_str."
	".$this->delete_str."
	".$this->function_del_file_str."
	".$this->update_str."
	".$this->_table_action_model($data['database_table'])."
	
}
	";
		return $file_detail;
	}
	
	
	private function _create_model($data = array()){
		
		$get_input  = substr($this->_get_input($data['database_table'],'create'),0,-1);
		
		$file_detail ="
			public function create_".$data['module_slug']."(\$input = array())
			{
				
				 \$this->db->insert('".$this->real_table."',array(\n".
									$get_input."
									
				));
				
				return \$this->db->insert_id();
			}";
			
		return $file_detail;
	}
	
	public function _update_model($data = array())
	{
		$get_input  = substr($this->_get_input($data['database_table'],'update'),0,-1);
		
		$file_detail = "
		public function update_".$data['module_slug']."(\$id = null,\$input = null)
		{
			
			return \$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."',\$id)->update('".$this->real_table."',array(\n".
											$get_input."
		));
		
		}

		";
		return $file_detail;
	}
	
	public function _delete_model($data = array())
	{
		
		$file_detail = "
			public function delete_".$data['module_slug']."(\$id = null)
			{
			
				return \$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."', \$id)->delete('".$this->real_table."');
			}
		";
		return $file_detail;
		
	}
	
	public function _delete_has_file($data = array())
	{
		$file_detail = "
			public function delete_".$data['module_slug']."(\$id = null)
			{
				\$file = \$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."', \$id)->get('".$this->real_table."')->row();
		
				if(!empty(\$file))
				{
					Files::delete_file(\$file->".$data['check_db_table_exists_file']['Field'].");
				}
		
				return \$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."', \$id)->delete('".$this->real_table."');
			}
		";
		return $file_detail;
	}
	
	
	private function _table_action_model($table_name)
	{
		$file_detail = '';
		
		if(Easy_Database_Manage::check_structure_exists($table_name,'sorts')){
		
			$file_detail = "public function update_sort(\$sorts = array()){
				foreach(\$sorts['sorts'] as \$key => \$val){
					
					\$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($table_name)->Field."', \$key)
								->update('".$this->real_table."',
								  array('sorts'  => \$val
				));
				}
				
				return TRUE;
			}
			
			public function update_reset_sort(\$sorts=array())
			{
				foreach(\$sorts['sorts'] as \$key => \$val)
				{
					
					\$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($table_name)->Field."', \$key)
								->update('".$this->real_table."',
								  array('sorts'  => \$key
				));
				}
		
				return TRUE;
			}";
		}
		
		if(Easy_Database_Manage::check_structure_exists($table_name,'status')){
			
			$file_detail .= "public function update_draft_live(\$id)
			{
				return \$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($table_name)->Field."', \$id)
								->update('".$this->real_table."',
								  array('status'  => 'live'));
			}";
		}
		return $file_detail;
	}
	
	public function _get_input($table_name = null,$function_type = null)
	{
		$create_update_input_str = "";
		
		$desc = Easy_Database_Manage::get_table_desc($table_name);
		
		foreach($desc as $row)
		{
			if($row->Extra!="auto_increment"){
					
				if(strpos($row->Field,'file') !== false){
					
					$create_update_input_str.= "'".$row->Field."'=>\$result['data']['id'],\n";
					
				}elseif($row->Field == 'created_on'){
					
					if($function_type =='update'){
						
						$create_update_input_str .="";
						
					}else{
						
						$create_update_input_str.= "'".$row->Field."'=>time(),\n";
						
					}
						
				}elseif($row->Field == 'date_pk'){
					
					$create_update_input_str.= "'".$row->Field."'=>strtotime(\$input['".$row->Field."']),\n";
					
				}else{
					
					$create_update_input_str.= "'".$row->Field."'=>\$input['".$row->Field."'],\n";
					
				}
				
			}
		}
		return $create_update_input_str;
	}
	
	private function _create_has_file($data = array())
	{
		
		$get_input  = substr($this->_get_input($data['database_table'],'create'),0,-1);
		
		$file_detail ="
		public function create_".$data['module_slug']."(\$input = array(),\$result = array())
		{
		
		 \$this->db->insert('".$this->real_table."',array(\n".
									$get_input."
							
		));
		
			return \$this->db->insert_id();\n
			
		}
		
		public function get_folder_id()
		{
			return \$this->db->where('slug','".$data['module_slug']."-files')->get('file_folders')->row();
		
		}
	";
	return $file_detail;
	}

	private function _update_has_file($data = array())
	{
		$get_input  = substr($this->_get_input($data['database_table'],'update'),0,-1);
		
		$file_detail = "
		public function update_".$data['module_name']."(\$id = null,\$input = null,\$result = array())
		{
			
			return \$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."',\$id)->update('".$this->real_table."',array(\n".
											$get_input."
		));
		
		}

		";
		return $file_detail;
	}
	
		
}