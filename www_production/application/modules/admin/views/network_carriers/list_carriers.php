<div style="padding:10px;margin:10px">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	jQuery(document).ready(function() {
			jQuery('#carriers_list').dataTable( {
			"sPaginationType": "full_numbers"
			});
			
		});
	</script>
 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->

	 <?php if($page_title != ''): ?><h1 class="pageTitle"><?php echo "Network Carrier Details"; ?></h1><?php endif; ?>   
   
	<?php if($this->session->flashdata('message') !=''): ?> <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('message'); ?></p></div><?php endif; ?>
	
	<?php if($this->session->flashdata('error_msg') !=''): ?> <div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('error_msg'); ?></p></div><?php endif; ?>
	<?php  if($this->session->flashdata('operator_name_file_chk') !=''): ?><div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('operator_name_file_chk'); ?></p></div><?php endif; ?>

	<?php
	$mass_set_session	= array(
								"error_carrier_name",
								"error_start_ip",
								"error_end_ip",
								"error_head_field_details"
							);
	$user_data = $this->session->all_userdata();
	//print_r($user_data); exit;
	foreach ($user_data as $key => $value) {
		if (in_array($key, $mass_set_session)) {
			

			if($this->session->userdata($key) != ""){
			$error_value=$this->session->userdata($key);
			}
			if($this->session->flashdata($key."_msg") != ""):
			?>
			<div class="notification msgerror"><a class="close"></a>
			<p><?php echo $this->session->flashdata($key."_msg");
			
			if(count($error_value) == 1)
			{
				echo $error_value[0];
			}
			else
			{
				for($ev=0;$ev<count($error_value);$ev++)
				{	
					if($ev != (count($error_value)-1))
					{
						echo $error_value[$ev].',';
					}
					else
					{
						echo $error_value[$ev];
					}
				}
			}
			?>
			</p>
			</div>
		<?php
			endif;
		}
	}
			?>
					
			
			
<form enctype="multipart/form-data" name ="network_carriers_form"  method="post" action="<?php echo site_url('admin/settings_network_carriers/carriers_import_file');  ?>">
<div class="form_default">
<p>
<label><?php echo "Network Carrier Name"; ?></label>
<input type="file" name="carriers" id="carriers" value="1">&nbsp;&nbsp; 
<button style="margin-left:10px;"><?php echo $this->lang->line('label_upload'); ?></button>
<div class="notification msginfo"  style="width:18%;margin-left:553px;padding:5px;background:#C7D6EC;font-size:10px;margin-top:-53px;margin-bottom: 4px;text-align: center;" >
	<a href="<?php echo site_url('admin/settings_network_carriers/geo_operators_download_file');  ?>"> Download SampleFile.csv 
	</a></div>	
</p>
</div>
<br/>
<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
            <colgroup>
            	<col class="head0" width="5%" />
                <col class="head1" width="15%" />
                <col class="head0" width="20%" />
                <col class="head1" width="20%" />
                <col class="head0" width="20%" />
				<col class="head1" width="20%" />
            </colgroup>
            <tr>
            	<td><?php echo $this->lang->line('label_s_no'); ?></td>
                <td><?php echo "Name"; ?></td>
				<td><?php echo "Country"; ?></td>
				<td>Start_Ip_Address</td>
				<td>End_Ip_Address</td>
                <td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
</table>

<div class="sTableWrapper">
<table cellpadding="0" cellspacing="0" class="dyntable" id="carriers_list" width="100%">
<colgroup>
<col class="con0" width="5%"/>
<col class="con1" width="15%"/>
<col class="con0" width="20%"/>
<col class="con1" width="20%"/>
<col class="con0" width="20%"/>
<col class="con1" width="20%"/>
</colgroup>
<?php 
	if(count($carriers_list) > 0 && $carriers_list != ''):
	$i=$offset;
	foreach($carriers_list as $row):
?>
<tr>
<td><?php echo $i++."." ?></td>
<td><?php echo view_text($row->carriername); ?></td>
<td><?php echo view_text($row->country); ?></td>
<td><?php echo view_text($row->start_ip); ?></td>
<td><?php echo view_text($row->end_ip); ?></td>
<td align="center">
<a href="<?php echo site_url('admin/settings_network_carriers/edit_carriers/'.$row->id);?>"><?php echo $this->lang->line("label_edit"); ?></a>&nbsp;<a href="javascript:isDelsingle(<?php echo $row->id; ?>)">
<?php echo $this->lang->line("label_delete"); ?></a>
</td>
<?php
	endforeach;
	else:
?>
<tr>
  <td colspan="7" align="center"><?php echo "No Reacords Found"; ?></td>
</tr>
<?php endif; ?>

</table>
</div><!--sTableWrapper-->
</form>
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
							
							jConfirm('<?php echo $this->lang->line("confirmation_geo_operator_sel_deletion"); ?>','<?php echo $this->lang->line("confirmation_select_delete_geo_operator_title"); ?>',function(r){
							
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
						jConfirm('<?php echo $this->lang->line("confirmation_delete_geo_operator"); ?>','<?php echo $this->lang->line("confirmation_deletion_geo_operator_title"); ?>',
					function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url("admin/settings_network_carriers/delete_carriers/");?>/'+code;	
					}				
					});
					}
					
					function confirm_status()
					{
						var confirm_status = confirm('<?php echo $this->lang->line("confirmation_status_geo_operator"); ?>');
						if(confirm_status)
						{
							return true;
						}else{
							return false;
						}
					}

</script>
