<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Crud_View_M extends MY_Model
{
	
	protected $str_button_sort = '';
	
	public function form_view($database_table,$modulename,$position,$base_module = null,$sub_module = "")
	{
		return $this->create_form_html($database_table,$modulename,$position,$base_module,$sub_module);
	}
	
	public function admin_preview_view($module_name = null)
	{
		$file_detail = "<h3>File Path : addons/default/modules/<font color=\"FF6900\">".strtolower($module_name)."</font>/views/admin/preview.php</h3>";
		return $file_detail;
	}
	public function front_view($module_name = null,$table_name=null)
	{
		$file_detail = "<h3>File Path : addons/default/modules/<font color=\"FF6900\">".strtolower($module_name)."</font>/views/index.php</h3>\n";
		$file_detail .="
<style>
	.row{
		background-color:#ccc;
		margin-bottom:3px;
		padding:5px;
	}
		
</style>";
		$list_field = "";
		
		$desc = Easy_Database_Manage::get_table_desc($table_name);
		
		foreach($desc as $row){
			
			if($row->Field =='created_on' || $row->Field =='date_pk'){
				
				$list_field .= Crud_Field_Type::field_show_type_date($module_name,$row->Field);
				
			}elseif(strpos($row->Field,'file') !== false){
				
				$list_field .= Crud_Field_Type::field_show_type_date($module_name,$row->Field);		
						
			}else{
			
				$list_field .= Crud_Field_Type::field_show_text($module_name,$row->Field);
			}		
		}

		$file_detail .= "
<?php if(!empty(\$".$module_name.")):?>
	<?php foreach(\$".$module_name." as \$row):?>
		<div class=\"row\">\n
					".$list_field."
		</div>
	<?php endforeach;?>
<?php endif;?>
<div class=\"pagination\">
		<?php echo \$pagination; ?>
</div>
		
		"; 

		return $file_detail;
	}

	public function admin_index_view($base_url = null,$module_name = null,$table_name = null)
	{
		
		$str_button_status = "";
		$desc = Easy_Database_Manage::get_table_desc($table_name);
		foreach($desc as $row)
		{
			if($row->Field == "sorts"){
				
			}
		}
	
		$file_detail = 
"<section class=\"title\">\n
		<h4><?php echo lang('".$module_name.".name'); ?></h4>\n
</section>\n

<section class=\"item\">
	<div class=\"content\">
	<?php template_partial('filters'); ?>\n
	
	<?php echo form_open('".$base_url."/table_action'); ?>\n
	
		<div id=\"filter-stage\">
			<?php template_partial('tables/table_body'); ?>\n
		</div>\n
	<?php echo form_close(); ?>\n
</div>
</section>";
		
		return $file_detail;
	}
	
	public function admin_form_view($table_name ="",$modulename,$position,$base_module = null,$sub_module = "")
	{
		$str_input_html  = "";
		
		$str_html_javascript = "";
		
		$module_path = "";
		
		if($sub_module == "sub_module"){
			
			$module_path = $base_module;
			
		}else{
			
			$module_path = $modulename;
			
		}
		
		$desc = Easy_Database_Manage::get_table_desc($table_name);
		
		$str_input_html = 

"
<style>
	#".$modulename."_table td{
		vertical-align: middle;
		text-align: center;
		line-height: 20px;
	}
</style>
<section class=\"title\">\n
<div class=\"content\">
	<?php if(\$this->method ==='create_".$modulename."'): ?>\n
		<h4><?php echo sprintf(lang('".$modulename.".create_".$modulename."_title_label')); ?></h4>\n
	<?php else: ?>\n
		<h4><?php echo sprintf(lang('".$modulename.".edit_".$modulename."_title_label')); ?></h4>\n
	<?php endif;?>\n
</section>\n

<section class=\"item\">\n
<div class=\"content\">						
<?php echo form_open_multipart(uri_string(),'class=\"crud\"');?>\n
	<div class=\"tabs\">\n
		<ul class=\"tab-menu\">\n
			<li><a href=\"#".$modulename."-content\"><span><?php echo lang('".$modulename.".content_label'); ?></span></a></li>\n
		</ul>\n
	
	<div class=\"form_inputs\" id=\"".$modulename."-content\">\n
	
		<fieldset>\n
			<ul>\n";
		
		foreach($desc as $row)
		{
			if($row->Extra == "auto_increment")
			{
				$str_input_html .= "";
				
			}elseif($row->Type == "text" || $row->Type == 'tinytext' || $row->Type == 'mediumtext' || $row->Type == 'longtext'){
				
				if(strpos($row->Field,'intro') !== false){
					
					$str_input_html .= Crud_Field_Type::field_type_simple_textarea($modulename,$row->Field,$row->Null);
					
				}else{
					
					$str_input_html .= Crud_Field_Type::field_type_textarea($modulename,$row->Field,$row->Null);
					
				}
				
			}elseif($row->Field == "status"){
				
				$str_input_html .= Crud_Field_Type::field_type_status($modulename,$row->Field,$row->Null);
				
			}elseif(strpos($row->Field,'file') !== false){
				
				$str_input_html .= Crud_Field_Type::field_type_file_upload($modulename,$row->Field,$row->Null);
				
				$str_html_javascript = Crud_Script::show_hide_file_input();
				
			}elseif($row->Field == 'created_on'){
				
				$str_input_html .= "" ;
				
			}elseif($row->Field == 'date_pk'){
				
				$str_html_javascript .= Crud_Script::datepicker_script();

				$str_input_html .= Crud_Field_Type::field_type_date_pk($modulename,$row->Field,$row->Null); 

			}else{
				
				$str_input_html .= Crud_Field_Type::field_type_text($modulename,$row->Field,$row->Null);
				
			}
			
		}

		$str_input_html .=
			"</ul>\n
		
		</fieldset>\n
									
	</div>\n
</div>\n";
							
		$str_input_html .= Crud_Field_Type::field_show_save_button($modulename);

$str_input_html .= "</div>\n
<?php echo form_close(); ?>\n
</div>
</section>"

.$str_html_javascript;
		
		return $str_input_html;
	}

	public function filters_view($modulename="",$table_name="")
	{
		$str_filters = 
"<fieldset id=\"filters\">\n
	
	<legend><?php echo lang('global:filters'); ?></legend>
	
	<?php echo form_open(''); ?>
		<ul>
			<li>
				<?php echo lang('".$modulename.".status_label','f_status'); ?>
				<?php echo form_dropdown('f_status',array(0=>lang('global:select-all'),'draft'=>lang('".$modulename.".draft_label'),'live'=>lang('".$modulename.".live_label'))); ?>
			</li>
			<li><?php echo form_input('f_keywords'); ?></li>
			<li><?php echo anchor(current_url().'#',lang('buttons:cancel'),'class=\"cancel\"'); ?></li>
			<li>
				<?php echo lang('".$modulename.".limit_label','f_status'); ?>
				<?php echo form_dropdown('f_limit',array(10=>10,20=>20,30=>30));?>
			</li> 
		</ul>
	<div class=\"clear\"></div>
	<?php echo form_close(); ?>
	</div>
</fieldset>
			";
		return $str_filters;
	}
	
	public function tables_view($base_module,$modulename="",$table_name="",$position=null)
	{
		$i = 0;
		$str_button 		= "";
		$base_module_url 	= "";
		$file_path 			= "";
		$str_button_sort 	= "";
		$str_button_status	= "";
		
		if($base_module != Null){
			
			$base_url = "admin/".$base_module."/".$modulename."/";
			
			$file_path = $base_module;
			
		}else{
			
			$file_path = $modulename;
			
			$base_url	 ="admin/".$modulename."/";
		}
		
		$desc 		= Easy_Database_Manage::get_table_desc($table_name);
		$first_col	= Easy_Database_Manage::get_table_desc_first_row($table_name);
		$str_table	=
"
".Crud_Script::set_tablesorter_script($modulename)."
".Crud_Script::checkint_script()."

<section class=\"item\">
	<div class=\"content\">
<?php if (\$".$modulename."): ?>\n
<?php form_open('".$base_module_url."table_action');?>\n
<table border=\"0\" id=\"".$modulename."_table\" class=\"tablesorter\">\n
<thead>\n
<tr>\n
<th class=\"not-sortable\" width=\"20\"><?php echo form_checkbox(array('name'=>'action_to_all','class'=>'check-all')); ?></th>\n";

		foreach($desc as $row){
			if($row->Extra=="auto_increment"){
				
				$str_table .= "";
				
			}elseif($row->Field=="sorts"){
				
				$str_table .= "<th class=\"not-sortable\" >Sorts</th>";
				
				$str_button_sort = Crud_Field_Type::field_show_sort_button($modulename);
				
			}elseif($row->Field=="status"){
				
				$str_button_status  = ",'Publish'";
				
				$str_table .="<th><?php echo lang('".$modulename.".".$row->Field."_label'); ?></th>\n";
				
			}else{
			$str_table .=
"<th><?php echo lang('".$modulename.".".$row->Field."_label'); ?></th>\n";
				}
		}
		$str_table .=
"<th width=\"180\" class=\"not-sortable\"></th>\n
</tr>\n
</thead>\n
<tbody>\n
<?php foreach(\$".$modulename." as \$row ): ?>\n
<tr>\n";
		
		foreach($desc as $row){
			if($row->Extra=="auto_increment"){
				$str_table .=
					"<td class=\"not-sortable\" ><?php echo form_checkbox('action_to[]',\$row->".$row->Field.");?></td>\n
					";
$str_button = "<td>\n
".Crud_Field_Type::field_show_delete_edit_button($base_url,$modulename,$row->Field)."
</td>\n";
			}elseif($row->Field =="sorts"){
	
			$str_table .= "
			<td class=\"not-sortable\" style=\"width: 20px;\" class=\"not-sortable\" >
<?php echo form_input(array('id'=>'sort-id-'.\$row->".$first_col->Field.",'name'=>\"sorts[\" .\$row->".$first_col->Field.".\"]\",'value'=>\$row->".$row->Field.",'style'=>'width:30px;text-align:center','class'=>'sort-input','onkeyup'=>\"checkint(\".\$row->".$first_col->Field.".\",\".\$row->".$row->Field.".\");\"));?></td>";

			}elseif(strpos($row->Field,'file') !== false){
				
				$str_table .= "<td class=\"collapse\"  ><img width=\"100\" height=\"100\" src=\"<?php echo base_url();?>uploads/".$file_path."/<?php echo \$row->".$row->Field."; ?>\" /></td>\n";
				
			}elseif($row->Field == 'created_on' || $row->Field == 'date_pk'){
				
				$str_table .="<td><?php echo format_date(\$row->".$row->Field."); ?></td>\n";
			}
			else{
				if($i != 0){
					$str_table .=
					"<td><?php echo \$row->".$row->Field."; ?></td>\n";
				}else{
					$str_table .=
					"<td class=\"collapse\" ><?php echo \$row->".$row->Field."; ?></td>\n";
				}
				
				
			}
			$i++;
		}
		$str_table .= $str_button;
		$str_table .=
				  "</tr>\n
				<?php endforeach; ?>\n
</tbody>\n
<tfoot>
	<tr>
		<td colspan=\"".($i+1)."\">
			<div class=\"inner\"><?php \$this->load->view('admin/partials/pagination'); ?></div>
		</td>
	</tr>
</tfoot>
</table>\n
<div class=\"table_action_buttons\">\n
		<?php \$this->load->view('admin/partials/buttons',array('buttons'=>array('delete'".$str_button_status."))); ?>\n
		".$str_button_sort."
</div>\n

<?php form_close();?>\n
<?php else: ?>\n
	<div class=\"no_data\"><?php echo lang('".$modulename.".currently_no_data'); ?></div>\n
<?php endif;?>
</div>
</section>
";
				
		return $str_table;
	}
}