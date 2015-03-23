<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_geo_location").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
	
	function geolocDupcheck(field, rules, i, options)
	{
		var geo_location = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_geo_locations/geo_location_check"); ?>",
			data: "geo_location="+geo_location,

			success : function (data) {
				if(data == 'yes')
				{
					document.getElementById('loc_checkdata').value = data;
				}
				else
				{
					document.getElementById('loc_checkdata').value = data;
				}
			}
		});
				
	}
	
	function locisData(field, rules, i, options)
	{
		var storeData = document.getElementById('loc_checkdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('notification_system_settings_location_already_exists'); ?>';
		}
	}
	
	function codeDupcheck(field, rules, i, options)
	{
		var code = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_geo_locations/code_check"); ?>",
			data: "code="+code,

			success : function (data) {
				if(data == 'yes')
				{
					document.getElementById('code_checkdata').value = data;
				}
				else
				{
					document.getElementById('code_checkdata').value = data;
				}
			}
		});
				
	}
	
	function codeisData(field, rules, i, options)
	{
		var storeData = document.getElementById('code_checkdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('notification_system_settings_code_already_exists'); ?>';
		}
	}
		

</script>
  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="add_geo_location" action="<?php echo site_url('admin/settings_geo_locations/add_geo_location_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					<?php echo validation_errors(); ?>
						
                    <p>
							<label for="location_name"><?php echo $this->lang->line('label_location_name'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="location_name"  id="location_name" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_location_name_validate'); ?>"  value="<?php echo form_text(set_value('location_name')); ?>"/>
                    </p>
                    
					<p>
                    	<label for="country_code"><?php echo $this->lang->line('label_country_code'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="country_code"  id="country_code" class="validate[required,funcCall[codeDupcheck],funcCall[codeisData]] sf" alt="<?php echo $this->lang->line('notification_country_code_validate'); ?>"  value="<?php echo form_text(set_value('country_code')); ?>"/>
                    </p>
                          
					<p>
                    	<label for="continent"><?php echo $this->lang->line('label_continent'); ?><span class="mandatory">*</span></label>
                        <?php
						    $sel_continent = form_text(set_value('continent'));
							$options['']	=	'Select Continent';
							foreach($continent_list as $row): 
							$options[$row->continent_code.'||'.$row->continent_name] =  $row->continent_name;
							endforeach;
							$js = 'class="validate[required] " alt="'.$this->lang->line("notication_select_continent").'"';
							echo form_dropdown('continent',$options,$sel_continent,$js);
						?>
                    </p>
                    <p>
                    	<button><?php echo $this->lang->line("label_add"); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="loc_checkdata" name="loc_checkdata" /> 
		 <input type="hidden" id="code_checkdata" name="code_checkdata" />   
        
        </form>
		<script>
			function goToList(){
			document.location.href='<?php echo site_url('admin/settings_geo_locations'); ?>';
			}
		</script>
