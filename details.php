<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroCMS Develop Helper module
 *
 * @author Artapon Rittirote
 * @email a.rittirote@gmail.com 
 */
class Module_Eazycrudpyrocms extends Module{
	public $version = '1.0';
	
	public function info()
	{
		return array(
			'name'			=>array(
				'en'		=>'Eazy CRUD PyroCMS Develop Helper'
			),
			'description'	=>array(
				'en'		=>'Module Create Pyrocms Modules is module to help generate folder and file for create module'
			),
			'frontend'		=>TRUE,
			'backend'		=>True,
			'menu'			=>'content',
			
			'sections'=>array(
			
				'MainMenu'=>array(
						'name'=>'Main Menu',
						'uri' =>'admin/eazycrudpyrocms',

			),
			
//------------------------- Begin Short Cut ------------------//
			'CreateBaseModule'=>array(
					'name'=>'Create Base Module',
					'uri' =>'admin/eazycrudpyrocms/base_crud',
					

				),
//------------------------- End Short Cut-----------------------//

//------------------------- Begin Short Cut ------------------//
			'CreateSubModule'=>array(
					'name'=>'Create Sub Module',
					'uri' =>'admin/eazycrudpyrocms/sub_module/create_submodule',
				),
//------------------------- End Short Cut-----------------------//
			
		),
	);
	}
	
	public function install()
	{
		return TRUE;
	}
	
	public function uninstall()
	{
		return TRUE;
	}
	
	public function upgrade($old_version)
	{
		return TRUE;
	}
	
	public function help()
	{
		
	}
	
	
}
