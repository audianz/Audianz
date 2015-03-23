<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_operator").validationEngine();
		});
		
	function ipcheck(field, rules, i, options)
		{
				var value		=field.val();
				var ip = "^(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}"+ "|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])){3}$";
				var alpha			=	/^[0-9a-zA-Z\s\_]+[\.]?[0-9a-zA-Z\s\_]+$/;
				if(!value.match(ip))
					{
						return  'Invalid IP Address';
					}
			}
		
		//jQuery.validator.addMethod('IP4Checker', function(value) { var ip = "^(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}"+     "|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])){3}$"; return value.match(ip); }, ' Invalid IP Address');
	
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
	 <?php if($page_title != ''): ?><h1 class="pageTitle"><?php echo $page_title; ?></h1><?php endif; ?> 
     <br />
     <?php if($type='update') { ?>   
	<form id="edit_operator" action="<?php echo site_url('admin/settings_operators/process/operators'); ?>" method="post">
        <input type="hidden" name="type" id="type" value="<?php echo $type; ?>" />
		 <input type="hidden" name="id" id="id" value="<?php echo $code; ?>" />
		<?php foreach($rs as $obj) { ?>
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					<?php echo validation_errors(); ?>
                    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_geo_operators_name'); ?><span class="mandatory">*</span></label>
                        <input type="text" name="operators_name" id="operators_name" class="validate[required] sf" alt="<?php echo $this->lang->line('notification_geo_operators_validation'); ?>" value="<?php echo form_text((set_value('operators_name') != '')?set_value('operators_name'):$obj->carriername);?>"/>
                    </p>
                   
				    <p>
                    	<label for="man_name"><?php echo $this->lang->line('label_geo_operators_country'); ?><span class="mandatory">*</span></label>
                         <input type="text" name="country" id="country" size="20" value="<?php echo $obj->country; ?>" class="validate[required] sf" alt="Enter Country Name" >
				    </p>
                     
				    <p>
                    	<label for="man_name"><?php echo "Country Code"; //$this->lang->line('label_geo_operators_country'); ?><span class="mandatory">*</span></label>
                         <input type="text" name="country_code" id="country_code" size="20" value="<?php echo $obj->country_code; ?>" class="validate[required] sf" alt="Enter Country Code" />
				    </p>
                    
				    <p>
                    	<label for="man_name"><?php echo "Start IP"; //$this->lang->line('label_geo_operators_country'); ?><span class="mandatory">*</span></label>
                         <input type="text" name="sip" id="sip" size="20" value="<?php echo $obj->start_ip; ?>" class="validate[required, funcCall[ipcheck]] sf" alt="Enter Starting IP" />
				    </p>

				    <p>
                    	<label for="man_name"><?php echo "End IP"; //$this->lang->line('label_geo_operators_country'); ?><span class="mandatory">*</span></label>
                         <input type="text" name="eip" id="eip" size="20" value="<?php echo $obj->end_ip; ?>" class="validate[required, funcCall[ipcheck]] sf" alt="Enter Ending IP" >
				    </p>
					<?php } ?>
                    <p>
                    	<button><?php echo $this->lang->line('label_update'); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
        </form>
		<?php } ?>
		<script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/settings_operators'); ?>';
			}
	</script>

