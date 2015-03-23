<?php

/* Header */ 
$this->load->view("includes/header");

/* Menu */ 
$this->load->view("includes/menu");

/* Account */ 
$this->load->view("includes/account");

/* Sidebar */ 
$this->load->view("includes/sidebar");

?>
<div style="margin-left:18%"> 
<form id="add_store" name="form_edit_campaign" action="<?php echo site_url('admin/beacon/store_setup'); ?>" method="post" >
			<h1 class="pageTitle" style="margin-top:2%" ><?php echo "Beacon Setup Master" ?></h1>
			        <div class="form_default">
                     		
				
			        <fieldset>
				<?php
				if($this->session->userdata('setup') != ""): ?>
				<div class="notification msgsuccess"><a class="close"></a><?php echo $this->session->userdata('setup'); ?>
				</div>
		                <?php
					$this->session->unset_userdata('setup');
			            endif;	
            			?>
						
				

				
			            <legend><?php echo "Enter Details"; ?></legend>
			            
			<p>
				<label>Beacon Lot<span style="color:red;">*</span></label>
				<input type="text" name="beacon_lot"  id="beacon_lot" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" />
			</p>
			
			<p>
				<label>Invoice Details<span style="color:red;">*</span></label>
				<input type="text" name="invoice"  id="invoice" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" />
			</p>
			
			<p>
				<label>Date of Invoice<span style="color:red;">*</span></label>
				<input type="text" name="invoice_date"  id="invoice_date" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" />
			</p>
			<p>
				<label>Vendor Name<span style="color:red;">*</span></label>
				<input type="text" name="vendor_name"  id="vendor_name"  class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf">
			</p>
			<p>
				<label>Vendor Code</label>
				<input type="text" name="vendor_code"  id="vendor_code" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf"/>
			</p>
			<p>
				<label>Number of Beacons in Lot<span style="color:red;">*</span></label>
				<input type="text" name="no_of_beacons"  id="no_of_beacons" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" />
			</p>
		
			<p>
				<label>Beacon series<span style="color:red;">*</span></label>
				<input type="text" name="beacon_series"  id="beacon_series" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf"
				 />
			</p>
			
		<!--	<p>
				<label>System Date and Time<span style="color:red;">*</span></label>
				<input type="text" name="date_time"  id="date_time" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf"/>
			</p>   -->
			
			<p>
				<label>UUID<span style="color:red;">*</span></label>
				<input type="text" name="uuid"  id="uuid" />
			</p>
			
			<p>
			    <button ><?php echo $this->lang->line('label_submit'); ?></button>
			    <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
			</p>
			
			        </fieldset>
			   </div><!--form-->
	</form>
	
	</div>
	
	
			      
			            
					

