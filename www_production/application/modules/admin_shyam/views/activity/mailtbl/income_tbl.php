<div class="sTableWrapper">

  <table cellpadding="2" cellspacing="2" width="75%" border="0" align="center" style="color:#444;border:1px solid #ccc;padding:3px;line-height:25px;">
  <tr style="background:#CCC;">
      <th colspan="2" align="left"><?php echo $this->lang->line('label_income_day'); ?><?php echo $date; ?></th>
    </tr>
   <tr style="background:#EEE;">
      <td style=" font-size:10px"><?php echo $this->lang->line('label_income_userid'); ?></td>
      <td style=" font-size:10px"><?php echo ($userid ==0)?'ADMIN':$userid; ?></td>
    </tr>
    <tr style="background:#EEE;">
      <td style=" font-size:10px"><?php echo $this->lang->line('label_income_accounttype'); ?></td>
      <td style=" font-size:10px"><?php echo $type; ?></td>
    </tr>
    <tr style="background:#EEE;">      <td><?php echo $this->lang->line('label_income'); ?></td>
      <td style=" font-size:10px"><?php echo "$".$income; ?></td>
    </tr>
  </table>
  <!--sTableWrapper-->
</div>
<!--fullpage-->
<br clear="all" />
