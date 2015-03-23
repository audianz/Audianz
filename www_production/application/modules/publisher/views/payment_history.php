<div style="padding:20px;">
<h3><?php echo $this->lang->line('lang_publisher_option_issued_last_history')?></h3>
<table  cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
    <colgroup>
        <col class="head1" width="22%" />
        <col class="head0" width="38%" />
        <col class="head1" width="10%" />
        <col class="head0" width="10%" />
        <col class="head1" width="10%" />
        <col class="head0" width="10%" />
       <!-- <col class="head1" width="10%" />-->
    </colgroup>
    <tr>
        <td style="padding-right:96px;" ><?php echo $this->lang->line('lang_publisher_option_name')?></td>
        <td><?php echo $this->lang->line('lang_publisher_option_email')?></td>
        <td><?php echo $this->lang->line('lang_publisher_option_payment_type')?></td>
      <!--  <td><?php echo $this->lang->line('lang_publisher_option_payment_no')?></td>-->
        <td><?php echo $this->lang->line('lang_publisher_option_amount')?></td>
        <td><?php echo $this->lang->line('lang_publisher_option_applied_date')?></td>
        <td><?php echo $this->lang->line('lang_publisher_option_issued_date')?></td>
    </tr>
</table>
<?php
if(count($tot_issued)>0):
    foreach($tot_issued as $issue):
    ?>
    <div class="sTableWrapper">
    <table cellpadding="0" cellspacing="0" class="sTable" width="100%">
        <colgroup>
            <col class="con1" width="22%" />
            <col class="con0" width="38%" />
            <col class="con1" width="10%" />
            <col class="con0" width="10%" />
            <col class="con1" width="10%" />
            <col class="con0" width="10%" />
          <!--  <col class="con1" width="10%" />-->
        </colgroup>
        <tr>
            <td><?php echo $issue->name; ?></td>
            <td><?php echo $issue->email; ?></td>
            <td><?php echo $issue->paymenttype; ?> </td>
          <!--  <td><?php echo $issue->paymentno; ?> </td>-->
            <td>$<?php echo number_format($issue->amount,2); ?></td>
            <td><?php echo date('F d, Y', strtotime($issue->date)); ?></td>
            <td><?php echo date('F d, Y', strtotime($issue->clearing_date)); ?></td>
        </tr>
    </table>
    </div>
<?php
    endforeach;
else: ?>
    <div class="sTableWrapper">
    <table cellpadding="0" cellspacing="0" class="sTable" width="100%">
        <tr>
            
            <td colspan="6"><?php echo $this->lang->line('lang_publisher_option_no_payment_earlier')?></td>
        </tr>
    </table>
    </div>
<?php endif; ?>
</div>
