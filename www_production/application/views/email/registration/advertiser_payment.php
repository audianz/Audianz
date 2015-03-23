<html>
    <head>
	<style type="text/css">
	h3, .h3{
/*@editable*/ color:#202020;
display:block;
/*@editable*/ font-family:Arial;
/*@editable*/ font-size:26px;
/*@editable*/ font-weight:bold;
/*@editable*/ line-height:100%;
margin-top:0;
margin-right:0;
margin-bottom:0;
margin-left:0;
/*@editable*/ text-align:left;
}
h4, .h4{
/*@editable*/ color:#202020;
display:block;
/*@editable*/ font-family:Arial;
/*@editable*/ font-size:22px;
/*@editable*/ font-weight:bold;
/*@editable*/ line-height:100%;
margin-top:0;
margin-right:0;
margin-bottom:0;
margin-left:0;
/*@editable*/ text-align:left;
}
.bodyContent div{
/*@editable*/ color:#000;
/*@editable*/ font-family:Arial;
/*@editable*/ font-size:14px;
/*@editable*/ line-height:25px;
/*@editable*/ text-align:left;
}
.bodyContent div a:link, .bodyContent div a:visited, /* Yahoo! Mail Override */ .bodyContent div a .yshortcuts /* Yahoo! Mail Override */{
/*@editable*/ color:#336699;
/*@editable*/ font-weight:normal;
/*@editable*/ text-decoration:underline;
}

</style>
</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

  <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                               <tr style="background:#EEE;">
                                                                    <td valign="top">
  <div mc:edit="std_content00">

<h4 class="h3"><?php echo $this->lang->line('registration_welcome'); ?><?php echo $this->lang->line('site_title'); ?></h3>
<h3 class="h3"><?php echo $this->lang->line('greetings'); ?> </h3>
<p><strong><?php echo $this->lang->line('label_dear')?>,<?php echo $payer_name; ?></strong></p>
<div style="line-height:25px"> 
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('advertiser_payment_msg'); ?>,
   <?php  echo $payer_email; ?>
</div>
<br/>
<div style="font-weight:bold"><?php echo $this->lang->line('payment_account_details'); ?></div>
<br/>
<div style="line-height:25px">
<?php echo $this->lang->line('payment_gross'); ?> <?php echo $payment_gross; ?><br />
<?php echo $this->lang->line('payment_status'); ?> <?php echo $payment_status; ?> <br />
<?php echo $this->lang->line('payment_date'); ?> <?php echo $payment_date; ?><br /> 
<?php echo $this->lang->line('txn_id'); ?> <?php echo $txn_id; ?><br /> 
<?php echo $this->lang->line('ipn_track_id'); ?><?php echo $ipn_track_id; ?><br /> 
<br /></div>
<div style="font-weight:bold">
<?php echo $this->lang->line('thankyou'); ?>  
<br/>
<?php echo $this->lang->line('site_title'); ?><?php echo $this->lang->line('dreamadsadmin'); ?>
</div> 
  </div>
    </td>
    </tr>
    </table>
