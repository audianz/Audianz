<!-- Display page title dynamically. page_title content must be initialized corresponding controller. -->
<?php if($page_title!=''):?>

<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_trackers_status_page_title');?></h1>
<?php endif;?>


<?php 
			if($this->session->flashdata('trackers_success_message') != ""):
   ?>
					<div class="notification msgsuccess"><a class="close"></a>
  					<p><?php echo $this->session->flashdata('trackers_success_message'); ?> </p>
					</div>
<?php
			endif;?>
<?php /*?><a href="<?php echo site_url("admin/settings_trackers_status/add_status");?>" class="addNewButton"><?php echo $this->lang->line('label_inventory_trackers_add_trackers_status'); ?>	</a><?php */?>

<br />
<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
  <colgroup>
  <col class="head0" width="35%" />
  <col class="head1" width="35%" />
  <col class="head0" width="30%" />
  </colgroup>
  <tr>
    <td align="center"><?php echo $this->lang->line('label_inventory_trackers_trackers_status'); ?></td>
    <td align="center"><?php echo $this->lang->line('label_inventory_trackers_trackers_value'); ?></td>
	<td align="center"><?php echo $this->lang->line('label_inventory_trackers_trackers_action'); ?></td>
  </tr>
</table>
<div class="sTableWrapper">
  <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
    <colgroup>
    <col class="con0" width="35%" />
    <col class="con1" width="35%" />
    <col class="con0" width="30%" />
    </colgroup>
    <?php 
					if(count($status) > 0):
						foreach($status as $row):
							?>
    <tr>
      <td align="center"><?php echo  $row->name; ?></td>
      <td align="center"><?php echo $row->value; ?></td>
	  
	  <?php if($row->is_active == '0') 
	  {
	  ?>
	  <td align="center">
<span class="status_icon"><a title="<?php echo $this->lang->line('label_click_here_to_activate_trackers_status');?>" href="javascript:confirm_status('<?php echo $row->id;?>')" onclick="return confirm_status()"><img alt="Change active/inactive" src="<?php echo base_url();?>assets/images/icons/inactive.png" /></span></a>
</td>
<?php
}
else
if($row->is_active=1)
{
?>
<td align="center">

<span class="status_icon"><a title="<?php echo $this->lang->line('label_click_here_to_in-activate_trackers_status');?>" href="javascript:confirm_status('<?php echo $row->id;?>')" onclick="return confirm_status()"><img alt="Change active/inactive" src="<?php echo base_url();?>assets/images/icons/active.png" /></span></a>
</td>
<?php 
}?>
    </tr>
    <?php
						endforeach;
					else:
				?>
    <tr>
      <td colspan="7"><?php echo $this->lang->line("label_tracker_status_record_not_found"); ?></td>
    </tr>
    <?php
					endif;
				?>
  </table>
</div>
<!--sTableWrapper-->
<script>
function confirm_status(id)
					{
						
						jConfirm('<?php echo $this->lang->line("confirmation_status_tracker_status_change"); ?>','<?php echo $this->lang->line("label_confirm_box_tracker_staus"); ?>',function(r){
						//If the result is trur or Ok buttton 
						
						if(r)
						{
							document.location.href='<?php echo site_url("admin/settings_trackers_status/edit_status/");?>/'+id;	
						}
						else
						{
							return false;
						}
					
					});
					}
</script>
