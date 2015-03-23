<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>

<?php
/* Header */ 
$this->load->view("includes/advertiser/header");

/* Menu */ 
$this->load->view("includes/advertiser/menu");

/* Account */ 
$this->load->view("includes/advertiser/account");

/* Sidebar */ 
$this->load->view("includes/advertiser/sidebar");

?>
<!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/custom/reports.js"></script>
<script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/custom/dashboard.js"></script> -->

<!--Load the AJAX API-->

<SCRIPT type="text/javascript">
	 
	function noBack() {
//alert("test");
                
 window.history.go(+1)  }
noBack();
window.inhibited_load=noBack;
window.onpageshow=function(evt){if(evt.persisted)noBack()}
window.inhibited_unload=function(){void(0)}

  

</SCRIPT>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
		
        // Create the data table.
        var data = new google.visualization.arrayToDataTable([
        	 ['<?php echo $this->lang->line("label_month"); ?>','<?php echo $this->lang->line("label_spend"); ?>'],
			 <?php 
		 	$report	=	$six_monthly_stat["stat_list"]; 
			for($i=0,$j=5;$i<6;$i++,$j--):
			$month = date ('n',strtotime ( "-".$j." month"));
		 	?>
		 		['<?php echo date ( "M Y",strtotime ( "-".$j." month"));?>', 
				<?php echo isset($report[$month]["SPEND"])?$report[$month]["SPEND"]:"0";?>],
		 <?php 
		 endfor; ?>	
        ]);

        // Set chart options
        var options = {
                       'width':350,
                       'height':250,
                       'chartArea':{left:20,top:0,width:"100%",height:"100%"},
					    'is3D':true
                       };

		// Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_spend'));
        chart.draw(data, options);        
      }
    </script>
	<script type="text/javascript">
	 google.load('visualization', '1', {'packages': ['geochart']});
	 google.setOnLoadCallback(drawRegionsMap);

	  function drawRegionsMap() {
		var data = google.visualization.arrayToDataTable([
		  ['<?php echo $this->lang->line("label_country"); ?>', '<?php echo $this->lang->line("label_impressions"); ?>','<?php echo $this->lang->line("label_clicks"); ?>'],
		  <?php
		  foreach($map['stat_list'] as $glob):
		  ?>
		  ['<?php echo $glob['COUNTRY']; ?>', <?php echo $glob['IMP']; ?>, <?php echo $glob['CLK']; ?>],
		  <?php endforeach; ?>
		]);

		var options = {};

		var chart = new google.visualization.GeoChart(document.getElementById('chart_global'));
		chart.draw(data, options);
	};
	</script>
	<script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $this->lang->line("label_days"); ?>', '<?php echo $this->lang->line("label_impressions"); ?>', '<?php echo $this->lang->line("label_unique_impressions"); ?>', '<?php echo $this->lang->line("label_clicks"); ?>', '<?php echo $this->lang->line("label_unique_clicks"); ?>', '<?php echo $this->lang->line("label_conversions"); ?>','<?php echo $this->lang->line("label_call"); ?>','<?php echo $this->lang->line("label_web"); ?>','<?php echo $this->lang->line("label_map"); ?>','<?php echo $this->lang->line("label_ctr"); ?>(%)'],
         <?php 
		 	$report	=	$pastsdays_stat['stat_list']; 
			for($i=0,$j=6;$i<7;$i++,$j--):
			$date = date ( 'Y-m-d',strtotime ( '-'.$j.' day' ));
		 	?>
		 		['<?php echo date ( 'jS F Y',strtotime ( '-'.$j.' day' ));?>', 
				<?php echo isset($report[$date]['IMP'])?$report[$date]['IMP']:'0'; ?>, 
				<?php echo isset($report[$date]['UIMP'])?$report[$date]['UIMP']:'0'; ?>,
				<?php echo isset($report[$date]['CLK'])?$report[$date]['CLK']:'0';?>,
				<?php echo isset($report[$date]['UCLK'])?$report[$date]['UCLK']:'0';?>,
				<?php echo isset($report[$date]['CON'])?$report[$date]['CON']:'0';?>,
				<?php echo isset($click_to_action_pastdays[$date]['CALL'])?$click_to_action_pastdays[$date]['CALL']:'0';?>,
				<?php echo isset($click_to_action_pastdays[$date]['WEB'])?$click_to_action_pastdays[$date]['WEB']:'0';?>,
				<?php echo isset($click_to_action_pastdays[$date]['MAP'])?$click_to_action_pastdays[$date]['MAP']:'0';?>,				
				<?php echo isset($report[$date]['CTR'])?$report[$date]['CTR']:'0';?>],
		 <?php 
		 endfor; ?>	
        ]);

        var options = {
          'chartArea':{left:50,top:20,width:"70%",height:"75%"},
          vAxis: {title: ""},
          hAxis: {title: "<?php echo $this->lang->line('label_last_seven_days_comp'); ?>"},
          seriesType: "bars",
          series: {8: {type: "line"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('combo_chart'));
        chart.draw(data, options);
      }
      google.setOnLoadCallback(drawVisualization);
    </script>

<div class="maincontent">
	<div class="two_third maincontent_inner ">
    	<div class="left">
        <!-- START WIDGET LIST -->
            <ul class="widgetlist"> 				
                <li><a href="<?php echo site_url('advertiser/myaccount'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/settings.png" alt="Report Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_settings"); ?></span></a></li>
                <li><a href="<?php echo site_url('advertiser/payments'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/payments.png" alt="Mail Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_payments"); ?></span></a></li>
                <li><a href="<?php echo site_url('advertiser/refer_friends'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/refer.png" alt="Events Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_refer_friends"); ?></span></a></li>                
                <li><a href="<?php echo site_url('advertiser/advertiser_notifications'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/document.png" alt="Events Icon" /><span><?php echo $this->lang->line("label_notifications"); ?></span></a></li>
                <li><a href="<?php echo site_url('login/logout'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/logout.png" alt="Media Icon" /><span><?php echo $this->lang->line("label_logout"); ?></span></a></li>
            </ul>
            <!-- END WIDGET LIST -->
            <div class="clear"></div>
            <!-- widgetbox2 -->
            
		
			<?php 
				 $monthly_stat =	$this_month_stat['tot_val']; 
				$daily_stat	=	$daily_stat['tot_val'];	
				
			?>
			
			<div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_daily_statistics'); ?></span></h3>
                <div class="content nopadding ohidden">
                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
                        <thead>
                            <tr>
                                <td align="center"><?php echo $this->lang->line('label_date'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_impressions'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_unique_imp'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_clicks'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_unique_clicks'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_conversions'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_call'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_web'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_map'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_ctr'); ?></td>
								<td align="center"><?php echo $this->lang->line('label_spend'); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center"><?php echo $this->lang->line('label_today'); ?>(<?php echo date_format(date_create(date('Y-m-d')),'jS F Y'); ?>)</td>
                                <td align="center"><?php echo ($daily_stat['IMP']!='0')?$daily_stat['IMP']:'0'; ?></td>
                                <td align="center"><?php echo ($daily_stat['UIMP']!='0')?$daily_stat['UIMP']:'0'; ?></td>
                                <td align="center"><?php echo ($daily_stat['CLK']!='0')?$daily_stat['CLK']:'0'; ?></td>
                                <td align="center"><?php echo ($daily_stat['UCLK']!='0')?$daily_stat['UCLK']:'0'; ?></td>
                                <td align="center"><?php echo ($daily_stat['CON']!='0')?$daily_stat['CON']:'0'; ?></td>
                                <td align="center"><?php echo ($click_to_action_daily['CALL']!='0')?$click_to_action_daily['CALL']:'0'; ?></td>
                                <td align="center"><?php echo ($click_to_action_daily['WEB']!='0')?$click_to_action_daily['WEB']:'0'; ?></td>
                                <td align="center"><?php echo ($click_to_action_daily['MAP']!='0')?$click_to_action_daily['MAP']:'0'; ?></td>
                                
                                <td align="center"><?php echo ($daily_stat['CTR']!='0')?$daily_stat['CTR'].'%':'0.00%'; ?></td>
				<td align="center">$<?php echo ($daily_stat['SPEND']!='0')?number_format($daily_stat['SPEND'],2):'0.00'; ?></td>
                            </tr>
                           </tbody>
                    </table>
                </div><!-- content -->
            </div>
			
		<div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_monthly_statistics'); ?></span></h3>
                <div class="content nopadding ohidden">
                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
                        <thead>
                            <tr>
                                <td align="center"><?php echo $this->lang->line('label_month'); ?></td>
                           	<td align="center"><?php echo $this->lang->line('label_impressions'); ?></td>
                           	<td align="center"><?php echo $this->lang->line('label_unique_imp'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_clicks'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_unique_clicks'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_conversions'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_call'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_web'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_map'); ?></td>
                              
                                <td align="center"><?php echo $this->lang->line('label_ctr'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_spend'); ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center"><?php echo date('F'); ?></td>
                                <td align="center"><?php echo ($monthly_stat['IMP']!='0')?$monthly_stat['IMP']:'0'; ?></td>
                                <td align="center"><?php echo ($monthly_stat['UIMP']!='0')?$monthly_stat['UIMP']:'0'; ?></td>
                                <td align="center"><?php echo ($monthly_stat['CLK']!='0')?$monthly_stat['CLK']:'0'; ?></td>
                                <td align="center"><?php echo ($monthly_stat['UCLK']!='0')?$monthly_stat['UCLK']:'0'; ?></td>
                                <td align="center"><?php echo ($monthly_stat['CON']!='0')?$monthly_stat['CON']:'0'; ?></td>
                                <td align="center"><?php echo ($click_to_action_monthly['CALL']!='0')?$click_to_action_monthly['CALL']:'0'; ?></td>
                                <td align="center"><?php echo ($click_to_action_monthly['WEB']!='0')?$click_to_action_monthly['WEB']:'0'; ?></td>
                                <td align="center"><?php echo ($click_to_action_monthly['MAP']!='0')?$click_to_action_monthly['MAP']:'0'; ?></td>
                                <td align="center"><?php echo ($monthly_stat['CTR']!='0')?$monthly_stat['CTR'].'%':'0.00%'; ?></td>
				<td align="center">$<?php echo ($monthly_stat['SPEND']!='0')?number_format($monthly_stat['SPEND'],2):'0.00'; ?></td>
                            </tr>
                           </tbody>
                    </table>
                </div><!-- content -->
            </div>

				
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_weekly_statistics'); ?></span></h3>
                <div class="content">
                	<div id="combo_chart" style="width: 700px; height:300px;"></div>
                </div>
            </div>
           <br />

       		 <?php if(!empty($map['stat_list'])):?>
		   <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_global_statistics'); ?></span></h3>
                <div class="content">
						<div id="chart_global" style="width: 700px; height: 400px;"></div>
				</div><!-- content -->
           </div><!-- widgetbox2 -->
		<?php endif; ?>           

         </div><!-- left -->            
    </div><!-- two_third -->
    
    <div class="one_third last">
    	<div class="right">
            <div class="sTableOptions">
		<center><h2 style="color:#CC655A;"><?php echo $this->lang->line('label_welcome'); ?> <?php echo $this->session->userdata('session_advertiser_contact'); ?></h2></center>
	    </div><!--sTableOptions-->	
	    <div class="sTableWrapper">
	    </div>	
            <br/>
            	
            <div class="widgetbox">
                <h3><span><?php echo strtoupper($this->lang->line('label_spend')); ?></span></h3>
                <div class="content">
                    
                    <h1 class="prize">$<?php echo number_format($spend_today,2); ?></h1>
                    <p><?php echo $this->lang->line('label_estimate_spend_of_day'); ?> <strong>$<?php echo number_format(ceil($spend_today),2); ?></strong></p>
                	
                    <br />
                    
                	<div class="one_half bright">
						<h2 class="prize">$<?php echo number_format($spend_yesterday,2); ?></h2>
                        <small><?php echo $this->lang->line('label_yesterday_spend'); ?></small>
                    </div><!--one_half-->
                    
                    <div class="one_half last">
                    	<h2 class="prize">$<?php echo number_format($spend_tismonth,2); ?></h2>
                        <small><?php echo date('F'); ?> <?php echo $this->lang->line('label_month_spend'); ?></small>
                    </div><!--one_half-->
                    
                    
                </div><!-- content -->
            </div><!-- widgetbox -->
		
            <?php if(!empty($six_monthly_stat['stat_list'])):?>
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_spend_by_months'); ?></span></h3>
                <div class="content">
                    <div id="chart_spend"></div>
                </div><!--content-->
            </div><!--widgetbox-->
            <?php endif;?>
	<div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_network_stats'); ?></span></h3>
                <div class="content">
                	
                    <div class="progress">
                        <?php echo $this->lang->line('label_campaigns'); ?>
                        <div class="bar2"><div class="value bluebar" style="width: <?php echo $camp_percentage; ?>%;"><small><?php echo $camp_count; ?></small></div></div>
                    </div><!--progress-->
                    
                    <div class="progress">
                    	<?php echo $this->lang->line('label_banners'); ?>
                        <div class="bar2"><div class="value orangebar" style="width: <?php echo $ban_percentage; ?>%;"><small><?php echo $ban_count; ?></small></div></div>
                    </div><!--progress-->
                    
                </div><!--content-->
            </div><!--widgetbox-->  
			
		 <?php if(!empty($top_campaigns['stat_list'])):?>	
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_top_campaigns'); ?></span></h3>
                <div class="content">
							<ol>
									<?php 
									$i=1;
								 foreach($top_campaigns['stat_list'] as $camp_name=>$camp_value):?>
										<?php if($i<=5):?>
											<li><?php echo $camp_name; ?></li>
								<?php 
										endif;
										$i++;
								endforeach;?>
					</ol>
					
                </div><!-- content -->
            </div><!-- widgetbox -->
		<?php endif; ?>             

		 <?php if(!empty($top_banners)):?>
			 <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_top_banners'); ?></span></h3>
                <div class="content">
							<ol>
									<?php 
									$i=1;
								 foreach($top_banners as $ban_value):?>
										<?php if($i<=5 && $ban_value['CTR']>0):?>
											<li><?php echo $ban_value['description']; ?></li>
								<?php 
										endif;
										$i++;
								endforeach;?>
					</ol>
					
                </div><!-- content -->
            </div><!--widgetbox-->
		 <?php endif;?>		 
           <br />
            
           </div><!--right-->
    </div><!--one_third last-->
    
    <br clear="all" />
    
</div><!--maincontent-->
<br />

<?php
/* Footer */ 
$this->load->view("includes/advertiser/footer");
?>

