
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>DreamAds</title>
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/style1.css" />
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/custom_style.css" />

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.colorbox-min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/general.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/users.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/gallery.js"></script>
</head>

<body style="background:#c7d6ec;">
<div class="header">
	
    <!--logo-->
	<a href=""><img src="<?php echo base_url(); ?>assets/images/logo2.png" alt="Logo" /></a>
    
    <div class="tabmenu">
   	<?php 
		$this->load->view("page/menu"); ?>
    </div><!--tabmenu-->
    
    
</div><!--header-->

<div class="centerbody">
	
    <br clear="all" />
    <h1 class="pageTitle"><?php echo $page['page_title']; ?></h1>
	
	<?php echo ($page['description']); ?>
	
</div>
<div class="footer footer_float">
	<div class="footerinner">
    	&copy; 2011. Mandy Lane Premium Template. All Rights Reserved.
    </div><!-- footerinner -->
</div><!-- footer -->

</body>
</html>
