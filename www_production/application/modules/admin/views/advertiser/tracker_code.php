<h1><?php echo $this->lang->line('label_inventory_advertisers_trackers_code_page_title');?></h1>
<br>

<script type="text/javascript">
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#adv_add_fund").validationEngine();
		jQuery( "#tabs" ).tabs();
		
		jQuery('#textarea1').click(function() {
		  jQuery('#textarea1').select();
		});
		
		jQuery('#textarea2').click(function() {
		  jQuery('#textarea2').select();
		});
		
	});
	//////////// TABS /////////////////
	//	jQuery( "#tabs" ).tabs();

function selectall(txt)
{
	txt.focus();
	txt.select();
}

function tagrefresh() 
{
	jQuery('#adv_tracker_code').submit();		
}
</script>
<?php if($this->session->userdata('error_message') != ''): ?><div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->userdata('error_message'); ?></p></div>
 
<?php $this->session->unset_userdata('error_message'); endif; ?>
<form class="formular" id="adv_tracker_code" name"adv_tracker_code" action="#" method="post">
<div class="form_default"> 
<fieldset><legend><?php echo $this->lang->line('label_inventory_advertisers_trackers_code_page_title'); ?></legend>
<p><?php echo $this->lang->line('label_inventory_advertisers_trackers_code_page_c1'); ?> : <strong><?php echo $tracker_det->trackername; ?></strong></p>
<div id="tabs" class="tabs2" style="margin-top:5px;"> 
<ul>
    <li style="padding: 0px 0px; !important;"><a href="#tabs-1"><?php echo $this->lang->line('label_javascript_tag'); ?></a></li>
    <li style="padding: 0px 0px; !important;"><a href="#tabs-2"><?php echo $this->lang->line('label_image_tag'); ?></a></li>
</ul>
<?php
	$list['sel_tracker_id']	=$sel_tracker_id;
	$list['append_code']	=($this->input->post("append_code") ==0)?0:$this->input->post("append_code");
?>
<div id="tabs-1"><?php echo $this->load->view('tracker_javascript', $list); ?><br />
<button style="margin-left:0px !important;" type="button" class="button button_blue" onclick="tagrefresh()"><?php echo $this->lang->line("label_refresh"); ?></button>
<button style="margin-left:0px !important;" type="button" class="button button_blue" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
<br />
<br />
</div>
<div id="tabs-2"><?php echo $this->load->view('tracker_image', $list); ?></div>
</div><!-- tabs -->
</fieldset>
</div><!--form-->	
</form>
<script type="text/javascript">
function goToList(){
			document.location.href='<?php echo site_url("admin/inventory_advertisers"); ?>';
	}
</script>
