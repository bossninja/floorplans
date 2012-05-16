
<?php echo form_open('admin/files/action');?>

	<div class="buttons float-left padding-top">
			<?php if (group_has_role('files', 'edit_file')): ?>		
					<?php echo form_hidden('folder_id', $folder->id); ?>
					<a href="<?php echo site_url('admin/files/upload/'.$folder->id);?>" class="btn gray button upload open-files-uploader">
						<?php echo lang('files.upload_title'); ?>
					</a>		
			<?php endif; ?>		
	</div>

	<?php if ($files): ?>

	    <div id="grid" class="list-items">
	        <?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all grid-check-all')); ?><?php echo lang('global:check-all'); ?><br />
	        <div class="clear-both"></div>
	        <ul class="grid clearfix" id="gallery_images_list">
	        <?php foreach($files as $file): ?>
	            <li>
		            <a href="<?php echo site_url('admin/files/edit/'.$file->id); ?>" class="modal">
		                <?php echo img(array('src' => site_url('files/thumb/'.$file->id), 'alt' => $file->name, 'title' => 'Title: ' . $file->name . ' -- Caption: ' . $file->description)); ?>		                
		            </a>
		            <div class="actions">
	                	<?php echo form_checkbox('action_to[]', $file->id); ?>				
	                </div>
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