
<?php echo form_open('admin/files/action');?>

	<div id="files-toolbar">
		<ul>
			<?php if (group_has_role('files', 'edit_file')): ?>
				<li class="buttons buttons-small">
					<?php echo form_hidden('folder_id', $folder->id); ?>
					<a href="<?php echo site_url('admin/files/upload/'.$folder->id);?>" class="button upload open-files-uploader">
						<?php echo lang('files.upload_title'); ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>

	<?php if ($files): ?>

	    <div id="grid" class="list-items">
	        <?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all grid-check-all')); ?><?php echo lang('global:check-all'); ?><br />
	        <div class="clear-both"></div>
	        <ul class="grid clearfix" id="gallery_images_list">
	        <?php foreach($files as $file): ?>
	            <li>
	                <div class="actions">
	                	<?php echo form_checkbox('action_to[]', $file->id); ?>				
	                </div>		            
		            <a href="<?php echo site_url('admin/files/edit/'.$file->id); ?>" class="modal">
		                <?php echo img(array('src' => site_url('files/thumb/'.$file->id), 'alt' => $file->name, 'title' => 'Title: ' . $file->name . ' -- Caption: ' . $file->description)); ?>		                
		            </a>		            
	            </li>
	        <?php endforeach; ?>
	        </ul>
	    </div>

		<div>
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>

	<?php else: ?>

		<div class="no_data files">
			<p><?php echo lang('files.no_files');?></p>
		</div>
	
	<?php endif; ?>
<?php echo form_close();?>