<!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <h1 class="pageTitle"><?php echo $this->lang->line('notifications_title'); ?></h1>
        
     
	<?php if($this->session->flashdata('message') !=''): ?>
		 <div class="notification msgsuccess">
				<a class="close"></a>
				<p><?php echo $this->session->flashdata('message'); ?></p>
		  </div>
	<?php endif; ?>
	<?php if($this->session->flashdata('errmessage') !=''): ?>
		 <div class="notification msgerror">
				<a class="close"></a>
				<p><?php echo $this->session->flashdata('errmessage'); ?></p>
		  </div>
	<?php endif; ?>
	<?php if($this->session->flashdata('errmessage2') !=''): ?>
		 <div class="notification msgerror">
				<a class="close"></a>
				<p><?php echo $this->session->flashdata('errmessage2'); ?></p>
		  </div>
	<?php endif; ?>

    <form id="frmAdvertiserNotifications" action="<?php echo site_url('advertiser/advertiser_notifications/notification_process'); ?>" method="post">        
        <div class="form_default">
        	<fieldset><legend><?php echo $this->lang->line('label_notifications'); ?></legend>
			<?php if($notify !=NULL) : ?>
			<?php foreach($notify as $obj) : ?>
			<?php
					$accbalance		=$obj->accbalance;
					$acc_bal		=explode(",", $accbalance);
					
					$budbalance		=$obj->budbalance;
					$bud_bal		=explode(",", $budbalance);
			?>
			<?php if($acc_bal[0] =='no') { ?>
			<p>
			<h1 style="font-size:12px;text-align:left;"><?php echo $this->lang->line("label_advertiser_caption_balance_notify"); ?></h1>
            <table cellpadding="0" cellspacing="0" width="80%" align="center">
				<tr>
				<td><input type="radio" name="notify" id="notify" value="no" checked="checked" style="margin-right:10px;"><?php echo $this->lang->line("label_no_notify"); ?></td>
				</tr>
				<tr>
				<td><input type="radio" name="notify" id="notify_acc" value="yes" style="margin-right:10px;"><?php echo $this->lang->line("label_advertiser_need_total_notify"); ?>:<span style="color:#FF0000; padding-left:10px;"><input type="text" name="accbalance" id="accbalance" size="5" disabled="disabled" class="validate[required,funcCall[optionValidatetot] sf" /></span></td>
				</tr>
            </table>
			</p>
			<?php } else { ?>
		    <p>
			<h1 style="font-size:12px;text-align:left;"><?php echo $this->lang->line("label_advertiser_caption_balance_notify"); ?></h1>
            <table cellpadding="0" cellspacing="0" width="80%" align="center">
				<tr>
				<td><input type="radio" name="notify" id="notify" value="no" style="margin-right:10px;"><?php echo $this->lang->line("label_no_notify"); ?></td>
				</tr>
				<tr>
				<td><input type="radio" name="notify" id="notify_acc" value="yes" checked="checked" style="margin-right:10px;"><?php echo $this->lang->line("label_advertiser_need_total_notify"); ?>:<span style="color:#FF0000; padding-left:10px;"><input type="text" name="accbalance" id="accbalance" size="5" value="<?php echo $acc_bal[1]; ?>" class="validate[required,funcCall[optionValidatetot],funcCall[Numericcheck],funcCall[Nullcheck]] sf" /></span></td>
				</tr>
            </table>
			</p>
			 <?php } ?>
			 
			 
			<?php if($bud_bal[0] =='no') { ?>
			<p>
			<h1 style="font-size:12px;text-align:left;" ><?php echo $this->lang->line("label_advertiser_caption_budget_notify"); ?></h1>
            <table cellpadding="0" cellspacing="0" width="80%" align="center">
				<tr>
				<td><input type="radio" name="notify1" id="notify1" value="no" checked="checked" style="margin-right:10px;"><?php echo $this->lang->line("label_no_notify"); ?></td>
				</tr>
				<tr>
				<td><input type="radio" name="notify1" id="notify1_daily" value="yes" style="margin-right:10px;"><?php echo $this->lang->line("label_advertiser_need_daily_notify"); ?>:<span style="color:#FF0000; padding-left:10px;"><input type="text" name="dailyvalue" id="dailyvalue" size="5" disabled="disabled" class="validate[required,funcCall[optionValidatedly]] sf" /></span></td>
				</tr>
            </table>
			</p>
			<?php } else { ?>
		    <p>
			<h1 style="font-size:12px;text-align:left;" ><?php echo $this->lang->line("label_advertiser_caption_budget_notify"); ?></h1>
            <table cellpadding="0" cellspacing="0" width="80%" align="center">
				<tr>
				<td><input type="radio" name="notify1" id="notify1" value="no" style="margin-right:10px;"><?php echo $this->lang->line("label_no_notify"); ?></td>
				</tr>
				<tr>
				<td><input type="radio" name="notify1" id="notify1_daily" value="yes" checked="checked" style="margin-right:10px;"><?php echo $this->lang->line("label_advertiser_need_daily_notify"); ?>:<span style="color:#FF0000; padding-left:10px;"><input type="text" name="dailyvalue" id="dailyvalue" size="5" value="<?php echo $bud_bal[1]; ?>" class="validate[required,funcCall[optionValidatedly],funcCall[Numericcheck],funcCall[Nullcheck]] sf" /></span></td>
				</tr>
            </table>
			</p>
			 <?php } ?>
			 <?php endforeach; ?>
			 <?php else : ?>
			 
			<p>
			<h1 style="font-size:12px;text-align:left;"><?php echo $this->lang->line("label_advertiser_caption_balance_notify"); ?></h1>
            <table cellpadding="0" cellspacing="0" width="80%" align="center">
				<tr>
				<td><input type="radio" name="notify" id="notify" value="no" checked="checked" style="margin-right:10px;"><?php echo $this->lang->line("label_no_notify"); ?></td>
				</tr>
				<tr>
				<td><input type="radio" name="notify" id="notify_acc" value="yes" style="margin-right:10px;"><?php echo $this->lang->line("label_advertiser_need_total_notify"); ?>:<span style="color:#FF0000; padding-left:10px;"><input type="text" name="accbalance" id="accbalance" size="5" disabled="disabled" class="validate[required,funcCall[optionValidatetot]] sf" /></span></td>
				</tr>
            </table>
			</p>
			 
			<p>
			<h1 style="font-size:12px;text-align:left;" ><?php echo $this->lang->line("label_advertiser_caption_budget_notify"); ?></h1>
            <table cellpadding="0" cellspacing="0" width="80%" align="center">
				<tr>
				<td><input type="radio" name="notify1" id="notify1" value="no" checked="checked" style="margin-right:10px;"><?php echo $this->lang->line("label_no_notify"); ?></td>
				</tr>
				<tr>
				<td><input type="radio" name="notify1" id="notify1_daily" value="yes" style="margin-right:10px;"><?php echo $this->lang->line("label_advertiser_need_daily_notify"); ?>:<span style="color:#FF0000; padding-left:10px;"><input type="text" name="dailyvalue" id="dailyvalue" size="5" disabled="disabled" class="validate[required,funcCall[optionValidatedly]] sf" /></span></td>
				</tr>
            </table>
			</p>
			 
			<?php endif; ?>
			
			<p>
				<button><?php echo $this->lang->line("label_update"); ?></button>
				<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
			</p>

			 <br />
			 </fieldset>
			 </div>
			</form>

		<script type="text/javascript">
			function goToList()
			{
				document.location.href='<?php echo site_url('advertiser'); ?>';
			}

			jQuery(document).ready(function(){
				// binds form submission and fields to the validation engine
				jQuery("#frmAdvertiserNotifications").validationEngine();
			
				jQuery('#notify_acc').click(function(event) { jQuery('#accbalance').removeAttr("disabled"); });
				
				jQuery('#notify').click(function(event) { jQuery('#accbalance').attr('disabled','diaabled'); });

				jQuery('#notify1_daily').click(function(event) { jQuery('#dailyvalue').removeAttr("disabled"); });
				
				jQuery('#notify1').click(function(event) { jQuery('#dailyvalue').attr('disabled','diaabled'); });
			});
			
		  function optionValidatetot(field, rules, i, options)
		  {
			if(jQuery('#notify_acc').attr('checked') =='checked' && field.val() == "")  
			{
				return "<?php echo $this->lang->line("label_advertiser_required_notify"); ?>";
			}
		  }

		  function optionValidatedly(field, rules, i, options)
		  {
			if(jQuery('#notify1_daily').attr('checked') =='checked' && field.val() == "") 
			{
				return "<?php echo $this->lang->line("label_advertiser_required_notify"); ?>";
			}
		  }
		 
		function Numericcheck(field, rules, i, options)
		{
			var reg 		= /^[0-9]+$/;
			var value	=		field.val();
			if(!reg.test(value))
				{
					return "<?php echo $this->lang->line('label_err_balance'); ?>";
				}
			
			}
			function Nullcheck(field, rules, i, options)
		{
			
			var value	=		field.val();
			if (typeof value === 'undefined' || value === '0')
				{
					return "<?php echo $this->lang->line('label_err_null_balance'); ?>";
				}
			
			}
			
			
		</script>

