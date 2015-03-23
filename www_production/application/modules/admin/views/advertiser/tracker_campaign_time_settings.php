<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#tracker_campaigns").validationEngine();
	});

	function dayValidate(field, rules, i, options){


		var day = field.val();
                
                var numbers = /^[0-9]+$/;
            	if(!(field.val().match(numbers)))  
          	{  
          		return '<?php echo "Please Enter the Numbers only" ?>';  
           	}   

                /*if (day<0 || day>30)
           	{
                 	return '<?php echo "Max value for Day should be 30" ?>';
           	}*/
	 }

	function hourValidate(field, rules, i, options){


		var hour = field.val();
                
                var numbers = /^[0-9]+$/;
            	if(!(field.val().match(numbers)))  
          	{  
          		return '<?php echo "Please Enter the Numbers only" ?>';  
           	}   

                if (hour<0 || hour>23)
           	{
                 	return '<?php echo "Max value for Hour should be 23" ?>';
           	}
	 }

	function minValidate(field, rules, i, options){


		var mini = field.val();
                
                var numbers = /^[0-9]+$/;
            	if(!(field.val().match(numbers)))  
          	{  
          		return '<?php echo "Please Enter the Numbers only" ?>';  
           	}   

                if (mini<0 || mini>59)
           	{
                 	return '<?php echo "Max value for Minute should be 59" ?>';
           	}
	 }

	function secValidate(field, rules, i, options){


		var sec = field.val();
                
                var numbers = /^[0-9]+$/;
            	if(!(field.val().match(numbers)))  
          	{  
          		return '<?php echo "Please Enter the Numbers only" ?>';  
           	}   

                if (sec<0 || sec>59)
           	{
                 	return '<?php echo "Max value for Second should be 59" ?>';
           	}
	 }

</script>
<?php if($this->session->userdata('error_message') != ''): ?>
	 <div class="notification msgerror">
         <a class="close"></a>
         <p><?php echo $this->session->userdata('error_message'); ?></p>
     </div>
<?php $this->session->unset_userdata('error_message'); endif; ?>
		<form id="tracker_campaigns" name="tracker_campaigns" action="<?php echo site_url("admin/inventory_advertisers/tracker_campaign_process/time"); ?>" method="post">
        	
				<div class="form_default">
                <fieldset>
                  <legend><?php echo $this->lang->line('label_tracker_campaign_time_settings_page_title'); ?></legend>
                    <p><?php echo $this->lang->line('label_tracker_campaign_conversion'); ?> <strong><?php echo strtoupper($campaign_data->campaignname); ?></strong></p>
					<p>
						<label for="name"><strong><?php echo $this->lang->line('label_views'); ?></strong></label>
                        <input style="margin-right:2px" value="<?php echo $view_conversion['days']; ?>" type="text" class="validate[funcCall[dayValidate]]" name="view_days"  id="view_days" size="3" /><?php echo $this->lang->line('label_days'); ?>
						<input style="margin-right:2px" value="<?php echo $view_conversion['hours']; ?>"type="text"  name="view_hours"  id="view_hours" class="validate[funcCall[hourValidate]]" size="3" /><?php echo $this->lang->line('label_hours'); ?>
						<input style="margin-right:2px" value="<?php echo $view_conversion['minutes']; ?>" type="text" name="view_minutes"  id="view_minutes" class="validate[funcCall[minValidate]]" size="3" /><?php echo $this->lang->line('label_minutes'); ?>
						<input style="margin-right:2px" value="<?php echo $view_conversion['seconds']; ?>" type="text" name="view_seconds"  id="view_seconds" class="validate[funcCall[secValidate]]" size="3" /><?php echo $this->lang->line('label_seconds'); ?>
                    </p>
					<p>
						<label for="name"><strong><?php echo $this->lang->line('label_click'); ?></strong></label>
                        <input style="margin-right:2px" value="<?php echo $click_conversion['days']; ?>" type="text" name="click_days"  id="click_days" class="validate[funcCall[dayValidate]]" size="3" /><?php echo $this->lang->line('label_days'); ?>
						<input style="margin-right:2px" value="<?php echo $click_conversion['hours']; ?>" type="text" name="click_hours"  id="click_hours" class="validate[funcCall[hourValidate]]" size="3" /><?php echo $this->lang->line('label_hours'); ?>
						<input style="margin-right:2px" value="<?php echo $click_conversion['minutes']; ?>" type="text" name="click_minutes"  id="click_minutes" class="validate[funcCall[minValidate]]" size="3" /><?php echo $this->lang->line('label_minutes'); ?>
						<input style="margin-right:2px" value="<?php echo $click_conversion['seconds']; ?>" type="text" name="click_seconds"  id="click_seconds" class="validate[funcCall[secValidate]]" size="3" /><?php echo $this->lang->line('label_seconds'); ?>
                    </p>
                    <p>
                    	<button><?php echo $this->lang->line('label_save'); ?></button>
						<button onclick="javascript: goToList();" style="margin-left:10px;" type="button"><?php echo  $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
        	 <input type="hidden" name="sel_advertiser_id" id="sel_advertiser_id" value="<?php echo $sel_advertiser_id; ?>" />
		 	 <input type="hidden" name="sel_tracker_id" id="sel_tracker_id" value="<?php echo $sel_tracker_id; ?>" />
			  <input type="hidden" name="sel_campaign_id" id="sel_campaign_id" value="<?php echo $sel_campaign_id; ?>" />
		   	<input type="hidden" name="sel_status" id="sel_status" value="<?php echo $sel_status; ?>" />
        </form>
		<script type="text/javascript">
		function goToList(){
					document.location.href='<?php echo site_url("admin/inventory_advertisers/trackers_linked_campaigns/$sel_status/$sel_advertiser_id/$sel_tracker_id"); ?>';
			}
		
		</script>
