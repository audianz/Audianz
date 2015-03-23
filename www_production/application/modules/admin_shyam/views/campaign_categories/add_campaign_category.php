<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_cam_cat").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
	
	function camcatDupcheck(field, rules, i, options)
	{
		var category_name = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_campaign_categories/category_name_check"); ?>",
			data: "category_name="+category_name,

			success : function (data) {
				if(data == 'yes')
				{
					document.getElementById('cat_checkdata').value = data;
				}
				else
				{
					document.getElementById('cat_checkdata').value = data;
				}
			}
		});
				
	}
	
	function catisData(field, rules, i, options)
	{
		var storeData = document.getElementById('cat_checkdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_system_settings_category_already_exists'); ?>';
		}
	}
	

</script>
  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="add_cam_cat" action="<?php echo site_url('admin/settings_campaign_categories/add_campaign_category_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					<?php
					/*********Display Error Notification  Message ******/
					
								echo validation_errors();
								
					?>	
					<?php /* ?><p>
                    	<label for="campaign_value"><?php echo $this->lang->line('label_campaign_category_value'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="category_value"  id="category_value" class="validate[required, custom[integer]] sf" alt="<?php echo $this->lang->line('notification_category_value_validation'); ?>"  value="<?php echo form_text(set_value('category_value')); ?>"/>
                    </p><?php */ ?>
                    <p>
                    	<label for="campaign_category"><?php echo $this->lang->line('label_campaign_category'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="campaign_category"  id="camapign_category" class="validate[required,funcCall[camcatDupcheck],funcCall[catisData]] sf" alt="<?php echo $this->lang->line('notification_category_validation'); ?>"  value="<?php echo form_text(set_value('campaign_category')); ?>"/>
                    </p>
                    
                          
                    
                    
                    <p>
                    	<button><?php echo $this->lang->line("label_add"); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="cat_checkdata" name="cat_checkdata" />   
        
        </form>
		<script>
			function goToList(){
			document.location.href='<?php echo site_url('admin/settings_campaign_categories'); ?>';
			}
		</script>
