<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Crud_Script 
{
	
	public static function datepicker_script($selector = '.datepicker',$format = 'yy-mm-dd')
	{
		$str_html = "
		<script>
			\$(function() {
				\$( ".$selector." ).datepicker({
					changeMonth: true,
					changeYear: true
				});
				\$( ".$selector." ).datepicker( \"option\", \"dateFormat\",'".$format."');
				\$( ".$selector." ).datepicker( 'setDate',<?php echo time();?>);
			});
		</script>";
	
	return $str_html;
	}
	
	public static function checkint_script()
	{
		$str_html = "
		<script>
			 function checkint(id,val)
			 {			
			 	if(isNaN(document.getElementById('sort-id-'+id).value)){
			 		document.getElementById('sort-id-'+id).value=val;
			 	}
			 }
		</script>
		";
		
		return $str_html;
	}
	
	public static function set_tablesorter_script($module_name)
	{
		$str_html = "
			<script>
				$(document).ready(function(){
					 
			        $(\"#".$module_name."_table\").tablesorter(); 
			        
			    }); 
			</script>
				";
		
		return $str_html;
	}
	
	public static function show_hide_file_input()
	{
		$str_html = "
			<script>
				function checkchkdata(chkdata){
			       if (document.getElementById('upload_img').style.display == 'none'){
			           document.getElementById(\"upload_img\").style.display = \"\";
			       }else{
			            document.getElementById(\"upload_img\").style.display = \"none\";
			       	}
			   	}
			</script>
		";
		
		return $str_html;
	}
}