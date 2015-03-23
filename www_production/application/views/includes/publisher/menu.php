<?php $cur_page = explode("_",$this->uri->segment(2)); ?>
<div class="tabmenu">
	<ul>
		<li <?php echo($this->uri->segment(2)=="dashboard" || $this->uri->segment(1)== NULL)?"class='current'":""; ?>>
				<a href="<?php echo site_url('publisher/dashboard'); ?>" class="dashboard"><span><?php echo $this->lang->line('label_dashboard'); ?></span></a>
		</li>
		<li <?php echo($this->uri->segment(2)== "zones")?"class='current'":""; ?>>
			<a href="<?php echo site_url('publisher/zones'); ?>" class="users"><span>Zones</span></a></li>
		<li <?php echo($this->uri->segment(2)== "mobile_sdk")?"class='current'":""; ?>>
			<a href="<?php echo site_url('publisher/mobile_sdk'); ?>" class="users"><span>Mobile SDK</span></a></li>
			
                <li <?php echo($this->uri->segment(2)== "payments")?"class='current'":""; ?>>
                                <a href="<?php echo site_url('publisher/payments'); ?>" class="elements"><span>Payments</span></a></li>

		<li <?php echo($cur_page[0]== "performance")?"class='current'":""; ?>><a href="<?php echo site_url('publisher/performance_report'); ?>" class="reports"><span>Reports</span></a></li>		  
	  </ul>  
</div>
<!-- tabmenu -->
