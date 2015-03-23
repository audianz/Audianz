<?php $cur_page = explode("_",$this->uri->segment(2)); ?>
<div class="tabmenu">
    	<ul>
            <li <?php echo($cur_page[0]=="dashboard")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/dashboard'); ?>" class="dashboard"><span>Dashboard</span></a></li>
            <li  <?php echo($cur_page[0]=="campaigns")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns'); ?>" class="reports"><span>Campaign</span></a></li>
	        <li <?php echo($cur_page[0]== "performance" || $cur_page[0]== "store" )?"class='current'":""; ?>>
		 
		<a href="javascript:void(0);" class='reports'><span>Reports</span></a>
      <ul class="subnav">
	    <li><a href="<?php echo site_url('advertiser/performance_report'); ?>" ><span>Reports</span></a></li>
	  	<li><a href="<?php echo site_url('advertiser/store_report'); ?>"><span>Storewise Reports</span></a></li>
	  	
	  </ul>
	        <li  <?php echo($cur_page[0]=="storefront")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/storefront/map_view'); ?>" class="reports"><span>Location Manager</span></a></li>
	        <li  <?php echo($cur_page[0]=="beacon")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/beacon'); ?>" class="beaconManager"><span>Beacon Manager</span></a></li>
        </ul>
</div><!-- tabmenu -->
