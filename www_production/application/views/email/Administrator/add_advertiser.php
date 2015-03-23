<html>
<head>
</head>
<body>

<style type="text/css">
<!--
/*
*based
*/
body,td,th {
	color: #333333;
	font-family: Tahoma, Verdana,sans-serif, Arial, Helvetica;
	
}
.allborder {
	border: 1px solid #EEEEEE;
}
body {  
    
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.p
{
padding-left:20px;

}

.tit{
	color:#FFFFFF;
	font-size:12px;
	font-weight:900;
	padding-left: 20px;
}

.con{
	color:#3a3372;
	font-size:12px;
	padding-top: 20px;
	padding-right: 20px;
	padding-bottom: 20px;
	padding-left: 20px;
}

textpadding {
	font-size: 12px;
	padding-top: 14px;
	padding-right: 14px;
	padding-bottom: 14px;
	padding-left: 14px;
}
.taball {
	padding: 5px;
	border: 1px dashed #CCCCCC;
}
.bluetext {color: #000066 font-size: 13px}

 </style>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%" valign="top" bgcolor="#EEE"><table width="100%" height="1" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td></td>
        </tr>
      </table> 
        <table width="100%" height="300" border="0" align="center" cellpadding="0" cellspacing="0" >
          <tr>
            <td height="250" valign="top" class="con"> <div style="padding:10px; line-height:25px;"><p><strong> <?php echo $this->lang->line('registration_welcome'); ?><?php echo $this->lang->line('site_title'); ?></strong></p>
			<p style="padding-top:15px;"><strong><?php echo $this->lang->line('registration_dear');?> <?php echo  $username; ?>,</strong></p>
              <p style="padding-top:10px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->lang->line('lang_add_advertiser_msg1'); ?><?php echo $this->lang->line('site_title'); ?><?php echo $this->lang->line('lang_add_advertiser_msg2'); ?><?php echo $this->lang->line('site_title'); ?><?php echo $this->lang->line('lang_add_advertiser_msg3'); ?></p>
                  <table width="239" border="0" style="padding-top:15px;">
                <tr>
                  <td colspan="2" ><b><?php echo $this->lang->line('lang_email_acc_det'); ?></b></td>
                  </tr>
                <tr>
                  <td style="white-space:nowrap"><?php echo $this->lang->line('lang_email_username'); ?> </td>
                  <td style="white-space:nowrap">:&nbsp;<?php echo  $username; ?></td>
                </tr>
                <tr>
                  <td style="white-space:nowrap"><?php echo $this->lang->line('lang_forget_password_password'); ?></td>
                  <td style="white-space:nowrap">:&nbsp;<?php echo $password; ?></td>
                </tr>
				<tr>
                  <td><?php echo $this->lang->line('lang_add_advertiser_name'); ?></td>
                  <td>:&nbsp;<?php echo $advertiser_name; ?></td>
                </tr>
              </table>
			  <p align="right" style="padding-right:10px;"><?php echo $this->lang->line('registration_sincerely'); ?><br>
		<?php echo $this->lang->line('site_title'); ?></p>

           </div></td>
          </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
 
 
