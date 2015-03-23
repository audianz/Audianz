	 <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
	<script>
	jQuery(document).ready(function() {
			jQuery('#userlist').dataTable( {
			"sPaginationType": "full_numbers"
			});
			
		});
	</script>
	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
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
		<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_advertisers_page_title'); ?></h1>
     <?php endif; ?>   
     
	 <a href="<?php echo site_url("admin/inventory_advertisers/add_advertiser"); ?>" class="addNewButton"><?php echo $this->lang->line('label_add_advertiser'); ?></a>
      <?php if(!empty($advertiser_list)): ?>
	    <ul class="submenu">
        	<li <?php echo ($this->uri->segment(3)=="adlist" || $this->uri->segment(3)=="" )?"class='current'":""; ?>>
				<a href="<?php echo site_url('admin/inventory_advertisers'); ?>"><?php echo $this->lang->line("label_all"); ?> (<?php echo $all_records; ?>)</a>
			</li>
            <li <?php echo ($this->uri->segment(3)=="active")?"class='current'":""; ?> >
				<a href="<?php echo site_url('admin/inventory_advertisers/active'); ?>"><?php echo $this->lang->line("label_active"); ?> (<?php echo $active_records; ?>)</a>
			</li>
	    <li <?php echo ($this->uri->segment(3)=="inactive")?"class='current'":""; ?> >
				<a href="<?php echo site_url('admin/inventory_advertisers/inactive'); ?>"><?php echo $this->lang->line("label_inactive"); ?> (<?php echo $inactive_records; ?>)</a>
			</li>
        </ul>
	<?php endif; ?>
        <br />
         <br />
        
		<form id="frmAdvertiserList" action="<?php echo site_url('admin/inventory_advertisers/process/advertiser/rem'); ?>" method="post" >
        <?php if(!empty($advertiser_list)): ?>
        <div class="sTableOptions">
        <?php echo $this->pagination->create_links(); ?>
			<a   class="button delete"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
            
        </div><!--sTableOptions-->
		<?php endif; ?>
            <table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">
             <thead>
                <tr>
					<th class="head1"><input type="checkbox" class="checkall" id="checkall" style="margin-left: 8px;"/></th>
                    <th class="head0"><?php echo $this->lang->line('label_advertiser_name');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_email');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_account');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_trackers');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_campaigns');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_action');?></th>
                
				</tr>
            </thead>

            <colgroup>
                    <col class="con0" width="5%" />
                    <col class="con1" width="16%" />
                    <col class="con0" width="20%" />
                    <col class="con1" width="18%" />
                    <col class="con0" width="13%" />
					<col class="con1" width="13%" />
					<col class="con0" width="15%" />
             </colgroup>

             <tbody>
 
				<?php 
				        if(count($advertiser_list) > 0 AND $advertiser_list != FALSE):
						foreach($advertiser_list as $row):?>
						<tr>
							<td align="center"><input name="sel_advertiser[]"  type="checkbox" value="<?php echo $row->account_id; ?>" /></td>
							<td><?php echo view_text($row->contact_name); ?></td>
							<td><?php echo view_text($row->email); ?></td>
							<td align=""><?php echo number_format($row->acc_balance,2,".",","); ?> / <a href="<?php echo site_url("admin/inventory_advertisers/add_fund/".$row->client_id); ?>" ><?php echo $this->lang->line("label_add"); ?></a></td>
							<td align=""><a href="<?php echo site_url("admin/inventory_advertisers/trackers/all/".$row->client_id); ?>" ><?php echo $this->lang->line("label_list"); ?></a></td>
							<td align=""><a href="<?php echo site_url("admin/inventory_campaigns/add_campaign/".$row->client_id); ?>"> <?php echo $this->lang->line("label_add"); ?> </a> - <a href="<?php echo site_url("admin/inventory_campaigns/listing_adv/all/".$row->client_id); ?>" > <?php echo $this->lang->line("label_view"); ?> </a></td>
							<td align=""><a href="<?php echo site_url("admin/inventory_advertisers/edit/".$row->account_id); ?>"><?php echo $this->lang->line("label_edit"); ?></a> &nbsp; <a href="javascript: delSingle(<?php echo $row->account_id; ?>);"><?php echo $this->lang->line("label_delete"); ?></a></td>
							</tr>
							<?php endforeach;endif;?>
			</tbody>				
			</table>
			
			</form>
			<script type="text/javascript">
				function delSingle(id){
					/*if(confirm('<?php echo $this->lang->line('lang_advertiser_delete'); ?>')){
						document.location.href='<?php echo site_url("admin/inventory_advertisers/process/advertiser/rem"); ?>/'+id;
					}*/
					jConfirm('<?php echo $this->lang->line("lang_advertiser_delete"); ?>','<?php echo $this->lang->line("label_confirm_delete"); ?>',function(r){
							
							if(r)
							{
								document.location.href='<?php echo site_url("admin/inventory_advertisers/process/advertiser/rem"); ?>/'+id;
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
						jQuery('.dyntable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<?php echo $this->lang->line("alert_no_item_selected");?>');
						} else {
							/*var c = confirm('<?php echo $this->lang->line('lang_advertiser_delete'); ?>');
							if(c) {
								jQuery("#frmAdvertiserList").submit();
							}*/
							
							jConfirm('<?php echo $this->lang->line("lang_advertiser_delete"); ?>','<?php echo $this->lang->line("label_confirm_delete"); ?>',function(r){
							
							if(r)
							{
								jQuery("#frmAdvertiserList").submit();
							}else{
								jQuery(".checkall").attr('checked',false);
							}
							unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
							
						}
					});

	
	</script>
		
