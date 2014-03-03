<section class="title" >
<h4>Generate CRUD For Pyrocms</h4>
</section>
<script>
	$(document).ready(function(){
		pyro.generate_slug('input[name="module_name"]','input[name="slug"]','_',true);
	});
</script>

<section class="item">
<div class="content">

<?php echo form_open('admin/eazycrudpyrocms/create',array('id'=>'form1','method'=>'post'));?>
<div class="tabs">
	<ul class="tab-menu" >
		<li><a href="#create-content" ><span>CRUD</span></a></li>
	</ul>
	<div class="form_inputs" id="create-content">
	<fieldset>
		<ul>
			<li>
				<label>PyroCMS Version :</label>
				<div><?php echo form_dropdown('pyroversion',array('0'=>'2.2'));?></div>
			</li>
			<li>
				<label>Position :</label>
				<div><?php echo form_dropdown('position', array('default' => 'Default Folder','shared_addons' => 'Shared Addons Folder'),@$admin_crud->position); ?>ตำแหน่งโฟลเดอร์ที่เก็บโมดูล</div>
			</li>
			<li>
				<label>Module Name : </label>
				<div><?php echo form_input(array('name'=>'module_name','type'=>'text','value'=> @$admin_crud->module_name,'autocomplete'=>'off')); ?><font color="red">* English only</font></div>
			</li>
			<li>
				<label for="slug">Slug :<font color="red">*</font></label>
					<div class="input"><?php echo form_input('slug',@$admin_crud->slug,' readonly="true" ') ?></div>
			</li>
			<li>
				<label>Module Version : </label>
				<div><?php echo form_input(array('name'=>'module_version','type'=>'text','value'=>'1.0'),@$admin_crud->module_version);?><font color="red">*</font></div>
			</li>
			<li>
				<label>Database Table : </label>
				<div><?php echo form_dropdown('database_table',$list_table);?> ชื่อฐานข้อมูล</div>
			</li>
			<li>
				<label>Drop Database Function : </label>
				<div><?php echo form_checkbox('database_drop',1);?>อนุญาติให้โมดูลDrop database เมื่อทำการ Uninstall</div>
			</li>
			<li>
				<label>Condition Build : </label>
				<div><?php echo form_dropdown('condition_build',array('0'=>'Module include CRUD'));?>เงื่อนไขในการสร้าง</div>
			</li>
			<li>
				<label>Enable Cache : </label>
				<div><?php echo form_dropdown('enable_cache',array('no'=>'No','yes'=>'Yes'));?>เปิดการใช้งาน cache frontend</div>
			</li>
			<li>
				<label>Module Role : </label>
				<div><?php echo form_checkbox('role[]','put_live');?> Put Live <?php echo form_checkbox('role[]','edit_live');?> Edit Live <?php echo form_checkbox('role[]','delete_live');?>Delete Live &nbsp;เงื่อนไขในการตั้ง premission ของโมดูล</div>
			</li>
			<li>
				<label>Module Description :</label>
				<div><?php echo form_textarea(array('name'=>'description','type'=>'text','cols'=>'40','rows'=>'5'));?>คำอธิบายโมดูล</div>
			</li>
			<li>
				<label>Fontend :</label>
				<div><?php echo form_dropdown('fontend',array('TRUE'=>'TRUE','FALSE'=>'FALSE'));?>เปิดปิดการทำงานหน้าบ้าน</div>
			</li>
			<li>
				<label>Backend :</label>
				<div><?php echo form_dropdown('backend',array('TRUE'=>'TRUE','FALSE'=>'FALSE'));?>เปิดปิดการทำงานหลังบ้าน</div>
			</li>
			<li>
				<label>Skip XSS :</label>
				<div><?php echo form_dropdown('skip_xss',array('TRUE'=>'TRUE','FALSE'=>'FALSE'));?> อนุญาติให้ใส่ css ลงใน text editor ได้</div>
			</li>
			<li>
				<label>Upgrade :</label>
				<div><?php echo form_dropdown('upgrade',array('0'=>'TRUE','1'=>'FALSE'));?>ยังไม่สามารถใช้งานได้</div>
			</li>
			<li>
				<label>Help :</label>
				<div><?php echo form_textarea(array('name'=>'help','type'=>'text','cols'=>'40','rows'=>'5'));?>ข้อความช่วยเหลือของโมดูล</div>
			</li>
		</ul>
	</fieldset>
	</div>
	<div class="buttons float-right" >
		<?php echo form_submit('submit',"Create",'class="btn green" style="width:150px;height:40px;float:right;" ');?>
	</div>
<?php echo form_close();?>
</section>
</div>
</div>



