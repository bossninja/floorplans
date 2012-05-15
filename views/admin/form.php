
<section class="title">
    <?php if($this->method == 'edit'): ?>
        <h4>Edit Floorplan</h4>
    <?php else: ?>
        <h4>Create Floorplan</h4>
    <?php endif; ?>    
</section>

<section class="item">

    <div class="hidden">
        <div id="files-uploader">

            <div class="files-uploader-browser">
                <form class="file_upload" enctype="multipart/form-data" accept-charset="utf-8" method="post" action="<?php echo site_url('/admin/files/upload'); ?>">
                    <label for="userfile" class="upload"><?php echo lang('files.upload_title'); ?></label>
                    <?php echo form_upload('userfile', NULL, 'multiple="multiple"'); ?>
                </form>                  
                <ul id="files-uploader-queue" class="ui-corner-all"></ul>
            </div>
            
            <div class="buttons align-right padding-top">
                <a href="#" title="" class="button start-upload"><?php echo lang('files.upload_label'); ?></a>
                <a href="#" title="" class="button cancel-upload"><?php echo lang('buttons.cancel');?></a>
            </div>
            
        </div>
    </div>

    <!-- start tabs -->
    <div class="tabs">

        <ul class="tab-menu">
            <li><a href="#floorplan-details"><span><?php echo lang('floorplan_details_title'); ?></span></a></li>            
            <li><a href="#floorplan-images"><span><?php echo lang('floorplan_images_title'); ?></span></a></li>            
        </ul>

        <?php echo form_open(uri_string()); ?>
        <div class="form_inputs" id="floorplan-details">
            <fieldset>
                <ul>
                    
                    <li>
                        <label for="description">Title <span>*</span></label>
                        <div class="input"><?php echo form_input('title', set_value('title', (isset($plan)) ? $plan->title : null)); ?></div>
                    </li>
                    
                    <li>
                        <label for="slug">Slug <span>*</span></label>
                        <div class="input"><?php echo form_input('slug', set_value('slug', (isset($plan)) ? $plan->slug : null)); ?></div>
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
                        <table>
                            <thead>
                                <tr class="head">
                                    <th class="name">Feature Name</th>                        
                                    <th>&nbsp;&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="features">

                            <?php 
                                if(isset($plan) && isset($plan_features)): 
                                    foreach ($plan_features as $key => $feature):                                    
                            ?>
                                        <tr class="feature">
                                            <td class="name"><div class="input type-text"><input type="text" name="features[<?php echo $feature->floorplan_feature_id; ?>][name]" value="<?php echo $feature->name; ?>"/></div></td>                        
                                            <td class="actions">
                                                <input type="hidden" name="features[<?php echo $feature->floorplan_feature_id; ?>][delete]" value="0"/>
                                                <button class="btn red delete"><span>Delete</span></button>
                                            </td>
                                        </tr>
                            <?php 
                                    endforeach;
                                endif; 
                            ?>    

                            </tbody>
                            <tfoot class="features">
                                <tr> 
                                    <td class="url">&nbsp;&nbsp;</td>                                                    
                                    <td class="actions">
                                        <button class="btn orange add"><span>Add a Feature</span></button>
                                    </td>
                                </tr>                       
                            </tfoot>
                        </table>

                    </li>
                </ul>
                
            </fieldset>    
        </div>
        
        <div class="form_inputs" id="floorplan-images"> 
            <fieldset>
                <?php if(isset($plan)): ?>
                    <div id="files-browser-contents"></div>
                <?php else: ?>
                    <div class="no_data files">
                        <p><?php echo lang('floorplan_save_details_message'); ?></p>
                    </div>
                <?php endif; ?>
            </fieldset>
        </div>

        <?php form_close(); ?> 

    </div>
    <!-- close tabs -->  

    <div class="buttons float-right padding-top">
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
    </div>
</section>

<script type="text/javascript">

    var FOLDER_ID = '<?php echo (isset($plan)) ? $plan->folder_id : ''; ?>';

    (function($){            
        // Store data for filesUpload plugin
        $('#files-uploader form').data('fileUpload', {
            lang : {
                start : 'Start',
                cancel : '<?php echo lang('global:delete'); ?>'
            }
        });
    })(jQuery);

</script>