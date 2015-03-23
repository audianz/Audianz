<div class="sidebar">
	<div id="accordion">
        <h3 class="open"><?php echo $this->lang->line('label_sidebar_caption'); ?></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu">
            	<li <?php echo ($this->uri->segment(2)=="dashboard")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/dashboard'); ?>" class="home"><?php echo $this->lang->line('label_home'); ?></a></li>
				<li <?php echo ($this->uri->segment(3)=="map_view")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/storefront/map_view'); ?>" class="myaccount"><?php echo "Storefront Map View"; ?></a></li>
				<li <?php echo ($this->uri->segment(3)=="store_beacon_map")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/storefront/store_beacon_map'); ?>" class="myaccount"><?php echo "Beacon Map View"; ?></a></li>
				<li <?php echo ($this->uri->segment(3)=="show_list")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/storefront/show_list'); ?>" class="myaccount"><?php echo "Storefront List"; ?></a></li>
				<li <?php echo ($this->uri->segment(2)=="myaccount")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/myaccount'); ?>" class="myaccount"><?php echo $this->lang->line('label_myaccount'); ?></a></li>
			</ul>
        </div>
        <h4 style='visibility:hidden;' class="open">Latest News</h4>
        <div style='visibility:hidden;' class="content" style="display: block;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </div>
	</div>

</div><!-- leftmenu -->
