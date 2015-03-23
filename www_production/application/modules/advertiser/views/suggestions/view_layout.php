<?php if(count($rs) > 0 && $rs !=''): ?>
<?php foreach($rs as $row) : ?>

<form id="frmSuggestionReply" action="" method="post">

  <div class="form_default">
    <h4><?php echo $this->lang->line('label_suggestions_reply_title_view'); ?></h4>
    <p>
      <label for="sender"><?php echo $this->lang->line('label_suggestions_reply_sender'); ?></label>
      <input type="text" readonly="readonly" name="sender" id="sender" value="<?php echo $admin_email; ?>"/>
    </p>
    <p>
      <label for="subject"><?php echo $this->lang->line('label_suggestions_reply_subject'); ?></label>
      <input type="text" readonly="readonly" name="subject"  id="subject" value="<?php echo $row->subject; ?>"/>
    </p>
    <p>
      <label for="content"><?php echo $this->lang->line('label_suggestions_reply_message'); ?></label>
      <textarea rows="5" readonly="readonly"><?php echo $row->content; ?></textarea>
    </p>
	<p align="center">
	 <button type="button" id="close" style="margin-left:0px;"><?php echo $this->lang->line('label_cancel'); ?></button>
	</p>

  </div>

</form>
<?php endforeach; endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#close").click(function(){
			jQuery.colorbox.close();
		});
});
</script>