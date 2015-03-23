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

<div class="maincontent">
	
    <div class="breadcrumbs">
    	<?php echo $breadcrumb; ?>
    </div><!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">Your Page Title</h1>
    
        <!-- Your Working location -->
		
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<br />
<?php
/* Footer */ 
$this->load->view("includes/footer");
?>
