<section class="title">

	<?php if($this->method ==='create_module_categories'): ?>

		<h4><?php echo sprintf(lang('modulenametest.create_modulenametest_title_label')); ?></h4>

	<?php endif;?>

</section>
<script>
	$(document).ready(function(){
		pyro.generate_slug('input[name="name"]','input[name="slug"]','_',true);
	});


</script>
<section class="item">
<div class="alert error" style="width: 50%;"  >
	คำเตือน! เพื่อความปลอดภัย ควรใช้ฟังก์ชันนี้กับโมดูลที่สร้างขึ้นด้วยโมดูล<b> PyroCMS Develop Helper</b> เท่านั้น
</div>			
<?php echo form_open(uri_string());?>

	<div class="tabs">

		<ul class="tab-menu">

			<li><a href="#modulenametest-content"><span><?php echo lang('modulenametest.content_label'); ?></span></a></li>

		</ul>

	
	<div class="form_inputs" id="modulenametest-content">

	
		<fieldset>

			<ul>
				<li>
					<label for="title">Position <span>*</span></label>

					<div class="input"><?php echo form_dropdown('position',array(''=>'-- Select Position Of Module --','default'=>'default','shared_addons'=>'shared_addons'),@$submodule_form->position,'onChange="get_modulename()" id="module_position"'); ?></div>
			
				</li>
				
				<li>
					<label for="title">Base Module <span>*</span></label>

					<div class="input" id="boxModulename" ><?php echo form_dropdown('basemodule',$folder_name,@$submodule_form->basemodule); ?></div>
			
				</li>
				<li>

					<label for="title">Submodule Name <span>*</span></label>

					<div class="input"><?php echo form_input('name',@$submodule_form->name,'autocomplete="off"'); ?></div>

				</li>
				<li>

					<label for="title">Slug</label>

					<div class="input"><?php echo form_input('slug',@$submodule_form->slug,' readonly="true" '); ?></div>

				</li>
				<li>

					<label for="title">Database Table <span>*</span></label>

					<div class="input"><?php echo form_dropdown('database_table',$list_table,@$submodule_form->database_table); ?><font color="red">* Use table name and prefix <b>Ex</b> default_tablename</font></div>
				</li>
				<li>
					<label for="title">Module Role : <span>*</span></label>
					<?php echo form_checkbox('role[]','put_live');?> Put Live <?php echo form_checkbox('role[]','edit_live');?> Edit Live <?php echo form_checkbox('role[]','delete_live');?>Delete Live
					
				</li>
				<!-- <li>

					<label for="title">Create Public View</label>

					<div class="input"><?php echo form_checkbox('public_view'); ?></div>
				</li> -->
			</ul>
		</fieldset>
	</div>
	<div class="buttons float-right" >
			<?php echo form_submit('submit',"Create",'class="btn green" style="width:150px;height:40px;float:right;" ');?>
		</div>
</section>
</div>
<?php echo form_close();?>