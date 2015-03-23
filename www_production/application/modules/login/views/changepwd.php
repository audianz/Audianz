<?php 
if($this->session->flashdata('message') != ""):
   ?>
	<div class="notification msgsuccess"><a class="close"></a>
	  <p><?php echo $this->session->flashdata('message'); ?> </p>
	</div>
<?php
endif;
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
</script>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#change_password").validationEngine();
	});
function checkCPassword(field, rules, i, options){


			if (field.val() == "") {
						// this allows to use i18 for the error msgs
						return "<?php echo $this->lang->line('lang_advertiser_enter_cpassword'); ?>";
					}
			else if(document.getElementById("newpwd").value != field.val() ){
			  return "<?php echo $this->lang->line('lang_advertiser_password_cpassword_not_match'); ?>";
			}

	 }
</script>
<h1 class="pageTitle"><?php echo $this->lang->line("settings_site_changepassword"); ?></h1>
<form action="<?php //echo site_url('login/login/change_password_process'); ?>" method="post" name="change_password" id="change_password">
  <div class="form_default">
    <fieldset>
    <legend><?php echo $this->lang->line("settings_site_reset_password"); ?></legend>
    
	<p>
      <label for="name"><?php echo $this->lang->line("settings_site_Old Password"); ?></label>
      <input type="password" name="oldpwd" autocomplete="off" id="oldpwd" class="validate[required] sf"   
						alt="<?php echo $this->lang->line('lang_site_settings_Old Password');?>"  
						/>
    </p>
	
    <p>
      <label for="name"><?php echo $this->lang->line("settings_site_New Password"); ?></label>
      <input type="password"  name="newpwd" autocomplete="off" id="newpwd" class="validate[required] sf"  
						alt="<?php echo $this->lang->line('lang_site_settings_New Password');?>"
						/>
    </p>
    
	<p>
      <label for="email"><?php echo $this->lang->line("settings_site_Confirm Password"); ?></label>
      <input type="password"  name="confirmpwd" id="confirmpwd" autocomplete="off" class="validate[required,funcCall[checkCPassword]] sf" 
						alt="<?php echo $this->lang->line('lang_site_settings_Confirm Password');?> "
						/>
    </p>
    <!-- <p><button><?php echo $this->lang->line("settings_site_Submit"); ?></button></p> -->
    </fieldset>
  </div>
  <!--form-->
</form>
</div>
<!--fullpage-->
