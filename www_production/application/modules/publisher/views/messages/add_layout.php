<style>
.noborder {
    border: 1px solid #999999 !important;
    -moz-box-shadow: 0px 0px 0px rgba(0, 0, 0, .0) !important;
    -webkit-box-shadow: 0px 0px 0px rgba(0, 0, 0, .0) !important;
    box-shadow: 0px 0px 0px rgba(0, 0, 0, .0) !important;
    border-radius: 0px 0px 0px 0px !important;
    background: none !important;
}
</style>

<form id="frmSuggestionAdd" action="" method="post">
  <div class="form_default">
  <h4 id="popup_title"><?php echo $this->lang->line('label_suggestions_message_to'); ?> : <?php echo $admin_email; ?></h4>
	<p id="response" align="center"></p>
   <div id="popup_message" style="padding-left:10px;"><?php echo $this->lang->line('label_suggestions_reply_subject'); ?>:<br /><input type="text" style="width:450px;" name="subject" id="subject" class="validate[required] sf noborder" value="" alt="<?php echo $this->lang->line('label_suggestions_subjectrequired'); ?>"/></div>
   <br /> 
   <div id="popup_message" style="padding-left:10px;"><?php echo $this->lang->line('label_suggestions_reply_message'); ?>:<br /><textarea style="width:90%; height:200px; word-wrap:break-word !important;" name="content" id="content" class="validate[required] sf noborder" alt="<?php echo $this->lang->line('label_suggestions_contentrequired'); ?>"></textarea></div>
   	
	<div style="padding-left:10px;">
	<br />
	 <button type="button" id="submit"><?php echo $this->lang->line('label_submit'); ?></button>
	 <button type="button" id="closes" style="margin-left:10px;"><?php echo $this->lang->line('label_cancel'); ?></button>
	</div>
	<br />

</div>

<input type="hidden" name="recieverid" value="2" />
<input type="hidden" name="recievertype" value="MANAGER" />
<input type="hidden" name="suggestionid" value="0" />

<input type="hidden" name="sender" value="<?php echo $this->session->userdata('session_publisher_email'); ?>" />
<input type="hidden" name="reciever" value="<?php echo $admin_email; ?>" />
</form>
<script type="text/javascript">
    jQuery(document).ready(function(){
	
		// binds form submission and fields to the validation engine
		jQuery("#closes").click(function(){
			jQuery.colorbox.close();
			//location.reload();
		});

		jQuery("#cboxClose").remove();
		
		jQuery.colorbox.resize({width:"50%", height:"82%", scrolling: false}); //, fixed: true, rel :'iframe'});

		jQuery('#submit').click(function() { jQuery('#frmSuggestionAdd').submit(); });
		
//		jQuery("#frmSuggestionAdd").validationEngine();
		jQuery("#frmSuggestionAdd").validationEngine({promptPosition : "topLeft"});
		
		jQuery("#frmSuggestionAdd").submit(function(event){
	
		//event.preventDefault(); 
		if(jQuery("#content").val() !='' && jQuery("#subject").val() !='' )
		{

		 jQuery.ajax({
				type: 'POST',
				url:  '<?php echo site_url('publisher/messages/addprocess'); ?>',
				data: jQuery('#frmSuggestionAdd').serialize()+"&content="+escape(jQuery("#content").val()),
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
