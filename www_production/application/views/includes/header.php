<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
	/*
	   GET SITE DATA
	 */
	
	$site_data = $this->session->userdata('site_data');
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo $site_data->site_title.' | '.$this->lang->line('site_module_admin'); ?></title>
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/style.css" />
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/custom_style.css" />
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/jquery.treeview.css" />

<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css" /> 

<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/ie7.css"/>
<![endif]-->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/jquery.treeview.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/general.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/users.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/gallery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.alerts.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>

</head>

<body class="bodygrey">

<div class="headerspace"></div>

<div class="header">	
    <?php /* Notifications */ 
	$this->load->view("includes/notification"); ?>
    
    <!-- logo -->
	<?php
			$where=array("id"=>'1');
			$query=$this->db->get('oxm_admindetails',$where); 
			$row=$query->result();
			foreach($row as $log)
			{
				$image_name=$log->logo;
				$title=$log->site_title;
			}

//$title_name= 'mining120x20.gif';			
		if(	$image_name != '')
		{?>
		<a href="<?php echo site_url($this->uri->segment(1).'/dashboard'); ?>"><img src="<?php echo base_url().$this->config->item('admin_site_logo_view').$image_name;?>"alt="<?php echo $image_name;?>"></a><br />	
		<?php }  elseif($title!= '' ){ ?>
    <!-- logo -->
	<div style="height:54px"><a href="<?php echo site_url($this->uri->segment(1).'/dashboard'); ?>"><font color="#FFFFFF" size="6" ><?php echo $title;?></font></a></div>
    	
		<?php } else { ?>
    <!-- logo -->
	<a href="<?php echo site_url($this->uri->segment(1).'/dashboard'); ?>"><img src="<?php echo base_url();?>assets/images/logo2.png" alt="Logo" /></a>
    <?php }?>
