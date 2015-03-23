<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<?php
	/*
	   GET SITE DATA
	 */
	
	$site_data = $this->session->userdata('site_data');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $this->lang->line('label_admin_login_title').$site_data->site_title;?></title>


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

//jQuery(window).load(function(){
       // check_session();
       //  });
         
//function check_session()
       // {                
                        //jQuery.ajax({
                       // url:'./login/login_check',
                           //     success: function(msg) 
                          //      {
                                 //       if(msg!='')
                                    //      {
                                    //         window.location = msg;
                                       //          }
                               //  }
                       // });        
                                
       //  }

	jQuery(document).ready(function(){
              
            if(document.getElementById("username").value !="" && document.getElementById("password").value !="" )

		{ 
                 jQuery('.username, .password').css({backgroundPosition: "0 -32px"});
                }
             else
                {
                 jQuery('.username, .password').css({backgroundPosition: "0 0"});
		}
		// binds form submission and fields to the validation engine
		jQuery("#loginform").validationEngine();
	});
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
	<img src="<?php echo base_url().$this->config->item('admin_login_logo_view').$image_name;?>" alt="Logo" />
	<?php }  elseif($title != '' ){ ?>
    <!-- logo -->
	<div style="height:54px" align="center"><font color="#FFFFFF" size="8"><?php echo $title;?></font></a></div>
    	
		<?php } else { ?>
    <!-- logo -->
	<img src="<?php echo base_url();?>assets/images/logo.png" alt="Logo" /></a>
    <?php }?>

</div><!--loginlogo-->

<?php 
if($this->session->userdata('message') != ""):

   ?>
	<div class="loginerror">
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



<form id="loginform" action="<?php echo site_url('admin/login/admin_login_process'); ?>" method="post">

<div class="loginbox">

	<div class="loginbox_inner">

    	<div class="loginbox_content">

            <input type="text" name="username" id="username" autocomplete="off" class="validate[required] sf username" alt="<?php echo $this->lang->line('label_admin_login_username');?>" value="<?php echo (isset($_COOKIE['cookie_dreamads_user'])!='')?$_COOKIE['cookie_dreamads_user']:''; ?>" />

            <input type="password" name="password" id="password" autocomplete="off" class="validate[required] sf password" alt="<?php echo $this->lang->line('label_admin_login_password');?>" value="<?php echo (isset($_COOKIE['cookie_dreamads_user_pwd'])!='')?$_COOKIE['cookie_dreamads_user_pwd']:''; ?>"   />

            <button name="submit" class="submit"><?php echo $this->lang->line('label_admin_login_image');?></button>

        </div><!--loginbox_content-->

    </div><!--loginbox_inner-->

</div><!--loginbox-->



<div class="loginoption">

	<a href="<?php echo site_url('admin/login/forgot_password'); ?>" class="cant"><?php echo $this->lang->line('label_admin_login_forget_password');?></a>

    <input type="checkbox" name="remember" style="margin-right:5px" value="1" <?php echo (isset($_COOKIE['cookie_dreamads_user']) && isset($_COOKIE['cookie_dreamads_user_pwd']))?'checked':'';?>/><?php echo $this->lang->line('label_admin_login_remember');?> 

</div><!--loginoption-->

<?php /* ?><div align="center" style="color:#FFF;font-weight:bold;">
Contact us for Live Demo: <a href="mailto:info@openxservices.com">info@openxservices.com</a>
</div><!-- Contact --><?php */ ?>
</form>



</body>

</html>
