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
						   document.getElementById('category').style.display = "block";
					}
			
		else if (accounttype == "ADVERTISER/TRAFFICKER") {
            document.getElementById('website').style.display = "block";
			 document.getElementById('category').style.display = "block";
        } 
		 else {
            document.getElementById('website').style.display = "none";
			document.getElementById('category').style.display = "none";
        }

	 }

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
	  function mobileValidateNew(field, rules, i, options){


			var y = field.val();
                        
                        var numbers = /^[0-9]+$/;
            if(!(field.val().match(numbers)))  
          {  
          return '<?php echo "Please Enter the Numbers only" ?>';  
           }   

                            if (y.length<10 || y.length>15)
           {
               
                 return '<?php echo "Mobile No. should be Minimum 10 digit and Max 15 digit" ?>';
           }


	 }
             
        function zipValidateNew(field, rules, i, options){


			var y = field.val();
                        
                        var numbers = /^[0-9]+$/;
            if(!(field.val().match(numbers)))  
          {  
          return '<?php echo "Please Enter the Numbers only" ?>';  
           }   

          
	 }

	function isValidURL(field, rules, i, options){
		var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

	
			if(!RegExp.test(field.val())){
			return '<?php echo $this->lang->line('lang_site_settings_enter_valid_site_url'); ?>';
		}
	} 
	

</script>
    <div class="left">	
    <h1 class="pageTitle"><?php $this->lang->line('registration');?></h1>
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
        <form id="registration" action="<?php echo site_url('site/registration_process'); ?>" method="post">
        	<div class="form_default">
                <fieldset>
                     <?php echo validation_errors(); ?> 	
					<p>
                    <label for="name"><?php echo $this->lang->line("label_publisher_myaccount_account_type"); ?> <span style="color:red;">*</span> </label>
                    <?php 
			
                            $sel_accountype = (set_value('account_type') != '')?set_value('account_type'):$acc_type;
		
							$options['']	=	$this->lang->line("registration_choose_account_type");
							$options['ADVERTISER'] =  $this->lang->line("registration_advertiser");
						   	$options['TRAFFICKER'] =  $this->lang->line("registration_publisher");
							//$options['ADVERTISER/TRAFFICKER'] =$this->lang->line("registration_both_account_type"); 
							$js = 'class="validate[required,funcCall[accounttypechk]] sf" alt="'.$this->lang->line("registration_accounttype_validation").'"';
							echo form_dropdown('account_type',$options,$sel_accountype,$js);         ?>        		
                      
                    </p>
					
					
                    <p>
                    	<label for="name"><?php echo $this->lang->line("registration_name"); ?> <span style="color:red;">*</span> </label>
                        <input type="text" name="name"  id="name" class="validate[required,custom[onlyLetterSp]] sf" 
			 alt="<?php echo $this->lang->line("registration_name_validation");?>" 
			 value="<?php echo  form_text(set_value('name')); ?>"
			 />
                    </p>
						<p>
					<label for="name"><?php echo $this->lang->line('label_admin_myaccount_username');?><span style="color:red;">*</span></label>
                    	 <input type="text" 
	         name="username" 
			 class="validate[required,custom[onlyLetterSp]] sf" 
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
        <p style="display:none" id="category">            
		<?php
			$sel_category	='';
			$options	=array();
			//$options["0"] =$this->lang->line('label_select_category');
			if(!empty($category_list)){
				foreach ($category_list as $obj) { $options[$obj->category_id] =$obj->category_name; } 
			}
		?>
	
		
		   <label for="category"><?php echo $this->lang->line('label_campaign_category'); ?></label>
		   <?php echo form_multiselect('category[]', $options, $sel_category, "class='sf' alt='".$this->lang->line('label_select_campaign_category')."'"); ?>
	   </p>
	  
	  <p>
      <label for="address"><?php echo $this->lang->line("settings_site_address"); ?><span style="color:red;">*</span></label>
      <textarea name="address" class="validate[required] sf" alt="<?php echo $this->lang->line('lang_site_settings_site_address');?>" 
		id="address" ><?php echo  form_text(set_value('address')); ?></textarea>
			
    </p>
                    
                    
						<p>
      <label for="city"><?php echo $this->lang->line("settings_site_city"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="city"  
			 class="validate[required,custom[onlyLetterSp]]sf" 
			 alt="<?php echo $this->lang->line('lang_site_settings_site_city');?>" 
			 id="city" 
			 value="<?php echo  form_text(set_value('city')); ?>"
	 />
    </p>
					
					<p>
      <label for="state"><?php echo $this->lang->line("settings_site_state"); ?><span style="color:red;">*</span></label>
      <input type="text"
	   		 name="state"  
	         class="validate[required,custom[onlyLetterSp]]sf" 
	         alt="<?php echo $this->lang->line('lang_site_settings_site_state');?>" 
	         id="state"
			  value="<?php echo  form_text(set_value('state')); ?>" 
			
	 />
    </p>
					
					<p>
                    	<label for="name"><?php echo $this->lang->line("registration_country"); ?><span style="color:red;">*</span></label>
                      <?php
	   $sel_continent = form_text(set_value('country'));
							$options1['']	=	'Select country';
							foreach($country as $row): 
							$options1[$row->country_name] =  $row->country_name;
							endforeach;
							$js = 'class="validate[required] " alt="'.$this->lang->line("registration_country_validation").'"';
							echo form_dropdown('country',$options1,$sel_continent,$js);
	     ?>
       
                    </p>
					
				<p>
                    	<label for="name"><?php echo $this->lang->line("label_admin_myaccount_mobile"); ?><span style="color:red;">*</span></label>			<input type="text"  name="mobile"  
	          class="validate[required,funcCall[mobileValidateNew]] sf"
	         alt="<?php echo $this->lang->line('label_admin_myaccount_mobile_validation');?>" 
	         id="mobile" 
			  value="<?php echo  form_text(set_value('mobile')); ?>" 
                    />	
					<p>
                    	<label for="name"><?php echo $this->lang->line("registration_zipcode"); ?><span style="color:red;">*</span></label>
                        <input type="text" name="zip"  id="zip" class=class="validate[required,funcCall[zipValidateNew]] sf"  alt="<?php echo "Please Enter the Zip code";?>"  value="<?php echo  form_text(set_value('zip')); ?>"  />
                    </p>
					<p>
                   	<p>
                	   	<button><?php echo $this->lang->line("label_submit"); ?></button>
                	</p>
				</fieldset>
				
            </div><!--form-->
            
        
        </form>
        </div>
        <br clear="all" />
<?php if($acc_type =='TRAFFICKER') { ?>
<script type="text/javascript">document.getElementById('website').style.display = "block";</script>
<script type="text/javascript">document.getElementById('category').style.display = "block";</script>
<?php } else if($acc_type =='ADVERTISER') { ?>
<script type="text/javascript">document.getElementById('website').style.display = "none";</script>
<script type="text/javascript">document.getElementById('category').style.display = "none";</script>
<?php } ?>
