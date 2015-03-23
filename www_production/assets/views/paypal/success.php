<div class="form_default" align="center">
    <fieldset>
    <br/>
    <img src="<?php echo base_url(); ?>assets/images/payment_success.png" />
    <br/>
    <h1 class="pageTitle"><?php echo $this->lang->line('lang_thank_for_pay');?></h1>
    <p><?php echo $this->lang->line('lang_dreamads_recieved_the_pay');?><a href="<?php echo site_url('advertiser/payments/add_fund'); ?>"><?php echo $this->lang->line('lang_admin_click_here');?></a><?php echo $this->lang->line('lang_to_add_more_fund');?></p>
    </fieldset>
</div>
