<div class="sidebar">
	<div id="accordion">
        <h3 class="open"><?php echo $this->lang->line('label_sidebar_caption'); ?></h3>
        <div class="content" style="display: block;">
        	<ul class="leftmenu">
            	<li <?php echo ($this->uri->segment(2)=="dashboard")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/dashboard'); ?>" class="home"><?php echo $this->lang->line('label_home'); ?></a></li>
	<!--			<li <?php echo ($this->uri->segment(2)=="campaign_list")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns'); ?>" class="table"><?php echo "Campaigns"//$this->lang->line('label_report'); ?></a></li>
				<li <?php echo ($this->uri->segment(3)=="proximity_camp")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns/proximity_camp'); ?>" class="editor"><?php echo "Create Proximity Campaign "; ?></a></li>
				<li <?php echo ($this->uri->segment(3)=="proximity_list")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns/proximity_list'); ?>" class="editor"><?php echo "Proximity Campaigns List "; ?></a></li>
				<li <?php echo ($this->uri->segment(3)=="proximity")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns/proximity'); ?>" class="editor"><?php echo "Proximity Campaign "; ?></a></li>  -->
				<li <?php echo ($this->uri->segment(2)=="myaccount")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/myaccount'); ?>" class="myaccount"><?php echo $this->lang->line('label_myaccount'); ?></a></li> 
			<!--	<li <?php echo ($this->uri->segment(2)=="camp_optimisation")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/camp_optimisation'); ?>" class="myaccount"><?php echo "Manage Campaign Optimisation"; ?></a></li> -->
			</ul>
        </div>
        <h3 style='visibility:hidden;' class="open">Latest News</h3>
        <div style='visibility:hidden;' class="content" style="display: block;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </div>
	</div>

</div><!-- leftmenu -->

