	 <?php if($this->session->flashdata('success_message') != ''): ?>
	 <div class="notification msgsuccess">
            	<a class="close"></a>
            	<p><?php echo $this->session->flashdata('success_message'); ?></p>
     </div>
	 <?php endif; ?>
	 <?php if($this->session->flashdata('error_message') != ''): ?>
	 <div class="notification msgerror">
            	<a class="close"></a>
            	<p><?php echo $this->session->flashdata('error_message'); ?></p>
     </div>
	 <?php endif; ?>
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?>   
		<a href="<?php echo site_url("admin/inventory_advertisers/add_trackers/".$sel_advertiser_id.""); ?>" class="addNewButton"><?php echo $this->lang->line('label_add_tracker'); ?></a>
        <?php if(!empty($trackers_list)): ?>
        <ul class="submenu">
        	<li <?php echo ($this->uri->segment(4)=="all" || $this->uri->segment(4)=="" )?"class='current'":""; ?>>
					<a href="<?php echo site_url('admin/inventory_advertisers/trackers/all/'.$sel_advertiser_id); ?>"><?php echo $this->lang->line('label_all'); ?> (<?php echo ($num_rec['IGNORE'] + $num_rec['PENDING'] + $num_rec['APPROVED']) ; ?>)</a></li>
            <li <?php echo ($this->uri->segment(4)=="ignore")?"class='current'":""; ?>>
					<a href="<?php echo site_url('admin/inventory_advertisers/trackers/ignore/'.$sel_advertiser_id); ?>"><?php echo $this->lang->line('label_ignore'); ?> (<?php echo $num_rec['IGNORE']; ?>)</a></li>
            <li <?php echo ($this->uri->segment(4)=="pending")?"class='current'":""; ?>>
					<a href="<?php echo site_url('admin/inventory_advertisers/trackers/pending/'.$sel_advertiser_id); ?>"><?php echo $this->lang->line('label_pending'); ?> (<?php echo $num_rec['PENDING']; ?>)</a></li>
			<li <?php echo ($this->uri->segment(4)=="approved")?"class='current'":""; ?>>
					<a href="<?php echo site_url('admin/inventory_advertisers/trackers/approved/'.$sel_advertiser_id); ?>"><?php echo $this->lang->line('label_approved'); ?> (<?php echo $num_rec['APPROVED']; ?>)</a></li>
        </ul>
		<?php endif; ?>
        <br />
        <br />
        <form id="frmTrackerList" action="<?php echo site_url('admin/inventory_advertisers/tracker_process/rem/'.$sel_advertiser_id); ?>" method="post" >
        <?php if(!empty($trackers_list)): ?>
        <div class="sTableOptions">
        	
            <?php echo $this->pagination->create_links(); ?>
			
        	<a   class="button delete"><span><?php echo $this->lang->line('label_delete'); ?></span></a>
            
        </div><!--sTableOptions-->
        <?php endif; ?>
		
		<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="5%" />
                <col class="head1" width="10%" />
                <col class="head0" width="30%" />
                <col class="head1" width="10%" />
                <col class="head0" width="10%" />
				<col class="head1" width="10%" />
				<col class="head0" width="10%" />
				<col class="head1" width="15%" />
            </colgroup>
			<tr>
            	<td align="center"><input type="checkbox" class="checkall" /></td>
                <td><?php echo $this->lang->line('label_tracker_name'); ?></td>
                <td><?php echo $this->lang->line('label_tracker_desc'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_tracker_status'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_tracker_type'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_tracker_linked'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_tracker_code'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div  class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="5%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="30%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="10%" />
					<col class="con1" width="10%" />
					<col class="con1" width="10%" />
					<col class="con0" width="15%" />
                </colgroup>
				<?php 
						if(!empty($trackers_list)):
						foreach($trackers_list as $row):
							?>
								<tr>
									<td align="center"><input name="sel_tracker[]"  type="checkbox" value="<?php echo $row->trackerid; ?>" /></td>
									<td><?php echo view_text($row->trackername); ?></td>
									<td><?php echo view_text($row->description); ?></td>
									<td align="center"><?php echo $row->tracker_status; ?></td>
									<td align="center"><?php echo $row->tracker_type; ?></td>
									<td align="center" id="data_border"><a href="<?php echo site_url('admin/inventory_advertisers/trackers_linked_campaigns/'.$sel_status.'/'.$sel_advertiser_id.'/'.$row->trackerid); ?>" style="text-decoration:none;"><?php echo $this->lang->line('label_campaigns'); ?></a></td>
									<td align="center" id="data_border"><a href="<?php echo site_url('admin/inventory_advertisers/tracker_code/'.$sel_status.'/'.$sel_advertiser_id.'/'.$row->trackerid); ?>" style="text-decoration:none;"><?php echo $this->lang->line('label_invocation'); ?></a></td>
									<td align="center" id="data_border"><a href="<?php echo site_url('admin/inventory_advertisers/edit_tracker/'.$sel_status.'/'.$row->trackerid); ?>" style="text-decoration:none;">
									<?php echo $this->lang->line('label_edit'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="javascript: delSingle(<?php echo $sel_advertiser_id; ?>,<?php echo $row->trackerid;?>)" style="text-decoration:none;"><?php echo $this->lang->line('label_delete'); ?></a></td>

									</tr>
							<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="8"><strong><center><?php echo $this->lang->line("label_trackers_record_not_found"); ?></center></strong></td>
                </tr>
				<?php
					endif;
				?>
		 </table>
		   </div><!--sTableWrapper-->
	     </form>
		
		 <script type="text/javascript">
				function delSingle(sadid,id){
										
					jConfirm('<?php echo $this->lang->line("lang_tracker_delete"); ?>','<?php echo $this->lang->line("label_confirm_delete"); ?>',function(r){
							
					if(r)
					{
						document.location.href='<?php echo site_url("admin/inventory_advertisers/tracker_process/rem"); ?>/'+sadid+'/'+id;
					}else{
						jQuery(".checkall").attr('checked',false);
					}
							unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
					});
				}
			</script>
			<script type="text/javascript">
					/**
					 * Delete selected items in a table
					**/
					jQuery('.sTableOptions .delete').click(function(){
						var empt = true;
						jQuery('.sTable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<?php echo $this->lang->line("alert_no_item_selected");?>');
						} else {
							jConfirm('<?php echo $this->lang->line("lang_tracker_delete"); ?>','<?php echo $this->lang->line("label_confirm_delete"); ?>',function(r){
							
							if(r)
							{
								jQuery("#frmTrackerList").submit();
							}else{
								jQuery(".checkall").attr('checked',false);
							}
							unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
							
						}
					});	
	</script>
