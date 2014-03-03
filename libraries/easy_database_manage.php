<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Easy_Database_Manage extends CI_Model
{
	
	/**
	* return list of DESCRIBE database table
	* @param string $table_name database table name
	* @return object
	*/
	public static function get_table_desc($table_name)
	{
		
		return Easy_Database_Manage::_describe_table($table_name)->result();
		
	}
	
	/**
	* return DESCRIBE database table one row
	* @param string $table_name database table name
	* @return object
	*/
	public static function get_table_desc_first_row($table_name)
	{

		return Easy_Database_Manage::_describe_table($table_name)->row();
	}
	
	/**
	* return DESCRIBE database table seccond row
	* @param string $table_name database table name
	* @return object
	*/
	public static function get_table_desc_seccond_row($table_name)
	{
		
		$i = 0;
		
		$field = "";
		
		$desc = Easy_Database_Manage::_describe_table($table_name)->result();
		
		if(!empty($desc)){
			
			foreach($desc as $row){
				
				if($i ==1){
					
					$field = $row->Field;
					
					break;
					
				}
				$i++;
			}
			
		}
		
		return $field;

	}

	public static function get_status_col($table_name)
	{
		return Easy_Database_Manage::_describe_table($table_name)->row();
	}

	/**
	* return real table name without default
	* @param string $table_name database table name
	* @return string
	*/
	public static function get_real_table($table_name)
	{
	 	if(count(explode('default_',$table_name))>0)
		{
			return @end(explode('default_',$table_name));
			
		}else{
			
			return $table_name;
			
		}
	}
	
	/**
	* check exists table
	* @param string $table_name database table name
	* @return bool
	*/
	public static function check_table_exists($table_name)
	{
	 	return ci()->db->table_exists($table_name);
	}
	
	/**
	* return list of table array
	* @param string $table_name database table name
	* @return array
	*/
	public static function get_list_table()
	{
	 	$list_table = array();
	 	
		$get_list_table = array();
		
	 	$get_list_table = ci()->db->list_tables();
		
		if(!empty($get_list_table)){
			
			foreach($get_list_table as $key=>$val){
				
				if(count(explode("default_",$val))>1){
					
					$list_table[$val] = $val;
					
				}
				
			}
		}
		
	 	return $list_table;
	}
	
	/**
	* check if list database contains text file
	* @param string $table_name database table name
	* @return bool
	*/ 
	public static function check_structure_exists_file($table_name)
	{
	 	
		$desc = Easy_Database_Manage::_describe_table($table_name)->result();
		
		foreach($desc as $row){
			
			if(strpos($row->Field,'file') !== false){
				
				$result = array('has_file'=>'has_file','Field'=>"".$row->Field."");
				
				break;
				
			}else{
				
				$result = array('has_file'=>'','Field'=>"");
				
			}
		}

		return $result;
	}
	
	public static function check_structure_exists_sorts($table_name)
	{
		$desc = Easy_Database_Manage::_describe_table($table_name)->result();
		
		foreach($desc as $row){
			
			if(strpos($row->Field,'sorts') !== false){
				
				return TRUE;
				exit;
				
			}else{
				
				return FALSE;
				exit;
				
			}
		}

	}
	
	public static function check_structure_exists($table_name,$field)
	{
		$desc = Easy_Database_Manage::_describe_table($table_name)->result();
		
		foreach($desc as $row){
			
			if(strpos($row->Field,$field) !== false){
				
				return TRUE;
				exit;
				
			}else{
				
				return FALSE;
				exit;
				
			}
		}

	}
	
	/**
	* return DESCRIBE
	* @param string $table_name database table name
	 *@param string $table real table name
	* @return object
	*/
	private static function _describe_table($table_name)
	{
	 	$table = ci()->db->escape_str($table_name);
		
		if(count(explode('default_',$table_name))>1)
		{
			$table = ci()->db->escape_str($table_name);
			
		}else{
			
			$table = ci()->db->escape_str('default_'.$table_name);
		}
		
	 	$sql = "DESCRIBE `$table`";
		return ci()->db->query($sql);
	 }
	
	public static function field_lenght($field_type)
	{
		$lenght = filter_var($field_type, FILTER_SANITIZE_NUMBER_INT);
		
		if(!empty($lenght) && is_numeric($lenght))
		{
			if(strpos($field_type,'enum') == false )
			{
				$lenght = '|max_length['.$lenght.']';
				
			}else{
				
				$lenght = '';
			}
			
		}else{
			
			$lenght = '';
		}
		
		return $lenght;
	}
}
	