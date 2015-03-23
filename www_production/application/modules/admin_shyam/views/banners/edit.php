<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/gallery.js"></script>
<script type="text/javascript">
	
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#form_edit_banner").validationEngine();
		
		document.getElementById('campaign_type').disabled = true;
		jQuery('#sec1').show();
		jQuery('#sec2').hide();
		jQuery('#sec3').hide(); 
		var sel_btype = document.getElementById('sel_banner_type').value;
		if(sel_btype=='-1')
		{
			jQuery('#sec1').hide();
			jQuery('#sec2').show();
			jQuery('#sec3').hide();
		}
		else if(sel_btype=='-2')
		{
			jQuery('#sec1').show();
			jQuery('#sec2').hide();
			jQuery('#sec3').hide();
		}
		else if(sel_btype=='-3')
		{
			jQuery('#sec1').hide();
			jQuery('#sec2').hide();
			jQuery('#sec3').show();
		}
		
		check_advertiser_val();
		var aid = document.getElementById('advertiserlist').value;
		var cid = document.getElementById('ajax_camp_id').value;
		filter_campaigns_edit(aid,cid);
		
	});
	
	function showBannerForm(){
			
			if(jQuery('select[name=banner_type]', '#form_edit_banner').val()=="0")
			{
				jQuery('#sec1').show();
				jQuery('#sec2').hide();
				jQuery('#sec3').hide();
			}
			else if(jQuery('select[name=banner_type]', '#form_edit_banner').val()=="1")
			{
				jQuery('#sec1').hide();
				jQuery('#sec2').show();
				jQuery('#sec3').hide();
			}
			else if(jQuery('select[name=banner_type]', '#form_edit_banner').val()=="2"){
				jQuery('#sec1').hide();
				jQuery('#sec2').hide();
				jQuery('#sec3').show();
			}
			else{
				jQuery('#sec1').show();
				jQuery('#sec2').hide();
				jQuery('#sec3').hide();
			}
	}
	
	function check_advertiser_val()
	{
		var advval = document.getElementById('advertiserlist').value;
		if(advval)
		{
			document.getElementById('campaign_type').disabled = false;
		}
		else
		{
			document.getElementById('campaign_type').value = '';
			document.getElementById('campaign_type').disabled = true;
		}
		document.getElementById('advertiser').value = advval;
	}
	
	function filter_campaigns_edit(adv_id,cid)
	{
		if(adv_id!='' && cid!='')
		{	
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url('admin/inventory_banners/filter_campaigns_edit'); ?>/'+adv_id+'/'+cid,
				success: function(msg){
					jQuery("#camp_list").html(msg);
				}
			}); 			
		}
		else
		{
			return false;	
		}	
	}

    function goToList()
    {
        document.location.href='<?php echo site_url('admin/inventory_banners'); ?>';
    }
    function alpha_numeric(field, rules, i, options)
	{
		if(field.val() != '')
		{
			var keyword		=	field.val();
			var alpha			=	/^([a-z0-9_])+$/i;
			if(!alpha.test(keyword))
			{
				//return  '<?php echo $this->lang->line('label_static_page_contains_invalid'); ?>';
				return  'Pleae enter only alphabets, numbers, underscores';
				
			}
		}
	}
 </script>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_edit_banner'); ?></h1>
<?php
	if(count($bannercontent)>0):
		$banner = $bannercontent[0];
	endif; 

?>
<?php 
		if($sel_camp == '0' || $sel_camp == '')
		{
			if($sel_adv == '0' || $sel_adv == '')
			{
		?>         <form id="form_edit_banner" name="form_edit_banner" action="<?php echo site_url('admin/inventory_banners/edit_banner_process/'.$banner->bannerid); ?>" method="post" enctype="multipart/form-data">       
			
		<?php
			}
			else
			{
			?>
		 	<form id="form_edit_banner" name="form_edit_banner" action="<?php echo site_url('admin/inventory_banners/edit_banner_process/'.$banner->bannerid); ?>" method="post" enctype="multipart/form-data">
			<?php
			}		
		}
		else
		{
		?>
		<form id="form_edit_banner" name="form_edit_banner" action="<?php echo site_url('admin/inventory_banners/edit_banner_process/'.$banner->bannerid.'/'.$sel_camp.'/'.$sel_adv); ?>" method="post" enctype="multipart/form-data">
		
		<?php
		}
		?>


<div class="form_default">
	<fieldset>
		<legend><?php echo $this->lang->line('label_inventory_edit_banner'); ?></legend>
		<?php echo validation_errors();
		if($this->session->userdata('banner_error') != ""):
		?> 
			<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->userdata('banner_error'); ?></p></div>
			<?php $this->session->unset_userdata('banner_error'); 
		endif;
		if($this->session->flashdata('banner_error') != ""):
		?> 
			<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('banner_error'); ?></p></div>
		<?php 
		endif;
		if($this->session->userdata('banner_error') != ""):
		?> 
			<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->userdata('banner_error'); ?></p></div>
		<?php 
		endif;
		if($this->session->userdata('banner_duplicate') != ""):
		?> 
			<div  style="height:50px;" class="notification msgalert"><a class="close"></a><p><?php echo $this->session->userdata('banner_duplicate'); ?></p></div>
			<?php $this->session->unset_userdata('banner_duplicate'); 
		endif;

		?>
		<input type="hidden" name="sel_banner_type" id="sel_banner_type" value="<?php echo $banner->master_banner; ?>" />
		<input type="hidden" name="ajax_camp_id" id="ajax_camp_id" value="<?php echo $banner->cmid; ?>" />
		
		 <p>
			<label for="name"><?php echo $this->lang->line('label_inventory_advertiser'); ?> <span style="color:red;" >*</span></label>
			<select name="advertiserlist"  id="advertiserlist" class="validate[required,custom[integer]]" onchange="check_advertiser_val(); filter_campaigns(this.value);" DISABLED>
				<option value="" <?php echo set_select('advertiser', '', TRUE); ?>><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_advertiser'); ?></option>
				<?php if(count($advertiser)>0):
						for($i=0;$i<count($advertiser);$i++):
							if($advertiser[$i]->clientid == $banner->clid): 
							$advID = $advertiser[$i]->clientid;
							?>							
							<option selected="selected" value="<?php echo $advertiser[$i]->clientid; ?>" <?php echo set_select('advertiser', $advertiser[$i]->clientid); ?>><?php echo $advertiser[$i]->contact_name; ?></option>
							<?php 
							else: ?>
							<option value="<?php echo $advertiser[$i]->clientid; ?>" <?php echo set_select('advertiser', $advertiser[$i]->clientid); ?>><?php echo $advertiser[$i]->contact_name; ?></option>
							
						<?php endif; 
						endfor;
				else: ?>
				<option value=""><?php echo $this->lang->line('label_no_advertisers'); ?></option>
			<?php endif; ?>
			</select>
			<input type="hidden" name="advertiser" id="advertiser" value="<?php echo $advID; ?>" />
        </p>
		
		<p>
			<label for="name"><?php echo $this->lang->line('label_inventory_campaigns'); ?> <span style="color:red;" >*</span></label>
			<div id="camp_list">
			<select id="campaign_type" name="campaign_type" class="validate[required]">
				<?php if(count($campaigns)>0): ?>
				<option value=""><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_campaign'); ?></option>
				<?php foreach($campaigns as $camp): 
						if($camp->campaignid == $banner->cmid):	
						?>
						<option selected="selected" value="<?php echo $camp->campaignid; ?>"><?php echo $camp->campaignname; ?></option>
					<?php else: ?>
						<option value="<?php echo $camp->campaignid; ?>"><?php echo $camp->campaignname; ?></option>
						<?php
						endif;
					endforeach;
				else: ?>
				<option value=""><?php echo $this->lang->line('label_no_campaings'); ?></option>
				<?php endif; ?>
			</select>			
			</div>
		</p>
		
		<p>
			<label for="name"><?php echo $this->lang->line('label_banner_type'); ?> <span style="color:red;" >*</span></label>
			<select onchange="showBannerForm()" id="banner_type" name="banner_type" DISABLED>
				<option value="0" <?php echo ($banner->master_banner==-2)?'selected':'';?>><?php echo $this->lang->line('label_default').' - '.$this->lang->line('label_image_banner'); ?></option>
				<option value="1" <?php echo ($banner->master_banner==-1)?'selected':'';?>><?php echo $this->lang->line('label_text_banner'); ?></option>
				<option value="2" <?php echo ($banner->master_banner==-3)?'selected':'';?>><?php echo $this->lang->line('label_tablet_banner'); ?></option>
			</select>
			<?php if($banner->master_banner==-1): ?>
			<input type="hidden" name="bannertypeID" id="bannertypeID" value="1" />
			<?php elseif($banner->master_banner==-2): ?>
			<input type="hidden" name="bannertypeID" id="bannertypeID" value="0" />
			<?php else: ?>
			<input type="hidden" name="bannertypeID" id="bannertypeID" value="2" />
			<?php endif; ?>
		</p>
		<! ----------------------------- Image Banner --------------------------- >
                <?php if($banner->master_banner=='-2'): ?>
		<span id="sec1">
			<h3><?php echo $this->lang->line('label_edit').' '.$this->lang->line('label_image_banner'); ?></h3>
			<hr/>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_banner_name'); ?> <span style="color:red;" >*</span></label>
				<input class="validate[required,funcCall[alpha_numeric]] sf" type="text" name="img_banner_name" alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>" value="<?php echo $banner->description; ?>"/>
			</p>	
			 <p>
				<label for="name"><?php echo $this->lang->line('label_large').'('.$mob_screens[0]['width'].'X'.$mob_screens[0]['height'].')'; ?> <span style="color:red;" >*</span></label>
				<input class="sf" type="file" name="large_banner" alt="<?php echo $this->lang->line('label_alert_large_banner'); ?>" value="<?php echo set_value('large_banner'); ?>"/>
				<?php if($bannerimage[3]->filename): ?>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().$this->config->item('ads_url').$bannerimage[3]->filename; ?>" class="view"><?php echo $this->lang->line('label_banner_preview'); ?></a><?php endif; ?>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_medium').'('.$mob_screens[1]['width'].'X'.$mob_screens[1]['height'].')'; ?> <span style="color:red;" >*</span></label>
				<input class="sf" type="file" name="medium_banner" alt="<?php echo $this->lang->line('label_alert_medium_banner'); ?>" value="<?php echo set_value('medium_banner'); ?>"/>
				<?php if($bannerimage[2]->filename): ?>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().$this->config->item('ads_url').$bannerimage[2]->filename; ?>" class="view"><?php echo $this->lang->line('label_banner_preview'); ?></a><?php endif; ?>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_small').'('.$mob_screens[2]['width'].'X'.$mob_screens[2]['height'].')'; ?> <span style="color:red;" >*</span></label>
				<input class="sf" type="file" name="small_banner" alt="<?php echo $this->lang->line('label_alert_small_banner'); ?>" value="<?php echo set_value('small_banner'); ?>"/>
				<?php if($bannerimage[1]->filename): ?>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().$this->config->item('ads_url').$bannerimage[1]->filename; ?>" class="view"><?php echo $this->lang->line('label_banner_preview'); ?></a><?php endif; ?>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_x_small').'('.$mob_screens[3]['width'].'X'.$mob_screens[3]['height'].')'; ?> <span style="color:red;" >*</span></label>
				<input class="sf" type="file" name="x_small_banner" alt="<?php echo $this->lang->line('label_alert_x_small_banner'); ?>" value="<?php echo set_value('x_small_banner'); ?>"/>
				<?php if($bannerimage[0]->filename): ?>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().$this->config->item('ads_url').$bannerimage[0]->filename; ?>" class="view"><?php echo $this->lang->line('label_banner_preview'); ?></a><?php endif; ?>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_text_below_image'); ?></label>
				<input class="sf" type="text" name="img_banner_txt" value="<?php echo $banner->bannertext; ?>" />
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?> <span style="color:red;" >*</span></label>
				<input class="validate[required,custom[url]] sf" type="text" name="img_banner_url" alt="<?php echo $this->lang->line('label_alert_url'); ?>" value="<?php echo $banner->url; ?>"/>
			</p>
		</span>
                <?php endif;
                if($banner->master_banner=='-1'):
                ?>
		<! ----------------------------- Text Banner --------------------------- >
		<span id="sec2">
			<h3><?php echo $this->lang->line('label_edit').' '.$this->lang->line('label_text_banner'); ?></h3>
			<hr/>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_banner_name'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="txt_banner_name" class="validate[required,funcCall[alpha_numeric]] sf" alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>" value="<?php echo $banner->description; ?>"/>
			</p>	
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="txt_banner_url" class="validate[required,custom[url]] sf" alt="<?php echo $this->lang->line('label_alert_url'); ?>" value="<?php echo $banner->url; ?>"/>
			</p>
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_content'); ?> <span style="color:red;" >*</span></label>
				<textarea rows="5" cols="60" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_banner_content'); ?>"  name="txt_banner_content"><?php echo $banner->bannertext; ?></textarea>
			</p>

		</span>
                <?php
                endif;
                if($banner->master_banner=='-3'):
                ?>
		<! ----------------------------- Tablet Banner --------------------------- >
		<span id="sec3">
			<h3><?php echo $this->lang->line('label_edit').' '.$this->lang->line('label_tablet_banner'); ?></h3>
			<hr/>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_banner_name'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="tablet_banner_name" class="validate[required,funcCall[alpha_numeric]] sf" alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>" value="<?php echo $banner->description; ?>"/>
			</p>	
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="tab_banner_url" class="validate[required,custom[url]] sf" alt="<?php echo $this->lang->line('label_alert_url'); ?>" value="<?php echo (set_value('tab_banner_url')=="")?$banner->url:set_value('tab_banner_url'); ?>"/>
			</p>
			<p>
				<?php $dimension = $banner->width.' X '.$banner->height; ?>
				<label for="name"><?php echo $this->lang->line('lable_banner_size'); ?></label>
				<select name="banner_size_sel" class="validate[required]" DISABLED>
					<option <?php echo ($dimension==$this->lang->line('lable_banner_size_1'))?'selected':''; ?> value="<?php echo $this->lang->line('lable_banner_size_1'); ?>"><?php echo $this->lang->line('label_default').' - '.$this->lang->line('lable_banner_size_1'); ?></option>
					<option <?php echo ($dimension==$this->lang->line('lable_banner_size_2'))?'selected':''; ?> value="<?php echo $this->lang->line('lable_banner_size_2'); ?>"><?php echo $this->lang->line('lable_banner_size_2'); ?></option>
					<option <?php echo ($dimension==$this->lang->line('lable_banner_size_3'))?'selected':''; ?> value="<?php echo $this->lang->line('lable_banner_size_3'); ?>"><?php echo $this->lang->line('lable_banner_size_3'); ?></option>
				</select>
				<input type="hidden" name="banner_size" value="<?php echo $dimension; ?>" />
			</p>
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_image'); ?> </label>
				<input type="file" name="tab_banner_image" id="tab_banner_image" class="sf" alt="<?php echo $this->lang->line('label_alert_banner_image'); ?>" value="<?php echo set_value('tab_banner_image'); ?>"/>
				<?php if($banner->filename): ?>&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().$this->config->item('ads_url').$banner->filename; ?>" class="view"><?php echo $this->lang->line('label_banner_preview'); ?></a><?php endif; ?>
			</p>
		</span>
		<?php endif; ?>
		<p>
        	<button><?php echo $this->lang->line('label_submit'); ?></button>
                <?php 
		if($sel_camp == '0' || $sel_camp == '')
		{
			if($sel_adv == '0' || $sel_adv == '')
			{
		?>                
			<button type="button" style="margin-left:10px;" onclick="document.location.href='<?php echo site_url("admin/inventory_banners");?>'" ><?php echo $this->lang->line('label_cancel'); ?></button>
		<?php
			}
			else
			{
			?>
		<button type="button" style="margin-left:10px;" onclick="document.location.href='<?php echo site_url("admin/inventory_banners");?>'" ><?php echo $this->lang->line('label_cancel'); ?></button>
			<?php
			}		
		}
		else
		{
		?>
		<button type="button" style="margin-left:10px;" onclick="document.location.href='<?php echo site_url("admin/inventory_banners/listing_camp/all/".$sel_camp."/".$sel_adv);?>'" ><?php echo $this->lang->line('label_cancel'); ?></button>
		<?php
		}
		?> 
        </p>
		
	</fieldset>
	
</div><!--form-->
</form>

