<?php
class Mod_payments extends CI_Model
{ 
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    /* Last Payment Update */
    function last_payment_update($where_arr=0)
    {
        if($where_arr!=0)
        {
                $this->db->where('oxm_paypal.clientid',$where_arr);
        }
        
        $this->db->select('ox_clients.clientname,oxm_paypal.*');

        $this->db->join('ox_clients','ox_clients.clientid=oxm_paypal.clientid');

        $this->db->order_by('oxm_paypal.date','DESC');

	$this->db->order_by('oxm_paypal.id','DESC');

        $query = $this->db->get('oxm_paypal');

        $result = $query->result();

        //echo $this->db->last_query();exit;
        
        return $result;
    }

    function get_available_months($where_arr=0)
    {
        /*
        if($where_arr!=0)
        {
                $this->db->where('oxm_report.clientid',$where_arr);
        }

        $this->db->select('MONTH(date) as month, YEAR(date) as year');

        $this->db->order_by('MONTH(oxm_report.date)','DESC');

        $this->db->group_by('MONTH(oxm_report.date)');
	
	$this->db->join('oxm_paypal','oxm_paypal.clientid=oxm_report.clientid');

        $query = $this->db->get('oxm_report');
	
	*/

	$query = "select *
		from
		(
			SELECT MONTH(date) as month, YEAR(date) as year FROM oxm_report WHERE clientid = $where_arr GROUP BY MONTH(oxm_report.date)
			UNION
			SELECT MONTH(date) as month, YEAR(date) as year FROM oxm_paypal WHERE clientid = $where_arr GROUP BY MONTH(oxm_paypal.date)
		) as t
		ORDER BY t.year DESC, t.month DESC "; 
	$result = $this->db->query($query);

        $record = $result->result();

        return $record;
    }

    function get_available_dates($where_adv=0,$where_month=0,$where_year=0)
    {
        if($where_adv!=0)
        {
                $this->db->where('clientid',$where_adv);
        }

        if($where_month!=0)
        {
                $this->db->where('MONTH(date)',$where_month);
                $this->db->where('YEAR(date)',$where_year);
        }

        $this->db->select('*, SUM(amount) as spend_amt, MONTH(date) as month, YEAR(date) as year');

        $this->db->group_by('date');

        $query = $this->db->get('oxm_report');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result;
    }

    function get_debited_amount($where_adv=0,$where_date=0)
    {
        if($where_adv!=0)
        {
                $this->db->where('clientid',$where_adv);
        }

        if($where_date!=0)
        {
                $this->db->where('date',$where_date);
        }

        $this->db->select('*, SUM(Amount) as debit_amt, MONTH(date) as month, YEAR(date) as year');

        $this->db->group_by('date');

        $query = $this->db->get('oxm_paypal');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result;
    }

    function monthly_debit($where_adv=0,$lastDate=0)
    {
        if($where_adv!=0)
        {
                $this->db->where('clientid',$where_adv);
        }

        if($lastDate!=0)
        {
                $this->db->where('date <=',$lastDate);
        }

        $this->db->select('SUM(Amount) as tot_debit');

        $query = $this->db->get('oxm_paypal');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_debit;
    }

    function one_month_debit($where_adv=0,$firstDate=0,$lastDate=0)
    {
        if($where_adv!=0)
        {
                $this->db->where('clientid',$where_adv);
        }
        
        if($firstDate!=0)
        {
                $this->db->where('date >=',$firstDate);
        }

        if($lastDate!=0)
        {
                $this->db->where('date <=',$lastDate);
        }

        $this->db->select('SUM(Amount) as tot_debit');

        $query = $this->db->get('oxm_paypal');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_debit;
    }

    function monthly_credit($where_adv=0,$lastDate=0)
    {
        if($where_adv!=0)
        {
                $this->db->where('clientid',$where_adv);
        }

        if($lastDate!=0)
        {
                $this->db->where('date <=',$lastDate);
        }

        $this->db->select('SUM(amount) as tot_credit');

        $query = $this->db->get('oxm_report');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_credit;
    }

    function one_month_credit($where_adv=0,$firstDate=0,$lastDate=0)
    {
        if($where_adv!=0)
        {
                $this->db->where('clientid',$where_adv);
        }

        if($firstDate!=0)
        {
                $this->db->where('date >=',$firstDate);
        }

        if($lastDate!=0)
        {
                $this->db->where('date <=',$lastDate);
        }

        $this->db->select('SUM(amount) as tot_credit');

        $query = $this->db->get('oxm_report');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_credit;
    }

    /* Update Paypal Order IPN details */
    function insert_paypal_order($paypal_order)
    {
        $this->db->insert('oxm_paypal_report', $paypal_order);

        if($this->db->affected_rows()>0)
        {
                return $this->db->insert_id();
        }
        else
        {
                return FALSE;
        }
    }
    
     /*-------------------------------------------------------------------------------
	FUNCTIONS RELATED TO FUND PROCESS FOR SELECTED ADVERTISER
     --------------------------------------------------------------------------------*/
	 
	 function getFund($adv_id){
		
		$this->db->where("clientid", $adv_id); 
		
		$query = $this->db->select('accbalance')->get('oxm_accbalance');
		
		if($query->num_rows >0)
		{
			$rs	= $query->row();
			$fund	= $rs->accbalance;
		}
		else
		{
			$fund	= FALSE;
		}
		return $fund;
    	}
    	
    	function insert_fund($clientid, $amount)
	{
		if(!empty($amount)) {
		 
		 $data = array("clientid"=>$clientid,"accbalance"=>text_db($amount));
		 
		 $this->db->insert("oxm_accbalance",$data);
		 $status =$this->db->affected_rows();
			 if($status >0){
			 	return true;
			 }
			 else{
			 	return false;
			 }
		 }
	}
    	
    	function update_fund($clientid, $amount)
	{
		if(!empty($amount)) {
		 
		 $this->db->where("clientid", $clientid);
		 $data = array("accbalance"=>text_db($amount));
		 
		 $this->db->update("oxm_accbalance",$data);
		 $status =$this->db->affected_rows();
			 if($status >0){
			 	return true;
			 }
			 else{
			 	return false;
			 }
		 }
	}
	
	function insert_paypal_fund($clientid, $amount)
	{
		$data = array(
						"clientid" =>$clientid, 
						"Amount" =>$amount, 
						"date" =>date("Y-m-d")
				);
	
		$this->db->insert('oxm_paypal',$data);
		if($this->db->affected_rows()>0)
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}

    	//Export to Excel Payments
	function export_payment_details_excel($data)
	{
		//$filename=$this->lang->line('lang_statistics_title_global').$from_date."--".$to_date.$this->lang->line('lang_statistics_title_global_date_wise');
					$filename = str_replace(' ','_',$this->session->userdata('session_advertiser_contact'));
					$filename .= "_".$this->lang->line('label_payment_history').date('d.m.Y');
				
					//$filename = 'payments';
					// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_global_history'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_global_history'));
							$objPHPExcel->getSecurity()->setLockWindows(true);
							$objPHPExcel->getSecurity()->setLockStructure(true);
							$objPHPExcel->getSecurity()->setWorkbookPassword("PHPExcel");
							
							//Set default style
							$workSheet->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$workSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$workSheet->getColumnDimension('B')->setAutoSize(true);
							$workSheet->getColumnDimension('C')->setWidth(20);
							$workSheet->getColumnDimension('D')->setWidth(20);
							$workSheet->getColumnDimension('E')->setWidth(20);
							$workSheet->getColumnDimension('F')->setWidth(20);
						
						
							//Merge Cells for Heading
							$workSheet->mergeCells('B3:F4');
							$workSheet->getRowDimension(9)->setRowHeight(20);
							//$workSheet->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$workSheet->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 16,
																					'color'	=>array('rgb' => '333')
																					),
																					'alignment' => array(
																										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																										'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
																										'wrap'       => true
																										)
																					),
																					'B3:C4'
																				);
							
							//Style for table heading
							$tabletitleArray	= array(
														'font' 		=> array(
																		'bold'		=> true,
																		'italic'	=> false,
																		'size'		=> 12,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for table heading							
							$tableDataArray	= array(
												'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 10.5,
																					'color'	=>array('rgb' => '333')
																					),
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => 'DDE4F4')
																			)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);	
												
							$borderArray = array(
														'borders' => array(
														'outline' => array(
														'style' => PHPExcel_Style_Border::BORDER_THIN,
														'color' => array('rgb' => '4E5A7A'),
																			)
														)
												);
							/*Style for Debits*/
							$debitArray	=		array(
													'font'=>array('size'=>10.5)	
														);					
									

							$workSheet->getStyle('B9:F9')->applyFromArray($tabletitleArray);

							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$workSheet->SetCellValue('B3', $this->lang->line('lang_publisher_option_earn_and_pay'));
							
							
							$workSheet->SetCellValue('B9',$this->lang->line('lang_statistics_global_date'));
							$workSheet->SetCellValue('C9',$this->lang->line('lang_publisher_option_description'));
							$workSheet->SetCellValue('D9',$this->lang->line('lang_publisher_option_pay_and_debit'));
							$workSheet->SetCellValue('E9',$this->lang->line('lang_publisher_option_earn_and_credit'));
							$workSheet->SetCellValue('F9', $this->lang->line('lang_publisher_option_balance'));
			
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$workSheet->setTitle($this->lang->line('lang_publisher_option_earn_and_pay'));
						 	$monthAvail = $data['monthAvail'];
							$advertiser=$data['advertiser'];
						 
							if(count($monthAvail)){
   							 $i=1;
							 $row=10;
   								 foreach($monthAvail as $month)
								 {
									$workSheet->getStyle('B'.$row.':F'.$row)->applyFromArray($tableDataArray);
									
									$workSheet->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									
									$workSheet->getRowDimension($row)->setRowHeight(15);
									
									$lastDate = date('Y-m-t', strtotime($month->year.'-'.$month->month.'-'.'01'));
									$firstDate = date('Y-m-d', strtotime($month->year.'-'.$month->month.'-'.'01'));
							
									$monthlyDebit = $this->monthly_debit($advertiser,$lastDate);
							
									$onemonthDebit = $this->one_month_debit($advertiser,$firstDate,$lastDate);
							
									$monthlyCredit = $this->monthly_credit($advertiser,$lastDate);
							
									$onemonthCredit = $this->one_month_credit($advertiser,$firstDate,$lastDate);
									$endmonthBal = $monthlyDebit-$monthlyCredit;        
								
									$workSheet->setCellValueByColumnAndRow(1,$row,'');
									$workSheet->setCellValueByColumnAndRow(2,$row,date("F", mktime(0, 0, 0, $month->month, 10)).' '.$month->year );
									$workSheet->setCellValueByColumnAndRow(3,$row,($onemonthCredit!='')?'$'.number_format($onemonthCredit,2):'');
									$workSheet->setCellValueByColumnAndRow(4,$row,($onemonthDebit!='')?'$'.number_format($onemonthDebit,2):'');
									$workSheet->setCellValueByColumnAndRow(5,$row,($endmonthBal!='')?'$'.number_format($endmonthBal,2):'');
								
							  $avail_dates = $this->mod_payments->get_available_dates($advertiser,$month->month);
							//print_r($data['advertiser_list']);
							$row++;
    						 if(count($avail_dates))
							 {
   								 foreach($avail_dates as $availDate)
									{
									$col=0;
									$workSheet->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									$workSheet->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$workSheet->getStyle('D'.$row)->getNumberFormat()
->setFormatCode('#,##0.00');
									
									$workSheet->setCellValueByColumnAndRow(1,$row,date('M d, Y', strtotime($availDate->date)));
									$workSheet->setCellValueByColumnAndRow(2,$row,$this->lang->line('lang_spends'));
									$workSheet->setCellValueByColumnAndRow(3,$row,number_format($availDate->spend_amt,2));
									$workSheet->setCellValueByColumnAndRow(4,$row,'');
									$workSheet->setCellValueByColumnAndRow(5,$row,'');
									$row++;	
								}
							}
							
							 $avail_debit = $this->mod_payments->get_debited_amount($advertiser);
							
							 if(count($avail_debit))
							{
								foreach($avail_debit as $debitAmt)
								{
									if($month->year==$debitAmt->year && $month->month==$debitAmt->month)
									{
										$workSheet->getRowDimension($row)->setRowHeight(20);
										$workSheet->getStyle('B'.$row.':F'.$row)->applyFromArray($debitArray);
										$workSheet->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
										$workSheet->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
										$workSheet->getStyle('E'.$row)->getNumberFormat()
->setFormatCode('#,##0.00');
										
										$workSheet->SetCellValue('B'.$row,date('M d, Y', strtotime($debitAmt->date)));
										$workSheet->SetCellValue('C'.$row,$this->lang->line('label_credited'));
										$workSheet->SetCellValue('D'.$row,'');
										$workSheet->SetCellValue('E'.$row,number_format($debitAmt->debit_amt,2));
										$workSheet->SetCellValue('F'.$row,'');
									$row++;
								}	
								
							}
						}
						 
						 $workSheet->mergeCells("B$row:E$row");
						 $workSheet->getStyle('B'.$row.':F'.$row)->applyFromArray($dateArray);
						 $workSheet->getRowDimension($row)->setRowHeight(20);
							
						$workSheet->SetCellValue('B'.$row,$this->lang->line('lang_publisher_option_end_of').''.date("F", mktime(0, 0, 0, $month->month, 10)).' '.$this->lang->line('lang_publisher_option_balance'));
						 $workSheet->SetCellValue('F'.$row,'$'.number_format($endmonthBal,2));
						$row++;
						}
						$workSheet->getStyle('B9:F'.$row)->applyFromArray($borderArray);
						}else{
							redirect('advertiser/payments');
						}
							// Save Excel 2003 file
							//echo date('H:i:s') . " Write to Excel2003 format\n";

							header('Content-Type: application/vnd.ms-excel');
							//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
							header('Content-Disposition: attachment;filename='.$filename.'.xls');
							header('Cache-Control: max-age=0');
							//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
							$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
							

							//ob_end_clean();
							$objWriter->save('php://output');
							
							
	}
}
