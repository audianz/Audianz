<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_dev_man").validationEngine();
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

			url: "<?php echo site_url("admin/settings_device_manufacturers/manufacturer_name_check"); ?>",
			data: "manufacturer_name="+manufacturer_name,

			success : function (data) {				
				if(data == 'yes')
				{
					document.getElementById('man_checkdata').value = data;
				}
				else
				{
					document.getElementById('man_checkdata').value = data;
				}
			}
		});
				
	}
	
	function mandisData(field, rules, i, options)
	{
		var storeData = document.getElementById('man_checkdata').value;		
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_system_settings_manufacturer_already_exists'); ?>';
		}
	}
	

</script>
  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="add_dev_man" action="<?php echo site_url('admin/settings_device_manufacturers/add_device_manufacturer_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					<?php
					
								echo validation_errors();
								//echo $this->session->userdata('notification_message');
								//$this->session->unset_userdata('notification_message');
								/*if($this->session->userdata('camp_error') != "")
								{ 
									echo $this->session->userdata('camp_error');
									$this->session->unset_userdata('camp_error');
								} */
							?>
						
						
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_manufacturer_name'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="man_name"  id="man_name" class="validate[required,funcCall[devmanDupcheck],funcCall[mandisData]] sf" alt="<?php echo $this->lang->line('notification_manufacturer_validation'); ?>"  value="<?php echo form_text(set_value('man_name')); ?>"/>
                    </p>
                    
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line("label_add"); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div>
			
         <input type="hidden" id="man_checkdata" name="man_checkdata" />   
        
        </form><!--form-->
		<script>
			function goToList(){
			document.location.href='<?php echo site_url('admin/settings_device_manufacturers'); ?>';
			}
		</script>
