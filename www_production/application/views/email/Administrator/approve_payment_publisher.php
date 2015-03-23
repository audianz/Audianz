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
		  		<p style="padding-top:6%;padding-left:6%;"><strong><?php echo $this->lang->line('label_dear').". ";?> <?php echo  $name; ?>,</strong></p>
		<p style="padding-top:3%;padding-left:6%"><?php echo $this->lang->line('label_payment_approval_content'); ?><?php echo " ".$date;?></p>

		
				<table width="200" border="0" style="padding-left:10%">
			  <tr>
				<td style="text-align:right; white-space:nowrap"><strong><?php echo $this->lang->line('label_payment_approval_website_name'); ?><?php echo " : ";?></strong></td>
				<td style="text-align:left;white-space:nowrap"><a href="<?php echo $website;?>"><?php echo $website;?></a></td>
			  </tr>

              	
			<tr>
				<td style="text-align:right; white-space:nowrap"><strong><?php echo $this->lang->line('label_amount'); ?><?php echo " : ";?></strong></td>
				<td style="text-align:left;white-space:nowrap"><?php echo $amount;?></td>
			  </tr> 
			<tr>
				<td style="text-align:right; white-space:nowrap"><strong><?php echo $this->lang->line('label_payment_approval_login_url'); ?><?php echo " : ";?></strong></td>
				<td style="text-align:left;white-space:nowrap"><a href="<?php echo site_url(); ?>"><?php echo site_url(); ?></a></td>
			  </tr>
		
			</table>

		<br>
              	<br>
			   	<p align="right" style="padding-right:10px;"><?php echo $this->lang->line('registration_sincerely'); ?><br>
				<?php echo $this->lang->line('site_title'); ?></p>
 
          </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
 
 
