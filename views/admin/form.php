<section class="title">
    <?php if($this->method == 'edit'): ?>
        <h4>Edit Floorplan</h4>
    <?php else: ?>
        <h4>Create Floorplan</h4>
    <?php endif; ?>    
</section>

<section class="item">
    <?php echo form_open(uri_string(), 'class="crud"'); ?>
    <div class="form_inputs">        
        <ul>
            
            <li>
                <label for="description">Title <span>*</span></label>
                <div class="input"><?php echo form_input('title', set_value('title', (isset($plan)) ? $plan->title : null)); ?></div>
            </li>
            
            <li>
                <label for="description">Lease Price</label>
                <div class="input"><?php echo form_input('lease_price', set_value('lease_price', (isset($plan)) ? $plan->lease_price : null)); ?></div>                
            </li>
            
            <li>
                <label for="description">Purchase Price</label>
                <div class="input"><?php echo form_input('purchase_price', set_value('purchase_price', (isset($plan)) ? $plan->purchase_price : null)); ?></div>
            </li>
            
            <li>
                <label for="description">Features <span>*</span></label>
                <div class="input"><?php echo form_textarea('features', set_value('features', (isset($plan)) ? $plan->features : null)); ?></div>
            </li>
            
            <li class="<?php echo alternator('', 'even'); ?>">
                    <label for="folder_id">Folder<span>*</span></label>
                    <div class="input"><?php echo form_dropdown('folder_id', array(lang('global:select-pick')) + $folders_tree, (isset($plan)) ? $plan->folder_id : null, 'id="folder_id" class="required"'); ?></div>
            </li>
            
            <?php if (isset($gallery_images) && $gallery_images): ?>
            <li class="images-manage <?php echo alternator('', 'even'); ?>">
                    <label for="gallery_images"><?php echo lang('floorplan_current_label'); ?></label>
                    <div class="clear-both"></div>
                    <ul id="gallery_images_list">
                            <?php if ( $gallery_images !== FALSE ): ?>
                            <?php foreach ( $gallery_images as $image ): ?>
                            <li>
                                    <a href="<?php echo site_url('admin/files/edit/'.$image->file_id); ?>" class="modal">
                                            <?php echo img(array('src' => site_url('files/thumb/'.$image->file_id), 'alt' => $image->name, 'title' => 'Title: ' . $image->name . ' -- Caption: ' . $image->description)); ?>
                                            <?php echo form_hidden('action_to[]', $image->id); ?>
                                    </a>
                            </li>
                            <?php endforeach; ?>
                            <?php endif; ?>
                    </ul>
                    <div class="clear-both"></div>
            </li>
            <?php endif; ?>
            
            <li style="display: none;" class="images-placeholder <?php echo alternator('', 'even'); ?>">
                    <label for="gallery_images"><?php echo lang('floorplan_preview_label'); ?></label>
                    <div class="clear-both"></div>
                    <ul id="gallery_images_list">

                    </ul>
                    <div class="clear-both"></div>
            </li>
            
        </ul>        
    </div>   
    <div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
    </div>    
    <?php form_close(); ?>
</section>