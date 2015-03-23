<?php	
	$link = '';
	$ci = &get_instance();
	$i=1;
	$uri = $ci->uri->segment($i);
	while($uri != ''){
		$prep_link = '';
		for($j=1; $j<=$i;$j++){
			$prep_link .= $ci->uri->segment($j).'/';
		}
	
		if($ci->uri->segment($i+1) == ''){
			$link =$ci->uri->segment($i);
		}
		$i++;
		$uri = $ci->uri->segment($i);
		//echo $link;
	}
?>

<div class="sidebar">
	<div id="accordion">
        <h3 class="open"><?php echo $this->lang->line('label_admin_panel'); ?></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu">
            	<?php 
				
				$a = explode("_",$this->uri->segment(2));

				if($this->uri->segment(2)=="dashboard" || $this->uri->segment(2)=="site_settings" || $this->uri->segment(2)=="myaccount" || $this->uri->segment(1) == NULL || $this->uri->segment(2)=="user_activity" || $this->uri->segment(2)=="messages" || $this->uri->segment(3) == 'change_password'){
				?>
				<?php if($link == 'dashboard' || $link==NULL){ ?>
					<li class="current"><a href="<?php echo site_url('admin/dashboard'); ?>" class="home"><?php echo $this->lang->line('label_home'); ?></a></li>
				<?php } else { ?>
					<li><a href="<?php echo site_url('admin/dashboard'); ?>" class="home"><?php echo $this->lang->line('label_home'); ?></a></li>
				<?php  } ?>
				<?php if($this->uri->segment(2)=='myaccount'){ ?>
					<li class="current"><a href="<?php echo site_url('admin/myaccount'); ?>" class="myaccount"><?php echo $this->lang->line('label_account_settings'); ?></a></li>
				<?php } else { ?>
					<li><a href="<?php echo site_url('admin/myaccount'); ?>" class="myaccount"><?php echo $this->lang->line('label_account_settings'); ?></a></li>
				<?php } ?>
				<?php if($this->uri->segment(3)=='change_password'){ ?>
					<li class="current"><a href="<?php echo site_url('admin/site_settings/change_password'); ?>" class="editor"><?php echo $this->lang->line('label_change_password'); ?></a></li>
				<?php } else { ?>
					<li><a href="<?php echo site_url('admin/site_settings/change_password'); ?>" class="editor"><?php echo $this->lang->line('label_change_password'); ?></a></li>
				<?php } if($this->uri->segment(2)=='messages'){ ?>
					<li class="current"><a href="<?php echo site_url('admin/messages/messages_list'); ?>" class="mgright5"><?php echo $this->lang->line('label_messages'); ?></a></li>
				<?php } else { ?>
					<li><a href="<?php echo site_url('admin/messages/messages_list'); ?>" class="mgright5"><?php echo $this->lang->line('label_messages'); ?></a></li>
				<?php } ?>

				
				<?php				
				}
				else if($a[0]=="inventory")
				{
				?>
						<li <?php echo($this->uri->segment(2)=="inventory_advertisers")?"class='current'":""; ?> ><a href="<?php echo site_url("admin/inventory_advertisers"); ?>" class="adv_pub"><?php echo $this->lang->line('label_advertisers'); ?></a></li>
						
						<li <?php echo($this->uri->segment(2)=="inventory_campaigns")?"class='current'":""; ?>><a href="<?php echo site_url("admin/inventory_campaigns");; ?>" class="campaign"><?php echo $this->lang->line('label_campaigns'); ?></a></li>
						
						<li <?php echo($this->uri->segment(2)=="inventory_banners")?"class='current'":""; ?>><a href="<?php echo site_url("admin/inventory_banners"); ?>" class="gallery"><?php echo $this->lang->line('label_banners'); ?></a></li>
						
						<li <?php echo($this->uri->segment(2)=="inventory_websites")?"class='current'":""; ?>><a href="<?php echo site_url("admin/inventory_websites"); ?>" class="websites"><?php echo $this->lang->line('label_websites'); ?></a></li>
						
						<li <?php echo($this->uri->segment(2)=="inventory_zones")?"class='current'":""; ?>><a href="<?php echo site_url("admin/inventory_zones"); ?>" class="editor"><?php echo $this->lang->line('label_zones'); ?></a></li>
				<?php
				}
				else if($a[0]=="pages")
				{
				?>
						<li <?php echo($this->uri->segment(2)=="pages")?"class='current'":""; ?> ><a href="<?php echo site_url("admin/pages"); ?>" class="document"><?php echo $this->lang->line('label_static_pages_page_title'); ?></a></li>
						<li <?php echo($this->uri->segment(2)=="menus")?"class='current'":""; ?>><a href="<?php echo site_url("admin/menus"); ?>" class="tag"><?php echo $this->lang->line('lang_static_page_menu_title'); ?></a></li>
						
				<?php
				}
				else if($a[0]=="statistics" || $this->uri->segment(2)=="payment_history")
				{
				?>
						<li <?php echo($this->uri->segment(2)=="statistics_advertiser")?"class='current'":""; ?> ><a href="<?php echo site_url('admin/statistics_advertiser'); ?>" class="adv_pub">
						<?php echo $this->lang->line('lang_statistics_advertiser_page_title'); ?></a></li>

						<li <?php echo($this->uri->segment(2)=="statistics_publisher")?"class='current'":""; ?>><a href="<?php echo site_url('admin/statistics_publisher'); ?>" class="settings">
						
						<?php echo $this->lang->line('lang_statistics_publisher_title'); ?></a></li>
						<li <?php echo($this->uri->segment(2)=="statistics_global" AND $this->uri->segment(3)!="country_wise_statistics")?"class='current'":""; ?>><a href="<?php echo site_url('admin/statistics_global'); ?>" class="calendar">
						<?php echo $this->lang->line('lang_statistics_global_title'); ?></a></li>
						
						<li <?php echo(($this->uri->segment(2)=="statistics_global" AND $this->uri->segment(3)=="country_wise_statistics"))?"class='current'":""; ?>><a href="<?php echo site_url('admin/statistics_global/country_wise_statistics'); ?>" class="tag">
						<?php echo $this->lang->line('label_country'); ?></a></li>
						
						<li <?php echo(($this->uri->segment(2)=="payment_history"))?"class='current'":""; ?>><a href="<?php echo site_url('admin/payment_history'); ?>" class="document">
						<?php echo $this->lang->line('label_payment_history_data'); ?></a></li>
						
						
				<?php
				}
				else if($a[0]=="menus")
				{
				?>
						<li <?php echo($this->uri->segment(2)=="pages")?"class='current'":""; ?> ><a href="<?php echo site_url("admin/pages"); ?>" class="form"><?php echo $this->lang->line('label_static_pages_page_title'); ?></a></li>
						<li <?php echo($this->uri->segment(2)=="menus")?"class='current'":""; ?>><a href="<?php echo site_url("admin/menus"); ?>" class="gallery"><?php echo $this->lang->line('lang_static_page_menu_title'); ?></a></li>
						
				<?php
				}
				else if($a[0]=="settings")
				{
				?>
						<li <?php echo($this->uri->segment(2)=="settings_device_manufacturers")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_device_manufacturers"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_device_manufacturers'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(2)=="settings_device_os")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_device_os"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_device_os'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(2)=="settings_device_capability")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_device_capability"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_device_capability'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(2)=="settings_geo_locations")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_geo_locations"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_geo_locations'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(2)=="settings_geo_operators")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_geo_operators"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_geo_operators'); ?></a></li>



						<li <?php echo($this->uri->segment(2)=="settings_mobile_screens")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_mobile_screens"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_mobile_screens'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(2)=="settings_client_profile")?"class='current'":""; ?> >

						<a href="<?php echo site_url("admin/settings_client_profile"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_client_profile'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(2)=="settings_tera_wurfl")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_tera_wurfl"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_terawurfl');?>

						</a></li>

							

						<li <?php echo($this->uri->segment(2)=="settings_campaign_categories")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/settings_campaign_categories"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_campaign_categories');?>

						</a></li>
						
						</ul>
						</div>
						
						<h3 class="open"><?php echo $this->lang->line('label_default_settings'); ?> </h3>
						<div class="content" style="display: block;">
						<ul class="leftmenu">
								<li <?php echo($this->uri->segment(2)=="settings_campaign_status")?"class='current'":""; ?>>

									<a href="<?php echo site_url("admin/settings_campaign_status"); ?>" class="form">
			
									<?php echo $this->lang->line('label_campaign_status');?>
			
									</a></li>
									
									<li <?php echo($this->uri->segment(2)=="settings_revenue_type")?"class='current'":""; ?>>
			
									<a href="<?php echo site_url("admin/settings_revenue_type"); ?>" class="form">
			
									<?php echo $this->lang->line('label_revenue_type');?>
			
									</a></li>
									
									<li <?php echo($this->uri->segment(2)=="settings_trackers_status")?"class='current'":""; ?>>
			
									<a href="<?php echo site_url("admin/settings_trackers_status"); ?>" class="form">
			
									<?php echo $this->lang->line('label_tracker_status');?>
			
									</a></li>
									
									<li <?php echo($this->uri->segment(2)=="settings_trackers_type")?"class='current'":""; ?>>
			
									<a href="<?php echo site_url("admin/settings_trackers_type"); ?>" class="form">
			
									<?php echo $this->lang->line('label_trackers_type');?>
			
									</a></li>																		
		 <?php }else if($this->uri->segment(2) ==="approvals")  //Approval Settings sidebar
				{
				?>
					<li <?php echo($this->uri->segment(3)=="advertisers")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/approvals/advertisers"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_advertisers'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(3)=="publishers")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/approvals/publishers"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_publishers'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(3)=="publisher_share")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/approvals/publisher_share"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_publisher_share'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(3)=="approvals_type")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/approvals/approvals_type"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_approval_type'); ?>

						</a></li>



						<li <?php echo($this->uri->segment(3)=="banners")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/approvals/banners"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_banners'); ?></a></li>
						
						
						
						<li <?php echo($this->uri->segment(3)=="payments")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/approvals/payments"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_payment'); ?></a></li>
						
				
						<li <?php echo($this->uri->segment(3)=="minimum_rate")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/approvals/minimum_rate"); ?>" class="form">

						<?php echo $this->lang->line('label_side_menu_minimum_rate'); ?></a></li>
										
								
			<?php }
 				else if($a[0]=="report")
				{
				?>
						<li <?php echo($this->uri->segment(2)=="report_website")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/report_website"); ?>" class="form">

						Website

						</a></li>
						
						<li <?php echo($this->uri->segment(2)=="report_zone")?"class='current'":""; ?>>

						<a href="<?php echo site_url("admin/report_zone"); ?>" class="form">

						Zones

						</a></li> 
			<?php } ?>			
            </ul>
        </div>
       
        <h2>&nbsp;</h2>
        
	</div>
	
</div><!-- leftmenu -->
