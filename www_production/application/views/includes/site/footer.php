<?php
	/*
	   GET SITE DATA
	 */
	
	$site_data = $this->session->userdata('site_data');
?>
<div class="footer footer_float">
	<div class="footerinner">
    	&copy; <?php echo $this->lang->line('site_footer1').$site_data->site_title.$this->lang->line('site_footer2');?>
    </div><!-- footerinner -->
</div><!-- footer -->

</body>
</html>
