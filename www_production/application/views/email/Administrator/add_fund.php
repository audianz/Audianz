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
            <td height="250" valign="top" class="con"><div style="padding:10px; line-height:25px;"><p><strong><?php echo lang('lang_add_website_welcome'); ?><?php echo "  ".$advertiername ?>,</strong></p>
			<p><strong><?php echo lang('lang_add_advertiser_welcome'); ?><?php echo "  ".$site_title; ?>,</strong></p>
            <?php
				$msg1	=str_replace('{SITE_TITLE}', $site_title, lang('lang_add_fund_admin_msg1'));
				$msg1	=str_replace('{DATE}', $date, $msg1);
				$msg2	=str_replace('{SITE_TITLE}', $site_title,$msg1);
				$msg2	=str_replace('{SITE_URL}', site_url(), $msg2);
			?>
			<p><?php echo $msg1; ?></p>
			
            <table width="334" border="0" align="left" style="margin-left:30px;">
              <tr>
                <td width="138"><b><?php echo lang('lang_fund_advertiser_fund'); ?></b></td>
                <td width="186">:&nbsp;<?php echo $this->config->item('currency_symbol'); ?><?php echo $fund; ?></td>
              </tr>
              <tr>
                 <td><b><?php echo lang('lang_fund_advertiser_existing_fund'); ?></b></td>
                 <td>:&nbsp;<?php echo $this->config->item('currency_symbol'); ?><?php 
                 
                 if($existing_fund =='' || $existing_fund == 0)
                 {
					$existing_fund='0';
					echo $existing_fund;
				}
				else
				{
					echo $existing_fund;
				}
				 ?></td>
              </tr>
			  <tr>
                <td><b><?php echo lang('lang_fund_advertiser_current_value'); ?></b></td>
                <td>:&nbsp;<?php echo $this->config->item('currency_symbol'); ?><?php echo $current_value; ?></td>
              </tr>
              </table>
			 
             <p align="right" style="padding-right:10px;;padding-top:120px"><?php echo lang('sincerely'); ?><br>
			<?php echo $site_title.lang('lang_sincerely_admin'); ?>

           </div></td>
          </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
