<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_dev_cap").validationEngine();
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
        
		<form id="edit_dev_cap" action="<?php echo site_url('admin/settings_device_capability/edit_device_capability_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
			
                    <legend><?php echo $this->lang->line('label_edit_device_capability'); ?></legend>
					
					<?php echo validation_errors(); ?>
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_capability_name'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="capability_name"  id="capability_name" class="validate[required] sf" 
						alt="<?php echo $this->lang->line('notification_capability_validation'); ?>"  
						value="<?php echo form_text((set_value('capability_name') != '')?set_value('capability_name'):$capability_data->capability_name);?>"/>
                    </p>
                   
				    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_capability_value'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="capability_value"  id="capability_value" class="validate[required] sf"
						 alt="<?php echo $this->lang->line('notification_capability_validation'); ?>"  
						 value="<?php echo form_text((set_value('"capability_value') != '')?set_value('"capability_value'):$capability_data->capability_value);?>"/>
                    </p>
                     
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line('label_update'); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="capability_checkdata" name="capability_checkdata" />   
		 <input type="hidden"	id="cap_id" name="cap_id" value="<?php echo $capability_id; ?>" />
        
        </form>
		<script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/settings_device_capability'); ?>';
			}
	</script>
