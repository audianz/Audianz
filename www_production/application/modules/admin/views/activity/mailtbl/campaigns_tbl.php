<?php if(count($rs) > 0 && $rs != '') : ?>
<h1 style=" font-size:12px; color:#666666;text-align:center" ><?php echo $this->lang->line('label_campaign'); ?></h1>
<table cellpadding="2" cellspacing="2" width="75%" border="0" align="center" style="color:#444;border:1px solid #ccc;padding:3px;line-height:25px;">
  <tr style="background:#CCC;">
    <th style=" font-size:10px"><?php echo $this->lang->line('label_zone_sno'); ?></th>
    <th style=" font-size:10px"><?php echo $this->lang->line('label_campaign _name'); ?></th>
    <th style=" font-size:10px"><?php echo $this->lang->line('label_zone_email'); ?></th>
	<th style=" font-size:10px"><?php echo $this->lang->line('label_zone_revenue'); ?></th>
	<th style=" font-size:10px"><?php echo $this->lang->line('label_zone_revenuetype'); ?></th>
	<th style=" font-size:10px"><?php echo $this->lang->line('label_campaign _activate_time'); ?></th>
	  </tr>

  <?php 
		$i	=1;
		foreach($rs as $row):
  ?>
  <tr style=" background:#EEE;">
    <td style=" font-size:10px" align="center"><?php echo $i++; ?></td>
    <td style=" font-size:10px"><?php echo $row->campaignname; ?></td>
   <td style=" font-size:10px"><?php echo $row->email; ?></td>
    <td style=" font-size:10px"align="center"><?php echo $row->revenue; ?></td>
    <td  style=" font-size:10px"align="center"><?php echo $row->revenue_type; ?></td>
    <td style=" font-size:10px"><?php echo date("Y-m-d", strtotime($row->activate_time)); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
