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

<div class="maincontent">
	
   

    <div class="left">
	<h1 class="pageTitle"><?php echo $this->lang->line('lang_add_fund');?></h1>
	<div class="form_default">
	<fieldset>
    	<?php
    	//$this->button('Click here if you\'re not automatically redirected...');

		echo '<html>' . "\n";
		echo '<head><title>Processing Payment...</title></head>' . "\n";
       
		echo '<body onLoad="document.forms[\'paypal_auto_form\'].submit();">' . "\n";
		echo '<center>';
		echo '<img src='.base_url().'assets/images/loaders/loader6.gif alt="Loading..." />'."<br/>";
		
		echo '<h3 style="margin-top:25px">Please wait, your order is being processed and you will be redirected to the paypal website.</h3>' . "\n";
		echo $button;
		echo $form;
		//echo $this->paypal_form('paypal_auto_form');
		echo '</body></html>';
		
		echo '</center>';
    	?>
        <!-- Your Working location -->
	</fieldset>
	</div>	
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<br />
<?php
/* Footer */ 
$this->load->view("includes/advertiser/footer");
?>
