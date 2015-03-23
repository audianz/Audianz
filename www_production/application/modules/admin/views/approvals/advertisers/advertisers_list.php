<script language="javascript" type="text/javascript">
	        jQuery(document).ready(function() {
	            setInterval("location.reload(true)", 120000);
	        });  
</script> 

<!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?>   
     
	<?php  
		if($this->session->flashdata('success_message') !=''):
	?>
		 <div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					 <?php 
								//echo validation_errors();
								echo $this->session->flashdata('success_message');
					?>
				</p>
			</div>		
	<?php 
		endif;
	?>
	
	<?php  
		if($this->session->flashdata('error_message') !=''):
	?>
		 <div class="notification msgerror">
				<a class="close"></a>
				<p>
					 <?php echo $this->session->flashdata('error_message');?>
				</p>
			</div>		
	<?php 
		endif;
	?>
	 
	    <br/>

		
		
		<?php if(count($advertisers_list) > 0 && $advertisers_list !=''):?>
		<div class="sTableOptions">
				<a class="button approve"><span><?php echo $this->lang->line("label_approve"); ?></span> </a>
				<a  class="button reject"><span><?php echo $this->lang->line("label_reject"); ?></span></a>		
			<?php echo $this->pagination->create_links(); ?>
		</div><!--sTableOptions-->
		<?php endif;?>
		
		<form id="frmApprovalList"  name="frmApprovalList" action="<?php echo site_url('admin/approvals/process'); ?>" method="post" >
    	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="4%" />
                <col class="head1" width="32%" />
                <col class="head0" width="32%" />
                <col class="head1" width="32%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall"  id="checkall"/></td>
                <td><?php echo $this->lang->line('label_advertisers'); ?></td>
                <td><?php echo $this->lang->line('label_email'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="4%" />
					<col class="con0" width="32%" />
                    <col class="con1" width="32%" />
                    <col class="con0" width="32%" />
                </colgroup>
				<?php 
					if(count($advertisers_list) > 0 && $advertisers_list !=''):
						$i=$offset;
						foreach($advertisers_list as $row):
						?>
								<tr>
									<td align="center"><input name="sel_approval_advertisers[]" id="uid" type="checkbox" value="<?php echo $row->user_id; ?>" /></td>
									<td><?php echo $row->contact_name; ?></td>
									<td><?php echo $row->email_address; ?></td>
									<td align="center">
										<a href="<?php echo site_url(); ?>/admin/approvals/view/advertiser/<?php echo $row->user_id; ?>" class="view" title="<?php echo $this->lang->line('label_view'); ?> <?php echo $row->contact_name; ?> <?php echo $this->lang->line('label_full_details'); ?>">
										<img src="<?php echo base_url();?>/assets/images/icons/view.png" width="17px" height="15px" /></a> &nbsp; 
										<a href="javascript:isApprove(<?php echo $row->user_id; ?>)" title="<?php echo $this->lang->line('label_approve_advertisers'); ?>">
										<img src="<?php echo base_url();?>/assets/images/icons/success.png" width="17px" height="15px" /></a> &nbsp; 
										<a href="javascript:isReject(<?php echo $row->user_id; ?>)" title="<?php echo $this->lang->line('label_reject_advertisers'); ?>">
										<img src="<?php echo base_url();?>/assets/images/icons/error.png" width="17px" height="15px" /></a>
									</td>
								</tr>
						<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="7" align="center"><?php echo $this->lang->line("label_advertisers_record_not_found"); ?></td>
                </tr>
				<?php
					endif;
				?>
				
            </table>
			</div><!--sTableWrapper-->
			
			
			</form>
		  <script type="text/javascript">
					/**
					 * Delete selected items in a table
					**/
					jQuery('.sTableOptions .reject').click(function(){
						var empt = true;
						jQuery('.sTable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<?php echo $this->lang->line("alert_no_item_selected");?>','<?php echo $this->lang->line("confirmation_advertiser_reject_title");?>');
						} else {
							
							jConfirm('<?php echo $this->lang->line("confirmation_selected_reject_advertisers"); ?>','<?php echo $this->lang->line("confirmation_advertiser_sel_reject_title"); ?>',function(r){
							if(r)
							{
								jQuery("#frmApprovalList").attr("action",'<?php echo site_url("admin/approvals/process/advertisers/reject"); ?>');
								jQuery("#frmApprovalList").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.
							});

						}
					});
					
					//Approve Selected Items
					jQuery('.sTableOptions .approve').click(function(){
						var empt = true;
						jQuery('.sTable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<?php echo $this->lang->line("alert_no_item_selected"); ?>','<?php echo $this->lang->line("confirmation_advertiser_approve_title"); ?>');
						} else {
							
							jConfirm('<?php echo $this->lang->line("confirmation_selected_approve_advertisers"); ?>','<?php echo $this->lang->line("confirmation_advertiser_sel_approve_title"); ?>',function(r){
							if(r)
							{
								jQuery("#frmApprovalList").attr("action",'<?php echo site_url("admin/approvals/process/advertisers/approve"); ?>');
								jQuery("#frmApprovalList").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.
							
							});
						}
					});
					
					
					// For single Approval of Publishers
					function isApprove(user_id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_approve_advertiser"); ?>','<?php echo $this->lang->line("confirmation_advertiser_approve_title"); ?>',function(r){
							if(r)
							{
								document.location.href	= '<?php echo site_url('admin/approvals/process/advertisers/approve/');?>/'+user_id;	
							}				
						});
					
					}
					
					
					// For single Rejection of Publishers
					function isReject(user_id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_reject_advertiser"); ?>','<?php echo $this->lang->line("confirmation_advertiser_reject_title"); ?>',function(r){
							if(r)
							{
								document.location.href	= '<?php echo site_url('admin/approvals/process/advertisers/reject/');?>/'+user_id;	
							}				
						});
					}

					
	</script>
