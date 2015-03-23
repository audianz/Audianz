<div class="form_default" align="center">
    <fieldset>
    <br/>
    <img src="<?php echo base_url(); ?>assets/images/no-payment.png" />
    <br/>
    <h1 class="pageTitle"><?php echo $this->lang->line('lang_admin_payment_canceled');?></h1>
    <p><?php echo $this->lang->line('lang_admin_please');?><a href="<?php echo site_url('admin/approvals/payments'); ?>"><?php echo $this->lang->line('lang_admin_click_here');?></a><?php echo $this->lang->line('lang_admin_pay_the_amt_of_pub');?></p>
    </fieldset>
</div>
