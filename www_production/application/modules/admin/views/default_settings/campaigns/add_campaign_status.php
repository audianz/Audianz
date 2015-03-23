
	<script>
			jQuery(document).ready(function(){
				// binds form submission and fields to the validation engine
				jQuery("#campaign_add_form").validationEngine();
			});

	</script>
	
	<script type="text/javascript">
	function typeDupcheck(field, rules, i, options)
	{
		var new_status = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_campaign_status/campaign_status_check"); ?>",
			data: "new_status="+new_status,

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
			return '<?php echo $this->lang->line('label_inventory_campaign_status_exists'); ?>';
		}
	}
	
	<!-- Function which allows only aplhabets -->
	function alphacheck(field, rules, i, options)
		{
				var keyword		=	field.val();
				var alpha			=	/^[a-zA-Z\s]+$/;
				if(!alpha.test(keyword))
					{
						return  '<?php echo $this->lang->line('label_static_page_only_alpha'); ?>';
						
					}
			}
</script>
<form id="campaign_add_form" action="<?php echo site_url("admin/settings_campaign_status/insert_status"); ?>" method="post">
        
      <div class="form_default">
                
			<fieldset>
                   
				    <legend><?php echo $this->lang->line("label_inventory_campaign_status_feildset");?> </legend>
					<?php echo validation_errors();?>
					
                  	 <p>
                    	<label for="name"><?php echo $this->lang->line("label_inventory_campaign_status_name");?> 
						<span style="color:red;">*</span> </label>
                        <input type="text" name="new_status"  id="name" 
						class="validate[required,funcCall[typeDupcheck],funcCall[uresData],funcCall[alphacheck]] sf"
						alt="<?php echo $this->lang->line("label_inventory_campaign_enter_status");?>"
						 value="<?php echo form_text(set_value('new_status'));?>"  />
					</p>
					
					<p>
                    	<button ><?php echo $this->lang->line("label_submit");?> </button>
						<input type="hidden" id="ucheckdata" name="ucheckdata" />
                    </p>
                    
            </fieldset>
       </div>
</form><!--form-->
		