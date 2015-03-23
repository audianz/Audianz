<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/general.js"></script>

<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#trackers_type_form").validationEngine();
	});

</script>
<script>
 function Numericcheck(field, rules, i, options)
                {
						var reg                 = /^[-]?[0-9\.]+[\.]?[0-9\.]+$/;
                        var value        =                field.val();
                        if(!reg.test(value))
                                {
                                        return "<?php echo $this->lang->line('label_enter_numbers_only'); ?>";
                                }}
function Alpha_numericcheck(field, rules, i, options)
                {
                        var reg                 = /^([a-z0-9])+$/i;
                        var value        =                field.val();
                        if(!reg.test(value))
                                {
                                        return "<?php echo $this->lang->line('label_enter_alphanumeric_values_only'); ?>";
                                }}
								

</script>     
<script>
 function typeDupcheck(field, rules, i, options)
        {
                var trackers_status = field.val();
                var flag = 1;
                jQuery.ajax({ cache: false,

                        type : "POST",

                        url: "<?php echo site_url("admin/settings_trackers_status/trackers_status_check"); ?>",
                        data: "trackers_status="+trackers_status,

                        success : function (data) {
                                if(data == '1')
                                {
                                        document.getElementById('ucheckdata').value = data;
                                }
                                else
                                {
                                        document.getElementById('ucheckdata').value = data;
                                }
                        }
                });
                                
        }
        
        function uresData(field, rules, i, options)
        {
                var storeData = document.getElementById('ucheckdata').value;
                if(storeData == 'yes')
                {
                        return '<?php echo $this->lang->line('label_inventory_trackers_status_exists'); ?>';
                }
        }
</script>

<form action="<?php echo site_url("admin/settings_trackers_status/insert_status"); ?>" method="post" id="trackers_type_form">
        
        	<div class="form_default">
                <fieldset>
					<?php  echo validation_errors();?>
                    <legend><?php echo $this->lang->line("label_inventory_trackers_add_trackers_status");?> </legend>
                    
                    <p>
                    	<label for="trackers_status"><?php echo $this->lang->line('label_inventory_trackers_trackers_status'); ?> </label>
                        <input type="text" name="trackers_status"  id="trackers_status" class="validate[required,funcCall[typeDupcheck],funcCall[uresData],funcCall[Alpha_numericcheck]]"  value="<?php echo set_value('trackers_status');?>"  
						alt="<?php echo $this->lang->line('label_trackers_type_enter_name');?>"/>
					</p>
					
					
					<p>
                    	<label for="trackers_value"><?php echo $this->lang->line('label_inventory_trackers_trackers_value'); ?> </label>
                        <input type="text" name="trackers_value"  id="trackers_value" class="validate[required,funcCall[Numericcheck]]"  value="<?php echo set_value('trackers_value');?>"  
						alt="<?php echo $this->lang->line('label_trackers_type_enter_value');?>"/>
					</p>
					
					<p>
                <label for="email"><?php echo $this->lang->line('label_inventory_trackers_trackers_active'); ?></label>
              <input type="checkbox" name="active" checked="checked" value="1" />
            	</p>
					
					
					
					
				  	<p>
                    	<button onClick="<?php echo site_url("admin/settings_trackers_status/insert_status"); ?>"><?php echo $this->lang->line("label_submit");?> </button>
						<input type="hidden" id="ucheckdata" name="ucheckdata" />
                    </p>
                    
                </fieldset>
            </div><!--form-->

</form>
		
