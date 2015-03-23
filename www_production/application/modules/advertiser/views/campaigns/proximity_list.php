
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#camptab').dataTable( {
                "bSort": false,
				/*"aaSorting": [],*/
                "sPaginationType": "full_numbers"
	});

});
</script>
<script>
	function delete_campaigns()
	{
		var campArr= jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
			if(campArr!='')
				{
					jConfirm('<?php echo "Are you sure You want to delete these proximity campaigns"; ?>','<?php echo "Manage Campaigns"; ?>',function(r)
					{
						if(r)
						{
							jQuery.post('<?php echo site_url('advertiser/campaigns/remove_camp'); ?>', {'arr[]': campArr}, function(response) {
							location.reload();
							//alert(response);
							
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
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo "Manage Proximity Campaigns"; ?>');
			}  
			
	}
	
	function add_campaign()
	{
		window.location.href="<?php echo base_url();?>index.php/advertiser/campaigns/add_prox_campaign";
	}

	function send_mail()
	{
		window.location.href="<?php echo base_url();?>index.php/advertiser/campaigns/sendMail";
	}

</script>




<h1 class="pageTitle">
	<?php echo "Proximity Campaigns List"; ?>
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
		<a href="javascript:void(0);" onclick="add_campaign();"
			class="iconlink"> <span><?php echo "Add Campaign"; ?>
		</span> </a>
		<a href="javascript:void(0);" onclick="delete_campaigns();"
			class="iconlink"><span><?php echo "Delete " ?>
		</span> </a> 
		<!-- <a href="javascript:void(0);" onclick="send_mail();"
			class="iconlink"> <span><?php echo "Mail"; ?>
		</span> </a>  -->
	</div>
	
</div>



<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="camp">
	<thead>
		<tr>
			<th class="head0"><input type="checkbox" class="checkall" id="checkall" /></th>
			<th class="head1">Campaign Name</th>
			<th class="head0">Pricing Model</th>
			<th class="head1">Message</th>
			<th class="head0">Start Date</th>
			<th class="head1">End Date</th>
			<th class="head1">Landing Image</th>
		</tr>
	</thead>
	<colgroup>
		<col class="con0" width="4%" />
		<col class="con1" width="10%"/>
		<col class="con0" width="10%"/>
		<col class="con1" width="20%"/>
		<col class="con0" width="8%" />
		<col class="con1" width="8%" />
		<col class="con0" width="10%"/>

	</colgroup>

	<tbody>
						<?php 
						
						if(count($list) > 0 && $list != 0):
						$i=1;
						
						foreach($list as $row):
						?>
		
						<tr>
							<td class="con1"><input type="checkbox" id="<?php echo $row->campaign_id; ?>" /></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->camp_name); ?><br /> 
								<a href="<?php echo site_url('advertiser/campaigns/edit_prox_camp/'.$row->campaign_id); ?>"><?php echo "Edit"; ?>
							</td>
							<td style="word-wrap: break-word"><?php echo view_text($row->pricing_model) ; ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->message) ; ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->activate_time) ; ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->end_time); ?>	</td>
							<?php //echo base_url().$row->landing_image ?>
							<td style="word-wrap: break-word"><a href="<?php echo base_url().$row->landing_image ?>" class="view"  >Preview</a></td> 
							</tr>
		
						<?php
						endforeach;
						?>
						<?php endif; ?>
					</tbody> 
				</table>
		</form>
		
		</div>
		
