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
		<!--<h1 class="pageTitle"><?php echo "Network Carrier Details"; ?></h1>-->
     <?php //endif; ?>  
        
        
        <br />
        
		<form id="edit_carrier" action="<?php echo site_url('admin/settings_network_carriers/edit_carrier_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
			
                    <legend><?php echo "Edit Carrier Info"; ?></legend>
					
					<?php echo validation_errors(); ?>
					
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_geo_operators_name'); ?><span class="mandatory">*</span></label>
                        <input type="text"
						 name="carrier_name"  
						 id="carrier_name" 
						 class="validate[required] sf" 
						 alt="<?php echo $this->lang->line('notification_geo_operators_validation'); ?>"
						 value="<?php echo form_text((set_value('carrier_name') != '')?set_value('carrier_name'):$carriers_data->carriername);?>"/>  
						
                    </p>
                   
				<p>
                    	<label for="geo_operators_value"><?php echo "Country"; ?><span class="mandatory">*</span></label>
                        <input type="text"
						 name="country"  
						 id="country" 
						 class="validate[required] sf" 
						 alt="<?php echo $this->lang->line('notification_geo_operators_value_validation'); ?>"
						 value="<?php echo form_text((set_value('country') != '')?set_value('country'):$carriers_data->country);?>"/>  
						
                  </p>	
                  <p>
                    	<label for="geo_operators_value"><?php echo "Start IP Address"; ?><span class="mandatory">*</span></label>
                        <input type="text"
						 name="start_ip"  
						 id="start_ip" 
						 class="validate[required] sf" 
						 alt="<?php echo $this->lang->line('notification_geo_operators_value_validation'); ?>"
						 value="<?php echo form_text((set_value('start_ip') != '')?set_value('start_ip'):$carriers_data->start_ip);?>"/>  
						
                  </p>	
                  <p>
                    	<label for="geo_operators_value"><?php echo "End IP Address"; ?><span class="mandatory">*</span></label>
                        <input type="text"
						 name="end_ip"  
						 id="end_ip" 
						 class="validate[required] sf" 
						 alt="<?php echo $this->lang->line('notification_geo_operators_value_validation'); ?>"
						 value="<?php echo form_text((set_value('end_ip') != '')?set_value('end_ip'):$carriers_data->end_ip);?>"/>  
						
                  </p>	   
                     
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line('label_update'); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id=" geo_operators_checkdata" name="geo_operators_checkdata" />   
	 <input type="hidden"	id="carrier_id" name="carrier_id" value="<?php echo $carrier_id; ?>" />
        
        </form>
		<script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/settings_network_carriers'); ?>';
			}
	</script>

