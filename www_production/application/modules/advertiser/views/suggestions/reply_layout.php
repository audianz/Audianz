<?php if(count($rs) > 0 && $rs !=''): ?>
<?php foreach($rs as $row) : ?>

<form id="frmSuggestionReply" action="" method="post">

  <div class="form_default">
    <h4><?php echo $this->lang->line('label_suggestions_reply_title'); ?></h4>
	<p id="response" align="center"></p>
    <p>
      <label for="sender"><?php echo $this->lang->line('label_suggestions_reply_sender'); ?></label>
      <input type="text" readonly="readonly" name="sender" id="sender" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_senderrequired'); ?>" value="<?php echo $this->session->userdata('session_advertiser_email'); ?>"/>
    </p>
    <p>
      <label for="sender"><?php echo $this->lang->line('label_suggestions_reply_reciever'); ?></label>
      <input type="text" readonly="readonly" name="reciever" id="reciever" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_recieverrequired'); ?>" value="<?php echo $admin_email; ?>"/>
    </p>
    <p>
      <label for="subject"><?php echo $this->lang->line('label_suggestions_reply_subject'); ?></label>
      <input type="text" name="subject" readonly="readonly" id="subject" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_subjectrequired'); ?>" value="<?php echo "Re: ".$row->subject; ?>"/>
    </p>
    <p>
      <label for="content"><?php echo $this->lang->line('label_suggestions_reply_message'); ?></label>
      <textarea name="content" id="content" class="validate[required] sf" alt="<?php echo $this->lang->line('label_suggestions_contentrequired'); ?>"><?php echo $row->content; ?></textarea>
    </p>
	
	<p>
	 <button type="button" id="submit"><?php echo $this->lang->line('label_submit'); ?></button>
	 <button type="button" id="close" style="margin-left:10px;"><?php echo $this->lang->line('label_cancel'); ?></button>
	</p>

  </div>

<input type="hidden" name="recieverid" value="<?php echo $row->suggestion_sender; ?>" />
<input type="hidden" name="recievertype" value="<?php echo $row->type; ?>" />
<input type="hidden" name="suggestionid" value="<?php echo $row->suggestion_id; ?>" />

</form>

<?php endforeach; else : ?>
<script type="text/javascript">
location.reload();
</script>
<?php endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function(){
	
		// binds form submission and fields to the validation engine
		jQuery("#close").click(function(){
			jQuery.colorbox.close();
			location.reload();
		});
	
		jQuery('#submit').click(function() { jQuery('#frmSuggestionReply').submit(); });
		
		jQuery("#frmSuggestionReply").validationEngine();
		jQuery("#frmSuggestionReply").submit(function(event){
	
		//event.preventDefault();
		if(jQuery("#content").val() !='' && jQuery("#subject").val() !='' )
		{

		  jQuery.ajax({
				type: 'POST',
				url:  '<?php echo site_url('advertiser/suggestions/process'); ?>',
				data: jQuery('#frmSuggestionReply').serialize()+"&content="+escape(jQuery("#content").val()),
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
