<?php if (!defined('BASEPATH')) exit('No direct script access allowed');  
/**
 * CSVReader Class
 *
 * $Id: csvreader.php 147 2007-07-09 23:12:45Z Pierre-Jean $
 *
 * Allows to retrieve a CSV file content as a two dimensional array.
 * The first text line shall contains the column names.
 *
 * @author        Pierre-Jean Turpeau
 * @link        http://www.codeigniter.com/wiki/CSVReader
*/
class CSVReader {

	var $fields;        /** columns names retrieved after parsing */
	var $separator = ',';    /** separator used to explode each line */

	/**
	 * Parse a text containing CSV formatted data.
	 *
	 * @access    public
	 * @param    string
	 * @return    array
	 */
	function parse_text($p_Text) {
		$lines = explode("\n", $p_Text);
		return $this->parse_lines($lines);
	}

	/**
	 * Parse a file containing CSV formatted data.
	 *
	 * @access    public
	 * @param    string
	 * @return    array
	 */
	function parse_file($p_Filepath)
	{
		$handle = @fopen($p_Filepath, "r");
		if ($handle) {
			while (!feof($handle)) {
				$lines = file($p_Filepath);
				return $this->parse_lines($lines);
				//return $lines;
			}
			fclose($handle);
		}


		//$lines = file($p_Filepath);

	}
	/**
	 * Parse an array of text lines containing CSV formatted data.
	 *
	 * @access    public
	 * @param    array
	 * @return    array
	 */
	function parse_lines($p_CSVLines) {

		$content = FALSE;
		foreach( $p_CSVLines as $line_num => $line ) {
			if( $line != '' ) { // skip empty lines
					
				$elements = explode($this->separator, $line);

				if( !is_array($content) ) { // the first line contains fields names
					$this->fields = $elements;
					$content = array();
				} else {
					$item = array();
					foreach( $this->fields as $id => $field ) {

						if( isset($elements[$id]) ) {

							$field = str_replace('"','',$field);
							$elemen = str_replace('"','',$elements[$id]);
							$item[trim($field)] = $elemen;
						}
					}
					$content[] = $item;
				}
			}
		}
		return $content;
	}

	function parse_csv($p_Filepath)
	{
		$row = 1;
		$count=-1;
		$result=array();
		if (($handle = fopen($p_Filepath, "r")) !== FALSE)
		{
			while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
			{
				if($count==-1)
				{

					$count=0;

					$num = count($data);
					$head=array();
					for ($c=0; $c < $num; $c++)
					{
						$head[$c]=$data[$c];
					}

				}
				else
				{
					$temp=array();
					$num = count($data);
					for ($c=0; $c < $num; $c++)
					{
						$temp[$head[$c]]	=$data[$c];
					}
					$result[$count]	=$temp;
					$count=$count+1;

				}
				
			}
			
			return $result;
			fclose($handle);
		}



	}
}
