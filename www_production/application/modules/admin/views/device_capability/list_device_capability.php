<!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?>   
     
	<?php  
		if($this->session->flashdata('message') !=''):
	?>
		 <div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					 <?php 
								
								echo $this->session->flashdata('message');
								
					?>
				</p>
			</div>		
	<?php 
		endif;
	?>
	<a
	 href="<?php echo site_url("admin/settings_device_capability/add_device_capability"); ?>" class="addNewButton">
	  <?php echo $this->lang->line('label_add_device_capability'); ?>
	  </a>
     
	<br />
	       
	<br />
        <?php if(count($device_capability_list) > 0 && $device_capability_list != ''): ?>
         <div class="sTableOptions">
        <?php echo $this->pagination->create_links(); ?>
		<a   class="button delete_record"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
            
        </div><!--sTableOptions-->
	<?php endif; ?>
	
    <form id="frmDevicecapabilityList" action="<?php echo site_url('admin/settings_device_capability/delete_device_capability'); ?>" method="post" >
    	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="3%" />
                <col class="head1" width="5%" />
                <col class="head0" width="30%" />
				 <col class="head1" width="30%" />
                <col class="head0" width="15%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" id="checkall" /></td>
                <td><?php echo $this->lang->line('label_s_no'); ?></td>
                <td><?php echo $this->lang->line('label_device_capability'); ?></td>
				<td><?php echo $this->lang->line('label_device_capability_value'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="3%" />
                    <col class="con1" width="5%" />
                    <col class="con0" width="30%" />
					 <col class="con1" width="30%" />
                    <col class="con0" width="15%" />
                </colgroup>
				<?php 
					if(count($device_capability_list) > 0 && $device_capability_list != ''):
						$i=$offset;
						foreach($device_capability_list as $row):
						?>
								<tr>
									<td align="center"><input name="sel_device_capability[]" type="checkbox" value="<?php echo $row->capability_id; ?>" /></td>
									<td><?php echo $i++; ?></td>
									<td><?php echo view_text($row->capability_name); ?></td>
									<td><?php echo view_text($row->capability_value); ?></td>
									<td align="center">
										<a href="<?php echo site_url('admin/settings_device_capability/edit_device_capability/'.$row->capability_id);?>">
										<?php echo $this->lang->line("label_edit"); ?></a> &nbsp; 
										<a href="javascript:isDelsingle(<?php echo $row->capability_id; ?>)">
										<?php echo $this->lang->line("label_delete"); ?></a>
									</td>
								</tr>
						<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="7"><strong><center><?php echo $this->lang->line("label_device_capability_record_not_found"); ?></center></strong></td>
                </tr>
				<?php
					endif;
				?>

            </table>
			</form>
	     </div><!--sTableWrapper-->
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
							jConfirm('<?php echo $this->lang->line("confirmation_selected_delete_device_capability"); ?>','<?php echo $this->lang->line("confirmation_selected_delete_device_capability_title"); ?>',function(r){
							if(r){
								jQuery("#frmDevicecapabilityList").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
									unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.													
							});
						}
						
					});
					

					function isDelsingle(cap_id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_delete_device_capability"); ?>','<?php echo $this->lang->line("confirmation_deletion_device_capability_title"); ?>',function(r){
					if(r)
					{
						document.location.href	= '<?php echo site_url("admin/settings_device_capability/delete_device_capability/");?>/'+cap_id;	
					}				
					});
					}
	</script>
