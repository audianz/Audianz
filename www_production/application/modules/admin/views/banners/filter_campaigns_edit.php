<select id="campaign_type" name="campaign_type" class="validate[required]" DISABLED>
	<?php if(count($campaigns)>0): ?>
	<option value=""><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_campaign'); ?></option>
	<?php foreach($campaigns as $camp):
		if($camp->campaignid==$bannercamp):
		$campvval = $camp->campaignid;
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
<input type="hidden" name="campaignID" id="campaignID" value="<?php echo $campvval; ?>" />
<?php exit; ?>
