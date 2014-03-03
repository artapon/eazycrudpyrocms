<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */
class Crud_Field_Type 
{
	
	public static function field_type_textarea($module_name,$field,$null)
	{
		$str_input_html =
				"<li class=\"editor\">
					<label for=\"body\"><?php echo lang('".$module_name.".".$field."_label'); ?> ";
					
		$str_input_html .= Crud_Field_Type::_span_required($field, $null);
					
		$str_input_html .=
				"
				</label>\n
				<div class=\"input\">\n
				
					<?php echo form_dropdown('type',array(\n
												'html'=>'html',\n
												'markdown'=>'markdown',\n
												'wysiwyg-simple'=>'wysiwyg-simple',\n
												'wysiwyg-advanced'=>'wysiwyg-advanced',\n
												),\$".$module_name."->type); ?>\n
					</div>\n
										
					<br style=\"clear:both\"/>\n
												
					<?php echo form_textarea(array('id'=>'".$field."','name'=>'".$field."','class' => \$".$module_name."->type,'value'=>\$".$module_name."->".$field.")); ?>\n
				</li>";
				
		return $str_input_html;
	}
	
	public static function field_type_simple_textarea($module_name,$field,$null)
	{
		$str_input_html =
				"<li class=\"editor\">
					<label for=\"body\"><?php echo lang('".$module_name.".".$field."_label'); ?>";
		$str_input_html .= Crud_Field_Type::_span_required($field, $null);
		$str_input_html .= "</label>\n
				 	<div class=\"input\">\n
										
					<br style=\"clear:both\"/>\n
												
					<?php echo form_textarea(array('id'=>'".$field."','name'=>'".$field."','class' => 'wysiwyg-simple','value'=>\$".$module_name."->".$field.")); ?>\n
				</li>";
				
		return $str_input_html;
	}
	
	public static function field_type_text($module_name,$field,$null)
	{
		$str_input_html = "
				<li>\n
					<label for=\"".$field."\"><?php echo lang('".$module_name.".".$field."_label'); ?>";
		$str_input_html .= Crud_Field_Type::_span_required($field, $null);
		$str_input_html	.= "</label>\n
					<div class=\"input\"><?php echo form_input('".$field."',\$".$module_name."->".$field."); ?></div>\n
				</li>\n";
		
		return $str_input_html;
	}
	
	public static function field_type_date_pk($module_name,$field,$null)
	{
		$str_input_html = "
			<?php
				\$datestring = \"%Y-%m-%d\";
				\$time = 0;
				\$time = @\$".$module_name."->".$field.";		
			?>
			
			<li>\n
				<label for=\"".$field."\"><?php echo lang('".$module_name.".".$field."_label'); ?>";
				
		$str_input_html .= Crud_Field_Type::_span_required($field, $null);
		$str_input_html	.= "</label>\n
				<div class=\"input\"><?php echo form_input('".$field."',@mdate(\$datestring, \$time),'class=\"datepicker\"'); ?></div>\n
			</li>\n";
			
		return $str_input_html;
	}
	
	public static function field_type_status($module_name,$field,$null)
	{
		$str_input_html = 				
				"<li>
					<label for=\"status\"><?php echo lang('".$module_name.".status_label'); ?>";
		$str_input_html .= Crud_Field_Type::_span_required($field, $null);
		$str_input_html	.= "</label>\n
					<div class=\"input\"><?php echo form_dropdown('".$field."', array('draft' => lang('".$module_name.".draft_label'), 'live' => lang('".$module_name.".live_label')),\$".$module_name."->status); ?></div>
				</li>";;
		
		return $str_input_html;
	}
	public static function field_type_file_upload($module_name,$field,$null = 'NO')
	{
		$str_html = "
				<input type=\"hidden\" name=\"delete_file\" value=\"0\" />
				<li <?php if(empty(\$".$module_name."->".$field.")){ ?> style=\"display:none\" <?php } ?> >
					 <div class=\"input\">
						<label for=\"image\"><?php echo lang('".$module_name.".file_label'); ?>  :</label>
						<?php if(!empty(\$".$module_name."->".$field.")){ ?>
						<img src=\"<?php echo base_url();?>uploads/".$module_path."/<?php echo (\$".$module_name."->".$field."?\$".$module_name."->".$field.":''); ?>\" style=\"max-height:200px; max-width:200px;\"  /> 
						<input type=\"checkbox\" name=\"delete_file\" value=\"1\" onclick=\"checkchkdata('1')\" /><?php echo lang('".$module_name.".change_file_label'); ?><?php } ?>
					</div>
				</li>
				<li id=\"upload_img\" <?php if(!empty(\$".$module_name."->".$field.")){ ?> style=\"display:none;\" <?php } ?>>
                 	<label for=\"nothing\"><?php echo lang('".$module_name.".file_label'); ?> </label>
                  	<div class=\"input\"><?php echo form_upload('".$module_name."_file');?></div>
                </li>";
		
		return $str_html;
	}
	
	public static function field_show_type_date($module_name,$field)
	{
		$str_html = "
				<div class =\"row\">
					<label for=\"".$field."\"> <b><?php echo lang('".$module_name.".".$field."_label'); ?> : </b></label>\n
					<div class=\"".$field."\" ><?php echo format_date(\$row->".$field.");?></div>\n
				</div>";
		
		return $str_html;
	}
	
	public static function field_show_text($module_name,$field)
	{
		$str_html = "
				<div class =\"row\">
					<label for=\"".$field."\"> <b><?php echo lang('".$module_name.".".$field."_label'); ?> : </b></label>\n
					<div class=\"".$field."\" ><?php echo \$row->".$field.";?></div>\n
				</div>";

		return $str_html;
	}
	
	public static function field_show_save_button($module_name)
	{
		$str_html = 
				"<div class=\"buttons float-right padding-top\" style=\"text-align: center;padding-bottom: 10px;\" >
					<button id=\"new\" type=\"submit\" name=\"btnAction\" value=\"save_new\" class=\"btn green\">
						<span><?php echo lang('".$module_name.".create_new_label'); ?></span>
					</button>
					<?php \$this->load->view('admin/partials/buttons', array('buttons' => array('save','save_exit', 'cancel') )); ?>
				</div>";
		
		return $str_html;
	}
	
	public static function field_show_sort_button($module_name)
	{
		$str_html = "
				<button id=\"btn-sort\" type=\"submit\" name=\"btnAction\" value=\"sort\" class=\"btn orange\">
				<span><?php echo lang('".$module_name.".sorts_label'); ?></span>
				</button>
				<button id=\"btn-re-sort\" type=\"submit\" name=\"btnAction\" value=\"re-sort\" class=\"btn blue\" onclick=\"return confirm('Are you sure you want to reset sort ?');\" >
				<span><?php echo lang('".$module_name.".re-sort_label'); ?></span>
				</button>";
		
		return $str_html;
	}
	
	public static function field_show_delete_edit_button($base_url,$module_name,$field)
	{
		$str_html = "
			<?php echo anchor('".$base_url."edit_".$module_name."/'.\$row->".$field.",lang('global:edit'),'class=\"btn orange edit\"');?>\n
			<?php echo anchor('".$base_url."delete_".$module_name."/'.\$row->".$field.",lang('global:delete'),array('class'=>'confirm btn red delete'));?>";
		
		return $str_html;		
	}
	
	private static function _span_required($field,$null)
	{
		
		$str_input_html = '';
		
		if($null == 'NO')
		{
			if($field =='text' || $field == 'tinytext' || $field == 'mediumtext' || $field == 'longtext')
			{
				$str_input_html = "";
			}else{
				
				$str_input_html = "<span>*</span>";
				
			}
			
			
		}
		
		return $str_input_html;
	}
	
		
}
	