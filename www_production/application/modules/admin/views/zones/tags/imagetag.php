<table cellpadding="0" cellspacing="0" class="sTableHead" width="80%">
	<colgroup>
		<col class="head1" width="50%" />		
	</colgroup>
	<tr>
		<td align="center" colspan="2"><?php echo $this->lang->line('label_tag_settings');?></td>		
	</tr>
</table>				
<table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="80%">
 <colgroup>
      <col class="con0" width="20%" />
      <col class="con0" width="30%" />
 </colgroup>

<?php
$blank="";
$top="";
switch($target)
{
case '_blank':
	$blank="selected";
	break;
case '_top':
	 $top="selected";
	 break;
}
?>
<tr>
<td id="data"> <?php echo $this->lang->line('label_target_frame');?></td>
<td id="data"><select name="select">
		<option value=""><?php echo $this->lang->line('label_default');?></option>
		<option  <?php echo $blank;?> value="_blank"><?php echo $this->lang->line('label_new_window');?></option>
		<option <?php echo $top;?>  value="_top"><?php echo $this->lang->line('label_same_window');?></option>
</select>
</td>
</tr>
<tr>
<td id="data"><?php echo $this->lang->line('label_source');?></td>
<td id="data"><input type="text" name="source" id="source" class="sf" value="<?php echo $source; ?>"></td>
</tr>
<!--<tr>
<td id="data"><?php echo 'Refresh';//$this->lang->line('label_source'); ?></td>
<td id="data"><input type="text" name="refresh" id="refresh" class="sf" value="<?php echo $refresh; ?>"></td>
</tr>-->

<?php
	$generic		="";
	$doubleclick	="";
	$openx			="";

switch($party)
{
case 'generic':
	$generic		="selected";
	break;
case 'doubleclick':
	 $doubleclick	="selected";
	 break;
case 'openx':
	 $openx			="selected";	
	 break;
}
?>
<tr>
<td id="data"><?php echo $this->lang->line('label_support');?></td>
<td><select name="party">
<option value="no"><?php echo $this->lang->line('label_no');?></option>
<option <?php echo $generic; ?> value="generic"><?php echo $this->lang->line('label_generic');?></option>
<option <?php echo $doubleclick; ?> value="doubleclick"><?php echo $this->lang->line('label_rich_media_double_click');?></option>
<option <?php echo $openx; ?> value="openx"><?php echo $this->lang->line('label_rich_media_openx');?></option>
</select></td>
</tr>

<tr>
<td id="data"><?php echo 'Insert Cache-Busting code'; //$this->lang->line("label_comments");?></td>
<td id="data">
<?php if($cachebuster =="1") { ?>
<input type="radio" name="cachebuster" id="cachebuster" value="1" checked="checked" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cachebuster" id="cachebuster" value="0">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } else { ?>
<input type="radio" name="cachebuster" id="cachebuster" value="1"  >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="cachebuster" id="cachebuster" value="0" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } ?>
</td>
</tr>
<tr>
<td id="data"><?php echo $this->lang->line("label_comments");?></td>
<td id="data">
<?php if($comments =="1") { ?>
<input type="radio" name="comments" id="comments" value="1" checked="checked" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="comments" id="comments" value="0">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } else { ?>
<input type="radio" name="comments" id="comments" value="1"  >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="comments" id="comments" value="0" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } ?>
</td>
</tr>
</table>
