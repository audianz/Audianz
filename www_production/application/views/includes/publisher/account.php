<?php header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0");
  header("Pragma: no-cache");?>
<div class="accountinfo">
    	
	<?php
			$id=$this->session->userdata('session_publisher_account_id');
			$query = $this->db->get_where('oxm_userdetails', array("accountid"=>$id)); 
			$row=$query->result();
			foreach($row as $log)
			{
				$avatar_name=$log->avatar;
			}

//$title_name= 'mining120x20.gif';			
		if(	$avatar_name != '')
		{
			?><img src="<?php echo base_url().$this->config->item('user_img_view').$avatar_name?>" width="50"  height="50" alt="Avatar" /></a><?php
		}
		else
		{?>
	<img src="<?php echo base_url();?>assets/images/avatar.png" width="50"  height="50" alt="Avatar" />
	<?php
	}
	?>
		<?php if($this->session->userdata('session_publisher_name') =='' and $this->session->userdata('session_publisher_email') =='')
		  {
		  
		   redirect("login/login");
		   }
                  
		  ?>
		
        <div class="info">
        	<h3><?php echo $this->session->userdata('session_publisher_name');?>  </h3>
            <small><?php echo $this->session->userdata('session_publisher_email');?></small>
            <p>
            	<a href="<?php echo site_url('publisher/myaccount'); ?>">Account Settings</a>
			    <a href="<?php echo site_url('login/logout'); ?>">Logout</a>
            </p>
        </div><!-- info -->
    </div><!-- accountinfo -->
</div><!-- header -->
