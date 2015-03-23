<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#adv_signup_form").validationEngine();
	});
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
	
	function checkCPassword(field, rules, i, options){

		if (field.val() == "") {
                    // this allows to use i18 for the error msgs
			        return "<?php echo $this->lang->line('lang_advertiser_enter_cpassword'); ?>";
                }
		else if(document.getElementById("password").value != field.val() ){
		  return "<?php echo $this->lang->line('lang_advertiser_password_cpassword_not_match'); ?>";
		}

    }

	function emailValidateNew(field, rules, i, options)
	{

		var email = field.val();

		var mailadd = email.split("@");

		if(mailadd.length>1)
		{
			var finalsplit = mailadd[1].split(".");
			if(finalsplit.length <= 1){
				return '<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>';
			}
			else{
				if(finalsplit[1]=='' || finalsplit[1]==null){
					return '<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>';
				}
			}
		}
		else
		{
			return '<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>';
		}

	}

	function emailDupcheck(field, rules, i, options)
	{
		var email = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/inventory_advertisers/email_check"); ?>",
			data: "email="+email,
			success : function (data) {
				if(data == 'yes')
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
	
	function resData(field, rules, i, options)
	{
		var storeData = document.getElementById('checkdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_advertiser_email_already_exists'); ?>';
		}
	}
	
	function usernameDupcheck(field, rules, i, options)
	{
		var username = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("admin/inventory_advertisers/username_check"); ?>",
			data: "username="+username,

			success : function (data) {
				if(data == 'yes')
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
			return '<?php echo $this->lang->line('lang_advertiser_username_already_exists'); ?>';
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
   
</script>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_advertisers_page_title'); ?></h1>
<form  class="formular" id="adv_signup_form"  name="adv_signup_form" action="<?php echo site_url('admin/inventory_advertisers/process/advertiser/edit'); ?>" method="post">
  <div class="form_default">
    <fieldset>
    <?php echo validation_errors(); ?>
    <?php
						if($this->session->flashdata('error_user_already_exists') != ''){
					?>
    <div style="width:auto;margin:5px;" class="notification msgerror"> <?php echo $this->session->flashdata('error_user_already_exists'); ?> </div>
    <?php
						
						}
					?>
    <legend><?php echo $this->lang->line('label_inventory_advertisers_edit_form_page_title'); ?></legend>
    <p>
      <label for="name"><?php echo $this->lang->line('label_name'); ?><span style="color:red;">*</span></label>
      <input type="text" class="validate[required] sf"  name="name" alt="<?php echo $this->lang->line('lang_advertiser_enter_name'); ?>"  id="name" value="<?php echo form_text((set_value('name') != '')?set_value('name'):$advertiser_data->contact);?>"  />
    </p>
    <p>
      <label for="email"><?php echo $this->lang->line('label_email'); ?><span style="color:red;">*</span></label>
      <input type="text" readonly="readonly" name="email" class="validate[required,funcCall[emailDupcheck],funcCall[resData]] sf" alt="<?php echo $this->lang->line('lang_advertiser_enter_email');?>" value="<?php echo form_text((set_value('email') != '')?set_value('email'):$advertiser_data->email);?>"  id="email" />
    </p>
    <p>
      <label for="username"><?php echo $this->lang->line('label_username'); ?><span style="color:red;">*</span></label>
      <input readonly="readonly" type="text" name="username"  id="username" value="<?php echo form_text((set_value('username') != '')?set_value('username'):$advertiser_data->username);?>"   class="validate[required,,funcCall[usernameDupcheck],funcCall[uresData]] sf" alt="<?php echo $this->lang->line('lang_advertiser_enter_username');?>" />
      <span id="ajaxMsg" ></span> </p>
    <p>
      <label for="address"><?php echo $this->lang->line('label_address'); ?><span style="color:red;">*</span></label>
      <textarea name="address" id="address"  class="validate[required] mf" alt="<?php echo $this->lang->line('lang_advertiser_enter_address');?>" cols="" rows=""><?php echo form_text((set_value('address') != '')?set_value('address'):$advertiser_data->address);?></textarea>
      <?php //echo form_error('address'); ?>
    </p>
    <p>
      <label for="city"><?php echo $this->lang->line('label_city'); ?><span style="color:red;">*</span></label>
      <input type="text" name="city"  id="city"  class="validate[required,custom[onlyLetterSp]] sf" value="<?php echo form_text((set_value('city') != '')?set_value('city'):$advertiser_data->city);?>"  alt="<?php echo $this->lang->line('lang_advertiser_enter_city');?>" />
    </p>
    <p>
      <label for="state"><?php echo $this->lang->line('label_state'); ?><span style="color:red;">*</span></label>
      <input type="text" name="state"  id="state"  class="validate[required,custom[onlyLetterSp]] sf" alt="<?php echo $this->lang->line('lang_advertiser_enter_state');?>"  value="<?php echo form_text((set_value('state') != '')?set_value('state'):$advertiser_data->state);?>" />
    </p>
    <p>
      <label for="country"><?php echo $this->lang->line('label_country'); ?><span style="color:red;">*</span></label>
      <?php
		$sel_country = (set_value('country') != '')?set_value('country'):$advertiser_data->country;
         $options[""] = $this->lang->line('label_select_country');
		  foreach ($country as $cobj) { $options[$cobj->name] =$cobj->name; } ?>
      <?php echo form_dropdown('country', $options,$sel_country,"class='validate[required] sf' alt='".$this->lang->line('lang_advertiser_enter_country')."'"); ?> </p>
    <p>
      <label for="mobile"><?php echo $this->lang->line('label_mobile'); ?><span style="color:red;">*</span></label>
      <input type="text" name="mobile"  id="mobile"  class="validate[required] sf" value="<?php echo form_text((set_value('mobile') != '')?set_value('mobile'):$advertiser_data->mobileno);?>"  alt="<?php echo $this->lang->line('lang_advertiser_enter_mobile');?>" />
    </p>
    <p>
      <label for="zib_code"><?php echo $this->lang->line('label_zip_code'); ?><span style="color:red;">*</span></label>
      <input type="text" name="zip_code"  id="zip_code"  class="validate[required,funcCall[Numericcheck]] sf" value="<?php echo form_text((set_value('zip_code') != '')?set_value('zip_code'):$advertiser_data->postcode);?>"  alt="<?php echo $this->lang->line('lang_advertiser_enter_zip_code');?>" />
    </p>
    <p>
      <button><?php echo $this->lang->line('label_submit'); ?></button>
	  <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
      <input type="hidden" id="checkdata" name="checkdata" />
      <input type="hidden" id="ucheckdata" name="ucheckdata" />
      <input type="hidden" id="advertiser_account_id" name="advertiser_account_id" value="<?php echo $advt_account_id;?>" />
    </p>
    </fieldset>
  </div>
  <!--form-->
</form>
<script>
	function goToList(){
			document.location.href='<?php echo site_url('admin/inventory_advertisers'); ?>';
	}
</script>
