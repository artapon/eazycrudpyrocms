<script>
$(document).ready(function(){
	pyro.generate_slug('input[name="name"]','input[name="slug"]','_',true);
});
</script>
<section class="title" >
<h4>Generate Themes Structure</h4>
</section>
<?php echo form_open_multipart(uri_string());?>
<section class="item">
	<div class="tabs">
		<ul class="tab-menu">

			<li><a href="#create-content"><span>Create Themes Structure</span></a></li>

		</ul>
		<div class="form_inputs" id="create-content">
			<fieldset>
				<ul>
					<li>
						<label for="position">Position :</label>
						<div class="input"><?php echo form_dropdown('position', array('default' => 'Default Folder','shared_addons' => 'Shared Addons Folder'),@$theme_form->position); ?></div>
					</li>
					<li>
						<label for="name-english">Themes Name :<font color="red">*</font></label>
						<div class="input"><?php echo form_input('name',@$theme_form->name,'autocomplete="off"'); ?></div>
					</li>
					<li>
						<label for="slug">Slug :<font color="red">*</font></label>
						<div class="input"><?php echo form_input('slug',@$theme_form->slug,' readonly="true"') ?></div>
					</li>
					
					<li>
						<label for="description">Description :</label>
						<div class="input"><?php echo form_input('description',@$theme_form->description); ?></div>
					</li>
					<li>
						<label for="author">Author :</label>
						<div class="input"><?php echo form_input('author',@$theme_form->author); ?></div>
					</li>
					<li>
						<label for="website">Website :</label>
						<div class="input"><?php echo form_input('website',@$theme_form->website); ?></div>
					</li>
					<li>
						<label for="version">Version :</label>
						<div class="input"><?php echo form_input('version',@$theme_form->version); ?></div>
					</li>
				</ul>
			</fieldset>
		</div>
		<div class="buttons" >
			<?php echo form_submit('submit',"Create",'class="btn green" style="width:150px;height:40px;float:right;" ');?>
		</div>
	</div>
</section>
<?php echo form_close();?>