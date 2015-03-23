<div class="dropbox">
	<div class="messagelist">

	    <h4><?php echo $this->lang->line('label_payments_notification_title'); ?></h4>
	    <ul>
	<?php if(isset($payment_list) && count($payment_list)>1): ?>
		
		<?php	for($i=0;$i<count($payment_list);$i++): 
				if($payment_list[$i]->status ==1):
			?>
		<li class="current" <?php if(date('Y-m-d',strtotime($payment_list[$i]->payment_paid_date)) == date('Y-m-d')): ?> style="background-color:#E1E1E1" <?php endif; ?> >

			<span><b>$<?php echo $payment_list[$i]->amount; ?></b> <?php echo $this->lang->line('label_amount_paid_notify'); ?>.</span>

		    <small><b><?php echo $this->lang->line('label_paid_date_notify'); ?> :</b> <?php echo date('d-M-Y',strtotime($payment_list[$i]->payment_paid_date)); ?></small>

		</li>
			<?php else: ?>
			<li>

			<span><b>$<?php echo $payment_list[$i]->amount; ?></b> <?php echo $this->lang->line('label_amount_request_payment'); ?>.</span>

		    <small><b><?php echo $this->lang->line('label_req_date_notify'); ?> : </b><?php echo date('d-M-Y',strtotime($payment_list[$i]->date)); ?></small>

		</li>
			
			<?php endif; ?>
			<?php endfor; ?>
	


	<?php else: ?> 
	<li><div class="no_notifications"><?php echo $this->lang->line("label_no_payment_notifications"); ?>.</div></li>
			<?php endif; ?>
	    </ul>
	    <!--<div class="link"><a href="">View All Notifications</a></div>-->

	</div>
</div>
<?php exit; ?>
