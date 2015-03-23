<?php 
	
	$clientid = $this->session->userdata('session_publisher_id');//Pass the session publisher id value
	
	$count_payment	=	$this->mod_pub_notifications->get_total_paid_payments_today($clientid);
	$total_payment_notify = $count_payment[0]->COUNT;
		
?>

<div class="topheader">
        <ul class="notebutton">
        <?php if($this->session->userdata('session_user_type') =='TRAFFICKER') { ?>
		<li class="note">
			<a href="javascript:void(0);" onclick="message_notify();" title="<?php echo $this->lang->line('label_message_notification_title');?>">
				<span class="wrap">
				<span class="thicon msgicon"></span>
				<?php $count	=$this->mod_suggestions->get_tabread('0', $this->session->userdata('session_publisher_id')); ?>
				<?php if($count >0) : ?><span class="count"><?php echo $count; ?></span><?php endif; ?>
				</span>
			</a>
			<div class="message_pub"></div>
	       </li>
	<?php } ?>

            <li class="note">
		<a href="javascript:void(0);" onclick="notify_today();" title="<?php echo $this->lang->line('label_payments_notification_title'); ?>" >
                	<span class="wrap">
                    	<span class="thicon infoicon"></span>
						<?php if($total_payment_notify>0 && isset($total_payment_notify)):?>
                        <span class="count"><?php echo $total_payment_notify; ?></span>
						<?php endif; ?>
                    </span>
                </a>
		<div class="notify_today"></div>
            </li>
        </ul>
    </div><!-- topheader -->

<script type="text/javascript">

		function notify_today()
		{
			//alert('test');
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url('publisher/publisher_notification'); ?>',
				success: function(msg){
					jQuery(".notify_today").append(msg);
				}
			}); 	
		}

		function message_notify()
		{
			//alert('test');
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url('publisher/messages/tabview/0'); ?>',
				success: function(msg){
					jQuery(".message_pub").append(msg);
				}
			}); 	
		}
</script>
