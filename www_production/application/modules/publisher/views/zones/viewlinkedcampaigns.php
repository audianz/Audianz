<script>
		jQuery(document).ready(function(){
			// binds form submission and fields to the validation engine
			//jQuery("#adv_add_fund").validationEngine();
			jQuery( "#tabs" ).tabs({selected: <?php echo $linked_type; ?>});
			
			jQuery('#textarea1').click(function() {
			  jQuery('#textarea1').select();
			});
			
			jQuery('#textarea2').click(function() {
			  jQuery('#textarea2').select();
			});
			
		});
	//////////// TABS /////////////////
	//	jQuery( "#tabs" ).tabs();
</script>

<h3><?php echo $this->lang->line("label_inventory_zone_banners_title");?></h3>
<div style="margin-top:5px;" id="tabs" class="tabs2">
  <ul>
    <li style="padding: 0px 0px !important;"><a href="#tabs-1"><strong><?php echo $this->lang->line("label_inventory_zone_linked_by_banners");?></strong></a></li>
    <li style="padding: 0px 0px !important;"><a href="#tabs-2"><strong><?php echo $this->lang->line("label_inventory_zone_linked_individual_banners");?></strong></a></li>
  </ul>
  <div id="tabs-1">
	<?php if($this->session->flashdata('success_message') != ''): ?>
		<div style="margin:5px;" class="notification msgsuccess"><a class="close"></a>
	  		<p><?php echo $this->session->flashdata('success_message'); ?></p>
		</div>
	<?php endif; ?>
    <form name="show_linked_campaigns" id="show_linked_campaigns" method="post" action='<?php echo site_url("publisher/zones/process/linkedbycampaigns");?>'>
      <div class="sTableOptions">
        <?php //echo $this->pagination->create_links(); ?>
        <a   class="button save1"><span><?php echo $this->lang->line('label_save_update'); ?></span></a> </div>
      <!--sTableOptions-->
      <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        <colgroup>
        <col class="head0" width="3%" />
        <col class="head1" width="25%" />
        <col class="head0" width="25%" />
        </colgroup>
        <tr>
          <td align="center"><input type="checkbox" class="checkall" <?php echo (count($associated['campaign_id'])==count($campaigns_list))?"checked":""; ?> /></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_advertiser");?></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_campaign");?></td>
        </tr>
      </table>
      
      <div class="sTableWrapper" width="100%">
        <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
          <colgroup>
          <col class="con0" width="3%" />
          <col class="con1" width="25%" />
          <col class="con0" width="25%" />
          </colgroup>
          <?php	
					if($campaigns_list != false):
						foreach($campaigns_list as $row):
						?>

          <tr>
            <td align="center"><input <?php echo ($associated != FALSE AND in_array($row->campaignid,$associated['campaign_id']))?"checked":""; ?> type="checkbox"  id="sel_campaign" name="sel_campaign[]" value="<?php echo $row->campaignid; ?>"/></td>
            <td><?php echo $row->clientname; ?></td>
            <td><?php echo $row->campaignname; ?></td>
          </tr>
          <?php		
						endforeach;
					else:
						?>
          <tr>
            <td id="data" class="update_red" colspan="4" align="center"><?php echo $this->lang->line("label_inventory_zone_linked_campaign_not_found");?></td>
          </tr>
          <?php
					endif;
				?>
        </table>
      </div>
      <!--sTableWrapper-->
      <input type="hidden" name="sel_zone_id" id="sel_zone_id" value="<?php echo $sel_zone_id; ?>" />
      <input type="hidden" name="affiliateid" id="affiliateid" value="<?php echo $affiliateid; ?>" />
      <br/>
      <br/>
      <h3><?php echo $this->lang->line("label_inventory_zone_linked_banners_with_zone");?></h3>
      <br/>
      <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        <colgroup>
        <col class="head0" width="20%" />
        <col class="head1" width="25%" />
        <col class="head0" width="25%" />
        </colgroup>
        <tr>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_campaign");?></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_banner");?></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_showbanner");?></td>
        </tr>
      </table>
      <div class="sTableWrapper" width="100%">
        <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
          <colgroup>
          <col class="con0" width="20%" />
          <col class="con1" width="25%" />
          <col class="con0" width="25%" />
          </colgroup>
          <?php
					$atts = array(
					  'width'      => '400',
					  'height'     => '400',
					  'scrollbars' => 'yes',
					  'status'     => 'yes',
					  'resizable'  => 'yes',
					  'screenx'    => '0',
					  'screeny'    => '0'
					);
				
					if($linked_banners_list != false):
						foreach($linked_banners_list as $banner_data):
						$img_path =$this->config->item('ads_url');
						
			if($banner_data->storagetype == "web" &&  $banner_data->campaignname !="" && $banner_data->description != ""):
							?>
          <tr>
            <td><?php echo $banner_data->campaignname; ?></td>
            <td><?php echo $banner_data->description; ?></td>
            <td><a class="view" href="<?php echo base_url().$this->config->item('ads_url').$banner_data->filename; ?>"><?php echo $this->lang->line("label_show_banner");?></a><?php //echo anchor_popup(base_url().$img_path.$banner_data->filename,'Show Banner',$atts);?></td>
          </tr>
          <?php		
							elseif($banner_data->campaignname != "" && $banner_data->description != ""):
							?>
          <tr>
            <td><?php echo $banner_data->campaignname; ?></td>
            <td><?php echo $banner_data->description; ?></td>
            <td><a class="view" href="<?php echo site_url('publisher/zones/show_text_banner/'.$banner_data->bannerid); ?>"><?php echo $this->lang->line("label_show_banner");?></a><?php //echo anchor_popup(site_url('inventory_zones/show_text_banner/'.$banner_data->bannerid),'Show Banner',$atts);?></td>
          </tr>
          <?php
							endif;						
						endforeach;
					else:
						?>
          <tr>
            <td id="data" class="update_red" colspan="3" align="center"><?php echo $this->lang->line("label_inventory_zone_linked_banners_not_found");?></td>
          </tr>
          <?php
					endif;
					?>
        </table>
      </div>
      <!--sTableWrapper-->
    </form>
    <script type="text/javascript">
					/**
					 * Delete selected items in a table
					**/
					jQuery('.sTableOptions .save1').click(function(){
						var empt = true;
						jQuery('#userlist input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) 
						{
							jConfirm('<?php echo $this->lang->line("label_confirmation_for_remove_previous_link_campaign"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
							if(r) {
								jQuery("#show_linked_campaigns").submit();
							}
							});
						} 
						
						
						else {
							jConfirm('<?php echo $this->lang->line("lang_zone_campaign_link"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
							
							if(r) {
								jQuery("#show_linked_campaigns").submit();
							}
							});
						}
					});	
			</script>
  </div>
  <div id="tabs-2"> 
  	<?php if($this->session->flashdata('success_message1') != ''): ?>
		<div style="margin:5px;" class="notification msgsuccess"><a class="close"></a>
	  		<p><?php echo $this->session->flashdata('success_message1'); ?></p>
		</div>
	<?php endif; ?>
    <form name="show_linked_banners" id="show_linked_banners" method="post" action='<?php echo site_url("publisher/zones/process/linkedbybanners");?>'>
      <div class="sTableOptions">
        <?php //echo $this->pagination->create_links(); ?>
        <a class="button save2"><span><?php echo $this->lang->line('label_save_update'); ?></span></a> </div>
      <!--sTableOptions-->
      <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        <colgroup>
        <col class="head0" width="3%" />
        <col class="head1" width="25%" />
        <col class="head0" width="25%" />
		<col class="head1" width="25%" />
        </colgroup>
        <tr>
          <td align="center"><input type="checkbox" class="checkall" <?php echo (count($associated['ad'])==count($banners_list))?"checked":""; ?> /></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_advertiser");?></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_campaign");?></td>
		  <td><?php echo $this->lang->line("label_inventory_zone_banners_banner");?></td>
        </tr>
      </table>
      <div class="sTableWrapper" width="100%">
        <table cellpadding="0" cellspacing="0" class="sTable" id="banner_list" width="100%">
          <colgroup>
          <col class="con0" width="3%" />
          <col class="con1" width="25%" />
          <col class="con0" width="25%" />
		   <col class="con1" width="25%" />
          </colgroup>
          <?php
			if($banners_list != false):
				foreach($banners_list as $row):							
				?>
          <tr>
            <td align="center"><input <?php echo ($associated != FALSE AND in_array($row->bannerid,$associated['ad']))?"checked":""; ?> type="checkbox"  id="sel_banner" name="sel_banner[]" value="<?php echo $row->bannerid; ?>"/></td>
            <td><?php echo $row->clientname; ?></td>
            <td><?php echo $row->campaignname; ?></td>
			<td><?php echo $row->description; ?></td>
          </tr>
          <?php		
			endforeach;
		  else:
		  ?>
          <tr>
            <td id="data" class="update_red" colspan="4" align="center"><?php echo $this->lang->line("label_inventory_zone_linked_banners_not_found");?></td>
          </tr>
          <?php
					endif;
				?>
        </table>
      </div>
      <!--sTableWrapper-->
      <input type="hidden" name="sel_zone_id" id="sel_zone_id" value="<?php echo $sel_zone_id; ?>" />
      <input type="hidden" name="affiliateid" id="affiliateid" value="<?php echo $affiliateid; ?>" />
      <br/>
      <br/>
      <h3><?php echo $this->lang->line("label_inventory_zone_linked_banners_with_zone");?></h3>
      <br/>
      <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        <colgroup>
        <col class="head0" width="20%" />
        <col class="head1" width="25%" />
        <col class="head0" width="25%" />
        </colgroup>
        <tr>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_campaign");?></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_banner");?></td>
          <td><?php echo $this->lang->line("label_inventory_zone_banners_showbanner");?></td>
        </tr>
      </table>
      <div class="sTableWrapper" width="100%">
        <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
          <colgroup>
          <col class="con0" width="20%" />
          <col class="con1" width="25%" />
          <col class="con0" width="25%" />
          </colgroup>
          <?php
					$atts = array(
					  'width'      => '400',
					  'height'     => '400',
					  'scrollbars' => 'yes',
					  'status'     => 'yes',
					  'resizable'  => 'yes',
					  'screenx'    => '0',
					  'screeny'    => '0'
					);
				
					if($linked_banners_list != false):
						foreach($linked_banners_list as $banner_data):
						$img_path =$this->config->item('ads_url');
							if($banner_data->storagetype == "web" &&  $banner_data->campaignname !="" && $banner_data->description != ""):
							?>
          <tr>
            <td><?php echo $banner_data->campaignname; ?></td>
            <td><?php echo $banner_data->description; ?></td>
            <td><a class="view" href="<?php echo base_url().$this->config->item('ads_url').$banner_data->filename; ?>"><?php echo $this->lang->line("label_show_banner");?></a><?php //echo anchor_popup(base_url().$img_path.$banner_data->filename,'Show Banner',$atts);?></td>
          </tr>
          <?php		
							elseif($banner_data->campaignname != "" && $banner_data->description != ""):
							?>
          <tr>
            <td><?php echo $banner_data->campaignname; ?></td>
            <td><?php echo $banner_data->description; ?></td>
            <td><a class="view" href="<?php echo site_url('publisher/zones/show_text_banner/'.$banner_data->bannerid); ?>"><?php echo $this->lang->line("label_show_banner");?></a><?php //echo anchor_popup(site_url('inventory_zones/show_text_banner/'.$banner_data->bannerid),'Show Banner',$atts);?></td>
          </tr>
          <?php
							endif;						
						endforeach;
					else:
						?>
          <tr>
            <td id="data" class="update_red" colspan="3" align="center"><?php echo $this->lang->line("label_inventory_zone_linked_banners_not_found");?></td>
          </tr>
          <?php
					endif;
					?>
        </table>
      </div>
      <!--sTableWrapper-->
    </form>
    <script type="text/javascript">
					/**
					 * Delete selected items in a table
					**/
					jQuery('.sTableOptions .save2').click(function(){
						var empt = true;
						jQuery('#banner_list input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						
					if(empt == true) 
						{
							
							jConfirm('<?php echo $this->lang->line("label_confirmation_for_remove_previous_link_banner"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r1){
							if(r1) {
								jQuery("#show_linked_banners").submit();
							}
							else
							{
								document.location.href='';
							}
							});
						} 
						
						
						else {
							jConfirm('<?php echo $this->lang->line("lang_zone_banner_link"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r1){
							
							if(r1) {
								jQuery("#show_linked_banners").submit();
							}
							else
							{
								jQuery(".checkall").attr('checked',false);
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.
								document.location.href='';
							}
							});
						}
					});
			</script>  
  </div>
</div>
<!-- tabs -->

