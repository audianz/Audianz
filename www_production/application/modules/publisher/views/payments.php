<script type="text/javascript">
jQuery(document).ready(function(){
        var countmonth = document.getElementById('countmonth').value;
        for(i=1;i<=countmonth;i++)
        {
            var credit = "earn"+i;
            var debit = "pay"+i;
            var bal = "totbal"+i;
            jQuery("tr[id="+credit+"]").slideUp("slow");
            jQuery("tr[id="+debit+"]").slideUp("slow");
            jQuery("tr[id="+bal+"]").slideUp("slow");
        }
});

function showhidePayments(monthval)
{
    var display = document.getElementById('detailDisplay').value;
    var credit = "earn"+monthval;
    var debit = "pay"+monthval;
    var bal = "totbal"+monthval;
    if(display=='' || display=='2')
    {

        jQuery("tr[id="+credit+"]").slideDown("slow");
        jQuery("tr[id="+debit+"]").slideDown("slow");
        jQuery("tr[id="+bal+"]").slideDown("slow");

        document.getElementById('detailDisplay').value = 1;
    }
    else
    {
        jQuery("tr[id="+credit+"]").slideUp("slow");
        jQuery("tr[id="+debit+"]").slideUp("slow");
        jQuery("tr[id="+bal+"]").slideUp("slow");

        document.getElementById('detailDisplay').value = 2;
    }

}
</script>
<h1 class="pageTitle"><?php echo $this->lang->line('lang_publisher_option_page_title');?></h1>
<?php if($this->session->flashdata('payment_success') != '')
{?>
	<div class="notification msgsuccess"><a class="close"></a>
	<p><?php echo $this->session->flashdata('payment_success');?></p>
	</div>
<?php 
}?>
<?php if($this->session->flashdata('payment_failure') != '')
{?>
	<div class="notification msgerror"><a class="close"></a>
	<p><?php echo $this->session->flashdata('payment_failure');?></p>
	</div>
<?php 
}?>
<?php echo validation_errors();?>
<div width="100%" style="border:1px solid #ddd;height:220px;">
    <div style="width:40%;float: left;border:0px solid red;padding:20px;">
        <h3><?php echo $this->lang->line('lang_publisher_option_unpaid_earnings')?></h3>
        <br/>
        <h1>$<?php echo number_format($unpaid,2); ?></h1>
    </div>
    <div style="width:50%;float: right;border:0px solid red;padding: 20px;">
        <h3><?php echo $this->lang->line('lang_publisher_option_last_pay_update')?></h3>
        <br/>
        <?php if($issued!=''): ?>
        <table align="left" width="100%" style="line-height: 30px;">
            <tr>
                <td width="6%"><?php echo $this->lang->line('lang_publisher_option_amount')?></td>
                <td width="2%"> : </td>
                <td width="20%">$<?php echo number_format($issued->amount,2); ?></td>
            </tr>
            <tr>
                <td><?php echo $this->lang->line('lang_publisher_option_issued_date')?></td>
                <td> : </td>
                <td><?php echo date('F d, Y', strtotime($issued->clearing_date)); ?></td>
            </tr>
            <tr>
                <td><?php echo $this->lang->line('lang_publisher_option_payment_type')?></td>
                <td> : </td>
                <td><?php echo $issued->paymenttype; ?></td>
            </tr>
            <?php /* ?><tr>
                <td><?php echo $this->lang->line('lang_publisher_option_payee_name')?></td>
                <td> : </td>
                <td><?php echo $issued->name; ?></td>
            </tr>
            <?php */ ?>
        </table>
        <?php else: ?>
            <p><?php echo $this->lang->line('lang_publisher_option_no_payment_earlier')?></p>
        <?php endif; ?>
    </div>
</div>

	<br/>
	<div style='width:98%;padding:10px;margin-bottom:15px;'>
 	<?php
	if($unpaid>$withdraw_limit):
	?>
	<div style="width:40%;float:left;">
	<a href="<?php echo site_url('publisher/payments/apply_payment_option'); ?>"><?php echo $this->lang->line('lang_publisher_option_apply_for_pay')?></a>&nbsp;&nbsp; | &nbsp;&nbsp;
	<a href="<?php echo site_url('publisher/payments/payment_history'); ?>" class="view"><?php echo $this->lang->line('lang_publisher_option_view_pay_history')?></a>
	</div>
	<?php endif; ?>
	
	<?php if(count($monthAvail)): ?>
	<div style="width:60%;float:right;text-align:right;">
		<a title="Click to Export Payment history" href="<?php echo site_url('publisher/payments/export_excel_payments');?>"><span class="export_excel_link"><?php echo $this->lang->line('label_export_excel'); ?></span></a>
	</div>
	<?php endif; ?>
	</div>
	<br/>
<div class="sTableOptions">
<h3><?php echo $this->lang->line('lang_publisher_option_earn_and_pay')?></h3>
</div><!--sTableOptions-->
<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
<colgroup>
<col class="head0" width="10%" />
<col class="head1" width="35%" />
<col class="head0" width="15%" />
<col class="head1" width="15%" />
<col class="head0" width="15%" />
</colgroup>
<tr>
<td width="10%" align="center"><?php echo $this->lang->line('lang_publisher_option_date')?></td>
<td width="35%" align="center"><?php echo $this->lang->line('lang_publisher_option_description')?></td>
<td width="15%" align="center"><?php echo $this->lang->line('lang_publisher_option_earn_and_credit')?></td>
<td width="15%" align="center"><?php echo $this->lang->line('lang_publisher_option_pay_and_debit')?></td>
<td width="15%" align="center"><?php echo $this->lang->line('lang_publisher_option_balance')?></td>
</tr>
</table>
<input type="hidden" name="detailDisplay" id="detailDisplay" />
<div class="sTableWrapper">
<table cellpadding="0" cellspacing="0" class="sTable" width="100%">
<colgroup>
    <col class="con0" width="10%" />
    <col class="con1" width="35%" />
    <col class="con0" width="15%" />
    <col class="con1" width="15%" />
    <col class="con0" width="15%" />
</colgroup>
<?php
if(count($monthAvail)):
    $i=1;
    foreach($monthAvail as $month):
        $publisher = $this->session->userdata('session_publisher_id');
        $lastDate = date('Y-m-t', strtotime($month->year.'-'.$month->month.'-'.'01'));
        $firstDate = date('Y-m-d', strtotime($month->year.'-'.$month->month.'-'.'01'));

        $monthlyDebit = $this->mod_payments->monthly_debit($publisher,$lastDate);
        $onemonthDebit = $this->mod_payments->one_month_debit($publisher,$firstDate,$lastDate);

        $monthlyCredit = $this->mod_payments->monthly_credit($publisher,$lastDate);
        $onemonthCredit = $this->mod_payments->one_month_credit($publisher,$firstDate,$lastDate);
        $endmonthBal = $monthlyCredit-$monthlyDebit;

?>
<tr>
    <td>&nbsp;</td>
    <td><b><?php echo date("F", mktime(0, 0, 0, $month->month, 10)).' '.$month->year; ?></b> (<a href="javascript:showhidePayments(<?php echo $i; ?>);"><?php echo $this->lang->line('lang_publisher_option_details');?></a>)</td>
    <td align="right"><?php echo ($onemonthCredit!='')?'$'.number_format($onemonthCredit,2):''; ?></td>
    <td align="right"><?php echo ($onemonthDebit!='')?'$'.number_format($onemonthDebit,2):''; ?></td>
    <td align="right"><?php echo ($endmonthBal!='')?'$'.number_format($endmonthBal,2):''; ?>
    <input type="hidden" name="countmonth" id="countmonth" value="<?php echo count($monthAvail); ?>" />
    <input type="hidden" name="monthval" id="monthval" value="<?php echo $i; ?>" />
    </td>
</tr>
<?php
    $avail_dates = $this->mod_payments->get_available_dates($publisher,$month->month,$month->year);
    if(count($avail_dates)):
        foreach($avail_dates as $availDate):
        ?>
        <tr id="earn<?php echo $i; ?>">
            <td align="center" width="10%"><?php echo date('M d, Y', strtotime($availDate->date)); ?></td>
            <td align="center" width="35%"><?php echo $this->lang->line('lang_publisher_option_earned');?></td>
            <td align="right" width="15%"><?php echo number_format($availDate->earn_amt,2); ?></td>
            <td align="right" width="15%">&nbsp;</td>
            <td align="right" width="15%">&nbsp;</td>
        </tr>
        <?php
        endforeach;
    endif;
    
    $avail_debit = $this->mod_payments->get_debited_amount($publisher);
    if(count($avail_debit)):
        foreach($avail_debit as $debitAmt):
            if($month->year==$debitAmt->year && $month->month==$debitAmt->month):
        ?>
        <tr id="pay<?php echo $i; ?>">
            <td align="center" width="10%"><?php echo date('M d, Y', strtotime($debitAmt->clearing_date)); ?></td>
            <td align="center" width="35%"><?php echo $this->lang->line('lang_paid_by_admin');?> (<a href="<?php echo site_url('publisher/payments/payment_history/'.$debitAmt->id); ?>" class="view"><?php echo $this->lang->line('lang_publisher_option_details');?></a>)</td>
            <td align="right" width="15%">&nbsp;</td>   
            <td align="right" width="15%"><?php echo number_format($debitAmt->debit_amt,2); ?></td>
            <td align="right" width="15%">&nbsp;</td>
        </tr>
        <?php
            endif;
        endforeach;
    endif;
    ?>
    <tr id="totbal<?php echo $i; ?>">
        <td colspan="4" align="right"><b><?php echo $this->lang->line('lang_publisher_option_end_of');?><?php echo date("F", mktime(0, 0, 0, $month->month, 10)); ?> <?php echo $this->lang->line('lang_publisher_option_balance');?></b></td>
        <td align="right"><b>$<?php echo number_format($endmonthBal,2); ?></b></td>
    </tr>
    <?php
        $i++;
    endforeach;
else:?>
	<tr>
		<td colspan="5" align="center"><?php echo $this->lang->line('lang_publisher_option_no_pay_and_earn');?></td>
	</tr>	    
<?php endif; ?>
</table>
</div>
