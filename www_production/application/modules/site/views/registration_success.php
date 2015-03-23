    <div class="left">	
       <br/>
    	
      <?php echo validation_errors(); ?> 	
         <br/>
    <?php  
		if($this->session->flashdata('message') !=''):
	?>
		<div style="font-size:13px; color:#006600" width="60%" align="center">
		<?php 
			echo $this->session->flashdata('message');
		?>
		</div>
	<?php 
		endif;
	?> 
	<center><img align="middle"  src="<?php echo base_url(); ?>assets/images/Success.png" /></center>
        </div>
        <br clear="all" />

  
