<?php if(count($rs) > 0 && $rs !=''): ?>
<?php foreach($rs as $row) : ?>
<?php
if($row->type =='ADVERTISER') { $client	=$this->mod_suggestions->get_advertiser_info($row->suggestion_sender); $reciever	=@$client[0]->email; }
else if($row->type =='TRAFFICKER') { $client =$this->mod_suggestions->get_publisher_info($row->suggestion_sender); $reciever	=@$client[0]->email; }
else { $reciever	='MANAGER'; }
?>
  <div class="form_default">
    <h4 id="popup_title"><?php echo $this->lang->line('label_suggestions_message_to'); ?> : <?php echo $reciever; ?></h4>
	<div id="popup_message" style="padding-left:10px; min-height:200px !important; word-wrap:break-word;"><strong><u><?php echo $row->subject; ?></u></strong> :<br><textarea style="padding-left:10px; min-height:200px !important; min-width:94% !important; overflow:scroll-y !important;" readonly="readonly"><?php echo $row->content; ?></textarea></div>
   	<p align="center">
	 <button type="button" id="close" style="margin-left:0px;"><?php echo $this->lang->line('label_cancel'); ?></button>
	</p>

  </div>

<?php endforeach; endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#close").click(function(){
			jQuery.colorbox.close();
		});

		//cboxLoadedContent
		jQuery("#cboxClose").remove();
});
</script>
