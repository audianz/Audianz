<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_dev_os").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
	
	function devosDupcheck(field, rules, i, options)
	{
		var device_os = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_device_os/device_os_check"); ?>",
			data: "device_os="+device_os,

			success : function (data) {
				if(data == 'yes')
				{
					document.getElementById('os_checkdata').value = data;
				}
				else
				{
					document.getElementById('os_checkdata').value = data;
				}
			}
		});
				
	}
	
	function osisData(field, rules, i, options)
	{
		var storeData = document.getElementById('os_checkdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_system_settings_os_already_exists'); ?>';
		}
	}
	

</script>
  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="add_dev_os" action="<?php echo site_url('admin/settings_device_os/add_device_os_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					<?php echo validation_errors(); ?>
						
                    <p>
                    	<label for="device_os"><?php echo $this->lang->line('label_device_os'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="device_os"  id="device_os" class="validate[required,funcCall[devosDupcheck],funcCall[osisData]] sf" alt="<?php echo $this->lang->line('notification_device_os_validate_platform'); ?>"  value="<?php echo form_text(set_value('device_os')); ?>"/>
                    </p>
                    
					<p>
                    	<label for="device_os_value"><?php echo $this->lang->line('label_device_os_value'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="device_os_value"  id="device_os_value" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_device_os_validate_value'); ?>"  value="<?php echo form_text(set_value('device_os_value')); ?>"/>
                    </p>
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line("label_add"); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="os_checkdata" name="os_checkdata" />   
        
        </form>
		<script>
			function goToList(){
			document.location.href='<?php echo site_url('admin/settings_device_os'); ?>';
			}
		</script>
