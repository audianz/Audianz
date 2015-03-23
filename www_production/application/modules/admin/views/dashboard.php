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
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Advertiser', <?php echo $approved_ad_list_count; ?>],
          ['Publisher', <?php echo $approved_pub_list_count; ?>]          
        ]);

        var options = {
          width: 300, height: 200,
          greenFrom: 85, greenTo: 100,
          yellowFrom:70, yellowTo: 85,
          greenFrom: 85, greenTo: 100,
          redFrom: 0, redTo: 10,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_meter'));
        chart.draw(data, options);
      }
    </script>
    
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Publisher', 'Campaigns', 'Banners', 'Websites', 'Zones'],
          ['',  <?php echo $countcamp; ?>, <?php echo $countbanner; ?>, <?php echo $countsites; ?>, <?php echo $countzones; ?>]          
        ]);

        var options = {
		chartArea:{left:20,top:10,width:"100%",height:"80%"},
		tooltip: {textStyle: {fontSize: 14}},
		legend:{position: 'bottom', textStyle: {color: '#333'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_tot_stat2'));
        chart.draw(data, options);
      }
    </script>
    
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
        	 ['<?php echo $this->lang->line("label_month"); ?>','<?php echo $this->lang->line("label_revenue"); ?>'],
			 <?php 
		 	$report	=	$six_monthly_stat['stat_list']; 
			for($i=0,$j=5;$i<6;$i++,$j--):
			$month = date ('n',strtotime ( '-'.$j.' month' ));
		 	?>
		 		['<?php echo date ( 'M Y',strtotime ( '-'.$j.' month' ));?>', 
				<?php echo isset($report[$month]['SPEND'])?$report[$month]['SPEND']:'0';?>],
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
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);        
      }
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
		 	$report	=	$weekly_stat['stat_list']; 
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
          'chartArea':{left:50,top:20,width:"70%",height:"75%"},
          vAxis: {title: ""},
          hAxis: {title: '<?php echo $this->lang->line("label_last_seven_days_comparision"); ?>'},
          seriesType: "bars",
          series: {8: {type: "line"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_bar'));
        chart.draw(data, options);
      }
      google.setOnLoadCallback(drawVisualization);
    </script>
<div class="maincontent"> 
    	
    <div class="breadcrumbs">
    	<?php echo $breadcrumb; ?>    
    </div><!-- breadcrumbs -->
	<div class="two_third maincontent_inner ">
    <div class="left">

	<?php if($this->session->userdata('admin_notify') != ""): ?>
		<div class="notification msgerror">
		<a class="close"></a><?php echo $this->session->userdata('admin_notify'); ?>
	</div><!-- notification info -->
	<?php endif; ?>	
            
	<div class="sTableOptions">
		<h3><?php echo $this->lang->line('label_daily_stats');?></h3>	        		
	</div><!--sTableOptions-->
	<?php $daily_stat = $daily_stat['tot_val'];	?>
	
	<table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">
		 	<thead>
				<tr>
					<th class="head0"><?php echo $this->lang->line('label_date'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_impressions');?>
					<th class="head0"><?php echo $this->lang->line('label_unique_imp'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_clicks'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_unique_clicks'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_conversions'); ?></th>
					
					<th class="head0"><?php echo $this->lang->line('label_call'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_web'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_map'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_ctr'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_revenue'); ?></th>
				</tr>
			</thead>
			<colgroup>
				<col class="con0" width="6%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="7%" />
				<col class="con1" width="10%" />
				<col class="con0" width="9%" />
				<col class="con1" width="9%" />
				<col class="con0" width="9%" />
			</colgroup>

 
			<tbody>
				
				<tr>

				<td align="center" ><?php echo "Today"; ?></td>
		        <td align="center" style="word-break: break-all;"><?php echo ($daily_stat['IMP']!='0')?$daily_stat['IMP']:'0'; ?></td>
		        <td align="center" style="word-break: break-all;"><?php echo ($daily_stat['UIMP']!='0')?$daily_stat['UIMP']:'0'; ?></td>
		        <td align="center" style="word-break: break-all;"><?php echo ($daily_stat['CLK']!='0')?$daily_stat['CLK']:'0'; ?></td>
		        <td align="center" style="word-break: break-all;"><?php echo ($daily_stat['UCLK']!='0')?$daily_stat['UCLK']:'0'; ?></td>
		        <td align="center" style="word-break: break-all;"><?php echo ($daily_stat['CON']!='0')?$daily_stat['CON']:'0'; ?></td>
		        <td align="center"  style="word-break: break-all;"><?php echo ($click_to_action_daily['CALL']!='0')?$click_to_action_daily['CALL']:'0';?></td>
		        <td align="center" style="word-break: break-all ;"><?php echo ($click_to_action_daily['WEB']!='0')?$click_to_action_daily['WEB']:'0';?></td>
		        <td align="center" style="word-break: break-all"><?php echo ($click_to_action_daily['MAP']!='0')?$click_to_action_daily['MAP']:'0';?></td>
		        <td align="center" style="word-break: break-all;"><?php echo ($daily_stat['CTR']!='0')?$daily_stat['CTR'].'%':'0.00%'; ?></td>
			    <td align="center" style="word-break: break-all;">$<?php echo ($daily_stat['SPEND']!='0')?number_format($daily_stat['SPEND'],2):'0.00'; ?></td>

				</tr>
			</tbody> 
		</table>
	
        
        		
            <br/>
            
            	<?php $monthly_stat =	$this_month_stat['tot_val'];  ?>
            	<div class="sTableOptions">
		<h3><span><?php echo $this->lang->line('label_monthly_statistics'); ?></span></h3>
		</div><!--sTableOptions-->
		
		<table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">
		 	<thead>
				<tr>
					<th class="head0"><?php echo $this->lang->line('label_month'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_impressions');?>
					<th class="head0"><?php echo $this->lang->line('label_unique_imp'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_clicks'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_unique_clicks'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_conversions'); ?></th>
					
					<th class="head0"><?php echo $this->lang->line('label_call'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_web'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_map'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_ctr'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_revenue'); ?></th>
				</tr>
			</thead>
			<colgroup>
				<col class="con0" width="6%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="7%" />
				<col class="con1" width="10%" />
				<col class="con0" width="9%" />
				<col class="con1" width="9%" />
				<col class="con0" width="9%" />
			</colgroup>

 
			<tbody>
				
				<tr>

				<td align="center" ><?php echo date('F'); ?></td>
		        <td align="center" style="word-break: break-all;"><?php echo ($monthly_stat['IMP']!='0')?$monthly_stat['IMP']:'0'; ?></td>
                <td align="center" style="word-break: break-all;"><?php echo ($monthly_stat['UIMP']!='0')?$monthly_stat['UIMP']:'0'; ?></td>
                <td align="center" style="word-break: break-all;"><?php echo ($monthly_stat['CLK']!='0')?$monthly_stat['CLK']:'0'; ?></td>
                <td align="center" style="word-break: break-all;"><?php echo ($monthly_stat['UCLK']!='0')?$monthly_stat['UCLK']:'0'; ?></td>
                <td align="center" style="word-break: break-all;"><?php echo ($monthly_stat['CON']!='0')?$monthly_stat['CON']:'0'; ?></td>
                <td align="center"><?php echo ($click_to_action_monthly['CALL']!='0')?$click_to_action_monthly['CALL']:'0'; ?></td>
                <td align="center"><?php echo ($click_to_action_monthly['WEB']!='0')?$click_to_action_monthly['WEB']:'0'; ?></td>
               <td align="center"><?php echo ($click_to_action_monthly['MAP']!='0')?$click_to_action_monthly['MAP']:'0'; ?></td>
                <td align="center" style="word-break: break-all;"><?php echo ($monthly_stat['CTR']!='0')?$monthly_stat['CTR'].'%':'0.00%'; ?></td>
		<td align="center" style="word-break: break-all;">$<?php echo ($monthly_stat['SPEND']!='0')?number_format($monthly_stat['SPEND'],2):'0.00'; ?></td>
				
				</tr>
			</tbody> 
		</table>
          
            <br/>
       	    <div class="sTableOptions">
		<h3><?php echo $this->lang->line('label_overall_stats');?></h3>
	</div><!--sTableOptions-->
	<?php $overall_stat =	$overall_stat['tot_val'];  ?>
	
	
	   <table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">
		 	<thead>
				<tr>
					
					<th class="head0"><?php echo $this->lang->line('label_impressions');?>
					<th class="head1"><?php echo $this->lang->line('label_unique_imp'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_clicks'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_unique_clicks'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_conversions'); ?></th>
					
					<th class="head1"><?php echo $this->lang->line('label_call'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_web'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_map'); ?></th>
					<th class="head0"><?php echo $this->lang->line('label_ctr'); ?></th>
					<th class="head1"><?php echo $this->lang->line('label_revenue'); ?></th>
				</tr>
			</thead>
			<colgroup>
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
		 </colgroup>

 
			<tbody>
				
				<tr>

				<td align="center"><?php echo ($overall_stat['IMP']!='0')?$overall_stat['IMP']:'0'; ?></td>
                <td align="center"><?php echo ($overall_stat['UIMP']!='0')?$overall_stat['UIMP']:'0'; ?></td>
                <td align="center"><?php echo ($overall_stat['CLK']!='0')?$overall_stat['CLK']:'0'; ?></td>
                <td align="center"><?php echo ($overall_stat['UCLK']!='0')?$overall_stat['UCLK']:'0'; ?></td>
		        <td align="center"><?php echo ($overall_stat['CON']!='0')?$overall_stat['CON']:'0'; ?></td>
		        <td align="center"><?php echo ($click_to_action_overall['CALL']!='0')?$click_to_action_overall['CALL']:'0'; ?></td>
                <td align="center"><?php echo ($click_to_action_overall['WEB']!='0')?$click_to_action_overall['WEB']:'0'; ?></td>
                <td align="center"><?php echo ($click_to_action_overall['MAP']!='0')?$click_to_action_overall['MAP']:'0'; ?></td>
               
                <td align="center"><?php echo ($overall_stat['CTR']!='0')?$overall_stat['CTR']:'0'; ?>%</td>
                <td align="center">$<?php echo ($overall_stat['SPEND']!='0')?number_format($overall_stat['SPEND'],2):'0.00'; ?></td>
				
				</tr>
			</tbody> 
		</table>
	
       
        
                    
            
	     <br/>	
      <?php /* total Regiter users today registered*/?>
	<!--	<div class="sTableOptions">
			<h3><?php echo $this->lang->line("label_total_register_users_today"); ?> -  <span style="color:#006699"><?php echo $users_list_count_today; ?></span></h3>    
		</div>
        <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head1" width="70%" />
                <col class="head0" width="30%" />
           </colgroup>
            <tr>
				<td align="center"><?php echo $this->lang->line("label_user_type"); ?></td>
				<td align="center"><?php echo $this->lang->line("label_registered_today"); ?></td>
			</tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con1" width="70%" />
                    <col class="con0" width="30%" />
                </colgroup>
                <tr>
                    <td align="center"><?php echo  $this->lang->line("label_advertisers"); ?></td>
                    <td align="center">
					<?php if($approved_ad_list_count_today !=0): ?>
					<a href="<?php echo site_url();?>/admin/inventory_advertisers" title="<?php echo $this->lang->line('label_view_advertisers_list');?>"><?php echo $approved_ad_list_count_today; ?></a> 
					<?php else:
									echo $this->lang->line("label_no_ad_register_today"); 
									endif;
					?>
					
					
					</td>
                </tr> 
				
				<tr>
                    <td align="center"><?php echo  $this->lang->line("label_publishers"); ?></td>
                    <td align="center">
					<?php if($approved_pub_list_count_today !=0): ?>
					<a href="<?php echo site_url();?>/admin/inventory_websites" title="<?php echo $this->lang->line('label_view_publishers_list');?>"><?php echo $approved_pub_list_count_today; ?></a> 
					<?php else:
									echo $this->lang->line("label_no_pub_register_today"); 
									endif;
					?>
					</td>
                </tr>                               
            </table>
	     </div>
            <br/> -->	
	<?php /*Total Advertiser and  Publisher Registered List */?>		
	<div class="sTableOptions">
		<h3><?php echo $this->lang->line("label_total_register_users"); ?> -  <span style="color:#006699"><?php echo $users_list_count; ?></span></h3>    
	</div>
        <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head1" width="50%" />
                <col class="head0" width="25%" />
                <col class="head1" width="25%" />
           </colgroup>
            <tr>
				<td align="center"><?php echo $this->lang->line("label_user_type"); ?></td>
				<td align="center"><?php echo $this->lang->line("label_approved"); ?></td>
				<td align="center"><?php echo $this->lang->line("label_pending"); ?></td>
			</tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con1" width="50%" />
                    <col class="con0" width="25%" />
                    <col class="con1" width="25%" />
                </colgroup>
                <tr>
                    <td align="center"><?php echo  $this->lang->line("label_advertisers"); ?></td>
                    <td align="center"><a href="<?php echo site_url();?>/admin/inventory_advertisers" title="<?php echo $this->lang->line('label_view_advertisers_list');?>"><?php echo $approved_ad_list_count; ?></a></td>
                    <td align="center">
					<?php if($pending_ad_list_count !=0): ?>
					<a href="<?php echo site_url();?>/admin/approvals/advertisers" title="<?php echo $this->lang->line('label_view_app_advertisers_list');?>"><?php echo $pending_ad_list_count; ?></a>
					<?php else: 
									echo $this->lang->line('label_no_pending_approval'); 
									endif; 
					?>
					</td>
                </tr> 
				
				<tr>
                    <td align="center"><?php echo  $this->lang->line("label_publishers"); ?></td>
                    <td align="center"><a href="<?php echo site_url();?>/admin/inventory_websites" title="<?php echo $this->lang->line('label_view_publishers_list');?>"><?php echo $approved_pub_list_count; ?></a></td>
                    <td align="center">
					<?php if($pending_pub_list_count !=0): ?>
					<a href="<?php echo site_url();?>/admin/approvals/publishers" title="<?php echo $this->lang->line('label_view_app_publishers_list');?>"><?php echo $pending_pub_list_count; ?></a>
					<?php else: 
							echo $this->lang->line('label_no_pending_approval'); 
							endif; 
					?>
					
					</td>
                </tr>                               
            </table>
	     </div> <!--sTableWrapper-->			
         <br/>

	<div class="widgetbox">
        	<h3><span><?php echo $this->lang->line("label_stats_chart"); ?></span></h3>
        	<div class="content">
			<div id="chart_bar" style="width: 700px; height: 300px;"></div>
		</div><!-- content -->
        </div>
	<br/>		
	<div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("label_global_statistics"); ?></span></h3>
                <div class="content">
			<div id="chart_global" style="width: 650px; height: 400px;"></div>
				</div><!-- content -->
        </div><!-- widgetbox2 -->
	<br />

	
           
        </div><!--left-->            
    </div><!--two_third-->
    
    <div class="one_third last">
    	<div class="right">
            <div class="sTableOptions">
		<center><h2 style="color:#CC655A;"><?php echo $this->lang->line("label_welcome");?> <?php echo ucfirst($this->session->userdata('mads_sess_admin_username')); ?></h2></center>
	    </div><!--sTableOptions-->	
	    <div class="sTableWrapper">
	    </div>	
            <br/>	
            <div class="widgetbox">
                <h3><span><?php echo $this->lang->line("site_title").$this->lang->line("label_dreamads_rev"); ?></span></h3>
                <div class="content">
                    <h1 class="prize"><?php echo '$'.number_format($revenue,2); ?></h1>
                    <p><?php echo $this->lang->line("label_estimate_earnings_of_day"); ?> <strong><?php echo '$'.number_format(ceil($revenue),2); ?></strong></p>
                	
                    <br />
                    
                	<div class="one_half bright">
                        <?php 
                            $yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
                            $revenue_yesterday = $this->mod_dashboard->get_revenue($yesterday);
                            $rev_yesterday = ($revenue_yesterday[0]->rev!="")?number_format($revenue_yesterday[0]->rev,2):'0.00';
                        ?>
                    	<h2 class="prize"><?php echo '$'.$rev_yesterday; ?></h2>
                        <small><?php echo $this->lang->line("label_yesterday_earnings"); ?></small>
                    </div><!--one_half-->
                    
                    <div class="one_half last">
                        <?php
                            $thismonth = date('m');
                            $revenue_month = $this->mod_dashboard->get_month_revenue($thismonth);
                            $rev_tismonth = ($revenue_month[0]->rev!="")?number_format($revenue_month[0]->rev,2):'0.00';
                        ?>
                    	<h2 class="prize"><?php echo '$'.round($rev_tismonth,2); ?></h2>
                        <small><?php echo $this->lang->line("label_this_month_earn"); ?></small>
                    </div><!--one_half-->
                    
                    
                </div><!--content-->
            </div><!--widgetbox-->
            
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("site_title").$this->lang->line("label_dreamads_users"); ?>(<?php echo $users_list_count; ?>)</span></h3>
                <div class="content">
                    <div id='chart_meter'></div>     
                </div><!--content-->
            </div><!--widgetbox-->
            
            <?php if(!empty($six_monthly_stat['stat_list'])):?>
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line("label_revenue_by_months"); ?>($)</span></h3>
                <div class="content">
                    <div id="chart_div"></div>
                </div><!--content-->
            </div><!--widgetbox-->
            <?php endif;?>
            
            
            <?php if($countcamp>0 || $countbanner>0 || $countsites>0 || $countzones>0)
            {
				?>
				<div class="widgetbox">
					<h3><span><?php echo $this->lang->line("label_network_stats"); ?></span></h3>
					<div class="content">
						<div id='chart_tot_stat2'></div>     
					</div><!--content-->
				</div><!--widgetbox-->
			<?php } ?>
            
            <?php if(!empty($top_campaigns['stat_list'])):?>	
            <div class="widgetbox">
            	<h3><span><?php echo $this->lang->line('label_top_campaigns'); ?></span></h3>
                <div class="content">
							<ol>
									<?php 
									$i=1;
								 foreach($top_campaigns['stat_list'] as $camp_name=>$camp_value):?>
										<?php if($i<=5):?>
											<li><?php echo $camp_name; ?>(<?php echo $this->config->item('currency_symbol').''.$camp_value['SPEND']; ?>)</li>
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
											<li><?php echo $ban_value['description']; ?>(<?php echo $this->config->item('currency_symbol').''.$ban_value['SPEND']; ?>)</li>
								<?php 
										endif;
										$i++;
								endforeach;?>
					</ol>
					
                </div><!-- content -->
            </div><!--widgetbox-->
		 <?php endif;?>		           
			
    	</div><!--right-->
    </div><!--one_third last-->
    
    <br clear="all" />
    
</div><!--maincontent-->
<br />

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/customs/gallery.js"></script>
<?php
/* Footer */ 
$this->load->view("includes/footer");
?>