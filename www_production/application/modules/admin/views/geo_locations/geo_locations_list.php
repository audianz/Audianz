<div style="padding:10px;margin:10px">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
<br/>
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     	<?php endif; ?>   
     
<br/>
<table cellpadding="0" cellspacing="0" id="trackerslist" class="dyntable" width="100%">
          <thead>
                <tr>
            	 	<th class="head0"><?php echo $this->lang->line('label_s_no'); ?></td>
                	<th class="head1"><?php echo $this->lang->line('label_geo_locations'); ?></td>
			<th class="head0"><?php echo $this->lang->line('label_country_code'); ?></th>
			<th class="head1"><?php echo $this->lang->line('label_continent'); ?></th>
			
            	</tr>
	 </thead>
             <colgroup>
                <col class="con0" />
                <col class="con1" />
		<col class="con0" />
                <col class="con1" />
               
            </colgroup>
           
        <tbody>
		<?php 
					if(count($geo_locations_list) > 0 && $geo_locations_list!=''):
						$i=$offset;
						foreach($geo_locations_list as $row):
						?>
						<tr>
									
									<td><?php echo $i++; ?></td>
									<td><?php echo view_text($row->name); ?></td>
									<td><?php echo view_text($row->code); ?></td>
									<td><?php echo view_text($row->continent_name); ?></td>
									<!--<td align="center">
										<a href="<?php echo site_url('admin/settings_geo_locations/edit_geo_location/'.strtolower($row->code));?>">
										<?php echo $this->lang->line("label_edit"); ?></a> &nbsp; 
									<a href="javascript:isDelsingle('<?php echo strtolower($row->code); ?>')">
										<?php echo $this->lang->line("label_delete"); ?></a>
										
									</td>	-->
								</tr>
						<?php
						endforeach;
					else:
				?>
					<tr>
                    <td colspan="7"><?php echo $this->lang->line("label_geo_locations_record_not_found"); ?></td>
                </tr>
				<?php
					endif;
				?>

            	</tbody>

            </table>
			</div>
					  <script type="text/javascript">
					/**
					 * Delete selected items in a table
					**/
					jQuery('.sTableOptions .delete_record').click(function(){
						var empt = true;
						jQuery('.sTable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<?php echo $this->lang->line("alert_no_item_selected");?>');
						} else {
							
							jConfirm('<?php echo $this->lang->line("confirmation_geo_locations_sel_deletion"); ?>','<?php echo $this->lang->line("confirmation_select_delete_geo_locations_title"); ?>',function(r){
							
							if(r)
							{
								jQuery("#frmGeoLocationsList").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
									unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
						}
						
					});
					
					function isDelsingle(code)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_delete_geo_locations"); ?>','<?php echo $this->lang->line("confirmation_deletion_geo_locations_title"); ?>',
					function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url("admin/settings_geo_locations/delete_geo_location/");?>/'+code;	
					}				
					});
					}
					
					
	
					
					function confirm_status()
					{
						var confirm_status = confirm('<?php echo $this->lang->line("confirmation_status_geo_locations"); ?>');
						if(confirm_status)
						{
							return true;
						}else{
							return false;
						}
					}

<?php if(count($geo_locations_list) > 0 && $geo_locations_list!=''):?>

jQuery(document).ready(function() {
	jQuery('#trackerslist').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
});

<?php endif;?>
					
	</script>

