<?php

/* Header */ 
$this->load->view("includes/publisher/header");

/* Menu */ 
$this->load->view("includes/publisher/menu");

/* Account */ 
$this->load->view("includes/publisher/account");

/* Sidebar */ 
$this->load->view("includes/publisher/sidebar");

?>
<!-- <script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/custom/reports.js"></script>
<script type="text/javascript" src="<?php //echo base_url(); ?>assets/js/custom/dashboard.js"></script> -->

<!--Load the AJAX API-->
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
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Revenue');
        data.addRows([
          <?php foreach($pieval as $rev): ?>
          ['<?php echo date("M", mktime(0, 0, 0, $rev->month, 10)).' '.$rev->year; ?>', <?php echo $rev->month_rev; ?>],
        <?php endforeach; ?>   
        ]);

        // Set chart options
        var options = {
                       'width':350,
                       'height':250,
                       'chartArea':{left:20,top:0,width:"100%",height:"100%"},
                       'is3D':true
                       };

		// Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);        
      }
    </script>	
    <script type='text/javascript'>
	 google.load('visualization', '1', {'packages': ['geochart']});
	 google.setOnLoadCallback(drawRegionsMap);

	  function drawRegionsMap() {
		var data = google.visualization.arrayToDataTable([
		  ['Country', 'Impressions','Clicks'],
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
        // Create and populate the data table.
		
		        var data = google.visualization.arrayToDataTable([
          ['<?php echo $this->lang->line("label_days"); ?>', '<?php echo $this->lang->line("label_impressions"); ?>', '<?php echo $this->lang->line("label_unique_impressions"); ?>', '<?php echo $this->lang->line("label_clicks"); ?>', '<?php echo $this->lang->line("label_unique_clicks"); ?>', '<?php echo $this->lang->line("label_conversions"); ?>','<?php echo $this->lang->line("label_call"); ?>','<?php echo $this->lang->line("label_web"); ?>','<?php echo $this->lang->line("label_map"); ?>', '<?php echo $this->lang->line("label_ctr"); ?>(%)'],
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
		
     
      
        // Create and draw the visualization.
         
        var options = {
          'chartArea':{left:50,top:20,width:"75%",height:"75%"},
          vAxis: {title: ""},
          hAxis: {title: "Last 7 days Comparision"},
          seriesType: "bars",
          series: {8: {type: "line"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_bar'));
        chart.draw(data, options);
      }
      google.setOnLoadCallback(drawVisualization);
    </script>
    
    <script type='text/javascript'>
	google.load('visualization', '1', {packages:['gauge']});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
	<?php $daily = $daily_stat['tot_val'];	 ?>
	var data = google.visualization.arrayToDataTable([
	  ['Label', 'Value'],
	  ['Impressions', <?php echo ($daily['IMP']!='0')?$daily['IMP']:'0'; ?>],
	  ['Clicks', <?php echo ($daily['CLK']!='0')?$daily['CLK']:'0'; ?>]          
	]);

	var options = {
	  width: 300, height: 200,
	  greenFrom: 85, greenTo: 100,
	  yellowFrom:70, yellowTo: 85,
	  greenFrom: 85, greenTo: 100,
	  redFrom: 0, redTo: 10,
	  minorTicks: 5
	};

	var chart = new google.visualization.Gauge(document.getElementById('chart_meter_count'));
	chart.draw(data, options);
	}
	</script>
    	
<div class="maincontent">
	<div class="two_third maincontent_inner ">
    	<div class="left">
        <!-- START WIDGET LIST -->
            <ul class="widgetlist">
 		
                <li><a href="<?php echo site_url('publisher/myaccount'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/settings.png" alt="Report Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_settings")?></span></a></li>

                <li><a href="<?php echo site_url('publisher/payments'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/payments.png" alt="Mail Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_payments")?></span></a></li>
                <li><a href="<?php echo site_url('publisher/refer_friends'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/refer.png" alt="Events Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_refer_friends")?></span></a></li>
                <li><a href="<?php echo site_url('publisher/performance_report'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/createreport.png" alt="Report Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_reports")?></span></a></li>
                <li><a href="<?php echo site_url('login/logout'); ?>"><img src="<?php echo base_url(); ?>assets/images/icons/logout.png" alt="Media Icon" /><span><?php echo $this->lang->line("label_publisher_dashboard_log_out")?></span></a></li>
            </ul>
            <!-- END WIDGET LIST -->
            <div class="clear"></div>
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("label_publisher_dashboard_earning")?></span></h3>
                <div class="content nopadding ohidden">
                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
                        <thead>
                            <tr>
                                <td align="center"><?php echo $this->lang->line("label_today")?></td>
                                <td align="center"><?php echo $this->lang->line("label_yesterday")?></td>
                                <td align="center"><?php echo $this->lang->line("label_current_month")?></td>
                                <td align="center"><?php echo $this->lang->line("label_last_month")?></td>
                                <td align="center"><?php echo $this->lang->line("label_unpaid_earnings")?></td>
                                <td align="center"><?php echo $this->lang->line("label_last_issued_payment")?></td>
                                <td align="center"><?php echo $this->lang->line("label_paid_date")?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center">$<?php echo number_format($today,2); ?></td>
                                <td align="center">$<?php echo number_format($rev_yesterday,2); ?></td>
                                <td align="center">$<?php echo number_format($rev_tismonth,2); ?></td>
                                <td align="center">$<?php echo number_format($rev_prevmonth,2); ?></td>
                                <?php  if($unpaidAmt !='')
                                    {
                                  ?>

                                  <td align="center">$<?php echo ($unpaidAmt > 0)?number_format($unpaidAmt,2):'0.00';   ?></td>
                                 <?php }
                                   else
                                 {
				?>   
                                <td align="center">$<?php echo ($unpaidAmt!='')?number_format($unpaidAmt,2):'0.00';   ?></td>
                                 <?php }
                                   ?>
                                <td align="center">$<?php echo ($last_pay_amt!='')?number_format($last_pay_amt,2):'-'; ?></td>
                                <td align="center"><?php echo ($last_pay_date!='')?date('Y-M-d', strtotime($last_pay_date)):'-'; ?></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div><!-- content -->
            </div><!-- widgetbox2 -->
            
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
				<td align="center"><?php echo $this->lang->line('label_revenue'); ?></td>
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
				<td align="center">$<?php echo ($daily_stat['REV']!='0')?number_format($daily_stat['REV'],2):'0.00'; ?></td>
                            </tr>
                           </tbody>
                    </table>
                </div><!-- content -->
            </div>
			
			<div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("label_monthly_report");?></span></h3>
                <div class="content nopadding ohidden">
                	<table cellpadding="0" cellspacing="0" class="sTable3" width="100%">
                        <thead>
                            <tr>
                                <td align="center"><?php echo $this->lang->line("label_month");?></td>
                                <td align="center"><?php echo $this->lang->line("label_impressions");?></td>
                                <td align="center"><?php echo $this->lang->line("label_unique_imp");?></td>
                                <td align="center"><?php echo $this->lang->line("label_clicks");?></td>
                                <td align="center"><?php echo $this->lang->line("label_unique_clicks");?></td>
                                <td align="center"><?php echo $this->lang->line("label_conversions");?></td>
                               <td align="center"><?php echo $this->lang->line('label_call'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_web'); ?></td>
                                <td align="center"><?php echo $this->lang->line('label_map'); ?></td>
                                
                                <td align="center"><?php echo $this->lang->line("label_ctr");?></td>
				<td align="center"><?php echo $this->lang->line("label_revenue");?></td>
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
				<td align="center">$<?php echo ($monthly_stat['REV']!='0')?number_format($monthly_stat['REV'],2):'0.00'; ?></td>
                            </tr>
                           </tbody>
                    </table>
                </div><!-- content -->
            </div>
	    
	    <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("label_weekly_statistics");?></span></h3>
                <div class="content">
						<div id="chart_bar" style="width: 700px; height: 300px;"></div>
				</div><!-- content -->
           </div><!-- widgetbox2 -->
	    <?php if(!empty($map['stat_list'])):?>
	    <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("label_global_statistics");?></span></h3>
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
		<center><h2 style="color:#CC655A;"><?php echo $this->lang->line("label_welcome");?> <?php echo $this->session->userdata('session_publisher_contact');?></h2></center>
	    </div><!--sTableOptions-->	
	    <div class="sTableWrapper">
	    </div>	
            <br/>
            	
            <div class="widgetbox">
                <h3><span><?php echo $this->lang->line("label_earnings");?></span></h3>
                <div class="content">
                    
                    <h1 class="prize">$<?php echo number_format($today,2); ?></h1>
                    <p><?php echo $this->lang->line("label_estimate_earnings_of_day");?> <strong>$<?php echo number_format(ceil($today),2); ?></strong></p>
                	
                    <br />
                    
                	<div class="one_half bright">
						<h2 class="prize">$<?php echo number_format($rev_yesterday,2); ?></h2>
                        <small><?php echo $this->lang->line("label_yesterday_earnings");?></small>
                    </div><!--one_half-->
                    
                    <div class="one_half last">
                    	<h2 class="prize">$<?php echo number_format($rev_tismonth,2); ?></h2>
                        <small><?php echo date('F'); ?> <?php echo $this->lang->line("label_months_earnings");?></small>
                    </div><!--one_half-->
                    
                    
                </div><!-- content -->
            </div><!-- widgetbox -->
            
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("label_revenue_by_months");?>($)</span></h3>
                <div class="content">
                <?php if(!empty($pieval)): ?>
                    <div id="chart_div"></div>
                <?php else: ?>
					 Sorry, No data available!
                <?php endif; ?>
                </div><!--content-->
            </div><!--widgetbox-->
            
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_network_stats'); ?></span></h3>
                <div class="content">
                    <div class="progress">
                        <?php echo $this->lang->line('label_zones'); ?>
                        <div class="bar2">
                        	<?php 
                        		if($totzones == 0)
								{
												$zoneper = 0;
								}
								else
								{
									$zoneper = ($zones/$totzones)*100;	
								}
                        	?>
                        	<div class="value bluebar" style="width: <?php echo $zoneper; ?>%;"><small><?php echo $zones; ?></small></div>
                        </div>
                        <?php echo "Linked Campaigns"; ?>
                        <div class="bar2">
                        	<?php 
                        		if($totlinkcamps == 0)
								{
												$linkcampper = 0;
								}
								else
								{
									$linkcampper = ($linkcamps/$totlinkcamps)*100;
								}
                        	?>
                        	<div class="value orangebar" style="width: <?php echo $linkcampper; ?>%;"><small><?php echo $linkcamps; ?></small></div>
                        </div>
                        <?php echo "Linked Banners"; ?>
                        <div class="bar2">
                        	<?php 	
                        		if($totlinkbanners == 0)
								{
												$linkbanper = 0;
								}
								else
								{
									$linkbanper = ($linkbanners/$totlinkbanners)*100;
								}
                        	?>
                        	<div class="value redbar" style="width: <?php echo $linkbanper; ?>%;"><small><?php echo $linkbanners; ?></small></div>
                        </div>
                    </div><!--progress-->	
                    <!-- <div id='chart_meter_count'></div> -->
                </div><!--content-->
            </div><!--widgetbox-->
            
           <br />
            
           </div><!--right-->
    </div><!--one_third last-->
    
    <br clear="all" />
    
</div><!--maincontent-->
<br />

<?php
/* Footer */ 
$this->load->view("includes/publisher/footer");
?>

