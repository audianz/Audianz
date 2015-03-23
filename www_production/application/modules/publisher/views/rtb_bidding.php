<?php 
if($this->session->flashdata('success') != ""):
   ?>
	<div class="notification msgsuccess"><a class="close"></a>
	  <p><?php echo $this->session->flashdata('success'); ?> </p>
	</div>
<?php
endif;
?>
<?php 
if($this->session->flashdata('error') != ""):
   ?>
	<div class="notification msgerror"><a class="close"></a>
	  <p>
		<?php 
		echo $this->session->flashdata('error');	
		?>
	  </p>
	</div>
<?php
endif;
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
</script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
</script>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#rtb_bidding").validationEngine();
	});
	
</script>
<h1 class="pageTitle"><?php echo $this->lang->line("label_realtime_bid_rate"); ?></h1>
<form action="<?php echo site_url('publisher/zones/rtb_bidding_update'); ?>" method="post" name="rtb_bidding" id="rtb_bidding">
  <div class="form_default">
    <fieldset>
    <legend><?php echo $this->lang->line("label_realtime_bidding_bid_rate"); ?></legend>
    
	<p>
      <label for="name"><?php echo $this->lang->line("label_rtb_bid_rate"); ?></label>
      <input type="text" name="rtb_rate" id="rtb_rate" class="validate[required,custom[number],min[0]] sf" value="<?php if(isset($bid_rate[0]->bid)){echo $bid_rate[0]->bid; }?>"
						alt="<?php echo $this->lang->line('label_rtb_bid_rate');?>"  
						/>
    </p> 
    
	<p><button><?php echo $this->lang->line("settings_site_Submit"); ?></button>
        <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button></p>
    </fieldset>
  </div>
  <!--form-->
</form>
</div>
<script type="text/javascript">
			function goToList()
			{
				document.location.href='<?php echo site_url('publisher/dashboard'); ?>';
			}
</script>
