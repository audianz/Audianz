<script>
function goTolist()
{
window.location.href="<?php echo base_url();?>index.php/advertiser/beacon";
}
function check_near()
{
	'<?php for($i=0;$i<count($action_list);$i++) { 
		if($action_list[$i]->Proximity_Id==22) { ?>'
		//alert( "Near action already Exist" );
		jAlert('<center><?php echo "Please edit from Action List below" ?></center>','<?php echo "Near action already Exist"; ?>');
		'<?php }  } ?>'
}

function check_far()
{
	'<?php for($i=0;$i<count($action_list);$i++) { 
		if($action_list[$i]->Proximity_Id==11) { ?>'
		jAlert('<center><?php echo "Please edit from Action List below" ?></center>','<?php echo "Far action already Exist"; ?>');
		'<?php }  } ?>'
}

function check_immediate()
{
	'<?php for($i=0;$i<count($action_list);$i++) { 
		if($action_list[$i]->Proximity_Id==33) { ?>'
		jAlert('<center><?php echo "Please edit from Action List below" ?></center>','<?php echo "Immediate action already Exist"; ?>');
		'<?php }  } ?>'
}
</script>
<script>
	$(document).ready(function () {
    resetForms();
});

function resetForms() {
    document.forms['attach_actions'].reset();
}
</script>

<h1 class="pagetitle"  style="margin-bottom:5px;" ><?php echo "Create Beacon Actions" ?></h1>
		<?php if($this->session->flashdata('message') !=''): ?>
			<div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('message'); ?>
				</p>
			</div>
			<?php endif; ?>
			
			<div class="configure_beacon" >
		        <fieldset >
		            <legend><?php echo "Enter Action details"; ?></legend>
		            <p>
		            <p><label ><?php echo "UUID"; ?></label>
		             <input type="text" class="validate[required] sf" style="background-color:#E0E0E0;margin-left:7% "  name="uuid"  id="uuid" value="<?php echo $beacon_info[0]->Beacon_UUID ; ?>"  readonly />
		             <label ><?php echo "Major Id"; ?></label>
		             <input type="text" name="cus_id"  id="cus_id" style="background-color:#E0E0E0 ;margin-left:4%;width:10%;" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_campname'); ?>" value="<?php echo $beacon_info[0]->Beacon_Major_ID ; ?>" readonly />
		             <label ><?php echo "Minor Id";?></label>
		             <input type="text" name="agency_id"  id="agency_id" style="background-color:#E0E0E0 ;width:10%;"  class="validate[required] sf"  value="<?php echo $beacon_info[0]->Beacon_Minor_ID ; ?>" readonly />
		             </p> 
		             
		             <p>
		            <label><?php echo "Beacon Name" ?></label>
		            <input type="text" name="major_id"  id="major_id" style="background-color:#E0E0E0;margin-left:25px; " class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" 
						value="<?php echo $beacon_info[0]->Beacon_name ; ?>" readonly />
						
					<label><?php echo "Beacon Location" ?></label>
		            <input type="text" name="minor_id"  id="location" style="background-color:#E0E0E0;" class="validate[required,min[1],max[9999],custom[integer]] sf"  alt="<?php echo "Please Enter Minor Id between 1 to 9999" ?>"  value="<?php echo $location; ?>" />
		            
		            </p>
		            
		          </form>
		         <form id="attach_actions" name="attach_actions" action="<?php echo site_url('advertiser/beacon/update_actions/'.$beacon_info[0]->Beacon_Seq_ID); ?>" method="post" >
		
		        <div class="form_attach_actions">
		        
		            <p >
		                <label style="margin-left:4px">Select PROXIMITY setting of the user for attaching to the beacon:<span style="color:red;">*</span></label>
		           </p>
		           <p class="radio_text">
			           <input type="radio" name="proximity" value="22" onclick="check_near();" <?php if($this->session->userdata('pid')==22) {  ?> checked <?php } ?> >Near
					   <input type="radio" name="proximity" value="11" style="margin-left:10%;" onclick="check_far();" <?php if($this->session->userdata('pid')==11) {  ?> checked <?php } ?> >Far
					   <input type="radio" name="proximity" value="33" style="margin-left:10%;" onclick="check_immediate();" <?php if($this->session->userdata('pid')==33) {  ?> checked <?php } ?> >Immediate
		           </p>
					
					<p>
						<label style="margin-left:4px" >Select one of the following campaigns to show:</label>
					</p>
		            <p style="margin-top:5px" >
		          
						<select style=" max-width:35%;min-width: 30%;width:80%;background-color:#F1ECEC" id="campaign"  name="campaign" >
							<option>Select Campaign</option>
							<?php for($i=0;$i<count($campaigns);$i++) { ?>
							<option <?php if($campaigns[$i]->campaignname==$this->session->userdata('campaign')) { ?> selected="selected" <?php } ?>value="<?php echo $campaigns[$i]->campaignid; ?>"><?php echo $campaigns[$i]->campaignname; ?></option>
							<?php } ?> 
						</select>
		            	<label style="margin-left:3.5%;" >Remarks</label>
		            
		            
						<input type="text" id="remarks" name="remarks" style=" max-width: 35%;min-width: 30%;width:40%;margin-left:3.8%" value="<?php  echo $this->session->userdata('remarks'); ?> "> 
		            </p>
		            <p  style="margin-top:20px;">
		                <button style="margin-left:15px;"  >Attach the Action</button>
		                <button type="button" style="margin-left:15px;" onclick="javascript: resetForms();" >Clear</button>
		                 <button type="button" style="margin-left:55%;" onclick="javascript:goTolist();" >Back to Beacon List</button>
		            </p> 
		            
				</div>
		</form>
		</fieldset>
		
		<div class="attached_actions" >
			<fieldset>
		            <legend>Attached actions list</legend>
		      
		     <?php if ($action_list!=NULL) { ?>      
		    
<table class="flatTable" style="margin-top:2%;">
  
  <tr class="headingTr" style="font-weight:bold; text-align:center;" >
    <td>Proximity</td>
    <td>Campaign Details</td>
    <td>Remarks</td>
    <td>Date</td>
    <td></td>
    
  </tr>
  
  <?php for($i=0;$i<count($action_list);$i++) { ?>
  
  <tr style="text-align:center;" >
    <td><?php  if($action_list[$i]->Proximity_Id==11) echo "Far";  else if($action_list[$i]->Proximity_Id==22) echo "Near" ; else echo "Immediate"; ?></td>
    <td><?php echo $action_list[$i]->campaign; ?></td>
    <td><?php echo $action_list[$i]->Remarks; ?></td>
    <td><?php echo $action_list[$i]->Date; ?></td>
    <td><a href="<?php echo site_url('advertiser/beacon/edit_action/'.$action_list[$i]->Beacon_Seq_ID.'/'.$action_list[$i]->Proximity_Id); ?>">Edit</a> - <a href="<?php echo site_url('advertiser/beacon/remove_action/'.$action_list[$i]->Beacon_Seq_ID.'/'.$action_list[$i]->Proximity_Id); ?>" >Remove</a></td>
    
  </tr>
  
  

<?php } ?>
</table>

<?php } else { ?>
			<p style="margin-top:10px;font-size:14px" >No List Available</p>
			<?php } ?>


		
		</div>
		
		            
