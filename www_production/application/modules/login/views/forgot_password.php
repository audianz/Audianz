<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $this->lang->line('label_admin_login_title');?></title>

<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/style.css" />
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/custom_style.css" />

<!--[if IE 9]>

    <link rel="stylesheet" media="screen" href="css/ie9.css"/>

<![endif]-->



<!--[if IE 8]>

    <link rel="stylesheet" media="screen" href="css/ie8.css"/>

<![endif]-->



<!--[if IE 7]>

    <link rel="stylesheet" media="screen" href="css/ie7.css"/>

<![endif]-->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/general.js"></script>
</head>


<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/style.css" />
<link rel="stylesheet" media="screen" href="<?php echo base_url(); ?>assets/css/custom_style.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/general.js"></script>
<script>

	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#loginform").validationEngine();
	});
		function emailValidateNew(field, rules, i, options)
	{
        
		 
		var email = field.val();
      
		var mailadd = email.split("@");
        
		if(mailadd.length>1)
		{
		  
			var finalsplit = mailadd[1].split(".");
			if(finalsplit.length <= 1){
				return '<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>';
			}
			else{
				if(finalsplit[1]=='' || finalsplit[1]==null){
					return '<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>';
				}
			}
		}
		else
		{
		
			return '<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>';
		}

	}
	
	</script>

<body>



<div class="loginlogo">

	 <?php
			$where=array("id"=>'1');
			$query=$this->db->get('oxm_admindetails',$where); 
			$row=$query->result();
			foreach($row as $log)
			{
				$image_name=$log->logo;
				$title=$log->site_title;
			}
			if(	$image_name != '')
		{?>
	<img src="<?php echo base_url(); ?>assets/upload/admin/logo_big/<?php echo $image_name;?>" alt="Logo" />
	<?php }  elseif($title != '' ){ ?>
    <!-- logo -->
	<div style="height:54px" align="center"><font color="#FFFFFF" size="8"><?php echo $title;?></font></a></div>
    	
		<?php } else { ?>
    <!-- logo -->
	<img src="<?php echo base_url();?>assets/images/logo2.png" alt="Logo" /></a>
    <?php }?>

</div><!--loginlogo-->

<?php 
if($this->session->userdata('message') != ""):
   ?>
	<div class="loginerror"><a class="close"></a>
	  <p>
		<?php 
		echo $this->session->userdata('message');	
		$this->session->unset_userdata('message');
	  	?>
	  </p>
	</div>
<?php
endif;
?>
<?php 
if($this->session->userdata('eamilmessage') != ""):
   ?>
	<div class="loginsuss"><a class="close"></a>
	  <p>
		<?php 
		echo $this->session->userdata('eamilmessage');	
		$this->session->unset_userdata('eamilmessage');
	  	?>
	  </p>
	</div>
<?php
endif;
?>


<form id="loginform" action="<?php echo site_url('login/login/forget_password_process'); ?>" method="post">
 <div class="loginoption" style="width:500px;">
	       <input type="radio" name="checkbox_type" checked="checked"  value="ADVERTISER" style="margin-left:25px;"/> <font style=" margin-right:50px; font-size:12px;"><?php echo $this->lang->line('label_login_advertise');?></font>
              <input type="radio" name="checkbox_type"   value="TRAFFICKER" style="margin-left:25px;" /> <font style=" margin-right:50px;font-size:12px;vertical-align:middle;"><?php echo $this->lang->line('label_login_publisher');?></font>
        
			</div>
<div class="forgetbox">

	<div class="forgetbox_inner">

    	<div class="forgetbox_content">

            <input type="text" name="useremail"  autocomplete="off" class="validate[required,funcCall[emailValidateNew]] sf email"  alt="<<?php echo $this->lang->line('label_admin_login_email');?>"/>

            <button name="submit" class="submit"><?php echo $this->lang->line('label_admin_login_submit');?></button>
            <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
        </div><!--loginbox_content-->

    </div><!--loginbox_inner-->

</div><!--loginbox-->



</form>

 <script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/login'); ?>';
			}
	</script>

</body>
