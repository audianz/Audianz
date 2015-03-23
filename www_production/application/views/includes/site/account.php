<?php header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0");
  header("Pragma: no-cache");?>
<div class="accountinfo">
    	<img src="<?php echo base_url();?>assets/images/avatar.png" width="50"  height="50"alt="Avatar" />
		<?php if($this->session->userdata('session_advertiser_name') =='' and $this->session->userdata('session_advertiser_email') =='')
		  {
		  
		   redirect("login/login");
		   }
                   
		  ?>
		
        <div class="info">
        	<h3><?php echo $this->session->userdata('session_advertiser_name');?>  </h3>
            <small><?php echo $this->session->userdata('session_advertiser_email');?></small>
            <p>
            	<a href="<?php echo site_url('advertiser/myaccount'); ?>">Account Settings</a>
			    <a href="<?php echo site_url('login/logout'); ?>">Logout</a>
            </p>
        </div><!-- info -->
    </div><!-- accountinfo -->
</div><!-- header -->
