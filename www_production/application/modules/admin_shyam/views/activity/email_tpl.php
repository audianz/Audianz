<title><?php echo $this->lang->line('lang_daily_notify');?></title>
<div width="600px" style="border: 2px solid #333;-moz-border-radius: 5px 5px 5px 5px; -webkit-border-radius: 5px 5px 5px 5px; border-radius: 5px 5px 5px 5px;">
	<div style="background: #333; padding: 20px 10px 11px 10px; position: relative;border-top: 1px solid #444; border-bottom: 3px solid #272727;">
	<!--logo-->
	<a href=""><img src="<?php echo base_url();?>assets/images/logo2.png" alt="Logo" /></a>
	<!--info-->
	</div><!--header-->
	<div style="padding-bottom:20px;">
	<?php echo "<br>";?>
	<?php echo $this->lang->line('label_active_admin_welcome'); ?>
	<?php echo "<br>";?>
	<?php echo "<br>";?>
	<?php echo $content; ?>	
	</div>  
	<div style="background: #333; padding: 10px 0px ;">
		<div style="padding: 0 20px; text-align: right; font-size: 11px; color: #ccc;">
		<?php echo $this->lang->line('label_copy_rights'); ?>
	    	</div><!-- footerinner -->
	</div><!-- footer -->
</div>

