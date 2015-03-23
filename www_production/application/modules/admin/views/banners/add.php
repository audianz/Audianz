<script
	src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js"
	type="text/javascript" charset="utf-8"></script>
<link
	rel="stylesheet"
	href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css"
	type="text/css" />
<script
	src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js"
	type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#form_add_banner").validationEngine();

		//document.getElementById('campaign_type').disabled = true;
		jQuery('#sec1').show();
		jQuery('#sec2').hide();
		jQuery('#sec3').hide();
		
		var adv_val = jQuery('#advertiser').val();
		var sel_camp_type = jQuery('#sel_camp_type').val();
		
		if(adv_val !='')
		{
			filter_campaigns(adv_val,sel_camp_type);
			jQuery('#campaign_type').val(sel_camp_type);
		}
		
		showBannerForm();
		
		
		
	});

	jQuery('.campaign_type').live('change',function(){
		
		var camp_type = jQuery(this).val();
		jQuery('#sel_camp_type').val(camp_type);
			
	});
	
	

	

	function showBannerForm(){

			if(jQuery('select[name=banner_type]', '#form_add_banner').val()=="0")
			{
				jQuery('#sec1').show();
				jQuery('#sec2').hide();
				jQuery('#sec3').hide();
			}
			else if(jQuery('select[name=banner_type]', '#form_add_banner').val()=="1")
			{
				jQuery('#sec1').hide();
				jQuery('#sec2').show();
				jQuery('#sec3').hide();
			}
			else if(jQuery('select[name=banner_type]', '#form_add_banner').val()=="2"){
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
		var advval = document.getElementById('advertiser').value;
		if(advval)
		{
			document.getElementById('campaign_type').disabled = false;
		}
		else
		{
			document.getElementById('campaign_type').value = '';
			document.getElementById('campaign_type').disabled = true;
		}
	}

	function filter_campaigns(adv_id,sel_camp_id)
	{
		if(adv_id)
		{
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url('admin/inventory_banners/filter_campaigns'); ?>/'+adv_id+'/'+sel_camp_id,
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
<h1 class="pageTitle">
	<?php echo $this->lang->line('label_inventory_add_banner'); ?>
</h1>



<?php
if($sel_camp == '0' || $sel_camp == '')
{
	if($sel_adv == '0' || $sel_adv == '')
	{
		?>
<form id="form_add_banner" name="form_add_banner"
	action="<?php echo site_url('admin/inventory_banners/add_banner_process'); ?>"
	method="post" enctype="multipart/form-data">

	<?php
	}
	else
	{
		?>
	<form id="form_add_banner" name="form_add_banner"
		action="<?php echo site_url('admin/inventory_banners/add_banner_process'); ?>"
		method="post" enctype="multipart/form-data">
		<?php
	}
}
else
{
	?>
		<form id="form_add_banner" name="form_add_banner"
			action="<?php echo site_url('admin/inventory_banners/add_banner_process/'.$sel_camp.'/'.$sel_adv); ?>"
			method="post" enctype="multipart/form-data">
			<?php
}
?>




			<div class="form_default">
				<fieldset>
					<legend>
						<?php echo $this->lang->line('label_inventory_add_banner'); ?>
					</legend>
					<?php //echo validation_errors();
if($this->session->userdata('banner_error') != ""):
?>
					<div class="notification msgerror">
						<a class="close"></a>
						<p>
							<?php echo $this->session->userdata('banner_error'); ?>
						</p>
					</div>
					<?php $this->session->unset_userdata('banner_error');
					endif;
					if($this->session->flashdata('banner_error') != ""):
					?>
					<div class="notification msgerror">
						<a class="close"></a>
						<p>
							<?php echo $this->session->flashdata('banner_error'); ?>
						</p>
					</div>
					<?php
					endif;
					if($this->session->userdata('banner_duplicate') != ""):
					?>
					<div style="height: 50px;" class="notification msgalert">
						<a class="close"></a>
						<p>
							<?php echo $this->session->userdata('banner_duplicate'); ?>
						</p>
					</div>
					<?php $this->session->unset_userdata('banner_duplicate');
					endif;
					?>
					<?php

					if($sel_adv !=0 && $sel_adv !=''):
					$disabled_adv = 'disabled="disabled"';
					else:
					$disabled_adv = '';
					endif;

					if($sel_camp !=0 && $sel_camp !=''):
					$disabled_camp = 'disabled="disabled"';
					else:
					$disabled_camp = '';
					endif;


					?>
					<p>
						<label for="name"><?php echo $this->lang->line('label_inventory_advertiser'); ?>
							<span style="color: red;">*</span> </label> <select
							name="advertiser" id="advertiser" class="validate[required]"
							onchange="check_advertiser_val(); filter_campaigns(this.value,0);"
							<?php echo $disabled_adv; ?>>
							<option value=""
							<?php echo form_text(set_select('advertiser', '', TRUE)); ?>>
								<?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_advertiser'); ?>
							</option>
							<?php if(count($advertiser)>0):
							for($i=0;$i<count($advertiser);$i++)
							{
								if($sel_adv ==$advertiser[$i]->clientid) {
						?>
							<option selected="selected"
								value="<?php echo form_text($advertiser[$i]->clientid); ?>"
								<?php echo set_select('advertiser', $advertiser[$i]->clientid); ?>>
								<?php echo $advertiser[$i]->contact_name; ?>
							</option>
							<?php } else { ?>
							<option
								value="<?php echo form_text($advertiser[$i]->clientid); ?>"
								<?php echo set_select('advertiser', $advertiser[$i]->clientid); ?>>
								<?php echo $advertiser[$i]->contact_name; ?>
							</option>
							<?php } ?>
							<?php } else: ?>
							<option value="">
								<?php echo $this->lang->line('label_no_advertisers'); ?>
							</option>
							<?php endif; ?>
						</select>


						<?php if($sel_adv !='0' && $sel_adv !=''): ?>
						<input type="hidden" name="advertiser"
							value="<?php echo $sel_adv; ?>" />
						<?php endif; ?>

					</p>

					<p>
						<label for="name"><?php echo $this->lang->line('label_inventory_campaigns'); ?>
							<span style="color: red;">*</span> </label>
					
					
					<div id="camp_list">
						<select id="campaign_type" name="campaign_type"
							class="validate[required]" <?php echo $disabled_camp; ?>>
							<?php if(count($campaigns)>0): ?>
							<option value=""
							<?php echo form_text(set_select('campaign_type', '',TRUE)); ?>>
								<?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_campaign'); ?>
							</option>
							<?php foreach($campaigns as $camp): ?>
							<?php if($sel_camp ==$camp->campaignid) { ?>
							<option selected="selected"
								value="<?php echo form_text($camp->campaignid); ?>"
								<?php echo set_select('campaign_type', $camp->campaignid); ?>>
								<?php echo form_text($camp->campaignname); ?>
							</option>
							<?php } else { ?>
							<option value="<?php echo form_text($camp->campaignid); ?>"
							<?php echo set_select('campaign_type', $camp->campaignid); ?>>
								<?php echo form_text($camp->campaignname); ?>
							</option>
							<?php } ?>
							<?php
							endforeach;
				else: ?>
							<option value="">
								<?php echo $this->lang->line('label_no_campaings'); ?>
							</option>
							<?php endif; ?>
						</select>

						<?php if($sel_camp !='0' && $sel_camp !=''): ?>
						<input type="hidden" name="campaign_type"
							value="<?php echo $sel_camp; ?>" />
						<?php endif; ?>

					</div>
					</p>
					<input type="hidden" name="sel_camp_type" id="sel_camp_type"
						value="<?php echo $sel_camp_type; ?>" />
					<p>
						<label for="name"><?php echo $this->lang->line('label_banner_type'); ?>
							<span style="color: red;">*</span> </label> <select
							onchange="showBannerForm()" id="banner_type" name="banner_type">
							<option value="0" <?php echo set_select('banner_type', '0'); ?>>
								<?php echo $this->lang->line('label_default_banner').' - '.$this->lang->line('label_image_banner'); ?>
							</option>
							<option value="1" <?php echo set_select('banner_type', '1'); ?>>
								<?php echo $this->lang->line('label_text_banner'); ?>
							</option>
							<option value="2" <?php echo set_select('banner_type', '2'); ?>>
								<?php echo $this->lang->line('label_tablet_banner'); ?>
							</option>
						</select>
					</p>


					<! ----------------------------- Image Banner --------------------------- >

					<span id="sec1">
						<h3>
							<?php echo $this->lang->line('label_add').' '.$this->lang->line('label_image_banner'); ?>
						</h3>
						<hr />
						<p>
							<label for="name"><?php echo $this->lang->line('label_banner_name'); ?>
								<span style="color: red;">*</span> </label> 
								<input class="validate[required,funcCall[alpha_numeric]] sf"
								type="text" name="img_banner_name"
								alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>"
								value="<?php echo form_text(set_value('img_banner_name')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('label_large').'('.$mob_screens[0]['width'].'X'.$mob_screens[0]['height'].')'; ?>
								<span style="color: red;">*</span> </label> 
								<input
								class="validate[required] sf" type="file" name="large_banner"
								alt="<?php echo $this->lang->line('label_alert_large_banner').'('.$mob_screens[0]['width'].'X'.$mob_screens[0]['height'].')'; ?>"
								value="<?php echo form_text(set_value('large_banner')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('label_medium').'('.$mob_screens[1]['width'].'X'.$mob_screens[1]['height'].')'; ?>
								<span style="color: red;">*</span> </label> <input
								class="validate[required] sf" type="file" name="medium_banner"
								alt="<?php echo $this->lang->line('label_alert_medium_banner').'('.$mob_screens[1]['width'].'X'.$mob_screens[1]['height'].')'; ?>"
								value="<?php echo form_text(set_value('medium_banner')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('label_small').'('.$mob_screens[2]['width'].'X'.$mob_screens[2]['height'].')'; ?>"
								<span style="color: red;">*</span> </label> <input
								class="validate[required] sf" type="file" name="small_banner"
								alt="<?php echo $this->lang->line('label_alert_small_banner').'('.$mob_screens[2]['width'].'X'.$mob_screens[2]['height'].')'; ?>"
								value="<?php echo form_text(set_value('small_banner')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('label_x_small').'('.$mob_screens[3]['width'].'X'.$mob_screens[3]['height'].')'; ?>"
								<span style="color: red;">*</span> </label> <input
								class="validate[required] sf" type="file" name="x_small_banner"
								alt="<?php echo $this->lang->line('label_alert_x_small_banner').'('.$mob_screens[3]['width'].'X'.$mob_screens[3]['height'].')'; ?>"
								value="<?php echo form_text(set_value('x_small_banner')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('label_text_below_image'); ?>
							</label> <input class="sf" type="text" name="img_banner_txt" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?>
								<span style="color: red;">*</span> </label> 
								<input
								 type="text"
								name="img_banner_url"
								alt="<?php echo $this->lang->line('label_alert_url'); ?>"
								value="<?php echo form_text(set_value('img_banner_url')=="")?'http://':set_value('img_banner_url'); ?>" />
						</p>
					</span>

					<! ----------------------------- Text Banner --------------------------- >
					<span id="sec2">
						<h3>
							<?php echo $this->lang->line('label_add').' '.$this->lang->line('label_text_banner'); ?>
						</h3>
						<hr />
						<p>
							<label for="name"><?php echo $this->lang->line('label_banner_name'); ?>
								<span style="color: red;">*</span> </label> <input type="text"
								name="txt_banner_name" class="validate[required] sf"
								alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>"
								value="<?php echo form_text(set_value('txt_banner_name')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?>
								<span style="color: red;">*</span> </label> <input type="text"
								name="txt_banner_url" class="validate[required,custom[url]] sf"
								alt="<?php echo $this->lang->line('label_alert_url'); ?>"
								value="<?php echo form_text((set_value('txt_banner_url')=="")?'http://':set_value('txt_banner_url')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('lable_banner_content'); ?>
								<span style="color: red;">*</span> </label>
							<textarea rows="5" cols="60" class="validate[required]"
								alt="<?php echo $this->lang->line('label_alert_banner_content'); ?>"
								name="txt_banner_content">
								<?php echo form_text(set_value('banner_content')); ?>
							</textarea>
						</p>

					</span>
					<! ----------------------------- Tablet Banner --------------------------- >
					<span id="sec3">
						<h3>
							<?php echo $this->lang->line('label_add').' '.$this->lang->line('label_tablet_banner'); ?>
						</h3>
						<hr />
						<p>
							<label for="name"><?php echo $this->lang->line('label_banner_name'); ?>
								<span style="color: red;">*</span> </label> <input type="text"
								name="tablet_banner_name" class="validate[required] sf"
								alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>"
								value="<?php echo form_text(set_value('tablet_banner_name')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?>
								<span style="color: red;">*</span> </label> <input type="text"
								name="tab_banner_url" class="validate[required,custom[url]] sf"
								alt="<?php echo $this->lang->line('label_alert_url'); ?>"
								value="<?php echo form_text((set_value('tab_banner_url')=="")?'http://':set_value('tab_banner_url')); ?>" />
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('lable_banner_size'); ?>
							</label> <select name="banner_size" class="validate[required]">
								<option
									value="<?php echo $this->lang->line('lable_banner_size_1'); ?>">
									<?php echo $this->lang->line('label_default').' - '.$this->lang->line('lable_banner_size_1'); ?>
								</option>
								<option
									value="<?php echo $this->lang->line('lable_banner_size_2'); ?>">
									<?php echo $this->lang->line('lable_banner_size_2'); ?>
								</option>
								<option
									value="<?php echo $this->lang->line('lable_banner_size_3'); ?>">
									<?php echo $this->lang->line('lable_banner_size_3'); ?>
								</option>
							</select>
						</p>
						<p>
							<label for="name"><?php echo $this->lang->line('lable_banner_image'); ?>
								<span style="color: red;">*</span> </label> <input type="file"
								name="tab_banner_image" id="tab_banner_image"
								class="validate[required] sf"
								alt="<?php echo $this->lang->line('label_alert_banner_image'); ?>"
								value="<?php echo form_text(set_value('tab_banner_image')); ?>" />
						</p>

					</span>

					<p>
						<button>
							<?php echo $this->lang->line('label_submit'); ?>
						</button>
						<?php
		if($sel_camp == '0' || $sel_camp == '')
		{
			if($sel_adv == '0' || $sel_adv == '')
			{
		?>
						<button type="button" style="margin-left: 10px;"
							onclick="document.location.href='<?php echo site_url("admin/inventory_banners");?>'">
							<?php echo $this->lang->line('label_cancel'); ?>
						</button>
						<?php
			}
			else
			{
			?>
						<button type="button" style="margin-left: 10px;"
							onclick="document.location.href='<?php echo site_url("admin/inventory_banners");?>'">
							<?php echo $this->lang->line('label_cancel'); ?>
						</button>
						<?php
			}
		}
		else
		{
		?>
						<button type="button" style="margin-left: 10px;"
							onclick="document.location.href='<?php echo site_url("admin/inventory_banners/listing_camp/all/".$sel_camp."/".$sel_adv);?>'">
							<?php echo $this->lang->line('label_cancel'); ?>
						</button>
						<?php
		}
		?>
					</p>


				</fieldset>
			</div>
			<!--form-->
		</form>
