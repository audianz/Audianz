<?php if(count($rs) > 0 && $rs !=''): ?>
<?php foreach($rs as $row) : ?>
<?php
if($row->type =='ADVERTISER') { $client	=$this->mod_suggestions->get_advertiser_info($row->suggestion_sender); $reciever	=@$client[0]->email; }
else if($row->type =='TRAFFICKER') { $client =$this->mod_suggestions->get_publisher_info($row->suggestion_sender); $reciever	=@$client[0]->email; }
else { $reciever	=$this->session->userdata('mads_sess_admin_email'); }
?>
<form id="frmSuggestionReply" action="" method="post">
  <div class="form_default">
  <h4 id="popup_title"><?php echo $this->lang->line('label_suggestions_message_to'); ?> : <?php echo $reciever; ?></h4>
	<p id="response" align="center"></p>
   <div id="popup_message" style="padding-left:10px;"><?php echo $this->lang->line('label_suggestions_type_something'); ?>:<br><input type="text" id="popup_prompt" size="30" style="width: 290px;"></div>
    <p>
      <label for="subject"><?php echo $this->lang->line('label_suggestions_reply_subject'); ?></label>
      <input type="text" name="subject" id="subject" class="validate[required] sf" value="<?php echo "Re: ".$row->subject; ?>" alt="<?php echo $this->lang->line('label_suggestions_subjectrequired'); ?>"/>
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
<input type="hidden" name="sender" value="<?php echo $this->session->userdata('mads_sess_admin_email'); ?>" />
<input type="hidden" name="sender" value="<?php echo $reciever; ?>" />
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
			//location.reload();
		});

		jQuery("#cboxClose").remove();
	
		jQuery('#submit').click(function() { jQuery('#frmSuggestionReply').submit(); });
		
		jQuery("#frmSuggestionReply").validationEngine();
		jQuery("#frmSuggestionReply").submit(function(event){
	
		//event.preventDefault(); 
		if(jQuery("#content").val() !='' && jQuery("#subject").val() !='' )
		{

		 jQuery.ajax({
				type: 'POST',
				url:  '<?php echo site_url('admin/suggestions/process'); ?>',
				data: jQuery('#frmSuggestionReply').serialize()+"&content="+escape(jQuery("#content").val()),
				success: function(response) 
				{
				   if(response ==1) { jQuery('#response').css('color', 'green'); jQuery('#response').html('<?php echo $this->lang->line('label_suggestions_reply_success'); ?>'); }
				   else { jQuery('#response').css('color', 'red'); jQuery('#response').html('<?php echo $this->lang->line('label_suggestions_reply_unsuccess'); ?>'); } 
							jQuery("#submit").attr('disabled','disabled');
							jQuery.colorbox.close();
							location.reload();
				   }
		 })
		}
	});
	
	});
</script>
