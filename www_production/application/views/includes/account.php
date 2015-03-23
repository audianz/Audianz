<?php header("Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0");
  header("Pragma: no-cache");?>
<div class="accountinfo">
    	<?php
			$id='2';
			$query = $this->db->get_where('oxm_admindetails', array("accountid"=>$id)); 
			$row=$query->result();
			foreach($row as $log)
			{
				$avatar_name=$log->admin_avatar;
			}

//$title_name= 'mining120x20.gif';			
		if(	$avatar_name != '')
		{
			?><img src="<?php echo base_url().$this->config->item('admin_img_view').$avatar_name?>" width="50"  height="50" alt="Avatar" /></a><?php
		}
		else
		{?>
	<img src="<?php echo base_url();?>assets/images/avatar.png" width="50"  height="50"alt="Avatar" />
	<?php
	}
	?>

		
        <div class="info">
        	<h3><?php echo $this->session->userdata('mads_sess_admin_username');?>  </h3>
            <small><?php echo $this->session->userdata('mads_sess_admin_email');?></small>
            <p>
            	<a href="<?php echo site_url('admin/myaccount'); ?>">Account Settings</a>
			    <a href="<?php echo site_url('admin/admin_logout'); ?>">Logout</a>
            </p>
        </div><!-- info -->
    </div><!-- accountinfo -->
</div><!-- header -->
