<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Lang_m extends MY_Model
{

	public function create_lang($data = array(),$nation = 'en')
	{
		
		$desc = Easy_database_manage::get_table_desc($data['database_table']);
		
		$file_detail =
		 "<?php defined('BASEPATH') or exit('No direct script access allowed');\n\n
		 ".$this->base_lang($data['module_slug'],$nation)."
		 
\$lang['".trim($data['module_slug']).".name']							='".ucfirst($data['module_name'])."';\n\n";
		
	foreach($desc as $row){
		
		if($nation =='en'){
			
			if(strpos($row->Field,'file') !== false){
				
				$file_detail .= "\$lang['".$data['module_slug'].".file_label']	='".ucfirst($row->Field)."';\n\n";
				$file_detail .= "\$lang['".$data['module_slug'].".change_file_label']	='Delete or Change file';";
				
			}elseif($row->Field == "sorts"){
				
				$file_detail .= "\$lang['".$data['module_slug'].".sorts_label']	='Sorts';\n\n";
				$file_detail .= "\$lang['".$data['module_slug'].".re-sort_label']	='Reset Sorts';\n\n";
				
			}elseif($row->Field == "status"){
				
				$file_detail .= "\$lang['".$data['module_slug'].".publish_label']	='Publish';\n\n";
				
			}else{
				
				$file_detail .= "\$lang['".$data['module_slug'].".".$row->Field."_label']				='".ucfirst($row->Field)."';\n\n";
		
			}
		}else{
			
			if(strpos($row->Field,'file') !== false){
				
				$file_detail .= "\$lang['".$data['module_slug'].".file_label']	='".ucfirst($row->Field)."';\n\n";
				$file_detail .= "\$lang['".$data['module_slug'].".change_file_label']	='ลบหรือเปลี่ยนไฟล์';";
				
			}elseif($row->Field == "sorts"){
				
				$file_detail .= "\$lang['".$data['module_slug'].".sort_label']	='เรียงข้อมูล';\n\n";
				$file_detail .= "\$lang['".$data['module_slug'].".re-sort_label']	='รีเซตการเรียงข้อมูล';\n\n";
				
			}elseif($row->Field == "status"){
				
				$file_detail .= "\$lang['".$data['module_slug'].".publish_label']	='ตีพิมพ์';\n\n";
				
			}else{
				$file_detail .= "\$lang['".$data['module_slug'].".".$row->Field."_label']				='".ucfirst($row->Field)."';\n\n";
			}
		}
		
			 
		}
	
		return $file_detail;
	}

	public function create_permission_lang($modulename = "",$condition = "")
	{
		$head_file_lang = "";
		
		if($condition == "basemodule"){
			
			$head_file_lang = "<?php defined('BASEPATH') OR exit('No direct script access allowed');";
			
		}else{
			
			$head_file_lang = "";
			
		}
		
		$file_detail =
"".$head_file_lang."

\n// ".ucfirst($modulename). "Permissions
\$lang['".$modulename.".role_put_live']		= 'Put ".$modulename." live';
\$lang['".$modulename.".role_edit_live']	= 'Edit ".$modulename." articles';
\$lang['".$modulename.".role_delete_live'] 	= 'Delete live ".$modulename."';";

		return $file_detail;
	}
	public function base_lang($modulename="",$nation)
	{
		$file_detail ="";
		if($nation =='en'){

$file_detail .= "\$lang['".$modulename.".status_label']							='Status';\n\n
\$lang['".$modulename.".draft_label']							='Draft';\n\n
\$lang['".$modulename.".live_label']							='Live';\n\n
\$lang['".$modulename.".currently_no_data']						='No Data';\n\n
\$lang['".$modulename.".limit_label']							='Data Perpage';\n\n
\$lang['".$modulename.".create_new_label']						='Save & Create New';\n\n


\$lang['create_success']										='Add Success';\n\n
\$lang['create_error']											='Add Failed';\n\n
\$lang['edit_success']											='Edit Success';\n\n
\$lang['edit_error']											='Edit Error';\n\n
\$lang['delete_success']										='Delete Success';\n\n
\$lang['delete_error']											='Delete Failed';\n\n


\$lang['".$modulename."_create_title']							='Add ".ucfirst($modulename)."';\n\n
\$lang['".$modulename.".content_label']							='Add ".ucfirst($modulename)."';\n\n
\$lang['".$modulename.".create_".$modulename."_title_label']	='Add ".ucfirst($modulename)."';\n\n
\$lang['".$modulename.".edit_".$modulename."_title_label']		='Add ".ucfirst($modulename)."';\n\n
\$lang['".trim($modulename).".name']							='".ucfirst($modulename)."';\n\n";
}else{
	$file_detail .= "\$lang['".$modulename.".status_label']		='สถานะ';\n\n
\$lang['".$modulename.".draft_label']							='แบบร่าง';\n\n
\$lang['".$modulename.".live_label']							='ไลฟ์';\n\n
\$lang['".$modulename.".currently_no_data']						='ไม่มีข้อมูล';\n\n
\$lang['".$modulename.".limit_label']							='แสดงข้อมูลต่อหน้า';\n\n


\$lang['create_success']										='เพิ่มข้อมูลสำเร็จ';\n\n
\$lang['create_error']											='เพิ่มข้อมูลล้มเหลว';\n\n
\$lang['edit_success']											='แก้ไขข้อมูลสำเร็จ';\n\n
\$lang['edit_error']											='แก้ไขข้อมูลล้มเหลว';\n\n
\$lang['delete_success']										='ลบข้อมูลสำเร็จ';\n\n
\$lang['delete_error']											='ลบข้อมูลล้มเหลว';\n\n


\$lang['".$modulename."_create_title']							='เพิ่ม ".ucfirst($modulename)."';\n\n
\$lang['".$modulename.".content_label']							='เพิ่ม ".ucfirst($modulename)."';\n\n
\$lang['".$modulename.".create_".$modulename."_title_label']	='เพิ่ม ".ucfirst($modulename)."';\n\n
\$lang['".$modulename.".edit_".$modulename."_title_label']		='เพิ่ม".ucfirst($modulename)."';\n\n";
}
return $file_detail;
}


public function create_blank_lang(){
		
		$file_detail =
		 "<?php defined('BASEPATH') or exit('No direct script access allowed');\n\n";
		return $file_detail;
	}

}
	