<?php
/**
 * PayPal_Lib Controller Class (Paypal IPN Class)
 *
 * Paypal controller that provides functionality to the creation for PayPal forms, 
 * submissions, success and cancel requests, as well as IPN responses.
 *
 * The class requires the use of the PayPal_Lib library and config files.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Commerce
 * @author      Ran Aroussi <ran@aroussi.com>
 * @copyright   Copyright (c) 2006, http://aroussi.com/ci/
 *
 */

class Paypal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
                $this->load->library('Paypal_Lib');
                $this->load->model('mod_payments');
                /* Libraries */
		$this->load->library('email');
	}

	function index()
	{
		$this->form();
	}
	
	function form()
	{
		
            // GET Administrator PAYPAL Account ID 
		
	    $this->db->where('accountid',2); 
	    $query	=	$this->db->get('oxm_admindetails');
            $admin_data =  $query->result();
		 
	    // ASSIGN Administrator PAY PAL Account EMAIL ID 
		 
	    $this->paypal_lib->add_field('business',$admin_data[0]->paypalid);	
	    $this->paypal_lib->add_field('return', site_url('advertiser/paypal/success'));
	    $this->paypal_lib->add_field('cancel_return', site_url('advertiser/paypal/cancel'));
	    $this->paypal_lib->add_field('notify_url', site_url('advertiser/paypal/ipn')); // <-- IPN url
	    $this->paypal_lib->add_field('custom', $this->session->userdata('session_advertiser_id')); // <-- Verify return

	    $this->paypal_lib->add_field('item_name', 'Add Fund to AdNetwork');
	    $this->paypal_lib->add_field('item_number', '1');
	    $this->paypal_lib->add_field('amount', $this->session->userdata('amount'));

		// if you want an image button use this:
		$this->paypal_lib->image('button_03.gif');
		
		// otherwise, don't write anything or (if you want to 
		// change the default button text), write this:
		// $this->paypal_lib->button('Click to Pay!');
		
	    $data['paypal_form'] = $this->paypal_lib->paypal_form();
	
            $this->load->view('advertiser/paypal/form', $data);
        
	}

	function auto_form()
	{
		//$this->paypal_lib->add_field('business', 'dinesh_1338204226_biz@dreamajax.com');
		
		// GET Administrator PAYPAL Account ID 
		
		 $this->db->where('accountid',2); 
		 $query	=	$this->db->get('oxm_admindetails');
        	 $admin_data =  $query->result();
		 
		// ASSIGN Administrator PAY PAL Account ID 
		 
		$this->paypal_lib->add_field('business',$admin_data[0]->paypalid);		

		$this->paypal_lib->add_field('return', site_url('advertiser/paypal/success'));
		$this->paypal_lib->add_field('cancel_return', site_url('advertiser/paypal/cancel'));
		$this->paypal_lib->add_field('notify_url', site_url('advertiser/paypal/ipn')); // <-- IPN url
		$this->paypal_lib->add_field('custom', $this->session->userdata('session_advertiser_id')); // <-- Verify return

		$this->paypal_lib->add_field('item_name', 'Add Fund to AdNetwork');
                $this->paypal_lib->add_field('item_number', '1');
		$this->paypal_lib->add_field('amount', $this->session->userdata('amount'));
		$data['button']=$this->paypal_lib->get_button_content();
		$data['form']=$this->paypal_lib->get_paypal_form_content();
		$this->load->view('advertiser/paypal_wait',$data);
		//$this->paypal_lib->paypal_auto_form();
	}
	function cancel()
	{
            log_message('info', 'We are in Failure...');
            /*-------------------------------------------------------------
                Breadcrumb Setup Start
             -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;
            /*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content']	= $this->load->view('advertiser/paypal/cancel',$data,true);
            $this->load->view('advertiser_layout',$data);
	}
	
	function success()
	{
		// This is where you would probably want to thank the user for their order
		// or what have you.  The order information at this point is in POST 
		// variables.  However, you don't want to "process" the order until you
		// get validation from the IPN.  That's where you would have the code to
		// email an admin, update the database with payment status, activate a
		// membership, etc.
	
		// You could also simply re-direct them to another page, or your own 
		// order status page which presents the user with the status of their
		// order based on a database (which can be modified with the IPN code 
		// below).
                log_message('info', 'We are Success...');

                $data['pp_info'] = $this->input->post();
		
                /*-------------------------------------------------------------
                    Breadcrumb Setup Start
                 -------------------------------------------------------------*/
                $link = breadcrumb();
                $data['breadcrumb'] = $link;
                /*-------------------------------------------------------------
                        Embed current page content into template layout
                 -------------------------------------------------------------*/
                $data['page_content']	= $this->load->view('advertiser/paypal/success',$data,true);
                $this->load->view('advertiser_layout',$data);
	}
	
	function ipn()
	{
		// Payment has been received and IPN is verified.  This is where you
		// update your database to activate or process the order, or setup
		// the database with the user's order details, email an administrator,
		// etc. You can access a slew of information via the ipn_data() array.
 
		// Check the paypal documentation for specifics on what information
		// is available in the IPN POST variables.  Basically, all the POST vars
		// which paypal sends, which we send back for validation, are now stored
		// in the ipn_data() array.
 
		// For this example, we'll just email ourselves ALL the data.
		$to = 'vnmuthu@gmail.com';
                log_message('info', 'We are in IPN...');
		if ($this->paypal_lib->validate_ipn()) 
		{
                        /* Store Paypal IPN data into Database */
                        $advertiser     = $this->paypal_lib->ipn_data['custom'];
                        
                       /*  $adv_email = $this->mod_payment->get_advertiser_email($advertiser);
                        log_message('info', $adv_email);
                        if($adv_email)
                        {
                        	$to = $adv_email;
                        }
                        else
                        {
                        	$to = 'vnmuthu@gmail.com';
                        } */	
                        
                        $payer_email    = $this->paypal_lib->ipn_data['payer_email'];
                        $receiver_email = $this->paypal_lib->ipn_data['receiver_email'];
                        $txn_id         = $this->paypal_lib->ipn_data['txn_id'];
                        $ipn_track_id   = $this->paypal_lib->ipn_data['ipn_track_id'];
                        $payment_gross  = $this->paypal_lib->ipn_data['payment_gross'];
                        $mc_currency    = $this->paypal_lib->ipn_data['mc_currency'];
                        $item_name      = $this->paypal_lib->ipn_data['item_name'];
                        $item_number    = $this->paypal_lib->ipn_data['item_number'];
                        $quantity       = $this->paypal_lib->ipn_data['quantity'];
                        $payer_status   = $this->paypal_lib->ipn_data['payer_status'];
                        $payment_status = $this->paypal_lib->ipn_data['payment_status'];
                        $payment_date   = $this->paypal_lib->ipn_data['payment_date'];
                        $country_code   = $this->paypal_lib->ipn_data['address_country_code'];

                        $this->session->set_userdata('paid_amount',$payment_gross);

                        $update_order = array(
                            'client_id'     =>  $advertiser,
                            'payer_email'   =>  $payer_email,
                            'receiver_email'=>  $receiver_email,
                            'txn_id'        =>  $txn_id,
                            'ipn_track_id'  =>  $ipn_track_id,
                            'payment_gross' =>  $payment_gross,
                            'mc_currency'   =>  $mc_currency,
                            'item_name'     =>  $item_name,
                            'item_number'   =>  $item_number,
                            'quantity'      =>  $quantity,
                            'payer_status'  =>  $payer_status,
                            'payment_status'=>  $payment_status,
                            'payment_date'  =>  $payment_date,
                            'address_country_code'=> $country_code
                        );
                        log_message('info',$update_order);
                        $last_order_id = $this->mod_payments->insert_paypal_order($update_order);
                        
                        /*------------------------------------------------------------------------
				GET PREVIOUS FUND VALUE FOR ADDING NEW FUND
			--------------------------------------------------------------------------*/
			$existing_fund	=	$this->mod_payments->getFund($advertiser);
							
			$current_value	=($existing_fund + $payment_gross);
		
			if($existing_fund != FALSE)
			{
				$this->mod_payments->update_fund($advertiser,$current_value);
			}
			else
			{
				$this->mod_payments->insert_fund($advertiser,$current_value);
			}
                        
                        $this->mod_payments->insert_paypal_fund($advertiser,$payment_gross);
                        
                        if(!empty($last_order_id))
                        {
                            log_message('info',$last_order_id);
                            //exit;
                            //print_r($this->paypal_lib->ipn_data);exit;
                            $payer_name  = $this->paypal_lib->ipn_data['first_name'];
                            //$body .= '\n An instant payment notification was successfully received from ';
                            $payer_email= $this->paypal_lib->ipn_data['payer_email'] . ' on '.date('m/d/Y') . ' at ' . date('g:i A') . "\n\n";                     $data['payment_gross'] = $payment_gross;
			    $data['payment_status']= $payment_status;
			    $data['payment_date'] = $payment_date;
			    $data['txn_id']= $txn_id;
			    $data['ipn_track_id'] = $ipn_track_id;
			    $data['payer_name']= $payer_name;
			    $data['payer_email']= $payer_email;
			    $content= $this->load->view('email/registration/advertiser_payment',$data,TRUE);
			    $data['content']	=$content;
			    $mail_content=$this->load->view('email/registration/email_tpl',$data,TRUE);
			    $message=$mail_content;
			    $subject=$this->lang->line('advertiser_payment_email_subject');
			    $config['protocol'] = "sendmail";
                 	    $config['wordwrap'] = TRUE;		
		            $config['mailtype']	='html';
		            $config['charset']	='UTF-8'; 
                            $this->email->initialize($config);
			    $this->email->to($to);
              		    $this->email->from($this->paypal_lib->ipn_data['payer_email'], $this->paypal_lib->ipn_data['payer_name']);
			    $this->email->subject($subject);
			    $this->email->message($message);	
			    $this->email->send();
                   
                          
                        }
		}
	}
	
	
	
}
?>
