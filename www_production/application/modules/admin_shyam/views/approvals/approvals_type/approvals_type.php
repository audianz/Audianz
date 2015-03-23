<?php 
if($this->session->flashdata('message') != ""):
   ?>
<div class="notification msgsuccess"><a class="close"></a>
  <p><?php echo $this->session->flashdata('message'); ?> </p>
</div>
<?php
endif;
?>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#change_password").validationEngine();
	});
</script>
<?php foreach ($getrecord as $row_record) 
{

?>

 <h1 class="pageTitle"><?php echo $this->lang->line('settings_site_approvalstypes'); ?></h1>   	
        <form  action=<?php echo site_url('admin/approvals/approvals_type_update'); ?> method="post" name="change_password" id="change_password">
         
        	<div class="form_default">
                <fieldset>
				 <?php 
if($this->session->userdata('notification_message') != ""):
   ?>
<div class="notification msgsuccess"><a class="close"></a>
  <p>
  <?php 
  echo $this->session->userdata('notification_message'); 
  $this->session->unset_userdata('notification_message');
  ?> 
 </p>
</div>
<?php
endif;
?>
                    <legend><?php echo $this->lang->line("settings_site_approvalstype"); ?></legend>
                    
					 <p>
                    	<label for="gender" class="nopadding"><?php echo $this->lang->line("settings_site_approvals_type"); ?></label>
                                         </p>
                                          
						 <?php 
						   $admin_accept=FALSE;
						   $email_accept=FALSE;
						   ($row_record->approval_type ==1)?$email_accept=TRUE:$admin_accept=TRUE; ?>
                                           <div style="float:left; width: 82%;">
						
						<p style="float:left;width: 82%;"> 
						 <?php echo form_radio('apptype', '1', $email_accept); ?> <?php echo $this->lang->line('settings_site_email_approvals_type');?>                                         </p>
                                       
                                         <p style="float:lef;width: 82%;">
						 <?php echo form_radio('apptype', '2', $admin_accept); ?> <?php echo $this->lang->line('settings_site_admin_approvals_type');?>
                        
                    </p>
                        </div>
                    <p>
                	   	<button><?php echo $this->lang->line("settings_site_update"); ?></button>
                                 <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                	</p>
				</fieldset>
				
            </div><!--form-->
            <?php
	}
	?>
        
        </form>
        
    </div><!--fullpage-->
     <script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/dashboard'); ?>';
			}
	</script>
  
