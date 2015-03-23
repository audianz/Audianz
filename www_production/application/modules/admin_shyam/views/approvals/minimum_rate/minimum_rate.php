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
		jQuery("#amount").validationEngine();
	});
	function checknumber(field, rules, i, options){

          var numericExpression = /^(?:\d*\.\d{1,5}|\d+)$/;
		  
		  var rgexp = new RegExp("^\d*([.]\d{2})?$");


			if (!field.val().match(numericExpression)) 
			  {
				 return "<?php echo $this->lang->line('notification_minimumamount_numberonly'); ?>";		
			  }
			  
			
              
	 }
</script>
<h1 class="pageTitle"><?php echo $this->lang->line('settings_site_minimumamount'); ?></h1>   	
        <form  action=<?php echo site_url('admin/approvals/minimum_rate_update'); ?> method="post" name="amount" id="amount">
         
        	<div class="form_default">
                <fieldset>
				 <?php 
if($this->session->userdata('notification_message') != ""):
   ?>
<div class="notification msgerror"><a class="close"></a>
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
                    <legend><?php echo $this->lang->line("settings_site_minimumamountlimit"); ?></legend>
                    
					<p>
                    	<label for="name"><?php echo $this->lang->line("settings_site_amount"); ?></label>
<?php
if(count($getrecord)>0)
{
foreach ($getrecord as $row_record) 
{
?>                        
<input type="text" name="Amount"  id="Amount" maxlength=20 size=5 class="validate[funcCall[checknumber]required] sf" value="<?php echo $row_record->Amount;?>"  
						alt="<?php echo $this->lang->line('lang_site_settings_minimum_rate');?>"  
						/>
<?php
	}
	}
else
{
	?><input type="text" name="Amount"  id="Amount" maxlength=20 size=5 class="validate[funcCall[checknumber]required] sf" value="0"  
						alt="<?php echo $this->lang->line('lang_site_settings_minimum_rate');?>" /> 
<?php						
}
	?>
                    </p>
					
                    <p>
                	   	<button><?php echo $this->lang->line("settings_site_update"); ?></button>
<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
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
  
