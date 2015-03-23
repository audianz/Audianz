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
	$blank		="";
	$top		="";

switch($target)
{
	case '_blank':
		$blank	="selected";
		break;
	case '_top':
		 $top	="selected";
	 	 break;
}
?>
<tr>
<td id="data"> <?php echo $this->lang->line('label_target_frame');?></td>
<td id="data"><select name="select">
		<option value=""><?php echo $this->lang->line('label_default');?></option>
		<option  <?php echo $blank; ?> value="_blank"><?php echo $this->lang->line('label_new_window');?></option>
		<option <?php echo $top; ?>  value="_top"><?php echo $this->lang->line('label_same_window');?></option>
</select>
</td>
</tr>
<tr>
<td id="data"><?php echo $this->lang->line('label_source'); ?></td>
<td id="data"><input type="text" name="source" id="source" class="sf" value="<?php echo $source; ?>"></td>
</tr>

<!--<tr>
<td id="data"><?php echo 'Refresh';//$this->lang->line('label_source'); ?></td>
<td id="data"><input type="text" name="refresh" id="refresh" class="sf" value="<?php echo $refresh; ?>"></td>
</tr>-->

<tr>
<td id="data"><?php echo $this->lang->line('label_dont_show'); ?> </td>
<td id="data">
<?php if($banner =="1") { ?>
<input type="radio" name="banner" value="1" checked="checked" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="banner" value="0">&nbsp;&nbsp;<?php echo $this->lang->line('label_no');?>
<?php } else { ?>
<input type="radio" name="banner" value="1" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="banner" value="0" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_no');?>
<?php } ?>
</td>
</tr>

<tr>
<td id="data"><?php echo $this->lang->line('label_show'); ?></td>
<td id="data"><?php if($text =="1") { ?>
<input type="radio" name="text" id="text" value="1" checked="checked" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="text" id="text" value="0">&nbsp;&nbsp;<?php echo $this->lang->line('label_no');?>
<?php } else { ?>
<input type="radio" name="text" id="text" value="1" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes');?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="text" id="text" value="0" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } ?>
</td>
</tr>
<tr>
<td id="data"><?php echo $this->lang->line("label_dont_show_camp");?></td>
<td id="data">
<?php if($campaign =="1") { ?>
<input type="radio" name="campaign" id="campaign" value="1" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="campaign" id="campaign" value="0" >&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } else { ?>
<input type="radio" name="campaign" id="campaign" value="1" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="campaign" id="campaign" value="0" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } ?>
</td>
</tr>
<?php 
$iso="";
$iso1="";
$iso2="";
$iso3="";
$iso4="";
$iso5="";
$iso6="";
$iso7="";
$iso8="";$iso9="";
$iso10="";$iso11="";$iso12="";
$iso13="";$iso14="";$iso15="";$iso16="";$iso17="";$iso18="";$iso19="";$iso20="";
$iso21="";
$iso22="";$iso23="";$iso24="";$iso25="";$iso26="";$iso27="";$iso28="";$iso29="";
switch($charset)
{
		case "ISO-8859-6":
				$iso="selected";
				break;
		case "Windows-1256":
				$iso1="selected";
				break;	
		case "ISO-8859-4":
				$iso2="selected";
				break;
		case "Windows-1257":
				$iso3="selected";
				break;
		case "ISO-8859-2":
				$iso4="selected";
				break;
		case "Windows-1250":
				$iso5="selected";
				break;
		case "GB18030":
				$iso6="selected";
				break;
		case "GB2312":
				$iso7="selected";
				break;
		case "HZ":
				$iso8="selected";
				break;
		case "Big5":
				$iso9="selected";
				break;
		case "ISO-8859-5":
				$iso10="selected";
				break;
		case "KOI8-R":
				$iso11="selected";
				break;
		case "Windows-1251":
				$iso12="selected";
				break;
		case "ISO-8859-13":
				$iso13="selected";
				break;
		case "ISO-8859-7":
				$iso14="selected";
				break;
		case "Windows-1253":
				$iso15="selected";
				break;
		case "ISO-8859-8-l":
				$iso16="selected";
				break;
		case "ISO-8859-8":
				$iso17="selected";
				break;
		case "Windows-1255":
				$iso18="selected";
				break;
		case "EUC-JP":
				$iso19="selected";
				break;
		case "Shift-JIS":
				$iso20="selected";
				break;		
		case "EUC-KR":
				$iso21="selected";
				break;
		case "ISO-8859-15":
				$iso22="selected";
				break;
		case "TIS-620":
				$iso23="selected";
				break;
		case "ISO-8859-9":
				$iso24="selected";
				break;	
		case "Windows-1254":
				$iso25="selected";
				break;																															
		case "UTF-8":
				$iso26="selected";
				break;	
		case "Windows-1258":
				$iso27="selected";
				break;	
		case "ISO-8859-1":
				$iso28="selected";
				break;	
		case "Windows-1252":
				$iso29="selected";
				break;									
}
?>
<tr>
<td id="data"><?php echo $this->lang->line('label_character_set');?></td>
<td><select name="charset">
		<option value="" selected="selected">Auto-detect</option>
		<option <?php echo $iso;?> value="ISO-8859-6">Arabic (ISO-8859-6)</option>
		<option <?php echo $iso1;?> value="Windows-1256">Arabic (Windows-1256)</option>
		<option <?php echo $iso2;?> value="ISO-8859-4">Baltic (ISO-8859-4)</option>
		<option <?php echo $iso3;?> value="Windows-1257">Baltic (Windows-1257)</option>
		<option <?php echo $iso4;?> value="ISO-8859-2">Central European (ISO-8859-2)</option>
		<option <?php echo $iso5;?> value="Windows-1250">Central European (Windows-1250)</option>
		<option <?php echo $iso6;?> value="GB18030">Chinese Simplified (GB18030)</option>
		<option <?php echo $iso7;?> value="GB2312">Chinese Simplified (GB2312)</option>
		<option  <?php echo $iso8;?> value="HZ">Chinese Simplified (HZ)</option>
		<option <?php echo $iso9;?>  value="Big5">Chinese Traditional (Big5)</option>
		<option <?php echo $iso10;?> value="ISO-8859-5">Cyrillic (ISO-8859-5)</option>
		<option <?php echo $iso11;?> value="KOI8-R">Cyrillic (KOI8-R)</option>
		<option <?php echo $iso12;?> value="Windows-1251">Cyrillic (Windows-1251)</option>
		<option <?php echo $iso13;?> value="ISO-8859-13">Estonian (ISO-8859-13)</option>
		<option <?php echo $iso14;?> value="ISO-8859-7">Greek (ISO-8859-7)</option>
		<option <?php echo $iso15;?> value="Windows-1253">Greek (Windows-1253)</option>
		<option <?php echo $iso16;?> value="ISO-8859-8-l">Hebrew (ISO Logical: ISO-8859-8-l)</option>
		<option <?php echo $iso17;?> value="ISO-8859-8">Hebrew (ISO:Visual: ISO-8859-8)</option>
		<option <?php echo $iso18;?> value="Windows-1255">Hebrew (Windows-1255)</option>
		<option <?php echo $iso19;?> value="EUC-JP">Japanese (EUC-JP)</option>
		<option <?php echo $iso20;?> value="Shift-JIS">Japanese (Shift-JIS)</option>
		<option <?php echo $iso21;?> value="EUC-KR">Korean (EUC-KR)</option>
		<option <?php echo $iso22;?> value="ISO-8859-15">Latin 9 (ISO-8859-15)</option>
		<option <?php echo $iso23;?> value="TIS-620">Thai (TIS-620)</option>
		<option <?php echo $iso24;?> value="ISO-8859-9">Turkish (ISO-8859-9)</option>
		<option <?php echo $iso25;?> value="Windows-1254">Turkish (Windows-1254)</option>
		<option <?php echo $iso26;?> value="UTF-8">Unicode (UTF-8)</option>
		<option <?php echo $iso27;?> value="Windows-1258">Vietnamese (Windows-1258)</option>
		<option <?php echo $iso28;?> value="ISO-8859-1">Western European (ISO-8859-1)</option>
		<option <?php echo $iso29;?> value="Windows-1252">Western European (Windows-1252)</option>
</select></td>
</tr>
<?php
$generic		="";
$doubleclick	="";
$openx			="";
switch($party)
{
case 'generic':
	$generic	="selected";
	break;
case 'doubleclick':
	 $doubleclick	="selected";
	 break;
case 'openx':
	 $openx		="selected";	
	 break; 
}
?>
<tr>
<td id="data"><?php echo $this->lang->line('label_support'); ?></td>
<td><select name="party">
<option value="no"><?php echo $this->lang->line('label_no'); ?></option>
<option <?php echo $generic; ?> value="generic"><?php echo $this->lang->line('label_generic'); ?></option>
<option <?php echo $doubleclick; ?> value="doubleclick"><?php echo $this->lang->line('label_rich_media_double_click'); ?></option>
<option <?php echo $openx; ?> value="openx"><?php echo $this->lang->line('label_rich_media_openx'); ?></option>
</select></td>
</tr>
<tr>
<td id="data"><?php echo $this->lang->line("label_comments"); ?></td>
<td id="data">
<?php if($comments =="1") { ?>
<input type="radio" name="comments" id="comments" value="1" checked="checked" >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="comments" id="comments" value="0">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } else { ?>
<input type="radio" name="comments" id="comments" value="1"  >&nbsp;&nbsp;<?php echo $this->lang->line('label_yes'); ?>&nbsp;&nbsp;&nbsp;
<input type="radio" name="comments" id="comments" value="0" checked="checked">&nbsp;&nbsp;<?php echo $this->lang->line('label_no'); ?>
<?php } ?></td>
</tr>
</table>
