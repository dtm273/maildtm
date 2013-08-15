<?php
$CI =& get_instance(); 
$CI->load->helper('sendmail_helper');
if ( ! function_exists('write_log')){

     function write_log($type = NULL, $message) {

        $log_path = LOG_PATH;

        if(DISPLAY_LOG_ENABLED){
            // create log path if not exist
            if (!is_dir($log_path))
                mkdir($log_path);
            
            // create log file 
            $log_file = $log_path . "_" . date("Y-m-d");
            $time = date('Y-m-d H:i:s');
            if(!$type) $type = "LOG_INFO";
            $type = strtoupper($type);
            
            // write log message to file
            error_log("[$time][$type]$message \n", 3, $log_file);
            
            // send mail error 
            if(SEND_MAIL_ERROR_ENABLED && ( preg_match('/ERROR/', $type) || preg_match('/EXCEPTION/', $type))){
                alotaxi_send('ALOTAXI ERROR', $message);
            }
        }
    }
}
