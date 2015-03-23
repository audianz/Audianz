<div class="dropbox">
	<div class="messagelist">

	    <h4><?php echo $this->lang->line('lang_notify_approval_notify_title');?></h4>

	    <ul>

			<?php if(isset($total_pending_ad_list_count) && $total_pending_ad_list_count>0): ?>
		<li class="current">

			<a href="<?php echo site_url('admin/approvals/advertisers'); ?>"><?php echo $this->lang->line('lang_notify_advertiser');?></a>

			<span><b><?php echo $total_pending_ad_list_count; ?></b> <?php echo $this->lang->line('lang_notify_advertiser_wait_approval');?></span>

		    <div class="notify_duration"><small><?php echo $last_adv_entered_app; ?></small></div>

		</li>
			<?php endif; ?>

			<?php if(isset($total_pending_pub_list_count) && $total_pending_pub_list_count>0): ?>
		<li class="current">

			<a href="<?php echo site_url('admin/approvals/publishers'); ?>"><?php echo $this->lang->line('lang_notify_publishers');?></a>

			<span><b><?php echo $total_pending_pub_list_count; ?></i></b><?php echo $this->lang->line('lang_notify_pub_wait_approval');?>.</span>

		    <div class="notify_duration"><small><?php echo $last_pub_entered_app; ?></small></div>

		</li>
			<?php endif; ?>
		
			<?php if(isset($total_pending_ban_list_count) && $total_pending_ban_list_count>0): ?>
		<li class="current">

			<a href="<?php echo site_url('admin/approvals/banners'); ?>"><?php echo $this->lang->line('lang_notify_banners');?></a>

			<span><b><?php echo $total_pending_ban_list_count; ?></b><?php echo $this->lang->line('lang_notify_ban_wait_approval');?></span>

		    <div class="notify_duration"><small><?php echo $last_ban_entered_pending; ?></small></div>

		</li>
			<?php endif; ?>
		
			<?php if(isset($pending_payment_list_count) && $pending_payment_list_count>0): ?>
			<li class="current">

			<a href="<?php echo site_url('admin/approvals/payments'); ?>"><?php echo $this->lang->line('lang_notify_pending');?></a>

			<span><b><?php echo $pending_payment_list_count; ?></b><?php echo $this->lang->line('lang_notify_pay_wait_approval');?></span>

		    <div class="notify_duration"><small><?php echo $last_pay_entered_app; ?></small></div>

		</li>
			<?php endif; ?>
		
		 <?php if(isset($total_unviewed_adv) && $total_unviewed_adv >0): ?>
			<li class="current">
					<a href="<?php echo site_url('admin/approvals/unviewed_advertisers'); ?>"><?php echo $this->lang->line('lang_notify_unviewed_adv');?></a>

				<span><b><?php echo $total_unviewed_adv; ?></b><?php echo $this->lang->line('lang_notify_adv_unview');?></span>

		    <div class="notify_duration"><small><?php echo $last_adv_entered; ?></small></div>
			</li>
			<?php endif; ?>
			 <?php if(isset($total_unviewed_pub) && $total_unviewed_pub >0): ?>
			<li class="current">
					<a href="<?php echo site_url('admin/approvals/unviewed_publishers'); ?>"><?php echo $this->lang->line('lang_notify_unviewed_pub');?></a>

				<span><b><?php echo $total_unviewed_pub; ?></b><?php echo $this->lang->line('lang_notify_pub_unview');?></span>

		    <div class="notify_duration"><small><?php echo $last_pub_entered; ?></small></div>
			</li>
			<?php endif; ?>

	    </ul>

	    <!--<div class="link"><a href="">View All Notifications</a></div>-->

	</div>
</div>
<?php exit; ?>
