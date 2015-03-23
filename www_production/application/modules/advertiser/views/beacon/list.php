<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#beacontab').dataTable( {
                "bSort": false,
				/*"aaSorting": [],*/
                "sPaginationType": "full_numbers"
	});

});
</script>
<script>
	// To delete selected beacons from the data table.
	function delete_beacons()
			{ 
				
				var beaconArr= jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
				//alert(beaconArr);
				if(beaconArr!='')
				{
					jConfirm('<?php echo "Are you sure You want to delete these stores"; ?>','<?php echo "Manage Locations"; ?>',function(r)
					{
						if(r)
						{
							jQuery.post('<?php echo site_url('advertiser/beacon/delete_beacons'); ?>', {'arr[]': beaconArr}, function(response) {
							location.reload();
	                });
	            }
	            else
	            {
	                document.getElementById('checkall').checked = false;
	               
	                    jQuery('input[type=checkbox]').each(function(){
	                            jQuery(this).attr('checked',false);
	                            jQuery(this).parents('tr').removeClass('selected');
	                    });
	               
	            }
					});
			}
			else
			{
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo "Manage Locations"; ?>');
			}  
			
			}
			
	//To change beacon status to ACTIVE
	
	function activate_beacons()
			{ 
				
				var beaconArr= jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
		
				if(beaconArr!='')
				{
					jConfirm('<?php echo "Are you sure you want to change status of selected beacons?"; ?>','<?php echo "Manage Beacon Status"; ?>',function(r)
					{
						if(r)
						{
							jQuery.post('<?php echo site_url('advertiser/beacon/active_status'); ?>', {'arr[]': beaconArr}, function(response) {
							location.reload();
	                });
	            }
	            else
	            {
	                document.getElementById('checkall').checked = false;
	               
	                    jQuery('input[type=checkbox]').each(function(){
	                            jQuery(this).attr('checked',false);
	                            jQuery(this).parents('tr').removeClass('selected');
	                    });
	               
	            }
					});
			}
			else
			{
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo "Manage Beacon Status"; ?>');
			}  
			
			}
			
			
	//To change beacon status to PASSIVE
	
	function deactivate_beacons()
			{ 
				
				var beaconArr= jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
				//alert(beaconArr);
				if(beaconArr!='')
				{
					jConfirm('<?php echo "Are you sure you want to change status  of selected beacons?"; ?>','<?php echo "Manage Beacon Status"; ?>',function(r)
					{
						if(r)
						{
							jQuery.post('<?php echo site_url('advertiser/beacon/passive_status'); ?>', {'arr[]': beaconArr}, function(response) {
							location.reload();
	                });
	            }
	            else
	            {
	                document.getElementById('checkall').checked = false;
	               
	                    jQuery('input[type=checkbox]').each(function(){
	                            jQuery(this).attr('checked',false);
	                            jQuery(this).parents('tr').removeClass('selected');
	                    });
	               
	            }
					});
			}
			else
			{
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo "Manage Beacon status"; ?>');
			}  
			
			}
</script>

<h1 class="pageTitle">
	<?php echo "Beacon List"; ?>
</h1>

			<?php if($this->session->flashdata('message') !=''): ?>
			<div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('message'); ?>
				</p>
			</div>
			<?php endif; ?>
<br />
<div id="buttons" style="width: 99%; height: 40px;">
	<div style="width: 45%; float: left;">
	<!--<a href="javascript:void(0);" onclick="delete_beacons();" class="iconlink"><span><?php echo "Delete" ?> 
		</span> </a> -->
		<a href="javascript:void(0);" onclick="activate_beacons();"
			class="iconlink"><span><?php echo "Active " ?>
		</span> </a> <a href="javascript:void(0);" onclick="deactivate_beacons();"
			class="iconlink"> <span><?php echo "Passive"; ?>
		</span> </a> 
	</div>
	
</div>

<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="beacontab">
	<thead>
		<tr>
			<th class="head0"><input type="checkbox" class="checkall" id="checkall" /></th>
			<th class="head1">Beacon Name</th>
			<th class="head0">UUID</th>
			<th class="head1">Beacon Description</th>
			<th class="head0">Major Id</th>
			<th class="head1">Minor Id</th>
			<th class="head0">Client Name</th>
			<th class="head1">Beacon Location</th>
			<th class="head0">Beacon Status</th>
		</tr>
	</thead>
	<colgroup>
		<col class="con0" width="4%" />
		<col class="con1" width="18%"/>
		<col class="con0" width="14%"/>
		<col class="con1" width="20%"/>
		<col class="con0" width="8%" />
		<col class="con1" width="8%" />
		<col class="con0" width="10%"/>
		<col class="con1" width="12%" />
	</colgroup>

	<tbody>
						<?php 
						
						if(count($list) > 0 && $list != 0):
						$i=1;
						
						foreach($list as $row):
						?>
		
						<tr>
							<td class="con1"><input type="checkbox" id="<?php echo $row->Beacon_Seq_ID; ?>" /></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->Beacon_name); ?><br /> 
								<a href="<?php echo site_url('advertiser/beacon/configure_beacon/'.$row->Beacon_Seq_ID); ?>"><?php echo "Configure"; ?></a> - <a href="<?php echo site_url('advertiser/beacon/attach_actions/'.$row->Beacon_Seq_ID)  ?>">Attach Action</a>
							</td>
							<td style="word-wrap: break-word"><?php echo view_text($row->Beacon_UUID) ; ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->description) ; ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->Beacon_Major_ID) ; ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->Beacon_Minor_ID); ?>	</td>
							<td style="word-wrap: break-word"><?php echo $this->session->userdata('session_advertiser_name'); ?></td> 
							<td style="word-wrap: break-word"><?php echo view_text($row->location); ?>	</td>
							<td style="word-wrap: break-word"><?php if($row->Beacon_Status == 1 ) echo "Active"; else echo "Passive"; ?></td>
						</tr>
		
						<?php
						endforeach;
						?>
						<?php endif; ?>
					</tbody> 
				</table>
		</form>
		
		</div>
		
