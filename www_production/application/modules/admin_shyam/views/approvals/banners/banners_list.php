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
					 <?php 
								//echo validation_errors();
								echo $this->session->flashdata('error_message');
					?>
				</p>
			</div>		
	<?php 
		endif;
	?>


        <br />
		
		<!-- Button Rejection and Approvals -->
		<!--<div class="approval_button">
				<a  class="button delete"><button class="button button_red"><?php echo $this->lang->line("label_reject"); ?></button></a>
         		<a  class="button approve"><button class="button button_green"><?php echo $this->lang->line("label_approve"); ?></button> </a>

		</div>-->
        

		<?php 
				//  to hide when banners_list is empty
				if(count($banners_list) > 0 && $banners_list !=''): ?>
		<div class="sTableOptions">
			 <a class="button approve"><span><?php echo $this->lang->line("label_approve"); ?></span> </a>
			 <a class="button reject"><span><?php echo $this->lang->line("label_reject"); ?></span></a>
			<?php echo $this->pagination->create_links(); ?>
		</div><!--sTableOptions-->
		<?php endif; ?>
		<form id="frmApprovalList"  name="frmApprovalList" action="<?php echo site_url('admin/approvals/process'); ?>" method="post" >
    	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="4%" />
                <col class="head1" width="24%" />
				<col class="head0" width="24%" />
                <col class="head1" width="24%" />
                <col class="head0" width="24%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" id="checkall" /></td>
                <td><?php echo $this->lang->line('label_banners'); ?></td>
				<td><?php echo $this->lang->line('label_size_w_h'); ?></td>
                <td><?php echo $this->lang->line('label_preview_banner'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="4%" />
					<col class="con1" width="24%" />
					<col class="con0" width="24%" />
                    <col class="con1" width="24%" />
                    <col class="con0" width="24%" />
                </colgroup>
				<?php 
					if(count($banners_list) > 0 && $banners_list !=''):
						foreach($banners_list as $row):
						?>
								<tr>
									<td align="center"><input name="sel_approval_banners[]" id="uid" type="checkbox" value="<?php echo $row->bannerid; ?>" /></td>
									<td><?php echo $row->description; ?></td>
									<?php $banner_size = $row->width.' * '.$row->height; ?>
									<td><?php echo $banner_size; ?></td>
									<td>
										<?php
											if($row->storagetype!='txt')
											{?>
													<a class="view" href="<?php echo base_url();?><?php echo $this->config->item('ads_url');?><?=$row->filename;?>" title=""><?php echo $this->lang->line("label_view_image_banner"); ?></a>
											<?php
											}
											else
											{
												?><a class="view" href="<?php echo site_url('admin/approvals/show_text_banner');?>/<?=$row->bannerid;?>" title=""><?php echo $this->lang->line("label_view_text_banner"); ?></a><?php
											}
											?>
									</td>
									<td align="center">
										<a href="javascript:isApprove(<?php echo $row->bannerid; ?>)">
										<img src="<?php echo base_url();?>/assets/images/icons/success.png" width="17px" height="15px" ></a> &nbsp; 
										<a href="javascript:isReject(<?php echo $row->bannerid; ?>)">
										<img src="<?php echo base_url();?>/assets/images/icons/error.png" width="17px" height="15px" ></a>
									</td>
								</tr>
						<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="7" align="center"><?php echo $this->lang->line("label_banners_record_not_found"); ?></td>
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
							jAlert('<?php echo $this->lang->line("alert_no_item_selected");?>','<?php echo $this->lang->line("confirmation_ban_reject_title");?>');
						} else {
							jConfirm('<?php echo $this->lang->line("confirmation_selected_reject_banners"); ?>','<?php echo $this->lang->line("confirmation_ban_sel_reject_title"); ?>',function(r){
							if(r)
							{
								jQuery("#frmApprovalList").attr("action",'<?php echo site_url("admin/approvals/process/banners/reject"); ?>');
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
							jAlert('<?php echo $this->lang->line("alert_no_item_selected"); ?>','<?php echo $this->lang->line("confirmation_ban_approve_title"); ?>');
						} else {
							jConfirm('<?php echo $this->lang->line("confirmation_selected_approve_banners"); ?>','<?php echo $this->lang->line("confirmation_ban_sel_approve_title"); ?>',function(r){
							if(r)
							{
								jQuery("#frmApprovalList").attr("action",'<?php echo site_url("admin/approvals/process/banners/approve"); ?>');
								jQuery("#frmApprovalList").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.
							
							});
						}
					});
					
					// For single Approval of Banner
					function isApprove(ban_id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_approve_banner"); ?>','<?php echo $this->lang->line("confirmation_ban_approve_title"); ?>',function(r){
							if(r)
							{
								document.location.href	= '<?php echo site_url('admin/approvals/process/banners/approve/');?>/'+ban_id;	
							}				
						});
					
					}
					
					
					// For single Rejection of Banner
					function isReject(ban_id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_reject_banner"); ?>','<?php echo $this->lang->line("confirmation_ban_reject_title"); ?>',function(r){
							if(r)
							{
								document.location.href	= '<?php echo site_url('admin/approvals/process/banners/reject/');?>/'+ban_id;	
							}				
						});
					}
					
					
	</script>
