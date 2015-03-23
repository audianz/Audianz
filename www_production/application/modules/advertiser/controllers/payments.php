<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payments extends CI_Controller {
	/* Page Limit */	
	var $page_limit = 10;
	
	public function __construct()
	{
		parent::__construct();
                $this->load->model('mod_payments');
                $this->load->library('Paypal_Lib');
		$this->load->library("PHPExcel");
		$this->load->library("PHPExcel/IOFactory"); 
                
                /* Login Check */
		$check_status = advertiser_login_check();	
		if($check_status == FALSE)
		{
			redirect('site');
		}
	}
	
	/* Payments Page */
	public function index()
	{
            //$adv_id = 14;
            $adv_id = $this->session->userdata('session_advertiser_id');
            $data['advertiser'] = $adv_id;
            /*-------------------------------------------------------------
                    Last Payment Update
             -------------------------------------------------------------*/
            $last_pay = $this->mod_payments->last_payment_update($adv_id);
            $data['lastPay']  = $last_pay;
            /*-------------------------------------------------------------
                    Get Available Dates of Months
             -------------------------------------------------------------*/
            $avail_months = $this->mod_payments->get_available_months($adv_id);
            $data['monthAvail']  = $avail_months;

        
            /*-------------------------------------------------------------
                    Get Available Months
             -------------------------------------------------------------
            $avail_dates = $this->mod_payments->get_available_dates($adv_id);
            $data['dateAvail']  = $avail_dates;*/
            /*-------------------------------------------------------------
                    Breadcrumb Setup Start
             -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;
            /*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content']	= $this->load->view('advertiser/payments',$data,true);
            $this->load->view('advertiser_layout',$data);
	}

        public function add_fund()
        {
            /*-------------------------------------------------------------
                    Breadcrumb Setup Start
             -------------------------------------------------------------*/
            $link = breadcrumb();
            $data['breadcrumb'] = $link;
            /*-------------------------------------------------------------
                    Embed current page content into template layout
             -------------------------------------------------------------*/
            $data['page_content']	= $this->load->view('advertiser/add_fund',$data,true);
            $this->load->view('advertiser_layout',$data);
        }

        public function add_fund_process()
        {
            $this->form_validation->set_rules('amount', 'Amount' , 'required|numeric|greaterthan[1]');
	    if($this->form_validation->run() == FALSE)
	    {
		$this->session->set_userdata('error_fund', $this->lang->line('label_enter_fund').' (or) Please enter the amount greater than 1');
		redirect("advertiser/payments/add_fund");
		
	    }
	    else
	    { 
	    $amount = $this->input->post('amount');
            $this->session->set_userdata('amount',$amount);
            redirect("advertiser/paypal/auto_form");
	    }        
	}

	//Export to Excel
	public function export_excel_payments()
	{
	    $adv_id = $this->session->userdata('session_advertiser_id');
            $data['advertiser'] = $adv_id;
            /*-------------------------------------------------------------
                    Last Payment Update
             -------------------------------------------------------------*/
            $last_pay = $this->mod_payments->last_payment_update($adv_id);
            $data['lastPay']  = $last_pay;
            /*-------------------------------------------------------------
                    Get Available Dates of Months
             -------------------------------------------------------------*/
            $avail_months = $this->mod_payments->get_available_months($adv_id);
            $data['monthAvail']  = $avail_months;
			
			$this->mod_payments->export_payment_details_excel($data);
		
		
	}	
}

/* End of file payments.php */
/* Location: ./modules/advertiser/payments.php */
