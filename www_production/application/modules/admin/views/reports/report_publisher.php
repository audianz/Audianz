<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/colorpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom/elements.js"></script>

    
    	<h1 class="pageTitle">Website Report</h1>
    	
    	<form id="report_websiteform" action="<?php echo site_url("admin/report_website/website_generate");?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    
                    
					<p>
					
					<?php
					echo form_label('Date', 'Date');
					$options = array(
									  'today'=> 'Today',
									  'yesterday'=> 'Yesterday',
									  'thisweek'   => 'Thisweek',
									  'last7days'=>'Last 7 days',
									  'thismonth'=>'Thismonth',
									  'lastmonth'=>'Lastmonth',
									  'all'=>'All statistics',
									  'specificdate'=>'Specific Date',
								);
					$js = 'id="date" onChange="selectdatebychange(this);"';
					echo form_dropdown('date', $options, $js);
					?>
					</p>
					
					<p> 
					<?php 
					echo form_label('From Date', 'startdate');
					$startdate = array(
										'name'        => 'startdate',
										'id'          => 'datepicker',
					
								);
					
					echo form_input($startdate);
					?>  
					</p>
					
					<p>   
					
					<?php 
					echo form_label('To Date', 'enddate');
					$enddate = array(
										'name'        => 'enddate',
										'id'          => 'datepicker2',
					
									);
					
					echo form_input($enddate);
					?>
					
					</p>
                  
                  
                  
                   <p>
                    	<?php
						echo form_label('Limitations','limitations');
                        <select name="advlimit" id="advlimit">
                        <option value="all">All Advertisers</option>
                        
						?>

                    </p>
                   
                   
                   <p>
                    	<?php
						echo form_label('Website Limitations','Website limitations');
                        $options["all"] ="All Websites";
                        $sel_weblimit = form_text((set_value('weblimit')));
 						foreach ($zones_list as $zonelimit):
						$weboptions[$zonelimit->zoneid]=$zonelimit->zonename;
						endforeach;
						
						$js = 'id="zonelimit"';
						echo form_dropdown('weblimit', $weboptions, $sel_weblimit,$js);
						?>


                    </p>
        
                    <p>
					
					<?php 
						$attribute= array(
											'class' => 'nopadding' 
										);
						echo form_label('Filtering Options','filteroption',$attribute);
						$filterdata = array(
											'name'        => 'country',
											'id'          => 'country',
											'value'       => '1',
											'checked'     => FALSE
										);
						
						echo form_checkbox($filterdata);
						?> Country
							
                    </p>
                
                    <p>
                    	<button>generate</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>