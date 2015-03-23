<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#myaccount").validationEngine();
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
<h1 class="pageTitle"><?php echo $this->lang->line('label_admin_myaccount');?></h1>
<?php 
if($this->session->userdata('notification_message') != ""):
   ?>
	<div class="notification msgerror"><a class="close"></a>
	  <p>
		<?php 
		echo $this->session->userdata('notification_message');	
		$this->session->unset_userdata('notification_message');
	  	?>
	  </p>
	</div>
<?php
endif;
?>
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
<?php 
if($this->session->flashdata('message_error') != ""):
   ?>
	<div class="notification msgerror"><a class="close"></a>
	  <p>
		<?php 
			echo $this->session->flashdata('message_error');	
	 	?>
	 </p>
	</div>
<?php
endif;
?>
<?php echo validation_errors();?>
    	
        <form id="myaccount"  name="myaccount"  method="post" action="<?php echo site_url('publisher/myaccount/myaccount_settings_update');?>" enctype="multipart/form-data">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line('label_admin_myaccount_information');?></legend>
                    
					<p>
					<label for="name"><?php echo $this->lang->line('label_admin_myaccount_username');?><span style="color:red;">*</span></label>
                    	 <input type="text" 
	         name="username" 
			 class="validate[required] sf" 
			 alt="<?php echo $this->lang->line('label_admin_myaccount_username_validation');?>" 
			 value="<?php echo $row_record->username; ?>"  
			readonly='true'
			 id="email" 
	 />
                    </p>				
                                        
                   <p>
     		 <label for="email"><?php echo $this->lang->line("settings_site_email"); ?><span style="color:red;">*</span></label>
      <input type="text" 
	         name="email" 
			 class="validate[required,funcCall[emailValidateNew]] sf" 
			 alt="<?php echo $this->lang->line('lang_advertiser_enter_email');?>" 
			 value="<?php echo $row_record->email; ?>"  
			readonly='true'
			 id="email" 
	 />
    </p>
	<div class="notification msginfo"  style="width:33%;margin-left:170px;padding:5px;background:#C7D6EC;font-size:10px;margin-top:-7px;" >
	 <?php echo $this->lang->line('lang_site_settings_profile_image_notice');?>
	</div>
	<p>
      <label for="name"><?php echo $this->lang->line("settings_site_profileimage"); ?></label>
      <input type="file" 
	         name="avatar_upload" 
			 id="avatar_upload"  
			 class="sf"
                         onchange="Checkfiles()"  
	 style="vertical-align:top"/ >
    <?php
if($row_record->avatar != ''): ?>
      <img src="<?php echo base_url().$this->config->item('user_img_view').$row_record->avatar; ?>" alt="<?php echo $row_record->username; ?>" title="<?php echo $row_record->username; ?>" style="padding-left:10px; border:solid:0 0 #C7D6EC;"/>
<a href="javascript:menu_confirm()"><span style="vertical-align:bottom;margin-left:10px;"> <?php echo "[-] ".$this->lang->line('label_delete_profile'); ?></span></a>
      <?php endif; 
 ?></p>
                    
                    <p>
      <label for="address"><?php echo $this->lang->line("settings_site_address"); ?><span style="color:red;">*</span></label>
      <textarea name="address" 
	  			class="validate[required] sf" 
				cols="" rows="" 
				alt="<?php echo $this->lang->line('lang_site_settings_site_address');?>" 
				id="address" 
				><?php echo $row_record->address;?></textarea>
    </p>
                    
					<p>
      <label for="city"><?php echo $this->lang->line("settings_site_city"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="city"  
			 class="validate[required]sf" 
			 alt="<?php echo $this->lang->line('lang_site_settings_site_city');?>" 
			 id="city" 
			 value="<?php echo $row_record->city;?>" 
	 />
    </p>
					
					<p>
      <label for="state"><?php echo $this->lang->line("settings_site_state"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="state"  
	         class="validate[required]sf" 
	         alt="<?php echo $this->lang->line('lang_site_settings_site_state');?>" 
	         id="state" 
			 value="<?php echo $row_record->state;?>"  
	 />
    </p>
    <p>
      <label for="occupation"><?php echo $this->lang->line("settings_site_country"); ?></label>
      <?php
	  $sel_country = (set_value('country') != '')?set_value('country'):$row_record->country;
      foreach ($country as $cobj) { $options_country[$cobj->name] =$cobj->name; } ?>
      <?php echo form_dropdown('country', $options_country,$sel_country,"class='validate[required] sf' alt='".$this->lang->line('lang_advertiser_enter_country')."'"); ?>
     
    </p>
					
					<p>
                    	<label for="name"><?php echo $this->lang->line("label_admin_myaccount_mobile"); ?></label>
                        <input type="text"
	   		 name="mobileno"  
	         class="validate[required]sf" 
	         alt="<?php echo $this->lang->line('label_admin_myaccount_mobile_validation');?>" 
	         id="state" 
			 value="<?php echo $row_record->mobileno;?>"  
                    />				
					
					
                </fieldset>
				<br/>
				
				<fieldset>
                    <legend><?php echo $this->lang->line('label_publisher_payment_information');?></legend>
                    	
					<p>
                    	<label for="name"><?php echo $this->lang->line("label_publisher_myaccount_paypal_id"); ?><span style="color:red;">*</span></label>
                              <input type="text"
	   		 name="paypalid"  
	          class="validate[required,funcCall[emailValidateNew]] sf" 
	         alt="<?php echo $this->lang->line('label_publisher_myaccount_paypal_id_validation');?>" 
	         id="state" 
			 value="<?php echo $row_record->paypalid;?>"  
                    />	
                    </p>
					
					<p>
     <?php
$options = array(
    ''              => 'Choose One',
    'Individual'    => 'Individual',
    'Corporation'   => 'Corporation',
    'Partnership'   => 'Partnership'
    
    );
echo form_dropdown('bank_acctype', $options, set_value('bank_acctype', (isset($row_record->bank_acctype)) ? $row_record->bank_acctype: '')) ;
echo form_error('bank_acctype') ;
?>                 	<label for="name"><?php echo $this->lang->line("label_publisher_myaccount_account_type"); ?></label>
						 

                     
                    </p>
					
						<p>
                    	<label for="name"><?php echo $this->lang->line("label_publisher_myaccount_tax"); ?></label>
                              <input type="text"
	   		 name="tax"  
	              	 value="<?php echo $row_record->tax;?>"  
                    />	
                    </p>
					   <?php
	}
	?>
		     <p>
                	   	<button><?php echo $this->lang->line('label_admin_login_submit');?></button>
                	</p>		
                </fieldset>
				
				
            </div>
            
        
        </form>
</div>
<script>
function Checkfiles()
{
var fup = document.getElementById('image_upload');
var image_upload = fup.value;
var ext = image_upload.substring(image_upload.lastIndexOf('.') + 1);
if(ext == "gif" || ext == "GIF" || ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG" || ext == "doc" || ext == "PNG" || ext == "png" || ext == "BMP" || ext == "bmp")
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
function menu_confirm()
		{
			
			jConfirm('<center><?php echo $this->lang->line("settings_site_profileimage_delete_msg"); ?></center>',
			'<?php echo $this->lang->line("settings_site_delete_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url('publisher/myaccount/myaccount_delete_avatar');?>';	
	}				});
					}
</script>
<!--fullpage-->
