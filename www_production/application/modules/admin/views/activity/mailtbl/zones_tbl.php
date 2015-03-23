<?php if(count($rs) > 0 && $rs != '') : ?>
<h1 style=" font-size:12px; color:#666666;text-align:center" ><?php echo $this->lang->line('label_zone_name'); ?></h1>
<table cellpadding="2" cellspacing="2" width="75%" border="0" align="center" style="color:#444;border:1px solid #ccc;padding:3px;line-height:25px;">
  <tr style="background:#CCC ;">
     <th style=" font-size:10px"><?php echo $this->lang->line('label_zone_sno'); ?></th>
     <th style=" font-size:10px"><?php echo $this->lang->line('label_zone_name'); ?></th>
	<th style=" font-size:10px"><?php echo $this->lang->line('label_zone_delivery'); ?></th>
	<th style=" font-size:10px"><?php echo $this->lang->line("label_zone_width"); ?></th>
	<th style=" font-size:10px"><?php echo $this->lang->line('label_zone_height'); ?></th>
	<th style=" font-size:10px"><?php echo $this->lang->line('label_zone_revenuetype'); ?></th>
  </tr>

  <?php 
		$i	=1;
		foreach($rs as $row):
  ?>
 <tr style="background:#EEE;">
   <td style=" font-size:10px"><?php echo $i++; ?></td>
     <td style=" font-size:10px"><?php echo $row->zonename; ?></td>
    <td style=" font-size:10px"><?php echo $row->delivery; ?></td>
    <td style=" font-size:10px"><?php echo $row->width; ?></td>
    <td style=" font-size:10px"><?php echo $row->height; ?></td>
    <td style=" font-size:10px"><?php echo $row->revenuetype; ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
