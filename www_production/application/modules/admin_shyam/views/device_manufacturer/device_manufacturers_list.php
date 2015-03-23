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
								//echo validation_errors();
								echo $this->session->flashdata('message');
								//$this->session->unset_userdata('message');
								/*if($this->session->userdata('camp_error') != "")
								{ 
									echo $this->session->userdata('camp_error');
									$this->session->unset_userdata('camp_error');
								} */
					?>
				</p>
			</div>		
	<?php 
		endif;
	?>
	 <a
	 href="<?php echo site_url("admin/settings_device_manufacturers/add_device_manufacturer"); ?>" class="addNewButton">
	  <?php echo $this->lang->line('label_add_device_manufacturer'); ?>
	  </a>
     
	    <!-- <ul class="submenu">
        	<li <?php echo ($this->uri->segment(3)=="device_list" || $this->uri->segment(3)=="" )?"class='current'":""; ?>>
			<a href="<?php echo site_url('admin/settings_device_manufacturers'); ?>"><?php echo $this->lang->line("label_all"); ?> (<?php echo $all_records; ?>)
			</a>
			</li>
            
			<li <?php echo ($this->uri->segment(3)=="active")?"class='current'":""; ?>>
			<a href="<?php echo site_url('admin/settings_device_manufacturers/active'); ?>"><?php echo $this->lang->line("label_active"); ?> (<?php echo $active_records; ?>)</a>
			</li>
            <li <?php echo ($this->uri->segment(3)=="inactive")?"class='current'":""; ?>><a href="<?php echo site_url('admin/settings_device_manufacturers/inactive'); ?>"><?php echo $this->lang->line("label_inactive"); ?> (<?php echo $inactive_records; ?>)</a></li>
        </ul> -->
	
	<br/>
	
	<br />
        <?php  if(count($device_manufacturer_list) > 0 && $device_manufacturer_list !=''): ?>
        <div class="sTableOptions">
        <?php echo $this->pagination->create_links(); ?>
		<a   class="button delete_record"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
            
        </div><!--sTableOptions-->
	<?php endif; ?>
		<form id="frmDeviceManufacturerList" action="<?php echo site_url('admin/settings_device_manufacturers/delete_device_manufacturer'); ?>" method="post" >
    	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="3%" />
                <col class="head1" width="5%" />
                <col class="head0" width="30%" />
                <col class="head1" width="15%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" id="checkall" /></td>
                <td><?php echo $this->lang->line('label_s_no'); ?></td>
                <td><?php echo $this->lang->line('label_device_manufacturers'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="3%" />
                    <col class="con1" width="5%" />
                    <col class="con0" width="30%" />
                    <col class="con1" width="15%" />
                </colgroup>
				<?php 
					if(count($device_manufacturer_list) > 0 && $device_manufacturer_list !=''):
						$i=$offset;
						foreach($device_manufacturer_list as $row):
						?>
								<tr>
									<td align="center"><input name="sel_device_manufacturer[]" type="checkbox" value="<?php echo $row->manufacturer_id; ?>" /></td>
									<td><?php echo $i++; ?></td>
									<td><?php echo view_text($row->manufacturer_name); ?></td>
									<td align="center">
										<a href="<?php echo site_url('admin/settings_device_manufacturers/edit_device_manufacturer/'.$row->manufacturer_id);?>">
										<?php echo $this->lang->line("label_edit"); ?></a> &nbsp; 
										<a href="javascript:isDelsingle(<?php echo $row->manufacturer_id; ?>)">
										<?php echo $this->lang->line("label_delete"); ?></a>
									</td>
								</tr>
						<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="7" align="center"><?php echo $this->lang->line("label_device_manufacturer_record_not_found"); ?></td>
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
							jConfirm('<?php echo $this->lang->line("confirmation_selected_delete_device_manufacturers"); ?>','<?php echo $this->lang->line("confirmation_select_delete_manufacturer_title"); ?>',function(r){
							
							if(r)
							{
								jQuery("#frmDeviceManufacturerList").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
									unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
						}

					});
					
				

					function isDelsingle(manufacturer_id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_delete_device_manufacturers"); ?>','<?php echo $this->lang->line("confirmation_deletion_manufacturer_title"); ?>',function(r){
					if(r)
					{
						document.location.href	= '<?php echo site_url('admin/settings_device_manufacturers/delete_device_manufacturer/');?>/'+manufacturer_id;	
	}				});
					}
	</script>
