<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_dev_cap").validationEngine();
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

			url: "<?php echo site_url("admin/settings_device_capability/capability_name_check"); ?>",
			data: "capability_name="+capability_name,

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
			return '<?php echo $this->lang->line('lang_system_settings_capability_already_exists'); ?>';
		}
	}
	

</script>
  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br/>
        
		<form id="add_dev_cap" action="<?php echo site_url('admin/settings_device_capability/add_device_capability_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					<?php echo validation_errors(); ?>
						
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_capability_name'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="capability_name"  id="capability_name" class="validate[required] sf" 
						alt="<?php echo $this->lang->line('notification_capability_validation'); ?>" 
						value="<?php echo form_text(set_value('capability_name')); ?> "/>
                    </p>
                    
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_capability_value'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="capability_value"  id="capability_value" class="validate[required] sf" 
						alt="<?php echo $this->lang->line('notification_capability_validation_value'); ?>"
						 value="<?php echo form_text(set_value('capability_value')); ?>"/>
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
			document.location.href='<?php echo site_url('admin/settings_device_capability'); ?>';
			}
		</script>
