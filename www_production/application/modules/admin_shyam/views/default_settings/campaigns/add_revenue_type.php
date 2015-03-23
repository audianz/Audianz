<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#revenue_type_form").validationEngine();
	});

</script>

<script type="text/javascript">
	function typeDupcheck(field, rules, i, options)
	{
		var revenue_type = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_revenue_type/revenue_type_check"); ?>",
			data: "revenue_type="+revenue_type,

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
			return '<?php echo $this->lang->line('label_inventory_revenue_type_exists'); ?>';
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

<form action="<?php echo site_url("admin/settings_revenue_type/insert_type"); ?>" method="post" id="revenue_type_form">
        
        	<div class="form_default">
                <fieldset>
					
                    <legend><?php echo $this->lang->line("label_inventory_revenue_type_feildset");?> </legend>
                   <?php echo validation_errors();?>
                    <p>
                    	<label for="name"><?php echo $this->lang->line('label_inventory_revenue_type_pricing_model');?> 
						<span style="color:red;">*</span></label>
                        <input type="text" name="new_revenue_type"  id="name" 
						class="validate[required,funcCall[typeDupcheck],funcCall[uresData],funcCall[alphacheck]] sf"  
						value="<?php echo form_text(set_value('new_revenue_type'));?>"  
						alt="<?php echo $this->lang->line("label_inventory_enter_revenue_type");?>"/>
						
                    </p>
					
				
					
				  	<p>
                    	<button onClick="<?php echo site_url("admin/settings_revenue_type/insert_type"); ?>"><?php echo $this->lang->line("label_submit");?> </button>
						<input type="hidden" id="ucheckdata" name="ucheckdata" />
                    </p>
                    
                </fieldset>
            </div><!--form-->

</form>
		