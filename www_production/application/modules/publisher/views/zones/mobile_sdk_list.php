<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/"; ?>js/custom/general.js"></script>
<script type="text/javascript">
<?php if(count($zones_list) > 0 AND is_array($zones_list)): ?>
jQuery(document).ready(function() {
	
	jQuery('#zones_list').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
});
<?php endif; ?>
</script>
		 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
			 <?php if($page_title != ''): ?>
			<h1 class="pageTitle"><?php echo $this->lang->line("label_inventory_mobile_page_title");?></h1>
			 <?php endif; ?> 
			 
			 <?php 
					if($this->session->flashdata('zones_success_message') != ""):
		   ?>
							<div class="notification msgsuccess"><a class="close"></a>
							<p><?php echo $this->session->flashdata('zones_success_message'); ?> </p>
							</div>
		<?php
					endif;?>
					<?php echo validation_errors();?>   
     
        
          <ul class="submenu" style="visibility:hidden;">
        	<li class="current"><a href=""><?php echo $this->lang->line("label_all");?></a></li>
            <li><a href=""><?php echo $this->lang->line("label_active");?></a></li>
            <li><a href=""><?php echo $this->lang->line("label_inactive");?></a></li>
        </ul>
<!--		<a href="<?php //echo site_url("publisher/zones/add_zones"); ?>" class="addNewButton"><?php //echo $this->lang->line('label_inventory_addzones_fieldset'); ?></a>
-->		
		<form name="list" id="list" action="<?php echo site_url('publisher/zones/delete_zones');?>" method="post">
		<?php if(count($zones_list) > 0 AND is_array($zones_list)): ?>
		<!--<div style="text-align:left;" class="sTableOptions">
     		<a class="button delete_record"><span><?php //echo $this->lang->line("label_delete"); ?></span></a>
                
        </div>--><!--sTableOptions-->
		<?php endif; ?>
        <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="zones_list">

            <thead>
                <tr>
                   <th class="head0"><input type="checkbox" class="checkall" />
                      </th>
                    <th class="head0"><?php echo $this->lang->line("label_zone_name"); ?></th>
                    <th class="head1"><?php echo $this->lang->line("label_zone_mobile_sdk"); ?></th>
                   
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
                     <th class="center con1" width="10"><input type="checkbox" id="checkvals" value="<?php echo $row->zoneid; ?>" name="check[]"/>
                      </th>
                    <td class="con0">
							
								<?php echo view_text($row->zonename); ?>
							
					</td>
                    <td class="con1"><a href="<?php echo site_url("publisher/mobile_sdk/edit_mobile_sdk/".$row->zoneid.""); ?>">Mobile SDK</a> </td>
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
				<?php
					endif;
					endif;
				?>
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
