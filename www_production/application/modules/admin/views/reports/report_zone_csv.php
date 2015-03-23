<?php
require_once MAX_PATH .'/init.php';
require_once MAX_PATH . '/lib/pear/Spreadsheet/Excel/Writer.php';
require_once MAX_PATH . '/lib/pear/Spreadsheet/Excel/Writer/Workbook.php';
$workbook = new Spreadsheet_Excel_Writer();



if($filter=="")
{
	
	echo $name="Zone-Report Based date on ".$start."--".$end;
	}
	
	exit;
  	/*$workbook->send($name.'.xls');

    $worksheet =& $workbook->addWorksheet($AdvertiserDetails->AdvertiserName.'Report');
    $format6 =& $workbook->addFormat();
    $format6->setBold(); 
    $format6->setSize(10); 	
    //$format6->setPattern(1);
    $format5 =& $workbook->addFormat();
    $format5->setAlign('right');
	$format4 =& $workbook->addFormat();
    $format4 ->setBold();
	$format4 ->setSize(12);
	
	$worksheet->write(0, 0, '',$format4);
	 	 $worksheet->write(0, 1, '',$format4);
		 	 $worksheet->write(0, 2, '',$format4);	 $worksheet->write(0, 3, 'Zone-Report Based on date',$format4);
    $worksheet->write(2, 3, 'Zone Name',$format6);
	$worksheet->write(2, 4, $zonename,$format6);
     $worksheet->write(3, 3, 'Start Date',$format6);
	$worksheet->write(3, 4, $startval,$format6);
    $worksheet->write(4, 3, 'End Date',$format6);
	$worksheet->write(4, 4, $endval,$format6);
	
	$worksheet->write(6, 3, 'Date & Time',$format6);
	$worksheet->write(6, 4, 'Impressions',$format6);
    $worksheet->write(6, 5, 'Clicks',$format6);
    $worksheet->write(6, 6, 'Conversions',$format6);
	$worksheet->write(6, 7, 'CTR(%)',$format6);
	$worksheet->write(6, 8, 'Revenue',$format6);*/
	

/*
foreach($adhourly as $adhourl):
echo $a=$adhourl->impressions;
//echo $ad_imp=($adhourl->sum(h.impressions)!="")?$adhourl->sum(h.impressions):'0';

//echo $ad_cli=($adhourl->sum(h.clicks)!="")?$adhourl->sum(h.clicks):'0';
//
//echo $ad_con=($adhourl->sum(h.conversions)="")?$adhourl->sum(h.conversions):'0';
endforeach;}
exit;*/?>
