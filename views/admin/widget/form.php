<script>
$(document).ready(function(){
	pyro.generate_slug('input[name="name"]','input[name="slug"]','_',true);
});
</script>
<section class="title" >
<h4>Generate Widget Structure</h4>
</section>
<?php echo form_open(uri_string());?>
<section class="item">
	<div class="tabs">

		<ul class="tab-menu">

			<li><a href="#create-content"><span>Create Widget Structure</span></a></li>

		</ul>
		<div class="form_inputs" id="create-content">
			<fieldset>
				<ul>
					<li>
						<label for="position">Position :</label>
						<div class="input"><?php echo form_dropdown('position', array('default' => 'Default Folder','shared_addons' => 'Shared Addons Folder'),@$widgets_form->position); ?></div>
					</li>
					<li>
						<label for="name-english">Widget Name :<font color="red">*</font></label>
						<div class="input"><?php echo form_input('name',@$widgets_form->name,'autocomplete="off"'); ?></div>
					</li>
					<li>
						<label for="slug">Slug :<font color="red">*</font></label>
						<div class="input"><?php echo form_input('slug',@$widgets_form->slug,' readonly="true" ') ?></div>
					</li>
					<li>
						<label for="description">Description :</label>
						<div class="input"><?php echo form_input('description',@$widgets_form->description); ?></div>
					</li>
					<li>
						<label for="author">Author :</label>
						<div class="input"><?php echo form_input('author',@$widgets_form->author); ?></div>
					</li>
					<li>
						<label for="website">Website :</label>
						<div class="input"><?php echo form_input('website',@$widgets_form->website); ?></div>
					</li>
					<li>
						<label for="version">Version :</label>
						<div class="input"><?php echo form_input('version',@$widgets_form->version); ?></div>
					</li>
					<li>
						<label for="example">Include Example Code :</label>
						<div class="input"><?php echo form_checkbox('example','example'); ?></div>
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