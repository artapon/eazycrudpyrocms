<style>
	textarea{
		background-color: black;
		color:white;
	}
</style>

<section class="title" >
<h4>Generate Function</h4>
</section>
<?php echo form_open('admin/create_pyrocms_modules/create_admin_upload_text');?>
<section class="item">
	
	<div class="tabs">

		<ul class="tab-menu">

			<li><a href="#set-parameter"><span>Function Upload One File</span></a></li>
			<li><a href="#set-parameter"><span>Function Upload Multi File</span></a></li>


		</ul>
<div class="form_inputs" id="set-parameter">
	<fieldset id="text-function-menu" style="float: left;width: 35%" >
		<ul>
			<li>

				<label for="title">Name :<span>*</span></label>

				<div class="input"><?php echo form_input('name'); ?></div>

			</li>
			<li>

				<label for="title">Allowed Types :</label>

				<div class="input"><?php echo form_input('allowed'); ?></div>

			</li>
			<li>

				<label for="title">Prefix :</label>

				<div class="input"><?php echo form_input('prefix'); ?></div>

			</li>
			<li>

				<label for="title">Max Size :</label>

				<div class="input"><?php echo form_input('max_size'); ?></div>

			</li>
			<li>

				<label for="title">Max Width :</label>

				<div class="input"><?php echo form_input('max_width'); ?></div>

			</li>
			<li>

				<label for="title">Max Height :</label>

				<div class="input"><?php echo form_input('max_height'); ?></div>

			</li>
		</ul>
		<div class="buttons float-right" >
		<?php echo form_submit('submit',"Create",'class="btn green" style="width:150px;height:40px;float:right;" ');?>
		</div>
	</fieldset>
	<div class="clear"></div>
	<fieldset id="text-function-text" style="float: left;width: 55%" >
		<ul>
<!-- 			<li> -->
				<label>Controller</label>
				<div class="tabs">

					<ul class="tab-menu">

						<li><a href="#text-create"><span>Create Function</span></a></li>
						<li><a href="#text-edit"><span>Edit Function</span></a></li>
						<li><a href="#text-delete"><span>Delete Function</span></a></li>

					</ul>
					<div id="text-create" style="width: 100%;" >
						<li class="editor" >
					
						<?php echo form_textarea("",@$controller_create_text);?>
						</li>
					</div>
					<div id="text-edit" style="width: 100%;" >
						<li class="editor" >
					
						<?php echo form_textarea("",@$controller_edit_text);?>
						</li>
					</div>
					<div id="text-delete" style="width: 100%;" >
						<li class="editor" >
					
						<?php echo form_textarea("",@$controller_delete_text);?>
						</li>
					</div>
				
			</div>
<!-- 			</li> -->
			
			<li class="editor" >
				<label>Model</label>
				<?php echo form_textarea("",@$model_upload_text);?>
			</li>
			<li class="editor" >
				<label>View</label>
				<?php echo form_textarea("",@$view_upload_text);?>
			</li>
		</ul>
	</fieldset >
	<div class="clear"></div>
	</div>
</div>

</section>
<?php form_close();?>