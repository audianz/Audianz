<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#editzoneform").validationEngine();
	});
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/

	

</script>
<style>
.tes
{
	width:300px !important;
}
</style>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_editsdk_fieldset');?></h1>
 <form id="editzoneform"  action="<?php echo site_url("publisher/zones/update_zones/".$zoneid);?>" method="post">
 <?php foreach($record as $row): ?>      
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line("label_inventory_editsdk_fieldset");?> </legend>
                  			<?php echo validation_errors(); ?>

					<p>
						<label for="zonename"><?php echo $this->lang->line("label_inventory_mobile_sdk_url");?></label>
						<input type="text" name="zonename" id="zonename" alt="<?php echo $this->lang->line('label_inventory_mobile_sdk'); ?>" value="<?php echo base_url();?>ads/www/delivery/" class="tes" readonly/>
						
						<input type="hidden" name="zoneid" id="zoneid" value="<?php echo $row->zoneid;?>" readonly/>
						
						<input type="hidden" name="affiliateid" value="<?php echo $row->affiliateid;endforeach;?>"readonly/>
					</p>
					
					<p>
						<label for="zonename"><?php echo $this->lang->line("label_inventory_mobile_app_sdk");?></label>
						<input type="text" name="zonename" id="zonename" alt="<?php echo $this->lang->line('label_inventory_mobile_sdk'); ?>" value="<?php echo form_text((set_value('zoneid') != '')?set_value('zoneid'):$row->zoneid);?>" readonly/>
						<br/><br/>
					</p>
					
<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
<colgroup>
<col class="head0" width="30%" />
<col class="head1" width="35%" />
<col class="head0" width="15%" />
<col class="head1" width="15%" />
<col class="head0" width="15%" />
</colgroup>
<tr>
<td width="10%" align="center"><?php echo $this->lang->line('label_inventory_mobile_app')?></td>
<td width="35%" align="center"><?php echo $this->lang->line('label_inventory_mobile_sdk')?></td>

<p>
*note:-If you already downloaded sdk, please note the above details
</p>
           
</tr>
</table>
 <table cellpadding="0" cellspacing="0" class="sTable" width="100%">
<colgroup>
    <col class="con0" width="30%" />
    <col class="con1" width="35%" />
    <col class="con0" width="15%" />
    <col class="con1" width="15%" />
    <col class="con0" width="15%" />
</colgroup>
<tr>
    
    <td align="center">Android</td>
  	<td align="center"><a href="<?php echo site_url("admin/inventory_zones_sdk/download/"); ?>">Android SDK ver1.0   </a> </td>
</tr>
<tr>
<td align="center">IOS</td>
<td align="center"><a href="<?php echo site_url("admin/inventory_zones_sdk/downloadios/"); ?>">IOS SDK ver5.1.0 </a> </td>
</tr>
<!--
<tr>
<td align="center">Windows</td>
<td align="center"><a href="<?php echo site_url("admin/inventory_zones_sdk/downloadios/"); ?>">Windows SDK ver2.1.0 </a> </td>
</tr>

<tr>
<td align="center">Symbian</td>
<td align="center"><a href="<?php echo site_url("admin/inventory_zones_sdk/downloadios/"); ?>">Symbian SDK ver0.1.0</a> </td>
</tr>
<tr>
<td align="center">BlackBerry</td>
<td align="center"><a href="<?php echo site_url("admin/inventory_zones_sdk/downloadios/"); ?>">BlackBerry SDK ver8.1.0 </a> </td>
</tr>
<tr>
<td align="center">Bada</td>
<td align="center"><a href="<?php echo site_url("admin/inventory_zones_sdk/downloadios/"); ?>">Bada SDK ver4.1.0 </a> </td>
</tr>
-->

</table>

        
		    
                </fieldset>
            </div><!--form-->
  	     </form>
		
