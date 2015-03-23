<div style="padding:10px;margin:10px">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->

	 <?php if($page_title != ''): ?><h1 class="pageTitle"><?php echo $page_title; ?></h1><?php endif; ?>   
   
	<?php if($this->session->flashdata('message') !=''): ?> <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('message'); ?></p></div><?php endif; ?>
	
	<?php if($this->session->flashdata('error_msg') !=''): ?> <div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('error_msg'); ?></p></div><?php endif; ?>
	<?php  if($this->session->flashdata('operator_name_file_chk') !=''): ?><div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('operator_name_file_chk'); ?></p></div><?php endif; ?>

	<?php 
			if($this->session->userdata('error_telecom_name') != ""){
			$error_telecom_name=$this->session->userdata('error_telecom_name');}
			if($this->session->flashdata('operator_name_check') != ""):
			?>
			<div class="notification msgerror"><a class="close"></a>
			<p><?php echo $this->session->flashdata('operator_name_check');
			if(count($error_telecom_name) == 1)
			{
				echo $error_telecom_name[0];
			}
			else
			{
				for($f=0;$f<count($error_telecom_name);$f++)
				{	
					if($f != (count($error_telecom_name)-1))
					{
						echo $error_telecom_name[$f].',';
					}
					else
					{
						echo $error_telecom_name[$f];
					}
				}
			}
			?></p>
			</div>
			<?php endif; ?>
<form enctype="multipart/form-data" name ="geo_operators_form"  method="post" action="<?php echo site_url('admin/settings_geo_operators/geo_operators_import_file');  ?>">
<div class="form_default">
<p>
<label><?php echo $this->lang->line('label_geo_operators'); ?></label>
<input type="file" name="geo_operators" id="geo_operators" value="1">&nbsp;&nbsp; 
<button style="margin-left:10px;"><?php echo $this->lang->line('label_upload'); ?></button>
<div class="notification msginfo"  style="width:18%;margin-left:553px;padding:5px;background:#C7D6EC;font-size:10px;margin-top:-53px;margin-bottom: 4px;text-align: center;" >
	<a href="<?php echo site_url('admin/settings_geo_operators/geo_operators_download_file');  ?>"> Download SampleFile.csv 
	</a></div>	
</p>
</div>
<br/>
<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
            <colgroup>
            	<col class="head0" width="10%" />
                <col class="head1" width="35%" />
                <col class="head0" width="35%" />
				<col class="head1" width="20%" />
            </colgroup>
            <tr>
            	<td><?php echo $this->lang->line('label_s_no'); ?></td>
                <td><?php echo $this->lang->line('label_geo_operators'); ?></td>
				<td><?php echo $this->lang->line('label_geo_operators_value'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
</table>

<div class="sTableWrapper">
<table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
<colgroup>
<col class="con0" width="10%"/>
<col class="con1" width="35%"/>
<col class="con0" width="35%"/>
<col class="con1" width="20%"/>
</colgroup>
<?php 
	if(count($geo_operators_list) > 0 && $geo_operators_list != ''):
	$i=$offset;
	foreach($geo_operators_list as $row):
?>
<tr>
<td><?php echo $i++."." ?></td>
<td><?php echo view_text($row->telecom_name); ?></td>
<td><?php echo view_text($row->telecom_value); ?></td>
<td align="center">
<a href="<?php echo site_url('admin/settings_geo_operators/edit_geo_operators/'.$row->telecom_id);?>"><?php echo $this->lang->line("label_edit"); ?></a>&nbsp;<a href="javascript:isDelsingle(<?php echo $row->telecom_id; ?>)">
<?php echo $this->lang->line("label_delete"); ?></a>
</td>
<?php
	endforeach;
	else:
?>
<tr>
  <td colspan="7" align="center"><?php echo $this->lang->line("label_geo_operators_record_not_found"); ?></td>
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
					document.location.href	= '<?php echo site_url("admin/settings_geo_operators/delete_geo_operators/");?>/'+code;	
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

<?php if(count($geo_operators_list) > 0 && $geo_operators_list!=''):?>

jQuery(document).ready(function() {
	jQuery('#trackerslist').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
});

<?php endif;?>
</script>
