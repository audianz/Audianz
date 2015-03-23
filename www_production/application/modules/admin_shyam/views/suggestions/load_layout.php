<?php
echo '<style type="text/css">
	#popup_message > #popup_content
	{
		min-height : 150px !important;
		min-width : 700px !important;
	}
	#popup_content > #popup_message
	{
		overflow: scroll-y !important;
		word-wrap:break-word !important;
		min-height:150px !important;
		height:auto !important;
	}
	
	#popup_container
	{
		/*min-height:610px !important;*/
		min-width: 500px !important;
		left: 400px !important;
	}
	</style>';
	?>
<?php if(count($rs) > 0 && $rs !=''): ?>
<?php foreach($rs as $row) : ?>
<?php
if($row->type =='ADVERTISER') { $client	=$this->mod_suggestions->get_advertiser_info($row->suggestion_sender); $reciever	=@$client[0]->contact; }
else if($row->type =='TRAFFICKER') { $client =$this->mod_suggestions->get_publisher_info($row->suggestion_sender); $reciever	=@$client[0]->contact; }
else { $reciever	='MANAGER'; }
?>
<div class="one_half" style="float:left !important; width:100% !important;"><h4 style="text-decoration:underline; font-weight:bold;"><?php echo $row->subject; ?></h4><p><?php echo $row->content; ?></p></div><!-- one_half -->
<?php endforeach; endif; ?>

