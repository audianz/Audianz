<div style="margin:10px;">
	
	<h4 style="font-size:18px;	color:#202020;	display:block;	font-family:Arial;	font-weight:bold; line-height:100%;	text-align:left;"><?php echo 'Hi Administrator,'; ?> </h4>
		<div style="margin-left:50px;">
			<p>New user has been logged into mJAX UI Demo. The logged details are given below,</p>
			<p>To view the trackers list, Click Here : <a href="<?php echo site_url('site/trackers'); ?>"><?php echo site_url('site/trackers'); ?></a></p>
		</div>

	<h4 style="font-size:18px;	color:#202020;	display:block;	font-family:Arial;	font-weight:bold; line-height:100%;	text-align:left;">User Logged In Details</h4>
		<div style="margin-left:50px;">
			<p><strong>IP Address</strong>: <?php echo $ip_address; ?></p>
			<p><strong>Country</strong>: <?php echo $country; ?></p>
			<p><strong>Logged In Time</strong>: <?php echo date("d-m-Y H:i:s",strtotime($logged_in)); ?></p>
		</div>
	<p><?php echo 'Thank You!..'; ?></p>
	

</div>
