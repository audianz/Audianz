<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_client_profile").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
	
	function checknumber(field, rules, i, options){

          var numericExpression = /^[0-9]+$/;

			if (!field.val().match(numericExpression) ) 
			  {
				 return "<?php echo $this->lang->line('notification_client_profile_numberonly'); ?>";		
			  }
			
              
              
	 }

 function checkage(field, rules, i, options){

         

			if (parseInt(document.getElementById("client_profile_start").value) >= field.val()) 
			  { 
				 return "<?php echo $this->lang->line('notification_client_profile_agecheck'); ?>";		
			  }
			
              
              
	 }
function checkagelimitstart(field, rules, i, options)
{
           if ((parseInt(document.getElementById("client_profile_start").value) <5) || (parseInt(document.getElementById("client_profile_start").value) > 120))
{

return "<?php echo $this->lang->line('notification_client_profile_agecheck_limit'); ?>";	

}
}
function checkagelimitto(field, rules, i, options)
{
  if ((parseInt(document.getElementById("client_profile_to").value) <5) || (parseInt(document.getElementById("client_profile_to").value) > 120))
{

return "<?php echo $this->lang->line('notification_client_profile_agecheck_limit'); ?>";	

}
}
</script>


  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="edit_client_profile" action="<?php echo site_url('admin/settings_client_profile/edit_client_profile_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
			
                    <legend><?php echo $page_title; ?></legend>
					
					<?php
					/*********Display Error Notification  Message ******/
					if($this->session->userdata('notification_message') !=''):
					?>
					<div class="notification msgerror">
						<a class="close"></a>
						<p>
					<?php 
						  echo $this->session->userdata('notification_message');
						  $this->session->unset_userdata('notification_message');
					?>
                    </p>
					</div>
					<?php
						endif;
					?>
                                       <?php 
				echo validation_errors();
													
					?>
				     <p>
                      	<label for="man_name"><?php echo $this->lang->line('label_client_profile_From'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="client_profile_start"  id="client_profile_start" 
						class="validate[required,funcCall[checknumber],funcCall[checkagelimitstart]]"  size="5" alt="<?php echo $this->lang->line('notification_client_profile_validation'); ?>"  
				value="<?php echo form_text((set_value('client_profile_start') != '')?set_value('client_profile_start'):$client_profile_data->from)?>"
						/>
                  </p>
                  <p>
                        <label  for="man_name" ><?php echo $this->lang->line('label_client_profile_TO'); ?><span class="mandatory">*</span></label>
                     	<input type="text" name="client_profile_to"  id="client_profile_to" 
					 	 class="validate[required,funcCall[checknumber],funcCall[checkage],funcCall[checkagelimitto]]" 
						 size="5" alt="<?php echo $this->lang->line('notification_client_profile_validation_end'); ?>" 
					 	 value="<?php echo form_text((set_value('client_profile_to') != '')?set_value('client_profile_to'):$client_profile_data->to)?>"
					  	/>
                    </p>
                     <p>
                    	<button><?php echo $this->lang->line('label_update'); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id=" client_profile_checkdata" name="client_profile_checkdata" />   
		 <input type="hidden"	id="client_id" name="client_id" value="<?php echo $client_profile_id; ?>" />
        
        </form>
		<script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/settings_client_profile'); ?>';
			}
	</script>
