<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	
            $file = './application/config/test.php';
            $flag = 0;
            $this->update_file($file,$flag);
        }

       private function update_file ($file,$flag)
       {
            if (file_exists($file))
            {

                require_once ($file);
        
                $contents = file_get_contents($file);
                //echo $contents;
               // exit;
                //$updated_array = array_merge($$var_name, $var);
                $search = "Welcome";
                $replace = "Hi";
                $new_file = './application/config/testing.php';
                
                /* For preg_replace */
                //foreach($$var_name as $key => $val) {
                //$search = '/\$'.$a.'\[\\\''.$key.'\\\'\]\s+=\s+[^\;]+/';
                //$replace_string = "\$".$var_name."['".$key."'] = ".var_export($updated_array[$key], true);
                //array_push($search, $pattern);
                //array_push($replace, $replace_string);
                //  }
                //$new_contents = preg_replace($search, $replace, $contents);
                $new_contents = str_replace($search, $replace, $contents);
                if($flag!=1)
                {
                    write_file($file, $new_contents);
                    echo "File Updated Successfully";
                }
                else
                {
                   write_file($new_file, $new_contents);
                   echo "File created successfully";
                }
             }
      }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
