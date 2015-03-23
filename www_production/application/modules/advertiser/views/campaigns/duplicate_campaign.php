<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#adv_add_fund").validationEngine();
	});
</script>
 <?php if($this->session->userdata('error_message') != ''): ?>
	 <div class="notification msgerror">
         <a class="close"></a>
         <p><?php echo $this->session->userdata('error_message'); ?></p>
     </div>
<?php $this->session->unset_userdata('error_message'); endif; ?>

			<?php if($this->session->userdata('camp_duplicate') != ""): ?>
			<div style="height:20px;" class="notification msgalert"> <a class="close"></a> <p><?php echo $this->session->userdata('camp_duplicate'); ?></p></div>
			<?php $this->session->unset_userdata('camp_duplicate');
			endif; ?>

<form class="formular" id="duplicate_campaign" name"duplicate_campaign" action="<?php echo site_url('advertiser/campaigns/duplicate_campaign_process'); ?>" method="post">
<h1 class="pageTitle"><?php echo $this->lang->line('label_advertiser_campaign_duplicate_campaign'); ?></h1>
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line('label_advertiser_campaign_duplicate_campaign');?></legend>
                    <p>
                    	<label for="name"><?php echo $this->lang->line('label_advertiser_campaign_new_campaign_name');?></label>
                        <input type="text" alt="<?php echo $this->lang->line('label_advertiser_campaign_enter_campaign_name');?>" value="<?php echo form_text((set_value('campaign_name') != '')?set_value('campaign_name'):$campaign_name." Copy"); ?>" class="validate[required]" name="campaign_name"  id="campaign_name"  />
                    </p>
                    <p>
                    	<input type="hidden" value="<?php echo $sel_campaign_id; ?>" name="sel_campaign_id" id="sel_campaign_id" />
						<button type="submit"><?php echo $this->lang->line('label_advertiser_campaign_duplicate');?></button>
						<button onclick="javascript: goToList();" style="margin-left:10px;" type="button"><?php echo  $this->lang->line('label_cancel'); ?></button>
                    </p>
                </fieldset>
            </div><!--form-->
</form>
<script>
function goToList(){
			document.location.href='<?php echo site_url("advertiser/campaigns"); ?>';
	}

</script>
