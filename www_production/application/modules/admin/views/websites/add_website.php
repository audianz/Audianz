
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_website_form").validationEngine();
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
	function namecheck(field, rules, i, options)
		{
				var keyword		=	field.val();
				var alpha			=	/^[0-9a-zA-Z\s\_]+[\.]?[0-9a-zA-Z\s\_]+$/;
				if(!alpha.test(keyword))
					{
						return  '<?php echo $this->lang->line('label_static_page_contains_invalid'); ?>';
						
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
			
			
		<!-- Function which checks for character length-->
	function charlength(field, rules, i, options)
		{
				var keyword		=	field.val();
				if((keyword.length)>20||(keyword.length)<3)
					{
						return  '<?php echo $this->lang->line('lang_static_page_char_menu'); ?>';
						
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
			
						
	
	function checkCPassword(field, rules, i, options)
		{
			if (field.val() == "") 
					{
						// this allows to use i18 for the error msgs
						return "<?php echo $this->lang->line('lang_advertiser_enter_cpassword'); ?>";
					}
					
			else if(document.getElementById("password").value != field.val() )
					{
			  			return "<?php echo $this->lang->line('lang_advertiser_password_cpassword_not_match'); ?>";
			
					}
		}

	<!-- Function which checks for valid email-->
	function emailValidateNew(field, rules, i, options)
		{

		var email = field.val();
                var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		//var mailadd = email.split("@");
       if(field.val().search(emailRegEx) == -1)
		{
		 			
		return '<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>';
		}

		}

	<!-- Function which checks for valid url -->
	function validateurl(feild,rules,i,options)
	{
		
var RegExp = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;

    if (! RegExp.test(feild.val())) 
		 {
		 
        	return '<?php echo $this->lang->line('lang_website_enter_valid_url'); ?>';
		}
    
	} 

		<!-- Function which check for email duplication -->
	function emailDupcheck(field, rules, i, options)
	{
		var email 		= 		field.val();
		var flag 			= 		1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/inventory_websites/email_check"); ?>",
			data: "email="+email,

			success : function (data) {
				if(data == 1)
				{
					document.getElementById('checkdata').value = data;
				}
				else
				{
					document.getElementById('checkdata').value = data;
				}
			}
		});
				
	}
	
	
	<!-- Function which check for username duplication -->
	function usernameDupcheck(field, rules, i, options)
	{
		var username 	= 	field.val();
		var flag 				= 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/inventory_websites/username_check"); ?>",
			data: "username="+username,

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
	
	function userDupcheck(field, rules, i, options)
	{
		var username 	= 		field.val();
		var flag 				= 		1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/inventory_websites/user_check"); ?>",
			data: "username="+username,

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
	
	<!-- Function which check for website_url duplication -->
	function websiteDupcheck(field, rules, i, options)
	{
		var website_url 	= 		field.val();
		var flag 				= 		1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/inventory_websites/website_check"); ?>",
			data: "website_url="+website_url,

			success : function (data) {
				if(data == 1)
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
	
	function resData(field, rules, i, options)
	{
		var storeData 	= 		document.getElementById('checkdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_website_email_already_exists'); ?>';
		}
	}
	
	function uresData(field, rules, i, options)
	{
		var storeData 	= 		document.getElementById('ucheckdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_website_username_already_exists'); ?>';
		}
	}
   
</script>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_websites_addsite_feildset'); ?></h1>
  <form action="<?php echo site_url("admin/inventory_websites/insert_site");?>" method="post" name="add_website_form" id="add_website_form">
        
<div class="form_default">

<fieldset>
		<legend><?php echo $this->lang->line("label_inventory_websites_addsite_feildset");?> </legend>
			<?php echo validation_errors(); ?>	
			
			<p>
				<label for="website_url"><?php echo $this->lang->line("label_inventory_websites_addsite_name");?> <span style="color:red;">*</span></label>
						<input type="text" 
						 name="website_url"  id="website_url" 
						class="validate[required,funcCall[validateurl],funcCall[websiteDupcheck],funcCall[wresData]] sf" 
						value="http://" "<?php echo form_text((set_value('website_url')=='')?set_value('website_url'):"http://".set_value('website_url'));?>" 
						alt="<?php echo $this->lang->line("lang_website_enter_url");?>"/>
			</p>
			
			<p>
				<label for="email"><?php echo $this->lang->line("label_inventory_websites_addsite_email");?> <span style="color:red;">*</span></label>
						<input type="text" name="email"  id="email" 
						class="validate[required,funcCall[emailValidateNew],funcCall[emailDupcheck],funcCall[resData]] sf" 
						value="<?php echo form_text(set_value('email'));?>" 
						alt="<?php echo $this->lang->line("lang_website_enter_email");?>"/>
			</p>
			
			<p>
				<label for="username"><?php echo $this->lang->line("label_inventory_websites_addsite_username");?> <span style="color:red;">*</span></label>
						<input type="text" name="username" id="username"  
						class="validate[required,funcCall[usernameDupcheck],funcCall[userDupcheck],funcCall[uresData],funcCall[namecheck],funcCall[charlength] sf" 
						alt="<?php echo $this->lang->line("lang_website_enter_username");?>" 
						value="<?php  echo form_text(set_value('username'));?>" />
			</p>
			
			<p>
				<label for="password"><?php echo $this->lang->line("label_inventory_websites_addsite_password");?><span style="color:red;">*</span> </label>
						<input type="password" name="password"  AUTOCOMPLETE=OFF
						alt="<?php echo $this->lang->line("lang_website_enter_password");?>" id="password" 
						class="validate[required] sf"
						value="<?php echo form_text(set_value('password'));?>" />
			</p>
			
			<?php if($start==1):?>
			
			<p style="display:none;">
				<label for="cpassword"><?php echo $this->lang->line("label_inventory_websites_addsite_confirm_password");?><span style="color:red;">*</span> </label>
						<input type="password" name="confirmpwd"  id="cpassword"  size="20"
						alt="<?php echo $this->lang->line("lang_website_enter_cpassword");?>" 
						class="validate[funcCall[checkCPassword]] sf" 
						value="<?php echo form_text(set_value('confirmpwd'));?>"/>
			</p>
			<?php else: ?>
			<p>
				<label for="cpassword"><?php echo $this->lang->line("label_inventory_websites_addsite_confirm_password");?><span style="color:red;">*</span> </label>
						<input type="password" name="confirmpwd"  id="cpassword"  
						alt="<?php echo $this->lang->line("lang_website_enter_cpassword");?>" 
						class="validate[funcCall[checkCPassword]] sf" 
						value="<?php echo form_text(set_value('confirmpwd'));?>"/>
			</p>
			<?php endif;?>
			
			<p>
				<label for="publisher_share"><?php echo $this->lang->line("label_inventory_websites_addsite_publisher_share");?><span style="color:red;">*</span> </label>
						<input type="text" name="publisher_share"   
						alt="<?php echo $this->lang->line("lang_website_enter_publisher_share");?>" id="publisher_share"
						class="validate[required,funcCall[Numericcheck]] sf" 
						value="<?php echo form_text(set_value('publisher_share'));?>"/>
			</p>	
			
			<?php
				$sel_category	='';
				$options1		=array();
				//$options1[""] = "Select Category";
				if(!empty($category_list))
				{
					foreach ($category_list as $obj) { $options1[$obj->category_id] =$obj->category_name; } 
				}
			?>
		
			<p>
			   <label for="category"><?php echo $this->lang->line('label_campaign_category'); ?></label>
			   <?php echo form_multiselect('category[]', $options1, $sel_category, "class='sf'"); // ,"class='validate[required] sf' alt='".$this->lang->line('label_select_campaign_category')."'" ?>
			   <?php echo $this->lang->line('note_for_category_select'); ?>
		   </p>
		
			<p>
				<label for="location"><?php echo $this->lang->line("label_inventory_websites_addsite_address");?><span style="color:red;">*</span> </label>
				<textarea name="address" id="address"  class="validate[required] mf" 
				alt="<?php echo $this->lang->line('label_inventory_websites_addsite_address_field'); ?>" cols="" rows=""><?php echo form_text(set_value('address'));?></textarea>
			</p>
			
			<p>
				<label for="city"><?php echo $this->lang->line("label_inventory_websites_addsite_city");?> <span style="color:red;">*</span></label>
						<input type="text" name="city"  id="city" 
						class="validate[required,funcCall[alphacheck]] sf"
						alt="<?php echo $this->lang->line("lang_website_enter_city");?>"
						value="<?php echo form_text(set_value('city'));?>"/>
			</p>
			
			<p>
				<label for="state"><?php echo $this->lang->line("label_inventory_websites_addsite_state");?> <span style="color:red;">*</span></label>
						<input type="text" name="state"  id="state" 
						class="validate[required,funcCall[alphacheck]] sf" 
						alt="<?php echo $this->lang->line("lang_website_enter_state");?>" 
						value="<?php echo form_text(set_value('state'));?>"/>
			</p>
			
			<p>
				<label for="country"><?php echo $this->lang->line("label_inventory_websites_addsite_country");?><span style="color:red;">*</span> </label>
					<?php
					$options[""] ="Select Country";
					foreach ($country as $cobj) { $options[$cobj->name] =$cobj->name; } ?>
					<?php echo form_dropdown('country', $options,set_value('country'),"class='validate[required] sf' alt='".$this->lang->line('lang_website_enter_country')."'"); ?> 
			</p>
			
		   <p>
				<label for="mobileno"><?php echo $this->lang->line("label_inventory_websites_addsite_mobileno");?> <span style="color:red;">*</span></label>
						<input type="text" name="mobileno"  id="mobile" 
						class="validate[required,funcCall[Mobilenocheck]] sf" 
						alt="<?php echo $this->lang->line("lang_website_enter_mobile");?>" 
						value="<?php echo form_text(set_value('mobileno'));?>" />
			</p>
			
			<p>
				<label for="zipcode"><?php echo $this->lang->line("label_inventory_websites_addsite_zip_code");?><span style="color:red;">*</span> </label>
						<input type="text" name="zipcode"  id="zip_code" 
						class="validate[required,funcCall[Mobilenocheck]] sf" 
						alt="<?php echo $this->lang->line("lang_website_enter_zip_code");?>" 
						value="<?php echo form_text(set_value('zipcode'));?>"/>
			</p>
			
			<p>
				<button><?php echo $this->lang->line("label_submit");?> </button>
				<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
						<input type="hidden" id="checkdata" name="checkdata" />
						<input type="hidden" id="ucheckdata" name="ucheckdata" />
						<input type="hidden" id="wcheckdata" name="wcheckdata" />
			</p>
			
</fieldset>

</div><!--form-->
  	     </form>
<script>
	function goToList(){
			document.location.href='<?php echo site_url('admin/inventory_websites'); ?>';
	}
</script>          
