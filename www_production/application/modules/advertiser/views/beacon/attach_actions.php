<script>
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

		<h1 class="pagetitle" ><?php echo "Create Beacon Actions" ?></h1>
		<?php if($this->session->flashdata('message') !=''): ?>
			<div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					<?php echo $this->session->flashdata('message'); ?>
				</p>
			</div>
			<?php endif; ?>
		
		<div class="beacon_details">
			
			<fieldset>
		            <legend>Beacon details</legend>
		            <table cellpadding="10px" cellspacing="10px" border="0"  style="margin-top:2px">
				 
					<tr>
							<td style="word-wrap: break-word"><b>Beacon Name</b><br /> 
								
							</td>
							<td style="word-wrap: break-word"><b>UUID</b></td>
							<td style="word-wrap: break-word"><b>Location</b></td>
							<td style="word-wrap: break-word"><b>Major Id</b></td>
							<td style="word-wrap: break-word"><b>Minor Id</b></td>
							
							 
						</tr>
					
					<tr>
							<td style="word-wrap: break-word"><?php echo $beacon_info[0]->Beacon_name ; ?></td>
							<td style="word-wrap: break-word"><?php echo $beacon_info[0]->Beacon_UUID ; ?></td>
							<td style="word-wrap: break-word"><?php echo $location; ?></td>
							<td style="word-wrap: break-word"><?php echo $beacon_info[0]->Beacon_Major_ID ; ?></td>
							<td style="word-wrap: break-word"><?php echo $beacon_info[0]->Beacon_Minor_ID ; ?></td>
							
							 
						</tr>
						</table>
		
		</div>
		<form id="attach_actions" name="attach_actions" action="<?php echo site_url('advertiser/beacon/update_actions/'.$beacon_info[0]->Beacon_Seq_ID); ?>" method="post" >
		
		        <div class="form_attach_actions">
		        <fieldset>
		            <legend><?php echo "Enter Action Details"; ?></legend>
				
		            <p class="select_line" >
		                <label>Select PROXIMITY setting of the user for attaching to the beacon:<span style="color:red;">*</span></label>
		           </p>
		           <p class="radio_text">
			           <input type="radio" name="proximity" value="22" onclick="check_near();" <?php if($this->session->userdata('pid')==22) {  ?> checked <?php } ?> >Near
					   <input type="radio" name="proximity" value="11" style="margin-left:10%;" onclick="check_far();" <?php if($this->session->userdata('pid')==11) {  ?> checked <?php } ?> >Far
					   <input type="radio" name="proximity" value="33" style="margin-left:10%;" onclick="check_immediate();" <?php if($this->session->userdata('pid')==33) {  ?> checked <?php } ?> >Immediate
		           </p>
					
					<p>
						<label>Select one of the following actions to show:</label>
					</p>
		            <p>
		            </p>
						<select style=" max-width: 35%;min-width: 30%;width:80%;background-color:#F1ECEC" id="campaign"  name="campaign" >
							<option>Select Campaign</option>
							<?php for($i=0;$i<count($campaigns);$i++) { ?>
							<option <?php if($campaigns[$i]->campaignname==$this->session->userdata('campaign')) { ?> selected="selected" <?php } ?>value="<?php echo $campaigns[$i]->campaignid; ?>"><?php echo $campaigns[$i]->campaignname; ?></option>
							<?php } ?> 
						</select>
		            </p>
		            
		            <p>
						<label>Remarks</label>
		            </p>
		            <p>
						<input type="text" id="remarks" name="remarks" style=" max-width: 35%;min-width: 30%;width:60%;" value="<?php  echo $this->session->userdata('remarks'); ?> "> 
		            </p>
		            <p  style="margin-top:20px;">
		                <button style="margin-left:15px;"  >Attach the Action</button>
		                <button type="button" style="margin-left:15px;" onclick="javascript: resetForms();" >Clear</button>
		            </p>
		            
				</div>
		</form>
		
		<div class="attached_actions" >
			<fieldset>
		            <legend>Attached actions list</legend>
		      
		     <?php if ($action_list!=NULL) { ?>      
		     <table cellpadding="50px" cellspacing="15px" border="0" >
				 
					<tr>
							<td style="word-wrap: break-word"><b>Proximity</b><br /> 
								
							</td>
							<td style="word-wrap: break-word"><b>Campaign Details</b></td>
							<td style="word-wrap: break-word"><b>Remarks</b></td>
							<td style="word-wrap: break-word"><b>Date</b></td>
							
							 
						</tr>
					<?php for($i=0;$i<count($action_list);$i++) { ?>
						<tr >
							<td style="word-wrap: break-word">
							<?php  if($action_list[$i]->Proximity_Id==11) echo "Far";  else if($action_list[$i]->Proximity_Id==22) echo "Near" ; else echo "Immediate"; ?>
							</td>
							
							<td style="word-wrap: break-word"><?php echo $action_list[$i]->campaign; ?></td>
							<td style="word-wrap: break-word"><?php echo $action_list[$i]->Remarks; ?></td>
							<td style="word-wrap: break-word"><?php echo $action_list[$i]->Date; ?></td>
							<td style="word-wrap: break-word"><a href="<?php echo site_url('advertiser/beacon/edit_action/'.$action_list[$i]->Beacon_Seq_ID.'/'.$action_list[$i]->Proximity_Id); ?>">Edit</a> - <a href="<?php echo site_url('advertiser/beacon/remove_action/'.$action_list[$i]->Beacon_Seq_ID.'/'.$action_list[$i]->Proximity_Id); ?>" >Remove</a></td>
							 
						</tr>
					<?php } ?>
						
						
						
					</tbody> 
				</table>
			<?php } else { ?>
			<p style="margin-top:10px;font-size:14px" >No List Available</p>
			<?php } ?>
		
		</div>
		
		            
