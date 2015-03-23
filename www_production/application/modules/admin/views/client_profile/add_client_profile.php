<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_client_profile").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
	
	function devmanDupcheck(field, rules, i, options)
	{
		var manufacturer_name = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_client_profile/client_profile_name_check"); ?>",
			data: "client_profile_name="+client_profile_name,

			success : function (data) {
				if(data == 'yes')
				{
					document.getElementById('cap_checkdata').value = data;
				}
				else
				{
					document.getElementById('cap_checkdata').value = data;
				}
			}
		});
				
	}
	
	function mandisData(field, rules, i, options)
	{
		var storeData = document.getElementById('man_checkdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_system_settings_client_profile_already_exists'); ?>';
		}
	}
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
        
		<form id="add_client_profile" action="<?php echo site_url('admin/settings_client_profile/add_client_profile_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
			<?php 
				echo validation_errors();
													
			?>

					
            	   <p>
                      	<label for="man_name"><?php echo $this->lang->line('label_client_profile_From'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="client_profile_start"  id="client_profile_start" 
						class="validate[required,funcCall[checknumber],funcCall[checkagelimitstart]]" size="5" alt="<?php echo $this->lang->line('notification_client_profile_validation'); ?>"  
						value="<?php echo set_value('client_profile_start'); ?>"
						/>
                  </p>
                  <p>

                        <label  for="man_name" ><?php echo $this->lang->line('label_client_profile_TO'); ?><span class="mandatory">*</span></label>
                     	<input type="text" name="client_profile_to"  id="client_profile_to" 
					 	class="validate[required,funcCall[checknumber],funcCall[checkage],funcCall[checkagelimitto]]" size="5" alt="<?php echo $this->lang->line('notification_client_profile_validation_end'); ?>" 
					 	 value="<?php echo set_value('client_profile_to'); ?>"
					  	/>
                    </p>
                                    
                    
                    <p>
                    	<button><?php echo $this->lang->line("label_add"); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="cap_checkdata" name="cap_checkdata" />   
        
        </form>
		<script>
			function goToList(){
			document.location.href='<?php echo site_url('admin/settings_client_profile'); ?>';
			}
		</script>
