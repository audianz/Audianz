
	<script>
			jQuery(document).ready(function(){
				// binds form submission and fields to the validation engine
				jQuery("#campaign_edit_form").validationEngine();
			});
			
		</script>
		
		
		
		<script type="text/javascript">	
		
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
			
			function status_check(field, rules, i, options)
	{
		var camp_status 	= 		field.val();
		var id				=		document.getElementById('campaign_id').value;
		
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/settings_campaign_status/status_check"); ?>",
				data:"camp_status="+camp_status + "&camp_id="+id,
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
	 

	</script>
	
	<script type="text/javascript">
function wresData(field, rules, i, options)
	
	{
		
		var storeData 	= 		document.getElementById('wcheckdata').value;
	
		if(storeData ==0)
		{
		
			return '<?php echo $this->lang->line('label_inventory_campaign_status_exists'); ?>';
		}	
	}
	</script>
			<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_campaign_status_title');?></h1><!-- Display page title dynamically -->
  
  <form id="campaign_edit_form" action="<?php echo site_url("admin/settings_campaign_status/update_status");?>" method="post">
			<?php 
			if(count($record) > 0): //Condition to check whether the record exist or not
			foreach($record as $row):?>        
        <div class="form_default">
                <fieldset>
                 
				 <legend><?php echo $this->lang->line("label_inventory_campaign_edit_status_feildset");?> </legend>
				 
				 <?php echo validation_errors();?>
                   <p>
                    	<label for="name"><?php echo $this->lang->line('label_inventory_campaign_status_title');?>
						<span style="color:red;">*</span> </label>
                        <input type="text" name="new_status"  id="status_name"  
						class="validate[required,funcCall[alphacheck]] sf"  
						alt="<?php echo $this->lang->line('label_inventory_campaign_enter_status');?>" 
						value="<?php echo form_text((set_value('update_campaign_status')!='')?
						set_value('update_campaign_status'):$row->status);?>"/>
						<input type="hidden" value="<?php echo $row->campaign_status_id;?>" name="campaign_id" id="campaign_id" />
						<input type="hidden" value="<?php echo $row->status;endforeach;?>" name="status" />
                    </p>
					 <p>
                    	<button><?php echo $this->lang->line("label_submit");?> </button>
						<button type="button"  style="margin-left:10px" onclick="location.href='<?php echo site_url("admin/settings_campaign_status")?>'"><?php echo $this->lang->line("label_cancel");?> </button>
						
						<input type="hidden" id="wcheckdata" name="wcheckdata" />
                    </p>
					
					<p>
					<?php else: 
					 echo $this->lang->line("label_inventory_campaign_status_no_records"); 
					 endif;
					 ?>
					</p>
                    
               	 </fieldset>
         </div>
</form> <!--form-->


