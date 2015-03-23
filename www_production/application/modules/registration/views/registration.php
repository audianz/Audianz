
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>DreamAds | Administrator | Dashboard</title>
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/style1.css" />
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/custom_style.css" />

<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/ie7.css"/>
<![endif]-->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.colorbox-min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/general.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/users.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/gallery.js"></script>
</head>

<script>

	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#registration").validationEngine();
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
	 	function accounttypechk(field, rules, i, options){
 
           var  accounttype = field.val();
		
			if (accounttype == "TRAFFICKER") {
					
						  document.getElementById('website').style.display = "block";
					}
			
		else if (accounttype == "ADVERTISER/TRAFFICKER") {
            document.getElementById('website').style.display = "block";
        } 
		 else {
            document.getElementById('website').style.display = "none";
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
<body style="background:#c7d6ec;">
<div class="header">
	
    <!--logo-->
	<a href=""><img src="<?php echo base_url(); ?>assets/images/logo2.png" alt="Logo" /></a>
    
    <div class="tabmenu">
    	<ul>
        	<li><a href="dashboard.html" class="dashboard"><span>Dashboard</span></a></li>
            <li><a href="elements.html" class="elements"><span>Elements</span></a>
            	<ul class="subnav">
                	<li><a href=""><span>Sub Menu One</span></a></li>
                    <li><a href=""><span>Sub Menu Two</span></a></li>
                    <li><a href=""><span>Sub Menu Three</span></a></li>
                </ul>
            </li>
            <li><a href="reports.html" class="reports"><span>Reports</span></a></li>
            <li><a href="users.html" class="users"><span>Users</span></a></li>
        </ul>
    </div><!--tabmenu-->
    
    
</div><!--header-->

<div class="centerbody">

  
    <br clear="all" />
    <h1 class="pageTitle">Registration</h1>
	<?php  
		if($this->session->flashdata('message') !=''):
	?>
		 <div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					 <?php 
								
								echo $this->session->flashdata('message');
								
					?>
				</p>
			</div>		
	<?php 
		endif;
	?>
      <?php echo validation_errors(); ?> 	
        <form id="registration" action=" registration/registration_process" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line("registration_Information"); ?></legend>
                     
								
					<p>
                    	<label for="name"><?php echo $this->lang->line("registration_choose_account_type"); ?> </label>
                    <?php 
                            $sel_accountype = form_text(set_value('account_type'));
							$options['']	=	$this->lang->line("registration_choose_account_type");
							$options['ADVERTISER'] =  $this->lang->line("registration_advertiser");
						   	$options['TRAFFICKER'] =  $this->lang->line("registration_publisher");
							$options['ADVERTISER/TRAFFICKER'] =$this->lang->line("registration_both_account_type"); 
							$js = 'class="validate[required,funcCall[accounttypechk]] sf" alt="'.$this->lang->line("registration_accounttype_validation").'"';
							echo form_dropdown('account_type',$options,$sel_accountype,$js);         ?>        		
                      
                    </p>
					
					
                    <p>
                    	<label for="name"><?php echo $this->lang->line("registration_name"); ?> <span style="color:red;">*</span> </label>
                        <input type="text" name="name"  id="name" class="validate[required] sf" 
			 alt="<?php echo $this->lang->line("registration_name_validation");?>" 
			 value="<?php echo  form_text(set_value('name')); ?>"
			 />
                    </p>
						<p>
					<label for="name"><?php echo $this->lang->line('label_admin_myaccount_username');?><span style="color:red;">*</span></label>
                    	 <input type="text" 
	         name="username" 
			 class="validate[required] sf" 
			 alt="<?php echo $this->lang->line('label_admin_myaccount_username_validation');?>" 
			 id="email" 
			  value="<?php echo  form_text(set_value('username')); ?>"
	 />
                    </p>			
						<p>
                    	<label for="name"><?php echo $this->lang->line("registration_password"); ?><span style="color:red;">*</span></label>
                          <input type="password"  name="password" autocomplete="off"  id="password" class="validate[required] sf"  
						alt="<?php echo $this->lang->line('registration_password_validation');?>"
						 oncopy ="retrun false" onpaste="return false" oncut="return false"
						 
						 />
                    </p>
                     <p>
                    	<label for="name"><?php echo $this->lang->line("registration_confirm_password"); ?><span style="color:red;">*</span></label>
                         <input type="password"  name="cfmpwd" s id="cfmpwd"  autocomplete="off"class="validate[required,funcCall[checkCPassword]] sf"  
						alt="<?php echo $this->lang->line('registration_confirm_password_validation')?>"
						 oncopy ="retrun false" onpaste="return false" oncut="return false"
						 
						 />
                    </p>
                         <p>
     		 <label for="email"><?php echo $this->lang->line("settings_site_email"); ?><span style="color:red;">*</span></label>
      <input type="text" 
	         name="email" 
			 class="validate[required,funcCall[emailValidateNew]] sf" 
			 alt="<?php echo $this->lang->line('lang_advertiser_enter_email');?>" 
			 id="email" 
			 value="<?php echo  form_text(set_value('email')); ?>"
	 />
    </p>
                       <p style="display:none" id="website">
                    	 <label for="name"><?php echo $this->lang->line("registration_website"); ?><span style="color:red;">*</span></label>
      <input 
	  	type="text" 
		name="website"  
		 class="validate[required,funcCall[isValidURL]] sf"  
		alt="<?php echo $this->lang->line('registration_website_validation'); ?>"   
		id="site_title" 
		 value="<?php echo  form_text(set_value('website')); ?>"
		 />
		
                    </p>
                    
                    <p>
      <label for="address"><?php echo $this->lang->line("settings_site_address"); ?><span style="color:red;">*</span></label>
      <textarea name="address" 
	  			class="validate[required] sf" 
				cols="" rows="" 
				alt="<?php echo $this->lang->line('lang_site_settings_site_address');?>" 
				id="address" >
					<?php echo  form_text(set_value('address')); ?>
				</textarea>
			
    </p>
                    
                    
						<p>
      <label for="city"><?php echo $this->lang->line("settings_site_city"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="city"  
			 class="validate[required]sf" 
			 alt="<?php echo $this->lang->line('lang_site_settings_site_city');?>" 
			 id="city" 
			 value="<?php echo  form_text(set_value('city')); ?>"
	 />
    </p>
					
					<p>
      <label for="state"><?php echo $this->lang->line("settings_site_state"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="state"  
	         class="validate[required]sf" 
	         alt="<?php echo $this->lang->line('lang_site_settings_site_state');?>" 
	         id="state"
			  value="<?php echo  form_text(set_value('state')); ?>" 
			
	 />
    </p>
					
					<p>
                    	<label for="name"><?php echo $this->lang->line("registration_country"); ?><span style="color:red;">*</span></label>
                      <?php
	   $sel_continent = form_text(set_value('country'));
							$options['']	=	'Select country';
							foreach($country as $row): 
							$options[$row->country_name.'||'.$row->country_name] =  $row->country_name;
							endforeach;
							$js = 'class="validate[required] " alt="'.$this->lang->line("registration_country_validation").'"';
							echo form_dropdown('country',$options,$sel_continent,$js);
	     ?>
       
                    </p>
					
				<p>
                    	<label for="name"><?php echo $this->lang->line("label_admin_myaccount_mobile"); ?></label>
                        <input type="text"
	   		 name="mobile"  
	         class="validate[required]sf" 
	         alt="<?php echo $this->lang->line('label_admin_myaccount_mobile_validation');?>" 
	         id="mobile" 
			  value="<?php echo  form_text(set_value('mobile')); ?>" 
                    />	
					<p>
                    	<label for="name"><?php echo $this->lang->line("registration_zipcode"); ?></label>
                        <input type="text" name="zip"  id="zip" class="sf"  value="<?php echo  form_text(set_value('zip')); ?>"  />
                    </p>
					<p>
                   	<p>
                	   	<button><?php echo $this->lang->line("label_submit"); ?></button>
                	</p>
				</fieldset>
				
            </div><!--form-->
            
        
        </form>
        

    
    <br clear="all" />

</div>
<br />
<div class="footer footer_float">
	<div class="footerinner">
    	<?php echo $this->lang->line('label_copy_rights'); ?>
    </div><!-- footerinner -->
</div><!-- footer -->

</body>
</html>
