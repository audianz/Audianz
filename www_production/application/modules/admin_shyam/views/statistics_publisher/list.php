<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery.nestedAccordion.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>

<h1 class="pageTitle"> <?php echo $this->lang->line('lang_statistics_publisher_statistics');?></h1>
<style type="text/css">
#tableSection { clear: both; width: 70%; padding-top: 20px }

.row-drops .fields { float: left; padding: 10px 15px; width: 100% }
.row-drops .fields label { display: block; margin-left: 2px }

/* expanded light brown table row. */
.exp-row {display: none;} 

.hdr .col-qty { background-color: #0b72d1 !important }



.childinfobox {width: 140px; float: left; font-weight: normal; text-align: left; border:1px solid #C4C4C4; padding: 5px; margin-right: 10px;}
.childpic {width: 100px; float: right; border:1px solid #C4C4C4; padding: 5px; margin-right: 40px;}
.childinfobox label {display: block; font-weight: bold; color: #0b72d1; margin-bottom: 5px; }
.childinfobox ul{list-style:none} 
.childinfobox .crit label {display: inline; font-weight: normal; color: #000;}
.childinfobox .crit span {text-align: right; color: #00A82C; font-weight: bold; }
.flrt {float: right;}

/*style for the article */
.boldme {font-weight: bold;}
.redbold {color:#F90E0E; font-weight: bold; }
#articleSection h3 { color: #1509F4; font-weight: bold; }
#domsection {margin-left: 20px;}</style>
<script type="text/javascript">
$(window).unload( function () {
  $('.col-input input').attr('checked', false); } 
);
$(document).ready(function() {
  var classNames = { 0: 'r1', 1: 'r2', 2: 'r3', 3: 'r4'};
  $('table.dyntable tbody tr').not('[th]').each(function(index) {
    $(this).addClass(classNames[index % 4]);
  });
  $('.col-input input').change(function() {
    if($(this).attr('checked')) {
      $(this).parent().parent().next().children(":first").slideDown("fast");			
      return;
    }
    $(this).parent().parent().next().children(":first").slideUp("fast"); 
  });
});

</script>

<script type="text/javascript">
	   function select_date()
	   		{
				var selected_date			=	document.getElementById("date").value;
				
				document.location.href	=	'<?php echo site_url("admin/statistics_publisher/process_date") ?>/'+selected_date;
				
			}			
	   </script> 
	  
 <form action="#"  method="post">
  <div class="form_default">    <!-- starting of form_default -->
  		  <fieldset>
		    <p>
                    	<label for="date"><?php echo $this->lang->line('lang_statistics_publisher_date');?></label>
                       <select name="date" id="date" onchange="select_date()" class="sf">
                        <option value="today"><?php echo $this->lang->line('lang_statistics_today');?></option>
                        <option value="yesterday"><?php echo $this->lang->line('lang_statistics_yesterday');?></option>
                        <option value="thisweek"><?php echo $this->lang->line('lang_statistics_this_week');?></option>
                        <option value="last7days"><?php echo $this->lang->line('lang_statistics_last_sev_day');?></option>
                        <option value="thismonth"><?php echo $this->lang->line('lang_statistics_this_month');?></option>
                        <option value="lastmonth"><?php echo $this->lang->line('lang_statistics_last_month');?></option>
                        <option value="all"><?php echo $this->lang->line('lang_statistics_spec_date');?></option>
                        <option value="specificdate" selected="selected"><?php echo $this->lang->line('lang_statistics_today');?></option>
                        </select>
                    </p>
                     
		    
		    
                    <p>   
                <label><?php echo $this->lang->line('lang_statistics_publisher_from_date');?></label>
                <input id="datepicker" type="text" class="sf" /> 
               </p>
                
                <p>   
                <label><?php echo $this->lang->line('lang_statistics_publisher_to_date');?></label>
                <input id="datepicker1" type="text" class="sf" /> 
               </p>
                  
                <p>
                    	<label for="filteroption" class="nopadding"><?php echo $this->lang->line('lang_statistics_publisher_filtering_options');?></label>
                        <input type="radio" name="filter" value="0" /> 
						<?php echo $this->lang->line('lang_statistics_publisher_country');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="filter" value="1" /> 
						<?php echo $this->lang->line('lang_statistics_publisher_highest_publisher');?>
                    </p>
                     <p>
                    	<button><?php echo $this->lang->line('label_submit');?></button>
                    </p>     
                                        
                </fieldset>
  	
</form>
       
           </div> <!-- ending of form_default -->
		   
		   <br />
		 
  <table id="example" cellpadding="0" celllspacing="0"  class="dyntable">
    <thead>	 
    <tr class="hdr">
      <th class="head1"></th>
      <th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_site');?></th>
	<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_impression');?></th>
	<th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_clicks');?></th>
	<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_conversions');?></th>
	<th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_ctr');?></th>
	<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_revenue');?></th>
    </tr>
    </thead>
	 <colgroup>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            	<col class="con0" />
				<col class="con1" />
            </colgroup>
    <tbody>
    <tr>
      <td class="col-input"><input type="checkbox"></td>
      <td class="col-qty">020</td>
      <td>Lucy</td>
      <td>Stewart</td>
      <td>Mrs. Familia</td>
      <td>K</td>
      <td>Suzzy Stewart</td>
      
    </tr>
    <tr>
      <td colspan="8" class="row-drops exp-row">
        <div class="fields"> 
        <div class="childinfobox">
          <label>Personality Criteria</label> 
          Lucy is a quiet little girl.  Loves group activities and tends to be shy in large groups.
          Skills she excels at are mostly spelling and reading. 
        </div>	
        <div class="childinfobox">
          <label>Academic Grades</label> 
          <ul class="crit">
            <li><label>Reading</label><span>A</span></li>
            <li><label>Math</label><span>B</span></li>
            <li><label>Sciene</label><span>A</span></li>
            <li><label>History</label><span>A</span></li>
          </ul>
        </div>
        </div>	
      </td>
    </tr>
    <tr>
      <td class="col-input"><input type="checkbox"></td>
      <td class="col-qty">031</td>
      <td>Johnny</td>
      <td>Flerall</td>
      <td>Mrs. Rosental</td>
      <td>1st</td>
      <td>Grace Flerall</td>
     
    </tr>
    <tr>
      <td colspan="8" class="row-drops exp-row">
       		   <table style="background-color:#EAEAEA;width:100%;"><tr>       <!-- Second level  table-->
			   <td class="col-input"><input type="checkbox"></td>
			  <td class="col-qty">031</td>
			  <td>Johnny</td>
			  <td>Flerall</td>
			  <td>Mrs. Rosental</td>
			  <td>1st</td>
			  <td>Grace Flerall</td>
			  </tr>
	   
	   			<tr>
	   			<td colspan="8" class="row-drops exp-row">
	   					<table style="width:100%;margin-left:20px;background:#E4F2FF"><tr>			<!-- Third level  table-->
						  <td class="col-input"><input type="checkbox"></td>
						  <td class="col-qty">031</td>
						  <td>Johnny</td>
						  <td>Flerall</td>
						  <td>Mrs. Rosental</td>
						  <td>1st</td>
						  <td>Grace Flerall</td>
						  </tr>
						 </table>
	   
	   			</td>
	   			</tr>
	   			</table>
	   
      </td>
    </tr>
    <tr>
      <td class="col-input"><input type="checkbox"></td>
      <td class="col-qty">121</td>
      <td>Mirtha</td>
      <td>Sacon</td>
      <td>Mrs. Moore</td>
      <td>3rd</td>
      <td>Gloria Sacon</td>
     
    </tr>	
    <tr>
      <td colspan="8" class="row-drops exp-row">
	  				<table><tr>       <!-- Second level  table-->
			   <td class="col-input"><input type="checkbox"></td>
			  <td class="col-qty">031</td>
			  <td>Johnny</td>
			  <td>Flerall</td>
			  <td>Mrs. Rosental</td>
			  <td>1st</td>
			  <td>Grace Flerall</td>
			  </tr>
	   
	   			<tr>
	   			<td colspan="8" class="row-drops exp-row">
	   					<table><tr>			<!-- Third level  table-->
						  <td class="col-input"><input type="checkbox"></td>
						  <td class="col-qty">031</td>
						  <td>Johnny</td>
						  <td>Flerall</td>
						  <td>Mrs. Rosental</td>
						  <td>1st</td>
						  <td>Grace Flerall</td>
						  </tr>
						 </table>
	   
	   			</td>
	   			</tr>
	   			</table>
	 </td>
    </tr>
    </tbody>
  </table>

			
