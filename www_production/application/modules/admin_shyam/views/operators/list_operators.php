<div style="padding:10px;margin:10px">
 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
<?php if($page_title != ''): ?><h1 class="pageTitle"><?php echo $page_title; ?></h1><?php endif; ?>   

<?php if($this->session->flashdata('operator_name_file_chk') !=''): ?><div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('operator_name_file_chk'); ?></p></div><?php endif; ?>

<?php if($this->session->flashdata('error_msg') !=''): ?><div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('error_msg'); ?></p></div><?php endif; ?>

<form enctype="multipart/form-data" name="operators_form" method="post" action="<?php echo site_url('admin/settings_operators/import_operator'); ?>">
 <div class="form_default"> 
   <p>
      <label><?php echo $this->lang->line('label_geo_operators'); ?></label>
      <input type="file" name="geoOpCsv" id="geoOpCsv" value="1">&nbsp;&nbsp; 
      <button style="margin-left:10px;"><?php echo $this->lang->line('label_upload'); ?></button>
	<div class="notification msginfo" style="width:18%;margin-left:553px;padding:5px;background:#C7D6EC;font-size:10px;margin-top:-53px;margin-bottom: 4px;text-align: center;" ><a href="<?php echo site_url('admin/settings_operators/operators_download_file'); ?>">Download Sample CSV File</a></div>	
   </p>
</div>
</form>
<br/>
<?php if($this->session->flashdata('success_msg')!=''): ?><div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('success_msg'); ?></p></div><?php endif; ?>

<?php if(count($operators_list) > 0 AND $operators_list != FALSE && $this->pagination->create_links() !=''): ?><div class="sTableOptions" style="padding-bottom:25px;"><?php echo $this->pagination->create_links(); ?><br /></div><?php endif; ?>

<table cellpadding="0" cellspacing="0" id="trackerslist" class="dyntable" width="100%">
<thead>
<tr>
  <th class="head0"><?php echo $this->lang->line('label_s_no'); ?></th>
  <th class="head1"><?php echo $this->lang->line('label_geo_operators'); ?></th>
  <th class="head0"><?php echo $this->lang->line('label_geo_operators_country_code'); ?></th>
  <th class="head1"><?php echo $this->lang->line('label_geo_operators_country'); ?></th>
   <th align="center" class="head0"><?php echo $this->lang->line('label_action'); ?></th>
</tr>
</thead>
<colgroup>
	<col class="con0" />
	<col class="con1" />
	<col class="con0" />
	<col class="con1" />
	<col class="con0" />
</colgroup>
<tbody>
<?php 
	if(count($operators_list) >0 && $operators_list !=''):
	$i	=$offset;
	foreach($operators_list as $row):
?>
<tr>
	<td><?php echo $i++; ?></td>
	<td><?php echo view_text($row->carriername); ?></td>
	<td><?php echo ($row->country_code =='')?"&nbsp;":$row->country_code;?></td>
	<td><?php echo ($row->country =='')?"&nbsp;":$row->country;?></td>
	<td align="center">
<a href="<?php echo site_url('admin/settings_operators/functional/operators/'.$row->id);?>"><?php echo $this->lang->line("label_edit"); ?></a>&nbsp;<a href="javascript:isDelsingle(<?php echo $row->id; ?>)"><?php echo $this->lang->line("label_delete"); ?></a>
</td>
<?php endforeach; ?>
<tr>
<td colspan="8" align="left"><div class="notification msginfo" style="width:13%; font-size:10px; background:#C7D6EC;text-align:center; margin-bottom: 2px; margin-top: 1px; padding-left:1px; padding-right: 0px;"><a href="<?php echo site_url('admin/settings_operators/operator_download'); ?>">Export Last Imported List</a></div></td>
</tr>
<?php else: ?>
<tr>
   <td colspan="8" align="center"><?php echo $this->lang->line("label_geo_operators_record_not_found"); ?></td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
<script type="text/javascript">

function isDelsingle(pid){
	/*if(confirm("Are you sure want to delete?")){*/

	jQuery.ajax({
			type: "POST",
			url: '<?php echo site_url("admin/settings_operators/validate/operator");?>',
			data: "id="+pid,
			success: function(data)
			{
				if(data ==1)
				{
					if(confirm("This Operator already Asigned to Campaign.\nAre you sure want to delete?"))
					{
						document.location.href='<?php echo site_url("admin/settings_operators/delete/operator"); ?>/'+pid;		
					}
				}
				else
				{
					if(confirm("Are you sure want to delete?")){
						document.location.href='<?php echo site_url("admin/settings_operators/delete/operator"); ?>/'+pid;		
					}
				}
		    }
          });
}

</script>
