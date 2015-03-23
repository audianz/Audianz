
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_website_form").validationEngine();
	});
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/

	
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
		
		<!-- Function which checks for character length-->
	function charlength(field, rules, i, options)
		{
				var keyword		=	field.val();
				if((keyword.length)>20||(keyword.length)<3)
					{
						return  '<?php echo $this->lang->line('lang_static_page_char_menu'); ?>';
						
					}
			}
		
		
		<!-- Function which allows only numbers and dots -->
	function Numericcheck(field, rules, i, options)
		{
			var reg 		= /^[-]?[0-9\.]+$/;
			var value	=		field.val();
			if(!reg.test(value))
				{
					return "<?php echo $this->lang->line('lang_inventory_websites_add_only_numbers'); ?>";
				}
			
			}
			
		<!-- Function which checks for invalid characters -->
	function addresscheck(field, rules, i, options)
		{
			var reg 		= /^[-]?[0-9a-zA-Z\.\-\#\,\s]+[\.]?[0-9a-zA-Z\.\-\#\,\s]+$/;
			var value	=		field.val();
			if(!reg.test(value))
				{
					return "<?php echo $this->lang->line('label_static_page_contains_invalid'); ?>";
				}
			
			}	
			
			
		<!-- Function which allows only numbers and hypens -->
		function Mobilenocheck(field, rules, i, options)
		{
			var reg 		= /^[-]?[0-9\-]+$/;
			var value	=		field.val();
			if(!reg.test(value))
				{
					return "<?php echo $this->lang->line('label_static_page_contains_invalid'); ?>";
				}
			
			}	
	
	function validateurl(feild,rules,i,options)
	{
		
var RegExp = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;

    if (! RegExp.test(feild.val())) 
		 {
		 
        	return '<?php echo $this->lang->line('lang_website_enter_valid_url'); ?>';
		}
    
	} 
	
	function websiteDupcheck(field, rules, i, options)
	{
		var website_url 		= 		field.val();
		var account_id		=		document.getElementById('accountid').value;
		
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/inventory_websites/website_check"); ?>",
				data:"website_url="+website_url + "&account_id="+ account_id,
				success : function (data) {
				
				if(data == "no")
				{
				
						document.getElementById('wcheckdata').value = data;
				}
				else
				{
					
					document.getElementById('wcheckdata').value = data;
				}
			}
		});
				
	}
	 function wresData(field, rules, i, options)
	
	{
		
		var storeData 	= 		document.getElementById('wcheckdata').value;
	
		if(storeData == 'yes')
		{
								return '<?php echo $this->lang->line('lang_inventory_websitesew_already_exists'); ?>';
	
		
		}
	}
	
	
</script>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_websites_editsite_feildset'); ?></h1>
<form id="edit_website_form" action="<?php echo site_url("admin/inventory_websites/update_site");?>" method="post">
        
<div class="form_default">
<fieldset>
	
   <legend><?php echo $this->lang->line("label_inventory_websites_editsite_feildset");?> </legend>
   
  
 		<?php echo validation_errors(); ?>			
		
		<?php 
		if(count($record) > 0): //Condition to check whether the record exist or not
		foreach($record as $row):
		//print_r($row);
		//die();
		?>
		<p>
				<label for="website"><?php echo $this->lang->line("label_inventory_websites_addsite_name");?> <span style="color:red;">*</span></label>
						<input type="text" 
						value="<?php echo form_text((set_value('website_url') !='')?set_value('website_url'): $row->websitename); ?>" name="website_url"  id="site"  
						class="validate[required,funcCall[validateurl]] sf" 
						alt="<?php echo $this->lang->line("lang_website_enter_url");?>"  />
		</p>

		<p>
				<label for="email"><?php echo $this->lang->line("label_inventory_websites_addsite_email");?> <span style="color:red;">*</span></label>
					   <input type="text"  disabled="disabled" name="email"  id="email"  class="sf"
					   value="<?php echo $row->email; ?>" />
					   
		 </p>
								
		 <p>
				<label for="username"><?php echo $this->lang->line("label_inventory_websites_addsite_username");?> <span style="color:red;">*</span></label>
						<input type="text" name="username"  disabled="disabled" AUTOCOMPLETE=OFF
						value="<?php echo $row->username; ?>" id="name"  
						class="sf"   />
		</p>
			
		    
		<p>
				<label for="publisher_share"><?php echo $this->lang->line("label_inventory_websites_addsite_publisher_share");?><span style="color:red;">*</span> </label>
						 <input type="text" name="publisher_share" 
						  value="<?php echo form_text((set_value('publisher_share') !='')?set_value('publisher_share'):$row->publishershare);?>"  id="share" 
						  alt="<?php echo $this->lang->line("lang_website_enter_publisher_share");?>" 
						   class="validate[required,funcCall[Numericcheck]] sf" />
		</p>
			
			<?php
				$sel_category	=explode(",", $row->oac_category_id);
				$options1	=array();
				//$options["0"] =$this->lang->line('label_select_category');
				foreach ($category_list as $obj) { $options1[$obj->category_id] =$obj->category_name; } 
			?>
		
			<p>
			   <label for="category"><?php echo $this->lang->line('label_campaign_category'); ?></label>
			   <?php echo form_multiselect('category[]', $options1, @$sel_category, "class='sf'"); // , "class='validate[required] sf' alt='".$this->lang->line('label_select_campaign_category')."'" ?>
			   <?php echo $this->lang->line('note_for_category_select'); ?>
		   </p>
								 
		<p>
				<label for="location"><?php echo $this->lang->line("label_inventory_websites_addsite_address");?><span style="color:red;">*</span> </label></label>
							<?php 
							$address=$row->address;
							$address1=str_replace('\n',"",$address);
							$address2=str_replace('\r',"",$address1);
							$data = preg_replace('@(<|&gt;)br\s*/?(>|&lt;)@', "\n", $address2);?>
						    <textarea name="address" id="address"  class="validate[required] mf" 
				alt="<?php echo $this->lang->line('label_inventory_websites_addsite_address_field'); ?>" cols="" rows=""><?php echo form_text((set_value('address') != '')?set_value('address'):$data);?></textarea>
							
							 
		</p>
			  
		<p>
			   <label for="city"><?php echo $this->lang->line("label_inventory_websites_addsite_city");?> <span style="color:red;">*</span></label>
							<input type="text" name="city" 
							value="<?php echo form_text((set_value('city') !='')?set_value('city'):$row->city); ?>"  
							id="city" class="validate[required,funcCall[alphacheck]] sf" 
						   alt="<?php echo $this->lang->line("lang_website_enter_city");?>" />
		</p>
			   
		<p>
				<label for="state"><?php echo $this->lang->line("label_inventory_websites_addsite_state");?> <span style="color:red;">*</span></label>
							<input type="text" name="state" 
							value="<?php echo form_text((set_value('state') !='')?set_value('state'):$row->state); ?>"  id="state"  
							alt="<?php echo $this->lang->line("lang_website_enter_state");?>" 
							class="validate[required,funcCall[alphacheck]] sf" />
	   </p>
								
		<p>
				<label for="country"><?php echo $this->lang->line("label_inventory_websites_addsite_country");?><span style="color:red;">*</span> </label>
							<?php
							$sel_country = (set_value('country') != '')?set_value('country'):$row->country;
							foreach ($country as $cobj) { $options[$cobj->name] =$cobj->name; } ?>
							<?php echo form_dropdown('country', $options,$sel_country,"class='validate[required] sf' alt='".$this->lang->line('lang_website_enter_country')."'"); ?>
		</p>
				 
		<p>
				<label for="mobileno"><?php echo $this->lang->line("label_inventory_websites_addsite_mobileno");?> <span style="color:red;">*</span></label>
							<input type="text" name="mobileno"  
							value="<?php echo form_text((set_value('mobileno') !='')?set_value('mobileno'):$row->mobileno); ?>" id="mobileno" 
							alt="<?php echo $this->lang->line("lang_website_enter_mobile");?>" 
							class="validate[required,,funcCall[Mobilenocheck]] sf" />
	   </p>
			   
	   <p>
				<label for="zipcode"><?php echo $this->lang->line("label_inventory_websites_addsite_zip_code");?> <span style="color:red;">*</span></label>
						   <input type="text" name="zipcode" value="<?php echo form_text((set_value('zipcode') !='')?set_value('zipcode'):$row->postcode);  ?>"  id="zipcode"  
						   alt="<?php echo $this->lang->line("lang_website_enter_zip_code");?>" class="validate[required,funcCall[Mobilenocheck]] sf"  />
						   <input type="hidden" name="update_id" value="<?php echo $row->id;?>"/>
						   <input type="hidden" name="account_id" id="accountid" value="<?php echo $row->accountid;?>" />
							
		<p>
				<button><?php echo $this->lang->line("label_submit");?> </button>
				<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
				<input type="hidden" id="wcheckdata" name="wcheckdata" />
		</p>
		</p>
		
		<p>
		<h1><?php endforeach;
		else:
		echo $this->lang->line('label_inventory_revenue_type_no_records');
		endif;
		?></h1>
		</p>
							
</fieldset>
</div><!--form-->
  	     </form>
<script>
	function goToList(){
			document.location.href='<?php echo site_url('admin/inventory_websites'); ?>';
	}
</script>
