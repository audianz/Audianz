<div class="dropbox">
	<div class="messagelist">

	    <h4><?php echo $this->lang->line('lang_notify_today_notify_title');?></h4>

	    <ul>

			<?php if(isset($approved_ad_list_count_today) && $approved_ad_list_count_today>0): ?>
		<li class="current">

			<a href="<?php echo site_url('admin/inventory_advertisers'); ?>"><?php echo $this->lang->line('lang_notify_advertiser');?></a>

			<span><b><?php echo $approved_ad_list_count_today; ?></b><?php echo $this->lang->line('lang_notify_adv_added_today');?></span>

		    <div class="notify_duration"><small><?php echo $last_adv_entered_add; ?></small></div>

		</li>
			<?php endif; ?>

			<?php if(isset($approved_pub_list_count_today) && $approved_pub_list_count_today>0): ?>
		<li class="current">

			<a href="<?php echo site_url('admin/inventory_websites');?>"><?php echo $this->lang->line('lang_notify_publishers');?></a>

			<span><b><?php echo $approved_pub_list_count_today; ?></b><?php echo $this->lang->line('lang_notify_pub_added_today');?></span>

		    <div class="notify_duration"><small><?php echo $last_pub_entered_add; ?></small></div>

		</li>
			<?php endif; ?>
		
			<?php if(isset($approved_ban_list_count_today) && $approved_ban_list_count_today>0): ?>
		<li class="current">

			<a href="<?php echo site_url('admin/inventory_banners');?>"><?php echo $this->lang->line('lang_notify_banners');?></a>

			<span><b><?php echo $approved_ban_list_count_today; ?></b><?php echo $this->lang->line('lang_notify_banner_added_today');?></span>

		    <div class="notify_duration"><small><?php echo $last_ban_entered_app; ?></small></div>

		</li>
			<?php endif; ?>
		
			<?php if(isset($today_campaign_count) && $today_campaign_count>0):?>
		<li class="current">

			<a href="<?php echo site_url('admin/inventory_campaigns');?>"><?php echo $this->lang->line('lang_notify_campaign');?></a>

			<span><b><?php echo $today_campaign_count; ?></b><?php echo $this->lang->line('lang_notify_campaign_added_today');?></span>

		    <div class="notify_duration"><small><?php echo $last_campaign_entered_today;?></small></div>

		</li>
			<?php endif;?>
	
			<?php if(isset($today_zones_count) && $today_zones_count>0):?>
			 <li class="current">

			<a href="<?php echo site_url('admin/inventory_zones');?>"><?php echo $this->lang->line('lang_notify_zones');?></a>

			<span><b><?php echo $today_zones_count; ?></b><?php echo $this->lang->line('lang_notify_zone_added_today');?></span>

		   <div class="notify_duration"><small><?php echo $last_zone_entered_today;?></small></div>

		</li>
			<?php endif; ?>
		
		</ul>

	    <!--<div class="link"><a href="">View All Notifications</a></div>-->

	</div>
</div>
<?php exit; ?>
