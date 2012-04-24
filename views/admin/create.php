<section class="title">
    <h4>Create Floorplan</h4>
</section>

<section class="item">
    <?php echo form_open(uri_string(), 'class="crud"'); ?>
    <div class="form_inputs">        
        <ul>            
            <li>
                <label for="description">Title</label>
                <div class="input"><?php echo form_input('title', set_value('title')); ?></div>
            </li>
            <li>
                <label for="description">Lease Price</label>
                <div class="input"><?php echo form_input('lease_price', set_value('lease_price')); ?></div>                
            </li>    
            <li>
                <label for="description">Purchase Price</label>
                <div class="input"><?php echo form_input('purchase_price', set_value('purchase_price')); ?></div>
            </li>    
            <li>
                <label for="description">Features</label>
                <div class="input"><?php echo form_textarea('features', set_value('features')); ?></div>
            </li>    
        </ul>        
    </div>   
    <div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
    </div>    
    <?php form_close(); ?>
</section>