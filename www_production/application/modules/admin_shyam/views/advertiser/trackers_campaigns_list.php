	 <?php if($this->session->flashdata('success_message') != ''): ?><div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('success_message'); ?></p></div><?php endif; ?>
	 
	 <?php if($this->session->flashdata('error_message') != ''): ?><div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('error_message'); ?></p></div><?php endif; ?>

	 <?php if($page_title != ''): ?><h1 class="pageTitle"><?php echo $page_title; ?></h1><?php endif; ?>   
		<br />
        <form id="frmTrackerCampaignsList" action="<?php echo site_url('admin/inventory_advertisers/tracker_campaign_process/link'); ?>" method="post" >
        <div <?php echo ($campaigns_list == FALSE)?"style='display:none'":""; ?> class="sTableOptions">
        <?php echo $this->pagination->create_links(); ?><a class="button save"><span><?php echo $this->lang->line('label_save_update'); ?></span></a>
        </div><!--sTableOptions-->

		<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="5%" />
                <col class="head1" width="25%" />
                <col class="head0" width="10%" />
                <col class="head1" width="20%" />
                <col class="head0" width="20%" />
				<col class="head1" width="20%" />
		    </colgroup>
			<tr>
            	<td align="center"><input type="checkbox" class="checkall" /></td>
                <td><?php echo $this->lang->line('label_campaign_name'); ?></td>
                <td><?php echo $this->lang->line('label_campaign_id'); ?></td>
                <td><?php echo $this->lang->line('label_conversion_view'); ?></td>
				<td><?php echo $this->lang->line('label_conversion_click'); ?></td>
		        <td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div  class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                 <colgroup>
                    <col class="con0" width="5%" />
                    <col class="con1" width="25%" />
                    <col class="con0" width="10%" />
                    <col class="con1" width="20%" />
                    <col class="con0" width="20%" />
					<col class="con1" width="20%" />
                </colgroup>
				<?php 
					if(count($campaigns_list) >0 AND $campaigns_list !=FALSE ):
					foreach($campaigns_list as $row):
				?>
				<tr>
					<td align="center" id="listing"><input type="checkbox" id="sel_campaign[]" name="sel_campaign[] " value="<?php echo $row->campaign_id; ?>" <?php echo ($row->linked_campaign == 1)?"checked='checked'":""; ?> /></td>
					<td><?php echo view_text($row->campaign_name); ?></td>
					<td><?php echo $row->campaign_id; ?></td>
					<td><?php echo view_text($row->views); ?> <?php echo $this->lang->line('label_seconds'); ?></td>
					<td><?php echo view_text($row->clicks); ?> <?php echo $this->lang->line('label_seconds'); ?></td>
					<td align="center"><a href="<?php echo site_url("admin/inventory_advertisers/trackers_linked_campaigns_time_settings/$sel_status/$sel_advertiser_id/$sel_tracker_id/".$row->campaign_id); ?>"><?php echo $this->lang->line('label_update_time_settings'); ?></a> &nbsp;</td>
				</tr>
				<?php
					endforeach;
					else:
				?>
				<tr>
                    <td align="center" colspan="7"><?php echo $this->lang->line("label_trackers_campaigns_record_not_found"); ?></td>
                </tr>
				<?php endif; ?>
		 	</table>
		   </div><!--sTableWrapper-->

		   <input type="hidden" name="sel_advertiser_id" id="sel_advertiser_id" value="<?php echo $sel_advertiser_id; ?>" />
		   <input type="hidden" name="sel_tracker_id" id="sel_tracker_id" value="<?php echo $sel_tracker_id; ?>" />
		   <input type="hidden" name="sel_status" id="sel_status" value="<?php echo $sel_status; ?>" />
	     
		   <input type="hidden" name="list_campid" id="list_campid" value="" />
		 </form>
		
		 <script type="text/javascript">
		 
				function updateSingle(sadid, tid, id){
					
					jConfirm('<?php echo $this->lang->line("lang_tracker_camapaign_link"); ?>','<?php echo $this->lang->line("label_confirm_delete"); ?>',function(r){
					
					if(r)
					{
						document.location.href='<?php echo site_url("admin/inventory_advertisers/tracker_campaign_process/link"); ?>/'+sadid+'/'+tid+'/'+id;
					}else{
						jQuery(".checkall").attr('checked',false);
					}
							unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
					});
					
				}
			</script>
			<script type="text/javascript">
					/**
					 * Delete selected items in a table
					**/
					jQuery('.sTableOptions .save').click(function(){
						var empt = true;
						jQuery('.sTable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
								jConfirm('<?php echo $this->lang->line("alert_no_item_selected");?>','<?php echo $this->lang->line("label_confirm_delete"); ?>',
								function(r){
								if(r) {
									var unchecked	=[];
									jQuery("#userlist :input:not(:checked)").each(function() { unchecked.push(jQuery(this).val()); });
									jQuery('#list_campid').val(unchecked);
									jQuery("#frmTrackerCampaignsList").submit();
								} else { 
									jQuery('#list_campid').val('');
									jQuery(".checkall").attr('checked',false);
									unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.
									document.location.href='';
								}
								});
						} 
						else 
						{
							jConfirm('<?php echo $this->lang->line("lang_tracker_camapaign_link"); ?>','<?php echo $page_title; ?>',function(r){
					
							if(r)
							{
								var unchecked	=[];
								jQuery("#userlist :input:not(:checked)").each(function() { unchecked.push(jQuery(this).val()); });
								jQuery('#list_campid').val(unchecked);
								//alert(jQuery('#list_campid').val()); return false;
								jQuery("#frmTrackerCampaignsList").submit();
							}
							else
							{
								jQuery('#list_campid').val('');
								jQuery(".checkall").attr('checked',false);
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.
								document.location.href='';
							}
													
							});
							
						}
					});	
	</script>
	<script type="text/javascript">
	
		function getlimitations()
		{			
			var headerRow  =jQuery('tr:first', '#userlist');
			var first	   =jQuery("input[type=checkbox]:first", headerRow).val();

			var footerRow  =jQuery('tr:last', '#userlist');
			var last	   =jQuery("input[type=checkbox]:first", footerRow).val();
			
			var footerRow  =jQuery('tr:last', '#userlist');
			var last	   =jQuery("input[type=checkbox]:first", footerRow).val();
			
			jQuery('#first_campid').val(first);
			jQuery('#last_campid').val(last);		
		}

 		//getlimitations();
	</script>