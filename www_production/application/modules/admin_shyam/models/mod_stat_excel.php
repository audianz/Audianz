<?php
class Mod_stat_excel extends CI_Model 
{  
				function export_advertisers_excel($data='')
				{
						
						$stat_data=$data['stat_data'];
						
						if(!empty($stat_data['stat_list']))
						{
							//Setting the Statistical  Array
							
							 $search_arr= $this->session->userdata('statistics_search_arr');
		
							
							//Set the Variables
							$ad_count	=	count($data['advertiser_list']);
							
							// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties of the Excel
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator("Administrator");
							$objPHPExcel->getProperties()->setLastModifiedBy("Administrator");
							$objPHPExcel->getProperties()->setTitle("".$this->lang->line("label_advertiser_excel_title")."");
							$objPHPExcel->getProperties()->setSubject("");
							
							//Default Style Settings For Excel Sheet
							$workSheet->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$workSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							// Set column width
							
							//Set the dimensions for the Table Title Attributes
							$workSheet->getColumnDimension('B')->setAutoSize(true);
							$workSheet->getColumnDimension('C')->setAutoSize(true);
							$workSheet->getColumnDimension('D')->setWidth(20);
							$workSheet->getColumnDimension('E')->setWidth(20);
							$workSheet->getColumnDimension('F')->setWidth(20);
							$workSheet->getColumnDimension('G')->setWidth(20);
							$workSheet->getColumnDimension('H')->setWidth(20);
							//Merge Cells for Heading
							$workSheet->mergeCells('B3:G4');
							
							//Set the Row dimensions for Each Row 
							$workSheet->getRowDimension(6)->setRowHeight(20);
							
							/* Decalaration of Style in an Array */
							
							$workSheet->duplicateStyleArray(
							array(
							'font' => array(
							'bold'         => false,
							'italic'    	 => false,
							'size'        	 => 20,
							'color'		=>array(
            									'rgb' => '333'
												)
							),
							'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
							'wrap'       => true
								)
							),
							'B3:C4'
							);
							
							//Set the Table Title Style
							$tabletitleArray	=array(
																	'font' => array(
																	'bold'         => false,
																	'italic'    	 => false,
																	'size'        	 => 14,
																	'color'		=>array(
																						'rgb' => 'ffffff'
																						)
																	),
																	'fill'=> array(
																					'type' => PHPExcel_Style_Fill::FILL_SOLID,
																					'rotation' => 90,
																					'startcolor' => array(
																						'rgb' => '45588A',
																					)
																				)
																		);
							//$workSheet->getStyle('B9:G9')->applyFromArray($tabletstylearray);
							
							
							$tableDataArray	=		array(
																	'alignment' => array(
																	'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																	'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
																	'wrap'       => true
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
												
						$dateArray	=	array(
														'font'=>array(
																	'bold'=>true
																),
														'alignment' =>array(
														'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
														)	
														);				

						$childborderArray = array(
														'borders' => array(
														'vertical' => array(
														'style' => PHPExcel_Style_Border::BORDER_THIN,
														'color' => array('rgb' => '4E5A7A'),
																			),
														'bottom'=>array(
														'style' => PHPExcel_Style_Border::BORDER_THIN,
														'color' => array('rgb' => '4E5A7A'),
																		)
														)
												);
							
										
							//$workSheet->getStyle('A9:F9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
							
							// Add some data
							//echo date('H:i:s') . " Add some data\n";
							$objPHPExcel->setActiveSheetIndex(0);
							
							//Set the Title and Start Date ,End Date Details 
							$workSheet->SetCellValue('B3', ''.$this->lang->line("label_advertiser_sheet_name").'');
							$workSheet->SetCellValue('B6', ''.$this->lang->line("label_start_date").':');
							$workSheet->getStyle('B6')->applyFromArray($dateArray);
							
							$workSheet->SetCellValue('F6',''.$this->lang->line("label_to_date").':');
							$workSheet->getStyle('F6')->applyFromArray($dateArray);
							
							$workSheet->SetCellValue('C6', date("m/d/Y",strtotime($search_arr['from_date'])));
							$workSheet->SetCellValue('G6',date("m/d/Y",strtotime($search_arr['to_date'])));
							
							
							$workSheet->SetCellValue('B9',"".$this->lang->line('lang_statistics_advertiser_site')."");
							$workSheet->SetCellValue('D9',"".$this->lang->line('lang_statistics_advertiser_impression')."");
							$workSheet->SetCellValue('E9',"".$this->lang->line('lang_statistics_advertiser_clicks')."");
							$workSheet->SetCellValue('F9',"".$this->lang->line('lang_statistics_advertiser_conversions')."");
							$workSheet->SetCellValue('G9',"".$this->lang->line('lang_statistics_advertiser_call')."");
							$workSheet->SetCellValue('H9',"".$this->lang->line('lang_statistics_advertiser_web')."");
							$workSheet->SetCellValue('I9',"".$this->lang->line('lang_statistics_advertiser_map')."");
							$workSheet->SetCellValue('J9',"".$this->lang->line('lang_statistics_advertiser_ctr')."");
							$workSheet->SetCellValue('K9',"".$this->lang->line('lang_statistics_advertiser_spend')."");
							
							$workSheet->mergeCells('B9:C9');
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$workSheet->setTitle(''.$this->lang->line("label_advertiser_excel_title").'');
						
							//$workSheet->getStyle('B9:G9')->applyFromArray($tabletitleArray);
							//$workSheet->getRowDimension('9')->setRowHeight(20);
									
							
							
							//print_r($data['advertiser_list']);
    						if($ad_count > 0){
								$col=0;
								$row=10;
								$row_start	=	10;
								$camp_title_style = array();
								$camp_style	=	array();
								
								$stat_list		=	  $stat_data['stat_list'];
								
								$tot_val		=	  $stat_data['tot_val'];
								
								//$campaigns_list	=	$data['campaigns_list'];
								//$reports	=		$data['reports'];
								
								$i=0;
								foreach($data['advertiser_list'] as $adv)
								{
												
											$workSheet->getStyle('B'.$row.':G'.$row)->getFont()->setBold(true);
											$workSheet->mergeCells('B'.$row.':C'.$row);
											$workSheet->setCellValueByColumnAndRow(1,$row,$adv->advertiser_name);
											$workSheet->setCellValueByColumnAndRow(3,$row,isset($stat_list[$adv->client_id])?$stat_list[$adv->client_id]['IMP']:'0');
											$workSheet->setCellValueByColumnAndRow(4,$row,isset($stat_list[$adv->client_id])?$stat_list[$adv->client_id]['CLK']:'0');
											$workSheet->setCellValueByColumnAndRow(5,$row,isset($stat_list[$adv->client_id])?$stat_list[$adv->client_id]['CON']:'0');
											$workSheet->setCellValueByColumnAndRow(6,$row,isset($stat_list[$adv->client_id])?$stat_list[$adv->client_id]['CALL']:'0');
											$workSheet->setCellValueByColumnAndRow(7,$row,isset($stat_list[$adv->client_id])?$stat_list[$adv->client_id]['WEB']:'0');
											$workSheet->setCellValueByColumnAndRow(8,$row,isset($stat_list[$adv->client_id])?$stat_list[$adv->client_id]['MAP']:'0');
											$workSheet->setCellValueByColumnAndRow(8,$row,isset($stat_list[$adv->client_id])?$stat_list[$adv->client_id]['CTR']."%":"0.00%");
											$workSheet->setCellValueByColumnAndRow(10,$row,isset($stat_list[$adv->client_id])?"$".$stat_list[$adv->client_id]['SPEND']:'$0.00');
											$row++;	
													
											if(array_key_exists($adv->client_id,$stat_list))
											{
												$campaigns_list = $this->mod_statistics->get_campaigns($adv->client_id);
												$workSheet->getStyle('B'.$row.':H'.$row)->getFont()->setItalic(true);
												$reports	=	$this->mod_statistics->get_statistics_for_advertiser_campaigns($adv->client_id,$search_arr);
												$workSheet->mergeCells('B'.$row.':C'.$row);
												$workSheet->unmergeCells('B'.$row.':C'.$row);
													$workSheet->SetCellValue('B'.$row,"".$this->lang->line('lang_statistics_advertiser_campaigns')."");
													$workSheet->SetCellValue('C'.$row,"".$this->lang->line('lang_statistics_advertiser_banners')."");
													$workSheet->SetCellValue('D'.$row,"".$this->lang->line('lang_statistics_advertiser_impression')."");
													$workSheet->SetCellValue('E'.$row,"".$this->lang->line('lang_statistics_advertiser_clicks')."");
													$workSheet->SetCellValue('F'.$row,"".$this->lang->line('lang_statistics_advertiser_conversions')."");
													$workSheet->SetCellValue('G'.$row,"".$this->lang->line('lang_statistics_advertiser_call')."");
													$workSheet->SetCellValue('H'.$row,"".$this->lang->line('lang_statistics_advertiser_web')."");
													$workSheet->SetCellValue('I'.$row,"".$this->lang->line('lang_statistics_advertiser_map')."");
													$workSheet->SetCellValue('J'.$row,"".$this->lang->line('lang_statistics_advertiser_ctr')."");
													$workSheet->SetCellValue('K'.$row,"".$this->lang->line('lang_statistics_advertiser_spend')."");
													array_push($camp_title_style,$row);
													$row++;
												foreach($campaigns_list['campaigns'] as $campID => $campaignname){
													$camp_report = $reports['reports_campaigns']; 
												//echo $row;
												//echo $campaignname;
											//	echo'<br/>';
													array_push($camp_style,$row);
													$workSheet->setCellValueByColumnAndRow(1,$row,view_text($campaignname));
													$workSheet->setCellValueByColumnAndRow(3,$row,$camp_report[$campID]['IMP']);
													$workSheet->setCellValueByColumnAndRow(4,$row,$camp_report[$campID]['CLK']);
													$workSheet->setCellValueByColumnAndRow(5,$row,$camp_report[$campID]['CON']);
													$workSheet->setCellValueByColumnAndRow(6,$row,$camp_report[$campID]['CALL']);
													$workSheet->setCellValueByColumnAndRow(7,$row,$camp_report[$campID]['WEB']);
													$workSheet->setCellValueByColumnAndRow(8,$row,$camp_report[$campID]['MAP']);
													$workSheet->setCellValueByColumnAndRow(9,$row,$camp_report[$campID]['CTR'].'%');
													$workSheet->setCellValueByColumnAndRow(10,$row,'$'.$camp_report[$campID]['SPEND']);
													$row++;
												
													if(isset($campaigns_list['banners'][$campID]) AND count($campaigns_list['banners'][$campID])){
														foreach($campaigns_list['banners'][$campID] as $bannerData){
															array_push($camp_style,$row);
															$banner_report = $reports['reports_banners']; 	
															$workSheet->setCellValueByColumnAndRow(2,$row,view_text($bannerData['description']));
															$workSheet->setCellValueByColumnAndRow(3,$row,$banner_report[$campID][$bannerData['bannerid']]['IMP']);
															$workSheet->setCellValueByColumnAndRow(4,$row,$banner_report[$campID][$bannerData['bannerid']]['CLK']);
															$workSheet->setCellValueByColumnAndRow(5,$row,$banner_report[$campID][$bannerData['bannerid']]['CON']);
															$workSheet->setCellValueByColumnAndRow(6,$row,$banner_report[$campID][$bannerData['bannerid']]['CALL']);
															$workSheet->setCellValueByColumnAndRow(7,$row,$banner_report[$campID][$bannerData['bannerid']]['WEB']);
															$workSheet->setCellValueByColumnAndRow(8,$row,$banner_report[$campID][$bannerData['bannerid']]['MAP']);
															$workSheet->setCellValueByColumnAndRow(9,$row,$banner_report[$campID][$bannerData['bannerid']]['CTR'].'%');
															$workSheet->setCellValueByColumnAndRow(10,$row,'$'.$banner_report[$campID][$bannerData['bannerid']]['SPEND']);									
														$row++;
													}
												}
											}
										}
									
								}
					$total_record	=	$row-$row_start;
						//Apply the Style Attributes to the Excel Sheet
						for($i=0,$row_data=9;$i<=$total_record;$i++,$row_data++)
							{
									$workSheet->getRowDimension($row_data)->setRowHeight(15);
									//$workSheet->getStyle('B'.$row_data)->getFont()->setBold(false);
									$workSheet->getStyle('B'.$row_data.':K'.$row_data)->applyFromArray($tableDataArray);
									$workSheet->getStyle('B'.$row_data.':C'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$workSheet->getStyle('K'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
									
									
									// To set the Style for Parent and Child Relationships
									switch($row_data)
										{
												//Set the title Row Style
												case '9':
														$workSheet->getStyle('B'.$row_data.':K'.$row_data)->applyFromArray($tabletitleArray);
														$workSheet->getRowDimension($row_data)->setRowHeight(20);
														break;
												//Set the Campaign Title Style
												case  in_array($row_data,$camp_title_style):
														$workSheet->getStyle('B'.$row_data.':K'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('6A9DEC');
														$workSheet->getStyle('B'.$row_data.':K'.$row_data)->applyFromArray($childborderArray);
														$workSheet->getStyle('J'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
														$workSheet->getStyle('K'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
														break;
												//Set the Campaign inner child Style		
												case  in_array($row_data,$camp_style):
														$workSheet->getStyle('B'.$row_data.':K'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C9DAF8');
														$workSheet->getStyle('B'.$row_data.':K'.$row_data)->applyFromArray($childborderArray);
														$workSheet->getStyle('J'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
														$workSheet->getStyle('K'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
														break;
												//Set the even rows style
												case  $row_data%2 !=0:
														$workSheet->getStyle('B'.$row_data.':K'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
														break;
										}
								if($i ==$total_record)
									{
											$workSheet->getStyle('B9:K'.$row_data)->applyFromArray($borderArray);
									}
							}
						

						
						//exit;
						$workSheet->mergeCells('B'.$row.':C'.$row);
						$workSheet->SetCellValue('B'.$row,"Total");
						
						$workSheet->getStyle('B'.$row.':K'.$row)->applyFromArray($tabletitleArray);
						$workSheet->getRowDimension($row)->setRowHeight(20);
						$workSheet->getStyle('B'.$row.':K'.$row)->applyFromArray($tableDataArray);
						$workSheet->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$workSheet->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
						
						$workSheet->setCellValueByColumnAndRow(3,$row,isset($tot_val['IMP'])?$tot_val['IMP']:'0');
						$workSheet->setCellValueByColumnAndRow(4,$row,isset($tot_val['CLK'])?$tot_val['CLK']:'0');
						$workSheet->setCellValueByColumnAndRow(5,$row,isset($tot_val['CON'])?$tot_val['CON']:'0');
						$workSheet->setCellValueByColumnAndRow(6,$row,isset($tot_val['CON'])?$tot_val['CALL']:'0');
						$workSheet->setCellValueByColumnAndRow(7,$row,isset($tot_val['CON'])?$tot_val['WEB']:'0');
						$workSheet->setCellValueByColumnAndRow(8,$row,isset($tot_val['CON'])?$tot_val['MAP']:'0');
						$workSheet->setCellValueByColumnAndRow(9,$row,isset($tot_val['CTR'])?$tot_val['CTR']."%":'0.00%');
						$workSheet->setCellValueByColumnAndRow(10,$row,isset($tot_val['SPEND'])?"$".$tot_val['SPEND']:'$0.00');
						
						$ad_rep_row	=	$row+3;
						$camp_rep_row	=	$row+5;
						$workSheet->getStyle('G'.$ad_rep_row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('45588A');
						$workSheet->getStyle('K'.$ad_rep_row)->getFont()->setBold(true);
						$workSheet->SetCellValue('K'.$ad_rep_row," - ".$this->lang->line('label_advertiser_excel_not'));
						
						$workSheet->getStyle('J'.$camp_rep_row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6A9DEC');
						$workSheet->getStyle('K'.$camp_rep_row)->getFont()->setBold(true);
						$workSheet->SetCellValue('K'.$camp_rep_row," - ".$this->lang->line('label_campaign_banner_excel_not'));
						}
						//exit;
						
						
						
						

				//exit;
					//exit;	
				/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setPath('/opt/lampp/htdocs/dreamads_current/assets/images/logo2.png');
$objDrawing->setHeight(54);
$objDrawing->setCoordinates('B15');
$objDrawing->setOffsetX(110);
$objDrawing->setRotation(25);
$objDrawing->getShadow()->setVisible(true);
$objDrawing->getShadow()->setDirection(45);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/

						
						// Save Excel 2003 file
						//echo date('H:i:s') . " Write to Excel2003 format\n";
							
						//Excel Sheet File Name
						if($search_arr['search_type'] == 'specific_date')
						{
							$filename	=		'statistical_report_for_advertisers_'.$search_arr['from_date'].'_to_'.$search_arr['to_date'];
						}else{
							$filename	=		'statistical_report_for_advertisers_'.$search_arr['search_type'];
						}	
						/********* TO DOWNLOAD THE EXCEL FILE********/
						header('Content-Type: application/vnd.ms-excel');
						//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
						header('Cache-Control: max-age=0');
						//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
						$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
						//ob_end_clean();
						$objWriter->save('php://output');
						
					
					}
					
					else
					{
						redirect('admin/statistics_advertiser');
					}
			}

				function export_publishers_excel($data='')
				{
							$stat_data=$data['stat_data'];
						
							if(!empty($stat_data['stat_list']))
							{
								//Setting the Statistical  Array
								
								 $search_arr= $this->session->userdata('statistics_search_arr');
								
	
								//Set the Variables
								$pub_count	=	count($data['publisher_list']);
								
								// Create new PHPExcel object
								//echo date('H:i:s') . " Create new PHPExcel object\n";
								$objPHPExcel = new PHPExcel();
								
								$workSheet	=	 $objPHPExcel->getActiveSheet();
								// Set properties of the Excel
								//echo date('H:i:s') . " Set properties\n";
								$objPHPExcel->getProperties()->setCreator("Administrator");
								$objPHPExcel->getProperties()->setLastModifiedBy("Administrator");
								$objPHPExcel->getProperties()->setTitle("".$this->lang->line("label_publisher_excel_title")."");
								$objPHPExcel->getProperties()->setSubject("");
								
								//Default Style Settings For Excel Sheet
								$workSheet->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
								$workSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
			
								// Set column width
								
								//Set the dimensions for the Table Title Attributes
								$workSheet->getColumnDimension('B')->setAutoSize(true);
								$workSheet->getColumnDimension('C')->setAutoSize(true);
								$workSheet->getColumnDimension('D')->setWidth(20);
								$workSheet->getColumnDimension('E')->setWidth(20);
								$workSheet->getColumnDimension('F')->setWidth(20);
								$workSheet->getColumnDimension('G')->setWidth(20);
								$workSheet->getColumnDimension('H')->setWidth(20);
								$workSheet->getColumnDimension('I')->setWidth(20);
								$workSheet->getColumnDimension('J')->setWidth(20);
								$workSheet->getColumnDimension('K')->setWidth(20);
							
								//Merge Cells for Heading
								$workSheet->mergeCells('B3:H4');
								
								//Set the Row dimensions for Each Row 
								$workSheet->getRowDimension(6)->setRowHeight(20);
								
								/* Decalaration of Style in an Array */
								
								$workSheet->duplicateStyleArray(
								array(
								'font' => array(
								'bold'         => false,
								'italic'    	 => false,
								'size'        	 => 20,
								'color'		=>array(
													'rgb' => '333'
													)
								),
								'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
								'wrap'       => true
									)
								),
								'B3:C4'
								);
								
								//Set the Table Title Style
								$tabletitleArray	=array(
																		'font' => array(
																		'bold'         => false,
																		'italic'    	 => false,
																		'size'        	 => 14,
																		'color'		=>array(
																							'rgb' => 'ffffff'
																							)
																		),
																		'fill'=> array(
																						'type' => PHPExcel_Style_Fill::FILL_SOLID,
																						'rotation' => 90,
																						'startcolor' => array(
																							'rgb' => '45588A',
																						)
																					)
																			);
								//$workSheet->getStyle('B9:G9')->applyFromArray($tabletstylearray);
								
								
								$tableDataArray	=		array(
																		'alignment' => array(
																		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'       => true
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
													
							$dateArray	=	array(
															'font'=>array(
																		'bold'=>true
																	),
															'alignment' =>array(
															'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
															)	
															);				
	
							$childborderArray = array(
															'borders' => array(
															'vertical' => array(
															'style' => PHPExcel_Style_Border::BORDER_THIN,
															'color' => array('rgb' => '4E5A7A'),
																				),
															'bottom'=>array(
															'style' => PHPExcel_Style_Border::BORDER_THIN,
															'color' => array('rgb' => '4E5A7A'),
																			)
															)
													);
								
											
								//$workSheet->getStyle('A9:F9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
								
								// Add some data
								//echo date('H:i:s') . " Add some data\n";
								$objPHPExcel->setActiveSheetIndex(0);
								
								//Set the Title and Start Date ,End Date Details 
								$workSheet->SetCellValue('B3', ''.$this->lang->line("label_publisher_sheet_name").'');
								$workSheet->SetCellValue('B6', ''.$this->lang->line("label_start_date").':');
								$workSheet->getStyle('B6')->applyFromArray($dateArray);
								
								$workSheet->SetCellValue('J6',''.$this->lang->line("label_to_date").':');
								$workSheet->getStyle('J6')->applyFromArray($dateArray);
								
								$workSheet->SetCellValue('C6', date("m/d/Y",strtotime($search_arr['from_date'])));
								$workSheet->SetCellValue('K6',date("m/d/Y",strtotime($search_arr['to_date'])));
								
								
								$workSheet->SetCellValue('B9',"".$this->lang->line('lang_statistics_publisher_site')."");
								$workSheet->SetCellValue('C9',"".$this->lang->line('lang_statistics_publisher_impression')."");
								$workSheet->SetCellValue('D9',"".$this->lang->line('lang_statistics_publisher_clicks')."");
								$workSheet->SetCellValue('E9',"".$this->lang->line('lang_statistics_publisher_conversions')."");
								$workSheet->SetCellValue('F9',"".$this->lang->line('lang_statistics_publisher_call')."");
								$workSheet->SetCellValue('G9',"".$this->lang->line('lang_statistics_publisher_web')."");
								$workSheet->SetCellValue('H9',"".$this->lang->line('lang_statistics_publisher_map')."");
								$workSheet->SetCellValue('I9',"".$this->lang->line('lang_statistics_publisher_ctr')."");
								$workSheet->SetCellValue('J9',"".$this->lang->line('lang_statistics_publisher_revenue')."");
								$workSheet->SetCellValue('K9',"".$this->lang->line('lang_statistics_publisher_share')."");
								
								
								// Rename sheet
								//echo date('H:i:s') . " Rename sheet\n";
								$workSheet->setTitle(''.$this->lang->line("label_publisher_excel_title").'');
							
								
								//print_r($data['advertiser_list']);
								if($pub_count > 0){
									$col=0;
									$row=10;
									$row_start	=	10;
									$camp_title_style = array();
									$camp_style	=	array();
									
									$stat_list		=	  $stat_data['stat_list'];
									
									$tot_val		=	  $stat_data['tot_val'];
									
									//$campaigns_list	=	$data['campaigns_list'];
									//$reports	=		$data['reports'];
									
									$i=0;
									foreach($data['publisher_list'] as $adv)
									{
												// Publisher List	
												$workSheet->getStyle('B'.$row.':G'.$row)->getFont()->setBold(true);
												$workSheet->setCellValueByColumnAndRow(1,$row,view_text($adv->websitename));
												$workSheet->setCellValueByColumnAndRow(2,$row,isset($stat_list[$adv->accountid])?$stat_list[$adv->accountid]['IMP']:'0');
												$workSheet->setCellValueByColumnAndRow(3,$row,isset($stat_list[$adv->accountid])?$stat_list[$adv->accountid]['CLK']:'0');
												$workSheet->setCellValueByColumnAndRow(4,$row,isset($stat_list[$adv->accountid])?$stat_list[$adv->accountid]['CON']:'0');
												$workSheet->setCellValueByColumnAndRow(5,$row,isset($stat_list[$adv->accountid])?$stat_list[$adv->accountid]['CALL']:'0');
												$workSheet->setCellValueByColumnAndRow(6,$row,isset($stat_list[$adv->accountid])?$stat_list[$adv->accountid]['WEB']:'0');
												$workSheet->setCellValueByColumnAndRow(7,$row,isset($stat_list[$adv->accountid])?$stat_list[$adv->accountid]['MAP']:'0');
												$workSheet->setCellValueByColumnAndRow(8,$row,isset($stat_list[$adv->account_id])?$stat_list[$adv->account_id]['CTR']."%":"0.00%");
												$workSheet->setCellValueByColumnAndRow(9,$row,isset($stat_list[$adv->account_id])?"$".$stat_list[$adv->account_id]['SPEND']:'$0.00');
												$workSheet->setCellValueByColumnAndRow(10,$row,isset($stat_list[$adv->account_id])?"$".$stat_list[$adv->account_id]['PUBSHARE']:'$0.00');							
												$row++;	
														
												if(array_key_exists($adv->account_id,$stat_list))
												{
													$zones_list =$this->mod_publisher->get_publishers($adv->account_id,$search_arr);
													$workSheet->getStyle('B'.$row.':H'.$row)->getFont()->setItalic(true);
													
													$workSheet->SetCellValue('B'.$row,"".$this->lang->line('lang_statistics_publisher_zone_name')."");
													$workSheet->SetCellValue('C'.$row,"".$this->lang->line('lang_statistics_publisher_impression')."");
													$workSheet->SetCellValue('D'.$row,"".$this->lang->line('lang_statistics_publisher_clicks')."");
													$workSheet->SetCellValue('E'.$row,"".$this->lang->line('lang_statistics_publisher_conversions')."");
													$workSheet->SetCellValue('F'.$row,"".$this->lang->line('lang_statistics_publisher_call')."");
													$workSheet->SetCellValue('F'.$row,"".$this->lang->line('lang_statistics_publisher_web')."");
													$workSheet->SetCellValue('F'.$row,"".$this->lang->line('lang_statistics_publisher_map')."");
													$workSheet->SetCellValue('F'.$row,"".$this->lang->line('lang_statistics_publisher_ctr')."");
													$workSheet->SetCellValue('G'.$row,"".$this->lang->line('lang_statistics_publisher_revenue')."");
													$workSheet->SetCellValue('H'.$row,"".$this->lang->line('lang_statistics_publisher_share')."");
													
														array_push($camp_title_style,$row);
														$row++;
													foreach($zones_list as $data){
														//Zones List For Each Publisher
														array_push($camp_style,$row);
														$workSheet->setCellValueByColumnAndRow(1,$row,view_text($data['zonename']));
														$workSheet->setCellValueByColumnAndRow(2,$row,$data['IMP']);
														$workSheet->setCellValueByColumnAndRow(3,$row,$data['CLK']);
														$workSheet->setCellValueByColumnAndRow(4,$row,$data['CON']);
														$workSheet->setCellValueByColumnAndRow(5,$row,$data['CALL']);
														$workSheet->setCellValueByColumnAndRow(6,$row,$data['WEB']);
														$workSheet->setCellValueByColumnAndRow(7,$row,$data['MAP']);
														$workSheet->setCellValueByColumnAndRow(8,$row,$data['CTR'].'%');
														$workSheet->setCellValueByColumnAndRow(9,$row,'$'.$data['SPEND']);
														$workSheet->setCellValueByColumnAndRow(10,$row,'$'.$data['PUBSHARE']);
														$row++;
												}
											}
										
									}
						$total_record	=	$row-$row_start;
							//Apply the Style Attributes to the Excel Sheet
							for($i=0,$row_data=9;$i<=$total_record;$i++,$row_data++)
								{
										$workSheet->getRowDimension($row_data)->setRowHeight(15);
										//$workSheet->getStyle('B'.$row_data)->getFont()->setBold(false);
										$workSheet->getStyle('B'.$row_data.':H'.$row_data)->applyFromArray($tableDataArray);
										$workSheet->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
										$workSheet->getStyle('J'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
										$workSheet->getStyle('K'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
										
										
										// To set the Style for Parent and Child Relationships
										switch($row_data)
											{
													//Set the title Row Style
													case '9':
															$workSheet->getStyle('B'.$row_data.':K'.$row_data)->applyFromArray($tabletitleArray);
															$workSheet->getRowDimension($row_data)->setRowHeight(20);
															break;
													//Set the Campaign Title Style
													case  in_array($row_data,$camp_title_style):
															$workSheet->getStyle('B'.$row_data.':K'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	->getStartColor()->setRGB('6A9DEC');
															$workSheet->getStyle('B'.$row_data.':K'.$row_data)->applyFromArray($childborderArray);
															$workSheet->getStyle('J'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
															$workSheet->getStyle('K'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
															break;
													//Set the Campaign inner child Style		
													case  in_array($row_data,$camp_style):
															$workSheet->getStyle('B'.$row_data.':K'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C9DAF8');
															$workSheet->getStyle('B'.$row_data.':K'.$row_data)->applyFromArray($childborderArray);
															$workSheet->getStyle('J'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
															$workSheet->getStyle('K'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
															break;
													//Set the even rows style
													case  $row_data%2 !=0:
															$workSheet->getStyle('B'.$row_data.':K'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	->getStartColor()->setRGB('EAEAEA');
															break;
											}
									if($i ==$total_record)
										{
												$workSheet->getStyle('B9:K'.$row_data)->applyFromArray($borderArray);
										}
								}
							
	
							
							//exit;
							
							$workSheet->SetCellValue('B'.$row,"Total");
							
							$workSheet->getStyle('B'.$row.':K'.$row)->applyFromArray($tabletitleArray);
							$workSheet->getRowDimension($row)->setRowHeight(20);
							$workSheet->getStyle('B'.$row.':K'.$row)->applyFromArray($tableDataArray);
							$workSheet->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$workSheet->getStyle('J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
							$workSheet->getStyle('K'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
							
							$workSheet->setCellValueByColumnAndRow(2,$row,isset($tot_val['IMP'])?$tot_val['IMP']:'0');
							$workSheet->setCellValueByColumnAndRow(3,$row,isset($tot_val['CLK'])?$tot_val['CLK']:'0');
							$workSheet->setCellValueByColumnAndRow(4,$row,isset($tot_val['CON'])?$tot_val['CON']:'0');
							$workSheet->setCellValueByColumnAndRow(5,$row,isset($tot_val['CALL'])?$tot_val['CALL']:'0');
							$workSheet->setCellValueByColumnAndRow(6,$row,isset($tot_val['WEB'])?$tot_val['WEB']:'0');
							$workSheet->setCellValueByColumnAndRow(7,$row,isset($tot_val['MAP'])?$tot_val['MAP']:'0');
							$workSheet->setCellValueByColumnAndRow(8,$row,isset($tot_val['CTR'])?$tot_val['CTR']."%":'0.00%');
							$workSheet->setCellValueByColumnAndRow(9,$row,isset($tot_val['SPEND'])?"$".$tot_val['SPEND']:'$0.00');
							$workSheet->setCellValueByColumnAndRow(10,$row,isset($tot_val['SPEND'])?"$".$tot_val['PUBSHARE']:'$0.00');
							
							$ad_rep_row	=	$row+3;
							$camp_rep_row	=	$row+5;
							$workSheet->getStyle('J'.$ad_rep_row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('45588A');
							$workSheet->getStyle('K'.$ad_rep_row)->getFont()->setBold(true);
							$workSheet->SetCellValue('K'.$ad_rep_row," - ".$this->lang->line('label_publisher_excel_not'));
							
							$workSheet->getStyle('J'.$camp_rep_row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6A9DEC');
							$workSheet->getStyle('K'.$camp_rep_row)->getFont()->setBold(true);
							$workSheet->SetCellValue('K'.$camp_rep_row," - ".$this->lang->line('label_zones_excel_not'));
							}
		
							//Excel Sheet File Name
							if($search_arr['search_type'] == 'specific_date')
							{
								$filename	=		'statistical_report_for_publishers_'.$search_arr['from_date'].'_to_'.$search_arr['to_date'];
							}else{
								$filename	=		'statistical_report_for_publishers_'.$search_arr['search_type'];
							}	
							/********* TO DOWNLOAD THE EXCEL FILE********/
							header('Content-Type: application/vnd.ms-excel');
							//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
							header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
							header('Cache-Control: max-age=0');
							//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
							$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
							//ob_end_clean();
							$objWriter->save('php://output');
							
						
						}
						else
						{
							redirect('admin/statistics_publisher');
						}
				
				}
			
				function export_date_wise($data)
				{		
							
							
							$stat_data=$data['stat_data'];
							
							if(!empty($stat_data['stat_list']))
							{
								
								$total_data=$stat_data['tot_val'];
								$search=$data['search_date'];

								$from_date=(date("d-m-Y",strtotime($search['from_date'])));
								$to_date=(date("d-m-Y",strtotime($search['to_date'])));
								$filename=$this->lang->line('lang_statistics_title_advertiser').$from_date."--".$to_date.$this->lang->line('lang_statistics_title_advertiser_date_wise');
								
								$total_data=$stat_data['tot_val'];
								
								
								$date_count	= count($stat_data['stat_list']);
								
								
								// Create new PHPExcel object
								//echo date('H:i:s') . " Create new PHPExcel object\n";
								$objPHPExcel = new PHPExcel();
								$workSheet	=	 $objPHPExcel->getActiveSheet();
								// Set properties
								//echo date('H:i:s') . " Set properties\n";
								$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
								$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_advertiser_hour_wise'));
								$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_advertiser_hour_wise'));
								
								//Set default style
								$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
								$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
			
								
								
								// Set column width
								
								$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
								$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
							
								//Merge Cells for Heading
								$objPHPExcel->getActiveSheet()->mergeCells('B3:G4');
								$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
								$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
								
					
								//Leading heading style
								$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																					array(
																						'font'	=> array(
																						'bold'	=> false,
																						'italic'=> false,
																						'size'	=> 20,
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
																			'bold'		=> false,
																			'italic'	=> false,
																			'size'		=> 14,
																			'color'		=>array('rgb' => 'ffffff')
																				),
															'fill'		=> array(
																			'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																			'rotation'		=> 90,
																			'startcolor'	=> array('rgb' => '4E5A7A')
																				)
															);
								//Alignment for taable heading							
								$tableDataArray	= array(
														'alignment'	=> array(
																			'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																			'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																			'wrap'			=> true
																			)				
								
														);
								
								//Style for date					
								$dateArray	= array(
													'font'=>array('bold'=>true),
													'alignment' =>array(
																		'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																		)	
													);			
	
								//Set Row Height
								for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
								{
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':G'.$row_data)->applyFromArray($tableDataArray);
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
										$objPHPExcel->getActiveSheet()->getStyle('G'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
										
										if($row_data%2 !=0 && $row_data!=9)
										{
											$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':G'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	->getStartColor()->setRGB('EAEAEA');
										}else if($row_data ==9){
											$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':G'.$row_data)->applyFromArray($tabletitleArray);
											$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
										}
										/*else if($i ==$date_count+1)
										{
												$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
										}*/
								}
								
															
								
								
								
								//set default heading and location
								$objPHPExcel->setActiveSheetIndex(0);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_advertiser_date_wise'));
								
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B6', $this->lang->line('lang_statistics_advertiser_start_date'));
								$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
								
								$adv_name=$data['stat_adv_det'];
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B7', $this->lang->line('lang_statistics_advertiser'));
								$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($dateArray);
								$objPHPExcel->getActiveSheet()->SetCellValue('C7', $adv_name[0]->advertiser_name);
								
								if(isset($data['stat_camp_data']))
								{
									$campaign=$data['stat_camp_data'];
									$objPHPExcel->getActiveSheet()->SetCellValue('D7', $this->lang->line('lang_statistics_advertiser_campaign'));
									$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($dateArray);
									$objPHPExcel->getActiveSheet()->SetCellValue('E7', $campaign->campaignname);
								}
								
								if(isset($data['stat_banner_data']))
								{
									$banner=$data['stat_banner_data'];
									$objPHPExcel->getActiveSheet()->SetCellValue('F7', $this->lang->line('lang_statistics_advertiser_banner'));
									$objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray($dateArray);
									$objPHPExcel->getActiveSheet()->SetCellValue('G7', $banner->description);	
								}
								
								
								$objPHPExcel->getActiveSheet()->SetCellValue('F6',$this->lang->line('lang_statistics_advertiser_end_date'));
								$objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray($dateArray);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('C6', $from_date);
								$objPHPExcel->getActiveSheet()->SetCellValue('G6', $to_date);
								
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_advertiser_date'));
								$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_advertiser_impression'));
								$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_advertiser_clicks'));
								$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_advertiser_conversions'));
								$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_advertiser_ctr'));
								$objPHPExcel->getActiveSheet()->SetCellValue('G9',$this->lang->line('lang_statistics_advertiser_spend'));
								
								// Rename sheet
								//echo date('H:i:s') . " Rename sheet\n";
								$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_advertiser_date_wise'));
							
								
								
								
								
								//print_r($data['advertiser_list']);
								if($date_count > 0)
								{
								$col=0;
								$row=10;
							
									foreach($stat_data['stat_list'] as $date_key=>$objStat)
									{
													
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,$date_key);
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($objStat['IMP'])?$objStat['IMP']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($objStat['CLK'])?$objStat['CLK']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($objStat['CON'])?$objStat['CON']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($objStat['CTR'])?$objStat['CTR']."%":"0.00%");
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,isset($objStat['SPEND'])?"$".$objStat['SPEND']:'$0.00');
									$row++;				
									}
								}
								
								if($row >0)
								{
									
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':G'.$row)->applyFromArray($tabletitleArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
									$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
									
									$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_advertiser_total'));
									$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
									$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
									$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CON'])?$total_data['CON']:'0'));
									$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CTR'])?number_format($total_data['CTR'],2,'.',',').'%':'0.00%'));
									$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,(isset($total_data['SPEND'])?'$'.number_format($total_data['SPEND'],2,'.',','):'$0.00'));	
									
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
								else
								{
									redirect('admin/statistics_advertiser');
								}
					
					}//end of export_date_data
				
				function export_hour_wise($data)
				{		
						
						
						$stat_data=$data['stat_data'];
						
						if(!empty($stat_data['stat_list']))
						{
							
							$date_count	= count($stat_data['stat_list']);
							$hour_wise=$stat_data['stat_list'];
							$total_data=$stat_data['tot_val'];
							$search=$data['search_hour'];
							//$from_date=(date("d-m-Y",strtotime($search['from_date'])));
							//$to_date=(date("d-m-Y",strtotime($search['to_date'])));
							$sel_date = (date("d-m-Y",strtotime($search['sel_date'])));
							$filename=$this->lang->line('lang_statistics_title_advertiser').$sel_date."_".$this->lang->line('lang_statistics_title_advertiser_hour_wise'); 
							
							
							// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_advertiser_hour_wise'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_advertiser_hour_wise'));
							
							//Set default style
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
							//$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
						
							//Merge Cells for Heading
							$objPHPExcel->getActiveSheet()->mergeCells('B3:F4');
							$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
							$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 20,
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
																		'bold'		=> false,
																		'italic'	=> false,
																		'size'		=> 14,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for taable heading							
							$tableDataArray	= array(
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);			

							//Set Row Height
							for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
							{
									$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tableDataArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									//$objPHPExcel->getActiveSheet()->getStyle('G'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
									
									if($row_data%2 !=0 && $row_data!=9)
									{
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
									}else if($row_data ==9){
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tabletitleArray);
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
									}
									/*else if($i ==$date_count+1)
									{
											$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
									}*/
							}
							
														
							
							
							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_advertiser_hour_wise'));
							$objPHPExcel->getActiveSheet()->SetCellValue('B6',$this->lang->line('lang_statistics_advertiser_date'));
							$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
							
							$adv_name=$data['stat_adv_det'];
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B7', $this->lang->line('lang_statistics_advertiser'));
							$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($dateArray);
							$objPHPExcel->getActiveSheet()->SetCellValue('C7', $adv_name[0]->advertiser_name);
							
							if(isset($data['stat_camp_data']))
							{
								$campaign=$data['stat_camp_data'];
								$objPHPExcel->getActiveSheet()->SetCellValue('D7', $this->lang->line('lang_statistics_advertiser_campaign'));
								$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($dateArray);
								$objPHPExcel->getActiveSheet()->SetCellValue('E7', $campaign->campaignname);
							}
							
							if(isset($data['stat_banner_data']))
							{
								$banner=$data['stat_banner_data'];
								$objPHPExcel->getActiveSheet()->SetCellValue('E7', $this->lang->line('lang_statistics_advertiser_banner'));
								$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($dateArray);
								$objPHPExcel->getActiveSheet()->SetCellValue('F7', $banner->description);	
							}
							
							$objPHPExcel->getActiveSheet()->SetCellValue('C6', $sel_date);
							
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_advertiser_hour'));
							$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_advertiser_impression'));
							$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_advertiser_clicks'));
							$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_advertiser_conversions'));
							$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_advertiser_ctr'));
							//$objPHPExcel->getActiveSheet()->SetCellValue('G9',$this->lang->line('lang_statistics_advertiser_spend'));
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_advertiser_hour_wise'));
						
							
							
							
							
							//print_r($data['advertiser_list']);
    						if($date_count > 0)
							{
							$col=0;
							$row=10;
						
								foreach($hour_wise as $time_key=>$hour)
								{
									list($time) = explode(':',$time_key); 
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,date('H:i',mktime($time,0)).'-'.date('H:i',mktime($time,59)));
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($hour['IMP'])?$hour['IMP']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($hour['CLK'])?$hour['CLK']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($hour['CON'])?$hour['CON']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($hour['CTR'])?$hour['CTR']."%":"0.00%");
									//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,isset($hour['SPEND'])?"$".$hour['SPEND']:'$0.00');
									$row++;				
								}
							}
							
							if($row >0)
							{
								
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':F'.$row)->applyFromArray($tabletitleArray);
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								//$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_advertiser_total'));
								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CON'])?$total_data['CON']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CTR'])?number_format($total_data['CTR'],2,'.',',').'%':'0.00%'));
								//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,(isset($total_data['SPEND'])?'$'.number_format($total_data['SPEND'],2,'.',','):'$0.00'));	
								
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
							
							else
							{
								redirect('admin/statistics_advertiser');
							}
				
				}//end of export_hour_wise

				function export_publisher_date_wise($data)
				{
						$stat_data=$data['stat_data'];
						
						if(!empty($stat_data['stat_list']))
						{
							$adv_name=$data['stat_adv_det'];
							$total_data=$stat_data['tot_val'];
							$search=$data['search_date'];
							
							$from_date=(date("d-m-Y",strtotime($search['from_date'])));
							$to_date=(date("d-m-Y",strtotime($search['to_date'])));$from_date=(date("d-m-Y",strtotime($search['from_date'])));
							$to_date=(date("d-m-Y",strtotime($search['to_date'])));
							$filename=$this->lang->line('lang_statistics_title_publisher').$from_date."--".$to_date.$this->lang->line('lang_statistics_title_publisher_date_wise');
							
							$total_data=$stat_data['tot_val'];
							
							
							$date_count	= count($stat_data['stat_list']);
							
							
							// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_publisher_hour_wise'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_publisher_hour_wise'));
							
							//Set default style
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
						
							//Merge Cells for Heading
							$objPHPExcel->getActiveSheet()->mergeCells('B3:H4');
							$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
							$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 20,
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
																		'bold'		=> false,
																		'italic'	=> false,
																		'size'		=> 14,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for taable heading							
							$tableDataArray	= array(
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);			

							//Set Row Height
							for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
							{
									$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':H'.$row_data)->applyFromArray($tableDataArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$objPHPExcel->getActiveSheet()->getStyle('G'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									$objPHPExcel->getActiveSheet()->getStyle('H'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
									
									if($row_data%2 !=0 && $row_data!=9)
									{
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':H'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
									}else if($row_data ==9){
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':H'.$row_data)->applyFromArray($tabletitleArray);
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
									}
									/*else if($i ==$date_count+1)
									{
											$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
									}*/
							}
							
														
							
							
							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_publisher_date_wise'));
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B6', $this->lang->line('lang_statistics_publisher_start_date'));
							$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
							
							$adv_name=$data['stat_adv_det'];
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B7', $this->lang->line('lang_statistics_publisher'));
							$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($dateArray);
							$objPHPExcel->getActiveSheet()->SetCellValue('C7', $adv_name);
							
							
							if(isset($data['zone_list']))
							{
								$zone=$data['zone_list'];
								$objPHPExcel->getActiveSheet()->SetCellValue('G7', $this->lang->line('lang_statistics_publisher_zone_name'));
								$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($dateArray);
								$objPHPExcel->getActiveSheet()->SetCellValue('H7', $zone);	
							}
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('G6',$this->lang->line('lang_statistics_publisher_end_date'));
							$objPHPExcel->getActiveSheet()->getStyle('G6')->applyFromArray($dateArray);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('C6', $from_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('H6', $to_date);
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_publisher_date'));
							$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_publisher_impression'));
							$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_publisher_clicks'));
							$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_publisher_conversions'));
							$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_publisher_ctr'));
							$objPHPExcel->getActiveSheet()->SetCellValue('G9',$this->lang->line('lang_statistics_publisher_revenue'));
							$objPHPExcel->getActiveSheet()->SetCellValue('H9',$this->lang->line('lang_statistics_publisher_share'));
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_publisher_date_wise'));
						
							
							
							
							
							//print_r($data['advertiser_list']);
							if($date_count > 0)
							{
							$col=0;
							$row=10;
						
								foreach($stat_data['stat_list'] as $date_key=>$objStat)
								{
												
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,$date_key);
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($objStat['IMP'])?$objStat['IMP']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($objStat['CLK'])?$objStat['CLK']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($objStat['CON'])?$objStat['CON']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($objStat['CTR'])?$objStat['CTR']."%":"0.00%");
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,isset($objStat['SPEND'])?"$".$objStat['SPEND']:'$0.00');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,isset($objStat['SPEND'])?"$".$objStat['PUBSHARE']:'$0.00');
								$row++;				
								}
							}
							
							if($row >0)
							{
								
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':H'.$row)->applyFromArray($tabletitleArray);
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_publisher_total'));
								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CON'])?$total_data['CON']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CTR'])?number_format($total_data['CTR'],2,'.',',').'%':'0.00%'));
								$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,(isset($total_data['SPEND'])?'$'.number_format($total_data['SPEND'],2,'.',','):'$0.00'));	
								$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row,(isset($total_data['PUBSHARE'])?'$'.number_format($total_data['PUBSHARE'],2,'.',','):'$0.00'));	
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
							
							else
							{
								redirect('admin/statistics_publisher');
							}
				
				}//end of export_publisher_date_data
				
				function export_publisher_hour_wise($data)
				{		
						$stat_data=$data['stat_data'];
						
						if(!empty($stat_data['stat_list']))
						{
							$date_count	= count($stat_data['stat_list']);
							$hour_wise=$stat_data['stat_list'];
							$total_data=$stat_data['tot_val'];
							$search=$data['search_hour'];
							/*$from_date("d-m-Y",strtotime($search['from_date'])));
							$to_date=(date("d-m-Y",strtotime($search['to_date'])));*/
							$sel_date = (date("d-m-Y",strtotime($search['sel_date'])));
							$filename=$this->lang->line('lang_statistics_title_publisher').$sel_date."_".$this->lang->line('lang_statistics_title_publisher_hour_wise'); 
							
							
							// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_publisher_hour_wise'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_publisher_hour_wise'));
							
							//Set default style
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
						
							//Merge Cells for Heading
							$objPHPExcel->getActiveSheet()->mergeCells('B3:G4');
							$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
							$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 20,
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
																		'bold'		=> false,
																		'italic'	=> false,
																		'size'		=> 14,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for taable heading							
							$tableDataArray	= array(
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);			

							//Set Row Height
							for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
							{
									$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tableDataArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
									
									if($row_data%2 !=0 && $row_data!=9)
									{
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
									}else if($row_data ==9){
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tabletitleArray);
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
									}
									/*else if($i ==$date_count+1)
									{
											$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
									}*/
							}
							
														
							
							
							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_publisher_hour_wise'));
							$objPHPExcel->getActiveSheet()->SetCellValue('B6',$this->lang->line('notification_date'));
							$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
							
							$adv_name=$data['stat_adv_det'];
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B7', $this->lang->line('lang_statistics_publisher'));
							$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($dateArray);
							$objPHPExcel->getActiveSheet()->SetCellValue('C7', $adv_name);
							
							if(isset($data['zone_list']))
							{
								$zone=$data['zone_list'];
								$objPHPExcel->getActiveSheet()->SetCellValue('E7', $this->lang->line('lang_statistics_publisher_zone_name'));
								$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($dateArray);
								$objPHPExcel->getActiveSheet()->SetCellValue('F7', $zone);	
							}
							
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('C6', $sel_date);
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_publisher_hour'));
							$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_publisher_impression'));
							$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_publisher_clicks'));
							$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_publisher_conversions'));
							$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_publisher_ctr'));
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_publisher_hour_wise'));
						
							
							
							
							
							//print_r($data['advertiser_list']);
    						if($date_count > 0)
							{
							$col=0;
							$row=10;
						
								foreach($hour_wise as $time_key=>$hour)
								{
												
								list($time) = explode(':',$time_key); 
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,date('H:i',mktime($time,0)).'-'.date('H:i',mktime($time,59)));
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($hour['IMP'])?$hour['IMP']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($hour['CLK'])?$hour['CLK']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($hour['CON'])?$hour['CON']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($hour['CTR'])?$hour['CTR']."%":"0.00%");
								$row++;				
								}
							}
							
							if($row >0)
							{
								
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':F'.$row)->applyFromArray($tabletitleArray);
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_publisher_total'));
								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CON'])?$total_data['CON']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CTR'])?number_format($total_data['CTR'],2,'.',',').'%':'0.00%'));	
								
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
							
							else
							{
								redirect('admin/statistics_publisher');
							}
				
				}//end of export_publisher_hour_wise

				function export_global_data($data)
				{		
						
						$stat_data=$data['stat_data'];
						
						if(!empty($stat_data['stat_list']))
						{
							$total_data=$stat_data['tot_val'];
							$search=$data['search_global'];
							$from_date=(date("d-m-Y",strtotime($search['from_date'])));
							$to_date=(date("d-m-Y",strtotime($search['to_date'])));
							$filename=$this->lang->line('lang_statistics_title_global').$from_date."--".$to_date.$this->lang->line('lang_statistics_title_global_date_wise');
							
							$total_data=$stat_data['tot_val'];
							
							
							$date_count	= count($stat_data['stat_list']);
							
							
							// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_global_history'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_global_history'));
							
							//Set default style
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
							$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
							$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
							$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
							$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
							$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
						
							//Merge Cells for Heading
							$objPHPExcel->getActiveSheet()->mergeCells('B3:G4');
							$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
							$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 20,
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
																		'bold'		=> false,
																		'italic'	=> false,
																		'size'		=> 14,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for taable heading							
							$tableDataArray	= array(
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);			

							//Set Row Height
							for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
							{
									$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':J'.$row_data)->applyFromArray($tableDataArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$objPHPExcel->getActiveSheet()->getStyle('J'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
									
									if($row_data%2 !=0 && $row_data!=9)
									{
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':J'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
									}else if($row_data ==9){
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':J'.$row_data)->applyFromArray($tabletitleArray);
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
									}
									/*else if($i ==$date_count+1)
									{
											$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
									}*/
							}
							
														
							
							
							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_global_history'));
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B6', $this->lang->line('lang_statistics_global_from_start_date'));
							$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
							
							
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('I6',$this->lang->line('lang_statistics_global_end_date'));
							$objPHPExcel->getActiveSheet()->getStyle('I6')->applyFromArray($dateArray);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('C6', $from_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('J6', $to_date);
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_global_date'));
							$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_global_impression'));
							$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_global_clicks'));
							$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_global_conversions'));
							$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_global_call'));
							$objPHPExcel->getActiveSheet()->SetCellValue('G9',$this->lang->line('lang_statistics_global_web'));
							$objPHPExcel->getActiveSheet()->SetCellValue('H9',$this->lang->line('lang_statistics_global_map'));
							$objPHPExcel->getActiveSheet()->SetCellValue('I9',$this->lang->line('lang_statistics_global_ctr'));
							$objPHPExcel->getActiveSheet()->SetCellValue('J9',$this->lang->line('lang_statistics_publisher_revenue'));
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_global_history'));
						
							
							
							
							
							//print_r($data['advertiser_list']);
    						if($date_count > 0)
							{
							$col=0;
							$row=10;
						
								foreach($stat_data['stat_list'] as $date_key=>$objStat)
								{
												
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,date('d-m-Y',strtotime($date_key)));
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($objStat['IMP'])?$objStat['IMP']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($objStat['CLK'])?$objStat['CLK']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($objStat['CON'])?$objStat['CON']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($objStat['CALL'])?$objStat['CALL']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,isset($objStat['WEB'])?$objStat['WEB']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,isset($objStat['MAP'])?$objStat['MAP']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,isset($objStat['CTR'])?$objStat['CTR']."%":"0.00%");
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,isset($objStat['SPEND'])?"$".$objStat['SPEND']:'$0.00');
								$row++;				
								}
							}
							
							if($row >0)
							{
								
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':J'.$row)->applyFromArray($tabletitleArray);
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_advertiser_total'));
								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CON'])?$total_data['CON']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CALL'])?$total_data['CALL']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,(isset($total_data['WEB'])?$total_data['WEB']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('H'.$row,(isset($total_data['MAP'])?$total_data['MAP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('I'.$row,(isset($total_data['CTR'])?number_format($total_data['CTR'],2,'.',',').'%':'0.00%'));
								$objPHPExcel->getActiveSheet()->SetCellValue('J'.$row,(isset($total_data['SPEND'])?'$'.number_format($total_data['SPEND'],2,'.',','):'$0.00'));	
								
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
							
							else
							{
								redirect('admin/statistics_global');
							}
				
				}//end of export_date_country_wise
				
				function export_date_country_wise($data)
				{		
						
						$stat_data=$data['stat_data'];
						
						if(!empty($stat_data['stat_list']))
						{
							$total_data=$stat_data['tot_val'];
							$search=$data['search_date_country'];
							$from_date=(date("d-m-Y",strtotime($search['from_date'])));
							$to_date=(date("d-m-Y",strtotime($search['to_date'])));
							$filename=$this->lang->line('lang_statistics_title_global').$from_date."--".$to_date.$this->lang->line('lang_statistics_title_global_date_wise_with_country_name');
							
							$total_data=$stat_data['tot_val'];
							
							
							$date_count	= count($stat_data['stat_list']);
							
							
							// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_global_history_on_date_wise_country_name'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_global_history_on_date_wise_country_name'));
							
							//Set default style
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
						
							//Merge Cells for Heading
							$objPHPExcel->getActiveSheet()->mergeCells('B3:F4');
							$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
							$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 20,
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
																		'bold'		=> false,
																		'italic'	=> false,
																		'size'		=> 14,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for taable heading							
							$tableDataArray	= array(
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);			

							//Set Row Height
							for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
							{
									$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tableDataArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$objPHPExcel->getActiveSheet()->getStyle('F'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
									
									if($row_data%2 !=0 && $row_data!=9)
									{
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
									}else if($row_data ==9){
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tabletitleArray);
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
									}
									/*else if($i ==$date_count+1)
									{
											$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
									}*/
							}
							
														
							
							
							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_global_history_on_date_wise_country_name'));
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B6', $this->lang->line('lang_statistics_global_from_start_date'));
							$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
							
							
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('E6',$this->lang->line('lang_statistics_global_end_date'));
							$objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray($dateArray);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('C6', $from_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('F6', $to_date);
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_global_date'));
							$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_global_country'));
							$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_global_impression'));
							$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_global_clicks'));
							/*$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_global_conversions'));*/
							$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_global_ctr'));
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_global_history_in_date_wise_country_name'));
						
							
							

							
							
							//print_r($data['advertiser_list']);
    						if($date_count > 0)
							{
							$col=0;
							$row=10;
						
								foreach($stat_data['stat_list'] as $objStat)
								{
												
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,$objStat['date']);
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($objStat['COUNTRY'])?$objStat['COUNTRY']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($objStat['IMP'])?$objStat['IMP']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($objStat['CLK'])?$objStat['CLK']:'0');
								/*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($objStat['CON'])?$objStat['CON']."%":"0.00%");*/
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($objStat['CTR'])?"$".$objStat['CTR']:'$0.00');
								$row++;				
								}
							}
							
							if($row >0)
							{
								
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':G'.$row)->applyFromArray($tabletitleArray);
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								/*$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);*/
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_advertiser_total'));
								/*$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['COUNTRY'])?$total_data['COUNTRY']:'0'));*/
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
								/*$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CON'])?number_format($total_data['CON'],2,'.',',').'%':'0.00%'));*/
								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CTR'])?'$'.number_format($total_data['CTR'],2,'.',','):'$0.00'));	
								
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
							
							else
							{
								redirect('admin/statistics_global/view_date_country_wise');
							}
				
				}//end of export_global_data
				
				function export_country_wise($data)
				{		
						$stat_data=$data['stat_data'];
						
						if(!empty($stat_data['stat_list']))
						{
							$total_data=$stat_data['tot_val'];
							$search=$data['search_country'];
							$from_date=(date("d-m-Y",strtotime($search['from_date'])));
							$to_date=(date("d-m-Y",strtotime($search['to_date'])));
							$filename=$this->lang->line('lang_statistics_title_global').$from_date."--".$to_date.$this->lang->line('lang_statistics_title_global_country_wise');
							
							$total_data=$stat_data['tot_val'];
							$date_count	= count($stat_data['stat_list']);
							
							
							// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_global_history_on_country_wise'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_global_history_on_country_wise'));
							
							//Set default style
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
						
							//Merge Cells for Heading
							$objPHPExcel->getActiveSheet()->mergeCells('B3:E4');
							$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
							$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 20,
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
																		'bold'		=> false,
																		'italic'	=> false,
																		'size'		=> 14,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for taable heading							
							$tableDataArray	= array(
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);			

							//Set Row Height
							for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
							{
									$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':E'.$row_data)->applyFromArray($tableDataArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									$objPHPExcel->getActiveSheet()->getStyle('E'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
									
									if($row_data%2 !=0 && $row_data!=9)
									{
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':E'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
									}else if($row_data ==9){
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':E'.$row_data)->applyFromArray($tabletitleArray);
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
									}
									/*else if($i ==$date_count+1)
									{
											$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
									}*/
							}
							
														
							
							
							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_global_history_on_country_wise'));
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B6', $this->lang->line('lang_statistics_global_from_start_date'));
							$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
							
							
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('D6',$this->lang->line('lang_statistics_global_end_date'));
							$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($dateArray);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('C6', $from_date);
							$objPHPExcel->getActiveSheet()->SetCellValue('E6', $to_date);
							
							
							/*$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_global_date'));*/
							$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_global_country'));
							$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_global_impression'));
							$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_global_clicks'));
							/*$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_global_conversions'));*/
							$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_global_ctr'));
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_global_history_in_country_wise'));
						
							
							
							
							
							//print_r($data['advertiser_list']);
    						if($date_count > 0)
							{
							$col=0;
							$row=10;
						
								foreach($stat_data['stat_list'] as $objStat)
								{
												
								/*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,$objStat['date']);*/
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,isset($objStat['COUNTRY'])?$objStat['COUNTRY']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($objStat['IMP'])?$objStat['IMP']:'0');
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($objStat['CLK'])?$objStat['CLK']:'0');
								/*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($objStat['CON'])?$objStat['CON']."%":"0.00%");*/
								$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($objStat['CTR'])?$objStat['CTR']."%":'0.00%');
								$row++;				
								}
							}
							
							if($row >0)
							{
								
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':E'.$row)->applyFromArray($tabletitleArray);
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								/*$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
								/*$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);*/
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_advertiser_total'));
								/*$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['COUNTRY'])?$total_data['COUNTRY']:'0'));*/
								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
								/*$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CON'])?number_format($total_data['CON'],2,'.',',').'%':'0.00%'));*/
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CTR'])?number_format($total_data['CTR'],2,'.',',').'%':'0.00%'));	
								
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
							
							else
							{
								redirect('admin/statistics_global/view_country_wise');
							}
				
				}//end of export_country_wise

	//Export Excel Publisher Pament History
				function export_pub_payment_history_excel($data ='')
				{
						
							$stat_data=$data['stat_data'];
						
							if(!empty($stat_data['stat_list']))
							{
								//Setting the Statistical  Array
								
								 $search_arr= $this->session->userdata('statistics_search_arr');
								
	
								//Set the Variables
								$pub_count	=	count($data['publisher_list']);
								
								// Create new PHPExcel object
								//echo date('H:i:s') . " Create new PHPExcel object\n";
								$objPHPExcel = new PHPExcel();
								
								$workSheet	=	 $objPHPExcel->getActiveSheet();
								// Set properties of the Excel
								//echo date('H:i:s') . " Set properties\n";
								$objPHPExcel->getProperties()->setCreator("Administrator");
								$objPHPExcel->getProperties()->setLastModifiedBy("Administrator");
								$objPHPExcel->getProperties()->setTitle("".$this->lang->line("label_payment_history_of_publishers_title")."");
								$objPHPExcel->getProperties()->setSubject("");
								
								//Default Style Settings For Excel Sheet
								$workSheet->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
								$workSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
			
								// Set column width
								
								//Set the dimensions for the Table Title Attributes
								$workSheet->getColumnDimension('B')->setAutoSize(true);
								$workSheet->getColumnDimension('C')->setAutoSize(true);
								$workSheet->getColumnDimension('D')->setWidth(20);
								$workSheet->getColumnDimension('E')->setWidth(20);
								$workSheet->getColumnDimension('F')->setWidth(20);
							
								//Merge Cells for Heading
								$workSheet->mergeCells('B3:F4');
								
								//Set the Row dimensions for Each Row 
								$workSheet->getRowDimension(6)->setRowHeight(20);
								
								/* Decalaration of Style in an Array */
								
								$workSheet->duplicateStyleArray(
								array(
								'font' => array(
								'bold'         => false,
								'italic'    	 => false,
								'size'        	 => 20,
								'color'		=>array(
													'rgb' => '333'
													)
								),
								'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
								'wrap'       => true
									)
								),
								'B3:C4'
								);
								
								//Set the Table Title Style
								$tabletitleArray	=array(
																		'font' => array(
																		'bold'         => false,
																		'italic'    	 => false,
																		'size'        	 => 12,
																		'color'		=>array(
																							'rgb' => 'ffffff'
																							)
																		),
																		'fill'=> array(
																						'type' => PHPExcel_Style_Fill::FILL_SOLID,
																						'rotation' => 90,
																						'startcolor' => array(
																							'rgb' => '45588A',
																						)
																					)
																			);
								//$workSheet->getStyle('B9:G9')->applyFromArray($tabletstylearray);
								
								
								$tableDataArray	=		array(
																		'alignment' => array(
																		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'       => true
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
													
							$dateArray	=	array(
															'font'=>array(
																		'bold'=>true
																	),
															'alignment' =>array(
															'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
															)	
															);				
	
							$childborderArray = array(
															'borders' => array(
															'vertical' => array(
															'style' => PHPExcel_Style_Border::BORDER_THIN,
															'color' => array('rgb' => '4E5A7A'),
																				),
															'bottom'=>array(
															'style' => PHPExcel_Style_Border::BORDER_THIN,
															'color' => array('rgb' => '4E5A7A'),
																			)
															)
													);
													
								$alignArray = array(
													'alignment' => array(
																		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
													)
								);
								
											
								//$workSheet->getStyle('A9:F9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
								
								// Add some data
								//echo date('H:i:s') . " Add some data\n";
								$objPHPExcel->setActiveSheetIndex(0);
								
								//Set the Title and Start Date ,End Date Details 
								$workSheet->SetCellValue('B3', ''.$this->lang->line("label_payment_history_of_publishers_title").'');
								
								$workSheet->SetCellValue('B9',"".$this->lang->line('lang_statistics_publisher_site')."");
								$workSheet->SetCellValue('C9',"".$this->lang->line('lang_statistics_publisher_revenue')."");
								$workSheet->SetCellValue('D9',"".$this->lang->line('lang_statistics_publisher_share')."");
								$workSheet->SetCellValue('E9',$this->lang->line('lang_paid_by_admin')."");
								$workSheet->SetCellValue('F9',$this->lang->line('lang_publisher_option_unpaid_earnings')."");
								
								// Rename sheet
								//echo date('H:i:s') . " Rename sheet\n";
								$workSheet->setTitle(''.$this->lang->line("label_payment_history_of_publishers_title").'');
							
								
								//print_r($data['advertiser_list']);
								if($pub_count > 0){
									$col=0;
									$row=10;
									$row_start	=	10;
									$camp_title_style = array();
									$camp_style	=	array();
									
									$stat_list		=	  $stat_data['stat_list'];
									
									$tot_val		=	  $stat_data['tot_val'];
									
									//$campaigns_list	=	$data['campaigns_list'];
									//$reports	=		$data['reports'];
									
									$i=0;
									foreach($data['publisher_list'] as $pub)
									{
												 if(isset($stat_list[$pub->accountid]))
		 										{	
													 $unpaidpub = $stat_list[$pub->accountid]['PUBSHARE']-$stat_list[$pub->accountid]['PAID']; 
		  										}
												// Publisher List	
												$workSheet->getStyle('B'.$row.':F'.$row)->getFont()->setBold(true);
												$workSheet->setCellValueByColumnAndRow(1,$row,view_text($pub->websitename));
												$workSheet->setCellValueByColumnAndRow(2,$row,isset($stat_list[$pub->accountid])?"$".$stat_list[$pub->accountid]['SPEND']:'$0.00');
												$workSheet->setCellValueByColumnAndRow(3,$row,isset($stat_list[$pub->accountid])?"$".$stat_list[$pub->accountid]['PUBSHARE']:'$0.00');
												$workSheet->setCellValueByColumnAndRow(4,$row,isset($stat_list[$pub->accountid])?"$".$stat_list[$pub->accountid]['PAID']:'$0.00');
												$workSheet->setCellValueByColumnAndRow(5,$row,isset($stat_list[$pub->account_id])?"$".$unpaidpub:"$0.00");
												$row++;	
														
												if(array_key_exists($pub->accountid,$stat_list))
												{
													$pub_rev_list =$this->mod_publisher->get_publisher_monthly_revenue($pub->accountid);
													if(count($pub_rev_list)>0)
													{
														$monthly_list  = $pub_rev_list['stat_list'];
													}
													$workSheet->getStyle('B'.$row.':F'.$row)->getFont()->setItalic(true);
													
													$workSheet->SetCellValue('B'.$row,$this->lang->line('label_payment_history_month'));
													$workSheet->SetCellValue('C'.$row,$this->lang->line('label_payment_history_revenue'));
													$workSheet->SetCellValue('D'.$row,$this->lang->line('label_payment_history_share'));
													$workSheet->SetCellValue('E'.$row,$this->lang->line('label_payment_history_Paid'));
													$workSheet->SetCellValue('F'.$row,$this->lang->line('label_payment_history_Unpaid'));
													
														array_push($camp_title_style,$row);
														$row++;
													foreach($monthly_list as $key=>$data){
														//Zones List For Each Publisher
														array_push($camp_style,$row);
														$workSheet->setCellValueByColumnAndRow(1,$row,date("F", mktime(0, 0, 0, $key)).' '.$data['YEAR']);
														$workSheet->setCellValueByColumnAndRow(2,$row,'$'.$data['SPEND']);
														$workSheet->setCellValueByColumnAndRow(3,$row,'$'.$data['PUBSHARE']);
														$workSheet->setCellValueByColumnAndRow(4,$row,'$'.$data['PAID']);
														$workSheet->setCellValueByColumnAndRow(5,$row,'$'.number_format($data['PUBSHARE']-$data['PAID'],2));
														$row++;
												}
											}
										
									}
						$total_record	=	$row-$row_start;
							//Apply the Style Attributes to the Excel Sheet
							for($i=0,$row_data=9;$i<=$total_record;$i++,$row_data++)
								{
										$workSheet->getRowDimension($row_data)->setRowHeight(15);
										//$workSheet->getStyle('B'.$row_data)->getFont()->setBold(false);
										$workSheet->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tableDataArray);
										$workSheet->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
										$workSheet->getStyle('C'.$row_data.':F'.$row_data)->applyFromArray($alignArray);		
										
										
										// To set the Style for Parent and Child Relationships
										switch($row_data)
											{
													//Set the title Row Style
													case '9':
															$workSheet->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tabletitleArray);
															$workSheet->getRowDimension($row_data)->setRowHeight(20);
															break;
													//Set the Campaign Title Style
													case  in_array($row_data,$camp_title_style):
															$workSheet->getStyle('B'.$row_data.':F'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	->getStartColor()->setRGB('6A9DEC');
															$workSheet->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($childborderArray);
															$workSheet->getStyle('C'.$row_data.':F'.$row_data)->applyFromArray($alignArray);
															break;
													//Set the Campaign inner child Style		
													case  in_array($row_data,$camp_style):
															$workSheet->getStyle('B'.$row_data.':F'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C9DAF8');
															$workSheet->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($childborderArray);
															$workSheet->getStyle('C'.$row_data.':F'.$row_data)->applyFromArray($alignArray);
															break;
													//Set the even rows style
													case  $row_data%2 !=0:
															$workSheet->getStyle('B'.$row_data.':F'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
	->getStartColor()->setRGB('EAEAEA');
															break;
											}
									if($i ==$total_record)
										{
												$workSheet->getStyle('B9:F'.$row_data)->applyFromArray($borderArray);
										}
								}
							
	
							
							//exit;
							
							$workSheet->SetCellValue('B'.$row,"Total");
							
							$workSheet->getStyle('B'.$row.':F'.$row)->applyFromArray($tabletitleArray);
							$workSheet->getRowDimension($row)->setRowHeight(20);
							$workSheet->getStyle('B'.$row.':F'.$row)->applyFromArray($tableDataArray);
							$workSheet->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$workSheet->getStyle('C'.$row.':F'.$row)->applyFromArray($alignArray);
							
							$workSheet->setCellValueByColumnAndRow(2,$row,isset($tot_val['SPEND'])?"$".$tot_val['SPEND']:'$0.00');
							$workSheet->setCellValueByColumnAndRow(3,$row,isset($tot_val['PUBSHARE'])?"$".$tot_val['PUBSHARE']:'$0.00');
							$workSheet->setCellValueByColumnAndRow(4,$row,isset($tot_val['PAID'])?"$".number_format($tot_val['PAID'],2):'$0.00');
							$unpaid = $tot_val['PUBSHARE']-$tot_val['PAID']; 
							$workSheet->setCellValueByColumnAndRow(5,$row,"$".$unpaid);

							
							$ad_rep_row	=	$row+3;
							$camp_rep_row	=	$row+5;
							$workSheet->getStyle('E'.$ad_rep_row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('45588A');
							$workSheet->getStyle('F'.$ad_rep_row)->getFont()->setBold(true);
							$workSheet->SetCellValue('F'.$ad_rep_row," - ".$this->lang->line('label_publisher_excel_not'));
							
							$workSheet->getStyle('E'.$camp_rep_row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6A9DEC');
							$workSheet->getStyle('F'.$camp_rep_row)->getFont()->setBold(true);
							$workSheet->SetCellValue('F'.$camp_rep_row," - ".$this->lang->line('label_payment_history_month_wise_revenue'));
							}
		
							
							$filename	=		$this->lang->line('label_payment_history_of_publishers').date('d.m.Y');
							
							/********* TO DOWNLOAD THE EXCEL FILE********/
							header('Content-Type: application/vnd.ms-excel');
							//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
							header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
							header('Cache-Control: max-age=0');
							//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
							$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
							//ob_end_clean();
							$objWriter->save('php://output');
							
						
						}
						else
						{
							redirect('admin/statistics_publisher');
						}
				}
function export_global_hour_wise($data)
				{		
							
							$stat_data=$data['stat_data'];
							
							if(!empty($stat_data['stat_list']))
							{
								$date_count	= count($stat_data['stat_list']);
								$hour_wise=$stat_data['stat_list'];
								$total_data=$stat_data['tot_val'];
								$search=$data['search_global_hour'];
								$sel_date = (date("d-m-Y",strtotime($search['sel_date'])));
								
								$filename=$this->lang->line('lang_statistics_title_global').$sel_date."_".$this->lang->line('lang_statistics_title_global_hour_wise');
								
								// Create new PHPExcel object
							//echo date('H:i:s') . " Create new PHPExcel object\n";
							$objPHPExcel = new PHPExcel();
							$workSheet	=	 $objPHPExcel->getActiveSheet();
							// Set properties
							//echo date('H:i:s') . " Set properties\n";
							$objPHPExcel->getProperties()->setCreator($this->lang->line('lang_statistics_dreamads'));
							$objPHPExcel->getProperties()->setTitle($this->lang->line('lang_statistics_global_history_in_hour'));
							$objPHPExcel->getProperties()->setSubject($this->lang->line('lang_statistics_global_history_in_hour'));
							
							//Set default style
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Tahoma')->setSize(10);
							$objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);						
		
							
							
							// Set column width
							
							$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
							$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
							//$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
						
							//Merge Cells for Heading
							$objPHPExcel->getActiveSheet()->mergeCells('B3:F4');
							$objPHPExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(20);
							$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(20);
							
				
							//Leading heading style
							$objPHPExcel->getActiveSheet()->duplicateStyleArray(
																				array(
																					'font'	=> array(
																					'bold'	=> false,
																					'italic'=> false,
																					'size'	=> 20,
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
																		'bold'		=> false,
																		'italic'	=> false,
																		'size'		=> 14,
																		'color'		=>array('rgb' => 'ffffff')
																			),
														'fill'		=> array(
																		'type'			=> PHPExcel_Style_Fill::FILL_SOLID,
																		'rotation'		=> 90,
																		'startcolor'	=> array('rgb' => '4E5A7A')
																			)
														);
							//Alignment for taable heading							
							$tableDataArray	= array(
													'alignment'	=> array(
																		'horizontal'	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
																		'vertical'		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
																		'wrap'			=> true
																		)				
							
													);
							
							//Style for date					
							$dateArray	= array(
												'font'=>array('bold'=>true),
												'alignment' =>array(
																	'horizontal' =>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
																	)	
												);			

							//Set Row Height
							for($i=0,$row_data=9;$i<=$date_count;$i++,$row_data++)
							{
									$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(15);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getFont()->setBold(false);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tableDataArray);
									$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
									//$objPHPExcel->getActiveSheet()->getStyle('G'.$row_data)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
									
									if($row_data%2 !=0 && $row_data!=9)
									{
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
->getStartColor()->setRGB('EAEAEA');
									}else if($row_data ==9){
										$objPHPExcel->getActiveSheet()->getStyle('B'.$row_data.':F'.$row_data)->applyFromArray($tabletitleArray);
										$objPHPExcel->getActiveSheet()->getRowDimension($row_data)->setRowHeight(20);
									}
									/*else if($i ==$date_count+1)
									{
											$objPHPExcel->getActiveSheet()->getStyle('B'.:'G'.$row_data)->applyFromArray($tabletitleArray);
									}*/
							}
							
														
							
							
							
							//set default heading and location
							$objPHPExcel->setActiveSheetIndex(0);
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B3', $this->lang->line('lang_statistics_global_history_in_hour'));
							$objPHPExcel->getActiveSheet()->SetCellValue('B6',$this->lang->line('lang_statistics_advertiser_date'));
							$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($dateArray);
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('C6', $sel_date);
							
							
							
							$objPHPExcel->getActiveSheet()->SetCellValue('B9',$this->lang->line('lang_statistics_advertiser_hour'));
							$objPHPExcel->getActiveSheet()->SetCellValue('C9',$this->lang->line('lang_statistics_advertiser_impression'));
							$objPHPExcel->getActiveSheet()->SetCellValue('D9',$this->lang->line('lang_statistics_advertiser_clicks'));
							$objPHPExcel->getActiveSheet()->SetCellValue('E9',$this->lang->line('lang_statistics_advertiser_conversions'));
							$objPHPExcel->getActiveSheet()->SetCellValue('F9',$this->lang->line('lang_statistics_advertiser_ctr'));
							//$objPHPExcel->getActiveSheet()->SetCellValue('G9',$this->lang->line('lang_statistics_advertiser_spend'));
							
							// Rename sheet
							//echo date('H:i:s') . " Rename sheet\n";
							$objPHPExcel->getActiveSheet()->setTitle($this->lang->line('lang_statistics_global_history_in_hour'));
						
							
							
							
							
							//print_r($data['advertiser_list']);
    						if($date_count > 0)
							{
							$col=0;
							$row=10;
						
								foreach($hour_wise as $time_key=>$hour)
								{
									list($time) = explode(':',$time_key); 
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,date('H:i',mktime($time,0)).'-'.date('H:i',mktime($time,59)));
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,isset($hour['IMP'])?$hour['IMP']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,isset($hour['CLK'])?$hour['CLK']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,isset($hour['CON'])?$hour['CON']:'0');
									$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,isset($hour['CTR'])?$hour['CTR']."%":"0.00%");
									//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,isset($hour['SPEND'])?"$".$hour['SPEND']:'$0.00');
									$row++;				
								}
							}
							
							if($row >0)
							{
								
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':F'.$row)->applyFromArray($tabletitleArray);
								$objPHPExcel->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
								$objPHPExcel->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								//$objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
								
								$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row,$this->lang->line('lang_statistics_advertiser_total'));
								$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row,(isset($total_data['IMP'])?$total_data['IMP']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row,(isset($total_data['CLK'])?$total_data['CLK']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row,(isset($total_data['CON'])?$total_data['CON']:'0'));
								$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row,(isset($total_data['CTR'])?number_format($total_data['CTR'],2,'.',',').'%':'0.00%'));
								//$objPHPExcel->getActiveSheet()->SetCellValue('G'.$row,(isset($total_data['SPEND'])?'$'.number_format($total_data['SPEND'],2,'.',','):'$0.00'));	
								
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
							
							else
							{
								redirect('admin/statistics_global/view_global_hour_wise');
							}
				
				}//end of global_hour_wise
}
