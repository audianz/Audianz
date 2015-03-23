<?php	
	$link = '';
	$ci = &get_instance();
	$i=1;
	$uri = $ci->uri->segment($i);
	while($uri != ''){
		$prep_link = '';
		for($j=1; $j<=$i;$j++){
			$prep_link .= $ci->uri->segment($j).'/';
		}
	
		if($ci->uri->segment($i+1) == ''){
			$link =$ci->uri->segment($i);
		}
		$i++;
		$uri = $ci->uri->segment($i);
		//echo $link;
	}
?>

<div class="sidebar">
	<div id="accordion">
        <h3 id="tag" class="open"><?php echo $this->lang->line('label_publisher_panel'); ?></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu">
				<?php 				
				$a = explode("_",$this->uri->segment(2));
				
				if($this->uri->segment(2)=="dashboard" || $this->uri->segment(2)=="zones" || $this->uri->segment(2)=="performance_report" || $this->uri->segment(2)=="publisher_notifications" || $this->uri->segment(2)=="changepassword" || $this->uri->segment(2)=="refer_friends" || $this->uri->segment(2)=="myaccount" || $this->uri->segment(2)=="payments" || $this->uri->segment(2)=="messages" || $this->uri->segment(1) == NULL || $this->uri->segment(2)=="reports")
				{
				?>
				
			<li <?php echo ($this->uri->segment(2)=="dashboard")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/dashboard'); ?>" class="home"><?php echo $this->lang->line('label_home'); ?></a></li>
			
			<li <?php echo ($this->uri->segment(2)=="payments")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/payments'); ?>" class="document"><?php echo $this->lang->line('label_payments'); ?></a></li>

			<li <?php echo ($this->uri->segment(2)=="reports")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/reports'); ?>" class="table"><?php echo $this->lang->line('label_report'); ?></a></li>
	
		    <li <?php echo ($this->uri->segment(2)=="myaccount")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/myaccount'); ?>" class="myaccount"><?php echo $this->lang->line('label_account_settings'); ?></a></li>

			<li <?php echo ($this->uri->segment(2)=="changepassword")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/changepassword'); ?>" class="editor"><?php echo $this->lang->line('label_change_password'); ?></a></li>

			<li <?php echo ($this->uri->segment(2)=="publisher_notifications")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/publisher_notifications'); ?>" class="notifications"><?php echo $this->lang->line('label_notifications'); ?></a></li>
			
			<li <?php echo ($this->uri->segment(2)=="messages")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/messages'); ?>" class="mgright5"><?php echo $this->lang->line('label_messages'); ?></a></li>

			<li <?php echo ($this->uri->segment(3)=="rtb_bidding")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/zones/rtb_bidding'); ?>" class="document"><?php echo $this->lang->line('label_realtime_bid_rate'); ?></a></li>
				
				<?php }	?>
            </ul>
        
        </div>
       
        <h3 style='visibility:hidden;' class="open">Latest Update</h3>
        <div class="content" style="display: block;visibility:hidden;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </div>
	</div>
	
</div><!-- leftmenu -->
