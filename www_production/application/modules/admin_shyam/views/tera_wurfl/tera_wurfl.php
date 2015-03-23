<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#tera_wurl").validationEngine();
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

  <form id="tera_wurl" action="<?php echo site_url('admin/settings_tera_wurfl/update_process'); ?>" method="post">

       <?php  //print_r($mobile_screen_list);?>

        	<div class="form_default">

                <fieldset>

                    <legend><?php echo $page_title; ?></legend>
					
					
                    <div style="margin-left:15px">
					
                    <p>
			<?php
			if(($tera_wurl_list == FALSE)  ||  ($tera_wurl_list == NULL) || ($tera_wurl_list == '0'))
			{
			?>
                    	<label for="tera_wurl_path"><?php echo $this->lang->line('label_tera_wurl_path'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="tera_wurl_path"  id="tera_wurl_path" class="validate[required]sf" size="30" value="" alt="<?php echo $this->lang->line('notification_tera_wurl_validate'); ?>"/>
			<?php
			}
			else
			{
			?>
			<label for="tera_wurl_path"><?php echo $this->lang->line('label_tera_wurl_path'); ?> <span class="mandatory">*</span></label>
                        <input type="text" name="tera_wurl_path"  id="tera_wurl_path" class="validate[required]sf" size="30" value="<?php echo view_text((set_value('tera_wurl_path') != '')?set_value('tera_wurl_path'):$tera_wurl_list->terawurfl_path) ;?>" alt="<?php echo $this->lang->line('notification_tera_wurl_validate'); ?>"/>
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
        
