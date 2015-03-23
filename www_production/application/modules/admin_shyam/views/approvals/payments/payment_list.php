 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?>   
     
	 
	<?php  
			echo $this->session->userdata('Validate_ipn');
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
	
	 
	    <br/>

		
        
		<?php if(count($payments_list) > 0 && $payments_list !=''):?>
			<?php if($this->pagination->create_links() !=''): ?>		
		<div class="sTableOptions" style="height:33px;">

             
			<?php echo $this->pagination->create_links(); ?>
		</div><!--sTableOptions-->
			<?php endif;?>			
		<?php endif;?>
		
		<form id="frmApprovalList"  name="frmApprovalList" action="<?php echo site_url('admin/approvals/process'); ?>" method="post" >
    	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
                <col class="head1" width="10%" />
                <col class="head0" width="17%" />
                <col class="head1" width="10%" />
                <col class="head0" width="10%" />
                <col class="head1" width="10%" />
                <col class="head0" width="15%" />
                <col class="head1" width="15%" />
                <col class="head0" width="15%" />
                
               
                
            </colgroup>
            <tr>
            	
				 <td><?php echo $this->lang->line('label_name'); ?></td>
                <td><?php echo $this->lang->line('label_email'); ?></td>
				<td a><?php echo $this->lang->line('label_Payment Type'); ?></td>
				<td><?php echo $this->lang->line('label_Date'); ?></td>
				<td><?php echo $this->lang->line('label_Amount'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_Paypal_id'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_Status'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                 <colgroup>
                    <col class="con1" width="10%" />
                    <col class="con0" width="17%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="10%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="15%" />
                    <col class="con1" width="15%" />
                    <col class="con0" width="15%" />
                   
                    
                </colgroup>
				<?php 
					if(count($payments_list) > 0 && $payments_list !=''):
						$i=$offset;
						foreach($payments_list as $row):
						?>
								<tr>
									<td><?php echo $row->name;?></td>
									<?php if(strlen($row->email)>21): ?>
									<td><?php echo substr($row->email,0,21);?>...</td>
									<?php else: ?>
									<td><?php echo $row->email; ?></td>
									<?php endif; ?>
									<td><?php echo $row->paymenttype;?></td>
									<td><?php echo $row->date;?></td>
									<td><?php echo $row->amount;?></td>
									<td align="center">
									<?php if($row->paypalid !=''): 
										echo $row->paypalid;
									else:
										echo '----';
									endif;
									?>
									
									</td>
									
									<td align="center"><?php echo $this->lang->line("label_payments_pending"); ?></td>
									<td align="center">
										<a href="javascript:isApprove('<?php echo $row->publisherid; ?>-<?php echo $row->id; ?>')">
										<img src="<?php echo base_url();?>/assets/images/icons/success.png" width="17px" height="15px" ></a> &nbsp; 
										<a href="javascript:isReject('<?php echo $row->publisherid; ?>-<?php echo $row->id; ?>')">
										<img src="<?php echo base_url();?>/assets/images/icons/error.png" width="17px" height="15px" ></a>
									</td>
								</tr>
						<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="10" align="center"><?php echo $this->lang->line("label_payment_record_not_found"); ?></td>
                </tr>
				<?php
					endif;
				?>
				
            </table>
			</div><!--sTableWrapper-->
			
			
			</form>
		  <script type="text/javascript">
					
					// For single Approval of Payments
					function isApprove(id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_approve_payment"); ?>','<?php echo $this->lang->line("confirmation_approve_payment_title"); ?>',function(r){
							if(r)
							{
								document.location.href	= '<?php echo site_url('admin/approvals/process/payments/approve/');?>/'+id;	
							}				
						});
					
					}
					
					
					// For single Rejection of Payments
					function isReject(id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_reject_payment"); ?>','<?php echo $this->lang->line("confirmation_payment_reject_title"); ?>',function(r){
							if(r)
							{
								document.location.href	= '<?php echo site_url('admin/approvals/process/payments/reject/');?>/'+id;	
							}				
						});
					}

					
	</script>
