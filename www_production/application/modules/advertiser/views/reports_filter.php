<select name="campaign">
	<option value="all"><?php echo $this->lang->line('label_reports_all_campaign'); ?></option>
	<?php
		foreach($campaign as $cam)
		{?>
		<option value="<?php echo $cam->campaignid;?>"><?php echo $cam->campaignname;?></option>
		<?php
		} 
	?>
</select>
<?php exit;?>

