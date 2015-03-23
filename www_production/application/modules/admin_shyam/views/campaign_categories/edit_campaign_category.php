<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_cam_cat").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
</script>
  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="edit_cam_cat" action="<?php echo site_url('admin/settings_campaign_categories/edit_campaign_category_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					<?php
					/*********Display Error Notification  Message ******/
				    echo validation_errors();
				    ?>
					<?php /* ?><p>
                    	<label for="campaign_value"><?php echo $this->lang->line('label_campaign_category_value'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="category_value"  id="category_value" class="validate[required, custom[integer]] sf" alt="<?php echo $this->lang->line('notification_category_value_validation'); ?>"  value="<?php echo view_text((set_value('category_value') != '')?set_value('category_value'):$campaign_categories_data->category_value);?>"/>
                    </p><?php */ ?>	
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_campaign_category'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="campaign_category"  id="campaign_category" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_category_validation'); ?>"  value="<?php echo view_text((set_value('campaign_category') != '')?set_value('campaign_category'):$campaign_categories_data->category_name);?>"/>
                    </p>
                    
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line('label_update'); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="cat_checkdata" name="cat_checkdata" />   
		 <input type="hidden"	id="cat_id" name="cat_id" value="<?php echo $category_id; ?>" />
        
        </form>
		<script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/settings_campaign_categories'); ?>';
			}
	</script>
