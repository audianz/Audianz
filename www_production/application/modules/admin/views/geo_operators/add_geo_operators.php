<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_operators").validationEngine();
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

			url: "<?php echo site_url("admin/settings_geo_operators/geo_operators_name_check"); ?>",
			data: "geo_operators_name="+geo_operators_name,

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
			return '<?php echo $this->lang->line('lang_system_settings_geo_operators_already_exists'); ?>';
		}
	}
	

</script>
  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="add_operators" action="<?php echo site_url('admin/settings_geo_operators/add_geo_operators_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					<?php echo validation_errors(); ?>
					
						
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_geo_operators_name'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="geo_operators_name"  id="geo_operators_name" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_geo_operators_validation'); ?>"  value="<?php echo form_text(set_value('geo_operators_name')); ?>"/>
                    </p>
                    
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_geo_operators_country'); ?><span class="mandatory">*</span></label>
                                      
                        <?php
						  $sel_country= form_text(set_value('country')); 
						  $options['']=$this->lang->line('notification_geo_operators_select_country'); 
						  foreach($country as $row_country):
						  	$options[$row_country->counrty_code] =$row_country->country_name;
						  endforeach;
						   $sdi='class="validate[required] sf" alt="'.$this->lang->line("notification_geo_operators_validation_country").'"'; 
						   echo form_dropdown('country',$options,$sel_country,$sdi);
						 ?>
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
			document.location.href='<?php echo site_url('admin/settings_geo_operators'); ?>';
			}
		</script>
