<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#publisher_share").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
</script>
 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
<?php
					 echo validation_errors();
					?>
					
					<?php
					
					/*********Display Successful  Message ******/
					if($this->session->userdata('message') !=''):
					?>
					<div class="notification msgsuccess">
						<a class="close"></a>
						<p>
					<?php 
						  echo $this->session->userdata('message');
						  $this->session->unset_userdata('message');
					?>
                    </p>
					</div>
					<?php
						endif;
					?>

   <br />

  <form id="publisher_share" action="<?php echo site_url('admin/smaato_share_settings/update_process'); ?>" method="post">

       <?php  //print_r($mobile_screen_list);?>

        	<div class="form_default">

                <fieldset>

                    <legend><?php echo $page_title; ?></legend>
					
					
                    <div style="margin-left:15px">
					
                    <p>
			<?php
				if(($smaato_share == FALSE)  ||  ($smaato_share == NULL) || ($smaato_share == '0'))
				{
			?>
					<label for="pub_share"><?php echo $this->lang->line('title_publisher_share'); ?>(%) <span class="mandatory">*</span></label>
					<input type="text" name="smaato_share"  id="smaato_share" class="validate[required,custom[integer],min[0],max[100]]sf" size="30" value="" alt="<?php echo $this->lang->line('notification_publisher_share_validate'); ?>"/>
			<?php
				}
				else
				{
			?>
					<label for="pub_share"><?php echo $this->lang->line('title_publisher_share'); ?>(%) <span class="mandatory">*</span></label>
					<input type="text" name="smaato_share"  id="smaato_share" class="validate[required,custom[integer],min[0],max[100]]sf" size="30" value="<?php echo view_text((set_value('smaato_share') != '')?set_value('smaato_share'):$smaato_share->smaato_share) ;?>" alt="<?php echo $this->lang->line('notification_publisher_share_validate'); ?>"/>
			<?php
				}
			?>
                        <button style="margin-left:15px"><?php echo $this->lang->line('label_submit'); ?></button>
                         <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>

                    </p>

                    </div>

                </fieldset>

            </div><!--form-->
        

        </form>
        
<script>
	function goToList()
	{	
		document.location.href='<?php echo site_url('admin/dashboard'); ?>';
	}
</script>
        
