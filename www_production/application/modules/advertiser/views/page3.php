<?php //print_r($pages); ?>
<script>
	var ar = JSON.parse( '<?php echo json_encode($pages); ?>' );
</script>
<script>
	function change_image1()
	{
		var x = document.getElementsByName('image_form1');
		x[0].submit(); // Form submission
	
	}
	
	function change_image2()
	{
		var x = document.getElementsByName('image_form2');
		x[0].submit(); // Form submission
	}
	
	function change_image3()
	{
		var x = document.getElementsByName('image_form3');
		x[0].submit(); // Form submission
	
	}
	
	function change_image4()
	{
		var x = document.getElementsByName('image_form4');
		x[0].submit(); // Form submission
	
	}
	
	
	function change_image5()
	{
		var x = document.getElementsByName('image_form5');
		x[0].submit(); // Form submission
	}
		
	
	function check_image()
	{
		//alert(ar[0]['image1']);
		document.getElementById("image3").src = "<?php echo base_url(); ?>"+ar[0]['image1'];
	}

</script>


<script>
	jQuery(function ($) {
	    $("#add-image1").click(function () {
	        $("#myfile1").click();
	    });
	    
	    $("#add-image2").click(function () {
	        $("#myfile2").click();
	    });
	    
	    $("#add-image3").click(function () {
	        $("#myfile3").click();
	    });
	    
	    $("#add-image4").click(function () {
	        $("#myfile4").click();
	    });
	    
	    $("#add-image5").click(function () {
	        $("#myfile5").click();
	    });
	})
</script>


<?php $cur_page = explode("_",$this->uri->segment(3)); ?>
<div class="tabmenu_camp" style="margin-left:2%;" >
    	<ul>
            <li <?php echo($cur_page[0]=="proximity_camp")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns/proximity_camp'); ?>" class="dashboard"><span>Create Campaign</span></a></li>
            <li  <?php echo($cur_page[0]=="page1")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns/page1'); ?>" class="reports"><span>Page1</span></a></li>
	        <li <?php echo($cur_page[0]== "page2" || $cur_page[0]== "store" )?"class='current'":""; ?> ><a href="<?php echo site_url('advertiser/campaigns/page2'); ?>" class='reports'><span>Page 2</span></a>
			<li  <?php echo($cur_page[0]=="page3")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns/page3'); ?>" class="reports"><span>Page 3</span></a></li>
			<li  <?php echo($cur_page[0]=="page4 ")?"class='current'":""; ?>><a href="<?php echo site_url('advertiser/campaigns/page4'); ?>" class="reports"><span>Page 4</span></a></li>
	        
        </ul>
</div><!-- tabmenu -->


	
	<div style="margin-top:2%;margin-left:2%" >
	<div style="width:50%;float:left" >
	<form method="post" name="image_form1" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  style="margin-top:2%;" enctype="multipart/form-data"  >

				<label >Large: </label><input type="file"  id="myfile1" name="myfile1" onchange="change_image1()" style="margin-left:1.2%"  />
				<input type="text" id="page" name="page" value="3" style="visibility:hidden" />
				<input type="text" id="page" name="image" value="1" style="visibility:hidden" />
				<input type="text" id="file" name="file" value="myfile1" style="visibility:hidden" />
			</form>
			
			<form method="post" na me="image_form2" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file" style="margin-top:0%;"  enctype="multipart/form-data"  >

				<label >Small 1: </label><input type="file" id="myfile2" name="myfile2" onchange="change_image2()"  />
				<input type="text" id="page" name="page" value="3" style="visibility:hidden" />
				<input type="text" id="page" name="image" value="2" style="visibility:hidden" />
				<input type="text" id="file" name="file" value="myfile2" style="visibility:hidden" />
				
			</form>
	
			<form method="post" name="image_form3" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  style="margin-top:0%;" enctype="multipart/form-data"  >
		
				<label >Small 2: </label><input type="file"  id="myfile3" name="myfile3" onchange="change_image3()"  />
				<input type="text" id="page" name="page" value="3" style="visibility:hidden" />
				<input type="text" id="page" name="image" value="3" style="visibility:hidden" />
				<input type="text" id="file" name="file" value="myfile3" style="visibility:hidden" />
				
			</form>
			
			<form method="post" name="image_form4" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  style="margin-top:0%;" enctype="multipart/form-data"  >
		
				<label width="10%" >Small 3: </label><input type="file" id="myfile4" name="myfile4" onchange="change_image4()"  />
				<input type="text" id="page" name="page" value="3" style="visibility:hidden" />
				<input type="text" id="page" name="image" value="4" style="visibility:hidden" />
				<input type="text" id="file" name="file" value="myfile4" style="visibility:hidden" />
				
			</form>
			
			<form method="post" name="image_form5" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file" style="margin-top:0%" enctype="multipart/form-data"  >
		
				<label >Medium: </label><input type="file" id="myfile5" name="myfile5" onchange="change_image5()"  />
				<input type="text" id="page" name="page" value="3" style="visibility:hidden" />
				<input type="text" id="page" name="image" value="5" style="visibility:hidden" />
				<input type="text" id="file" name="file" value="myfile5" style="visibility:hidden" />
				
			</form>
		
</div>

<div style="width:45%;float:right;margin-right:2%" >
	<div style="float:right;background-color:grey;margin-top:0%;margin-right:4%;width:50%;height:350px" >
		<div style="height:67%;background-color:green;">
			<div style="width:60%;height:100%;background-color:blue;float:left;" id="add-image1" >
				<img src="<?php echo base_url(); ?><?php echo $pages[2]->image1; ?>" width="100%" height="100%" style="border:3px solid white;" />
			</div>
			<div style="width:40%;height:100%;background-color:pink;float:right;">
				<div style="height:50%;background-color:grey;" id="add-image2" >
				<img src="<?php echo base_url(); ?><?php echo $pages[2]->image2; ?>" width="100%" height="100%" style="border:3px solid white;" /></div>
				<div style="height:50%;background-color:red;border:3px solid white;" id="add-image3" >
				<img src="<?php echo base_url(); ?><?php echo $pages[2]->image3; ?>" width="100%" height="100%" style="border:3px solid white;" /></div>
			</div>
		</div>
			
		<div style="height:33%;;background-color:yellow;">
			<div style="width:40%;height:100%;background-color:yellow;float:left" id="add-image4">
			<img src="<?php echo base_url(); ?><?php echo $pages[2]->image4; ?>" width="100%" height="100%" style="border:3px solid white;" /></div>
			<div style="width:60%;height:100%;background-color:green;float:right" id="add-image5">
			<img src="<?php echo base_url(); ?><?php echo $pages[2]->image5; ?>" width="100%" height="100%" id="image5" style="border:3px solid white;" /></div>
		</div>
		
		<!-- <input type="button" name="check"  value ="check" onclick="check_image()" /> -->
		<form method="post" name="image_form1" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  enctype="multipart/form-data"  >

		<input type="file" style="visibility:hidden" id="myfile1" name="myfile1" onchange="change_image1()"  />
		<input type="text" id="page" name="page" value="1" style="visibility:hidden" />
		<input type="text" id="page" name="image" value="1" style="visibility:hidden" />
		<input type="text" id="file" name="file" value="myfile1" style="visibility:hidden" />
		
	</form>
	<form method="post" name="image_form2" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  enctype="multipart/form-data"  >

		<input type="file" style="visibility:hidden" id="myfile2" name="myfile2" onchange="change_image2()"  />
		<input type="text" id="page" name="page" value="1" style="visibility:hidden" />
		<input type="text" id="page" name="image" value="2" style="visibility:hidden" />
		<input type="text" id="file" name="file" value="myfile2" style="visibility:hidden" />
		
	</form>
	
	<form method="post" name="image_form3" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  enctype="multipart/form-data"  >

		<input type="file" style="visibility:hidden" id="myfile3" name="myfile3" onchange="change_image3()"  />
		<input type="text" id="page" name="page" value="1" style="visibility:hidden" />
		<input type="text" id="page" name="image" value="3" style="visibility:hidden" />
		<input type="text" id="file" name="file" value="myfile3" style="visibility:hidden" />
		
	</form>
	
	<form method="post" name="image_form4" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  enctype="multipart/form-data"  >

		<input type="file" style="visibility:hidden" id="myfile4" name="myfile4" onchange="change_image4()"  />
		<input type="text" id="page" name="page" value="1" style="visibility:hidden" />
		<input type="text" id="page" name="image" value="4" style="visibility:hidden" />
		<input type="text" id="file" name="file" value="myfile4" style="visibility:hidden" />
		
	</form>
	
	<form method="post" name="image_form5" action="<?php echo base_url();?>index.php/advertiser/upload_file/upload_image_file"  enctype="multipart/form-data"  >

		<input type="file" style="visibility:hidden" id="myfile5" name="myfile5" onchange="change_image5()"  />
		<input type="text" id="page" name="page" value="1" style="visibility:hidden" />
		<input type="text" id="page" name="image" value="5" style="visibility:hidden" />
		<input type="text" id="file" name="file" value="myfile5" style="visibility:hidden" />
		
	</form>
	
	</div></div>
