		<script type="text/javascript">
	jQuery(document).ready(function() {
	jQuery('#store_list').dataTable( {
                "bSort": false,
				/*"aaSorting": [],*/
                "sPaginationType": "full_numbers"
	});

});
			function goToRadius()
			{
	
				document.getElementById("storefront_form").action='<?php echo site_url('advertiser/storefront/updateRadius'); ?>';
				document.getElementById("storefront_form").submit();
			   
			}

	</script>

	<script>
				
			
	function goToImportFile()
	{
		document.getElementById("storefront_form").action='<?php echo site_url('advertiser/storefront/storefront_import_file'); ?>';
		document.getElementById("storefront_form").submit();
	   
			
	</script>

		<div style="padding: 2px; margin: 1px">
			<script type="text/javascript"
				src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
				
			
		
			<script>
				function add_store()
			{
				window.location.href="<?php echo base_url();?>index.php/advertiser/storefront/add_store";
			}
			
			</script>
			
			<script>
			function delstores()
			{ 
				//alert("called");
				var storearr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
				if(storearr!='')
				{
					//alert(storearr);
					
					jConfirm('<?php echo "Are you sure You want to delete these stores"; ?>','<?php echo "Manage Locations"; ?>',function(r)
					{
						if(r)
						{
							
							jQuery.post('<?php echo site_url('advertiser/storefront/delete_stores'); ?>', {'arr[]': storearr}, function(response) {
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
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo "Manage Locations"; ?>');
			} 
			}
			</script>
			<!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
		
			<h1 class="pageTitle">
				<?php echo "Storefront Details"; ?>
			</h1>
		
			<?php if($this->session->flashdata('message') !=''): ?>
			<div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('message'); ?>
				</p>
			</div>
			<?php endif; ?>
			<?php if($this->session->flashdata('error_msg') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('error_msg'); ?>
				</p>
			</div>
			<?php endif; ?>
			<?php  if($this->session->flashdata('storefront_file_chk') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('storefront_file_chk'); ?>
				</p>
			</div>
			<?php endif; ?>
			<?php  if($this->session->flashdata('error_head_field_details_msg') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('error_head_field_details_msg'); ?>
				</p>
			</div>
			<?php endif; ?>
			<?php  if($this->session->flashdata('poi_name_check') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('poi_name_check'); ?>
				</p>
			</div>
			<?php endif; ?>
			<?php  if($this->session->flashdata('updateErr') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('updateErr'); ?>
				</p>
			</div>
			<?php endif; ?>
			<?php  if($this->session->flashdata('invalidRadius') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('invalidRadius'); ?>
				</p>
			</div>
			<?php endif; ?>
			
			<?php  if($this->session->flashdata('largeRadius') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('largeRadius'); ?>
				</p>
			</div>
			<?php endif; ?>
			
			<?php  if($this->session->flashdata('negativeRadius') !=''): ?>
			<div class="notification msgerror">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('negativeRadius'); ?>
				</p>
			</div>
			<?php endif; ?>
			
			<?php if($this->session->flashdata('updateSuccess') !=''): ?>
			<div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('updateSuccess'); ?>
				</p>
			</div>
			<?php endif; ?>
		
			<form enctype="multipart/form-data" name="storefront_form"
				id="storefront_form" method="post">
				<div class="form_default">
					<p>
						<label><?php echo "Radius";?> </label>
					</p>
					<input type="text" id="txtradius" name="txtradius"
						value=<?php if(count($radius)==0) echo "0";else foreach ($radius as $rad):echo $rad->radius; endforeach; ?>>
					<button type="button" style="margin-left: 10px;"
						onclick="javascript:goToRadius();">
						<?php echo "Update"; ?>
					</button>
					</br>
					<p>
						<label><?php echo "Storefront Name"; ?> </label> 
						<input type="file"	name="storefront" id="storefront" value="1">&nbsp;&nbsp;
						<button type="button" onclick="javascript:goToImportFile();"
							style="margin-left: 10px;">
							<?php echo $this->lang->line('label_upload'); ?>
						</button>
					
						
					
					
		<div class="notification msginfo" style="width: 18%; margin-left: 60%; padding: 5px; background: #C7D6EC; font-size: 10px; margin-top: -53px; margin-bottom: 4px; text-align: center;">
		<a	href="<?php echo site_url('advertiser/storefront/storefront_download_file');  ?>">Download SampleFile.csv </a>
		</div>
		
		</p>
		
				</div>
				</br>
				
				<div id="buttons" style="width: 99%; height: 40px;">
			<div style="width: 45%; float: left;">
				<a href="javascript:void(0);" onclick="add_store();" class="iconlink">
				<span><?php echo "Add";//$this->lang->line('label_run_campaign');?>
				</span> </a> 
				<a href="javascript:void(0);" onclick="delstores();"
					class="iconlink"> <span><?php echo "Delete"?>
				</span> </a> 
				<!-- <a href="javascript:void(0);" onclick="delstores();" class="iconlink"> <span><?php echo "Edit"; ?>
				</span> </a> -->
			</div>
			
		</div>
		
				<br />
				<table cellpadding="0" cellspacing="0" class="dyntable" id="store_list"	width="100%">
				 	<thead>
						<tr>
							<th class="head1"><input type="checkbox" class="checkall" id="checkall" /></th>
							<th class="head0"><?php echo "POI Name"; ?></th>
							<th class="head1"><?php echo "Address"; ?></th>
							<th class="head0"><?php echo "City/State"; ?></th>
							<th class="head1"><?php echo "PIN"; ?></th>
							<th class="head0"><?php echo "Country"; ?></th>
							<th class="head1"><?php echo "Tel"; ?></th>
							<th class="head0"><?php echo "Lat/Lon"; ?></th>
							<th class="head1"><?php echo "Aisle/Shelf/Floor"; ?></th>
							<th class="head0"><?php echo "Location"; ?></th>
							</tr>
					</thead>
					<colgroup>
						<col class="con0" width="6%" />
						<col class="con1" width="10%" />
						<col class="con0" width="10%" />
						<col class="con1" width="10%" />
						<col class="con0" width="10%" />
						<col class="con1" width="10%" />
						<col class="con0" width="7%" />
						<col class="con1" width="10%" />
						<col class="con0" width="9%" />
						<col class="con1"  />
						
					</colgroup>
		
		 
					<tbody>
						<?php 
						
						if(count($storefront_list) > 0 && $storefront_list != 0):
						$i=1;
						
						foreach($storefront_list as $row):
						?>
		
						<tr>
							<td class="con1"><input type="checkbox" id="<?php echo $row->id; ?>" /></td>
							<td style="word-wrap: break-word"><?php echo ($row->poi_name); ?><br /> 
							<a href="<?php echo site_url('advertiser/storefront/edit/'.$row->id); ?>"><?php echo $this->lang->line('label_advertiser_campaign_edit'); ?></a>-<a href="<?php echo site_url('advertiser/storefront/get_map_view/'.$row->id); ?>"><?php echo "Map View"; ?></a>
							</td>
							<td style="word-wrap: break-word"><?php echo ($row->address1); ?><?php if($row->address2!=NULL) { ?> /<?php } ?><?php echo ($row->address2); ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->city) ; ?><?php if($row->state!=NULL) { ?> /<?php } ?><?php echo view_text($row->state); ?>	</td>
							<td style="word-wrap: break-word"><?php echo view_text($row->pin); ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->country); ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->tel); ?></td> 
							<td style="word-wrap: break-word"><?php echo view_text($row->lat); ?><?php if($row->lon!=NULL) { ?> /<?php } ?><?php echo view_text($row->lon); ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->aisle); ?><?php if($row->shelf!=NULL) { ?> /<?php } ?><?php echo view_text($row->shelf); ?><?php if($row->floor!=NULL) { ?> /<?php } ?><?php echo view_text($row->floor); ?></td>
							<td style="word-wrap: break-word"><?php echo view_text($row->location_01); ?><?php if($row->location_02!=NULL) { ?> /<?php } ?><?php echo view_text($row->location_02); ?><?php if($row->location_03!=NULL) { ?> /<?php } ?><?php echo view_text($row->location_03); ?></td>
						</tr>
		
						<?php
						endforeach;
					//	else:
						?>
						<!-- <tr>
							<td colspan="7" align="center"><?php echo "No Records Found"; ?></td>
						</tr>  -->  
						<?php endif; ?>
					</tbody> 
				</table>
		
		
			</form>
		
		</div>
		
	
