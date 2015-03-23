<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/colorpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom/elements.js"></script>
<script>
function selectdatebychange(val)
{
	var d =val.options[val.selectedIndex].value;
    document.location.href='<?php echo site_url("admin/report_zone/selectday"); ?>/'+d;
}
/**jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#report_zoneform").validationEngine();
	}); 
*/
function checkdatediff()
{
var start=document.getElementById("datepicker").value;
var end=document.getElementById("datepicker2").value;

if(start=="" && end=="")
{
jAlert('<?php echo $this->lang->line('label_reports_select_date')?>','<?php echo $this->lang->line('label_reports_zones_alert')?>');
return false;
}
if(start!=" " && end!=" ")
{
smonth=start.substring(0,2);
sday=start.substring(3,5);
syear=start.substring(6,10);

start1=syear + "/" + smonth + "/" +sday;
var date1=new Date(start1);

emonth=end.substring(0,2);
eday=end.substring(3,5);
eyear=end.substring(6,10);

end1=eyear + "/" + emonth + "/" +eday;
var date2=new Date(end1);

var oneDay=1000*60*60*24;
//alert(date1);
//alert(date2);
var check=Math.ceil((date2.getTime()-date1.getTime())/oneDay);
if(check<0)
{
jAlert('<?php echo $this->lang->line('label_reports_select_startdate_greater')?>','<?php echo $this->lang->line('label_reports_zones_alert')?>');
return false;
}
else
{
document.forms.dateform.submit();
}
}
}	
	
</script>


<?php 
					
					if($this->uri->segment(3)!="zone_generate" && $this->uri->segment(4)!="specificdate")
					{
					if(isset($start) && isset($end))
					{
					$startval=$start;
					$end_val=$end;
					$disable="disabled=true";
					}
					}
					if(!isset($start) && !isset($end))
					{
					$startval=date('m/d/Y');
					$end_val=date('m/d/Y');
					$disable="disabled=true";
					}
					if($this->uri->segment(4)=="specificdate")
					{
					$startval='';
					$end_val='';
					$disable="";
					}
					?>

<h1 class="pageTitle"><?php echo $this->lang->line('label_reports_zones_page_title');?></h1>
    	<form id="report_zoneform" action="<?php echo site_url("admin/report_zone/zone_generate");?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <?php echo validation_errors(); ?>
					<p>
					
					<?php
					echo form_label($this->lang->line("label_reports_date"), 'Date');
					if(!isset($dateval))
					{
						$sel_date= set_value('date');
					}
					else
					{	
						$sel_date= $dateval;
					}
					$dateoptions = array(
									  'today'=> 'Today',
									  'yesterday'=> 'Yesterday',
									  'thisweek' => 'Thisweek',
									  'last7days'=>'Last 7 days',
									  'thismonth'=>'Thismonth',
									  'lastmonth'=>'Lastmonth',
									  'all'=>'All statistics',
									  'specificdate'=>'Specific Date',
								);
					$js = 'id="date" onChange="selectdatebychange(this);"';
					echo form_dropdown('date', $dateoptions, $sel_date, $js);
					?>
					</p>
					<p> 
					<?php 
					echo form_label($this->lang->line("label_reports_from_date"), 'startdate');?>
					<input type="text" id="datepicker" name="period_start" <?=$disable;?>  value="<?=$startval;?>" class="validate[required]" alt="<?php echo $this->lang->line("label_reports_alt");?>" > 
					</p>
					
					<p>   
					
					<?php 
					echo form_label($this->lang->line("label_reports_to_date"), 'enddate');?>
					<input type="text" id="datepicker2" name="period_end" <?=$disable;?> value="<?=$end_val;?>" class="validate[required]" alt="<?php echo $this->lang->line("label_reports_alt");?>">
					</p>
                   <p>
                    	
                        <?php
						echo form_label($this->lang->line("label_reports_limitations"),'limitations');
						$sel_zonelimit = form_text((set_value('zonelimit')));
 						foreach ($zones_list as $zonelimit):
						$options1[$zonelimit->zoneid]=$zonelimit->zonename;
						endforeach;
						
						$js = 'id="zonelimit"';
						echo form_dropdown('zonelimit', $options1, $sel_zonelimit,$js);
						?>
                    </p>
                   
                   
        
                    <p>
					
					<?php 
						$attribute= array(
											'class' => 'nopadding' 
										);
						echo form_label($this->lang->line("label_reports_filter_option"),'filteroption',$attribute);
						$filterdata = array(
											'name'        => 'country',
											'id'          => 'country',
											'value'       => '1',
											'checked'     => FALSE
										);
						
						echo form_checkbox($filterdata);
						?> <?php echo $this->lang->line('label_reports_country');?>
							
                    </p>
					<p>
					
					<input type="hidden" name="start" value="<?=$startval;?>">
					<input type="hidden" name="end" value="<?=$end_val;?>">
                	
					</p>
                
                    <p>
                    	<button onclick="return checkdatediff();"><?php echo $this->lang->line('label_reports_generate');?></button>
                    </p>
                   
				
				</fieldset>
            </div><!--form-->
            
        
        </form>