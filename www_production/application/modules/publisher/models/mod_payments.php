<?php
class Mod_payments extends CI_Model
{  
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_report_pub_amt($where_arr=0)
    {
        if($where_arr!=0)
        {
            $this->db->where("oxm_report.publisherid",$where_arr);
        }

        $this->db->select('sum(oxm_report.publisher_amount) as reportrev');

        $query = $this->db->get('oxm_report');
        
        return $query->result();
    }

    function get_admin_payment($where_arr=0)
    {
        if($where_arr!=0)
        {
            $this->db->where("oxm_admin_payment.publisherid",$where_arr);
        }

        $this->db->where("oxm_admin_payment.status",'1');

        $this->db->select('sum(oxm_admin_payment.amount) as adminpayment');

        $query = $this->db->get('oxm_admin_payment');

        return $query->result();
    }

    function get_last_issued_payment($where_arr=0, $where_pay=0)
    {
        if($where_arr!=0)
        {
            $this->db->where("oxm_admin_payment.publisherid",$where_arr);
        }

        if($where_pay!=0)
        {
            $this->db->where("oxm_admin_payment.id",$where_pay);
        }

        $this->db->where("oxm_admin_payment.status",'1');

        $this->db->order_by("oxm_admin_payment.clearing_date","DESC");

        $query = $this->db->get('oxm_admin_payment');
        if($query->num_rows()>0)
        {
        	return $query->result();
        }
        else
        {
        	return 0;
        }
    }

    function get_available_months($where_arr=0)
    {
        /*if($where_arr!=0)
        {
                $this->db->where('oxm_report.publisherid',$where_arr);
        }

        $this->db->select('MONTH(date) as month, YEAR(date) as year');

        $this->db->order_by('oxm_report.date','DESC');

        $this->db->group_by('MONTH(oxm_report.date)');

        $query = $this->db->get('oxm_report'); */
        
        
        $av_mon_year_sql = "SELECT t.month,t.year FROM
							(
							(
								SELECT MONTH(date) as month, YEAR(date) as year FROM oxm_report 
								WHERE publisherid ={$where_arr} 	
							)UNION
							(
								SELECT MONTH(date) as month, YEAR(date) as year FROM oxm_admin_payment 
								WHERE publisherid ={$where_arr} 	
							)					
							) as t GROUP BY t.year,t.month ORDER BY t.year DESC,t.month DESC"; 
        
        $query = $this->db->query($av_mon_year_sql);
        

        $result = $query->result(); 

       //echo $this->db->last_query();exit;

        return $result;
    }

    function monthly_debit($where_pub=0,$lastDate=0)
    {
        if($where_pub!=0)
        {
                $this->db->where('publisherid',$where_pub);
        }

        if($lastDate!=0)
        {
                $this->db->where('clearing_date <=',$lastDate);
        }

        $this->db->where("oxm_admin_payment.status",'1');

        $this->db->select('SUM(amount) as tot_debit');

        $query = $this->db->get('oxm_admin_payment');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_debit;
    }

    function one_month_debit($where_pub=0,$firstDate=0,$lastDate=0)
    {
        if($where_pub!=0)
        {
                $this->db->where('publisherid',$where_pub);
        }

        if($firstDate!=0)
        {
                $this->db->where('clearing_date >=',$firstDate);
        }

        if($lastDate!=0)
        {
                $this->db->where('clearing_date <=',$lastDate);
        }

        $this->db->where("oxm_admin_payment.status",'1');

        $this->db->select('SUM(amount) as tot_debit');

        $query = $this->db->get('oxm_admin_payment');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_debit;
    }

    function monthly_credit($where_pub=0,$lastDate=0)
    {
        if($where_pub!=0)
        {
                $this->db->where('publisherid',$where_pub);
        }

        if($lastDate!=0)
        {
                $this->db->where('date <=',$lastDate);
        }

        $this->db->select('SUM(publisher_amount) as tot_credit');

        $query = $this->db->get('oxm_report');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_credit;
    }

    function one_month_credit($where_pub=0,$firstDate=0,$lastDate=0)
    {
        if($where_pub!=0)
        {
                $this->db->where('publisherid',$where_pub);
        }

        if($firstDate!=0)
        {
                $this->db->where('date >=',$firstDate);
        }

        if($lastDate!=0)
        {
                $this->db->where('date <=',$lastDate);
        }

        $this->db->select('SUM(publisher_amount) as tot_credit');

        $query = $this->db->get('oxm_report');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result[0]->tot_credit;
    }

    function get_available_dates($where_pub=0,$where_month=0,$where_year =0)
    {
        if($where_pub!=0)
        {
                $this->db->where('publisherid',$where_pub);
        }

        if($where_year!=0 || $where_month!=0)
        {
                $this->db->where('MONTH(date)',$where_month);
				$this->db->where('YEAR(date)',$where_year);
        }

        $this->db->select('*, SUM(publisher_amount) as earn_amt, MONTH(date) as month, YEAR(date) as year');

        $this->db->group_by('date');

        $query = $this->db->get('oxm_report');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result;
    }

    function get_debited_amount($where_pub=0,$where_date=0)
    {
        if($where_pub!=0)
        {
                $this->db->where('publisherid',$where_pub);
        }

        if($where_date!=0)
        {
                $this->db->where('clearing_date',$where_date);
        }

        $this->db->where('status','1');

        $this->db->select('*, amount as debit_amt, MONTH(clearing_date) as month, YEAR(clearing_date) as year');

        $this->db->group_by('clearing_date');

        $query = $this->db->get('oxm_admin_payment');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result;
    }

    function withdraw_limit()
    {
        $this->db->select('Amount');

        $query = $this->db->get('oxm_withdraw');

        $result = $query->result();

        //echo $this->db->last_query();exit;

        return $result;
    }
    
     //payment process
	function get_sum_amount($id)
	{
		$this->db->select('sum(publisher_amount) as publisher_amount');
		
		$this->db->where('publisherid',$id);
		
		$query = $this->db->get('oxm_report');
		
		$temp = $query->row();
		
		if($temp->publisher_amount > 0)
		{
			
			return $temp->publisher_amount;
		}
		else
		{
			return 0;
		}		
	}
	
	function get_paid_amount($id)
	{
		$this->db->select('sum(amount) as amount');
		
		$this->db->where('publisherid',$id);
		
		$query = $this->db->get('oxm_admin_payment');
		
		$temp = $query->row();
		
		if($temp->amount > 0)
		{
			
			return $temp->amount;
		}
		else
		{
			return 0;
		}		
	}
	function get_admin_amount()
	{
		$this->db->select('Amount');
		
		$query = $this->db->get('oxm_withdraw');
		
		$temp = $query->row();
		
		if($temp->Amount > 0)
		{
			
			return $temp->Amount;
		}
		else
		{
			return 0;
		}		
	}
	
	/*Apply for payment by publisher */
	function add_payment_data($ins_arr)
	{
		$this->db->insert('oxm_admin_payment',$ins_arr);
		
		if($this->db->affected_rows()>0)
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}
	public function check_user($name)
	{
		
		$this->db->where('username',$name);
		
		$query	=	$this->db->get('oxm_userdetails')->num_rows();
		
		if($query > 0)
			{
				return '1';				
			}
		else
			{
				return '0';
			}		
			
	}
	
	public function get_advertiser_email($advid)
	{
		$this->db->where('clientid',$advid);
		
		$this->db->select('email');
		
		$query = $this->db->get('ox_clients');
		
		$record = $query->row();
		
		if(isset($record->email))
		{
			return $record->email;				
		}
		else
		{
			return '0';
		}	
	}
	public function export_publisher_payment_details($data)
	{
		
		
				//$filename=$this->lang->line('lang_statistics_title_global').$from_date."--".$to_date.$this->lang->line('lang_statistics_title_global_date_wise');
					$filename = str_replace(' ','_',$this->session->userdata('session_publisher_contact'));
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
							$workSheet->SetCellValue('D9',$this->lang->line('lang_publisher_option_earn_and_credit'));
							$workSheet->SetCellValue('E9',$this->lang->line('lang_publisher_option_pay_and_debit'));
							$workSheet->SetCellValue('F9', $this->lang->line('lang_publisher_option_balance'));
			
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$workSheet->setTitle($this->lang->line('lang_publisher_option_earn_and_pay'));
						 	$monthAvail = $data['monthAvail'];
						 
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
									$publisher = $this->session->userdata('session_publisher_id');
							
									$monthlyDebit = $this->monthly_debit($publisher,$lastDate);
							
									$onemonthDebit = $this->one_month_debit($publisher,$firstDate,$lastDate);
							
									$monthlyCredit = $this->monthly_credit($publisher,$lastDate);
							
									$onemonthCredit = $this->one_month_credit($publisher,$firstDate,$lastDate);
									$endmonthBal = $monthlyCredit-$monthlyDebit;       
								
									$workSheet->setCellValueByColumnAndRow(1,$row,'');
									$workSheet->setCellValueByColumnAndRow(2,$row,date("F", mktime(0, 0, 0, $month->month, 10)).' '.$month->year );
									$workSheet->setCellValueByColumnAndRow(3,$row,($onemonthCredit!='')?'$'.number_format($onemonthCredit,2):'');
									$workSheet->setCellValueByColumnAndRow(4,$row,($onemonthDebit!='')?'$'.number_format($onemonthDebit,2):'');
									$workSheet->setCellValueByColumnAndRow(5,$row,($endmonthBal!='')?'$'.number_format($endmonthBal,2):'');
								
							  $avail_dates = $this->mod_payments->get_available_dates($publisher,$month->month);
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
									$workSheet->setCellValueByColumnAndRow(2,$row,$this->lang->line('lang_publisher_option_earned'));
									$workSheet->setCellValueByColumnAndRow(3,$row,number_format($availDate->earn_amt,2));
									$workSheet->setCellValueByColumnAndRow(4,$row,'');
									$workSheet->setCellValueByColumnAndRow(5,$row,'');
									$row++;	
								}
							}
							
							 $avail_debit = $this->mod_payments->get_debited_amount($publisher);
							
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
										
										$workSheet->SetCellValue('B'.$row,date('M d, Y', strtotime($debitAmt->clearing_date)));
										$workSheet->SetCellValue('C'.$row,$this->lang->line('lang_paid_amount'));
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
							redirect('publisher/payments');
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
