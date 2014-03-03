<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Crud_Front_Model_M extends CI_Model{
	
	
	protected $status_str;
	protected $delete_file_str;
	protected $function_del_file_str;
	protected $update_str;
	protected $create_str;
	protected $sorts_str;
	protected $real_table;
	
	
	public function create_front_model($data = array())
	{
		
		$this->real_table 	= Easy_Database_Manage::get_real_table($data['database_table']);
		
		return $this->_front_model($data);
	}
	
	public function _front_model($data = array())
	{
		$desc = Easy_Database_Manage::get_table_desc($data['database_table']);
		
		foreach($desc as $row){
			
			if($row->Field == "status"){
				if(Easy_Database_Manage::check_structure_exists($data['database_table'],'status')){
					
					$this->status_str = "\$this->db->where(\"status\",'live');";
					
				}
				
			}elseif($row->Field == "sorts"){
				
				if(Easy_Database_Manage::check_structure_exists($data['database_table'],'sorts')){
					
					$this->sorts_str = "\$this->db->order_by('sorts','DESC');";
					
				}
				
			}
		}

		$file_detail ="<?php defined('BASEPATH') or exit('No direct script access allowed');
class ".ucfirst($data['module_slug'])."_front_m extends MY_Model{
			
	public function _join_table()
	{
		/* Add join condition here 
		Ex \$this->db->select('".$this->real_table.".*,second_database.*');
			\$this->db->join('second_database', 'second_database.id = ".$this->real_table.".ref_second_database_id');
		*/
			
		return \$this->db->get('".$this->real_table."');
	}	
		
	public function get_".$data['module_slug']."_all(\$per_page = null,\$page = null)
	{
		".$this->status_str."\n	
		".$this->sorts_str."\n
		\$this->db->limit(\$per_page,\$page);
		return \$this->_join_table()->result();		
	}		
	
	public function get_".$data['module_slug']."_by_id(\$id=null)
	{
		\$this->db->where('".Easy_Database_Manage::get_table_desc_first_row($data['database_table'])->Field."',\$id);
		return \$this->_join_table()->row();
	}
	
}
		";
		return $file_detail;
	}
	
	
		
}