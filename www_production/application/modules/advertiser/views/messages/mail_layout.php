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
}
</style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<table border="0" cellpadding="20" cellspacing="0" width="100%">
  <tr style="background:#EEE;">
    <td valign="top"><div mc:edit="std_content00">
<!--  <h3 class="h3"><?php //echo $this->lang->line('lang_email_welcome'); ?></h3>-->
        <h3 class="h3"><?php //echo $this->lang->line('greetings'); ?></h3>
		<?php $name	=explode("@", $reciever_email); $sender	=explode("@", $sender_email); ?>
        <p><strong><?php echo $this->lang->line('label_dear')?> <?php echo $name[0]; ?>, </strong></p>
        <div style="line-height:25px"><?php //echo $this->lang->line('label_suggestions_reply_sender'); ?> <?php //echo $sender_email; ?></div>
		 <div style="line-height:25px"><strong><?php echo $this->lang->line('label_suggestions_reply_subject'); ?> :</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rs['suggestion_subject']; ?><br /></div>
		  <div style="line-height:25px"><strong><?php echo $this->lang->line('label_suggestions_reply_message'); ?> :</strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $rs['suggestion_content']; ?><br /></div>
        <div style="font-weight:bold"> <?php echo $this->lang->line('thank'); ?><br/><?php echo $sender[0]; //$this->lang->line('dreamads'); ?> </div>
      </div></td>
  </tr>
</table>
</body>
</html>
