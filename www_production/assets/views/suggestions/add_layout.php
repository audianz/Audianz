
<form id="frmSuggestionAdd" action="" method="post">

  <div class="form_default">
    <h4><?php echo $this->lang->line('label_suggestions_add_title'); ?></h4>
	<p id="response" align="center"></p>
    <p>
      <label for="sender"><?php echo $this->lang->line('label_suggestions_reply_sender'); ?></label>
      <input type="text" readonly="readonly" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_senderrequired'); ?>" name="sender" id="sender" value="<?php echo $this->session->userdata('session_advertiser_email'); ?>"/>
    </p>
    <p>
      <label for="sender"><?php echo $this->lang->line('label_suggestions_reply_reciever'); ?></label>
      <input type="text" name="reciever" readonly="readonly" id="reciever" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_recieverrequired'); ?>" value="<?php echo $admin_email; ?>"/>
    </p>
    <p>
      <label for="subject"><?php echo $this->lang->line('label_suggestions_reply_subject'); ?></label>
      <input type="text" name="subject" id="subject" value="" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_subjectrequired'); ?>"/>
    </p>
    <p>
      <label for="content"><?php echo $this->lang->line('label_suggestions_reply_message'); ?></label>
      <textarea name="content" id="content" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_contentrequired'); ?>"></textarea>
    </p>
	
	<p>
	 <button type="button" id="submit"><?php echo $this->lang->line('label_submit'); ?></button>
	 <button type="button" id="close" style="margin-left:10px;"><?php echo $this->lang->line('label_cancel'); ?></button>
	</p>

  </div>

<input type="hidden" name="recieverid" value="2" />
<input type="hidden" name="recievertype" value="MANAGER" />
<input type="hidden" name="suggestionid" value="0" />

</form>

<script type="text/javascript">
    jQuery(document).ready(function(){
	
		// binds form submission and fields to the validation engine
		jQuery("#close").click(function(){
			jQuery.colorbox.close();
			location.reload();
		});
	
		jQuery('#submit').click(function() { jQuery('#frmSuggestionAdd').submit(); });
		
		jQuery("#frmSuggestionAdd").validationEngine();
		jQuery("#frmSuggestionAdd").submit(function(event){
	
		//event.preventDefault();
		if(jQuery("#content").val() !='' && jQuery("#subject").val() !='' )
		{
		   jQuery.ajax({
				type: 'POST',
				url:  '<?php echo site_url('advertiser/suggestions/addprocess'); ?>',
				data: jQuery('#frmSuggestionAdd').serialize()+"&content="+escape(jQuery("#content").val()),
				success: function(response) 
				{
				   if(response ==1) { jQuery('#response').css('color', 'green'); jQuery('#response').html('<?php echo $this->lang->line('label_suggestions_reply_success'); ?>'); }
				   else { jQuery('#response').css('color', 'red'); jQuery('#response').html('<?php echo $this->lang->line('label_suggestions_reply_unsuccess'); ?>'); } 
							jQuery("#submit").attr('disabled','disabled');
				   }
		  })
		}
	});
	
	});
</script>
