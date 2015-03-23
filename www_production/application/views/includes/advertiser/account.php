<?php header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0");
  header("Pragma: no-cache");?>
<div class="accountinfo">
    	<?php
			$id=$this->session->userdata('session_advertiser_account_id');
			$query = $this->db->get_where('oxm_userdetails', array("accountid"=>$id)); 
			$row=$query->result();
			foreach($row as $log)
			{
				$avatar_name=$log->avatar;
			}

//$title_name= 'mining120x20.gif';			
		if( $avatar_name != '')
		{
			?><img src="<?php echo base_url().$this->config->item('user_img_view').$avatar_name?>" width="50"  height="50" alt="Avatar" /></a><?php
		}
		else
		{?>
	<img src="<?php echo base_url();?>assets/images/avatar.png" width="50"  height="50" alt="Avatar" />
	<?php
	}
	?>
		<?php if($this->session->userdata('session_advertiser_name') =='' and $this->session->userdata('session_advertiser_email') =='')
		  {
		  
		   redirect("login/login");
		   }
                   
		  ?>
		
        <div class="info">
        	<h3><?php echo $this->session->userdata('session_advertiser_name');?>  </h3>
            <small><?php echo $this->session->userdata('session_advertiser_email');?></small>
            <p>
            	<a href="<?php echo site_url('advertiser/myaccount'); ?>"><?php echo $this->lang->line('label_site_account_settings');?></a>
			    <a href="<?php echo site_url('login/logout'); ?>"><?php echo $this->lang->line('label_site_logout');?></a>
            </p>
        </div><!-- info -->
    </div><!-- accountinfo -->
</div><!-- header -->
