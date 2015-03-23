<style>
.noborder {
    border: 1px solid #666666 !important;
    -moz-box-shadow: 0px 0px 0px rgba(0, 0, 0, .0) !important;
    -webkit-box-shadow: 0px 0px 0px rgba(0, 0, 0, .0) !important;
    box-shadow: 0px 0px 0px rgba(0, 0, 0, .0) !important;
    border-radius: 0px 0px 0px 0px !important;
    background: none !important;
}
</style>

<?php if(count($rs) > 0 && $rs !=''): ?>
<?php foreach($rs as $row) : ?>
<?php
if($row->type =='ADVERTISER') { $client	=$this->mod_suggestions->get_advertiser_info($row->suggestion_sender); $reciever	=@$client[0]->email; }
else if($row->type =='TRAFFICKER') { $client =$this->mod_suggestions->get_publisher_info($row->suggestion_sender); $reciever	=@$client[0]->email; }
else { $reciever	='MANAGER'; }
?>
  <div class="form_default">
    <h4 id="popup_title"><?php echo $this->lang->line('label_suggestions_message_from'); ?> : <?php echo $reciever; ?></h4><br />
	<div id="popup_message" style="padding-left:10px; min-height:200px !important; word-wrap:break-word !important;"><strong><u><?php echo $row->subject; ?></u></strong> :<br /><br /><textarea class="noborder" style="padding-left:10px; height:250px !important; width:95% !important; overflow:scroll-y !important; word-wrap:break-word !important;" readonly="readonly"><?php echo $row->content; ?></textarea></div>
   	<p align="center">
	<input type="hidden" value="<?php echo site_url('admin/messages/reply/'.$row->suggestion_id); ?>" id="reply_url" />
	<button type="button" id="reply" style="margin-left:0px;"><?php echo $this->lang->line('label_suggestions_reply_reply'); ?></button>	
    <button type="button" id="close" style="margin-left:0px;"><?php echo $this->lang->line('label_cancel'); ?></button>
</p>
  </div>

<?php endforeach; endif; ?>
<script type="text/javascript">
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#close").click(function(){
			jQuery.colorbox.close();
			location.reload();
		});

		jQuery.colorbox.resize({width:"50%", height:"80%", scrolling: false}); //, fixed: true, rel :'iframe'});
		//cboxLoadedContent
		jQuery("#cboxClose").remove();
		
		jQuery("#reply").click(function(){
			var reply_data	=jQuery('#reply_url').val();
			//jQuery.colorbox.close();

			jQuery.colorbox({width:"50%", height:"82%", href:reply_data});
			
		});

		
		
		
});
</script>
