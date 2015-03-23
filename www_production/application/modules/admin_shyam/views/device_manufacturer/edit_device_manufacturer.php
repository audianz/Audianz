<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_dev_man").validationEngine();
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
        
		<form id="edit_dev_man" action="<?php echo site_url('admin/settings_device_manufacturers/edit_device_manufacturer_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					
					<?php 
						  echo validation_errors();
						  //echo $this->session->userdata('notification_message');
						  //$this->session->unset_userdata('notification_message');
					?>
                    
					
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_manufacturer_name'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="man_name"  id="man_name" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_manufacturer_validation'); ?>"  value="<?php echo form_text((set_value('man_name') != '')?set_value('man_name'):$manufacturer_data->manufacturer_name);?>"/>
                    </p>
                    
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line('label_update'); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="man_checkdata" name="man_checkdata" />   
		 <input type="hidden"	id="man_id" name="man_id" value="<?php echo $manufacturer_id; ?>" />
        
        </form>
		<script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/settings_device_manufacturers'); ?>';
			}
	</script>
