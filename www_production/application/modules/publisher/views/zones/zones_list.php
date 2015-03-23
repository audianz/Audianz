<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/custom/general.js"></script>
<script type="text/javascript">
<?php if(count($zones_list) > 0 AND is_array($zones_list)): ?>
jQuery(document).ready(function() {
	
	jQuery('#zones_list').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
	
});
jQuery('.open').click(function(){
		jQuery('.leftmenu').toggle('fast');
		var current=jQuery('#tag').attr('class');
		if(current=="open")
		{jQuery('#tag').removeClass('open');}
		else
		{jQuery('#tag').addClass('open');}
	});
<?php endif; ?>
</script>
		 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
			 <?php if($page_title != ''): ?>
			<h1 class="pageTitle"><?php echo $this->lang->line("label_inventory_zones_page_title");?></h1>
			 <?php endif; ?> 
			 
			 <?php if($this->session->flashdata('zones_success_message') != ""):?>
							<div class="notification msgsuccess"><a class="close"></a>
							<p><?php echo $this->session->flashdata('zones_success_message'); ?> </p>
							</div>
			 <?php endif;?>
			 <?php echo validation_errors();?>
			 
			<ul class="submenu" style="visibility:hidden;">
				<li class="current"><a href=""><?php echo $this->lang->line("label_all");?></a></li>
				<li><a href=""><?php echo $this->lang->line("label_active");?></a></li>
				<li><a href=""><?php echo $this->lang->line("label_inactive");?></a></li>
			</ul>
			
		<a href="<?php echo site_url("publisher/zones/add_zones"); ?>" class="addNewButton">
			<?php echo $this->lang->line('label_inventory_addzones_fieldset'); ?>
		</a>
		<br /><br />
		<form name="list" id="list" action="<?php echo site_url('publisher/zones/delete_zones');?>" method="post">
		<?php if(count($zones_list) > 0 AND is_array($zones_list)): ?>
		<div style="text-align:left;" class="sTableOptions">
     		<a class="button delete_record"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
                
        </div><!--sTableOptions-->
		<?php endif; ?>
        <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="zones_list">

            <thead>
                <tr>
					<th class="head0"><input type="checkbox" class="checkall" /></th>
                    <th class="head0"><?php echo $this->lang->line("label_zone_name"); ?></th>
                    <th class="head1"><?php echo $this->lang->line("label_zone_model"); ?></th>
                    <th  class="head0"><?php echo $this->lang->line("label_zone_sizes"); ?></th>
                    <th class="head1"><?php echo $this->lang->line("label_zone_imp"); ?></th>
                    <th class="head0"><?php echo $this->lang->line("label_zone_clicks"); ?></th>
					<th class="head1"><?php echo $this->lang->line("label_zone_conversions"); ?></th>
					<th class="head0"><?php echo $this->lang->line("label_zone_ctr"); ?>(%)</th>
                    <th class="head1"><?php echo $this->lang->line("label_zone_revenue"); ?>($)</th>
                    <th class="head0"><?php echo $this->lang->line("label_zone_linked"); ?></th>
                    <th class="head1"><?php echo $this->lang->line("label_zone_invocation"); ?></th>
		    <th class="head0"><?php echo 'RTB';//$this->lang->line('label_inventory_linkedcode');?></th>
                    <th class="head1"><?php echo $this->lang->line("label_inventory_action"); ?></th>
                </tr>
            </thead>
            <colgroup>
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            	<col class="con0" />
				<col class="con1" />		
				<col class="con0" />
                <col class="con1" />
                <col class="con0" />
		<col class="con1" />
                <col class="con0" />
            </colgroup>
            <tbody>
				
				<?php 
					if(count($zones_list) > 0):
					 if(is_array($zones_list)): 
							foreach($zones_list as $row):
				
							if($row->master_zone != "-1"){
								$size = $row->width." X ".$row->height;
							}
							else
							{
								$size  = "";
							}
						
				?>
			
                <tr class="gradeX">
                    <td class="center con1"><input type="checkbox" id="checkvals" value="<?php echo $row->zoneid; ?>" name="check[]"/></td>
                    <td class="con0">
						<a title="<?php echo $this->lang->line("label_edit_zone_details"); ?>" href="<?php echo site_url("publisher/zones/edit_zones/".$row->zoneid.""); ?>">			<?php echo view_text($row->zonename); ?></a> 
					</td>
                    <td class="con1"><?php echo view_text($row->pricing); ?></td>
                    <?php
                    if(($size) == "")
                    {?>
						<td class="con0"><?php echo "&nbsp;&nbsp;-----&nbsp;&nbsp;" ?></td>
					<?php
					}
					else
					{?>
                    <td class="con0"><?php echo $size; ?></td>
					<?php
					}
					?>	
                    <td class="con1"><?php echo (isset($stat_list[$row->zoneid]))?$stat_list[$row->zoneid]['IMP']:"0"; ?></td>
                    <td class="con0"><?php echo (isset($stat_list[$row->zoneid]))?$stat_list[$row->zoneid]['CLK']:"0"; ?></td>
					<td class="con1"><?php echo (isset($stat_list[$row->zoneid]))?$stat_list[$row->zoneid]['CON']:"0"; ?></td>
                    <td align="" class="con0"><?php echo (isset($stat_list[$row->zoneid]))?number_format($stat_list[$row->zoneid]['CTR'],2,".",","):"0.00"; ?></td>
                    <td align="" class="con1"><?php echo (isset($stat_list[$row->zoneid]))?$stat_list[$row->zoneid]['PUBSHARE']:"0.00"; ?></td>
                    <td class="con0"><a href="<?php echo site_url("publisher/zones/linkedbycampaigns/".$row->zoneid."/".$row->affiliateid); ?>"><?php echo $this->lang->line("label_inventory_banners"); ?></a></td>
                    <td class="con1"><a href="<?php echo site_url("publisher/zones/invocation/".$row->zoneid); ?>" class="invocation"><?php echo $this->lang->line("label_inventory_invocation"); ?></a></td>
		    <td class="con0"><?php if($row->rtb==1): ?><img src="<?php echo base_url(); ?>/assets/images/icons/success.png"><?php endif; ?></td>
					<td class="con1"><a href=" javascript:isDeleteZone(<?php echo $row->zoneid;?>)"  class="delete_zones"><?php echo $this->lang->line("label_delete"); ?></a></td>
                 </tr>
				<?php
						endforeach;
						else:
				?>
				<tr class="gradeX">
					<td colspan="10" class="con0" align="center">
					<?php echo $this->lang->line("label_zones_record_not_found"); ?>
					</td>
                </tr>
				<?php	endif;endif;?>
            </tbody>
	</table>


		<script>
		/**
		 * Delete selected items in a table
		**/
		jQuery('.sTableOptions .delete_record').click(function(){
		
				var empt = true;
				
				jQuery('.dyntable input[type=checkbox]').each(function(){
				
						if(jQuery(this).is(':checked')) {
								empt = false;
						}
				});
				
				if(empt == true)
				{
						jAlert('<center><?php echo $this->lang->line("label_no_item_selected"); ?></center>','<?php echo $this->lang->line("label_inventory_zones_page_title");?>');
				} 
				else 
				{
						jConfirm('<?php echo $this->lang->line("label_confirm_delete_selected_zone"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
						//If the result is trur or Ok buttton is clicked
						if(r)
						{
							jQuery("#list").submit();
						}else{
							jQuery(".checkall").attr('checked',false);
							jQuery("input[name=check\\[\\]]").each(function() { jQuery(this).attr('checked',false); });
							jQuery(this).parents('tr').removeClass('selected');
						}
						});
						
				}
		});
		
		function isDeleteZone(zone_id)
		{
			jConfirm('<?php echo $this->lang->line("label_confirm_delete_zone"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
				if(r)
				{
																
							document.location.href='<?php echo site_url("publisher/zones/delete_zones/"); ?>/'+zone_id;
				}			
		});
		}
</script>
