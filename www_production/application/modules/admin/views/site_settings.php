<?php 
if($this->session->flashdata('message') != ""):
   ?>
	<div class="notification msgsuccess"><a class="close"></a>
	  <p>
		<?php 
			echo $this->session->flashdata('message');	
	 	?>
	 </p>
	</div>
<?php
endif;
?>
</script>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#site_settings").validationEngine();
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
	
	function isValidURL(field, rules, i, options){
		var RegExp =  /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
	
			if(!RegExp.test(field.val())){
			return '<?php echo $this->lang->line('lang_site_settings_enter_valid_site_url'); ?>';
		}
	} 
</script>
<?php foreach ($getrecord as $row_record) 
{
?>
<h1 class="pageTitle"><?php echo $this->lang->line("settings_site_settings"); ?></h1>
<form  method="post" enctype="multipart/form-data" name="site_settings" id="site_settings" action="<?php echo site_url('admin/site_settings/site_settings_update');?>" >
  <div class="form_default">
    <fieldset>
    <?php 
echo validation_errors();
?>
    <legend><?php echo $this->lang->line("settings_site_settings_head"); ?></legend>
    <p>
      <label for="name"><?php echo $this->lang->line("settings_site_title"); ?><span style="color:red;">*</span></label>
      <input 
	  	type="text" 
		name="site_title"  
		class="validate[required] sf"  
		alt="<?php echo $this->lang->line('lang_site_settings_site_title'); ?>"   
		id="site_title" 
		value="<?php echo form_text((set_value('site_title') != '')?set_value('site_title'):$row_record->site_title);?>" 
	  />
    </p>
    <p>
      <label for="name"><?php echo $this->lang->line("settings_site_tagline"); ?></label>
      <input type="text" name="tagline"  class="sf"   id="tagline"
	   value="<?php echo form_text((set_value('tagline') != '')?set_value('tagline'):$row_record->tag_line);?>" />
    </p>
  <!--  <p>
      <label for="name"><?php echo $this->lang->line("settings_site_timezone"); ?></label>
      <?php
	  $sel_time_zone =form_text(set_value('time_zone') != '')?set_value('time_zone'):$row_record->time_zone ;
      foreach ($time_zone as $cobj) { $options_time[$cobj->value] =$cobj->value; } ?>
      <?php echo form_dropdown('time_zone', $options_time,$sel_time_zone,"class='validate[required] sf' alt='".$this->lang->line('lang_advertiser_enter_country')."'"); ?> </p>-->
    <!--<p>
      <label for="name"><?php echo $this->lang->line("settings_site_url"); ?><span style="color:red;">*</span></label>
      <input type="text" 
	         name="site_url"
		     class="validate[required,funcCall[isValidURL]] sf"  
			 alt="<?php echo $this->lang->line('lang_site_settings_site_url');?>" 
			 id="site_url" 
			 value="<?php echo form_text((set_value('site_url') != '')?set_value('site_url'):$row_record->site_url);?>" 
	 />
    </p>-->
    <p>
      <label for="name"><?php echo $this->lang->line("settings_site_logoupload"); ?></label>
      <input type="file" 
	         name="image_upload" 
			 id="image_upload"  
			 class="sf"
                         onchange="Checkfiles()"  
	 / >
      <?php if($row_record->logo != ''): ?>
      <a style="margin-left:10px;" class="view" href="<?php echo base_url().$this->config->item('admin_site_logo_view').$row_record->logo; ?>"><?php echo $this->lang->line('label_view_image'); ?></a>
	<a style="margin-left:10px;" href="<?php echo site_url('admin/site_settings/delete_admin_logo');?>"> <?php echo $this->lang->line('label_delete'); ?></a>
      <?php endif; ?>
    </p>
     <div class="notification msginfo"  style="width:40%;margin-left:170px;padding:5px;background:#C7D6EC;font-size:10px;" ><?php echo $this->lang->line('lang_site_settings_image_upload_notice');?>
                  
     </div>
    <p>
      <label for="email"><?php echo $this->lang->line("settings_site_email"); ?><span style="color:red;">*</span></label>
      <input type="text" 
	         name="email" 
			 class="validate[required,funcCall[emailValidateNew]] sf" 
			 alt="<?php echo $this->lang->line('lang_advertiser_enter_email');?>" 
			 value="<?php echo form_text((set_value('email') != '')?set_value('email'):$row_record->Email);?>"  
			 id="email" 
	 />
    </p>
    <p>
      <label for="address"><?php echo $this->lang->line("settings_site_address"); ?><span style="color:red;">*</span></label>
      <textarea name="address" 
	  			class="validate[required] sf" 
				cols="" rows="" 
				alt="<?php echo $this->lang->line('lang_site_settings_site_address');?>" 
				id="address" 
				><?php echo form_text((set_value('address') != '')?set_value('address'):$row_record->address);?></textarea>
    </p>
    <p>
      <label for="city"><?php echo $this->lang->line("settings_site_city"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="city"  
			 class="validate[required,custom[onlyLetterSp]]sf" 
			 alt="<?php echo $this->lang->line('lang_site_settings_site_city');?>" 
			 id="city" 
			 value="<?php echo form_text((set_value('city') != '')?set_value('city'):$row_record->city);?> "/>
	</p>
    <p>
      <label for="state"><?php echo $this->lang->line("settings_site_state"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="state"  
	         class="validate[required,custom[onlyLetterSp]]sf" 
	         alt="<?php echo $this->lang->line('lang_site_settings_site_state');?>" 
	         id="state" 
			 value="<?php echo form_text((set_value('state') != '')?set_value('state'):$row_record->state);?>"  
	 />
    </p>
    <p>
      <label for="occupation"><?php echo $this->lang->line("settings_site_country"); ?></label>
      <?php
	    $sel_country = form_text(set_value('country') != '')?set_value('country'):$row_record->country;
		$options_country['']=$this->lang->line('registration_country_select');
      foreach ($country as $cobj) { $options_country[$cobj->name] =$cobj->name; } ?>
      <?php echo form_dropdown('country', $options_country,$sel_country,"class='validate[required] sf' alt='".$this->lang->line('lang_advertiser_enter_country')."'"); ?>
      <?php
	}
	?>


    </p>
    <p>
      <button><?php echo $this->lang->line("label_save"); ?></button>
    <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
      <input type="hidden" id="checkdata" name="checkdata" />
      <input type="hidden" id="ucheckdata" name="ucheckdata" />
    </p>
    </fieldset>
  </div>
  <!--form-->
</form>
</div>
<script>
function Checkfiles()
{
var fup = document.getElementById('image_upload');
var image_upload = fup.value;
var ext = image_upload.substring(image_upload.lastIndexOf('.') + 1);
if(ext == "gif" || ext == "GIF" || ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG" || ext == "doc" || ext == "png" || ext == "PNG")
{
return true;
} 
else
{
jAlert('<?php echo $this->lang->line("alert_upload_image");?>');
fup.focus();
return false;
}
}

			function goToList()
			{
				document.location.href='<?php echo site_url('admin/dashboard'); ?>';
			}
	</script>
<!--fullpage-->
