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
		jQuery("#publisher_share").validationEngine();
	});
	function checknumber(field, rules, i, options){

          var numericExpression = /^(?:\d*\.\d{1,5}|\d+)$/;
		  
		  	if (!field.val().match(numericExpression)) 
			  {
				 return "<?php echo $this->lang->line('notification_minimumamount_numberonly'); ?>";		
			  }
			  
			
              
	 }
</script>


 <h1 class="pageTitle"><?php echo $this->lang->line('settings_site_publishershare'); ?></h1>   	
        <form  action=<?php echo site_url('admin/approvals/publisher_share_update'); ?> method="post" name="publisher_share" id="publisher_share">
         
        	<div class="form_default">
                <fieldset>
				 <?php 
if($this->session->userdata('notification_message') != ""):
   ?>
<div class="notification msgerror"><a class="close"></a>
  <?php 
  echo $this->session->userdata('notification_message'); 
  $this->session->unset_userdata('notification_message');
  ?> 
</div>
<?php
endif;
?>

                    <legend><?php echo $this->lang->line("settings_site_publisherlimit"); ?></legend>
                    
					<p>
                    	<label for="name"><?php echo $this->lang->line("settings_site_publishershare"); ?></label>
<?php
if(count($getrecord)>0)
{
foreach($getrecord as $rec)
{
?>                        
<input type="text" name="publisher_share" maxlength=20 size=5 id="publisher_share" class="validate[funcCall[checknumber]required] sf" value="<?php echo $rec->publishershare;?>"  
						alt="<?php echo $this->lang->line('lang_site_settings_publisher_share');?>"  
						/>
						
				<?php }}
				else
				{?>
				<input type="text" name="publisher_share" maxlength=20 size=5 id="publisher_share" class="validate[funcCall[checknumber]required] sf" value="<?php echo "0.00";?>"  
						alt="<?php echo $this->lang->line('lang_site_settings_publisher_share');?>"  
						/>
				<?php } ?>
				</p>
					
                    <p>
                	   	<button><?php echo $this->lang->line("settings_site_Save"); ?></button>
                                <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" >
				<?php echo $this->lang->line('label_cancel'); ?></button>
                	</p>
				</fieldset>
				
            </div><!--form-->
	
        
        </form>
        
    </div><!--fullpage-->
    <script>
			function goToList()
			{
				document.location.href='<?php echo site_url('admin/dashboard'); ?>';
			}
	</script>
  
