<script>
		jQuery(document).ready(function(){
			jQuery( "#tabs" ).tabs();
		});
		
</script>
<script>
function emailValidateNew(field, rules, i, options)
	{
        
		 
		var email = field.val();
      
		var mailadd = email.split("@");
        
		if(mailadd.length>1)
		{
		  
			var finalsplit = mailadd[1].split(".");
			if(finalsplit.length <= 1){
				return '<?php echo $this->lang->line('lang_publisher_option_enter_valid_email'); ?>';
			}
			else{
				if(finalsplit[1]=='' || finalsplit[1]==null){
					return '<?php echo $this->lang->line('lang_publisher_option_enter_valid_email'); ?>';
				}
			}
		}
		else
		{
		
			return '<?php echo $this->lang->line('lang_publisher_option_enter_valid_email'); ?>';
		}

	}
	function checknumber(field, rules, i, options){

          var numericExpression = /^(?:\d*\.\d{1,10}|\d+)$/;
		  
		  	if (!field.val().match(numericExpression)) 
			  {
				 return "<?php echo $this->lang->line('lang_publisher_option_enter_correct_amount'); ?>";		
			  }
			  
			
              
	 }
	function checknumber2(field, rules, i, options){

          var numericExpression = /^[0-9]+(.[0-9]+)?$/;
		  
		  	if (!field.val().match(numericExpression)) 
			  {
				 return "<?php echo $this->lang->line('lang_payment_option_err'); ?>";		
			  }
			  
			
              
	 }
	 function namecheck(field, rules, i, options)
		{
				var keyword		=	field.val();
				var alpha			=	/^[0-9a-zA-Z\s\_]+[\.]?[0-9a-zA-Z\s\_]+$/;
				if(!alpha.test(keyword))
					{
						return  '<?php echo $this->lang->line('lang_publisher_option_invalid_characters_username'); ?>';
						
					}
			}
	function alpha_numeric_check(field, rules, i, options)
		{
				var keyword		=	field.val();
				var alpha			=	/^[a-z0-9]+$/i;
				if(!alpha.test(keyword))
					{
						return  '<?php echo $this->lang->line('lang_publisher_option_alpha_numeric_only'); ?>';
						
					}
			}
	function charlength(field, rules, i, options)
		{
				var keyword		=	field.val();
				if((keyword.length)>4||(keyword.length)<0)
					{
						return  '<?php echo $this->lang->line('lang_publisher_option_alt_amount_check');?>';
						
					}
			}
			function Decimalcheck(field, rules, i, options)
			{
				var number = field.val();
				 var decimal= /^[0-9]+\.[0-9]{2}$/;
				 var str=number.split('.');
					if(typeof(str[1]) != "undefined" && str[1] !== null)
					{
						var len=str[1];
						var len_1=len.length;
						if(len_1 > 2)
						{	
							if ((!decimal.test(number)))
							{
								return '<?php echo "Cannot have more than 2 decimals." ?>'; 
							}
						}
					}
			}
	

	
	
</script>


<h3><?php echo $this->lang->line("lang_publisher_option_page_title");?></h3>
<div style="margin-top:15px;" id="tabs" class="tabs2">
  <ul>
    <li><a href="#tabs1"><strong><?php echo $this->lang->line("lang_publisher_option_by_check_transfer");?></strong></a></li>
    <li><a href="#tabs2"><strong><?php echo $this->lang->line("lang_publisher_option_by_paypal");?></strong></a></li>
	<li><a href="#tabs3"><strong><?php echo $this->lang->line("lang_publisher_option_by_wire_transfer");?></strong></a></li>
  </ul>
   
					<div id="tabs1">
					<script>
					jQuery(document).ready(function(){
						// binds form submission and fields to the validation engine
						jQuery("#cheque_transfer_form").validationEngine();
					});
					</script>
					

					<form id="cheque_transfer_form" action="<?php echo site_url("publisher/payments/cheque_payment_process");?>" method="post">
                
                    <div class="form_default">
					<fieldset>
					<legend><?php echo $this->lang->line('lang_publisher_option_page_legend');?></legend>
                     
					<?php if($this->session->flashdata('payment_success') != '')
					{?>
						<div class="notification msgsuccess"><a class="close"></a>
						<p><?php echo $this->session->flashdata('payment_info');?></p>
						</div>
					<?php 
					}?>
					<?php if($this->session->flashdata('payment_failure') != '')
					{?>
						<div class="notification msgerror"><a class="close"></a>
						<p><?php echo $this->session->flashdata('payment_failure');?></p>
						</div>
					<?php 
					}?>
					<?php if ($this->session->userdata('payment_error1') != '')
					{
						?><div class="notification msgerror"><a class="close"></a>
						<?php echo $this->session->userdata('payment_error1');$this->session->unset_userdata('payment_error1');?>
						</div><?php
					 }?>      
                            <p>
                                <label for="name"><?php echo $this->lang->line('lang_publisher_option_name');?></label>
                                <input type="text" name="c_name"  id="c_name" class="validate[required,funcCall[namecheck] sf" value="<?php echo form_text((set_value('c_name') != '')?set_value('c_name'):ucwords($pub_data->username));?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_name');?>"/>
                            </p>
                            
                            <p>
                                <label for="email"><?php echo $this->lang->line('lang_publisher_option_email');?></label>
                                <input type="text" name="c_email"  id="c_email" class="validate[required,funcCall[emailValidateNew]] sf" value="<?php echo form_text((set_value('c_email') != '')?set_value('c_email'):$pub_data->email);?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_email_id');?>"/>
                            </p>
                            
                            <p>
                                <label for="acc_no"><?php echo $this->lang->line('lang_publisher_option_bank_acc_no');?></label>
                                <input type="text" name="c_acc_no"  id="c_acc_no" class="validate[required,funcCall[alpha_numeric_check]] sf" value="<?php echo form_text((set_value('c_acc_no') != '')?set_value('c_acc_no'):$pub_data->bank_account_no);?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_account_number');?>"/>
                            </p>
                              
                            <p>
                                <label for="amount"><?php echo $this->lang->line('lang_publisher_option_amount');?></label>
                                <input type="text" name="c_amount"  id="c_amount" class="validate[required,funcCall[checknumber],funcCall[checknumber2],funcCall[Decimalcheck]] sf" size="4" value="<?php echo form_text(set_value('c_amount'));?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_amount');?>"/>
                            </p>
                            <input type="hidden" name="type"  id="type" value="<?php echo "cheque";?>"/>
							
                            <p>
                                <button><?php echo $this->lang->line('lang_publisher_option_send');?></button>
				<button type="button" style="margin-left:10px;" onclick="location.href='<?php echo site_url("publisher/payments");?>'" ><?php echo $this->lang->line('label_cancel'); ?></button>
                            </p>
							</fieldset>
        
                    </div><!--form-->
                	</form>
					</div><!--tab-1-->
					<div id="tabs2">
					<script>
					jQuery(document).ready(function(){
						// binds form submission and fields to the validation engine
						jQuery("#paypal_form").validationEngine();
					});
					</script> 	
					<form id="paypal_form" action="<?php echo site_url("publisher/payments/paypal_payment_process");?>" method="post">
                
                    <div class="form_default">
					<fieldset>
					<legend><?php echo $this->lang->line('lang_publisher_option_page_legend');?></legend>
                    <?php if($this->session->flashdata('payment_success') != '')
					{?>
						<div class="notification msgsuccess"><a class="close"></a>
						<p><?php echo $this->session->flashdata('payment_success');?></p>
						</div>
					<?php 
					}?>
					<?php if($this->session->flashdata('payment_failure') != '')
					{?>
						<div class="notification msgerror"><a class="close"></a>
						<p><?php echo $this->session->flashdata('payment_failure');?></p>
						</div>
					<?php 
					}?>
					
					<?php if ($this->session->userdata('payment_error2') != '')
					{
						?><div class="notification msgerror"><a class="close"></a>
						<?php echo $this->session->userdata('payment_error2');$this->session->unset_userdata('payment_error2');?>
						</div><?php
					 }?>         
                            <p>
                                <label for="name"><?php echo $this->lang->line('lang_publisher_option_name');?></label>
                                <input type="text" name="p_name"  id="p_name" class="validate[required,funcCall[namecheck]] sf" value="<?php echo form_text((set_value('p_name') != '')?set_value('p_name'):ucwords($pub_data->username));?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_name');?>"/>
                            </p>

                            
                            <p>
                                <label for="email"><?php echo $this->lang->line('lang_publisher_option_paypal_email');?></label>
                                <input type="text" name="p_email"  id="p_email" class="validate[required,funcCall[emailValidateNew]] sf" value="<?php echo form_text((set_value('p_email') != '')?set_value('p_email'):$pub_data->paypalid);?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_paypal_email_id');?>"/>
                            </p>
                           
                            
                            <p>
                                <label for="amount"><?php echo $this->lang->line('lang_publisher_option_amount');?></label>
                                <input type="text" name="p_amount"  id="p_amount" class="validate[required,funcCall[Decimalcheck],funcCall[checknumber],funcCall[checknumber2]] sf"  size="4" value="<?php echo form_text(set_value('p_amount'));?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_amount');?>"/>
                            </p>
                           
						    <input type="hidden" name="type"  id="type" value="<?php echo "paypal";?>"/>
                            <p>
                                <button><?php echo $this->lang->line('lang_publisher_option_send');?></button>
				<button type="button" style="margin-left:10px;" onclick="location.href='<?php echo site_url("publisher/payments");?>'" ><?php echo $this->lang->line('label_cancel'); ?></button>
                            </p>
							</fieldset>
        
                    </div><!--form-->
                	</form>	</div><!--tab-2-->

					<div id="tabs3">
					<script>
					jQuery(document).ready(function(){
						// binds form submission and fields to the validation engine
						jQuery("#wire_transfer_form").validationEngine();
					});
					</script>
					<form id="wire_transfer_form" action="<?php echo site_url("publisher/payments/wire_payment_process");?>" method="post">
                
                    <div class="form_default">
					<fieldset>
					<legend><?php echo $this->lang->line('lang_publisher_option_page_legend');?></legend>
                     <?php if($this->session->flashdata('payment_success') != '')
					{?>
						<div class="notification msgsuccess"><a class="close"></a>
						<p><?php echo $this->session->flashdata('payment_success');?></p>
						</div>
					<?php 
					}?>
					<?php if($this->session->flashdata('payment_failure') != '')
					{?>
						<div class="notification msgerror"><a class="close"></a>
						<p><?php echo $this->session->flashdata('payment_failure');?></p>
						</div>
					<?php 
					}?>
					
					<?php if ($this->session->userdata('payment_error3') != '')
					{
						?><div class="notification msgerror"><a class="close"></a>
						<?php echo $this->session->userdata('payment_error3');
								$this->session->unset_userdata('payment_error3');	?></div>
						<?php
					 }?>        
                            <p>
                                <label for="name"><?php echo $this->lang->line('lang_publisher_option_name');?></label>
                                <input type="text" name="w_name"  id="w_name" class="validate[required,funcCall[namecheck]] sf" value="<?php echo form_text((set_value('w_name') != '')?set_value('w_name'):ucwords($pub_data->username));?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_name');?>"/>
                            </p>
                            
                            <p>
                                <label for="acc_no"><?php echo $this->lang->line('lang_publisher_option_acc_no');?></label>
                                <input type="text" name="w_acc_no"  id="w_acc_no" class="validate[required,funcCall[alpha_numeric_check]] sf" value="<?php echo form_text((set_value('w_acc_no') != '')?set_value('w_acc_no'):$pub_data->wire_account_no);?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_account_number');?>"/>
                            </p>
							
							<p>
                                <label for="id_no"><?php echo $this->lang->line('lang_publisher_option_id_no');?></label>
                                <input type="text" name="w_id_no"  id="w_id_no" class="validate[required,funcCall[alpha_numeric_check]] sf" size="4" value="<?php echo form_text(set_value('w_id_no'));?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_id_no');?>"/>
                            </p>
                            
							<p>
                                <label for="email"><?php echo $this->lang->line('lang_publisher_option_email');?></label>
                                <input type="text" name="w_email"  id="w_email" class="validate[required,funcCall[emailValidateNew]] sf" value="<?php echo form_text((set_value('w_email') != '')?set_value('w_email'):$pub_data->email);?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_email_id');?>"/>
                            </p>
							
                            
                           <p>
                                <label for="amount"><?php echo $this->lang->line('lang_publisher_option_amount');?></label>
                                <input type="text" name="w_amount"  id="w_amount" class="validate[required,funcCall[Decimalcheck],funcCall[checknumber],funcCall[checknumber2]] sf" size="4" value="<?php echo form_text(set_value('w_amount'));?>" alt="<?php echo $this->lang->line('lang_publisher_option_alt_amount');?>"/>
                            </p>
                            
							<input type="hidden" name="type"  id="type" value="<?php echo "wire";?>"/>
							
                            <p>
                                <button><?php echo $this->lang->line('lang_publisher_option_send');?></button>
				<button type="button" style="margin-left:10px;" onclick="location.href='<?php echo site_url("publisher/payments");?>'" ><?php echo $this->lang->line('label_cancel'); ?></button>
                            </p>
							
							<strong><?php echo $this->lang->line('lang_publisher_option_id_no');?></strong> <?php echo $this->lang->line('lang_publisher_option_id_info');?>
							
							</fieldset>
        					
                    </div><!--form-->
                	</form>
					</div><!--tab-3-->
</div>
