<p><textarea rows="12" cols="150" readonly="yes" name="textarea" id="textarea1" onMouseOver="selectall(this);" onmousedown="selectall(this);">
<!--/*
  *
  *
  *  To help prevent caching of the <noscript> beacon, if possible,
  *  Replace %%RANDOM_NUMBER%% with a randomly generated number (or timestamp)
  *
  */-->

<!--/*
  *
  *  Place this code at the top of your thank-you page, just after the <body> tag,
  *  below any definitions of Javascript variables that need to be tracked.
  *
  */-->
<script type='text/javascript'>
<!--//<![CDATA[
var p = (location.protocol=='https:'?'<?php echo base_url(); ?><?php echo $this->config->item('ads_code'); ?>/delivery/tjs.php':'<?php echo base_url(); ?><?php echo $this->config->item('ads_code'); ?>/delivery/tjs.php');
var r=Math.floor(Math.random()*999999);
document.write ("<" + "script language='JavaScript' ");
document.write ("type='text/javascript' src='"+p);
document.write ("?trackerid=<?php echo $sel_tracker_id; ?>&amp;append=<?php echo $append_code; ?>&amp;r="+r+"'><" + "\/script>");
//]]>-->
</script>
<noscript><div id='m3_tracker_<?php echo  $sel_tracker_id; ?>' style='position: absolute; left: 0px; top: 0px; visibility: hidden;'><img src='<?php echo base_url();?><?php echo $this->config->item('ads_code');?>/delivery/ti.php?trackerid=<?php echo $sel_tracker_id; ?>&amp;cb=%%RANDOM_NUMBER%%' width='0' height='0' alt='' /></div></noscript>
</textarea>
</p>
<br />
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
<tr>
<td id="data"><?php echo 'Always display appended code, even if no conversion is recorded by the tracker?'; //$this->lang->line("label_comments"); ?></td>
<td id="data">
<?php if($append_code =="1") { ?>
<input type="radio" name="append_code" id="append_code" value="1" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="append_code" id="append_code" value="0">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } else { ?>
<input type="radio" name="append_code" id="append_code" value="1">&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="append_code" id="append_code" value="0" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } ?>
</td>
</tr>
</table>
