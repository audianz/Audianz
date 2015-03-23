<select id="campaign_type" name="campaign_type" class="validate[required,custom[integer]] campaign_type">
	<?php if(count($campaigns)>0): ?>
	<option value="" ><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_campaign'); ?></option>
	<?php foreach($campaigns as $camp): ?>
		<?php if($sel_camp_id == $camp->campaignid):?>
			<option value="<?php echo $camp->campaignid; ?>" selected="selected"><?php echo $camp->campaignname; ?></option>
		<?php else: ?>
	<option value="<?php echo $camp->campaignid; ?>"><?php echo $camp->campaignname; ?></option>
	<?php 
		endif;
		endforeach;
	else: ?>
	<option value=""><?php echo $this->lang->line('label_no_campaings'); ?></option>
	<?php endif; ?>
</select>

<?php exit; ?>
