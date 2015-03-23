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
	line-height:25px;
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

<table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%" valign="top" bgcolor="#EEE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td></td>
        </tr>
      </table> 
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" >
          <tr>
            <td  valign="top" class="con"> <div style="padding:10px;">
			<p><strong> <?php echo $this->lang->line('lang_add_advertiser_welcome'); ?><?php echo $site_title?>,</strong></p></br>
			
              <p > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo  $username .'  is logged in   '.$site_title .'.  Please find the User Login Information below,'; ?></p>
			    <table width="292"  border="0" >
                <tr>
                  <td colspan="2" ><b><?php echo "User Login Information Details"; ?></td>
                  </tr>
                <tr>
                  <td width="120" height="22" style="white-space:nowrap"><?php echo "User Name"; ?> </td>
                  <td width="215"  style="white-space:nowrap">&nbsp;:&nbsp;<?php echo  $username; ?></td>
                </tr>
                <tr>
                  <td height="22" style="white-space:nowrap"><?php echo "Login Time"; ?></td>
                  <td style="white-space:nowrap">&nbsp;:&nbsp;<?php echo $logged_in; ?></td>
                </tr>
				
				   <tr>
                  <td height="22" style="white-space:nowrap"><?php echo "IP Address"; ?></td>
                  <td style="white-space:nowrap">&nbsp;:&nbsp;<?php echo $ip_address;?></td>
                </tr>
				 <tr>
                  <td height="22" style="white-space:nowrap"><?php echo "Email"; ?></td>
                  <td style="white-space:nowrap">&nbsp;:&nbsp;<?php echo $email; ?></td>
                </tr>
				   <tr>
                  <td height="22" style="white-space:nowrap"><?php echo "Country"; ?></td>
                  <td style="white-space:nowrap">&nbsp;:&nbsp;<?php echo $country; ?></td>
                </tr>
              </table>

           </div></td>
          </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
 
