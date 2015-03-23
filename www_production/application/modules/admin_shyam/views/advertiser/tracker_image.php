<p><textarea rows="12" cols="150" readonly="yes" name="textarea" id="textarea2" onMouseOver="selectall(this);" onmousedown="selectall(this);">
<!--/*
  *
  *
  *  If this tag is being served on a secure (SSL) page, you must replace
  *  'http://domain.com/www/delivery/...'
  * with
  *  'https://domain.com/www/delivery/...'
  *
  *  To help prevent caching of this tracker beacon, if possible,
  *  Replace %%RANDOM_NUMBER%% with a randomly generated number (or timestamp)
  *
  *
  *  Place this code at the top of your thank-you page, just after the <body> tag.
  *
  */-->
<div id='m3_tracker_<?php echo $sel_tracker_id; ?>' style='position: absolute; left: 0px; top: 0px; visibility: hidden;'>
<img src='<?php echo base_url(); ?><?php echo $this->config->item('ads_code'); ?>/delivery/ti.php?trackerid=<?php echo $sel_tracker_id; ?>&amp;cb=%%RANDOM_NUMBER%%' width='0' height='0' alt='' />
</div>
</textarea>
</p>
