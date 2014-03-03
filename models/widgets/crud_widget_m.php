<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  
 * @author		Artapon Rittirote
 * @author		Artapon Rittirote
 * 
 */

class Crud_widget_m extends MY_Model{
	
	
	public function widget_structure($input = array())
	{
		$file_detail = 
"<?php defined('BASEPATH') OR exit('No direct script access allowed');\n\n
	
	class Widget_".ucfirst($input['slug'])." extends Widgets
	{
		public \$title		= array(
			'en'	=>	'".ucfirst($input['name'])."'
		);\n
		
		public \$description = array(
			'en'	=>	'".$input['description']."'
		);\n
		
		public \$author 	= '".$input['author']."';\n
		public \$website	= '".$input['website']."';\n
		public \$version	= '".$input['version']."';\n
		
		
		public function form()
		{
				
			// return query to admin widgets form
			return array(
				''=>''
			);
		}
		
		public function run(\$options)
		{
			// return query to display.php
			return array(
				''=>''
			);
		}
	}
		";
		
	return $file_detail;
	
	}
	
	public function widget_structure_ex($input = array())
	{
		$file_detail = 
"<?php defined('BASEPATH') OR exit('No direct script access allowed');\n\n
	
	class Widget_".ucfirst($input['slug'])." extends Widgets
	{
		public \$title		= array(
			'en'	=>	'".ucfirst($input['name'])."'
		);\n
		
		public \$description = array(
			'en'	=>	'".$input['description']."'
		);\n
		
		public \$author 	= '".$input['author']."';\n
		public \$website	= '".$input['website']."';\n
		public \$version	= '".$input['version']."';\n
		
		".$this->_field_detail_ex()."
		
		
		public function form()
		{
			
			".$this->_form_detail_ex()."
			
		}
		
		public function run(\$options)
		{
			
			".$this->_run_detail_ex()."
			
		}
	}
		";
		
	return $file_detail;
	}
	
	private function _form_detail_ex()
	{
		$file_detail = "
		\$folders_list = \$this->db->get('file_folders')->result();
			
		\$folders = array();
			
		foreach(\$folders_list as \$folder)
		{
			\$folders[\$folder->id] = \$folder->name;
		}
		// return query to admin widgets form
			
		return array(
			
			'folders' => \$folders
		);
		
		";
		
		return $file_detail;
		
	}
	
	private function _run_detail_ex()
	{
		$file_detail = "
		\$list_image = \$this->db->where('folder_id',\$options['folder'])->get('files')->result();
			// return query to display.php
		return array(
			'list_image' => \$list_image,
		);
		";
		
		return $file_detail;
		
		
	}
	
	private function _field_detail_ex()
	{
		$file_detail = "
		public \$fields = array(
			array(
				'field' => 'folder',
				'label' => 'Folder of Image',
			),
		);
			
		";
			
		return $file_detail;
		
	}
		
	public function widget_form_detail()
	{
		$file_detail = "
		<ul>
			<li>
				<label>Folder</label>
				<?php echo form_dropdown('folder', \$folders, @\$options['folder']); ?>
			</li>
		</ul>
		
		";
		return $file_detail;
	}
	
	public function widget_display_detail()
	{
		$file_detail = "
<?php
		print_r(\$list_image);
?>
		
		";
		return $file_detail;
	}
}
	