<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_operator").validationEngine();
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
	 <?php //if($page_title != ''): ?>
		<!--<h1 class="pageTitle"><?php echo $page_title; ?></h1>-->
     <?php //endif; ?>  
        
        
        <br />
        
		<form id="edit_operator" action="<?php echo site_url('admin/settings_geo_operators/edit_geo_operators_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
			
                    <legend><?php echo $this->lang->line('label_edit_geo_operators'); ?></legend>
					
					<?php echo validation_errors(); ?>
					
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_geo_operators_name'); ?><span class="mandatory">*</span></label>
                        <input type="text"
						 name="geo_operators_name"  
						 id="geo_operators_name" 
						 class="validate[required] sf" 
						 alt="<?php echo $this->lang->line('notification_geo_operators_validation'); ?>"
						 value="<?php echo form_text((set_value('geo_operators_name') != '')?set_value('geo_operators_name'):$geo_operators_data->telecom_name);?>"/>  
						
                    </p>
                   
		<p>
                    	<label for="geo_operators_value"><?php echo $this->lang->line('label_geo_operators_value'); ?><span class="mandatory">*</span></label>
                        <input type="text"
						 name="geo_operators_value"  
						 id="geo_operators_value" 
						 class="validate[required] sf" 
						 alt="<?php echo $this->lang->line('notification_geo_operators_value_validation'); ?>"
						 value="<?php echo form_text((set_value('geo_operators_value') != '')?set_value('geo_operators_value'):$geo_operators_data->telecom_value);?>"/>  
						
                  </p>	   
                     
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line('label_update'); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id=" geo_operators_checkdata" name="geo_operators_checkdata" />   
	 <input type="hidden"	id="operators_id" name="operators_id" value="<?php echo $geo_operators_id; ?>" />
        
        </form>
		<script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/settings_geo_operators'); ?>';
			}
	</script>

