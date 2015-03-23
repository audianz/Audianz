<?php $cur_page = explode("_",$this->uri->segment(2)); ?>
<div class="tabmenu">
	<ul>
		<li
		<?php echo($this->uri->segment(2)=="dashboard" || $this->uri->segment(1)== NULL)?"class='current'":""; ?>>
			<a href="<?php echo site_url('admin/dashboard'); ?>"
			class="dashboard"><span><?php echo $this->lang->line('label_dashboard'); ?>
			</span> </a>
		</li>
		<li
		<?php echo($cur_page[0]== "statistics" || $this->uri->segment(2)=="payment_history" || $this->uri->segment(2)=="smaato_delivery_report")?"class='current'":""; ?>>
			<a href="<?php echo site_url('admin/statistics_advertiser'); ?>"
			class="reports"><span><?php echo $this->lang->line('label_statistics'); ?>
			</span> </a>
		</li>
		<li
		<?php echo($cur_page[0]== "settings" || $cur_page[0]== "site" || $this->uri->segment(2)=="smaato_share_settings")?"class='current'":""; ?>>
			<a href="javascript:void(0);" class="users"><span><?php echo $this->lang->line('label_settings'); ?>
			</span> </a>
			<ul class="subnav">
				<li><a
					href="<?php echo site_url('admin/settings_device_manufacturers'); ?>"><span><?php echo $this->lang->line('label_system_settings'); ?>
					</span> </a></li>
				<li><a href="<?php echo site_url('admin/site_settings'); ?>"><span><?php echo $this->lang->line('label_site_settings'); ?>
					</span> </a></li>
				<!-- <li><a href="<?php echo site_url('admin/user_activity'); ?>"><span><?php echo $this->lang->line('label_activity_settings'); ?></span></a></li> -->
			</ul>
		</li>
		<li <?php echo($cur_page[0]=="inventory")?"class='current'":""; ?>><a
			href="<?php echo site_url('admin/inventory_advertisers'); ?>"
			class="elements"><span><?php echo $this->lang->line('label_inventory'); ?>
			</span> </a>
			<ul class="subnav">
				<li><a href="<?php echo site_url("admin/inventory_advertisers"); ?>"><span><?php echo $this->lang->line('label_advertisers'); ?>
					</span> </a></li>
				<li><a href="<?php echo site_url("admin/inventory_campaigns");; ?>"><span><?php echo $this->lang->line('label_campaigns'); ?>
					</span> </a></li>
				<li><a href="<?php echo site_url("admin/inventory_banners"); ?>"><span><?php echo $this->lang->line('label_banners'); ?>
					</span> </a></li>
				<li><a href="<?php echo site_url("admin/inventory_websites"); ?>"><span><?php echo $this->lang->line('label_websites'); ?>
					</span> </a></li>
				<li><a href="<?php echo site_url("admin/inventory_zones"); ?>"><span><?php echo $this->lang->line('label_zones'); ?>
					</span> </a></li>
				<li><a href="<?php echo site_url("admin/inventory_zones_sdk"); ?>"><span><?php echo $this->lang->line('label_zone_mobile_sdk'); ?>
					</span> </a></li>
			</ul>
		</li>
		<li <?php echo($cur_page[0]== "pages")?"class='current'":""; ?>><a
			href="<?php echo site_url('admin/pages'); ?>" class="reports"><span><?php echo $this->lang->line('label_static_pages'); ?>
			</span> </a></li>
		<li <?php echo($cur_page[0]== "approvals")?"class='current'":""; ?>><a
			href="<?php echo site_url('admin/approvals'); ?>" class="users"><span><?php echo $this->lang->line('label_approvals'); ?>
			</span> </a></li>

		<li <?php echo($cur_page[0]== "beacon")?"class='current'":""; ?>><a
			href="<?php echo site_url('admin/beacon'); ?>" class="users"><span><?php echo $this->lang->line('label_beacon_tab_admin'); ?>
		</span> </a></li>
			

	</ul>
</div>
<!-- tabmenu -->
