<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#form_add_banner").validationEngine();
		
		//document.getElementById('campaign_type').disabled = true;
		jQuery('#sec1').show();
		jQuery('#sec2').hide();
		jQuery('#sec3').hide();                
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
	
	function filter_campaigns(adv_id)
	{
		if(adv_id)
		{	
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url('admin/inventory_banners/filter_campaigns'); ?>/'+adv_id,
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
        var campaignid = document.getElementById('campaignid').value;
        document.location.href='<?php echo site_url('advertiser/banners/listing'); ?>/'+campaignid;
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


<form id="form_add_banner" name="form_add_banner" action="<?php echo site_url('advertiser/banners/add_banner_process/'.$campaignid); ?>" method="post" enctype="multipart/form-data">

    <input type="hidden" id="campaignid" value="<?php echo $campaignid; ?>" />
	<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_add_banner'); ?></h1>
<div class="form_default">
	<fieldset>
		<legend><?php echo $this->lang->line('label_inventory_add_banner'); ?></legend>
		<?php //echo validation_errors();
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
		if($this->session->userdata('banner_duplicate') != ""):
		?> 
			<div  style="height:50px;" class="notification msgalert"><a class="close"></a><p><?php echo $this->session->userdata('banner_duplicate'); ?></p></div>
			<?php $this->session->unset_userdata('banner_duplicate'); 
		endif;
		?>
		
		
		<p>
			<label for="name"><?php echo $this->lang->line('label_banner_type'); ?> <span style="color:red;" >*</span></label>
			<select onchange="showBannerForm()" id="banner_type" name="banner_type">
				<option value="0"><?php echo $this->lang->line('label_default_banner').' - '.$this->lang->line('label_image_banner'); ?></option>
				<option value="1"><?php echo $this->lang->line('label_text_banner'); ?></option>
				<option value="2"><?php echo $this->lang->line('label_tablet_banner'); ?></option>
			</select>
		</p>
		<! ----------------------------- Image Banner --------------------------- >
		<span id="sec1">
			<h3><?php echo $this->lang->line('label_add').' '.$this->lang->line('label_image_banner'); ?></h3>
			<hr/>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_banner_name'); ?> <span style="color:red;" >*</span></label>
				<input class="validate[required,funcCall[alpha_numeric]] sf" type="text" name="img_banner_name" alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>" value="<?php echo form_text(set_value('img_banner_name')); ?>"/>
			</p>	
			 <p>
				<label for="name"><?php echo $this->lang->line('label_large').'('.$mob_screens[0]['width'].'X'.$mob_screens[0]['height'].')'; ?> <span style="color:red;" >*</span></label>
				<input class="validate[required] sf" type="file" name="large_banner" alt="<?php echo $this->lang->line('label_alert_large_banner').'('.$mob_screens[0]['width'].'X'.$mob_screens[0]['height'].')'; ?>" value="<?php echo form_text(set_value('large_banner')); ?>"/>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_medium').'('.$mob_screens[1]['width'].'X'.$mob_screens[1]['height'].')'; ?> <span style="color:red;" >*</span></label>
				<input class="validate[required] sf" type="file" name="medium_banner" alt="<?php echo $this->lang->line('label_alert_medium_banner').'('.$mob_screens[1]['width'].'X'.$mob_screens[1]['height'].')'; ?>" value="<?php echo form_text(set_value('medium_banner')); ?>"/>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_small').'('.$mob_screens[2]['width'].'X'.$mob_screens[2]['height'].')'; ?> <span style="color:red;" >*</span></label>
				<input class="validate[required] sf" type="file" name="small_banner" alt="<?php echo $this->lang->line('label_alert_small_banner').'('.$mob_screens[2]['width'].'X'.$mob_screens[2]['height'].')'; ?>" value="<?php echo form_text(set_value('small_banner')); ?>"/>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_x_small').'('.$mob_screens[3]['width'].'X'.$mob_screens[3]['height'].')'; ?>" <span style="color:red;" >*</span></label>
				<input class="validate[required] sf" type="file" name="x_small_banner" alt="<?php echo $this->lang->line('label_alert_x_small_banner').'('.$mob_screens[0]['width'].'X'.$mob_screens[0]['height'].')'; ?>" value="<?php echo form_text(set_value('x_small_banner')); ?>"/>
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_text_below_image'); ?></label>
				<input class="sf" type="text" name="img_banner_txt" />
			</p>
			 <p>
				<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?> <span style="color:red;" >*</span></label>
				<input class="validate[required,custom[url]] sf" type="text" name="img_banner_url" alt="<?php echo $this->lang->line('label_alert_url'); ?>" value="<?php echo form_text(set_value('img_banner_url')=="")?'http://':set_value('img_banner_url'); ?>"/>
			</p>
		</span>
		<! ----------------------------- Text Banner --------------------------- >
		<span id="sec2">
			<h3><?php echo $this->lang->line('label_add').' '.$this->lang->line('label_text_banner'); ?></h3>
			<hr/>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_banner_name'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="txt_banner_name" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>" value="<?php echo form_text(set_value('txt_banner_name')); ?>"/>
			</p>	
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="txt_banner_url" class="validate[required,custom[url]] sf" alt="<?php echo $this->lang->line('label_alert_url'); ?>" value="<?php echo form_text((set_value('txt_banner_url')=="")?'http://':set_value('txt_banner_url')); ?>"/>
			</p>
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_content'); ?> <span style="color:red;" >*</span></label>
				<textarea rows="5" cols="60" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_banner_content'); ?>"  name="txt_banner_content"><?php echo form_text(set_value('banner_content')); ?></textarea>
			</p>

		</span>
		<! ----------------------------- Tablet Banner --------------------------- >
		<span id="sec3">
			<h3><?php echo $this->lang->line('label_add').' '.$this->lang->line('label_tablet_banner'); ?></h3>
			<hr/>
			 <p>
				<label for="name"><?php echo $this->lang->line('label_banner_name'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="tablet_banner_name" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_banner_name'); ?>" value="<?php echo form_text(set_value('tablet_banner_name')); ?>"/>
			</p>	
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_url'); ?> <span style="color:red;" >*</span></label>
				<input type="text" name="tab_banner_url" class="validate[required,custom[url]] sf" alt="<?php echo $this->lang->line('label_alert_url'); ?>" value="<?php echo form_text((set_value('tab_banner_url')=="")?'http://':set_value('tab_banner_url')); ?>"/>
			</p>
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_size'); ?></label>
				<select name="banner_size" class="validate[required]">
					<option value="<?php echo $this->lang->line('lable_banner_size_1'); ?>"><?php echo $this->lang->line('label_default').' - '.$this->lang->line('lable_banner_size_1'); ?></option>
					<option value="<?php echo $this->lang->line('lable_banner_size_2'); ?>"><?php echo $this->lang->line('lable_banner_size_2'); ?></option>
					<option value="<?php echo $this->lang->line('lable_banner_size_3'); ?>"><?php echo $this->lang->line('lable_banner_size_3'); ?></option>
				</select>
			</p>
			<p>
				<label for="name"><?php echo $this->lang->line('lable_banner_image'); ?> <span style="color:red;" >*</span></label>
				<input type="file" name="tab_banner_image" id="tab_banner_image" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_banner_image'); ?>" value="<?php echo form_text(set_value('tab_banner_image')); ?>"/>
			</p>		
		
		</span>
		
		<p>
        	<button><?php echo $this->lang->line('label_submit'); ?></button>
                <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
        </p>
		
		
	</fieldset>
</div><!--form-->
</form>
