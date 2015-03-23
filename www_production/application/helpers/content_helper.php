<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('form_text'))
{	
	function form_text($text){
		
		//$stringOut = str_replace("<br/>", "\r\n", $text);
		$stringOut = str_replace("\\n", "<br/>", $text);
		$stringOut = str_replace("<br/>", "\r\n", $stringOut);
		
		return htmlspecialchars(stripslashes(trim($stringOut)));
	}
}

if ( ! function_exists('view_text'))
{
	function view_text($text){
		
		
		$stringOut = str_replace("\\r\\n", "<br/>", $text);
		$stringOut = str_replace("\\n", "<br/>", $stringOut);
		return stripslashes($stringOut);
	}
}

if ( ! function_exists('text_db')) 
{
	function text_db($text){  
		return mysql_real_escape_string(trim($text));
	}
}
