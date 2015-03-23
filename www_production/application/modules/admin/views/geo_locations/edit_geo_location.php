<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_geo_location").validationEngine();
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
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
        
        
        <br />
        
		<form id="edit_geo_location" action="<?php echo site_url('admin/settings_geo_locations/edit_geo_location_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
					<?php echo validation_errors(); ?>
						
                    <p>
							<label for="location_name"><?php echo $this->lang->line('label_location_name'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="location_name"  id="location_name" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_location_name_validate'); ?>"  value="<?php echo form_text((set_value('location_name') != '')?set_value('location_name'):$loc_data->name);?>"/>
                    </p>
                    
					<p>
                    	<label for="country_code"><?php echo $this->lang->line('label_country_code'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="country_code"  id="country_code" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_country_code_validate'); ?>"  value="<?php echo form_text((set_value('country_code') != '')?set_value('country_code'):$loc_data->code);?>" disabled="disabled"/>
                    </p>
                       
					   
					  <p>
                    	<label for="continent"><?php echo $this->lang->line('label_continent'); ?><span class="mandatory">*</span></label>
                        <?php 	
						    $sel_continent =  form_text((set_value('continent') != '')?set_value('continent'):$loc_data->continent_code.'||'.$loc_data->continent);
							$options['']	=	'Select Continent';
							foreach($continent_list as $row): 
							$options[$row->continent_code.'||'.$row->continent_name] =  $row->continent_name;
							endforeach;
							$js = 'class="validate[required] " alt="'.$this->lang->line("notication_select_continent").'"';
							echo form_dropdown('continent',$options,$sel_continent,$js);
						?>
                    </p>
                    <p>
                    	<button><?php echo $this->lang->line("label_update"); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
			
         <input type="hidden" id="loc_checkdata" name="loc_checkdata" />
		 <input type="hidden" id="code_checkdata" name="code_checkdata" />
		  <input type="hidden"	id="code" name="code" value="<?php echo $code; ?>" />  
        
        </form>
		<script>
			function goToList(){
			document.location.href='<?php echo site_url('admin/settings_geo_locations'); ?>';
			}
		</script>
