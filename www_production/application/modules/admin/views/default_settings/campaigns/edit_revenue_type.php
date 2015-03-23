

<script type="text/javascript">
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#revenue_edit_form").validationEngine();
	});




	
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
	function revenue_dup_check(field, rules, i, options)
	{
		var revenue_type 	= 		field.val();
		var id				=		document.getElementById('revenue_id').value;
		
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_revenue_type/revenue_check"); ?>",
				data:"revenue_type="+revenue_type + "&id="+id,
				success : function (data) {
				
				if(data == "no")
				{	
				document.getElementById('wcheckdata').value = data;
				}
				else
				{
					exit();
				}
			}
		});
				
	}			
			
			
function wresData(field, rules, i, options)
	
	{
		
		var storeData 	= 		document.getElementById('wcheckdata').value;
	
		if(storeData ==0)
		{
		
			return '<?php echo $this->lang->line('label_inventory_revenue_type_exists'); ?>';
		}	
	}
	</script>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_revenue_type_title');?></h1>
<form id="revenue_edit_form" action="<?php echo site_url("admin/settings_revenue_type/update_type");?>" method="post">
	<?php 
	if(count($record) > 0): //Condition to check whether the record exist or not
	foreach($record as $row):?>        
	<div class="form_default">
                <fieldset>
					
			<legend><?php echo $this->lang->line("label_inventory_revenue_type_edit_feildset");?> </legend>
			<?php echo validation_errors();?>
		   <p>
				<label for="name"><?php echo $this->lang->line('label_inventory_revenue_type_pricing_model');?>
				<span style="color:red;">*</span> </label>
				<input type="text" name="new_revenue_type"  id="name" class="validate[required,funcCall[alphacheck]] sf" 
				 value="<?php echo form_text((set_value('update_campaign_status')!='')?
				 set_value('update_campaign_status'):$row->revenue_type);?>"
				 alt="<?php echo $this->lang->line("label_inventory_enter_revenue_type");?>"/>
				<input type="hidden" value="<?php echo $row->revenue_id;?>" name="revenue_id" id="revenue_id"/>
			</p>
			
			 <p>
				<button><?php echo $this->lang->line("label_submit");?> </button>
				<button type="button"  style="margin-left:10px" onclick="location.href='<?php echo site_url("admin/settings_revenue_type")?>'"><?php echo $this->lang->line("label_cancel");?> </button>
				<input type="hidden" id="wcheckdata" name="wcheckdata" />
			 </p>
			 
			 <p>
			<?php
			endforeach;
			 else: 
			 echo $this->lang->line("label_inventory_revenue_type_no_records"); 
			 endif;
			 ?>
			</p>
			
		</fieldset>
	</div><!--form-->
  	     </form>
		
