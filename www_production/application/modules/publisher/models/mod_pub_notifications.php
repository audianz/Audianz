<?php
class Mod_pub_notifications extends CI_Model 
{
		function __construct()
        {
            // Call the Model constructor
            parent::__construct();
        }
        /*Get Payment Notification List */
        function get_payment_list($pub_id='')
        {
			if($pub_id !='')
			{

					$sql = "SELECT  oxm_admin_payment.id as pay_id,oxm_paypal_report.payment_request_id as payment_request_id,oxm_admin_payment.amount as amount,oxm_admin_payment.paymenttype as paymenttype,oxm_admin_payment.date,oxm_admin_payment.status as status,oxm_paypal_report.payment_status,oxm_paypal_report.payment_paid_date";
					$sql .= " FROM oxm_admin_payment";
					$sql .=" LEFT JOIN oxm_paypal_report ON oxm_paypal_report.payment_request_id=oxm_admin_payment.id";
					$sql  .= "  WHERE oxm_admin_payment.publisherid = ".$pub_id."";
					$sql .=  "  ORDER BY GREATEST(oxm_admin_payment.date,ifnull(oxm_paypal_report.payment_paid_date,0)) DESC LIMIT 5";
					$query	=	 $this->db->query($sql);
					
					return $query->result();
			}else{
				return FALSE;
			}
		}
 	 
  		// Retreive the total credit  payment
		function get_total_paid_payments_today($pub_id='')
		{
				if($pub_id !='')
				{
					
						$query = $this->db->query("Select  count(*) as COUNT FROM oxm_admin_payment as oap JOIN oxm_paypal_report as opr ON opr.payment_request_id=oap.id WHERE oap.publisherid = ".$pub_id." AND oap.status=1 AND DATE(opr.payment_paid_date) =CURDATE()");
						
						return $query->result();
				}
		}
}
