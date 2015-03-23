<title><?php echo $this->lang->line('site_title'); ?></title>
<div width="600px" style="border: 2px solid #333;-moz-border-radius: 5px 5px 5px 5px; -webkit-border-radius: 5px 5px 5px 5px; border-radius: 5px 5px 5px 5px;">
	<div style="background: #333; padding: 20px 10px 11px 10px; position: relative;border-top: 1px solid #444; border-bottom: 3px solid #272727;">
	<!--logo-->
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
		if($image_name != '')
		{?>
		
	<a href="<?php echo site_url($this->uri->segment(1).'/dashboard'); ?>"><img src="<?php echo base_url().$this->config->item('admin_site_logo_view').$image_name;?>"alt="<?php echo $image_name;?>" ></a>
	<?php }  elseif($title!= '' ){ ?>
    <!-- logo -->
	<div style="height:54px"><a href=""><font color="#FFFFFF" size="8" ><?php echo $title;?></font></a></div>
    	
		<?php } else { ?>
    <!-- logo -->
	<a href="<?php echo site_url($this->uri->segment(1).'/dashboard'); ?>"><img src="<?php echo base_url();?>assets/images/logo2.png" alt="Logo" /></a>
    <?php }?>
	<!--info-->
	</div><!--header-->
	<div>
	<?php echo $content; ?>	
	</div>  
	<div style="background: #333; padding: 10px 0px ;">
		<div style="text-align: right; font-size: 11px; color: #ccc;">
		&copy; <?php echo $this->lang->line('site_footer'); ?>
	    	</div><!-- footerinner -->
	</div><!-- footer -->
</div>

