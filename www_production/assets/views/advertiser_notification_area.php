<div class="messagelist">

    <h4><?php echo $this->lang->line('label_banners_notification_title'); ?></h4>
    <ul>
	<?php if($banner_list !=FALSE):
		if(isset($banner_list) && count($banner_list)>0): 
		for($i=0;$i<count($banner_list);$i++): 
			if($banner_list[$i]['adminstatus'] ==1):
		?>
        <li class="current">

        	<a href="<?php echo site_url('advertiser/banners/listing')?>/<?php echo $banner_list[$i]['campaignid'];?>"><?php echo $banner_list[$i]['banner_name']; ?></a>

        	<span><b><?php echo $banner_list[$i]['ban_type']; ?> <?php echo $this->lang->line('label_not_banner'); ?></b> <?php echo $this->lang->line('label_waiting_approval'); ?>.</span>

            <div class="notify_duration"><small><?php echo $banner_list[$i]['duration']; ?></small></div>

        </li>
		<?php else: ?>
		<li>

        	<a href="<?php echo site_url('advertiser/banners/listing')?>/<?php echo $banner_list[$i]['campaignid'];?>"><?php echo $banner_list[$i]['banner_name']; ?></a>

        	<span><b><?php echo $banner_list[$i]['ban_type']; ?> <?php echo $this->lang->line('label_not_banner'); ?></b> <?php echo $this->lang->line('label_notify_ban_added'); ?>.</span>

            <div class="notify_duration"><small><?php echo $banner_list[$i]['duration']; ?></small></div>

        </li>
			
		<?php endif; ?>
		<?php endfor; ?>


		<?php endif; ?>
		<?php else: ?> 
<li><div class="no_notifications"><?php echo $this->lang->line('label_no_banners'); ?></div></li>
	<?php endif; ?>
    </ul>

    <!--<div class="link"><a href="">View All Notifications</a></div>-->

</div>
